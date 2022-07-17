<?php


class AddAttributeLabel extends Phinx\Migration\AbstractMigration
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
            ->addColumn('label', 'string', [
                'null' => false,
                'limit' => 150,
                'collation' => 'utf8mb4_unicode_520_ci',
                'encoding' => 'utf8mb4',
                'after' => 'name',
            ])
            ->save();
    }
}
