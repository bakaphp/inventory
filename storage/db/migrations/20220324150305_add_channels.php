<?php

use Phinx\Db\Adapter\MysqlAdapter;

class AddChannels extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->table('products_variants_warehouse_channels', [
            'id' => false,
            'primary_key' => ['channels_id', 'products_variants_id', 'warehouse_id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('channels_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('products_variants_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'channels_id',
            ])
            ->addColumn('warehouse_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'products_variants_id',
            ])
            ->addColumn('price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => '10',
                'scale' => '2',
                'after' => 'warehouse_id',
            ])
            ->addColumn('discounted_price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => '10',
                'scale' => '2',
                'after' => 'price',
            ])
            ->addColumn('is_published', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'discounted_price',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_published',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'after' => 'created_at',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'updated_at',
            ])
            ->addIndex(['price'], [
                'name' => 'price',
                'unique' => false,
            ])
            ->addIndex(['discounted_price'], [
                'name' => 'discounted_price',
                'unique' => false,
            ])
            ->addIndex(['is_published'], [
                'name' => 'is_published',
                'unique' => false,
            ])
            ->addIndex(['created_at'], [
                'name' => 'created_at',
                'unique' => false,
            ])
            ->addIndex(['updated_at'], [
                'name' => 'updated_at',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
                'unique' => false,
            ])
            ->addIndex(['channels_id', 'products_variants_id', 'warehouse_id', 'is_published'], [
                'name' => 'channels_id_is_published',
                'unique' => false,
            ])
            ->addIndex(['channels_id', 'products_variants_id', 'warehouse_id', 'is_published', 'is_deleted'], [
                'name' => 'channels_list_deleted',
                'unique' => false,
            ])
            ->create();


        $this->table('channels', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('uuid', 'char', [
                'null' => false,
                'limit' => 36,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'id',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'uuid',
            ])
            ->addColumn('description', 'text', [
                'null' => true,
                'limit' => 65535,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'name',
            ])
            ->addColumn('slug', 'char', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'description',
            ])
            ->addColumn('users_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'slug',
            ])
            ->addColumn('companies_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'users_id',
            ])
            ->addColumn('apps_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'companies_id',
            ])
            ->addColumn('is_published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'apps_id',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_published',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'after' => 'created_at',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'updated_at',
            ])
            ->addIndex(['slug', 'companies_id'], [
                'name' => 'slug_companies_id',
                'unique' => true,
            ])
            ->addIndex(['uuid'], [
                'name' => 'uuid',
                'unique' => false,
            ])
            ->addIndex(['slug'], [
                'name' => 'slug',
                'unique' => false,
            ])
            ->addIndex(['users_id'], [
                'name' => 'users_id',
                'unique' => false,
            ])
            ->addIndex(['companies_id'], [
                'name' => 'companies_id',
                'unique' => false,
            ])
            ->addIndex(['apps_id'], [
                'name' => 'apps_id',
                'unique' => false,
            ])
            ->addIndex(['created_at'], [
                'name' => 'created_at',
                'unique' => false,
            ])
            ->addIndex(['updated_at'], [
                'name' => 'updated_at',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
                'unique' => false,
            ])
            ->addIndex(['is_published'], [
                'name' => 'is_published',
                'unique' => false,
            ])
            ->create();

        $this->table('products_variants_warehouse_price_history', [
            'id' => false,
            'primary_key' => ['products_variants_id', 'warehouse_id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('channels_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'warehouse_id',
            ])
            ->changeColumn('price', 'decimal', [
                'null' => true,
                'precision' => '10',
                'scale' => '2',
                'after' => 'channels_id',
            ])
            ->changeColumn('from_date', 'datetime', [
                'null' => false,
                'comment' => 'changed date',
                'after' => 'price',
            ])
            ->changeColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'from_date',
            ])
            ->changeColumn('is_deleted', 'boolean', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'created_at',
            ])
            ->addIndex(['channels_id'], [
                'name' => 'channels_id',
                'unique' => false,
            ])
            ->save();

        $table = $this->table('products_variants_warehouse_channels');
        $table->addForeignKey(
            'channels_id',
            'channels',
            'id',
            ['constraint' => 'FK_product_variants_channels_channels'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );


        $table->addForeignKey(
            'products_variants_id',
            'products_variants',
            'id',
            ['constraint' => 'FK_product_variants_channels_variants'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );

        $table->addForeignKey(
            'warehouse_id',
            'warehouses',
            'id',
            ['constraint' => 'FK_product_variants_channels_warehouse'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );
        $table->save();
    }
}
