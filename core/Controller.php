<?php

namespace core;

class Controller
{
//    private $app;
    protected $viewPath;
    public $layout = null;

    public function __construct()
    {
        $app = Core::getInstance()->app;
//        $this->app = $app;
        $moduleName = $app['moduleName'];
        $actionName = $app['actionName'];
        //$actionName = $app['actionName'];
        $this->viewPath = "views/{$moduleName}/{$actionName}.php";
    }

    public function render($params = null, $layout = null,$viewPath = null)
    {
        //$viewPath: user set view path

        if (!empty($layout)) {
            $this->layout = $layout;
        }

        if (empty($viewPath)) {
            $viewPath = $this->viewPath;
        }

        $tpl = new Template($viewPath);
        if (!empty($params)) {
            $tpl->setParams($params);
        }

        return $tpl->getHTML();
    }
}
