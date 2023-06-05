<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230601125444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE web_push_user_subscription (id INT AUTO_INCREMENT NOT NULL, subscriptionHash VARCHAR(255) NOT NULL, subscription JSON NOT NULL, user_id INT NOT NULL, INDEX IDX_74F43C88A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE web_push_user_subscription');
    }
}
