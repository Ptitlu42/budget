<?php

namespace App\Console;

use App\Models\History;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\ArchiveLastMonth::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Vérifier toutes les 10 minutes si on a changé de mois
        $schedule->call(function () {
            $now = Carbon::now();
            $lastMonth = $now->copy()->subMonth()->startOfMonth();

            // Si nous sommes dans un nouveau mois et que le mois précédent n'est pas archivé
            if ($now->day === 1 && ! History::where('month_year', $lastMonth)->exists()) {
                $this->call('history:archive-last-month');
            }
        })->everyTenMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
