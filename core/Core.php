<?php

namespace core;

use controllers\MainController;

class Core
{
    ///
    ///
    /// new class core
    public DB $db;
    private static $instance = null;
    public $app; //container

    private function __construct()
    {
        $this->app = [];
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function Initialize()
    {
        //DB, session, ...
        $this->db = new DB(DATABASE_HOST,DATABASE_LOGIN,DATABASE_PASSWORD,DATABASE_BASENAME);

    }
    public function Run()
    {
        //router, run controller methods
        $route = $_GET['route'] ?? null;
        if (!empty($route)) {
            //var_dump($route); die;
            $routeParts = explode('/',$route);

            //get first elem and delete first
            $moduleName = strtolower(array_shift($routeParts));
            if(empty($moduleName)) $moduleName = 'main';

            //get next elem
            $actionName = strtolower(array_shift($routeParts));
            if(empty($actionName)) $actionName = 'index';

            //container (current module and action)
            $this->app['moduleName'] = $moduleName;
            $this->app['actionName'] = $actionName;

            //var_dump($this->app);
            $controllerName = '\\controllers\\'.ucfirst($moduleName).'Controller';
            $controllerActionName = $actionName.'Action';
            //var_dump($controllerName,$controllerActionName);
            //run controller method

            // RUN CONTROLLER
            $statusCode = 212;
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller,$controllerActionName)) {
                    $this->app['actionResult'] = $controller->$controllerActionName();
                    $this->app['layout'] = $controller->layout;
                    //echo $html;
                } else {
                    $statusCode = 404;
                }
            } else {
                $statusCode = 404;
            }

            // ERRORS
            $statusCodeType = intval($statusCode/100);
            if ($statusCodeType == 4 OR $statusCodeType == 5) {
                $mainController = new MainController();
                $mainController->error(404);
            }
        }
        // ERRORS
    }
    public function Done()
    {
        //var_dump('Done');
        // THEME
        // view pages
        //show html in browser from ob_buffer

        // ### CMS template (set template-file in controller)
        //if(isset($this->app['layout'])) {
            $layout = $this->app['layout'] ?? null;
            if($layout) {
                //json layout
                // ### JSON template (set template-file in controller)
                //$pathToLayout = 'themes/light/json.php';
                $pathToLayout = "themes/light/{$layout}.php";
            } else {
                //default layout
                $pathToLayout = 'themes/light/layout.php';
            }

            //var_dump($this->app);
            $tpl = new Template($pathToLayout); //$this->viewPath
            //var in template
            $actionResult = $this->app['actionResult'] ?? null;
            $tpl->setParam('content',$actionResult);
            $html = $tpl->getHTML();
            echo $html;
        //}

    }
}
