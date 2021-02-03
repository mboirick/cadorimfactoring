<?php


namespace App\Exports;

use App\Repositories\Backend\SubscribersRepository;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class SubscribersExport implements FromCollection
{
    /**
     * @var  SubscribersRepository
     */
    protected $subscribersRepository;

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
        $this->subscribersRepository = new SubscribersRepository();

        $this->params = $params;
    }

    public function collection()
    {
        return $this->subscribersRepository->getByCriterion($this->params);
    }
}