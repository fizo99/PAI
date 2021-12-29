<?php

require_once 'Repository.php';
require_once 'AddressExistsException.php';
require_once __DIR__ . '/../models/Address.php';

class AddressRepository
{
    public function addAddress(Address $address, PDO $existingConn = null): string
    {
        // TODO: handle errors
        $conn = $existingConn == null ? Repository::connect() : $existingConn;
//        $address = $this->getAddress($user->getEmail());
//        if ($address) {
//            throw new AddressExistsException("Address exists.");
//        }

        $stmt = $conn->prepare('
            INSERT INTO addresses (city,zip_code,street_name,street_nr)
            VALUES (?, ?, ?, ?)
        ');

        $stmt->execute([
            $address->getCity(),
            $address->getZipCode(),
            $address->getStreetName(),
            $address->getStreetNumber(),
        ]);

        return $conn->lastInsertId();
    }
}
