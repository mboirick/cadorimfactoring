<?php


namespace App\Exports;


use App\Repositories\Backend\UserRepository;
use Maatwebsite\Excel\Concerns\FromCollection;

class PaymentExport implements FromCollection
{
    /**
     * @var  UserRepository
     */
    protected $userRepositor;

    protected $params;

    /**
     * AgencyExport constructor.
     */
    public function __construct($params)
    {
        $this->userRepository = new UserRepository();

        $this->params = $params;
    }

    public function collection()
    {
        return $this->userRepository->getClientsByCriterion($this->params);
    }
}