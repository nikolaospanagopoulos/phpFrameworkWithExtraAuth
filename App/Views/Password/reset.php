<?php

use App\Config;

include dirname(__DIR__) . '/header.php';
?>
<h1>Reset password</h1>


<?php
if (isset($user) && !empty($user->errors)) {
    echo '<p>Errors: </p>';
    echo '<ul>';

    foreach ($user->errors as $error) {
        echo '<li>' . $error . '</li>';
    }

    echo '</ul>';
}



?>


<form method="post" id="form-password" action="<?php echo '/' . Config::APP_NAME . '/password/reset-password' ?>">
    <input type="hidden" name="token" value="<?php echo $token ?>">
    <div>
        <label for="inputPassword">Password</label>
        <input type="password" name='password' id='inputPassword' placeholder="Password" required>
    </div>
    <div>
        <label for="inputPasswordConfirmation">Password</label>
        <input type="password" name='password_confirmation' id='inputPasswordConfirmation' placeholder="repeat password" required>
    </div>
    <button>Reset</button>
</form>
<?php include  dirname(__DIR__) . '/footer.php'    ?>