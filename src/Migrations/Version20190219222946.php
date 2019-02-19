<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190219222946 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADC33F2EBA');
        $this->addSql('DROP INDEX IDX_D34A04ADC33F2EBA ON product');
        $this->addSql('ALTER TABLE product ADD sku VARCHAR(255) NOT NULL, CHANGE cat_id_id categories_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA21214B7 FOREIGN KEY (categories_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADA21214B7 ON product (categories_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA21214B7');
        $this->addSql('DROP INDEX IDX_D34A04ADA21214B7 ON product');
        $this->addSql('ALTER TABLE product DROP sku, CHANGE categories_id cat_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADC33F2EBA FOREIGN KEY (cat_id_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADC33F2EBA ON product (cat_id_id)');
    }
}
