<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424211935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avisjoueur (idAvis INT AUTO_INCREMENT NOT NULL, commentaire VARCHAR(255) NOT NULL, note DOUBLE PRECISION NOT NULL, dateAvis DATE NOT NULL, idJoueur INT DEFAULT NULL, INDEX idJoueur (idJoueur), PRIMARY KEY(idAvis)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(2000) NOT NULL, dateCreation DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images_stade (id INT AUTO_INCREMENT NOT NULL, id_stade INT NOT NULL, url_image VARCHAR(2555) NOT NULL, INDEX IDX_C46921167D4819A (id_stade), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `match` (id_match INT AUTO_INCREMENT NOT NULL, nom_match VARCHAR(255) NOT NULL, date_match DATE NOT NULL, duree_match VARCHAR(100) NOT NULL, nom_tournois VARCHAR(255) NOT NULL, nom_equipe1 VARCHAR(255) NOT NULL, nom_equipe2 VARCHAR(255) NOT NULL, PRIMARY KEY(id_match)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, reclamation_id INT DEFAULT NULL, receiver_id INT DEFAULT NULL, content VARCHAR(500) NOT NULL, date DATETIME NOT NULL, INDEX fk_sender (sender_id), INDEX fk_reclamation (reclamation_id), INDEX fk_reciver (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id_rec INT AUTO_INCREMENT NOT NULL, id INT DEFAULT NULL, date_rec DATE NOT NULL, object VARCHAR(255) NOT NULL, reclamation VARCHAR(1000) NOT NULL, email VARCHAR(255) NOT NULL, INDEX fk_ForigenUser (id), PRIMARY KEY(id_rec)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, id_stade INT DEFAULT NULL, id_organisateur INT DEFAULT NULL, date DATE NOT NULL, qr_code_base64 LONGTEXT DEFAULT NULL, id_DeuxiemeEquipe INT DEFAULT NULL, id_PremiereEquipe INT DEFAULT NULL, INDEX fk_equipe1 (id_PremiereEquipe), INDEX fk_equipe2 (id_DeuxiemeEquipe), INDEX fk_user (id_organisateur), INDEX FK_stade_Reservation (id_stade), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stade (id INT AUTO_INCREMENT NOT NULL, Nom VARCHAR(255) NOT NULL, Lieu VARCHAR(255) NOT NULL, Capacity INT NOT NULL, Numero INT NOT NULL, UNIQUE INDEX UNIQ_E951C0AA54231355 (Nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournois (id_tournois INT AUTO_INCREMENT NOT NULL, nom_tournois VARCHAR(255) NOT NULL, stade VARCHAR(255) NOT NULL, nombre_match INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, PRIMARY KEY(id_tournois)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, id_equipe INT DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, OTP VARCHAR(2500) DEFAULT NULL, number VARCHAR(20) NOT NULL, image LONGBLOB NOT NULL, firstname VARCHAR(3000) NOT NULL, lastname VARCHAR(3000) NOT NULL, username VARCHAR(3000) NOT NULL, INDEX fkequipe (id_equipe), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avisjoueur ADD CONSTRAINT FK_81C4279E60015F FOREIGN KEY (idJoueur) REFERENCES user (id)');
        $this->addSql('ALTER TABLE images_stade ADD CONSTRAINT FK_C46921167D4819A FOREIGN KEY (id_stade) REFERENCES stade (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E962D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id_rec)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404BF396750 FOREIGN KEY (id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849557D4819A FOREIGN KEY (id_stade) REFERENCES stade (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955645DB3CD FOREIGN KEY (id_DeuxiemeEquipe) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A41506CA FOREIGN KEY (id_PremiereEquipe) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495568161836 FOREIGN KEY (id_organisateur) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64927E0FF8 FOREIGN KEY (id_equipe) REFERENCES equipe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avisjoueur DROP FOREIGN KEY FK_81C4279E60015F');
        $this->addSql('ALTER TABLE images_stade DROP FOREIGN KEY FK_C46921167D4819A');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96F624B39D');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E962D6BA2D9');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96CD53EDB6');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404BF396750');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849557D4819A');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955645DB3CD');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A41506CA');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495568161836');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64927E0FF8');
        $this->addSql('DROP TABLE avisjoueur');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE images_stade');
        $this->addSql('DROP TABLE `match`');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE stade');
        $this->addSql('DROP TABLE tournois');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
