<?php

namespace App\Giftcard;

use ClientX\Navigation\NavigationItemInterface;
use ClientX\Renderer\RendererInterface;

class GiftcardAdminItem implements NavigationItemInterface
{

    public function getPosition(): int
    {
        return 30;
    }

    public function render(RendererInterface $renderer): string
    {
        return $renderer->render('@giftcard_admin/menu');
    }
}