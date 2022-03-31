<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Session\Session as HttpFoundationSessionSession;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::latest()->get();
        $categories = Category::inRandomOrder()->take(3)->get();
        $orderItems = OrderItem::where('session_id', Session::getId())
                                    ->where('status', 'pending')->get();
        
        $total = 0;

        foreach ($orderItems as $key => $orderItem) {
            $total = $total + $orderItem->item_price;
        }

        return view('order.index', compact('items', 'categories', 'orderItems', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('order.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Order::create($request->all());

        $orderItems = OrderItem::where('session_id', '=', $request->session_id)->get();

        foreach ($orderItems as $key => $oi) {
            $oi->status = 'ordered';
            $oi->save();
        }

        return redirect()->back()->with('message', 'Order Placed Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('order.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('order.edit');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
