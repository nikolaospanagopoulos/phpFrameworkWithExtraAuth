<?php


namespace App\Models;

use App\Config;
use App\Mail;
use App\Token;
use PDO;

class User extends \Core\Model
{

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
    public function save()
    {
        $this->validate();
        if (empty($this->errors)) {
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $token = new Token();

            $hashed_token = $token->getHash();
            $this->activation_token = $token->getToken();
            $sql = 'INSERT INTO users (name,email,password_hash,activation_hash)
            VALUES (:name,:email,:password_hash,:activation_hash)
            ';

            $db = static::getDB();

            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function validate()
    {
        if ($this->name == '') {
            $this->errors[] = 'Name is required';
        }
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Invalid email';
        }

        if ($this::emailExists($this->email, $this->id ?? null)) {
            $this->errors[] = 'email already taken';
        }


        if ($this->password != $this->password_confirmation) {
            $this->errors[] = 'passwords do not match';
        }
        if (strlen($this->password) < 6) {
            $this->errors[] = 'password must be at least 6 characters long';
        }
        if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
            $this->errors[] = 'password needs at least one letter';
        }
        if (preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors[] = 'password needs at least one number';
        }
    }
    public static function emailExists($email, $ignore_id = null)
    {
        $user =  static::findByEmail($email);

        if ($user) {
            if ($user->id != $ignore_id) {
                return true;
            } else {
                return false;
            }
        }
    }


    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(pdo::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return  $stmt->fetch();
    }

    public static function authenticate($email, $password)
    {
        $user = static::findByEmail(($email));

        if ($user && $user->is_active) {
            if (password_verify($password, $user->password_hash)) {
                return $user;
            }
        }

        return false;
    }


    public static function findById($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(pdo::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return  $stmt->fetch();
    }
    public function rememberLogin()
    {
        $token = new Token();

        $hashed_token = $token->getHash();

        $this->remember_token = $token->getToken();
        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30;

        $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
            VALUES (:token_hash, :user_id,:expires_at)
            ';
        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s',  $this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function sendPasswordReset($email)
    {
        $user = static::findByEmail($email);

        if ($user) {
            if ($user->startPasswordReset()) {
                $user->sendPasswordResetEmail();
            }
        }
    }

    protected function  startPasswordReset()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->password_reset_token = $token->getToken();
        $expiry_timestamp = time() + 60 * 60 * 2;

        $sql = 'UPDATE users SET password_reset_hash = :token_hash,
        password_reset_expiry = :expires_at
        WHERE id = :id
        ';

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s',  $expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }

    protected function sendPasswordResetEmail()
    {
        $protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
        $url =  $protocol . "://" .  $_SERVER['HTTP_HOST'] . "/" . Config::APP_NAME .  '/password/reset/' . $this->password_reset_token;
        $text = 'Please click on the following link to reset your password: ' . $url;
        $html = '<h1>Please click on the following link to reset your password:</h1>
        <a href=' . $url . '>' . $url . '</a>
        ';

        Mail::send($this->email, 'password reset', $text, $html);
    }

    public static function findByPasswordReset($token)
    {
        $token = new Token($token);


        $hashed_token = $token->getHash();

        $sql = 'SELECT * FROM users WHERE password_reset_hash = :token_hash';
        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $user =  $stmt->fetch();


        if ($user) {
            if (strtotime($user->password_reset_expiry) > time()) {
                return $user;
            }
        }
    }
    public function resetPassword($password)
    {
        $this->password = $password;
        $this->password_confirmation = $_POST['password_confirmation'];

        $this->validate();


        if (empty($this->errors)) {
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'UPDATE users SET password_hash = :password_hash,
            password_reset_hash = NULL,
            password_reset_expiry = NULL
            
             WHERE id=:id';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function sendActivationEmail()
    {
        $protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
        $url =  $protocol . "://" .  $_SERVER['HTTP_HOST'] . "/" . Config::APP_NAME .  '/signup/activate/' . $this->activation_token;
        $text = 'Please click on the following link to activatr your account: ' . $url;
        $html = '<h1>Please click on the following link to activatr your account:</h1>
        <a href=' . $url . '>' . $url . '</a>
        ';

        Mail::send($this->email, 'account activation', $text, $html);
    }

    public static function activate($value)
    {

        $token = new Token($value);
        $hashed_token = $token->getHash();

        $sql = 'UPDATE users SET is_active = 1, activation_hash = null WHERE activation_hash = :hashed_token';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(":hashed_token", $hashed_token, PDO::PARAM_STR);

        $stmt->execute();
    }
}
