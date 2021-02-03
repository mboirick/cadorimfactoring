<?php


namespace App\Exports;

use App\Repositories\Backend\CoordinatedOrdersRepository;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class CashOutExport implements FromCollection
{
    /**
     * @var  CoordinatedOrdersRepository
     */
    protected $coordinatedOrdersRepository;

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
        $this->coordinatedOrdersRepository = new CoordinatedOrdersRepository();

        $this->params = $params;
    }

    public function collection()
    {
        return $this->coordinatedOrdersRepository->getOrderByCriterion($this->params);
    }
}