<?php



namespace App\Controllers;

use App\Models\User;
use \Core\View;



class Password extends \Core\Controller
{




    public function forgotAction()
    {
        View::render('Password/forgot.php');
    }


    public function requestResetAction()
    {

        User::sendPasswordReset($_POST['email']);

        View::render('Password/reset_requested.php');
    }

    public function resetAction()
    {
        $token = $this->route_params['token'];

        $user = $this->getUserOrExit($token);

        View::render('Password/reset.php', [
            'token' => $token
        ]);
    }

    public function resetPasswordAction()
    {
        $token = $_POST['token'];
        $user = $this->getUserOrExit($token);

        if ($user->resetPassword($_POST['password'])) {
            View::render('Password/reset_success.php');
        } else {
            View::render('Password/reset.php', [
                'token' => $token,
                'user' => $user
            ]);
        }
    }

    protected function getUserOrExit($token)
    {
        $user = User::findByPasswordReset($token);

        if ($user) {
            return $user;
        } else {
            echo 'invalid password token';
            exit;
        }
    }
}
