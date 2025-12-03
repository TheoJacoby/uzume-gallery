<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251128192532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, rating INT NOT NULL, created_at DATETIME NOT NULL, is_visible TINYINT(1) NOT NULL, painting_id INT NOT NULL, INDEX IDX_9474526CB00EB939 (painting_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE painting (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created DATETIME NOT NULL, height DOUBLE PRECISION NOT NULL, width DOUBLE PRECISION NOT NULL, image_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_published TINYINT(1) NOT NULL, category_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_66B9EBA012469DE2 (category_id), INDEX IDX_66B9EBA0A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE painting_technique (painting_id INT NOT NULL, technique_id INT NOT NULL, INDEX IDX_AA4398E8B00EB939 (painting_id), INDEX IDX_AA4398E81F8ACB26 (technique_id), PRIMARY KEY (painting_id, technique_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE technique (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_disabled TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CB00EB939 FOREIGN KEY (painting_id) REFERENCES painting (id)');
        $this->addSql('ALTER TABLE painting ADD CONSTRAINT FK_66B9EBA012469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE painting ADD CONSTRAINT FK_66B9EBA0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE painting_technique ADD CONSTRAINT FK_AA4398E8B00EB939 FOREIGN KEY (painting_id) REFERENCES painting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE painting_technique ADD CONSTRAINT FK_AA4398E81F8ACB26 FOREIGN KEY (technique_id) REFERENCES technique (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CB00EB939');
        $this->addSql('ALTER TABLE painting DROP FOREIGN KEY FK_66B9EBA012469DE2');
        $this->addSql('ALTER TABLE painting DROP FOREIGN KEY FK_66B9EBA0A76ED395');
        $this->addSql('ALTER TABLE painting_technique DROP FOREIGN KEY FK_AA4398E8B00EB939');
        $this->addSql('ALTER TABLE painting_technique DROP FOREIGN KEY FK_AA4398E81F8ACB26');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE painting');
        $this->addSql('DROP TABLE painting_technique');
        $this->addSql('DROP TABLE technique');
        $this->addSql('DROP TABLE user');
    }
}
