<?php

use App\Service;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $doctors = User::all();

        $services = [
            'Visita di controllo',
            'Visita dermatologica',
            'Consulenza online',
            'Tampone',
            'Certificato anamnestico per patente di guida',
            'Elettrocardiogramma',
            'Ecografia',
            'Visita a domicilio',
            'Visita cardiologica + elettrocardiogramma (ECG)',
            'Ozonoterapia',
            'Trattamento della Sindrome da stanchezza cronica',
        ];

        foreach ($doctors as $doctor) {

            $r = rand(2, 8);
            if ($r % 2 != 0) {
                $r++;
            }
            for ($i = 0; $i < $r; $i++) { 

                $newService = new Service();
                $newService->user_id = $doctor->id;

                for ($j = 0; $j < count($services); $j++) {
                    $newService->service = $services[array_rand($services)];
                }

                // $newService->service = $faker->word();
                $newService->price = $faker->randomFloat(2, 50, 400);
                $newService->save();
            }
        }
    }
}
