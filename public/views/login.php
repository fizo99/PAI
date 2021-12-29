<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Login</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
        <link rel="stylesheet" href="public/css/common.css">
        <link rel="stylesheet" href="public/css/login.css">
    </head>
    <body>
        <div id="login">
            <span>TAXAMO</span>
            <form class="login" method="POST">
                <?php
                if(isset($messages)){
                    foreach($messages as $message) {
                        echo $message;
                    }
                }
                ?>
                <input type="text" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <button class = "btn-solid-blue" type="submit">Login</button>
                <a class = "btn-text" href="register"">Register</button>
            </form>
        </div>
    </body>
</html>