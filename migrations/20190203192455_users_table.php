<?php

use Phinx\Migration\AbstractMigration;

class UsersTable extends AbstractMigration
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
        $table = $this->table('users');
        $table->addTimestamps()
              ->addColumn('first', 'string', ['length'=>255, 'null'=>true])
              ->addColumn('last', 'string', ['length'=>255, 'null'=>true])
              ->addColumn('dob', 'date', ['null'=>true, 'default'=>null])
              ->addColumn('email', 'string', ['length'=>255, 'null'=>true])
              ->addColumn('phone', 'string', ['length'=>30, 'null'=>true])
              ->addColumn('address', 'string', ['length'=>255, 'null'=>true])
              ->addColumn('address2', 'string', ['length'=>255, 'null'=>true])
              ->addColumn('city', 'string', ['length'=>255, 'null'=>true])
              ->addColumn('state', 'string', ['length'=>255, 'null'=>true])
              ->addColumn('zip', 'string', ['length'=>255, 'null'=>true])
              ->addColumn('nl_signup', 'string', ['length'=>3, 'null'=>true])
              ->addColumn('password', 'string', ['length'=>255, 'null'=>true])
              ->addColumn('last_login', 'datetime', ['length'=>255, 'null'=>true])
              ->addColumn('referer', 'text', ['null'=>true])
              ->addColumn('c_time', 'boolean', ['default'=>0])
              ->create();
    }
}
