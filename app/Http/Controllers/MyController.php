<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;

use Illuminate\Http\Request;

class MyController extends Controller
{
    public function index() {
        // Fetch orders along with their associated product and payment details
        $orders = Order::with('product', 'payment')->get();
    
        return view('pay', compact('orders'));
    }
}
