<?php


namespace App\Exports;


use App\Repositories\Backend\UserRepository;
use Maatwebsite\Excel\Concerns\FromCollection;

class AgencyExport implements FromCollection
{
    /**
     * @var  UserRepository
     */
    protected $userRepositor;

    /**
     * AgencyExport constructor.
     */
    public function __construct()
    {
        $this->userRepositor = new UserRepository();
    }

    public function collection()
    {
        return $this->userRepositor->getByTypeAndIndex('operateur', '1');
    }
}