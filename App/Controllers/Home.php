<?php

namespace App\Controllers;
use App\Auth;
use \Core\View;


class Home extends \Core\Controller
{

    public function indexAction()
    {



        View::render('Home/index.php', [
            'user'=>Auth::getUser()
        ]);
    }

    protected function before()
    {
         
    }



    protected function after()
    {
      
    }
}
