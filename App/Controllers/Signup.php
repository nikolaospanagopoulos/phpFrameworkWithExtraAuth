<?php


namespace App\Controllers;

use App\Config;
use App\Models\User;
use \Core\View;

class Signup extends \Core\Controller
{



    public function newAction()
    {
        View::render('Signup/new.php');
    }

    public function createAction()
    {
        $user = new User($_POST);

        if ($user->save()) {
            $user->sendActivationEmail();
            header('Location:' . 'http://' . $_SERVER['HTTP_HOST'] . '/' . Config::APP_NAME . '/signup/success', true, 303);
            exit;
        } else {
            View::render('Signup/new.php', [
                'user' => $user
            ]);
        }
    }

    public function successAction()
    {
        View::render('Signup/success.php');
    }
    public function activateAction(){
        $this->route_params['token'];
        User::activate( $this->route_params['token']);
        header('Location:' . 'http://' . $_SERVER['HTTP_HOST'] . '/' . Config::APP_NAME . '/signup/activated', true, 303);
            exit;
    }

    public function activatedAction(){
        View::render('Signup/activated.php');
    }
}
