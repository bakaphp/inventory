<?php

use Phinx\Db\Adapter\MysqlAdapter;

class InitDB extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->execute("ALTER DATABASE CHARACTER SET 'utf8mb4';");
        $this->execute("ALTER DATABASE COLLATE='utf8mb4_unicode_520_ci';");
        $this->table('products_attributes', [
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
                'limit' => '10',
                'identity' => 'enable',
            ])
            ->addColumn('products_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'after' => 'id',
            ])
            ->addColumn('attributes_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'after' => 'products_id',
            ])
            ->addColumn('is_deleted', 'boolean', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'attributes_id',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_deleted',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'after' => 'created_at',
            ])
            ->addIndex(['products_id', 'attributes_id'], [
                'name' => 'products_id_attributes_id',
                'unique' => true,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
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
            ->addIndex(['products_id', 'attributes_id'], [
                'name' => 'products_id_attributes_idid',
                'unique' => false,
            ])
            ->create();
        $this->table('warehouses', [
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
            ->addColumn('apps_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('companies_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'apps_id',
            ])
            ->addColumn('regions_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'companies_id',
            ])
            ->addColumn('uuid', 'char', [
                'null' => false,
                'limit' => 36,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'regions_id',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 64,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'uuid',
            ])
            ->addColumn('location', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'name',
            ])
            ->addColumn('is_default', 'boolean', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'location',
            ])
            ->addColumn('is_published', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_default',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_published',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_deleted',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'after' => 'created_at',
            ])
            ->addIndex(['apps_id'], [
                'name' => 'apps_id',
                'unique' => false,
            ])
            ->addIndex(['companies_id'], [
                'name' => 'companies_id',
                'unique' => false,
            ])
            ->addIndex(['uuid'], [
                'name' => 'uuid',
                'unique' => false,
            ])
            ->addIndex(['regions_id'], [
                'name' => 'regions_id',
                'unique' => false,
            ])
            ->addIndex(['is_default'], [
                'name' => 'is_default',
                'unique' => false,
            ])
            ->addIndex(['is_published'], [
                'name' => 'is_published',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
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
            ->create();
        $this->table('categories_products', [
            'id' => false,
            'primary_key' => ['categories_id', 'products_id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('categories_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('products_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'categories_id',
            ])
            ->addColumn('is_deleted', 'boolean', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'products_id',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_deleted',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'after' => 'created_at',
            ])
            ->addIndex(['created_at'], [
                'name' => 'created_at',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
                'unique' => false,
            ])
            ->addIndex(['updated_at'], [
                'name' => 'updated_at',
                'unique' => false,
            ])
            ->create();
        $this->table('products_variants_warehouse', [
            'id' => false,
            'primary_key' => ['products_variants_id', 'warehouse_id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('products_variants_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('warehouse_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'products_variants_id',
            ])
            ->addColumn('quantity', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_MEDIUM,
                'after' => 'warehouse_id',
            ])
            ->addColumn('price', 'decimal', [
                'null' => true,
                'precision' => '10',
                'scale' => '2',
                'after' => 'quantity',
            ])
            ->addColumn('sku', 'char', [
                'null' => true,
                'limit' => 190,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'price',
            ])
            ->addColumn('position', 'integer', [
                'null' => false,
                'limit' => '10',
                'after' => 'sku',
            ])
            ->addColumn('serial_number', 'char', [
                'null' => true,
                'limit' => 190,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'position',
            ])
            ->addColumn('is_oversellable', 'boolean', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'serial_number',
            ])
            ->addColumn('is_out_of_stock_on_store', 'boolean', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_oversellable',
            ])
            ->addColumn('is_default', 'boolean', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_out_of_stock_on_store',
            ])
            ->addColumn('is_best_seller', 'boolean', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_default',
            ])
            ->addColumn('is_on_sale', 'boolean', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_best_seller',
            ])
            ->addColumn('is_on_promo', 'boolean', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_on_sale',
            ])
            ->addColumn('can_pre_order', 'boolean', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_on_promo',
            ])
            ->addColumn('is_comming_son', 'boolean', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'can_pre_order',
            ])
            ->addColumn('is_new', 'boolean', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_comming_son',
            ])
            ->addColumn('is_published', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_new',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_published',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_deleted',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'after' => 'created_at',
            ])
            ->addIndex(['quantity'], [
                'name' => 'quantity',
                'unique' => false,
            ])
            ->addIndex(['price'], [
                'name' => 'price',
                'unique' => false,
            ])
            ->addIndex(['sku'], [
                'name' => 'sku',
                'unique' => false,
            ])
            ->addIndex(['position'], [
                'name' => 'position',
                'unique' => false,
            ])
            ->addIndex(['serial_number'], [
                'name' => 'serial_number',
                'unique' => false,
            ])
            ->addIndex(['is_oversellable'], [
                'name' => 'is_oversellable',
                'unique' => false,
            ])
            ->addIndex(['is_out_of_stock_on_store'], [
                'name' => 'is_out_of_stock_on_store',
                'unique' => false,
            ])
            ->addIndex(['is_default'], [
                'name' => 'is_default',
                'unique' => false,
            ])
            ->addIndex(['is_best_seller'], [
                'name' => 'is_best_seller',
                'unique' => false,
            ])
            ->addIndex(['is_on_sale'], [
                'name' => 'is_on_sale',
                'unique' => false,
            ])
            ->addIndex(['is_on_promo'], [
                'name' => 'is_on_promo',
                'unique' => false,
            ])
            ->addIndex(['can_pre_order'], [
                'name' => 'can_pre_order',
                'unique' => false,
            ])
            ->addIndex(['is_comming_son'], [
                'name' => 'is_comming_son',
                'unique' => false,
            ])
            ->addIndex(['is_new'], [
                'name' => 'is_new',
                'unique' => false,
            ])
            ->addIndex(['is_published'], [
                'name' => 'is_published',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
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
            ->create();
        $this->table('products_variants_attribute_values', [
            'id' => false,
            'primary_key' => ['products_variants_id', 'products_attributes_values_id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('products_variants_id', 'integer', [
                'null' => false,
                'limit' => '10',
            ])
            ->addColumn('products_attributes_values_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'after' => 'products_variants_id',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'products_attributes_values_id',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_deleted',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'after' => 'created_at',
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
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
            ->create();
        $this->table('products_attribute_values', [
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
                'limit' => '10',
                'identity' => 'enable',
            ])
            ->addColumn('products_attributes_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'after' => 'id',
            ])
            ->addColumn('value', 'text', [
                'null' => false,
                'limit' => MysqlAdapter::TEXT_LONG,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'products_attributes_id',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'value',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_deleted',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'after' => 'created_at',
            ])
            ->addIndex(['products_attributes_id'], [
                'name' => 'products_attributes_id',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
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
            ->create();
        $this->table('products_variants', [
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
                'limit' => '10',
                'identity' => 'enable',
            ])
            ->addColumn('uuid', 'char', [
                'null' => false,
                'limit' => 36,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'id',
            ])
            ->addColumn('products_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'after' => 'uuid',
            ])
            ->addColumn('name', 'text', [
                'null' => false,
                'limit' => MysqlAdapter::TEXT_LONG,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'products_id',
            ])
            ->addColumn('slug', 'string', [
                'null' => false,
                'limit' => 190,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'name',
            ])
            ->addColumn('description', 'text', [
                'null' => true,
                'limit' => MysqlAdapter::TEXT_LONG,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'slug',
            ])
            ->addColumn('short_description', 'text', [
                'null' => true,
                'limit' => MysqlAdapter::TEXT_LONG,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'description',
            ])
            ->addColumn('position', 'integer', [
                'null' => false,
                'limit' => '10',
                'after' => 'short_description',
            ])
            ->addColumn('sku', 'string', [
                'null' => false,
                'limit' => 64,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'position',
            ])
            ->addColumn('ean', 'string', [
                'null' => false,
                'limit' => 64,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'sku',
            ])
            ->addColumn('barcode', 'string', [
                'null' => false,
                'limit' => 64,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'ean',
            ])
            ->addColumn('serial_number', 'char', [
                'null' => true,
                'limit' => 190,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'barcode',
            ])
            ->addColumn('is_published', 'boolean', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'serial_number',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_published',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'after' => 'created_at',
            ])
            ->addColumn('is_deleted', 'boolean', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'updated_at',
            ])
            ->addIndex(['products_id'], [
                'name' => 'products_id',
                'unique' => false,
            ])
            ->addIndex(['slug'], [
                'name' => 'slug',
                'unique' => false,
            ])
            ->addIndex(['position'], [
                'name' => 'position',
                'unique' => false,
            ])
            ->addIndex(['sku'], [
                'name' => 'sku',
                'unique' => false,
            ])
            ->addIndex(['serial_number'], [
                'name' => 'serial_number',
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
            ->addIndex(['uuid'], [
                'name' => 'uui',
                'unique' => false,
            ])
            ->create();
        $this->table('attributes', [
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
                'limit' => '10',
                'identity' => 'enable',
            ])
            ->addColumn('uuid', 'char', [
                'null' => false,
                'limit' => 36,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'id',
            ])
            ->addColumn('apps_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'uuid',
            ])
            ->addColumn('companies_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'apps_id',
            ])
            ->addColumn('name', 'integer', [
                'null' => false,
                'limit' => '10',
                'after' => 'companies_id',
            ])
            ->addColumn('is_deleted', 'boolean', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'name',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_deleted',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'after' => 'created_at',
            ])
            ->addIndex(['uuid'], [
                'name' => 'uuid',
                'unique' => false,
            ])
            ->addIndex(['apps_id'], [
                'name' => 'apps_id',
                'unique' => false,
            ])
            ->addIndex(['companies_id'], [
                'name' => 'companies_id',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
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
            ->create();
        $this->table('regions', [
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
            ->addColumn('apps_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('companies_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'apps_id',
            ])
            ->addColumn('uuid', 'char', [
                'null' => false,
                'limit' => 36,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'companies_id',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 64,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'uuid',
            ])
            ->addColumn('slug', 'string', [
                'null' => false,
                'limit' => 32,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'name',
            ])
            ->addColumn('short_slug', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 32,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'slug',
            ])
            ->addColumn('currency_id', 'integer', [
                'null' => false,
                'default' => '2',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'short_slug',
            ])
            ->addColumn('settings', 'text', [
                'null' => true,
                'limit' => MysqlAdapter::TEXT_LONG,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'currency_id',
            ])
            ->addColumn('is_default', 'boolean', [
                'null' => false,
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'settings',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_default',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_deleted',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'after' => 'created_at',
            ])
            ->addIndex(['apps_id'], [
                'name' => 'apps_id',
                'unique' => false,
            ])
            ->addIndex(['companies_id'], [
                'name' => 'companies_id',
                'unique' => false,
            ])
            ->addIndex(['uuid'], [
                'name' => 'uuid',
                'unique' => false,
            ])
            ->addIndex(['slug'], [
                'name' => 'slug',
                'unique' => false,
            ])
            ->addIndex(['short_slug'], [
                'name' => 'short_slug',
                'unique' => false,
            ])
            ->addIndex(['currency_id'], [
                'name' => 'currency_id',
                'unique' => false,
            ])
            ->addIndex(['is_default'], [
                'name' => 'is_default',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
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
            ->addColumn('products_variants_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('warehouse_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'products_variants_id',
            ])
            ->addColumn('price', 'decimal', [
                'null' => true,
                'precision' => '10',
                'scale' => '2',
                'after' => 'warehouse_id',
            ])
            ->addColumn('from_date', 'datetime', [
                'null' => false,
                'comment' => 'changed date',
                'after' => 'price',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'from_date',
            ])
            ->addColumn('is_deletd', 'boolean', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'created_at',
            ])
            ->addIndex(['from_date'], [
                'name' => 'from_date',
                'unique' => false,
            ])
            ->addIndex(['price'], [
                'name' => 'price',
                'unique' => false,
            ])
            ->addIndex(['created_at'], [
                'name' => 'created_at',
                'unique' => false,
            ])
            ->addIndex(['is_deletd'], [
                'name' => 'is_deletd',
                'unique' => false,
            ])
            ->create();
        $this->table('products', [
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
                'limit' => '10',
                'identity' => 'enable',
            ])
            ->addColumn('uuid', 'char', [
                'null' => false,
                'limit' => 36,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'id',
            ])
            ->addColumn('apps_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'uuid',
            ])
            ->addColumn('companies_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'apps_id',
            ])
            ->addColumn('name', 'text', [
                'null' => false,
                'limit' => MysqlAdapter::TEXT_LONG,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'companies_id',
            ])
            ->addColumn('slug', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'name',
            ])
            ->addColumn('description', 'text', [
                'null' => false,
                'limit' => MysqlAdapter::TEXT_LONG,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'slug',
            ])
            ->addColumn('short_description', 'text', [
                'null' => true,
                'limit' => MysqlAdapter::TEXT_LONG,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'description',
            ])
            ->addColumn('warranty_terms', 'text', [
                'null' => true,
                'limit' => MysqlAdapter::TEXT_LONG,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'short_description',
            ])
            ->addColumn('upc', 'string', [
                'null' => true,
                'limit' => 190,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'warranty_terms',
            ])
            ->addColumn('is_published', 'boolean', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'upc',
            ])
            ->addColumn('is_deleted', 'boolean', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_published',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_deleted',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'after' => 'created_at',
            ])
            ->addIndex(['uuid'], [
                'name' => 'uuid',
                'unique' => false,
            ])
            ->addIndex(['apps_id'], [
                'name' => 'apps_id',
                'unique' => false,
            ])
            ->addIndex(['companies_id'], [
                'name' => 'companies_id',
                'unique' => false,
            ])
            ->addIndex(['slug'], [
                'name' => 'slug',
                'unique' => false,
            ])
            ->addIndex(['is_published'], [
                'name' => 'is_published',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
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
            ->create();
        $this->table('products_warehouse', [
            'id' => false,
            'primary_key' => ['products_id', 'warehouse_id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('products_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('warehouse_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'products_id',
            ])
            ->addColumn('rating', 'integer', [
                'null' => true,
                'limit' => '1',
                'after' => 'warehouse_id',
            ])
            ->addColumn('is_published', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'rating',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_published',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_deleted',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'after' => 'created_at',
            ])
            ->addIndex(['rating'], [
                'name' => 'rating',
                'unique' => false,
            ])
            ->addIndex(['is_published'], [
                'name' => 'is_published',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
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
            ->create();
        $this->table('categories', [
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
            ->addColumn('apps_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('companies_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'apps_id',
            ])
            ->addColumn('uuid', 'char', [
                'null' => false,
                'limit' => 36,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'companies_id',
            ])
            ->addColumn('name', 'text', [
                'null' => false,
                'limit' => MysqlAdapter::TEXT_LONG,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'uuid',
            ])
            ->addColumn('slug', 'string', [
                'null' => false,
                'limit' => 64,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'name',
            ])
            ->addColumn('code', 'char', [
                'null' => false,
                'limit' => 36,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'slug',
            ])
            ->addColumn('position', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_MEDIUM,
                'after' => 'code',
            ])
            ->addColumn('parent_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_MEDIUM,
                'after' => 'position',
            ])
            ->addColumn('is_published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'parent_id',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_published',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_deleted',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'after' => 'created_at',
            ])
            ->addIndex(['apps_id'], [
                'name' => 'apps_id',
                'unique' => false,
            ])
            ->addIndex(['companies_id'], [
                'name' => 'companies_id',
                'unique' => false,
            ])
            ->addIndex(['uuid'], [
                'name' => 'uuid',
                'unique' => false,
            ])
            ->addIndex(['slug'], [
                'name' => 'slug',
                'unique' => false,
            ])
            ->addIndex(['code'], [
                'name' => 'code',
                'unique' => false,
            ])
            ->addIndex(['position'], [
                'name' => 'position',
                'unique' => false,
            ])
            ->addIndex(['parent_id'], [
                'name' => 'parent_id',
                'unique' => false,
            ])
            ->addIndex(['is_published'], [
                'name' => 'is_published',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
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
            ->create();


        $table = $this->table('products_attributes');
        $table->addForeignKey(
            'products_id',
            'products',
            'id',
            ['constraint' => 'FK_products_attributes_categories'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );
        $table->addForeignKey(
            'attributes_id',
            'attributes',
            'id',
            ['constraint' => 'FK_products_attributes_attributes'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );
        $table->save();

        $table = $this->table('categories_products');
        $table->addForeignKey(
            'products_id',
            'products',
            'id',
            ['constraint' => 'FK_categories_products_products'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );
        $table->addForeignKey(
            'categories_id',
            'categories',
            'id',
            ['constraint' => 'FK_categories_products_categories'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );
        $table->save();

        $table = $this->table('products_variants');
        $table->addForeignKey(
            'products_id',
            'products',
            'id',
            ['constraint' => 'FK_products_variants_products'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );
        $table->save();


        $table = $this->table('products_variants_attribute_values');
        $table->addForeignKey(
            'products_variants_id',
            'products_variants',
            'id',
            ['constraint' => 'FK_products_variants_attributes_products_attributes_variant'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );
        $table->addForeignKey(
            'products_attributes_values_id',
            'products_attribute_values',
            'id',
            ['constraint' => 'FK_products_variants_attributes_products_attributes_values'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );
        $table->save();

        $table = $this->table('products_variants_warehouse');
        $table->addForeignKey(
            'products_variants_id',
            'products_variants',
            'id',
            ['constraint' => 'FK_products_variants_warehouse_products_variant'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );
        $table->addForeignKey(
            'warehouse_id',
            'warehouses',
            'id',
            ['constraint' => 'FK_products_variants_warehouse_warehouse'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );
        $table->save();


        $table = $this->table('products_variants_warehouse_price_history');
        $table->addForeignKey(
            'products_variants_id',
            'products_variants',
            'id',
            ['constraint' => 'FK_products_variants_warehouse_price_products_variant'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );
        $table->addForeignKey(
            'warehouse_id',
            'warehouses',
            'id',
            ['constraint' => 'FK_products_variants_warehouse_price_warehouse'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );
        $table->save();

        $table = $this->table('products_warehouse');
        $table->addForeignKey(
            'products_id',
            'products',
            'id',
            ['constraint' => 'FK_products_warehouse_products'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );
        $table->addForeignKey(
            'warehouse_id',
            'warehouses',
            'id',
            ['constraint' => 'FK_products_warehouse_warehouse'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );
        $table->save();
    }
}
