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
    <link rel="stylesheet" href="public/css/new_invoice.css">
    <script src="https://kit.fontawesome.com/6afad8acbe.js" crossorigin="anonymous"></script>
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
        <form>
            <label id="invoice-details-label">Invoice Details</label>
            <div class="invoice-details-container">
                <input type="text" name="place" placeholder="Place">
                <input type="text" name="date" placeholder="Date">
                <input type="text" name="invoice_nr" placeholder="Invoice number">
                <input type="text" name="payment_method" placeholder="Payment method">
            </div>
            <label id="buyer-details-label">Buyer Details</label>
            <div class="buyer-details-container">
                <input type="text" name="nip" placeholder="NIP">
                <input type="text" name="city" placeholder="City">
                <input type="text" name="name" placeholder="Name">
                <input type="text" name="zip_code" placeholder="Zip Code">
                <input type="text" name="surname" placeholder="Surname">
                <div class="street-container">
                    <input type="text" name="street_name" placeholder="Street">
                    <input type="text" name="street_nr" placeholder="Nr">
                </div>
                <input type="text" name="phone_nr" placeholder="Phone Number">
            </div>
            <label id="items-label">Items<i class="fas fa-plus"></i></label>
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
                <tbody>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><i class="fas fa-trash"></i></td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><i class="fas fa-trash"></i></td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><i class="fas fa-trash"></i></td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><i class="fas fa-trash"></i></td>
                </tr>
                </tbody>
            </table>
            <div class="total-container">
                Total
                <div id="total">72322 zł</div>
            </div>
            <input id="total-words" type="text" name="total-words" placeholder="In Words:"></input>
        </form>
        <textarea id="additional-informations" placeholder="Additional informations..." type="text"></textarea>
        <button id="save" class="btn-solid-blue">SAVE</button>
    </div>
</main>
</body>
<script>
    const first = document.getElementById('first');
    const save = document.getElementById('save');
    save.onclick = function () {
        first.remove();
    }
</script>

</html>