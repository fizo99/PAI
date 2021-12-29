<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href="public/css/common.css">
    <link rel="stylesheet" href="public/css/register.css">
</head>

<body>
    <main id="login">
        <span>TAXAMO</span>
        <form class="login" method="POST">
            <div class="messages">
                <?php
                if(isset($messages)){
                    foreach($messages as $message) {
                        echo $message;
                    }
                }
                ?>
            </div>
            <label>Account Details</label>
            <input type="text" name="email" placeholder="Email">
            <input type="password" name="password" placeholder="Password">
            <input type="password" name="password-repeat" placeholder="Repeat Password">
            <label>Company Details</label>
            <input type="text" name="nip" placeholder="NIP">
            <input type="text" name="company_name" placeholder="Company Name">
            <label>Contact</label>
            <input type="text" name="contact-email" placeholder="Email">
            <input type="text" name="phone_number" placeholder="Phone Number">
            <input type="text" name="iban" placeholder="IBAN">
            <label>Address</label>
            <input type="text" name="city" placeholder="City">
            <input type="text" name="zip" placeholder="Zip Code">
            <div id="street">
                <input id="street-name" type="text" name="street_name" placeholder="Street Name">
                <input id="street-nr" type="text" name="street_nr" placeholder="Nr">
            </div>
            <button class="btn-solid-blue" type="submit">Register</button>
        </form>
    </main>
</body>

</html>