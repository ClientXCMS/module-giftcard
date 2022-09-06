<?php

namespace App\Giftcard\Actions;

use App\Auth\Database\UserTable;
use App\Auth\DatabaseUserAuth;
use App\Giftcard\Database\GiftTable;
use App\Giftcard\GiftcardModule;
use ClientX\Database\NoRecordException;
use ClientX\Helpers\Currency;
use ClientX\Session\FlashService;
use ClientX\Session\SessionInterface;
use ClientX\Translator\Translater;
use Psr\Http\Message\ServerRequestInterface;

class GiftAddCard extends \ClientX\Actions\Action
{
    /**
     * @var \App\Giftcard\Database\GiftTable
     */
    private GiftTable $table;
    private UserTable $userTable;
    private SessionInterface $session;
    private string $currency;

    public function __construct(GiftTable $table, UserTable $userTable, string $currency,DatabaseUserAuth $auth, SessionInterface $session, Translater $translater)
    {
        $this->table = $table;
        $this->userTable = $userTable;
        $this->auth = $auth;
        $this->session = $session;
        $this->translater = $translater;
        $this->currency = $currency;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $error = null;
        $amount = 0;
        $success = false;
        if ($request->getMethod() === 'POST') {
            $code = $request->getParsedBody()['code'];
            try {
                /** @var \App\Giftcard\Giftcard $gift */
                $gift = $this->table->findBy("code", $code);
                if ($gift->canUse($this->getUserId())) {
                    $amount = $gift->getAmount();
                    /** @var \App\Account\User $user */
                    $user = $this->getUser();
                    $user->addFund($amount);
                    $this->userTable->updateWallet($user);
                    $gift->use($this->getUserId());
                    $this->table->updateGift($gift);
                    $success = true;
                    (new FlashService($this->session))->info($this->trans("giftcard.success", ["%amount%" => $amount . " " . Currency::symbolFor($this->currency)]));
                } else {
                    $error = "giftcard.errors.cannotuse";
                }
            } catch (NoRecordException $e) {
                $error = "giftcard.errors.notfound";
            }
        }
        if (!$success) {
            (new FlashService($this->session))->warning($this->trans($error));
        } else {
            (new FlashService($this->session))->info($this->trans("giftcard.success", ["%amount%" => $amount . " " . Currency::symbolFor($this->currency)]));

        }
        return $this->back($request);
    }
}
