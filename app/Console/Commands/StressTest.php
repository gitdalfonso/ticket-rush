<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class StressTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stress:test {concert_id} {count=20}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simula compras masivas concurrentes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $concertId = $this->argument('concert_id');
        $count = $this->argument('count');
        $url = route('orders.store', ['id' => $concertId]); // Asegúrate que esta URL sea accesible localmente

        $this->info("🔥 Iniciando ataque de $count peticiones simultáneas al concierto $concertId...");

        $startTime = microtime(true);

        // Http::pool permite enviar peticiones en paralelo (simultáneas)
        $responses = Http::pool(function ($pool) use ($url, $count) {
            $requests = [];
            for ($i = 0; $i < $count; $i++) {
                // Enviamos POST sin datos extra, asumiendo que desactivaste auth temporalmente
                $requests[] = $pool->post($url);
            }
            return $requests;
        });

        $duration = microtime(true) - $startTime;
        $this->info("✅ Ataque finalizado en " . number_format($duration, 2) . " segundos.");

        // Conteo rápido de resultados
        $success = 0;
        $fails = 0;
        $errors = 0;

        foreach ($responses as $response) {
            // Verificar si la respuesta es una Instancia válida de Response
            if ($response instanceof \Illuminate\Http\Client\Response) {
                if ($response->successful()) {
                    $success++;
                } else {
                    $fails++;
                    // ESTO ES CLAVE: Imprimir el error para saber por qué falla
                    $this->error("Fallo HTTP " . $response->status() . ": " . Str::limit($response->body(), 100));
                }
            } else {
                // Si entra aquí, es una ConnectionException (Server caído/saturado)
                $errors++;
                $this->error("Error de Conexión: " . $response->getMessage());
            }
        }

        $this->info("📊 Resultados:");
        $this->info("   ✅ Compras Exitosas: $success");
        $this->info("   ❌ Rechazadas (4xx/5xx): $fails");
        $this->info("   💥 Errores de Conexión: $errors");
    }
}
