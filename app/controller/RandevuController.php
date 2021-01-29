<?php

namespace app\controller;

use core\Controller;
use core\View;

class RandevuController extends Controller
{
    public function takvimAction()
    {
        View::render('calendar.php');
    }
}