<?php

use function DI\add;
use function DI\get;

return [
    'admin.menu.items' => add(get(\App\Giftcard\GiftcardAdminItem::class)),
    \App\Giftcard\Actions\GiftAddCard::class => \DI\autowire()->constructorParameter('currency', get('app.currency')),
];