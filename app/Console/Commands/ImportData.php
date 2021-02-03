<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Backend\CashRepository;

class ImportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importe les factures';

     /**
     * @var  CashRepository
     */
    private $cashRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->cashRepository = new CashRepository();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->cashRepository->addFilesInvonces();

       echo 'Les ont été importé avec succés';
    }
}
