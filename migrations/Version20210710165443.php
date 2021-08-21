<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210710165443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_BA388B7A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart AS SELECT id, user_id, total_price, quantity, created_at, updated_at FROM cart');
        $this->addSql('DROP TABLE cart');
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, total_price DOUBLE PRECISION DEFAULT NULL, quantity INTEGER DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_user VARCHAR(255) DEFAULT NULL, updated_user VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO cart (id, user_id, total_price, quantity, created_at, updated_at) SELECT id, user_id, total_price, quantity, created_at, updated_at FROM __temp__cart');
        $this->addSql('DROP TABLE __temp__cart');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B7A76ED395 ON cart (user_id)');
        $this->addSql('DROP INDEX IDX_F0FE25271AD5CDBF');
        $this->addSql('DROP INDEX UNIQ_F0FE25274584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart_item AS SELECT id, product_id, cart_id, quantity, created_at, updated_at, total_price, created_user, updated_user FROM cart_item');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('CREATE TABLE cart_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER NOT NULL, cart_id INTEGER DEFAULT NULL, quantity INTEGER NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, total_price DOUBLE PRECISION NOT NULL, created_user VARCHAR(255) DEFAULT NULL COLLATE BINARY, updated_user VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_F0FE25274584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F0FE25271AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO cart_item (id, product_id, cart_id, quantity, created_at, updated_at, total_price, created_user, updated_user) SELECT id, product_id, cart_id, quantity, created_at, updated_at, total_price, created_user, updated_user FROM __temp__cart_item');
        $this->addSql('DROP TABLE __temp__cart_item');
        $this->addSql('CREATE INDEX IDX_F0FE25271AD5CDBF ON cart_item (cart_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F0FE25274584665A ON cart_item (product_id)');
        $this->addSql('DROP INDEX UNIQ_845CA2C14C3A3BB');
        $this->addSql('DROP INDEX UNIQ_845CA2C1A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__order_details AS SELECT id, user_id, payment_id, total, created_at, updated_at, created_user, updated_user FROM order_details');
        $this->addSql('DROP TABLE order_details');
        $this->addSql('CREATE TABLE order_details (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, payment_id INTEGER DEFAULT NULL, total INTEGER NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_user VARCHAR(255) DEFAULT NULL COLLATE BINARY, updated_user VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_845CA2C1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_845CA2C14C3A3BB FOREIGN KEY (payment_id) REFERENCES payment_details (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO order_details (id, user_id, payment_id, total, created_at, updated_at, created_user, updated_user) SELECT id, user_id, payment_id, total, created_at, updated_at, created_user, updated_user FROM __temp__order_details');
        $this->addSql('DROP TABLE __temp__order_details');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_845CA2C14C3A3BB ON order_details (payment_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_845CA2C1A76ED395 ON order_details (user_id)');
        $this->addSql('DROP INDEX UNIQ_62809DB04584665A');
        $this->addSql('DROP INDEX IDX_62809DB0FCDAEAAA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__order_items AS SELECT id, order_id_id, product_id, created_at, updated_at, created_user, updated_user FROM order_items');
        $this->addSql('DROP TABLE order_items');
        $this->addSql('CREATE TABLE order_items (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, order_id_id INTEGER NOT NULL, product_id INTEGER NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_user VARCHAR(255) DEFAULT NULL COLLATE BINARY, updated_user VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_62809DB0FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES order_details (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_62809DB04584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO order_items (id, order_id_id, product_id, created_at, updated_at, created_user, updated_user) SELECT id, order_id_id, product_id, created_at, updated_at, created_user, updated_user FROM __temp__order_items');
        $this->addSql('DROP TABLE __temp__order_items');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62809DB04584665A ON order_items (product_id)');
        $this->addSql('CREATE INDEX IDX_62809DB0FCDAEAAA ON order_items (order_id_id)');
        $this->addSql('DROP INDEX IDX_D34A04AD4C7C611F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, discount_id, name, brand, model, price, color, size, brief_description, description, created_at, updated_at, rate, thumbnail, image, created_user, updated_user FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, discount_id INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL COLLATE BINARY, brand VARCHAR(255) DEFAULT NULL COLLATE BINARY, model VARCHAR(255) DEFAULT NULL COLLATE BINARY, price DOUBLE PRECISION NOT NULL, color VARCHAR(255) DEFAULT NULL COLLATE BINARY, size DOUBLE PRECISION DEFAULT NULL, brief_description VARCHAR(255) DEFAULT NULL COLLATE BINARY, description CLOB DEFAULT NULL COLLATE BINARY, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, rate INTEGER DEFAULT NULL, thumbnail VARCHAR(255) DEFAULT NULL COLLATE BINARY, image VARCHAR(255) DEFAULT NULL COLLATE BINARY, created_user VARCHAR(255) DEFAULT NULL COLLATE BINARY, updated_user VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_D34A04AD4C7C611F FOREIGN KEY (discount_id) REFERENCES discount (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product (id, discount_id, name, brand, model, price, color, size, brief_description, description, created_at, updated_at, rate, thumbnail, image, created_user, updated_user) SELECT id, discount_id, name, brand, model, price, color, size, brief_description, description, created_at, updated_at, rate, thumbnail, image, created_user, updated_user FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE INDEX IDX_D34A04AD4C7C611F ON product (discount_id)');
        $this->addSql('DROP INDEX IDX_7CE748AA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reset_password_request AS SELECT id, user_id, selector, hashed_token, requested_at, expires_at FROM reset_password_request');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('CREATE TABLE reset_password_request (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, selector VARCHAR(20) NOT NULL COLLATE BINARY, hashed_token VARCHAR(100) NOT NULL COLLATE BINARY, requested_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , expires_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO reset_password_request (id, user_id, selector, hashed_token, requested_at, expires_at) SELECT id, user_id, selector, hashed_token, requested_at, expires_at FROM __temp__reset_password_request');
        $this->addSql('DROP TABLE __temp__reset_password_request');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_BA388B7A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart AS SELECT id, user_id, total_price, quantity, created_at, updated_at FROM cart');
        $this->addSql('DROP TABLE cart');
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, total_price DOUBLE PRECISION DEFAULT NULL, quantity INTEGER DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO cart (id, user_id, total_price, quantity, created_at, updated_at) SELECT id, user_id, total_price, quantity, created_at, updated_at FROM __temp__cart');
        $this->addSql('DROP TABLE __temp__cart');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B7A76ED395 ON cart (user_id)');
        $this->addSql('DROP INDEX UNIQ_F0FE25274584665A');
        $this->addSql('DROP INDEX IDX_F0FE25271AD5CDBF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart_item AS SELECT id, product_id, cart_id, quantity, created_at, updated_at, total_price, created_user, updated_user FROM cart_item');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('CREATE TABLE cart_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER NOT NULL, cart_id INTEGER DEFAULT NULL, quantity INTEGER NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, total_price DOUBLE PRECISION NOT NULL, created_user VARCHAR(255) DEFAULT NULL, updated_user VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO cart_item (id, product_id, cart_id, quantity, created_at, updated_at, total_price, created_user, updated_user) SELECT id, product_id, cart_id, quantity, created_at, updated_at, total_price, created_user, updated_user FROM __temp__cart_item');
        $this->addSql('DROP TABLE __temp__cart_item');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F0FE25274584665A ON cart_item (product_id)');
        $this->addSql('CREATE INDEX IDX_F0FE25271AD5CDBF ON cart_item (cart_id)');
        $this->addSql('DROP INDEX UNIQ_845CA2C1A76ED395');
        $this->addSql('DROP INDEX UNIQ_845CA2C14C3A3BB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__order_details AS SELECT id, user_id, payment_id, total, created_at, updated_at, created_user, updated_user FROM order_details');
        $this->addSql('DROP TABLE order_details');
        $this->addSql('CREATE TABLE order_details (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, payment_id INTEGER DEFAULT NULL, total INTEGER NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_user VARCHAR(255) DEFAULT NULL, updated_user VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO order_details (id, user_id, payment_id, total, created_at, updated_at, created_user, updated_user) SELECT id, user_id, payment_id, total, created_at, updated_at, created_user, updated_user FROM __temp__order_details');
        $this->addSql('DROP TABLE __temp__order_details');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_845CA2C1A76ED395 ON order_details (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_845CA2C14C3A3BB ON order_details (payment_id)');
        $this->addSql('DROP INDEX IDX_62809DB0FCDAEAAA');
        $this->addSql('DROP INDEX UNIQ_62809DB04584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__order_items AS SELECT id, order_id_id, product_id, created_at, updated_at, created_user, updated_user FROM order_items');
        $this->addSql('DROP TABLE order_items');
        $this->addSql('CREATE TABLE order_items (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, order_id_id INTEGER NOT NULL, product_id INTEGER NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_user VARCHAR(255) DEFAULT NULL, updated_user VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO order_items (id, order_id_id, product_id, created_at, updated_at, created_user, updated_user) SELECT id, order_id_id, product_id, created_at, updated_at, created_user, updated_user FROM __temp__order_items');
        $this->addSql('DROP TABLE __temp__order_items');
        $this->addSql('CREATE INDEX IDX_62809DB0FCDAEAAA ON order_items (order_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62809DB04584665A ON order_items (product_id)');
        $this->addSql('DROP INDEX IDX_D34A04AD4C7C611F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, discount_id, name, brand, model, price, color, size, brief_description, description, created_at, updated_at, rate, thumbnail, image, created_user, updated_user FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, discount_id INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, brand VARCHAR(255) DEFAULT NULL, model VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, color VARCHAR(255) DEFAULT NULL, size DOUBLE PRECISION DEFAULT NULL, brief_description VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, rate INTEGER DEFAULT NULL, thumbnail VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, created_user VARCHAR(255) DEFAULT NULL, updated_user VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO product (id, discount_id, name, brand, model, price, color, size, brief_description, description, created_at, updated_at, rate, thumbnail, image, created_user, updated_user) SELECT id, discount_id, name, brand, model, price, color, size, brief_description, description, created_at, updated_at, rate, thumbnail, image, created_user, updated_user FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE INDEX IDX_D34A04AD4C7C611F ON product (discount_id)');
        $this->addSql('DROP INDEX IDX_7CE748AA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reset_password_request AS SELECT id, user_id, selector, hashed_token, requested_at, expires_at FROM reset_password_request');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('CREATE TABLE reset_password_request (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , expires_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO reset_password_request (id, user_id, selector, hashed_token, requested_at, expires_at) SELECT id, user_id, selector, hashed_token, requested_at, expires_at FROM __temp__reset_password_request');
        $this->addSql('DROP TABLE __temp__reset_password_request');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
    }
}
