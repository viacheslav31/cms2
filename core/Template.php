<?php

namespace core;

class Template
{
    protected $path;
    protected $params;

    public function __construct($path)
    {
        $this->path = $path;
        $this->params = [];
    }

    public function setParam($name,$value)
    {
        $this->params[$name] = $value;
    }

    public function setParams($params)
    {
        foreach($params as $name => $value) {
            $this->setParam($name,$value);
        }
    }

    public function getHTML() {
        // ### ob_boofer замість виводу в бровсер - вертаєм html-код сторінки (через буфер)
        ob_start();
        //за заданим асоц. масивом створюєм локальні змінні
        //$arr = ['content'=>'<h1>...</h1>h1>'];extract($arr); //-> var $content = '<h1>...</h1>h1>'
        extract($this->params);
        include ($this->path);
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
}
