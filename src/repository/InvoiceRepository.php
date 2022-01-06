<?php

require_once 'Repository.php';
require_once 'UserExistsException.php';
require_once 'UserAbsentException.php';
require_once __DIR__ . '/../models/Invoice.php';

class InvoiceRepository
{
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

}
