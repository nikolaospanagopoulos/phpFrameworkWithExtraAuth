<?php


namespace App\Controllers;

use App\Auth;
use \Core\View;
use \App\Models\User;
use App\Config;
use App\Flash;
use App\Mail;

class Login extends \Core\Controller
{


    public function newAction()
    {
        View::render('Login/new.php');
        // Mail::send('nikos4222@outlook.com.gr', 'sdfghj', 'wsertyuio', '<h1>Nikos</h1>');
    }

    public function createAction()
    {

        $user = User::authenticate($_POST['email'], $_POST['password']);

        $remember_me = isset($_POST['remember_me']);

        
        if ($user) {
            Auth::login($user, $remember_me);
            Flash::addMessage('login successful');
            Auth::getReturnTopage();
        } else {
            View::render('Login/new.php', [
                'email' => $_POST['email'],
                'remember_me' => $remember_me,
                'errors'=>['something went wrong']
            ]);
        }
    }
    public function destroyAction()
    {

        Auth::logout();

        header('Location:' . 'http://' . $_SERVER['HTTP_HOST'] . '/' . Config::APP_NAME . '/login/show-logout-message', true, 303);
        exit;
    }
    public function showLogoutMessageAction()
    {


        Flash::addMessage('logout successful');
        header('Location:' . 'http://' . $_SERVER['HTTP_HOST'] . '/' . Config::APP_NAME . '/', true, 303);
        exit;
    }

    
}
