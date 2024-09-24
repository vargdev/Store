<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\Category;

class CategoryUpdateDTO
{
    public ?string $name = null;

    public function updateCategory(Category $category): Category
    {
        if ($this->name !== null) {
            $category->setName($this->name);
        }

        return $category;
    }
}