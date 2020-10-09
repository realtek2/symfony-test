<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201009134450 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE role_company');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE role_company (role_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_F372ACB4D60322AC (role_id), INDEX IDX_F372ACB4979B1AD6 (company_id), PRIMARY KEY(role_id, company_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE role_company ADD CONSTRAINT FK_F372ACB4979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_company ADD CONSTRAINT FK_F372ACB4D60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE');
    }
}
