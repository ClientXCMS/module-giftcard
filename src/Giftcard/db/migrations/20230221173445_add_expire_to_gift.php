<?php

use Phinx\Migration\AbstractMigration;

class AddExpireToGift extends AbstractMigration
{
    public function change()
    {
        $this->table('giftcards')
            ->addColumn('expire_at', 'datetime', ['null' => true])
            ->save();
    }
}
