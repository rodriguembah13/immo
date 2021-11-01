<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211101052304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE configuration (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, siteweb VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, bp VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, directeur VARCHAR(255) DEFAULT NULL, mode VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depense (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, depense_type_id INT DEFAULT NULL, local_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, date_achat DATETIME NOT NULL, INDEX IDX_34059757B03A8386 (created_by_id), INDEX IDX_34059757C0E45076 (depense_type_id), INDEX IDX_340597575D5A2101 (local_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depense_type (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, tenant_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, total DOUBLE PRECISION NOT NULL, amount DOUBLE PRECISION NOT NULL, amount_due DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_FE8664109033212A (tenant_id), INDEX IDX_FE866410B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture_item (id INT AUTO_INCREMENT NOT NULL, rental_id INT DEFAULT NULL, facture_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, amount_due DOUBLE PRECISION NOT NULL, INDEX IDX_F91D09D2A7CF2329 (rental_id), INDEX IDX_F91D09D27F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE local (id INT AUTO_INCREMENT NOT NULL, number_roon INT NOT NULL, number VARCHAR(255) DEFAULT NULL, consitance VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, position VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, date_begin DATE DEFAULT NULL, date_end DATE DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, receiver_id INT DEFAULT NULL, sender VARCHAR(255) NOT NULL, notified_id INT NOT NULL, type VARCHAR(255) NOT NULL, message TINYTEXT NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_BF5476CACD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_49BB6390B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rental (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, tenant_id INT DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, begin_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, type_rental VARCHAR(255) DEFAULT NULL, day VARCHAR(255) DEFAULT NULL, month VARCHAR(255) DEFAULT NULL, year VARCHAR(255) DEFAULT NULL, amount_due DOUBLE PRECISION DEFAULT NULL, active TINYINT(1) NOT NULL, INDEX IDX_1619C27DB03A8386 (created_by_id), INDEX IDX_1619C27D9033212A (tenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rental_contract (id INT AUTO_INCREMENT NOT NULL, tenant_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, type_rental VARCHAR(255) DEFAULT NULL, status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, amount_garanty DOUBLE PRECISION DEFAULT NULL, datedepot_garanty DATETIME DEFAULT NULL, amount_prevision DOUBLE PRECISION DEFAULT NULL, information_complementaires LONGTEXT DEFAULT NULL, INDEX IDX_D9E9316A9033212A (tenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rental_contract_local (rental_contract_id INT NOT NULL, local_id INT NOT NULL, INDEX IDX_1DEFD9733C8AD671 (rental_contract_id), INDEX IDX_1DEFD9735D5A2101 (local_id), PRIMARY KEY(rental_contract_id, local_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_element (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, number_roon INT NOT NULL, number VARCHAR(255) DEFAULT NULL, consitance VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, position VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_BAE4BCF2F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_element_rental_contract (site_element_id INT NOT NULL, rental_contract_id INT NOT NULL, INDEX IDX_EC9214483F16A5E1 (site_element_id), INDEX IDX_EC9214483C8AD671 (rental_contract_id), PRIMARY KEY(site_element_id, rental_contract_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tenant (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, situation VARCHAR(255) DEFAULT NULL, number_child INT DEFAULT NULL, profession VARCHAR(255) DEFAULT NULL, cni VARCHAR(255) NOT NULL, nationality VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, as_contrat TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_34059757B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_34059757C0E45076 FOREIGN KEY (depense_type_id) REFERENCES depense_type (id)');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_340597575D5A2101 FOREIGN KEY (local_id) REFERENCES local (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664109033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE facture_item ADD CONSTRAINT FK_F91D09D2A7CF2329 FOREIGN KEY (rental_id) REFERENCES rental (id)');
        $this->addSql('ALTER TABLE facture_item ADD CONSTRAINT FK_F91D09D27F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CACD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rental ADD CONSTRAINT FK_1619C27DB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rental ADD CONSTRAINT FK_1619C27D9033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id)');
        $this->addSql('ALTER TABLE rental_contract ADD CONSTRAINT FK_D9E9316A9033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id)');
        $this->addSql('ALTER TABLE rental_contract_local ADD CONSTRAINT FK_1DEFD9733C8AD671 FOREIGN KEY (rental_contract_id) REFERENCES rental_contract (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rental_contract_local ADD CONSTRAINT FK_1DEFD9735D5A2101 FOREIGN KEY (local_id) REFERENCES local (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE site_element ADD CONSTRAINT FK_BAE4BCF2F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE site_element_rental_contract ADD CONSTRAINT FK_EC9214483F16A5E1 FOREIGN KEY (site_element_id) REFERENCES site_element (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE site_element_rental_contract ADD CONSTRAINT FK_EC9214483C8AD671 FOREIGN KEY (rental_contract_id) REFERENCES rental_contract (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_34059757C0E45076');
        $this->addSql('ALTER TABLE facture_item DROP FOREIGN KEY FK_F91D09D27F2DEE08');
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_340597575D5A2101');
        $this->addSql('ALTER TABLE rental_contract_local DROP FOREIGN KEY FK_1DEFD9735D5A2101');
        $this->addSql('ALTER TABLE facture_item DROP FOREIGN KEY FK_F91D09D2A7CF2329');
        $this->addSql('ALTER TABLE rental_contract_local DROP FOREIGN KEY FK_1DEFD9733C8AD671');
        $this->addSql('ALTER TABLE site_element_rental_contract DROP FOREIGN KEY FK_EC9214483C8AD671');
        $this->addSql('ALTER TABLE site_element DROP FOREIGN KEY FK_BAE4BCF2F6BD1646');
        $this->addSql('ALTER TABLE site_element_rental_contract DROP FOREIGN KEY FK_EC9214483F16A5E1');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664109033212A');
        $this->addSql('ALTER TABLE rental DROP FOREIGN KEY FK_1619C27D9033212A');
        $this->addSql('ALTER TABLE rental_contract DROP FOREIGN KEY FK_D9E9316A9033212A');
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_34059757B03A8386');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410B03A8386');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CACD53EDB6');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390B03A8386');
        $this->addSql('ALTER TABLE rental DROP FOREIGN KEY FK_1619C27DB03A8386');
        $this->addSql('DROP TABLE configuration');
        $this->addSql('DROP TABLE depense');
        $this->addSql('DROP TABLE depense_type');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE facture_item');
        $this->addSql('DROP TABLE local');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE recette');
        $this->addSql('DROP TABLE rental');
        $this->addSql('DROP TABLE rental_contract');
        $this->addSql('DROP TABLE rental_contract_local');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE site_element');
        $this->addSql('DROP TABLE site_element_rental_contract');
        $this->addSql('DROP TABLE tenant');
        $this->addSql('DROP TABLE user');
    }
}
