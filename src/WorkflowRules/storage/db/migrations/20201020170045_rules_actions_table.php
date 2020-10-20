<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RulesActionsTable extends AbstractMigration
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
        $this->table('rules_actions')
            ->addColumn('rules_id', 'integer', ['null' => true])
            ->addColumn('rules_workflow_actions_id', 'integer', ['null' => true])
            ->addColumn('created_at', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addColumn('is_deleted', 'integer', ['null' => false])
            ->addForeignKey('rules_id', 'rules', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
            ->addForeignKey('rules_workflow_actions_id', 'rules_workflow_actions', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
            ->create();
    }
}
