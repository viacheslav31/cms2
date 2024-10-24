<?php

namespace controllers;

use core\Controller;

class UserController extends Controller
{
    public function indexAction()
    {
        // run with router: http://cms/news
        echo 'Index';
    }
    public function viewAction() {
        // run with router: http://cms/news/view

        echo 'View';
    }

    public function mail() {
        // not run with router
        echo 'mail';
    }

}