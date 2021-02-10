<?php


namespace App\Exports;


use App\Repositories\Backend\AtlpayRepository;
use Maatwebsite\Excel\Concerns\FromCollection;

class AtlpayExport implements FromCollection
{
    /**
     * @var  UserRepository
     */
    protected $userRepositor;

    protected $dateFrom;

    protected $dateTo;

    /**
     * AgencyExport constructor.
     */
    public function __construct($dateFrom, $dateTo)
    {
        $this->atlpayRepositor = new AtlpayRepository();

        $this->dateFrom = $dateFrom;
        $this->dateTo   = $dateTo;
    }

    public function collection()
    {
        return $this->atlpayRepositor->getDataExcelByDate($this->dateFrom, $this->dateTo);
    }
}