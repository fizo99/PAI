<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Rejestracja</title>
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
                <div class="flex row hor-space-between">
                    <label>Szczegóły konta</label>
                    <div class="flex row hor-center ver-center">
                        <span>Demo</span>
                        <input type="checkbox" id="is_demo" name="is_demo" checked>
                    </div>
                </div>
                <input type="text" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Hasło">
                <input type="password" name="password-repeat" placeholder="Powtórz hasło">
            </section>
            <section>
                <label>Szczegóły Firmy</label>
                <input type="text" name="nip" placeholder="NIP">
                <input type="text" name="company_name" placeholder="Nazwa firmy">
            </section>
            <section>
                <label>Kontakt</label>
                <input type="text" name="contact-email" placeholder="Email">
                <input type="text" name="phone_number" placeholder="Numer telefonu">
                <input type="text" name="iban" placeholder="Nr IBAN">
            </section>
            <section>
                <label>Adres</label>
                <input type="text" name="city" placeholder="Miejscowość">
                <input type="text" name="zip" placeholder="Kod pocztowy">
                <div class="flex row">
                    <input class="w80" type="text" name="street_name" placeholder="Nazwa ulicy">
                    <input class="w20"type="text" name="street_nr" placeholder="Nr">
                </div>
            </section>
            <button class="btn-blue" type="submit">Zarejestruj</button>
        </form>
    </main>
</body>

</html>