<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231123220812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book_to_book_category (book_id INT NOT NULL, book_category_id INT NOT NULL, PRIMARY KEY(book_id, book_category_id))');
        $this->addSql('CREATE INDEX IDX_57511BE216A2B381 ON book_to_book_category (book_id)');
        $this->addSql('CREATE INDEX IDX_57511BE240B1D29E ON book_to_book_category (book_category_id)');
        $this->addSql('ALTER TABLE book_to_book_category ADD CONSTRAINT FK_57511BE216A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_to_book_category ADD CONSTRAINT FK_57511BE240B1D29E FOREIGN KEY (book_category_id) REFERENCES book_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_book_category DROP CONSTRAINT fk_7a5a379416a2b381');
        $this->addSql('ALTER TABLE book_book_category DROP CONSTRAINT fk_7a5a379440b1d29e');
        $this->addSql('DROP TABLE book_book_category');
        $this->addSql('ALTER TABLE book DROP meap');
        $this->addSql('ALTER TABLE book ALTER slug TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE book ALTER image DROP NOT NULL');
        $this->addSql('ALTER TABLE book ALTER image TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE book ALTER authors DROP NOT NULL');
        $this->addSql('ALTER TABLE book ALTER publication_date DROP NOT NULL');
        $this->addSql('ALTER TABLE book ALTER isbn TYPE VARCHAR(13)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE book_book_category (book_id INT NOT NULL, book_category_id INT NOT NULL, PRIMARY KEY(book_id, book_category_id))');
        $this->addSql('CREATE INDEX idx_7a5a379440b1d29e ON book_book_category (book_category_id)');
        $this->addSql('CREATE INDEX idx_7a5a379416a2b381 ON book_book_category (book_id)');
        $this->addSql('ALTER TABLE book_book_category ADD CONSTRAINT fk_7a5a379416a2b381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_book_category ADD CONSTRAINT fk_7a5a379440b1d29e FOREIGN KEY (book_category_id) REFERENCES book_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_to_book_category DROP CONSTRAINT FK_57511BE216A2B381');
        $this->addSql('ALTER TABLE book_to_book_category DROP CONSTRAINT FK_57511BE240B1D29E');
        $this->addSql('DROP TABLE book_to_book_category');
        $this->addSql('ALTER TABLE book ADD meap BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE book ALTER slug TYPE VARCHAR(125)');
        $this->addSql('ALTER TABLE book ALTER image SET NOT NULL');
        $this->addSql('ALTER TABLE book ALTER image TYPE VARCHAR(125)');
        $this->addSql('ALTER TABLE book ALTER authors SET NOT NULL');
        $this->addSql('ALTER TABLE book ALTER isbn TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE book ALTER publication_date SET NOT NULL');
    }
}
