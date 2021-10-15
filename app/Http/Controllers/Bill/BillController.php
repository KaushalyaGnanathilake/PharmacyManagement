<?php

namespace App\Http\Controllers\Bill;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $bills = Bill::all();
        echo json_encode($bills);
    }

    public function store(Request $request)
    {
        if(Gate::denies('owner_and_cashier')){
            return response()->json([
              'status'=>false,
              'message'=>'Only Owner and Cashier can get action'
              ]);
          }
        
        $bill = new Bill();
        $bill->customer_id = $request->customer_id;
        $bill->amount = $request->amount;
        $customer->save();

        return response()->json([
            'status'=>true,
            'message'=>'Bill generated'
        ]);
    }

    public function update(Request $request, Bill $bill)
    {
        if(Gate::denies('owner_and_cashier')){
            return response()->json([
              'status'=>false,
              'message'=>'Only owner and Cashiers can get action'
              ]);
          }
        
        $id = $request->id;
                 
        $this->validator($request->all())->validate();

        DB::table('bills')
        ->where('id',$id)
        ->update([
            'customer_id' => $request['customer_id'],
            'amount' => $request['amount'],
        ]);

        return response()->json([
            'status'=>true,
            'message'=>'Bill updated'
        ]);
    }

    public function destroy(Bill $bill)
    {
        if(Gate::denies('owner_and_cashier')){
            return response()->json([
              'status'=>false,
              'message'=>'Only owner and Cashier can get action'
              ]);
          }

        $bill->delete();
        return response()->json([
          'status'=>true,
          'message'=>'Bill Cancelled'
          ]);
    }
}
