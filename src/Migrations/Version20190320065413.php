<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190320065413 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ordered_items ADD discount_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ordered_items ADD CONSTRAINT FK_DA06F5044C7C611F FOREIGN KEY (discount_id) REFERENCES coupon (id)');
        $this->addSql('CREATE INDEX IDX_DA06F5044C7C611F ON ordered_items (discount_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ordered_items DROP FOREIGN KEY FK_DA06F5044C7C611F');
        $this->addSql('DROP INDEX IDX_DA06F5044C7C611F ON ordered_items');
        $this->addSql('ALTER TABLE ordered_items DROP discount_id');
    }
}
