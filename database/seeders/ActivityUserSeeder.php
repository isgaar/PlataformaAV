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
            "Práctica PA17",
            "Práctica PA18",
            "Práctica PA20",
            "Práctica PA21",
            "Práctica PA22",
            "Práctica PA24",
            "Práctica PA30",
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
