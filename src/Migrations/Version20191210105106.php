<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191210105106 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE person ADD first_name VARCHAR(255) NOT NULL, ADD preprovision VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, ADD date_of_birth DATE NOT NULL, ADD gender VARCHAR(6) NOT NULL, ADD email_adress VARCHAR(255) NOT NULL, ADD hiring_date DATE DEFAULT NULL, ADD salary NUMERIC(10, 2) DEFAULT NULL, ADD street VARCHAR(255) DEFAULT NULL, ADD postal_code VARCHAR(7) DEFAULT NULL, ADD place VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE registration CHANGE payment payment TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE training CHANGE cost cost INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_34DCD176F85E0677 ON person');
        $this->addSql('ALTER TABLE person DROP first_name, DROP preprovision, DROP last_name, DROP date_of_birth, DROP gender, DROP email_adress, DROP hiring_date, DROP salary, DROP street, DROP postal_code, DROP place');
        $this->addSql('ALTER TABLE registration CHANGE payment payment TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE training CHANGE cost cost INT DEFAULT NULL');
    }
}
