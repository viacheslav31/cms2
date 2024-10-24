<?php


namespace core;


/**
 * Class DB
 * @package core
 * ### Query to DB
 */

class DB
{
    protected $pdo;

    public function __construct($hostname,$login,$password,$database)
    {
        $this->pdo = new \PDO("mysql: host={$hostname};dbname={$database}",$login,$password);
    }

    /**
     *
     * @param $sql
     * @return array
     * query to DB
     */
    public function query($sql)
    {
        $res = $this->pdo->query($sql);
        return $res->fetchALl(\PDO::FETCH_ASSOC);
    }


//    public function select($tableName,
//                            $fieldList = "*",
//                            $conditionArray = null
//    )
//    {
        //$HACK = "' OR TRUE#";
        //$res = $this->pdo->query("SELECT * FROM users");
        //get assoc array + array (first rec)
//        $row = $res->fetch(\PDO::FETCH_ASSOC);// 1 record
//        while( $row = $res->fetch(\PDO::FETCH_ASSOC)) {
//            echo '<pre>';var_dump($row);
//        }
//        $rows = $res->fetchAll();// 1 record
//        echo '<pre>';var_dump($rows);
//підготовлені запити

//        $res = $this->pdo->prepare("SELECT * FROM users WHERE login = :login");
//        $res->execute([
//            //':fiedListString' => '*',
//            ':login' => 'admin'
//        ]);
//        $row = $res->fetch(\PDO::FETCH_ASSOC);
//        echo '<pre>';var_dump($row);
//    }


    /**
     * Execute query from $tableName
     * @param string $tableName name table to DB
     * @param null|string $fieldsList
     * @param null|array $conditionArray
     * @param string $conditionCompare
     * @param null $orderString
     * @param null $start
     * @param null $perPage
     * @return array
     */
    public function select(string $tableName,
        $fieldsList = null,
        $conditionArray = null,
        $conditionCompare = 'AND',
        $orderString = null,
        $start = null,
        $perPage = null)
    {

        // ### ser redbean
        //return R::findAll($tableName);
        //logger($conditionArray);
        //logger($orderString);
        //Params: $fieldsList:
        //$db->select("users",['login','password']);
        //$db->select("users","login as l,password as p");

        //$res = $this->pdo->query('SELECT * FROM users');
        // $login = "admin";//$login = "' OR TRUE#";
        // $password = "admin";
        //$sql = "SELECT {$fieldsList} FROM {$tableName} WHERE login='{$login}' AND login='{$password}'";
        //$sql = "SELECT {$fieldsList} FROM {$tableName} WHERE login='' OR TRUE#' AND login='{$password}'";
        //$sql = "SELECT {$fieldsList} FROM {$tableName} WHERE login='{$login}' AND login='{$password}'";
        //$res = $this->pdo->query($sql);
        //$row = $res->fetch(\PDO::FETCH_ASSOC);
        //$row = $res->fetchALl(\PDO::FETCH_ASSOC);
        //$sql = "SELECT :fieldsListString FROM :tableName WHERE login=:login AND password=:password";

        if (empty($fieldsList)) {
            $fieldsListString = "*";
        } else if (is_string($fieldsList)) {
            $fieldsListString = $fieldsList;
        } else if (is_array($fieldsList)) {
            $fieldsListString = implode(', ', $fieldsList);
        }

        $executeArray = [];
        $wherePartString = "";
        if (is_array($conditionArray)) {
            $parts = [];

            //['key'] => ['value'=>1,'type'=>'text','compare'=>'Like']
            foreach ($conditionArray as $key => $value) {
                // ### LIKE OR =
                if ($value['compare'] === 'LIKE')
                    $parts [] = "{$key} LIKE CONCAT('%', :{$key}, '%')";
                else
                    $parts [] = "{$key} = :{$key}";

                $executeArray[$key] = $value['value'];

                //$parts [] = "{$key} = :{'%'.$key.'%'}";
            }

            $wherePartString = "WHERE ".implode(" {$conditionCompare} ",$parts);
        }
        //var_dump($wherePartString);
        //logger($wherePartString);

        $orderByPartString = "";
        if (!empty($orderString)) {
            $orderByPartString = "ORDER BY ".$orderString;
        }

//pagination
        $limitByPartString = "";
        if(!empty($start) && !empty($perPage)) {
            $limitByPartString = "LIMIT ".$start.",".$perPage;
        } elseif(empty($start) && !empty($perPage)) {
            $limitByPartString = "LIMIT ".$perPage;
        }

        $sql = "SELECT {$fieldsListString} FROM {$tableName} {$wherePartString} {$orderByPartString} {$limitByPartString}";

        //logger($executeArray);
        // sql injection
        $res = $this->pdo->prepare($sql); //not sql injection
        $res->execute(
            $executeArray
        //$conditionArray //['login' => $login, 'password' => $password,]
        );
        $data = $res->fetchALl(\PDO::FETCH_ASSOC);
        echo '<pre>';var_dump($data);
        return $data;
        /*
        $allRow = $res->fetchALl(\PDO::FETCH_ASSOC);dd($allRow);
        while($row = $res->fetch(\PDO::FETCH_ASSOC)) {var_dump($row);}
        */
    }

//SELECT customers.id as customers_id, customers.name as customers_name,users.id as users_id,users.login as users_name FROM customers
//LEFT JOIN users
//ON customers.manager_id = users.id;

