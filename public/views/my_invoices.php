<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>My Invoices</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href="public/css/commons.css">
    <link rel="stylesheet" href="public/css/my_invoices.css">
    <script type="text/javascript" src="./public/js/common.js" defer></script>
    <script type="text/javascript" src="./public/js/invoicesList.js" defer></script>
    <script src="https://kit.fontawesome.com/6afad8acbe.js" crossorigin="anonymous"></script>
</head>
<body>
<nav>
    <section id="logo">TAXAMO</section>
    <section id="nav-buttons-container">
        <a href="new_invoice">
            <i class="fas fa-plus"></i>
            <span>Create new</span>
        </a>
        <a href="my_invoices" class="nav-active">
            <i class="fas fa-list"></i>
            <span>My invoices</span>
        </a>
        <a href="logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </section>
</nav>
<main>
    <section>
        <input id="input-search-invoices" type="search" placeholder="Search">
        <section id="table-wrapper">
            <table id="items">
                <thead>
                <tr>
                    <th class="w20">Buyer</th>
                    <th class="w10">Number</th>
                    <th class="w15">NIP</th>
                    <th class="w15">Brutto</th>
                    <th class="w15">Date</th>
                    <th class="w10">Status</th>
                    <th class="w10">Akcja</th>
                </tr>
                </thead>
                <tbody id="items-table-body">
                <?php require_once "translate.php" ?>
                <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td><?= $invoice['buyer_name']; ?></td>
                        <td><?= $invoice['number']; ?></td>
                        <td><?= $invoice['nip']; ?></td>
                        <td><?= $invoice['total_brutto_value']; ?></td>
                        <td><?= $invoice['date']; ?></td>
                        <td>
                            <select name="state" onchange="updateInvoiceState(event);" required>
                                <option value="" disabled selected hidden><?= translateStatePL($invoice['state']); ?></option>
                                <option value="UNPAID">Niezapłacona</option>
                                <option value="PAID">Zapłacona</option>
                                <option value="CANCELLED">Anulowana</option>
                            </select>
                        </td>
                        <td>
                            <select name="action" onchange="handleAction(event);" required>
                                <option value="" disabled selected hidden>Akcja</option>
                                <option value="delete">Usuń</option>
                                <option value="download_doc">Pobierz jako doc</option>
                            </select>
                        </td>
                        <input type="hidden" value="<?= $invoice['invoice_id']; ?>">
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </section>
</main>
</body>

</html>