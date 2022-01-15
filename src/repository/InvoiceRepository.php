<?php

require_once 'Repository.php';
require_once 'UserExistsException.php';
require_once 'UserAbsentException.php';
require_once __DIR__ . '/../models/Invoice.php';

class InvoiceRepository
{
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
            WITH identity AS ( SELECT payment_method_id FROM payment_methods WHERE payment_method = ?)
            INSERT INTO invoices (buyer_id, seller_id, place, date, number, payment_method_id, additional_info, user_id)
            VALUES (?, ?, ?, ?, ?, (SELECT payment_method_id FROM identity), ?, ?) 
        ');

        $stmt->execute([
            $invoice->getPaymentMethod(),
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

}
