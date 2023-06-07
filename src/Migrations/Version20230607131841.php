<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230607131841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_push_user_subscription ADD CONSTRAINT FK_74F43C88A76ED395 FOREIGN KEY (user_id) REFERENCES sylius_shop_user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_push_user_subscription DROP FOREIGN KEY FK_74F43C88A76ED395');
    }
}
