<?php

namespace Database\Seeders;

use App\Models\Concert;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear un usuario de prueba
        User::factory()->create([
            'name' => 'Admin Developer',
            'email' => 'admin@ticketrush.com',
            'password' => bcrypt('password'),
        ]);

        // Crear EL Concierto
        $concert = Concert::create([
            'title' => 'Metallica - World Tour 2025',
            'location' => 'Estadio Centenario, Montevideo',
            'date' => now()->addDays(30), // Dentro de un mes
            'price' => 120.00,
            'total_tickets' => 50, // Solo 50 entradas
        ]);

        // Generar los 50 tickets individuales para ese concierto
        // Esto es CLAVE: En sistemas serios, el ticket existe ANTES de venderse
        Ticket::factory()
            ->count(50)
            ->create(['concert_id' => $concert->id]);

        $this->command->info('¡Concierto creado con 50 entradas listas!');
    }
}
