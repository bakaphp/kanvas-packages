<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class UsersMigration extends AbstractMigration
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
    public function change(): void
    {
        $table = $this->table('users', ['id' => false, 'primary_key' => ['id'], 'engine' => 'InnoDB', 'encoding' => 'utf8', 'collation' => 'utf8_general_ci', 'comment' => '', 'row_format' => 'Compact']);
        $table->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_BIG, 'precision' => 20, 'signed' => false, 'identity' => 'enable'])
            ->addColumn('email', 'string', ['null' => false, 'limit' => 45, 'collation' => 'utf8_general_ci', 'encoding' => 'utf8', 'after' => 'id'])
            ->addColumn('password', 'string', ['null' => false, 'limit' => 255, 'collation' => 'utf8_general_ci', 'encoding' => 'utf8', 'after' => 'email'])
            ->addColumn('firstname', 'string', ['null' => true, 'limit' => 45, 'collation' => 'utf8_general_ci', 'encoding' => 'utf8', 'after' => 'password'])
            ->addColumn('lastname', 'string', ['null' => true, 'limit' => 45, 'collation' => 'utf8_general_ci', 'encoding' => 'utf8', 'after' => 'firstname'])
            ->addColumn('user_role', 'string', ['null' => true, 'limit' => 45, 'collation' => 'utf8_general_ci', 'encoding' => 'utf8', 'after' => 'lastname'])
            ->addColumn('displayname', 'string', ['null' => true, 'limit' => 45, 'collation' => 'utf8_general_ci', 'encoding' => 'utf8', 'after' => 'user_role'])
            ->addColumn('registered', 'datetime', ['null' => false, 'after' => 'displayname'])
            ->addColumn('lastvisit', 'datetime', ['null' => false, 'after' => 'registered'])
            ->addColumn('dob', 'date', ['null' => false, 'after' => 'lastvisit'])
            ->addColumn('sex', 'enum', ['null' => false, 'default' => 'U', 'limit' => 1, 'values' => ['U', 'M', 'F'], 'after' => 'dob'])
            ->addColumn('timezone', 'string', ['null' => false, 'default' => 'America/New_York', 'limit' => 128, 'collation' => 'utf8_general_ci', 'encoding' => 'utf8', 'after' => 'sex'])
            ->addColumn('city_id', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_MEDIUM, 'precision' => 7, 'signed' => false, 'after' => 'timezone'])
            ->addColumn('state_id', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'after' => 'city_id'])
            ->addColumn('country_id', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_SMALL, 'precision' => 5, 'signed' => false, 'after' => 'state_id'])
            ->addColumn('profile_privacy', 'enum', ['null' => false, 'default' => '0', 'limit' => 1, 'values' => ['0', '1'], 'after' => 'country_id'])
            ->addColumn('interests', 'text', ['null' => true, 'limit' => MysqlAdapter::TEXT_MEDIUM, 'collation' => 'utf8_general_ci', 'encoding' => 'utf8', 'after' => 'profile_privacy'])
            ->addColumn('profile_image', 'string', ['null' => true, 'limit' => 45, 'collation' => 'utf8_general_ci', 'encoding' => 'utf8', 'after' => 'interests'])
            ->addColumn('profile_remote_image', 'string', ['null' => true, 'limit' => 255, 'collation' => 'utf8_general_ci', 'encoding' => 'utf8', 'after' => 'profile_image'])
            ->addColumn('profile_header', 'string', ['null' => true, 'limit' => 192, 'collation' => 'utf8_bin', 'encoding' => 'utf8', 'after' => 'profile_remote_image'])
            ->addColumn('profile_header_mobile', 'string', ['null' => true, 'limit' => 192, 'collation' => 'utf8_general_ci', 'encoding' => 'utf8', 'after' => 'profile_header'])
            ->addColumn('user_active', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'profile_header_mobile'])
            ->addColumn('user_level', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'user_active'])
            ->addColumn('user_login_tries', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'user_level'])
            ->addColumn('user_last_login_try', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_BIG, 'precision' => 19, 'after' => 'user_login_tries'])
            ->addColumn('session_time', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_BIG, 'precision' => 19, 'after' => 'user_last_login_try'])
            ->addColumn('session_page', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'session_time'])
            ->addColumn('welcome', 'integer', ['null' => false, 'default' => '0', 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'session_page'])
            ->addColumn('user_activation_key', 'string', ['null' => true, 'limit' => 64, 'collation' => 'utf8_bin', 'encoding' => 'utf8', 'after' => 'welcome'])
            ->addColumn('user_activation_email', 'string', ['null' => true, 'limit' => 64, 'collation' => 'utf8_bin', 'encoding' => 'utf8', 'after' => 'user_activation_key'])
            ->addColumn('user_activation_forgot', 'string', ['null' => true, 'limit' => 100, 'collation' => 'utf8_bin', 'encoding' => 'utf8', 'after' => 'user_activation_email'])
            ->addColumn('language', 'string', ['null' => true, 'limit' => 5, 'collation' => 'utf8_bin', 'encoding' => 'utf8', 'after' => 'user_activation_forgot'])
            ->addColumn('modified_at', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'after' => 'language'])
            ->addColumn('karma', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'modified_at'])
            ->addColumn('votes', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'after' => 'karma'])
            ->addColumn('votes_points', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'votes'])
            ->addColumn('banned', 'char', ['null' => false, 'default' => 'N', 'limit' => 1, 'collation' => 'utf8_general_ci', 'encoding' => 'utf8', 'after' => 'votes_points'])
            ->addColumn('created_at', 'datetime', ['null' => true, 'after' => 'banned'])
            ->addColumn('update_at', 'datetime', ['null' => true, 'after' => 'created_at'])
            ->save();
    }
}
