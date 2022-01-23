<?php

require_once 'Repository.php';
require_once 'UserExistsException.php';
require_once 'UserAbsentException.php';
require_once __DIR__ . '/../models/Invoice.php';

class InvoiceRepository
{
    public function getInvoiceDetails(string $invoiceId,  PDO $existingConn = null)
    {
        $conn = $existingConn == null ? Repository::connect() : $existingConn;
        $stmt = $conn->prepare('
            SELECT 
                buyer.nip buyer_NIP,
                buyer.name buyer_name,
                buyer.email buyer_email,
                buyer.phone_number buyer_phone_number,
                buyer_address.street_name buyer_street_name,
                buyer_address.street_nr buyer_street_number,
                buyer_address.zip_code buyer_zip_code,
                buyer_address.city buyer_city,
                seller.iban seller_iban,
                seller.nip seller_NIP,
                seller.name seller_name,
                seller.email seller_email,
                seller.phone_number seller_phone_number,
                seller_address.street_name seller_street_name,
                seller_address.street_nr seller_street_number,
                seller_address.zip_code seller_zip_code,
                seller_address.city seller_city,
                date,
                place,
                number,
                payment_method,
                additional_info,
                invoice_type
            FROM invoices
                JOIN companies seller ON seller.nip = invoices.seller_id
                JOIN companies buyer ON buyer.nip = invoices.buyer_id
                JOIN addresses seller_address ON seller.address_id = seller_address.address_id
                JOIN addresses buyer_address ON buyer.address_id = buyer_address.address_id
                JOIN users ON users.user_id = invoices.user_id
                JOIN payment_methods ON invoices.payment_method_id = payment_methods.payment_method_id
                JOIN invoices_types ON invoices.invoice_type_id = invoices_types.invoice_type_id
            where invoice_id = :invoice_id
        ');
        $stmt->bindParam(":invoice_id", $invoiceId);

        $stmt->execute();

        $invoiceDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        return $invoiceDetails;
    }

    public function getInvoiceProducts(string $invoiceId,  PDO $existingConn = null)
    {
        $conn = $existingConn == null ? Repository::connect() : $existingConn;
        $stmt = $conn->prepare('
            select 
                name,
                quantity,
                unit,
                netto_price,
                tax_percent,
                round(netto_price + (netto_price * (tax_percent::numeric / 100::numeric)) - netto_price, 2) tax_amount,
                round(netto_price + (netto_price * (tax_percent::numeric / 100::numeric)),2) brutto_price
            from invoices_products
                JOIN products on invoices_products.product_id = products.product_id
            where invoice_id = :invoice_id
        ');
        $stmt->bindParam(":invoice_id", $invoiceId);

        $stmt->execute();

        $invoiceProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $invoiceProducts;
    }

    public function getInvoicesForInvoicesListView(string $userId,  PDO $existingConn = null)
    {
        $conn = $existingConn == null ? Repository::connect() : $existingConn;
        $stmt = $conn->prepare('
            SELECT * FROM invoices_basic_data(:user_id);
        ');
        $stmt->bindParam(":user_id", $userId);

        $stmt->execute();

        $invoiceList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $invoiceList;
    }

    public function getTotalBruttoValueForInvoice(string $invoiceId, PDO $existingConn = null)
    {
        $conn = $existingConn == null ? Repository::connect() : $existingConn;
        $stmt = $conn->prepare('
            SELECT * FROM invoices_total_brutto_value(:invoice_id)
        ');
        $stmt->bindParam(":invoice_id", $invoiceId);

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_brutto_value'];
    }

    public function getAllInvoicesForUser(string $userId,  PDO $existingConn = null)
    {
        $conn = $existingConn == null ? Repository::connect() : $existingConn;
        $stmt = $conn->prepare('
            SELECT * FROM invoices WHERE user_id = :user_id
        ');
        $stmt->bindParam(":user_id", $userId);

        $stmt->execute();

        $invoiceListData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $invoiceList = array();
        foreach ($invoiceListData as $invoiceData) {
            array_push($invoiceList, new Invoice(
                $invoiceData['buyer_id'],
                $invoiceData['seller_id'],
                $invoiceData['place'],
                $invoiceData['date'],
                $invoiceData['number'],
                $invoiceData['payment_method_id'],
                $invoiceData['additional_info'],
                $invoiceData['user_id']
            ));
        }
        return $invoiceList;
    }
    
    public function addInvoice(Invoice $invoice,  PDO $existingConn = null): string
    {
        $conn = $existingConn == null ? Repository::connect() : $existingConn;
        $stmt = $conn->prepare('
            WITH payment_method_temp AS ( SELECT payment_method_id FROM payment_methods WHERE payment_method = ?),
            invoice_type_temp AS ( SELECT invoice_type_id FROM invoices_types WHERE invoice_type = ?),
            invoice_state_temp AS ( SELECT invoice_state_id FROM invoices_states WHERE invoice_state = ?)
            INSERT INTO invoices (buyer_id, seller_id, place, date, number, payment_method_id, additional_info, user_id, invoice_type_id, invoice_state_id)
            VALUES (?, ?, ?, ?, ?, (SELECT payment_method_id FROM payment_method_temp), ?, ?, (SELECT invoice_type_id FROM invoice_type_temp),(SELECT invoice_state_id FROM invoice_state_temp)) 
        ');

        $stmt->execute([
            $invoice->getPaymentMethod(),
            $invoice->getInvoiceType(),
            $invoice->getInvoiceState(),
            $invoice->getBuyerId(),
            $invoice->getSellerId(),
            $invoice->getPlace(),
            $invoice->getDate(),
            $invoice->getNumber(),
            $invoice->getAdditionalInformations(),
            $invoice->getUserId()
        ]);

        return $conn->lastInsertId();
    }

    public function deleteInvoice(string $invoiceId,  PDO $existingConn = null)
    {
        $conn = $existingConn == null ? Repository::connect() : $existingConn;
        $stmt = $conn->prepare('
            DELETE FROM invoices WHERE invoice_id = :invoice_id
        ');

        $stmt->bindParam(":invoice_id", $invoiceId);
        $stmt->execute();
    }

    public function getInvoiceType(string $invoice_type,PDO $existingConn = null)
    {
        $conn = $existingConn == null ? Repository::connect() : $existingConn;
        $stmt = $conn->prepare('
            SELECT invoice_type_id FROM invoices_types WHERE invoice_type = :invoice_type
        ');

        $stmt->bindParam(":invoice_type", $invoice_type);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['invoice_type_id'];
    }

    public function getAllInvoiceStates(PDO $existingConn = null)
    {
        $conn = $existingConn == null ? Repository::connect() : $existingConn;
        $stmt = $conn->prepare('
            SELECT invoice_state FROM invoices_states;
        ');

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function updateState($invoiceId, $newState, $existingConn = null)
    {
        $conn = $existingConn == null ? Repository::connect() : $existingConn;
        $stmt = $conn->prepare('
            WITH invoice_state_temp AS ( SELECT invoice_state_id FROM invoices_states WHERE invoice_state = ?)
            UPDATE invoices SET invoice_state_id = (SELECT invoice_state_id FROM invoice_state_temp) WHERE invoice_id = ?
        ');
        $result = $stmt->execute([
            $newState,
            $invoiceId
        ]);
        if($result) return true;
        else return false;
    }

}
