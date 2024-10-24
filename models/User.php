<?php

namespace models;

use core\Core;
use core\Utils;

class User
{
    private static $tableName = 'users';
    public static function addUser($data) {

        //$data = ['login' => 'login', 'password' => 'pass','name' => 'name1',];
        //set: DB $db in Core
        Core::getInstance()->db->insert(self::$tableName,$data);

    }

    public function updateUser($id,$updatesArray)
    {
        //fillable fields
        $updatesArray = Utils::filterArray($updatesArray,['name','phone']);
        Core::getInstance()->db->update(self::$tableName,$updatesArray,[
            'id' => $id
        ]);

    }
}
