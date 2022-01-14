<?php

require_once 'Repository.php';
require_once 'UserExistsException.php';
require_once 'UserAbsentException.php';
require_once __DIR__ . '/../models/User.php';

class ProductRepository
{
    public function addProduct(Product $product, PDO $existingConn = null): string
    {
        $conn = $existingConn == null ? Repository::connect() : $existingConn;
        $stmt = $conn->prepare('
            INSERT INTO PRODUCTS(name,unit,netto_price,tax_percent)
            VALUES (?, ?, ?, ?)
        ');

        $stmt->execute([
            $product->getName(),
            $product->getUnit(),
            $product->getNettoPrice(),
            $product->getTaxPercent()
        ]);

        return $conn->lastInsertId();
    }

    public function addProducts(array $products,string $invoiceId, PDO $existingConn = null)
    {
        $conn = $existingConn == null ? Repository::connect() : $existingConn;
        $productsCount = count($products);
        $stmt = $conn->prepare($this->prepareQueryForManyProductsInsert($productsCount));

        $values = array();
        foreach ($products as $product){
            $tempValues = array(
                $product['product_name'],
                $product['unit'],
                $product['netto'],
                $product['percent']
            );
            $values = array_merge($values,$tempValues);
        }

        $stmt->execute($values);

        $idsQuantitiesPairs = array();
        $productsIds = array_values($stmt->fetchAll(PDO::FETCH_ASSOC));
        $products = array_values($products);
        for($i = 0; $i < $productsCount; $i++){
            $idsQuantitiesPairs[$productsIds[$i]['product_id']] = $products[$i]['quantity'];
        }

        $this->assignProductsToInvoices($idsQuantitiesPairs, $invoiceId, $conn);
    }

    private function prepareQueryForManyProductsInsert(int $productsQuantity): string
    {
        $query = "INSERT INTO PRODUCTS(name,unit,netto_price,tax_percent) VALUES ".str_repeat("(?,?,?,?),", $productsQuantity);;
        return substr_replace($query ,"",-1)." RETURNING product_id";
    }

    private function prepareQueryForManyProductsAssignToInvoice(int $productsCount): string
    {
        $query = "INSERT INTO INVOICES_PRODUCTS(invoice_id,product_id,quantity) VALUES ".str_repeat("(?,?,?),", $productsCount);;
        return substr_replace($query ,"",-1);
    }

    private function assignProductsToInvoices(array $idsQuantitiesPairs,string $invoiceId, PDO $existingConn = null)
    {
        $conn = $existingConn == null ? Repository::connect() : $existingConn;
        $productsCount = count($idsQuantitiesPairs);
        $stmt = $conn->prepare($this->prepareQueryForManyProductsAssignToInvoice($productsCount));

        $values = array();
        foreach ($idsQuantitiesPairs as $productId => $quantity){
            $tempValues = array(
                $invoiceId,
                $productId,
                $quantity
            );
            $values = array_merge($values,$tempValues);
        }

        $stmt->execute($values);
    }




}
