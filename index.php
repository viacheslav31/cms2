<?php

//include('core/Core.php');//linux
use core\DB;

include('config/database.php');

spl_autoload_register(function ($className) {
    $path = $className.'.php';
    if (is_file($path)) {
        require($path);
    } //include
});

//Test DB
//$db = new DB(DATABASE_HOST,DATABASE_LOGIN,DATABASE_PASSWORD,DATABASE_BASENAME);
//$db->select('users',null,[
//    'login' => ['value'=>'user','compare'=>'LIKE',$type=>'date'],
//    'name' => ['value'=>'ggg','compare'=>'LIKE']
//],'AND');

$core = core\Core::getInstance();
$core->Initialize();
$core->Run();
$core->Done();
//$core = Core::getInstance();

