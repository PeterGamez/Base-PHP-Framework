<?php

namespace App\Controllers;

use System\Controller\Controller;
use System\Router\Request;

class View extends Controller
{
    public static function home(Request $request)
    {
        visitor_views('index');
    }
}
