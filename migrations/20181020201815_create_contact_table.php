<?php

use Phinx\Migration\AbstractMigration;

class CreateContactTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('contact_data');
        $table->addTimestamps()
              ->addColumn('form_id', 'string', ['length'=>255, 'null'=>true])
              ->addColumn('first', 'string', ['length'=>255, 'null'=>true])
              ->addColumn('last', 'string', ['length'=>255, 'null'=>true])
              ->addColumn('email', 'string', ['length'=>255, 'null'=>true])
              ->addColumn('phone', 'string', ['length'=>30, 'null'=>true])
              ->addColumn('message', 'text', ['null'=>true])
              ->addColumn('referer', 'text', ['null'=>true])
              ->addColumn('c_time', 'boolean', ['default'=>0])
              ->create();
    }
}
