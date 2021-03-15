<?php

namespace app\controller;

use core\Controller;
use core\View;

class HomeController extends Controller
{
    public function indexAction()
    {
        // View::render('calendar.php');
        View::render('home.php');
    }

    public function before()
    {
    }

    public function after()
    {
    }
}