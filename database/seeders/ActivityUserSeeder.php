<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Activity;

class ActivityUserSeeder extends Seeder
{
    public function run(): void
    {
        // Lista de nombres de actividades
        $activityNames = [
            "Práctica 17: Partículas, átomos, moléculas",
            "Práctica 18: Mezclas homogéneas y heterogéneas",
            "Práctica 20: Elementos en seres vivos, tierra, universo",
            "Práctica 21: Modelo de Bohr",
            "Práctica 22: Molécula iónica y molecular",
            "Práctica 24: Compuesto iónico y molecular",
            "Práctica 30: Reacciones exotérmicas y endotérmicas",
        ];


        // Crear o encontrar cada actividad por su nombre
        $activities = collect($activityNames)->map(function ($name) {
            return Activity::firstOrCreate(['name' => $name]);
        });

        // Obtener el usuario con ID 1
        $user = User::find(1);

        if ($user) {
            foreach ($activities as $activity) {
                // Prevenir duplicados si se vuelve a correr el seeder
                if (!$user->activities()->where('activity_id', $activity->id)->exists()) {
                    $user->activities()->attach($activity->id, [
                        'done' => false,
                        'session' => null
                    ]);
                }
            }
        }
    }
}
