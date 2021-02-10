<?php

namespace App\Http\Controllers\Backend\Agency;

use App\Exports\AgencyExport;
use App\Repositories\Backend\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    protected function setPermission()
    {
        $this->middleware('permission:admin-Agency', 
        ['only' => ['agency']]);
    }

    /**
     * @var  UserRepository
     */
    protected $userRepositor;

    /**
     * ExportController constructor.
     * @param UserRepository $userRepositor
     */
    public function __construct(UserRepository $userRepositor)
    {
        $this->userRepositor = $userRepositor;
        $this->middleware('auth');
    }

    /**
     * @param $type
     * @return mixed
     */
    public function agency()
    {
        return Excel::download(new AgencyExport, 'agencies.xlsx');
    }
}
