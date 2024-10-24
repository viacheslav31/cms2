<?php


namespace core;


class Model
{

    public static $tableName;
    public static $fillable;

    private static $db;
//    public function __construct($tableName)
//    {
//        self::$tableName = $tableName;
//    }

    public static function app()
    {
        self::$db =  Core::getInstance();
        return self::$db;
        //return Core::getInstance();
    }

    public static function db()
    {
        return Core::getInstance()->db;
    }

    public static function add($insertArray,$tableName,$fillable=null)
    {
        //        if (count(self::$fillable) > 0) {
        //            $insertArray = self::$fillable;
        //        }
        //dd($insertArray,$tableName,$fillable);
        if (!empty($fillable)) {
            $insertArray = Utils::filterArray($insertArray, $fillable);
        }
        Core::getInstance()->db->insert(
            $tableName,
            $insertArray
        );
        //$user = ['login' => $login, 'password' => $pass,];
    }

    public static function edit($id,$updateArray,$tableName,$fillable=null)
    {
        if (!empty($fillable)) {
            $updateArray = Utils::filterArray($updateArray, $fillable);
        }

        //dd($updateArray);
        Core::getInstance()->db->update(
            $tableName,
            $updateArray,
            [
                'id' => $id
            ],
        );
        //$user = ['login' => $login, 'password' => $pass,];
    }

    public static function update($updateArray,$id,$tableName,$fillable=null)
    {
        //locked sometimes fields
        //$updatesArray = Utils::filterArray($updatesArray, ['lastname','firstname']);

        if (!empty($fillable)) {
            $updateArray = Utils::filterArray($updateArray, $fillable);
        }

        Core::getInstance()->db->update(
            $tableName,
            $updateArray,
            [
                'id' => $id
            ]);
        //Core::getInstance()->db->insert(self::$tableName, ['login' => $login, 'password' => $pass,];);
    }

    public static function getById($id,$tableName)
    {

        $rows = Core::getInstance()->db->select($tableName,null,[
            'id' => $id
        ]);

        if (!empty($rows)) {
            return $rows[0];
        } else {
            return null;
        }
    }

    public static function delete($id,$tableName)
    {

        //$tableName = self::$tableName;
        return Core::getInstance()->db->delete($tableName,[
            'id' => $id
        ]);
    }

    public static function getList($tableName)
    {
        return Core::getInstance()->db->select($tableName);
    }

    public static function schema($table)
    {
        $query = "SHOW COLUMNS FROM $table";
        return self::$db->query($query);
    }

    public static function select($tableName,$fieldsList = null,$conditionArray = null, $orderString = null,$start = null,$perPage = null)
    {
        // var 1
        return RB::selectTable($tableName,$fieldsList,$conditionArray,$orderString,$start,$perPage);

        //return Core::getInstance()->db->select($tableName,$fieldsList,$conditionArray,$orderString,$start,$perPage);
        //var 2
        /*
        // CRUDMODEL
        //$db = Core::getInstance()->db;
        $crud = new CrudModel(self::$db);

        //test
        //if (!is_null($parentId)) {
        //    $where = [$this->parentKey => $parentId];
        //} else {
        $where = null;//['u_status =' => 'Active'];
        //}

        $schema = self::schema($tableName);
        //test
        $crud->getItems($tableName, $where = null, $request, $schema, $fields, $order, $offset, $per_page);
        */
    }

    public static function count($tableName,$conditionArray = null)
    {

        return Core::getInstance()->db->count($tableName,$conditionArray);
    }

}
