<?php

class AddUniquelugs extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->table('products_variants', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addIndex(['products_id', 'slug'], [
                'name' => 'products_id_slug',
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
            ->addIndex(['companies_id', 'slug'], [
                'name' => 'companies_id_slug',
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
            ->addIndex(['companies_id', 'slug'], [
                'name' => 'companies_id_slug',
                'unique' => true,
            ])
            ->save();
    }
}
