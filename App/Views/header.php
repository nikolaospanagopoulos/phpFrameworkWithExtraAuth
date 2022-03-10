<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php

    use App\Config;

    echo "<script defer src=" . 'http://' . $_SERVER['HTTP_HOST'] . "/" . Config::APP_NAME . '/public/js/dist/index.js' . "></script>"  ?>
    <title>Document</title>
</head>

<body>
    <?php


    if (isset($_SESSION['flash_notifications'])) {
        $messages = $_SESSION['flash_notifications'];
        unset($_SESSION['flash_notifications']);
        foreach ($messages as $message) {
            echo '<h1>' . $message . '</h1>';
        }
    }




    ?>
