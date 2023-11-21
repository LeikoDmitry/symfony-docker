<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231121201556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE book_format_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE book_relation_to_book_format_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE book_format (id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, comment VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE book_relation_to_book_format (id INT NOT NULL, book_id INT NOT NULL, format_id INT NOT NULL, price NUMERIC(10, 2) NOT NULL, discount_percent INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B42829B916A2B381 ON book_relation_to_book_format (book_id)');
        $this->addSql('CREATE INDEX IDX_B42829B9D629F605 ON book_relation_to_book_format (format_id)');
        $this->addSql('CREATE TABLE review (id INT NOT NULL, rating INT DEFAULT NULL, content TEXT DEFAULT NULL, author VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN review.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE review_book (review_id INT NOT NULL, book_id INT NOT NULL, PRIMARY KEY(review_id, book_id))');
        $this->addSql('CREATE INDEX IDX_2951EE293E2E969B ON review_book (review_id)');
        $this->addSql('CREATE INDEX IDX_2951EE2916A2B381 ON review_book (book_id)');
        $this->addSql('ALTER TABLE book_relation_to_book_format ADD CONSTRAINT FK_B42829B916A2B381 FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_relation_to_book_format ADD CONSTRAINT FK_B42829B9D629F605 FOREIGN KEY (format_id) REFERENCES book_format (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review_book ADD CONSTRAINT FK_2951EE293E2E969B FOREIGN KEY (review_id) REFERENCES review (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review_book ADD CONSTRAINT FK_2951EE2916A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book ADD isbn VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE book ALTER publication_date TYPE DATE');
        $this->addSql('COMMENT ON COLUMN book.publication_date IS \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE book_format_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE book_relation_to_book_format_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE review_id_seq CASCADE');
        $this->addSql('ALTER TABLE book_relation_to_book_format DROP CONSTRAINT FK_B42829B916A2B381');
        $this->addSql('ALTER TABLE book_relation_to_book_format DROP CONSTRAINT FK_B42829B9D629F605');
        $this->addSql('ALTER TABLE review_book DROP CONSTRAINT FK_2951EE293E2E969B');
        $this->addSql('ALTER TABLE review_book DROP CONSTRAINT FK_2951EE2916A2B381');
        $this->addSql('DROP TABLE book_format');
        $this->addSql('DROP TABLE book_relation_to_book_format');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE review_book');
        $this->addSql('ALTER TABLE book DROP isbn');
        $this->addSql('ALTER TABLE book DROP description');
        $this->addSql('ALTER TABLE book ALTER publication_date TYPE DATE');
        $this->addSql('COMMENT ON COLUMN book.publication_date IS NULL');
    }
}
