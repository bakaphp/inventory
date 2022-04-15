<?php


class UniqueSku extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->table('products_variants_warehouse', [
            'id' => false,
            'primary_key' => ['products_variants_id', 'warehouse_id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addIndex(['sku'], [
                'name' => 'uniquesku',
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
            ->addIndex(['sku'], [
                'name' => 'uniquesku',
                'unique' => true,
            ])
            ->save();
    }
}
