 <?php

    use App\Config;

    include dirname(__DIR__) . '/header.php';
    ?>
 <h1>Sign up</h1>


 <?php
    if (!empty($errors)) {
        echo '<p>Errors: </p>';
        echo '<ul>';

        foreach ($errors as $error) {
            echo '<li>' . $error . '</li>';
        }

        echo '</ul>';
    }

    $userEmail = isset($email) ? $email : '';

 
    ?>






 <form method="post" action="<?php echo '/' . Config::APP_NAME . '/login/create' ?>" id="form-login">


     <div>
         <label for="inputEmail">Email</label>
         <input type="email" name='email' id='inputEmail' placeholder="Email address" value="<?php echo $userEmail ?>" required>
     </div>
     <div>
         <label for="inputPassword">Password</label>
         <input type="password" name='password' id='inputPassword' placeholder="Password" required>
     </div>
     <div>

         <label>
             <input type="checkbox" name="remember_me" <?php
                                                        if (isset($remember_me)) {
                                                            echo  "checked";
                                                        }
                                                        ?> />Remember me

         </label>

     </div>
     <button>Login</button>
 </form>

 <a href="<?php echo '/' . Config::APP_NAME . '/password/forgot' ?>">Password Reset</a>
 <?php include  dirname(__DIR__) . '/footer.php'    ?>
