<?php

use Phinx\Migration\AbstractMigration;

class CreateGiftcardsTable extends AbstractMigration
{
    public function change()
    {
        $this->table("giftcards")
            ->addColumn("user_id", "integer", ['null' => true])
            ->addColumn("code", "string")
            ->addIndex("code", ["unique" => true])

            ->addColumn("type", "enum", ["values" => ['random', 'fixed']])
            ->addColumn("amount", "float", ['null' => true])
            ->addColumn("min_range", "float", ['null' => true])
            ->addColumn("max_range", "float", ['null' => true])
            ->addColumn("usages", "json")
            ->addColumn("maxusages", "integer", ['null' => 100])
            ->addTimestamps()
            ->create();
    }
}
