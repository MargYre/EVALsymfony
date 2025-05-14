<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250514125236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE etape_ressource (etape_id INT NOT NULL, ressource_id INT NOT NULL, INDEX IDX_BE8CF0AB4A8CA2AD (etape_id), INDEX IDX_BE8CF0ABFC6CD52A (ressource_id), PRIMARY KEY(etape_id, ressource_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_parcours (user_id INT NOT NULL, parcours_id INT NOT NULL, INDEX IDX_793C3AE3A76ED395 (user_id), INDEX IDX_793C3AE36E38C0DB (parcours_id), PRIMARY KEY(user_id, parcours_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etape_ressource ADD CONSTRAINT FK_BE8CF0AB4A8CA2AD FOREIGN KEY (etape_id) REFERENCES etape (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etape_ressource ADD CONSTRAINT FK_BE8CF0ABFC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_parcours ADD CONSTRAINT FK_793C3AE3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_parcours ADD CONSTRAINT FK_793C3AE36E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etape ADD parcours_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etape ADD CONSTRAINT FK_285F75DD6E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_285F75DD6E38C0DB ON etape (parcours_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD emetteur_id INT NOT NULL, ADD receveur_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307F79E92E8C FOREIGN KEY (emetteur_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB967E626 FOREIGN KEY (receveur_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307F79E92E8C ON message (emetteur_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307FB967E626 ON message (receveur_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendu ADD etape_id INT NOT NULL, ADD user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendu ADD CONSTRAINT FK_2A7F8EB94A8CA2AD FOREIGN KEY (etape_id) REFERENCES etape (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendu ADD CONSTRAINT FK_2A7F8EB9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2A7F8EB94A8CA2AD ON rendu (etape_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2A7F8EB9A76ED395 ON rendu (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE etape_ressource DROP FOREIGN KEY FK_BE8CF0AB4A8CA2AD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etape_ressource DROP FOREIGN KEY FK_BE8CF0ABFC6CD52A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_parcours DROP FOREIGN KEY FK_793C3AE3A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_parcours DROP FOREIGN KEY FK_793C3AE36E38C0DB
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE etape_ressource
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_parcours
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etape DROP FOREIGN KEY FK_285F75DD6E38C0DB
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_285F75DD6E38C0DB ON etape
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etape DROP parcours_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendu DROP FOREIGN KEY FK_2A7F8EB94A8CA2AD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendu DROP FOREIGN KEY FK_2A7F8EB9A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_2A7F8EB94A8CA2AD ON rendu
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_2A7F8EB9A76ED395 ON rendu
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendu DROP etape_id, DROP user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F79E92E8C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FB967E626
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B6BD307F79E92E8C ON message
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B6BD307FB967E626 ON message
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP emetteur_id, DROP receveur_id
        SQL);
    }
}
