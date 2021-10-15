<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index()
    {
        $items = Item::all();
        echo json_encode($items);
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

        $item = new Item();
        $item->name = $request->name;
        $item->price = $request->price;
        $item->quantity = $request->quantity;
        $item->save();

        return response()->json([
            'status'=>true,
            'message'=>'Item saved'
        ]);
    }

    public function update(Request $request, Item $item)
    {
        if(Gate::denies('owner_and_cashier')){
            return response()->json([
              'status'=>false,
              'message'=>'Only owner and Cashiers can get action'
              ]);
          }
        
        $id = $request->id;
                 
        $this->validator($request->all())->validate();

        DB::table('items')
        ->where('id',$id)
        ->update([
            'name' => $request['name'],
            'price' => $request['price'],
            'quantity' => $request['quantity'],
        ]);

        return response()->json([
            'status'=>true,
            'message'=>'Item updated'
        ]);
    }

    public function destroy(Item $item)
    {
        if(Gate::denies('owner_and_cashier')){
            return response()->json([
              'status'=>false,
              'message'=>'Only owner and Managers can get action'
              ]);
          }
    
        $item->delete();
        return response()->json([
          'status'=>true,
          'message'=>'Item removed'
          ]);
    }

    protected function validator(array $data)
  {
      return Validator::make($data, [
          'name' => ['required', 'string', 'max:255'],
          'price' => ['required', 'integer'],
          'quantity' => ['integer'],
      ]);
  }
}
