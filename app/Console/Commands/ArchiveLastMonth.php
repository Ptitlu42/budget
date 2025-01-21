<?php

namespace App\Console\Commands;

use App\Http\Controllers\HistoryController;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ArchiveLastMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'history:archive-last-month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive the previous month in history';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $historyController = new HistoryController();
        $historyController->archiveMonth($lastMonth);
        $this->info('Le mois précédent a été archivé avec succès.');
    }
}
