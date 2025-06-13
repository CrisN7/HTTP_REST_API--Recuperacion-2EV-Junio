<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250613072119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, pizza_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_6BAF7870D41D1D42 (pizza_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, delivery_time VARCHAR(255) NOT NULL, delivery_address VARCHAR(255) NOT NULL, payment_type VARCHAR(255) NOT NULL, number VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE pizza (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, ok_celiacs TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE pizza_order (id INT AUTO_INCREMENT NOT NULL, order_ref_id INT DEFAULT NULL, pizza_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_3589140E238517C (order_ref_id), INDEX IDX_3589140D41D1D42 (pizza_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF7870D41D1D42 FOREIGN KEY (pizza_id) REFERENCES pizza (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pizza_order ADD CONSTRAINT FK_3589140E238517C FOREIGN KEY (order_ref_id) REFERENCES `order` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pizza_order ADD CONSTRAINT FK_3589140D41D1D42 FOREIGN KEY (pizza_id) REFERENCES pizza (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF7870D41D1D42
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pizza_order DROP FOREIGN KEY FK_3589140E238517C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pizza_order DROP FOREIGN KEY FK_3589140D41D1D42
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ingredient
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `order`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE pizza
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE pizza_order
        SQL);
    }
}
