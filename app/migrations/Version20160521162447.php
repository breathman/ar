<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20160521162447 extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema)
    {
        $schema->getTable('order')
            ->addForeignKeyConstraint('order_status', ['status'], ['id'], [], 'fk_order_order_status');
    }

    /**
     * {@inheritdoc}
     */
    public function down(Schema $schema)
    {
        $schema->getTable('order')
            ->removeForeignKey('fk_order_order_status');
    }
}
