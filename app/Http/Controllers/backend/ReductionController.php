<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReductionController extends BaseController
{
    protected function setPermission()
    {
        $this->middleware('permission:coupon', ['only' => ['index', 'create','store', 'edit', 'update', 'destroy', 'applycoupon']]);
    }

    public function index()
    {
        $menu_active = 4;

        $promos = $this->couponRepository->getAll();

        return view('backend.reduction.index',compact('menu_active','promos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.reduction.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $request['coupon_code']=substr(str_shuffle($permitted_chars), 0, 7);
        
        $this->validate($request,[
            'coupon_code'=>'required|min:5|max:15|unique:coupons,coupon_code,',
            'amount'=>'required|numeric|between:1,99',
            'expiry_date'=>'required|date'
        ]);

        $input_data = $request->all();
    
        if(empty($input_data['status'])){
            $input_data['status']=0;
        }

        $this->couponRepository->create($input_data);
        //Coupon_model::create($input_data);
        return redirect()->route('reduce.index')->with('message','le code promo a été bien créé');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $coupon = $this->couponRepository->getById($id);

        return view('backend.reduction.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $coupon = $this->couponRepository->getById($id);
        $this->validate( $request, [ 'amount' => 'required|numeric|between:1,99', 'expiry_date'=>'required|date']);

        $inputData = $request->all();
        if(empty($inputData['status']))
            $inputData['status'] = 0;

        $coupon->update($inputData);

        return redirect()->route('reduce.index')->with('message','Modification effectuée');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $coupon = $this->couponRepository->getById($id);
        //$coupon=Coupon_model::findOrFail($id);
        $coupon->delete();
        return back()->with('message','Supression effectuée!');
    }

    public function applycoupon(Request $request)
    {
        $this->validate($request,[
            'coupon_code'=>'required'
        ]);
        
        $input_data         = $request->all();
        $coupon_code        = $input_data['coupon_code'];
        $total_amount_price = $input_data['Total_amountPrice'];

        $check_coupon = $this->couponRepository->getCountByCode($coupon_code);
        //$check_coupon       =   Coupon_model::where('coupon_code',$coupon_code)->count();
        if($check_coupon == 0){
            return back()->with('message_coupon','Your Coupon Code Not Exist!');
        }else if($check_coupon == 1){
            $check_status  = $this->couponRepository->getByStatus(1);
            //$check_status  =Coupon_model::where('status',1)->first();
            if($check_status->status == 0){
                return back()->with('message_coupon','Your Coupon was Disabled!');
            }else{
                $expiried_date  =   $check_status->expiry_date;
                $date_now       =   date('Y-m-d');
                if($expiried_date<$date_now) {
                    return back()->with('message_coupon','Your Coupon was Expired!');
                } else {
                    $discount_amount_price  =   ($total_amount_price*$check_status->amount)/100;
                    Session::put('discount_amount_price',$discount_amount_price);
                    Session::put('coupon_code',$check_status->coupon_code);

                    return back()->with('message_apply_sucess','Your Coupon Code was Apply');
                }
            }
        }
    }
}
