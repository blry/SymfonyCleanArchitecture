<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230720193953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stream_files (id BIGINT UNSIGNED NOT NULL, streamer_id BIGINT UNSIGNED DEFAULT NULL, type VARCHAR(20) NOT NULL, path VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_495DA53225F432AD (streamer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stream_streamers (id BIGINT UNSIGNED NOT NULL, avatar_id BIGINT UNSIGNED DEFAULT NULL, status VARCHAR(20) NOT NULL, status_reason LONGTEXT DEFAULT NULL, nickname VARCHAR(20) DEFAULT NULL, UNIQUE INDEX UNIQ_DBBDB7C286383B10 (avatar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stream_files ADD CONSTRAINT FK_495DA53225F432AD FOREIGN KEY (streamer_id) REFERENCES stream_streamers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stream_streamers ADD CONSTRAINT FK_DBBDB7C286383B10 FOREIGN KEY (avatar_id) REFERENCES stream_files (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stream_files DROP FOREIGN KEY FK_495DA53225F432AD');
        $this->addSql('ALTER TABLE stream_streamers DROP FOREIGN KEY FK_DBBDB7C286383B10');
        $this->addSql('DROP TABLE stream_files');
        $this->addSql('DROP TABLE stream_streamers');
    }
}
