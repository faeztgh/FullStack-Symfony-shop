<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210821122146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_BA388B7A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart AS SELECT id, user_id, total_price, quantity, created_at, updated_at, created_user, updated_user FROM cart');
        $this->addSql('DROP TABLE cart');
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, total_price DOUBLE PRECISION DEFAULT NULL, quantity INTEGER DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_user VARCHAR(255) DEFAULT NULL COLLATE BINARY, updated_user VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO cart (id, user_id, total_price, quantity, created_at, updated_at, created_user, updated_user) SELECT id, user_id, total_price, quantity, created_at, updated_at, created_user, updated_user FROM __temp__cart');
        $this->addSql('DROP TABLE __temp__cart');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B7A76ED395 ON cart (user_id)');
        $this->addSql('DROP INDEX IDX_F0FE25274584665A');
        $this->addSql('DROP INDEX IDX_F0FE25271AD5CDBF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart_item AS SELECT id, product_id, cart_id, quantity, created_at, updated_at, total_price, created_user, updated_user, status, checked_out_date FROM cart_item');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('CREATE TABLE cart_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER NOT NULL, cart_id INTEGER DEFAULT NULL, quantity INTEGER NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, total_price DOUBLE PRECISION NOT NULL, created_user VARCHAR(255) DEFAULT NULL COLLATE BINARY, updated_user VARCHAR(255) DEFAULT NULL COLLATE BINARY, status VARCHAR(255) DEFAULT NULL COLLATE BINARY, checked_out_date DATETIME DEFAULT NULL, CONSTRAINT FK_F0FE25271AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F0FE25274584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO cart_item (id, product_id, cart_id, quantity, created_at, updated_at, total_price, created_user, updated_user, status, checked_out_date) SELECT id, product_id, cart_id, quantity, created_at, updated_at, total_price, created_user, updated_user, status, checked_out_date FROM __temp__cart_item');
        $this->addSql('DROP TABLE __temp__cart_item');
        $this->addSql('CREATE INDEX IDX_F0FE25274584665A ON cart_item (product_id)');
        $this->addSql('CREATE INDEX IDX_F0FE25271AD5CDBF ON cart_item (cart_id)');
        $this->addSql('DROP INDEX IDX_D34A04AD4C7C611F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, discount_id, name, brand, model, price, color, size, brief_description, description, created_at, updated_at, rate, created_user, updated_user, image, quantity, views FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, discount_id INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL COLLATE BINARY, brand VARCHAR(255) DEFAULT NULL COLLATE BINARY, model VARCHAR(255) DEFAULT NULL COLLATE BINARY, price DOUBLE PRECISION NOT NULL, color VARCHAR(255) DEFAULT NULL COLLATE BINARY, brief_description VARCHAR(255) DEFAULT NULL COLLATE BINARY, description CLOB DEFAULT NULL COLLATE BINARY, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, rate INTEGER DEFAULT NULL, created_user VARCHAR(255) DEFAULT NULL COLLATE BINARY, updated_user VARCHAR(255) DEFAULT NULL COLLATE BINARY, image VARCHAR(255) NOT NULL COLLATE BINARY, quantity INTEGER DEFAULT 0, views INTEGER DEFAULT 0, size INTEGER DEFAULT NULL, CONSTRAINT FK_D34A04AD4C7C611F FOREIGN KEY (discount_id) REFERENCES discount (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product (id, discount_id, name, brand, model, price, color, size, brief_description, description, created_at, updated_at, rate, created_user, updated_user, image, quantity, views) SELECT id, discount_id, name, brand, model, price, color, size, brief_description, description, created_at, updated_at, rate, created_user, updated_user, image, quantity, views FROM __temp__product');
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
        $this->addSql('DROP INDEX IDX_97A0ADA37E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ticket AS SELECT id, owner_id, subject, message, status, answer, created_at, updated_at, created_user, updated_user FROM ticket');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('CREATE TABLE ticket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, subject VARCHAR(255) NOT NULL COLLATE BINARY, message CLOB NOT NULL COLLATE BINARY, status VARCHAR(255) NOT NULL COLLATE BINARY, answer CLOB DEFAULT NULL COLLATE BINARY, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_user VARCHAR(255) DEFAULT NULL COLLATE BINARY, updated_user VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_97A0ADA37E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ticket (id, owner_id, subject, message, status, answer, created_at, updated_at, created_user, updated_user) SELECT id, owner_id, subject, message, status, answer, created_at, updated_at, created_user, updated_user FROM __temp__ticket');
        $this->addSql('DROP TABLE __temp__ticket');
        $this->addSql('CREATE INDEX IDX_97A0ADA37E3C61F9 ON ticket (owner_id)');
        $this->addSql('DROP INDEX IDX_8B471AA74584665A');
        $this->addSql('DROP INDEX IDX_8B471AA7A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_product AS SELECT user_id, product_id FROM user_product');
        $this->addSql('DROP TABLE user_product');
        $this->addSql('CREATE TABLE user_product (user_id INTEGER NOT NULL, product_id INTEGER NOT NULL, PRIMARY KEY(user_id, product_id), CONSTRAINT FK_8B471AA7A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_8B471AA74584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user_product (user_id, product_id) SELECT user_id, product_id FROM __temp__user_product');
        $this->addSql('DROP TABLE __temp__user_product');
        $this->addSql('CREATE INDEX IDX_8B471AA74584665A ON user_product (product_id)');
        $this->addSql('CREATE INDEX IDX_8B471AA7A76ED395 ON user_product (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_BA388B7A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart AS SELECT id, user_id, total_price, quantity, created_at, updated_at, created_user, updated_user FROM cart');
        $this->addSql('DROP TABLE cart');
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, total_price DOUBLE PRECISION DEFAULT NULL, quantity INTEGER DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_user VARCHAR(255) DEFAULT NULL, updated_user VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO cart (id, user_id, total_price, quantity, created_at, updated_at, created_user, updated_user) SELECT id, user_id, total_price, quantity, created_at, updated_at, created_user, updated_user FROM __temp__cart');
        $this->addSql('DROP TABLE __temp__cart');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B7A76ED395 ON cart (user_id)');
        $this->addSql('DROP INDEX IDX_F0FE25271AD5CDBF');
        $this->addSql('DROP INDEX IDX_F0FE25274584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart_item AS SELECT id, cart_id, product_id, quantity, total_price, created_at, updated_at, status, checked_out_date, created_user, updated_user FROM cart_item');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('CREATE TABLE cart_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cart_id INTEGER DEFAULT NULL, product_id INTEGER NOT NULL, quantity INTEGER NOT NULL, total_price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, status VARCHAR(255) DEFAULT NULL, checked_out_date DATETIME DEFAULT NULL, created_user VARCHAR(255) DEFAULT NULL, updated_user VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO cart_item (id, cart_id, product_id, quantity, total_price, created_at, updated_at, status, checked_out_date, created_user, updated_user) SELECT id, cart_id, product_id, quantity, total_price, created_at, updated_at, status, checked_out_date, created_user, updated_user FROM __temp__cart_item');
        $this->addSql('DROP TABLE __temp__cart_item');
        $this->addSql('CREATE INDEX IDX_F0FE25271AD5CDBF ON cart_item (cart_id)');
        $this->addSql('CREATE INDEX IDX_F0FE25274584665A ON cart_item (product_id)');
        $this->addSql('DROP INDEX IDX_D34A04AD4C7C611F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, discount_id, name, brand, model, price, color, size, brief_description, description, created_at, updated_at, rate, image, views, quantity, created_user, updated_user FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, discount_id INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, brand VARCHAR(255) DEFAULT NULL, model VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, color VARCHAR(255) DEFAULT NULL, brief_description VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, rate INTEGER DEFAULT NULL, image VARCHAR(255) NOT NULL, views INTEGER DEFAULT 0, quantity INTEGER DEFAULT 0, created_user VARCHAR(255) DEFAULT NULL, updated_user VARCHAR(255) DEFAULT NULL, size DOUBLE PRECISION DEFAULT NULL)');
        $this->addSql('INSERT INTO product (id, discount_id, name, brand, model, price, color, size, brief_description, description, created_at, updated_at, rate, image, views, quantity, created_user, updated_user) SELECT id, discount_id, name, brand, model, price, color, size, brief_description, description, created_at, updated_at, rate, image, views, quantity, created_user, updated_user FROM __temp__product');
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
        $this->addSql('DROP INDEX IDX_97A0ADA37E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ticket AS SELECT id, owner_id, subject, message, status, answer, created_at, updated_at, created_user, updated_user FROM ticket');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('CREATE TABLE ticket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, subject VARCHAR(255) NOT NULL, message CLOB NOT NULL, status VARCHAR(255) NOT NULL, answer CLOB DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_user VARCHAR(255) DEFAULT NULL, updated_user VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO ticket (id, owner_id, subject, message, status, answer, created_at, updated_at, created_user, updated_user) SELECT id, owner_id, subject, message, status, answer, created_at, updated_at, created_user, updated_user FROM __temp__ticket');
        $this->addSql('DROP TABLE __temp__ticket');
        $this->addSql('CREATE INDEX IDX_97A0ADA37E3C61F9 ON ticket (owner_id)');
        $this->addSql('DROP INDEX IDX_8B471AA7A76ED395');
        $this->addSql('DROP INDEX IDX_8B471AA74584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_product AS SELECT user_id, product_id FROM user_product');
        $this->addSql('DROP TABLE user_product');
        $this->addSql('CREATE TABLE user_product (user_id INTEGER NOT NULL, product_id INTEGER NOT NULL, PRIMARY KEY(user_id, product_id))');
        $this->addSql('INSERT INTO user_product (user_id, product_id) SELECT user_id, product_id FROM __temp__user_product');
        $this->addSql('DROP TABLE __temp__user_product');
        $this->addSql('CREATE INDEX IDX_8B471AA7A76ED395 ON user_product (user_id)');
        $this->addSql('CREATE INDEX IDX_8B471AA74584665A ON user_product (product_id)');
    }
}
