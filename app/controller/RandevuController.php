<?php

namespace app\controller;

use core\View;

class RandevuController extends LoginRequiredController
{
    public function takvimAction()
    {
        View::render('calendar.php');
    }
}