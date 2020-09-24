<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200526100212 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json_array)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE award (id INT AUTO_INCREMENT NOT NULL, movie_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_8A5B2EE78F93B6FC (movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, birth_date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_actor (id INT AUTO_INCREMENT NOT NULL, movie_id INT NOT NULL, person_id INT NOT NULL, character_name VARCHAR(255) NOT NULL, INDEX IDX_3A374C658F93B6FC (movie_id), INDEX IDX_3A374C65217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, director_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, release_date DATE NOT NULL, image_filename VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_1D5EF26F899FB366 (director_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_category (movie_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_DABA824C8F93B6FC (movie_id), INDEX IDX_DABA824C12469DE2 (category_id), PRIMARY KEY(movie_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_writer (movie_id INT NOT NULL, person_id INT NOT NULL, INDEX IDX_6E6745F78F93B6FC (movie_id), INDEX IDX_6E6745F7217BBB47 (person_id), PRIMARY KEY(movie_id, person_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_post (movie_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_A9EC4D808F93B6FC (movie_id), INDEX IDX_A9EC4D804B89032C (post_id), PRIMARY KEY(movie_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE award ADD CONSTRAINT FK_8A5B2EE78F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE movie_actor ADD CONSTRAINT FK_3A374C658F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE movie_actor ADD CONSTRAINT FK_3A374C65217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26F899FB366 FOREIGN KEY (director_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE movie_category ADD CONSTRAINT FK_DABA824C8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_category ADD CONSTRAINT FK_DABA824C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_writer ADD CONSTRAINT FK_6E6745F78F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_writer ADD CONSTRAINT FK_6E6745F7217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_post ADD CONSTRAINT FK_A9EC4D808F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_post ADD CONSTRAINT FK_A9EC4D804B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE movie_actor DROP FOREIGN KEY FK_3A374C65217BBB47');
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26F899FB366');
        $this->addSql('ALTER TABLE movie_writer DROP FOREIGN KEY FK_6E6745F7217BBB47');
        $this->addSql('ALTER TABLE movie_post DROP FOREIGN KEY FK_A9EC4D804B89032C');
        $this->addSql('ALTER TABLE award DROP FOREIGN KEY FK_8A5B2EE78F93B6FC');
        $this->addSql('ALTER TABLE movie_actor DROP FOREIGN KEY FK_3A374C658F93B6FC');
        $this->addSql('ALTER TABLE movie_category DROP FOREIGN KEY FK_DABA824C8F93B6FC');
        $this->addSql('ALTER TABLE movie_writer DROP FOREIGN KEY FK_6E6745F78F93B6FC');
        $this->addSql('ALTER TABLE movie_post DROP FOREIGN KEY FK_A9EC4D808F93B6FC');
        $this->addSql('ALTER TABLE movie_category DROP FOREIGN KEY FK_DABA824C12469DE2');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE award');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE movie_actor');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE movie_category');
        $this->addSql('DROP TABLE movie_writer');
        $this->addSql('DROP TABLE movie_post');
        $this->addSql('DROP TABLE category');
    }
}
