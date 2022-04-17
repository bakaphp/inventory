<?php


class UpUpdatePricePrimarykeys extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->table('products_variants_warehouse_price_history', [
            'id' => false,
            'primary_key' => ['products_variants_id', 'warehouse_id', 'channels_id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_520_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->save();
    }
}
