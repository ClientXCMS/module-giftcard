<?php

namespace App\Giftcard;

class GiftAddEvent extends \ClientX\Event\Event
{

    public $name = "giftcard.add";

    public function __construct(Giftcard $giftcard)
    {
        $this->setTarget($giftcard);
    }

    public function getTarget():Giftcard
    {
        return parent::getTarget();
    }
}