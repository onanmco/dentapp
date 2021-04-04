<?php

namespace app\controller;

use core\View;

class AppointmentController extends LoginRequiredController
{
    public function calendarAction()
    {
        View::render('calendar.php');
    }
}