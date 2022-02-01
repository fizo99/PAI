<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Nowa Faktura</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href="public/css/commons.css">
    <link rel="stylesheet" href="public/css/create.css">
    <script src="https://kit.fontawesome.com/6afad8acbe.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="./public/js/common.js" defer></script>
    <script type="text/javascript" src="./public/js/create.js" defer></script>
</head>
<body>
<?php include("nav.php"); ?>
<main>
    <div id="form-container">
        <section id="left-bar">
            <form id="invoice-form">
                <section id="invoice-details-container">
                    <label class="w100" id="invoice-details-label">Szczegóły Faktury</label>
                    <section class="flex row ver-space-between w100">
                        <section>
                            <input type="text" name="invoice_nr" placeholder="Numer faktury" required>
                            <select name="invoice_type" required>
                                <option value="" disabled selected hidden>Typ</option>
                                <option value="VAT">VAT</option>
                                <option value="VAT-MARZA">VAT-Marża</option>
                            </select>
                            <select name="payment_method" required>
                                <option value="" disabled selected hidden>Typ płatności</option>
                                <option value="cash">Gotówka</option>
                                <option value="transfer">Przelew</option>
                            </select>
                        </section>
                        <section>
                            <input type="text" name="place" placeholder="Miejsce" required>
                            <input type="date" name="date" required>
                        </section>
                    </section>
                </section>
                <section class="mt-15" id="buyer-details-container">
                    <div class="w100">
                        <div class="flex row hor-space-between w45">
                            <label id="buyer-details-label">Kupujący</label>
                            <div class="flex row hor-center ver-center">
                                <input type="checkbox" id="is_company" name="is_company" checked>
                                <span>Firma</span>
                            </div>
                        </div>
                    </div>
                    <section class="flex col w45">
                        <div class="flex row ver-center hor-space-between">
                            <input class="w90" pattern="^\d{10}$" id="nip-input" type="number" name="nip"
                                   placeholder="NIP" required>
                            <i class="fa fa-search" onclick="findNIP()"></i>
                        </div>
                        <input id='invoice-company-name' type="text" name="company_name" placeholder="Nazwa firmy"
                               required>
                        <input type="text" name="phone_nr" placeholder="Numer telefonu">
                    </section>
                    <section class="flex col w45">
                        <input id='invoice-city' type="text" name="city" placeholder="Miejscowość" required>
                        <input id='invoice-zip' type="text" name="zip_code" placeholder="Kod pocztowy" required>
                        <div class="flex row hor-center ver-center w100">
                            <input class="w80" id='invoice-street-name' type="text" name="street_name"
                                   placeholder="Ulica" required>
                            <input class="w20" id='invoice-street-nr' type="text" name="street_nr" placeholder="Nr"
                                   required>
                        </div>
                    </section>
                </section>
                <label id="items-label" class="mt-15">
                    <span>Przedmioty</span>
                    <i id='item-add-button' class="fas fa-plus-circle"></i>
                </label>
                <section id="invoice-items-container">
                    <table class="w100" id="items">
                        <thead class="w100">
                        <tr>
                            <th class="w30">Nazwa</th>
                            <th class="w10">Ilość</th>
                            <th class="w10">Jm.</th>
                            <th class="w20">Netto</th>
                            <th class="w5">%</th>
                            <th class="w20">Brutto</th>
                            <th class="w5"></th>
                        </tr>
                        </thead>
                        <tbody class="w100" id="table-body">
                        <tr>
                            <td><input type="text" required></td>
                            <td><input pattern="\d*" type="number" value="1" onfocusout="handleCalculateTotal()"
                                       required></td>
                            <td><input type="text" value="szt" required></td>
                            <td><input pattern="^\d+(\.\d{1,2})?$" type="number"
                                       onfocusout="handleNettoChange(event)"
                                       placeholder="0.00" required></td>
                            <td><input pattern="^\d+(\.\d{1,2})?$" type="number" value="23"
                                       onfocusout="handlePercentChange(event)" required></td>
                            <td><input pattern="^\d+(\.\d{1,2})?$" type="number"
                                       onfocusout="handleBruttoChange(event)"
                                       placeholder="0.00" required></td>
                            <td><i class="fas fa-ban" onclick="handleDeleteRow(event)"></i></td>
                        </tr>
                        </tbody>
                    </table>
                </section>
            </form>
        </section>
        <section id="right-bar">
            <section id="summary">
                <section>
                    <span>Netto</span>
                    <span>Brutto</span>
                </section>
                <section>
                    <span>
                        <b id="total-netto">0.00</b><span> zł</span>
                    </span>
                    <span>
                        <b id="total-brutto">0.00</b><span> zł</span>
                    </span>
                </section>
            </section>
            <textarea class="mt-15" id="additional-informations" placeholder="Dodatkowe informacje..."></textarea>
            <button id="save" class="btn-blue mt-15" onclick="processSave()">ZAPISZ</button>
        </section>
    </div>
</main>
<template id="for-company">
    <div id="nip-search-container">
        <input id="nip-input" type="text" name="nip" placeholder="NIP" required>
        <i class="fa fa-search" onclick="findNIP()"></i>
    </div>
    <input id='invoice-city' type="text" name="city" placeholder="City" required>
    <input id='invoice-company-name' type="text" name="company_name" placeholder="Company Name" required>
    <input id='invoice-zip' type="text" name="zip_code" placeholder="Zip Code" required>
    <div class="street-container">
        <input id='invoice-street-name' type="text" name="street_name" placeholder="Street" required>
        <input id='invoice-street-nr' type="text" name="street_nr" placeholder="Nr" required>
    </div>
    <input type="text" name="phone_nr" placeholder="Phone Number">
</template>
<template id="for-private">
    <input type="text" name="buyer_full_name" placeholder="Buyer full name">
    <input type="text" name="city" placeholder="City">
    <input type="text" name="zip_code" placeholder="Zip Code">
    <div class="street-container">
        <input type="text" name="street_name" placeholder="Street">
        <input type="text" name="street_nr" placeholder="Nr">
    </div>
    <input type="text" name="phone_nr" placeholder="Phone Number">
</template>
<template id="table-row">
    <tr>
        <td><input type="text" required></td>
        <td><input pattern="\d*" type="number" value="1" onfocusout="handleCalculateTotal()" required></td>
        <td><input type="text" value="szt" required></td>
        <td><input pattern="^\d+(\.\d{1,2})?$" type="number"
                   onfocusout="handleNettoChange(event)"
                   placeholder="0.00" required></td>
        <td><input pattern="^\d+(\.\d{1,2})?$" type="number" value="23"
                   onfocusout="handlePercentChange(event)" required></td>
        <td><input pattern="^\d+(\.\d{1,2})?$" type="number"
                   onfocusout="handleBruttoChange(event)"
                   placeholder="0.00" required></td>
        <td><i class="fas fa-ban" onclick="handleDeleteRow(event)"></i></td>
    </tr>
</template>
</body>
</html>