<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240924084524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fill the tables with test data';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO category (id, name) VALUES 
        (1, 'Electronics'),
        (2, 'Books'),
        (3, 'Clothing')");

        $this->addSql("INSERT INTO customer (id, first_name, last_name, email, address) VALUES 
        (1, 'John', 'Doe', 'john@example.com', 'Long street 12'), 
        (2, 'Jane', 'Smith', 'jane@example.com', 'Short street 12')");

        $this->addSql("INSERT INTO product (id, category_id, name, description, price) VALUES 
        (1, 1, 'Laptop', 'Description', 999.99),
        (2, 2, 'Novel', 'Description', 19.99),
        (3, 3, 'T-Shirt', 'Description', 29.99)");

        $this->addSql('INSERT INTO "order" (id, customer_id, order_date, total_price, status) VALUES 
        (1, 1, \'2024-09-22\', 1019.98, \'SHIPPED\'),
        (2, 2, \'2024-09-24\', 29.99, \'PENDING\')');

        $this->addSql("INSERT INTO order_product (order_id, product_id) VALUES 
        (1, 1),
        (1, 2),
        (2, 3)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE order_customer');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE category');
    }
}
