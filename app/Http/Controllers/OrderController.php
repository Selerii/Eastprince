<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Product;
use App\Models\User;

class OrderController extends Controller
{

     public function showPersonalInfoForm($id)
     {
         $product = Product::findOrFail($id);
     
     
         return view('info.pageuser')->with('product', $product);
        
     }
     
    public function index()
    {
        {
            $order = Order::orderBy('created_at', 'DESC')->get();
      
            return view('orders.index', compact('order'));
        }
    }

    public function indexx()
    {
        {
            $order = Order::orderBy('created_at', 'DESC')->get();
      
            return view('info.pageuser', compact('order'));
        }
    }

 
    public function create()
    {
        {
            return view('orders.create');
        }
    }

  
    public function store(Request $request)
    {
     
            $validatedData = $request->validate([
                'customer_name' => 'required',
                'email' => 'required',
                'address' => 'required',
                'contact_number' => 'required',
                'product_id' => 'required|exists:products,id', 
                'layout' => 'required',
                'size' => 'required',
                'quantity' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                
            ]);
    
            $refNum = str_pad(rand(0, pow(10, 11) - 1), 11, '0', STR_PAD_LEFT);
    
            while (Order::where('ref_num', $refNum)->exists()) {
                $refNum = str_pad(rand(0, pow(10, 11) - 1), 11, '0', STR_PAD_LEFT);
            }
    
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $validatedData['image'] = $imagePath;
            }
    
          
            $validatedData['ref_num'] = $refNum;
            $validatedData['status'] = 'pending'; 
    
            $order = Order::create($validatedData);
    
         
    
            return redirect()->route('pay', ['id' => $order->id]);
       
    }
    

    public function show(string $id)
    {
        $order = Order::findOrFail($id);
  
        return view('orders.show', compact('order'));
    }

  
    public function edit(string $id)
    {
        {
            $order = Order::findOrFail($id);
      
            return view('orders.edit', compact('order'));
        }
    }

 
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $order->update(array_merge(
            $request->all(),
            ['status' => $request->input('status')] 
        ));

        return redirect()->route('orders')->with('success', 'Order updated successfully');
    }
  
    public function destroy(string $id)
    {
        $product = Order::findOrFail($id);
  
        $product->delete();
  
        return redirect()->route('orders')->with('success', 'orders deleted successfully');
    }


public function approve($id)
{
    $order = Order::findOrFail($id);
    $order->status = 'approved';
    $order->save();


    return redirect()->route('orders', $id)->with('success', 'Order approved successfully');
}

public function decline($id)
{
    $order = Order::findOrFail($id);
    $order->status = 'declined';
    $order->save();


    return redirect()->route('orders', $id)->with('success', 'Order declined successfully');
}
public function updateStatus(Request $request, $id)
{
    $order = Order::findOrFail($id);

    $request->validate([
        'status' => 'required|in:approved,declined', 
    ]);

    $order->status = $request->input('status');
    $order->save();


    return redirect()->route('orders', $id)->with('success', 'Order status updated successfully');
}

public function showpayment($id)
{
    $order = Order::find($id); 

    return view('pay.payuser', compact('order'));
}

}


