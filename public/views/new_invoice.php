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
    <link rel="stylesheet" href="public/css/new.css">
    <script src="https://kit.fontawesome.com/6afad8acbe.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="./public/js/common.js" defer></script>
    <script type="text/javascript" src="./public/js/script.js" defer></script>
</head>

<body>
<nav>
    <span>TAXAMO</span>
    <div class="nav-buttons-container">
        <a href="new_invoice" class="nav-button btn-active"><i class="fas fa-plus"></i>Create new</a>
        <a href="my_invoices" class="nav-button"><i class="fas fa-list"></i>My invoices</a>
        <a href="logout" class="nav-button"><i class="fas fa-sign-out-alt"></i></button></a>
    </div>
</nav>
<main>
    <h2 class="page-title">New Invoice</h2>
    <div id="form-container">
        <form id="invoice-form">
            <label id="invoice-details-label">Invoice Details</label>
            <div class="invoice-details-container">
                <select name="invoice_type" required>
                    <option value="" disabled selected hidden>Typ</option>
                    <option value="VAT">VAT</option>
                    <option value="VAT-MARZA">VAT-Marża</option>
                </select>
                <input type="text" name="place" placeholder="Place" required>
                <input type="date" name="date" placeholder="Date" required>
                <input type="text" name="invoice_nr" placeholder="Invoice number" required>
                <select name="payment_method" required>
                    <option value="" disabled selected hidden>Typ płatności</option>
                    <option value="cash">Gotówka</option>
                    <option value="transfer">Przelew</option>
                </select>
            </div>
            <div>
                <label id="buyer-details-label">Buyer Details</label>
                <input class="checkbox-round" type="checkbox" id="is_company" name="is_company" checked>
                <label for="is_company">Company</label>
            </div>
            <div id="buyer-details-container">
                <div id="nip-search-container">
                    <input pattern="^\d{10}$" id="nip-input" type="number" name="nip" placeholder="NIP" required>
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
            </div>
            <label id="items-label">Items<i id='item-add-button' class="fas fa-plus"></i></label>
            <table id="items">
                <thead>
                <tr>
                    <th class="w-30">Product Name</th>
                    <th class="w-10">QU</th>
                    <th class="w-10">Unit</th>
                    <th class="w-20">Netto</th>
                    <th class="w-10">%</th>
                    <th class="w-20">Brutto</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="table-body">
                <tr>
                    <td><input type="text" required></td>
                    <td><input pattern="\d*" type="number" value="1" required></td>
                    <td><input type="text" value="szt" required></td>
                    <td><input pattern="^\d+(\.\d{1,2})?$" min="0.01" step="0.01" type="number" onfocusout="handleCalculateBrutto(event);handleCalculateTotal()" placeholder="0.00" required></td>
                    <td><input pattern="^\d+(\.\d{1,2})?$" min="0.01" step="0.01" type="number" value="23" required></td>
                    <td><input pattern="^\d+(\.\d{1,2})?$" min="0.01" step="0.01" type="number" onfocusout="handleCalculateNetto(event);handleCalculateTotal()" placeholder="0.00" required></td>
                    <td><i class="fas fa-trash" onclick="handleDeleteRow(event)"></i></td>
                </tr>
                </tbody>
            </table>
            <div class="total-container">
                Total
                <div>
                    <div >Netto: <span id="total-netto">0.00</span> zł</div>
                    <div >Brutto: <span id="total-brutto">0.00</span> zł</div>
                </div>
            </div>
        </form>
        <textarea id="additional-informations" placeholder="Additional informations..." type="text"></textarea>
        <button id="save" onclick="processSave()" class="btn-solid-blue">SAVE</button>
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
        <td><input pattern="^[1-9]\d*$" type="number" value="1" required></td>
        <td><input type="text" value="szt" required></td>
        <td><input pattern="^[1-9][0-9]{0,2}(?:\.?[0-9]{3}){0,3}(,[0-9]{2})?$" type="number" onfocusout="handleCalculateBrutto(event);handleCalculateTotal(event)" placeholder="0.00" required></td>
        <td><input pattern="^[1-9][0-9]{0,2}(?:\.?[0-9]{3}){0,3}(,[0-9]{2})?$" type="number" value="23" required></td>
        <td><input pattern="^[1-9][0-9]{0,2}(?:\.?[0-9]{3}){0,3}(,[0-9]{2})?$" type="number" onfocusout="handleCalculateNetto(event);handleCalculateTotal(event)" placeholder="0.00" required></td>
        <td><i class="fas fa-trash" onclick="handleDeleteRow(event)"></i></td>
    </tr>
</template>
</body>
</html>