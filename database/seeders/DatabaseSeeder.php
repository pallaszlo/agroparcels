<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\FitoProduct;
use App\Models\Group;
use App\Models\Layer;
use App\Models\Parcel;
use App\Models\Person;
use App\Models\Season;
use App\Models\Status;
use App\Models\Treatment;
use App\Models\Work;
use App\Models\Work_Machine;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesAndPermissions::class);
        // $seasonsArr = ['winter', 'spring', 'summer', 'fall'];
        // $seasons = [];

        // foreach($seasonsArr as $season) {
        //     array_push($seasons, Season::factory()->name($season)->create());
        // }

        // $seasons  = collect($seasons);
        $this->call(CompanySeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(CultureSeeder::class);
        $this->call(FitoProductSeeder::class);
        $this->call(DiseaseSeeder::class);
        $this->call(ParcelSeeder::class);
        $this->call(SeasonSeeder::class);
        $this->call(PersonSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(WorkSeeder::class);
        $parcels = Parcel::all();

        foreach($parcels as $parcel){
            Layer::factory()->for($parcel)->count(rand(0,5))->create();
        }
        $layers = Layer::all();

        $work_machines = Work_Machine::factory()->count(rand(10, 20))->create();

        //$persons = Person::factory()->count(rand(20, 30))->create();

       $works = Work::all();
       $persons = Person::all();

        $treatments = Treatment::factory()->count(rand(10, 20))->create();

        $this->call(OperationSeeder::class);

        //attach a random layer, season, treatment, person and work_machine to each work
        foreach($works as $work){
            //$work->layers()->attach($layers->random(), ['season_uuid' => $seasons->random()->uuid, 'treatment_uuid' => $treatments->random()->uuid, 'treatment_quantity' => rand(0,100)]);
            for($i = 0; $i < rand(1,3); $i++){
                $work->persons()->attach($persons->random());
            }

            //$work->work_machines()->attach($work_machines->random(), ['person_uuid' => $persons->random()->uuid]);
        }

    }
}
