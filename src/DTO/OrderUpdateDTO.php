<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\Customer;
use App\Entity\Order;
use DateTime;
use phpDocumentor\Reflection\Types\Collection;

class OrderUpdateDTO
{
    public ?Customer $customer = null;
    public ?DateTime $orderDate = null;
    public ?float $totalPrice = null;
    public ?string $status = null;
    public ?Collection $products = null;

    public function updateOrder(Order $order): Order
    {
        if ($this->customer !== null) {
            $order->setCustomer($this->customer);
        }
        if ($this->orderDate !== null) {
            $order->setOrderDate($this->orderDate);
        }
        if ($this->totalPrice !== null) {
            $order->setTotalPrice($this->totalPrice);
        }
        if ($this->status !== null) {
            $order->setStatus($this->status);
        }
        if ($this->products !== null) {
            foreach ($this->products as $product) {
                $order->addProduct($product);
            }
        }

        return $order;
    }
}