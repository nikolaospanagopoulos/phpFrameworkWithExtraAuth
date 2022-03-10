<?php

use App\Config;

include dirname(__DIR__) . '/header.php';
?>
<h1>Sign up</h1>


<?php
if (isset($user) && !empty($user->errors)) {
    echo '<p>Errors: </p>';
    echo '<ul>';

    foreach ($user->errors as $error) {
        echo '<li>' . $error . '</li>';
    }

    echo '</ul>';
}

$userEmail = isset($user->email) ? $user->email : '';
$userName = isset($user->name) ? $user->name : '';

?>


<form method="post" action="<?php echo '/' . Config::APP_NAME . '/signup/create' ?>" id="form-signup">

    <div>
        <label for="inputName" >Name</label>
        <input type="text" name='name' id='inputName' placeholder="Name" autofocus value="<?php  echo $userName  ?>" required>
    </div>
    <div>
        <label for="inputEmail">Email</label>
        <input type="email" name='email' id='inputEmail' placeholder="Email address" value="<?php echo $userEmail ?>" required>
    </div>
    <div>
        <label for="inputPassword">Password</label>
        <input type="password" name='password' id='inputPassword' placeholder="Password" required>
    </div>
    <div>
        <label for="inputPasswordConfirmation">Password</label>
        <input type="password" name='password_confirmation' id='inputPasswordConfirmation' placeholder="repeat password" required>
    </div>
    <button>Submit</button>
</form>
<?php include  dirname(__DIR__) . '/footer.php'    ?>