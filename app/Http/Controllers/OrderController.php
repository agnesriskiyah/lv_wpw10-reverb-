<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $order = (object) [
            'id' => rand(1000, 9999),
            'total_amount' => 50000,
        ];

        // Memanggil trigger event OrderPlaced
        event(new OrderPlaced($order, $request->user()));

        return response()->json([
            'message' => 'Event OrderPlaced berhasil dipicu.',
            'order_id' => $order->id,
        ]);
    }
}
