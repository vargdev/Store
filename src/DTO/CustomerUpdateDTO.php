<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\Customer;

class CustomerUpdateDTO
{
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?string $email = null;
    public ?string $address = null;

    public function updateCustomer(Customer $customer): Customer
    {
        if ($this->firstName !== null) {
            $customer->setFirstName($this->firstName);
        }
        if ($this->lastName !== null) {
            $customer->setLastName($this->lastName);
        }
        if ($this->email !== null) {
            $customer->setEmail($this->email);
        }
        if ($this->address !== null) {
            $customer->setAddress($this->address);
        }

        return $customer;
    }
}