<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190224120235 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shopcard DROP FOREIGN KEY FK_C88451178D9F6D38');
        $this->addSql('DROP INDEX IDX_C88451178D9F6D38 ON shopcard');
        $this->addSql('ALTER TABLE shopcard CHANGE order_id ordereditems_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shopcard ADD CONSTRAINT FK_C88451172A42DCE2 FOREIGN KEY (ordereditems_id) REFERENCES ordered_items (id)');
        $this->addSql('CREATE INDEX IDX_C88451172A42DCE2 ON shopcard (ordereditems_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shopcard DROP FOREIGN KEY FK_C88451172A42DCE2');
        $this->addSql('DROP INDEX IDX_C88451172A42DCE2 ON shopcard');
        $this->addSql('ALTER TABLE shopcard CHANGE ordereditems_id order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shopcard ADD CONSTRAINT FK_C88451178D9F6D38 FOREIGN KEY (order_id) REFERENCES ordered_items (id)');
        $this->addSql('CREATE INDEX IDX_C88451178D9F6D38 ON shopcard (order_id)');
    }
}
