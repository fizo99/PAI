<?php

require_once 'Repository.php';
require_once 'CompanyExistsException.php';
require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../models/Address.php';

class CompanyRepository extends Repository
{

    public function getCompany(string $nip): ?Company
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM companies
            inner join addresses on companies.address_id=addresses.address_id 
            where nip = :nip
        ');
        $stmt->bindParam(":nip", $nip);
        $stmt->execute();

        $company = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($company == false) {
            return null;
        }
        return new Company(
            $company['nip'],
            $company['name'],
            $company['email'],
            $company['phone_number'],
            $company['iban']
        );
    }

    public function addCompany(Company $company, Address $address)
    {
        $existingCompany = $this->getCompany($company->getNIP());
        if ($existingCompany) {
            throw new CompanyExistsException("Company with NIP ". $company->getNIP() . " already exists");
        }

        //TODO: think about separate repository for address

        $connection = $this->database->connect();
        $stmtAddress = $connection->prepare('
            INSERT INTO addresses (city,zip_code,street_name,street_nr)
            VALUES (?, ?, ?, ?)
        ');
        $stmtCompany = $connection->prepare('
            INSERT INTO companies (nip,name,email,phone_number,iban,address_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ');

        $connection->beginTransaction();
        $stmtAddress->execute([
            $address->getCity(),
            $address->getZipCode(),
            $address->getStreetName(),
            $address->getStreetNumber(),
        ]);
        $id = $connection->lastInsertId();
        $stmtCompany->execute([
            $company->getNIP(),
            $company->getName(),
            $company->getEmail(),
            $company->getPhoneNumber(),
            $company->getIBAN(),
            $id
        ]);
        $connection->commit();
    }
}
