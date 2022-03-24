<?php


class UniqueUuids extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->table('products_categories', [
            'id' => false,
            'primary_key' => ['categories_id', 'products_id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addIndex(['categories_id', 'products_id'], [
                'name' => 'categories_id_products_iduni',
                'unique' => true,
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
            ->addIndex(['uuid'], [
                'name' => 'unique_uuid',
                'unique' => true,
            ])
            ->save();

        $this->table('attributes', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addIndex(['uuid'], [
                'name' => 'unique_uuid',
                'unique' => true,
            ])
            ->save();

        $this->table('regions', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addIndex(['uuid'], [
                'name' => 'unique_uuid',
                'unique' => true,
            ])
            ->save();

        $this->table('warehouses', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addIndex(['uuid'], [
                'name' => 'unique_uuid',
                'unique' => true,
            ])
            ->save();

        $this->table('channels', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addIndex(['uuid'], [
                'name' => 'unique_uuid',
                'unique' => true,
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
            ->addIndex(['uuid'], [
                'name' => 'unique_uuid',
                'unique' => true,
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



            ->addIndex(['uuid'], [
                'name' => 'unique_uuid',
                'unique' => true,
            ])
            ->save();
    }
}
