<?php

namespace App\Giftcard;

use App\Fund\FundModule;
use App\Giftcard\Actions\GiftAddCard;
use App\Giftcard\Actions\GiftcardCrudAction;
use ClientX\Helpers\Str;
use ClientX\ModuleCache;
use ClientX\Renderer\RendererInterface;
use ClientX\Router;
use ClientX\Session\FlashService;
use ClientX\Session\SessionInterface;
use ClientX\Theme\ThemeInterface;
use Psr\Container\ContainerInterface;
use function ClientX\request;

class GiftcardModule extends \ClientX\Module
{
    const GIFTCARD_SESSION = "_giftcard_session";
    const DEFINITIONS =  __DIR__ . '/config.php';
    const NAME = "basket";

    const TRANSLATIONS = [
        "fr_FR" => __DIR__ . '/trans/fr.php',
        "en_GB" => __DIR__ . '/trans/en.php',
    ];

    public function __construct(RendererInterface $renderer, ThemeInterface $theme, Router $router, ContainerInterface $container)
    {
        $renderer->addPath('giftcard', $theme->getViewsPath() . '/Giftcard');
        $renderer->addPath('giftcard_admin', __DIR__ .'/Views');
        $prefix = $container->get('clientarea.prefix');
        $router->post($prefix . '/giftcard', GiftAddCard::class, 'giftcard.submit');
        if ($container->has('admin.prefix')){
            $prefix = $container->get('admin.prefix');
            $router->crud($prefix . '/giftcard', GiftcardCrudAction::class, 'giftcard.admin');
        }
        $modules = (new ModuleCache())->getModulesEnabled();
        if (!in_array(FundModule::class, $modules)) {
            $session = $container->get(SessionInterface::class);
            if (Str::startsWith(request()->getUri()->getPath(), '/admin')) {
                (new FlashService($session))->error('The Gift card Module require the Fund Module to work');
            }
        }
    }
}