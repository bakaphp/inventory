<?php

use Phinx\Db\Adapter\MysqlAdapter;

class UpdateProductAttributes extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->table('products_attributes', [
            'id' => false,
            'primary_key' => ['products_id', 'attributes_id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->changeColumn('products_id', 'integer', [
                'null' => false,
                'limit' => '10',
            ])
            ->changeColumn('attributes_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'after' => 'products_id',
            ])
            ->addColumn('value', 'text', [
                'null' => true,
                'limit' => 65535,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'attributes_id',
            ])
            ->changePrimaryKey(['products_id', 'attributes_id'])
            ->removeColumn('id')
            ->removeIndexByName('products_id_attributes_id')
            ->save();


        $this->table('products_categories', [
            'id' => false,
            'primary_key' => ['categories_id', 'products_id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->changeColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_deleted',
            ])
            ->save();

        $this->table('products_variants_warehouse', [
            'id' => false,
            'primary_key' => ['products_variants_id', 'warehouse_id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('is_coming_soon', 'boolean', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'can_pre_order',
            ])
            ->changeColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_published',
            ])
            ->changeColumn('updated_at', 'datetime', [
                'null' => true,
                'after' => 'created_at',
            ])
            ->changeColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'updated_at',
            ])
            ->removeColumn('is_comming_son')
            ->removeIndexByName('is_comming_son')
            ->addIndex(['is_coming_soon'], [
                'name' => 'is_comming_son',
                'unique' => false,
            ])
            ->save();

        $this->table('products_variants', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->changeColumn('ean', 'string', [
                'null' => true,
                'limit' => 64,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'sku',
            ])
            ->changeColumn('barcode', 'string', [
                'null' => true,
                'limit' => 64,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'ean',
            ])
            ->save();

        $this->table('products_variants_warehouse_price_history', [
            'id' => false,
            'primary_key' => ['products_variants_id', 'warehouse_id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('is_deleted', 'boolean', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'created_at',
            ])
            ->removeColumn('is_deletd')
            ->removeIndexByName('is_deletd')
            ->addIndex(['is_deleted'], [
                'name' => 'is_deletd',
                'unique' => false,
            ])
            ->save();

        $this->table('products', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->changeColumn('description', 'text', [
                'null' => true,
                'limit' => MysqlAdapter::TEXT_LONG,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'slug',
            ])
            ->save();
        $this->table('products_variants_attributes', [
            'id' => false,
            'primary_key' => ['products_variants_id', 'attributes_id'],
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
            ->addColumn('attributes_id', 'integer', [
                'null' => false,
                'limit' => '10',
                'after' => 'products_variants_id',
            ])
            ->addColumn('value', 'text', [
                'null' => true,
                'limit' => 65535,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'attributes_id',
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
            ->changeColumn('position', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_MEDIUM,
                'after' => 'code',
            ])
            ->changeColumn('parent_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_MEDIUM,
                'after' => 'position',
            ])
            ->changeColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_deleted',
            ])
            ->save();
        $this->table('products_variants_attribute_values')->drop()->save();
        $this->table('products_attribute_values')->drop()->save();


        $table = $this->table('products_variants_attributes');
        $table->addForeignKey(
            'products_variants_id',
            'products_variants',
            'id',
            ['constraint' => 'FK_products_Variants_attributes_products_variants'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );
        $table->save();
        $table = $this->table('products_variants_attributes');
        $table->addForeignKey(
            'attributes_id',
            'attributes',
            'id',
            ['constraint' => 'FK_products_Variants_attributes_products_attribute'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );
        $table->save();

        $table = $this->table('warehouses');
        $table->addForeignKey(
            'regions_id',
            'regions',
            'id',
            ['constraint' => 'FK_warehouse_regions'],
            ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
        );
        $table->save();
    }
}
