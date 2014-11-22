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

        $blogEntryListTable = $schema->createTable('se_microblogs');
        $blogEntryListTable->addColumn('entry_id', Type::INTEGER);
        $blogEntryListTable->addColumn('content', Type::STRING, ['length' => 100]);
        $blogEntryListTable->addColumn('creation_date', Type::DATETIME);
        $blogEntryListTable->addColumn('author_id', Type::INTEGER);
        $blogEntryListTable->addColumn('image_name', Type::STRING);
        $blogEntryListTable->addIndex(['author_id'], 'blog_author');
        $blogEntryListTable->setPrimaryKey(['entry_id']);
        $blogEntryListTable->addIndex(['creation_date'], 'blog_date');
        $blogEntryListTable->addForeignKeyConstraint($userTable, ['author_id'], ['user_id'], [], 'r_blog_author');

        $commentListTable = $schema->createTable('se_comments');
        $commentListTable->addColumn('comment_id', Type::INTEGER);
        $commentListTable->addColumn('content', Type::STRING, ['length' => 50]);
        $commentListTable->addColumn('creation_date', Type::DATETIME);
        $commentListTable->addColumn('article_id', Type::INTEGER);
        $commentListTable->addColumn('author_id', Type::INTEGER);
        $commentListTable->setPrimaryKey(['comment_id']);
        $commentListTable->addIndex(['article_id'], 'comment_article');
        $commentListTable->addIndex(['author_id'], 'comment_author');
        $commentListTable->addForeignKeyConstraint($userTable, ['author_id'], ['user_id'], [], 'r_comment_author');
        $commentListTable->addForeignKeyConstraint($blogEntryListTable, ['article_id'], ['author_id'], [], 'r_comment_article');

        $hashTagTable = $schema->createTable('se_hashtags');
        $hashTagTable->addColumn('hashtag_id', Type::INTEGER);
        $hashTagTable->addColumn('name', Type::STRING);
        $hashTagTable->setPrimaryKey(['hashtag_id']);
        $hashTagTable->addUniqueIndex(['name'], 'tag_name');

        $tagInPostTable = $schema->createTable('se_tags_in_post');
        $tagInPostTable->addColumn('tag_name', Type::STRING);
        $tagInPostTable->addColumn('post_id', Type::INTEGER);
        $tagInPostTable->addIndex(['post_id'], 'post_tag_post_id');
        $tagInPostTable->addIndex(['tag_name'], 'post_tag_tag_id');
        $tagInPostTable->addForeignKeyConstraint($hashTagTable, ['tag_name'], ['name'], [], 'r_tag_post_tag_id');
        $tagInPostTable->addForeignKeyConstraint($blogEntryListTable, ['post_id'], ['entry_id'], [], 'r_tag_post_post_id');

        $tagInCommentsTable = $schema->createTable('se_tag_in_comment');
        $tagInCommentsTable->addColumn('tag_id', Type::INTEGER);
        $tagInCommentsTable->addColumn('comment_id', Type::INTEGER);
        $tagInCommentsTable->addIndex(['tag_id'], 'comment_tag_tag_id');
        $tagInCommentsTable->addIndex(['comment_id'], 'comment_tag_comment_id');
        $tagInCommentsTable->addForeignKeyConstraint($hashTagTable, ['tag_id'], ['hashtag_id'], [], 'r_tag_comment_tag_id');
        $tagInCommentsTable->addForeignKeyConstraint($commentListTable, ['comment_id'], ['comment_id'], [], 'r_tag_comment_comment_id');

        $markedUserInPostListTable = $schema->createTable('se_user_in_post');
        $markedUserInPostListTable->addColumn('user_name', Type::STRING);
        $markedUserInPostListTable->addColumn('post_id', Type::INTEGER);
        $markedUserInPostListTable->addIndex(['user_name'], 'user_post_user_name');
        $markedUserInPostListTable->addIndex(['post_id'], 'user_post_post_id');
        $markedUserInPostListTable->addForeignKeyConstraint($userTable, ['user_name'], ['username'], [], 'r_post_user_user_name');
        $markedUserInPostListTable->addForeignKeyConstraint($blogEntryListTable, ['post_id'], ['entry_id'], [], 'r_post_user_post_id');

        $markedUserInCommentListTable = $schema->createTable('se_user_in_comment');
        $markedUserInCommentListTable->addColumn('user_name', Type::STRING);
        $markedUserInCommentListTable->addColumn('comment_id', Type::INTEGER);
        $markedUserInCommentListTable->addIndex(['user_name'], 'user_comment_user_name');
        $markedUserInCommentListTable->addIndex(['comment_id'], 'user_comment_comment_id');
        $markedUserInCommentListTable->addForeignKeyConstraint($userTable, ['user_name'], ['username'], [], 'r_comment_user_user_name');
        $markedUserInCommentListTable->addForeignKeyConstraint($commentListTable, ['comment_id'], ['comment_id'], [], 'r_comment_user_comment_id');
    }

    public function down(Schema $schema)
    {
        foreach (
            [
                'se_users',
                'se_followers',
                'se_user_role',
                'se_user_token',
                'se_microblogs',
                'se_comments',
                'se_hashtags',
                'se_tags_in_post',
                'se_tag_in_comment',
                'se_user_in_post',
                'se_user_in_comment'
            ] as $table) {

            $schema->dropTable($table);
        }
    }
}
