<?php

namespace controllers;

use core\Controller;
use core\Core;
use core\Template;
use models\User;

class MainController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction()
    {
        //User::addUser();
            //Return html-code, not view
        //echo "view: " .$this->viewPath;

        // v1
        // mag const: current class name
        // $moduleName = strtolower(substr(__CLASS__,strlen("controllers\\"),
        //          strlen(__CLASS__)-strlen("controllers\\") - strlen("Controller")));
        //get singleton app class
        //$moduleName = Core::getInstance()->app['moduleName'];

        // v2
        //$app = Core::getInstance()->app;
        //$moduleName = $app['moduleName'];
        //$actionName = $app['actionName'];
        //$path = "views/{$moduleName}/{$actionName}.php";
        //include($path);
        // run with router: http://cms/news
        // echo 'Main page';
        // v3

        //include($this->viewPath);
        // ### TODO set CLASS  API (index,insert,edit,delete,get)

        //in parent->render()
        // $tpl = new Template($this->viewPath);
        // return $tpl->getHTML();

//        $layout = null;//'json';
//        $userViewPath = "views/main/user_view.php";
//        return $this->render($layout,$userViewPath);

//        params: ([ $title => 'main page'],null,null)
        return $this->render([
            'title' => 'News list',
        ]);

    }

    public function viewAction()
    {
        return $this->render();
    }

    public function error($code)
    {
        switch ($code) {
            case 404: echo 'Error 404. Page not found'; break;
        }
    }

    public function jsonAction() {
        //$data = fetchAll();
        //in view
        // header('Content-Type');
        $data = [
            'cars' => [
                'bmw' => 10000,
                'audi' => 20000,
            ]
        ];
        print(json_encode($data));
        //echo json_encode($data);
    }
}
