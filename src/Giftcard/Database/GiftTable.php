<?php

namespace App\Giftcard\Database;

use App\Giftcard\Giftcard;
use ClientX\Database\Table;

class GiftTable extends Table
{
    protected $table = "giftcards";
    protected $entity = Giftcard::class;

    public function updateGift(Giftcard $gift)
    {
        parent::update($gift->id, [
            'usages' => json_encode($gift->usages)
        ]);
    }

    public function insert(array $params): int
    {

        if ($params['user_id'] == 0){
            $params['user_id'] = null;
            $params['maxusages'] = (int) $params['maxusages'];

        } else {
            $params['maxusages'] = 1;
        }
        if (empty($params['expire_at'])){
            $params['expire_at'] = null;
        }
        if ($params['type'] == 'fixed'){
            $params['amount'] = (float) $params['amount'];
            $params['min_range'] = null;
            $params['max_range'] = null;
        } else {

            $params['amount'] = null;
            $params['min_range'] =  (float) $params['min_range'];
            $params['max_range'] = (float) $params['max_range'];
        }
        $params['usages'] = "[]";
        return parent::insert($params);
    }

    public function update($condition, $params, $where = 'id'): bool
    {
        if ($params['user_id'] == 0){
            $params['user_id'] = null;
            $params['maxusages'] = (int) $params['maxusages'];

        } else {
            $params['maxusages'] = 1;
        }
        if (empty($params['expire_at'])){
            $params['expire_at'] = null;
        }
        if ($params['type'] == 'fixed'){
            $params['amount'] = (float) $params['amount'];
            $params['min_range'] = null;
            $params['max_range'] = null;
        } else {

            $params['amount'] = null;
            $params['min_range'] =  (float) $params['min_range'];
            $params['max_range'] = (float) $params['max_range'];
        }
        return parent::update($condition, $params, $where);
    }
}