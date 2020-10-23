<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Rules extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change() : void
    {
        $this->table('rules')
            ->addColumn('system_modules_id', 'integer', ['null' => false])
            ->addColumn('rules_types_id', 'integer', ['null' => true])
            ->addColumn('name', 'string', ['null' => false])
            ->addColumn('description', 'string', ['null' => true])
            ->addColumn('pattern', 'string', ['null' => false])
            ->addColumn('created_at', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addColumn('is_deleted', 'integer', ['null' => false, 'default' => 0])
            ->addForeignKey('rules_types_id', 'rules_types', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
            ->create();
    }
}
