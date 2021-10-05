<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class WorkflowsLogsActionsTable extends AbstractMigration
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
        $this->table('workflows_logs_actions')
            ->addColumn('workflows_logs_id', 'integer', ['null' => false])
            ->addColumn('actions_id', 'integer', ['null' => false])
            ->addColumn('status', 'integer', ['null' => false])
            ->addColumn('action_name', 'string', ['null' => false])
            ->addColumn('result', 'text', ['null' => true])
            ->addColumn('error', 'text', ['null' => true])
            ->addColumn('is_deleted', 'integer', ['null' => true])
            ->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->create();
    }
}
