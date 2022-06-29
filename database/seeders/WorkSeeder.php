<?php

namespace Database\Seeders;

use App\Models\Work;
use Illuminate\Database\Seeder;

class WorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Work::create([
            'name' => 'Szántás',
            'is_treatment_required' => false,
            'culture_action' => 'nothing'
        ]);

        Work::create([
            'name' => 'Vetés',
            'is_treatment_required' => false,
            'culture_action' => 'add'
        ]);

        Work::create([
            'name' => 'Műtrágyázás',
            'is_treatment_required' => true,
            'culture_action' => 'nothing'
        ]);

        Work::create([
            'name' => 'Permetezés',
            'is_treatment_required' => true,
            'culture_action' => 'nothing'
        ]);

        Work::create([
            'name' => 'Aratás',
            'is_treatment_required' => false,
            'culture_action' => 'remove'
        ]);

        Work::create([
            'name' => 'Begyűjtés',
            'is_treatment_required' => false,
            'culture_action' => 'remove'
        ]);
    }
}
