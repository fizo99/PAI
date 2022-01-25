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
        <link rel="stylesheet" href="public/css/commons.css">
        <link rel="stylesheet" href="public/css/register.css">
    </head>
    <body>
        <main>
            <section id="logo">TAXAMO</section>
            <form method="POST">
                <div class="messages">
                    <?php
                    if(isset($messages)){
                        foreach($messages as $message) {
                            echo $message;
                        }
                    }
                    ?>
                </div>
                <section>
                    <input type="text" name="email" placeholder="Email">
                    <input type="password" name="password" placeholder="Password">
                </section>
                <button class = "btn-blue" type="submit">Login</button>
                <a class href="register"">Register</a>
            </form>
        </main>
    </body>
</html>