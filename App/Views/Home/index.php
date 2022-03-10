<?php

use App\Config;


include dirname(__DIR__) . '/header.php';
?>
<h1>Home</h1>




<?php

if (isset($_SESSION['user_id'])) {
    echo '<h1>welcome ' . $user->name . '</h1>';
    echo 'user with id ' . $_SESSION['user_id'] . ' is logged in';
    echo "<a href=/" . Config::APP_NAME . '/logout' . ">Logout</a>";
} else {
    echo "<a href=/" . Config::APP_NAME . '/signup/new' . ">Sign Up</a>";
    echo "<a href=/" . Config::APP_NAME . '/login/new' . ">Login</a>";
}


?>