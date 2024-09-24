<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\Category;
use App\Entity\Product;

class ProductUpdateDTO
{
    public ?string $name = null;
    public ?string $description = null;
    public ?float $price = null;
    public ?Category $category = null;

    public function updateProduct(Product $product): Product
    {
        if ($this->name !== null) {
            $product->setName($this->name);
        }
        if ($this->description !== null) {
            $product->setDescription($this->description);
        }
        if ($this->price !== null) {
            $product->setPrice($this->price);
        }
        if ($this->category !== null) {
            $product->setCategory($this->category);
        }

        return $product;
    }
}