<?php

use function DI\add;
use function DI\get;

return [
    'admin.menu.items' => add(get(\App\Giftcard\GiftcardAdminItem::class)),
    
    'permissions.list' => add([\App\Giftcard\Actions\GiftcardCrudAction::class => 'Giftcard']),
    \App\Giftcard\Actions\GiftAddCard::class => \DI\autowire()->constructorParameter('currency', get('app.currency')),
];
