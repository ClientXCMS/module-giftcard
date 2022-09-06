<?php

namespace App\Giftcard\Actions;

use App\Auth\Database\UserTable;
use App\Giftcard\Database\GiftTable;
use ClientX\Helpers\Str;
use ClientX\Renderer\RendererInterface;
use ClientX\Router;
use ClientX\Session\FlashService;
use ClientX\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;

class GiftcardCrudAction extends \ClientX\Actions\CrudAction
{

    protected $viewPath = "@giftcard_admin/crud";
    /**
     * @var string
     */
    protected $routePrefix = 'giftcard.admin';

    /**
     * @var string
     */
    protected $moduleName = "Gift card";

    protected $fillable = [
        'code', 'min_range', 'max_range', 'type', 'maxusages', 'user_id', 'amount'
    ];
    private UserTable $userTable;
    public function __construct(RendererInterface $renderer, GiftTable $table, Router $router, FlashService $flash, UserTable $userTable)
    {
        parent::__construct($renderer, $table, $router, $flash);
        $this->userTable = $userTable;
    }

    public function formParams(array $params): array
    {
        $params['types'] = [
            'fixed' => 'Fixed amount',
            'random' => 'Random amount between',
        ];
        if ($params['item']->userId) {
            $user = $this->userTable->find($params['item']->userId);
            $params['user'] = [$user->getId() => $user->getName()];
        } else {
            $params['user'] = [0 => 'Available to all users'];
        }
        if ($this->mode === 'edit' && !empty($params['item']->usages)) {
            $params['usernames'] = collect($this->userTable->findIn($params['item']->usages))->map(function ($user) {
                return $user->getEmail();
            })->join(',');
        } else {
            $params['usernames'] = '';
        }
        $params['defaultcode'] = Str::random(16);
        return parent::formParams($params);
    }

    public function getValidator(Request $request): Validator
    {
        return parent::getValidator($request)
            ->unique('code', $this->table, null, $request->getAttribute('id'))
            ->notEmpty('code')
            ->length('code', 2, 200);
    }
}
