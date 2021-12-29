<?php

require_once 'Repository.php';
require_once 'CompanyExistsException.php';
require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../models/Address.php';

class CompanyRepository extends Repository
{

    public function getCompany(string $nip, PDO $existingConn = null): ?Company
    {
        $conn = $existingConn == null ? $this->connectRepository() : $existingConn;

        $stmt = $conn->prepare('
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

    public function addCompany(Company $company, string $addressId, PDO $existingConn = null) : string
    {
        // TODO: handle errors
        $conn = $existingConn == null ? $this->connectRepository() : $existingConn;

        $existingCompany = $this->getCompany($company->getNIP());
        if ($existingCompany) {
            throw new CompanyExistsException("Company with NIP ". $company->getNIP() . " already exists");
        }

        $stmtCompany = $conn->prepare('
            INSERT INTO companies (nip,name,email,phone_number,iban,address_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ');

        $stmtCompany->execute([
            $company->getNIP(),
            $company->getName(),
            $company->getEmail(),
            $company->getPhoneNumber(),
            $company->getIBAN(),
            $addressId
        ]);

        return $company->getNIP();
    }
}
