<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230510141559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salle DROP FOREIGN KEY FK_4E977E5C6AB213CC');
        $this->addSql('DROP INDEX IDX_4E977E5C6AB213CC ON salle');
        $this->addSql('ALTER TABLE salle DROP lieu_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salle ADD lieu_id INT NOT NULL');
        $this->addSql('ALTER TABLE salle ADD CONSTRAINT FK_4E977E5C6AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id)');
        $this->addSql('CREATE INDEX IDX_4E977E5C6AB213CC ON salle (lieu_id)');
    }
}
