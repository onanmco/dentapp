<?php

namespace app\controller;

use core\Controller;
use core\View;

class HomeController extends Controller
{
    public function indexAction()
    {
        View::render('calendar.php');
    }

    public function before()
    {
    }

    public function after()
    {
    }
}