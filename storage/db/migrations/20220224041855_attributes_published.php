<?php

use Phinx\Db\Adapter\MysqlAdapter;

class AttributesPublished extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->table('attributes', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->changeColumn('name', 'string', [
                'null' => false,
                'limit' => 150,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'users_id',
            ])
            ->addColumn('is_published', 'boolean', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'name',
            ])
            ->changeColumn('is_deleted', 'boolean', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_published',
            ])
            ->changeColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_deleted',
            ])
            ->changeColumn('updated_at', 'datetime', [
                'null' => true,
                'after' => 'created_at',
            ])
            ->addIndex(['is_published'], [
                'name' => 'is_published',
                'unique' => false,
            ])
            ->save();
        $this->table('categories', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->changeColumn('name', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'uuid',
            ])
            ->changeColumn('slug', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'name',
            ])
            ->changeColumn('code', 'char', [
                'null' => true,
                'limit' => 36,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'slug',
            ])
            ->addIndex(['companies_id', 'slug'], [
                'name' => 'companies_id_slug',
                'unique' => true,
            ])
            ->removeIndexByName('code')
            ->addIndex(['code'], [
                'name' => 'code',
                'unique' => false,
            ])
            ->save();
    }
}
