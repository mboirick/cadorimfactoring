<?php

namespace App\Repositories\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Coupon;

class CouponRepository
{
    public function getAll()
    {
        return Coupon::orderBy('created_at', 'DESC')
                    ->paginate(20);
    } 

    public function getById($id)
    {
        return Coupon::findOrFail($id);
    }

    public function create($params)
    {
        return Coupon::create($params);
    } 

    public function getCountByCode($code)
    {
        return Coupon::where('coupon_code',$code)->count();
    }
    
    public function getByStatus($status)
    {
        return Coupon::where('status', $status)->first();
    }
}
