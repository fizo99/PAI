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
    <link rel="stylesheet" href="public/css/my_invoices.css">
    <script type="text/javascript" src="./public/js/invoicesList.js" defer></script>
    <script src="https://kit.fontawesome.com/6afad8acbe.js" crossorigin="anonymous"></script>
</head>

<body>
    <nav>
        <span>TAXAMO</span>
        <div class="nav-buttons-container">
            <a href="new_invoice" class="nav-button"><i class="fas fa-plus"></i>Create new</a>
            <a href="my_invoices" class="nav-button btn-active"><i class="fas fa-list"></i>My invoices</a>
            <a href="logout" class="nav-button"><i class="fas fa-sign-out-alt"></i></button></a>
        </div>
    </nav>
    <main>
        <div id="top-bar">
            <div id="search-bar">
                <i class="fa fa-search"></i>
                <input type="search" placeholder="Search">
            </div>
            <h2 class="page-title">Your Invoices</h2>
        </div>
        <table id="items">
            <thead>
                <tr>
                    <th class="w-20">Buyer</th>
                    <th class="w-20">NIP</th>
                    <th class="w-20">Brutto Value</th>
                    <th class="w-20">Date</th>
                    <th class="w-10"></th>
                    <th class="w-10"></th>
                </tr>
            </thead>
            <tbody id="items-table-body">
                <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td class="w-20"><?= $invoice['buyer_name']; ?></td>
                        <td class="w-20"><?= $invoice['nip']; ?></td>
                        <td class="w-20"><?= $invoice['total_brutto_value']; ?></td>
                        <td class="w-20"><?= $invoice['date']; ?></td>
                        <td class="w-10" onclick="downloadInvoice(event)"><i class="fas fa-file-download"></i></td>
                        <td class="w-10" onclick="deleteInvoice(event)"><i class="fas fa-trash"></i></td>
                        <input type="hidden" value="<?= $invoice['invoice_id']; ?>">
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>

</html>