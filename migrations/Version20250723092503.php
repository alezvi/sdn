<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250723092503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE condicion_iva (id_condicion_iva INT AUTO_INCREMENT NOT NULL, condicion_iva VARCHAR(50) NOT NULL, codigo SMALLINT NOT NULL, alicuota DOUBLE PRECISION NOT NULL, PRIMARY KEY(id_condicion_iva)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE producto_servicio (id_producto_servicio INT AUTO_INCREMENT NOT NULL, id_rubro INT NOT NULL, id_unidad_medida INT NOT NULL, id_condicion_iva INT NOT NULL, tipo VARCHAR(1) NOT NULL, codigo VARCHAR(20) NOT NULL, producto_servicio VARCHAR(255) NOT NULL, precio_producto_unitario NUMERIC(10, 2) NOT NULL, UNIQUE INDEX UNIQ_E31583FF20332D99 (codigo), INDEX IDX_E31583FF7C032DDF (id_rubro), INDEX IDX_E31583FFC38BC206 (id_unidad_medida), INDEX IDX_E31583FF7A9F46ED (id_condicion_iva), PRIMARY KEY(id_producto_servicio)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rubros (id_rubro INT AUTO_INCREMENT NOT NULL, rubro VARCHAR(50) NOT NULL, PRIMARY KEY(id_rubro)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unidades_de_medida (id_unidad_de_medida INT AUTO_INCREMENT NOT NULL, codigo VARCHAR(5) NOT NULL, unidad_medida VARCHAR(50) NOT NULL, PRIMARY KEY(id_unidad_de_medida)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE producto_servicio ADD CONSTRAINT FK_E31583FF7C032DDF FOREIGN KEY (id_rubro) REFERENCES rubros (id_rubro)');
        $this->addSql('ALTER TABLE producto_servicio ADD CONSTRAINT FK_E31583FFC38BC206 FOREIGN KEY (id_unidad_medida) REFERENCES unidades_de_medida (id_unidad_de_medida)');
        $this->addSql('ALTER TABLE producto_servicio ADD CONSTRAINT FK_E31583FF7A9F46ED FOREIGN KEY (id_condicion_iva) REFERENCES condicion_iva (id_condicion_iva)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE producto_servicio DROP FOREIGN KEY FK_E31583FF7C032DDF');
        $this->addSql('ALTER TABLE producto_servicio DROP FOREIGN KEY FK_E31583FFC38BC206');
        $this->addSql('ALTER TABLE producto_servicio DROP FOREIGN KEY FK_E31583FF7A9F46ED');
        $this->addSql('DROP TABLE condicion_iva');
        $this->addSql('DROP TABLE producto_servicio');
        $this->addSql('DROP TABLE rubros');
        $this->addSql('DROP TABLE unidades_de_medida');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
