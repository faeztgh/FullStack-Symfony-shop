<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210709160431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, session_id INTEGER DEFAULT NULL, product_id INTEGER NOT NULL, quantity INTEGER NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_user VARCHAR(255) DEFAULT NULL, updated_user VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_F0FE2527613FECDF ON cart_item (session_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F0FE25274584665A ON cart_item (product_id)');
        $this->addSql('CREATE TABLE contact (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, full_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, message CLOB NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE discount (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, discount_percent NUMERIC(10, 0) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_user VARCHAR(255) DEFAULT NULL, updated_user VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE order_details (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, payment_id INTEGER DEFAULT NULL, total INTEGER NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_user VARCHAR(255) DEFAULT NULL, updated_user VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_845CA2C1A76ED395 ON order_details (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_845CA2C14C3A3BB ON order_details (payment_id)');
        $this->addSql('CREATE TABLE order_items (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, order_id_id INTEGER NOT NULL, product_id INTEGER NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_user VARCHAR(255) DEFAULT NULL, updated_user VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_62809DB0FCDAEAAA ON order_items (order_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62809DB04584665A ON order_items (product_id)');
        $this->addSql('CREATE TABLE payment_details (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, amount INTEGER NOT NULL, provider VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_user VARCHAR(255) DEFAULT NULL, updated_user VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, discount_id INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, brand VARCHAR(255) DEFAULT NULL, model VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, color VARCHAR(255) DEFAULT NULL, size DOUBLE PRECISION DEFAULT NULL, brief_description VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, rate INTEGER DEFAULT NULL, thumbnail VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, created_user VARCHAR(255) DEFAULT NULL, updated_user VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_D34A04AD4C7C611F ON product (discount_id)');
        $this->addSql('CREATE TABLE reset_password_request (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , expires_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('CREATE TABLE shopping_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, created_user VARCHAR(255) DEFAULT NULL, updated_user VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CECE98A6A76ED395 ON shopping_session (user_id)');
        $this->addSql('CREATE TABLE "user" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone_no VARCHAR(20) NOT NULL, username VARCHAR(180) NOT NULL, email VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE discount');
        $this->addSql('DROP TABLE order_details');
        $this->addSql('DROP TABLE order_items');
        $this->addSql('DROP TABLE payment_details');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE shopping_session');
        $this->addSql('DROP TABLE "user"');
    }
}
