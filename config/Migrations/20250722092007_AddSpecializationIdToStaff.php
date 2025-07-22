<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddSpecializationIdToStaff extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('staff');
        $table->addColumn('specialization_id', 'integer', [
            'null' => true,
            'after' => 'specialization',
        ])
        ->addForeignKey('specialization_id', 'specializations', 'id', [
            'delete' => 'SET_NULL',
            'update' => 'CASCADE',
        ])
        ->addIndex(['specialization_id'])
        ->update();
    }
}