    public function selectRelation(string $tableName, $fieldsList = null, $conditionArray = null, $orderString = null, $start = null,$perPage = null)
    {

//    if (empty($fieldsList)) {
//            $fieldsListString = "*";
//        } else if (is_string($fieldsList)) {
//            $fieldsListString = $fieldsList;
//        } else if (is_array($fieldsList)) {
//            $fieldsListString = implode(', ', $fieldsList);
//        }
//
//        $executeArray = [];
//        $wherePartString = "";
//        if (is_array($conditionArray)) {
//            $parts = [];
//
//            //['key'] => ['value'=>1,'type'=>'text','compare'=>'Like']
//            foreach ($conditionArray as $key => $value) {
//                // ### LIKE OR =
//                if ($value['compare'] === 'LIKE')
//                    $parts [] = "{$key} LIKE CONCAT('%', :{$key}, '%')";
//                else
//                    $parts [] = "{$key} = :{$key}";
//
//                $executeArray[$key] = $value['value'];
//
//                //$parts [] = "{$key} = :{'%'.$key.'%'}";
//            }
//
//            $wherePartString = "WHERE ".implode(' AND ',$parts);
//        }
//
//        //logger($wherePartString);
//        $orderByPartString = "";
//        if (!empty($orderString)) {
//            $orderByPartString = "ORDER BY ".$orderString;
//        }
//
////pagination
//        $limitByPartString = "";
//        if(!empty($start) && !empty($perPage)) {
//            $limitByPartString = "LIMIT ".$start.",".$perPage;
//        } elseif(empty($start) && !empty($perPage)) {
//            $limitByPartString = "LIMIT ".$perPage;
//        }
//
//        $sql = "SELECT {$fieldsListString} FROM {$tableName} {$wherePartString} {$orderByPartString} {$limitByPartString}";
//
//        $res = $this->pdo->prepare($sql); //not sql injection
//        $res->execute(
//            $executeArray
//
//        );
//        return $res->fetchALl(\PDO::FETCH_ASSOC); //

    }


    public function count(string $tableName, $conditionArray = null)
    {
        $executeArray = [];
        $wherePartString = "";
        if (is_array($conditionArray)) {
            $parts = [];
            //['key'] => ['value'=>1,'type'=>'text','compare'=>'Like']
            foreach ($conditionArray as $key => $value) {
                // ### LIKE OR =
                if ($value['compare'] === 'LIKE')
                    $parts [] = "{$key} LIKE CONCAT('%', :{$key}, '%')";
                else
                    $parts [] = "{$key} = :{$key}";
                $executeArray[$key] = $value['value'];
            }
            $wherePartString = "WHERE ".implode(' AND ',$parts);
        }

        $sql = "SELECT count(*) FROM {$tableName} {$wherePartString}";

        $res = $this->pdo->prepare($sql);
        $res->execute(
            $executeArray
        );
        return $res->fetchColumn(); //$res->fetchALl(\PDO::FETCH_ASSOC);
    }

    //////////////////////////////////////////////////////////

    public function insert($tableName,$newRowArray)
    {
        // INSERT INTO {$tableName} (field1,field2,field3) VALUES (:f1,:f2,:f3)
        //all keys assoc array
        $fieldsArray = array_keys($newRowArray);

        $fieldsListString = implode(', ',$fieldsArray);
        //all values assoc array
        //$fieldsListString = array_values($newRowArray);
        $paramsArray = [];

        foreach($newRowArray as $key => $value) {
            $paramsArray [] = ':'.$key;
        }
        $valuesListString = implode(', ', $paramsArray);

        $sql = "INSERT INTO {$tableName} ($fieldsListString) VALUES ($valuesListString)";
        $res = $this->pdo->prepare($sql);

        return $res->execute($newRowArray); //$newRowArray
    }

    public function update($tableName,$newValuesArray, $conditionArray)
    {
        // UPDATE  {$tableName} SET field1 = :field1, field2 = :field2 WHERE field3 = :field3 AND field4 = :field4

        $setParts = [];
        $paramsArray = [];
        foreach ($newValuesArray as $key => $value) {
            $setParts [] = "{$key} = :set_{$key}";
            $paramsArray['set_'.$key] = $value;
        }
        //array to str
        $setPartString = implode(', ',$setParts);

        //$wherePartString = "";
        //if (is_array($conditionArray)) {
        $wherePart = [];
        foreach ($conditionArray as $key => $value) {
            $wherePart [] = "{$key} = :{$key}";
            $paramsArray[$key] = $value;
        }
        $wherePartString = "WHERE ".implode(' AND ',$wherePart);
        //}

        //dd("UPDATE {$tableName} SET {$setPartString} {$wherePartString}");
        $sql = "UPDATE {$tableName} SET {$setPartString} {$wherePartString}";
        $res = $this->pdo->prepare($sql);

        $res->execute($paramsArray);
    }

    public function delete($tableName, $conditionArray)
    {
        // DELETE FROM {$tableName} WHERE field1 = :f1 AND ...
        $wherePart = [];
        $paramsArray = [];
        foreach ($conditionArray as $key => $value) {
            $wherePart [] = "{$key} = :{$key}";
            $paramsArray[$key] = $value;
        }
        //array to str
        $wherePartString = "WHERE ".implode(' AND ',$wherePart);
        $sql = "DELETE FROM {$tableName} {$wherePartString}";
        $res = $this->pdo->prepare($sql);
        return $res->execute($conditionArray);
    }

}
