<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20160523213935 extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema)
    {
        $schema->getTable('contact')
            ->addForeignKeyConstraint('contact_type', ['type'], ['id'], [], 'fk_contact_type_type');
    }

    /**
     * {@inheritdoc}
     */
    public function down(Schema $schema)
    {
        $schema->getTable('contact')
            ->removeForeignKey('fk_contact_type_type');
    }
}
