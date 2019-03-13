<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190313103300 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC73566C8A81A9');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC7356A21214B7');
        $this->addSql('DROP INDEX IDX_CDFC7356A21214B7 ON product_category');
        $this->addSql('DROP INDEX IDX_CDFC73566C8A81A9 ON product_category');
        $this->addSql('ALTER TABLE product_category ADD category_id INT DEFAULT NULL, ADD product_id INT DEFAULT NULL, DROP categories_id, DROP products_id');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC735612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC73564584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_CDFC735612469DE2 ON product_category (category_id)');
        $this->addSql('CREATE INDEX IDX_CDFC73564584665A ON product_category (product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC735612469DE2');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC73564584665A');
        $this->addSql('DROP INDEX IDX_CDFC735612469DE2 ON product_category');
        $this->addSql('DROP INDEX IDX_CDFC73564584665A ON product_category');
        $this->addSql('ALTER TABLE product_category ADD categories_id INT DEFAULT NULL, ADD products_id INT DEFAULT NULL, DROP category_id, DROP product_id');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC73566C8A81A9 FOREIGN KEY (products_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC7356A21214B7 FOREIGN KEY (categories_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_CDFC7356A21214B7 ON product_category (categories_id)');
        $this->addSql('CREATE INDEX IDX_CDFC73566C8A81A9 ON product_category (products_id)');
    }
}
