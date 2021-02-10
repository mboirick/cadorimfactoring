<?php

namespace App\Repositories\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Atlpay;

class AtlpayRepository
{
    public function create(array $params)
    {
        return Atlpay::create($params);
    }

    public function getIdByDate($dateFrom)
    {
        return Atlpay::where('date', '=', $dateFrom)->value('id');
    }

    public function getByDate($dateFrom, $dateTo)
    {
        return Atlpay::where('date', '>=', $dateFrom)
            ->where('date', '<=', $dateTo)
            ->orderBy('date', 'DESC')
            ->paginate(20);
    }

    public function getSumByDate($dateFrom, $dateTo, $column)
    {
        return Atlpay::where('date', '>=', $dateFrom)
                ->where('date', '<=', $dateTo)
                ->sum($column);
    }

    public function getDataExcelByDate($dateFrom, $dateTo)
    {
        return Atlpay::where('date', '>=', $dateFrom)
            ->where('date', '<=', $dateTo)
            ->get();
    }
}
