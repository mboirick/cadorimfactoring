<?php


namespace App\Exports;

use App\Repositories\Backend\CashRepository;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class CashExport implements FromCollection
{
    /**
     * @var  CashRepository
     */
    protected $cashRepository;

    /**
     * @var array
     */
    private $params;

    /**
     * CashExport constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->cashRepository = new CashRepository();

        $this->params = $params;
    }
    public function collection()
    {
        return $this->cashRepository->getCashByCriterion($this->params);
    }
}