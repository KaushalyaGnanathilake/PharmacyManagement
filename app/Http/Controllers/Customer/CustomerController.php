<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Gate;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $customers = Customer::all();
        echo json_encode($customers);
    }

    public function store(Request $request)
    {
        if(Gate::denies('owner')){
            return response()->json([
              'status'=>false,
              'message'=>'Only owner can get action'
              ]);
          }
         
        $this->validator($request->all())->validate();
        $password = $request->password;

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->$password = Hash::make($request['password']);
        $customer->mobile_no = $request->mobile_no;
        $customer->nic_no = $request->nic_no;
        $customer->date_of_birth = $request->date_of_birth;
        $customer->save();

        return response()->json([
            'status'=>true,
            'message'=>'Customer account created'
        ]);
    }

    public function edit(Customer $customer)
    {
        if(Gate::denies('owner_and_manager')){
            return redirect()->back();
        }
        $customers = Customer::all();
        return $customers;
    }

    public function update(Request $request, Customer $customer)
    {
        if(Gate::denies('owner_and_manager')){
            return response()->json([
              'status'=>false,
              'message'=>'Only owner and Managers can get action'
              ]);
          }
        
        $id = $request->id;
                 
        $this->validator($request->all())->validate();

        DB::table('customers')
        ->where('id',$id)
        ->update([
            'name' => $request['name'],
            'mobile_no' => $request['mobile_no'],
            'nic_no' => $request['nic_no'],
            'date_of_birth' =>$request['date_of_birth'],
            'address' => $request['address'],
        ]);

        return response()->json([
            'status'=>true,
            'message'=>'Account updated'
        ]);
    }

    public function destroy(Customer $customer)
    {
        if(Gate::denies('owner_and_manager')){
            return response()->json([
              'status'=>false,
              'message'=>'Only owner and Managers can get action'
              ]);
          }
        
        $customer->delete();
        return response()->json([
          'status'=>true,
          'message'=>'Account removed'
          ]);
        
    }

    protected function validator(array $data)
  {
      return Validator::make($data, [
          'name' => ['required', 'string', 'max:255'],
          'email' => ['required', 'string', 'max:255', 'unique'],
          'password' => ['required', 'string', 'min:8', 'confirmed'],
          'mobile_no' => ['max:16'],
          'nic_no' => ['required', 'string', 'max:12'],
          'date_of_birth' =>['required', 'date'],
          'address' => ['required', 'string', 'max:255'],
      ]);
  }
}
