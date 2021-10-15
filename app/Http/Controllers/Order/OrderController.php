<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Item;

class OrderController extends Controller
{
    public function __construct(){
        $this->middleware('auth:customer,web');
    }
    
    public function index()
    {
        $orders = Order::all();
        echo json_encode($orders);
    }

    public function store(Request $request)
    {
        $this->validator($request->all())->validate();

        $order = new Order();
        $order->bill_id = $request->bill_id;
        $order->item_id = $request->item_id;
        $order->quantity = $request->quantity;
        $order->price = $request->price;
        $order->save();

        $item_id = $request->item_id;
        $item=Item::where('id',$item_id) -> first();
        $item_quantity = $item->quantity;
        $new_item_quantity = $item_quantity - $request->quantity;

        DB::table('items')
        ->where('id',$item_id)
        ->update([
            'quantity' => $new_item_quantity,
        ]);

        return response()->json([
            'status'=>true,
            'message'=>'Order saved'
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $id = $request->id;
        $order=Item::where('id',$item_id) -> first();
        $old_order_quantity = $order->quantity;

        DB::table('orders')
        ->where('id',$id)
        ->update([
            'bill_id' => $request['bill_id'],
            'item_id' => $request['item_id'],
            'quantity' => $request['quantity'],
            'price' => $request['price'],
        ]);

        $item_id = $request->item_id;
        $item=Item::where('id',$item_id) -> first();
        $item_quantity = $item->quantity;
        $new_item_quantity = $item_quantity + $old_order_quantity - $request->quantity;

        DB::table('items')
        ->where('id',$item_id)
        ->update([
            'quantity' => $new_item_quantity,
        ]);

        return response()->json([
            'status'=>true,
            'message'=>'Order updated'
        ]);
    }

    public function destroy(Order $order)
    {
        $old_order_quantity = $order->quantity;

        $order->delete();

        $item_id = $order->item_id;
        $item=Item::where('id',$item_id) -> first();
        $item_quantity = $item->quantity;
        $new_item_quantity = $item_quantity + $old_order_quantity;

        DB::table('items')
        ->where('id',$item_id)
        ->update([
            'quantity' => $new_item_quantity,
        ]);

        return response()->json([
          'status'=>true,
          'message'=>'Order removed'
          ]);
    }
}
