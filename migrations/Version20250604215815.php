<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250604215815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE build_products (build_id INT NOT NULL, products_id INT NOT NULL, INDEX IDX_5799002C17C13F8B (build_id), INDEX IDX_5799002C6C8A81A9 (products_id), PRIMARY KEY(build_id, products_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE build_products ADD CONSTRAINT FK_5799002C17C13F8B FOREIGN KEY (build_id) REFERENCES build (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE build_products ADD CONSTRAINT FK_5799002C6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE products DROP INDEX UNIQ_B3BA5A5A44F5D008, ADD INDEX IDX_B3BA5A5A44F5D008 (brand_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE products DROP INDEX UNIQ_B3BA5A5A12469DE2, ADD INDEX IDX_B3BA5A5A12469DE2 (category_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A17C13F8B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B3BA5A5A17C13F8B ON products
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE products DROP build_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE build_products DROP FOREIGN KEY FK_5799002C17C13F8B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE build_products DROP FOREIGN KEY FK_5799002C6C8A81A9
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE build_products
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE products DROP INDEX IDX_B3BA5A5A44F5D008, ADD UNIQUE INDEX UNIQ_B3BA5A5A44F5D008 (brand_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE products DROP INDEX IDX_B3BA5A5A12469DE2, ADD UNIQUE INDEX UNIQ_B3BA5A5A12469DE2 (category_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE products ADD build_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A17C13F8B FOREIGN KEY (build_id) REFERENCES build (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B3BA5A5A17C13F8B ON products (build_id)
        SQL);
    }
}
