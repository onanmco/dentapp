<?php

namespace app\controller;

use app\utility\Auth;
use core\Controller;

class LoginRequiredController extends Controller
{
    protected function before()
    {
        Auth::loginRequired();
    }
}