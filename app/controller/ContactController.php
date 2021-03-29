<?php

namespace app\controller;

use core\Controller;
use core\View;

class ContactController extends Controller
{
    public function indexAction()
    {
        View::render('contact.php');
    }
}