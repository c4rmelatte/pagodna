<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purpose;
use App\Models\Announcements;
use App\Models\Payment;
use App\Models\TotalFunds;
use Carbon\Carbon;

class TreasuryController extends Controller
{
    //

    //*********************treasury dashboard***************************
    public function index(){
        $products = Purpose::all();
        $announcements = Announcements::all();
        $payments = Payment::all();
        $funds = TotalFunds::firstOrCreate(
            ['funds'=>request(0)]);
            
        return view('treasury.pages.treasury', compact('products','announcements','payments','funds','tuitionFee'));
    }


    //**********************PURPOSE**************************

    public function createPurpose(){
        $products = Purpose::all();
        return view('treasury.pages.createpurpose', compact('products'));
    }

    public function storePurpose(Request $request) {

        Purpose::create([
            'name'=>$request->get('name'),
            'price'=>$request->get('price'),
            'type'=>$request->get('type')
        ]);

        return redirect()->to('/treasury');
        // return redirect()->route('treasury.pages.treasury');
    }

    public function editPurpose($id) {
        $product = Purpose::findOrFail($id);
        return view('treasury.pages.updatepurpose', compact('product'));
    }

    public function updatePurpose(Request $request, $id) {
        $product = Purpose::findOrFail($id);
    
        $product->update([
        'name' => $request->get('name'),
        'price' => $request->get('price'),
        'type'=>$request->get('type')
        ]);

        return redirect()->to('/treasury')->with('success', 'Product updated successfully.');
    }

    public function deletePurpose($id) {
        $product = Purpose::findOrFail($id);
        $product -> delete();
        return redirect()->to('/treasury')->with('success', 'Product deleted successfully.');
    }

    //**********************PAYMENT**************************
  
}