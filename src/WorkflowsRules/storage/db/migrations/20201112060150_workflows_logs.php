<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class WorkflowsLogs extends AbstractMigration
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
        $this->table('workflows_logs')
            ->addColumn('rules_id', 'integer', ['null' => true, 'after' => 'id'])
            ->addColumn('actions_id', 'integer', ['null' => true, 'after' => 'id'])
            ->addColumn('start_at', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('end_at', 'datetime', ['null' => true])
            ->addColumn('did_succeed', 'boolean', ['null' => false, 'default' => 0])
            ->addColumn('data', 'json', ['null' => true])
            ->addColumn('message', 'string', ['null' => true])
            ->addColumn('created_at', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addColumn('is_deleted', 'integer', ['null' => false, 'default' => 0])
            ->addForeignKey('rules_id', 'rules', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
            ->addForeignKey('actions_id', 'actions', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
            ->create();
    }
}
