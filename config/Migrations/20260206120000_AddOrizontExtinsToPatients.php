<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddOrizontExtinsToPatients extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('patients');
        $table
            ->addColumn('orizont_extins_programare', 'boolean', [
                'default' => false,
                'null' => false,
                'after' => 'is_active',
            ])
            ->update();
    }
}
