<?php

use App\Config;

include dirname(__DIR__) . '/header.php';
?>

<h1>Request password reset</h1>


<form action="<?php echo '/' . Config::APP_NAME . '/password/request-reset' ?>" method="POST">

    <div>
        <label for="inputEmail">email address</label>
        <input type="email" in='inputEmail' name="email" placeholder="email address" autofocus required>
    </div>

    <button type="submit">send password reset</button>
</form>


<?php include  dirname(__DIR__) . '/footer.php'    ?>