<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191128103714 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lesson ADD training_id_id INT NOT NULL, ADD instructor_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F3909E143A FOREIGN KEY (training_id_id) REFERENCES training (id)');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F3AD96791B FOREIGN KEY (instructor_id_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_F87474F3909E143A ON lesson (training_id_id)');
        $this->addSql('CREATE INDEX IDX_F87474F3AD96791B ON lesson (instructor_id_id)');
        $this->addSql('ALTER TABLE person CHANGE preprovision preprovision VARCHAR(20) DEFAULT NULL, CHANGE hiring_date hiring_date DATE DEFAULT NULL, CHANGE salary salary NUMERIC(6, 2) DEFAULT NULL, CHANGE street street VARCHAR(255) DEFAULT NULL, CHANGE postal_code postal_code VARCHAR(6) DEFAULT NULL, CHANGE place place VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE registration ADD lesson_id_id INT NOT NULL, ADD member_id_id INT NOT NULL, CHANGE payment payment TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A735A24AD0 FOREIGN KEY (lesson_id_id) REFERENCES lesson (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A71D650BA4 FOREIGN KEY (member_id_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_62A8A7A735A24AD0 ON registration (lesson_id_id)');
        $this->addSql('CREATE INDEX IDX_62A8A7A71D650BA4 ON registration (member_id_id)');
        $this->addSql('ALTER TABLE training CHANGE cost cost INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F3909E143A');
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F3AD96791B');
        $this->addSql('DROP INDEX IDX_F87474F3909E143A ON lesson');
        $this->addSql('DROP INDEX IDX_F87474F3AD96791B ON lesson');
        $this->addSql('ALTER TABLE lesson DROP training_id_id, DROP instructor_id_id');
        $this->addSql('ALTER TABLE person CHANGE preprovision preprovision VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE hiring_date hiring_date DATE DEFAULT \'NULL\', CHANGE salary salary NUMERIC(6, 2) DEFAULT \'NULL\', CHANGE street street VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE postal_code postal_code VARCHAR(6) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE place place VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A735A24AD0');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A71D650BA4');
        $this->addSql('DROP INDEX IDX_62A8A7A735A24AD0 ON registration');
        $this->addSql('DROP INDEX IDX_62A8A7A71D650BA4 ON registration');
        $this->addSql('ALTER TABLE registration DROP lesson_id_id, DROP member_id_id, CHANGE payment payment TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE training CHANGE cost cost INT DEFAULT NULL');
    }
}
