<?php

use App\Config;

include dirname(__DIR__) . '/header.php';
?>
<h1>Success ! thank you for activating your account</h1>

<?php

echo "<a href=/" . Config::APP_NAME . '/login/new' . ">Login</a>";
?>


<?php include  dirname(__DIR__) . '/footer.php'    ?>