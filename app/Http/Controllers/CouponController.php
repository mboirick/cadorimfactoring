<?php

namespace App\Http\Controllers;

use App\Coupon_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
      $this->middleware('auth');
    }

    
    /*public function index()
    {
        $menu_active=4;
        $promos=Coupon_model::orderBy('created_at', 'DESC')
        ->paginate(20);


        return view('coupon.index',compact('menu_active','promos'));
    }*/

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function create()
    {
        
        return view('coupon.create');
    }*/

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*public function store(Request $request)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $request['coupon_code']=substr(str_shuffle($permitted_chars), 0, 7);
        
        $this->validate($request,[
            'coupon_code'=>'required|min:5|max:15|unique:coupons,coupon_code,',
            'amount'=>'required|numeric|between:1,99',
            'expiry_date'=>'required|date'
        ]);
        $input_data=$request->all();
    
        if(empty($input_data['status'])){
            $input_data['status']=0;
        }
        Coupon_model::create($input_data);
        return redirect()->route('coupon.index')->with('message','le code promo a été bien créé');
    }*/

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function show($id)
    {
        //
    }*/

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function edit($id)
    {
              $edit_coupons=Coupon_model::findOrFail($id);
        return view('coupon.edit',compact('edit_coupons'));
    }*/

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function update(Request $request, $id)
    {
        $update_coupon=Coupon_model::findOrFail($id);
        $this->validate($request,[
           
            'amount'=>'required|numeric|between:1,99',
            'expiry_date'=>'required|date'
        ]);
        $input_data=$request->all();
        if(empty($input_data['status'])){
            $input_data['status']=0;
        }
        $update_coupon->update($input_data);
        return redirect()->route('coupon.index')->with('message','Modification effectuée');
    }*/

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function destroy($id)
    {
        $delete_coupon=Coupon_model::findOrFail($id);
        $delete_coupon->delete();
        return back()->with('message','Supression effectuée!');
    }
    public function applycoupon(Request $request){
        $this->validate($request,[
            'coupon_code'=>'required'
        ]);
        $input_data=$request->all();
        $coupon_code=$input_data['coupon_code'];
        $total_amount_price=$input_data['Total_amountPrice'];
        $check_coupon=Coupon_model::where('coupon_code',$coupon_code)->count();
        if($check_coupon==0){
            return back()->with('message_coupon','Your Coupon Code Not Exist!');
        }else if($check_coupon==1){
            $check_status=Coupon_model::where('status',1)->first();
            if($check_status->status==0){
                return back()->with('message_coupon','Your Coupon was Disabled!');
            }else{
                $expiried_date=$check_status->expiry_date;
                $date_now=date('Y-m-d');
                if($expiried_date<$date_now){
                    return back()->with('message_coupon','Your Coupon was Expired!');
                }else{
                    $discount_amount_price=($total_amount_price*$check_status->amount)/100;
                    Session::put('discount_amount_price',$discount_amount_price);
                    Session::put('coupon_code',$check_status->coupon_code);
                    return back()->with('message_apply_sucess','Your Coupon Code was Apply');
                }
            }
        }
    }*/
}