<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260109093826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredients (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, quantity NUMERIC(10, 2) NOT NULL, unit VARCHAR(50) NOT NULL, deleted_at DATETIME DEFAULT NULL, recipe_id INT NOT NULL, INDEX IDX_4B60114F59D8A214 (recipe_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE nutrient_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, unit VARCHAR(50) NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE ratings (id INT AUTO_INCREMENT NOT NULL, rate INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, deleted_at DATETIME DEFAULT NULL, ip VARCHAR(45) NOT NULL, recipe_id INT NOT NULL, INDEX IDX_CEB607C959D8A214 (recipe_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE recipe_nutrients (id INT AUTO_INCREMENT NOT NULL, quantity NUMERIC(10, 2) NOT NULL, deleted_at DATETIME DEFAULT NULL, recipe_id INT NOT NULL, nutrient_type_id INT NOT NULL, INDEX IDX_87D8CF2B59D8A214 (recipe_id), INDEX IDX_87D8CF2BCBF7D9B1 (nutrient_type_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE recipe_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE recipes (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, number_diner INT NOT NULL, deleted_at DATETIME DEFAULT NULL, recipe_type_id INT NOT NULL, INDEX IDX_A369E2B589A882D3 (recipe_type_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE steps (id INT AUTO_INCREMENT NOT NULL, order_step INT NOT NULL, description LONGTEXT NOT NULL, deleted_at DATETIME DEFAULT NULL, recipe_id INT NOT NULL, INDEX IDX_34220A7259D8A214 (recipe_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE ingredients ADD CONSTRAINT FK_4B60114F59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipes (id)');
        $this->addSql('ALTER TABLE ratings ADD CONSTRAINT FK_CEB607C959D8A214 FOREIGN KEY (recipe_id) REFERENCES recipes (id)');
        $this->addSql('ALTER TABLE recipe_nutrients ADD CONSTRAINT FK_87D8CF2B59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipes (id)');
        $this->addSql('ALTER TABLE recipe_nutrients ADD CONSTRAINT FK_87D8CF2BCBF7D9B1 FOREIGN KEY (nutrient_type_id) REFERENCES nutrient_types (id)');
        $this->addSql('ALTER TABLE recipes ADD CONSTRAINT FK_A369E2B589A882D3 FOREIGN KEY (recipe_type_id) REFERENCES recipe_types (id)');
        $this->addSql('ALTER TABLE steps ADD CONSTRAINT FK_34220A7259D8A214 FOREIGN KEY (recipe_id) REFERENCES recipes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredients DROP FOREIGN KEY FK_4B60114F59D8A214');
        $this->addSql('ALTER TABLE ratings DROP FOREIGN KEY FK_CEB607C959D8A214');
        $this->addSql('ALTER TABLE recipe_nutrients DROP FOREIGN KEY FK_87D8CF2B59D8A214');
        $this->addSql('ALTER TABLE recipe_nutrients DROP FOREIGN KEY FK_87D8CF2BCBF7D9B1');
        $this->addSql('ALTER TABLE recipes DROP FOREIGN KEY FK_A369E2B589A882D3');
        $this->addSql('ALTER TABLE steps DROP FOREIGN KEY FK_34220A7259D8A214');
        $this->addSql('DROP TABLE ingredients');
        $this->addSql('DROP TABLE nutrient_types');
        $this->addSql('DROP TABLE ratings');
        $this->addSql('DROP TABLE recipe_nutrients');
        $this->addSql('DROP TABLE recipe_types');
        $this->addSql('DROP TABLE recipes');
        $this->addSql('DROP TABLE steps');
    }
}
