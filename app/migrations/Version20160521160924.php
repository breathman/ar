<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;


class Version20160521160924 extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema)
    {
        $schema->getTable('detail');
        $schema->getTable('package');
        $schema->getTable('work');
        $schema->getTable('package_detail');
        $schema->getTable('work_detail_package');
        $schema->getTable('order');
        $schema->getTable('order_status');
        $schema->getTable('contact');
    }

    /**
     * {@inheritdoc}
     */
    public function down(Schema $schema)
    {
        $schema->getTable('detail');
        $schema->getTable('package');
        $schema->getTable('work');
        $schema->getTable('package_detail');
        $schema->getTable('work_detail_package');
        $schema->getTable('order');
        $schema->getTable('order_status');
        $schema->getTable('contact');
    }
}
