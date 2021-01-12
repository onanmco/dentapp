<?php

namespace app\controller;
use core\View;

class RestrictedController extends LoginRequiredController
{
    public function indexAction()
    {
        View::render('restricted.php');
    }
}