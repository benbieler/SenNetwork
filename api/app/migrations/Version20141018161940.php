<?php
namespace Ma27\Sententiaregum\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141018161940 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $userTable = $schema->createTable('se_users');
        $userTable->addColumn('user_id', Type::INTEGER, ['length' => 11, 'autoincrement' => true]);
        $userTable->addColumn('username', Type::STRING, ['length' => 32]);
        $userTable->addColumn('realname', Type::STRING, ['length' => 128]);
        $userTable->addColumn('password', Type::STRING, ['length' => 512]);
        $userTable->addColumn('email', Type::STRING, ['length' => 128]);
        $userTable->addColumn('locked', Type::BOOLEAN);
        $userTable->addColumn('registrationDate', Type::DATETIME);
        $userTable->addColumn('lastAction', Type::DATETIME);
        $userTable->setPrimaryKey(['user_id']);
        $userTable->addUniqueIndex(['username']);
        $userTable->addUniqueIndex(['email']);

        $followerTable = $schema->createTable('se_followers');
        $followerTable->addColumn('user_id', Type::INTEGER);
        $followerTable->addColumn('follower_id', Type::INTEGER);
        $followerTable->setPrimaryKey(['user_id', 'follower_id']);
        $followerTable->addForeignKeyConstraint($userTable, ['user_id'], ['user_id'], [], 'r_follower_user_id');
        $followerTable->addForeignKeyConstraint($userTable, ['follower_id'], ['user_id'], [], 'r_follower_id');

        $roleAliasTable = $schema->createTable('se_user_role');
        $roleAliasTable->addColumn('user_id', Type::INTEGER);
        $roleAliasTable->addColumn('role_alias', Type::STRING);
        $roleAliasTable->addForeignKeyConstraint($userTable, ['user_id'], ['user_id'], [], 'r_roles_user_id');
        $roleAliasTable->addIndex(['user_id'], 'role_user');
        $roleAliasTable->addIndex(['role_alias'], 'role_index_alias');

        $userTokenTable = $schema->createTable('se_user_token');
        $userTokenTable->addColumn('user_id', Type::INTEGER);
        $userTokenTable->addColumn('token', Type::STRING, ['length' => 255]);
        $userTokenTable->addForeignKeyConstraint($userTable, ['user_id'], ['user_id'], [], 'r_token_user_id');
        $userTokenTable->setPrimaryKey(['token']);
        $userTokenTable->addUniqueIndex(['user_id']);

    }

    public function down(Schema $schema)
    {
        foreach (['se_users', 'se_followers', 'se_user_role', 'se_user_token'] as $table) {
            $schema->dropTable($table);
        }
    }
}
