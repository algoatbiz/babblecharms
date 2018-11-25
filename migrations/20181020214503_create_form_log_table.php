<?php

use Phinx\Migration\AbstractMigration;

class CreateFormLogTable extends AbstractMigration
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
        $table = $this->table('form_log');
        $table->addTimestamps()
              ->addColumn('form_id', 'string', ['length'=>50, 'null'=>true])
              ->addColumn('form_data',  'text', ['null'=>true])
              ->addColumn('spam_timer', 'integer', ['default'=>0, 'null'=>true])
              ->addColumn('errors', 'text', ['null'=>true])
              ->addColumn('browser', 'string', ['null'=>true, 'limit'=>255])
              ->addColumn('device', 'string', ['null'=>true, 'limit'=>255])
              ->addColumn('platform', 'string', ['null'=>true, 'limit'=>255])
              ->addColumn('referer', 'text', ['null'=>true])
              ->addColumn('useragent', 'text', ['null'=>true])
              ->addColumn('mobile', 'integer', ['default'=>0, 'null'=>true])
              ->addColumn('successful', 'integer', ['default'=>0, 'null'=>true])
              ->create();
    }
}
