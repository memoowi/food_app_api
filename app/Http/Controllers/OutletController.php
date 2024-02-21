<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Outlet;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index()
    {
        $outlets = Outlet::all();
        return view('pages.home', compact('outlets'));
    }

    public function create()
    {
        return view('pages.new-outlet');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'open_time' => 'required',
            'close_time' => 'required',
        ]);
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = substr(str_shuffle($characters), 0, 6);
        $code = $randomString;
        $outlet = Outlet::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'code' => $code,
            'open_time' => $request->open_time,
            'close_time' => $request->close_time,
        ]);

        $outlet->save();
        return redirect('/home');
    }
    public function updateCode($id)
    {
        $outlet = Outlet::find($id);
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = substr(str_shuffle($characters), 0, 6);
        $code = $randomString;
        $outlet->code = $code;
        $outlet->save();
        return redirect('/home');
    }
    public function show($id)
    {
        $outlet = Outlet::find($id);
        $menus = $outlet->menus;
        $transactions = $outlet->transactions;
        return view('pages.show-outlet', compact('outlet', 'menus', 'transactions'));
    }
    public function createMenu($id)
    {
        $outlet = Outlet::find($id);
        return view('pages.new-menu', compact('outlet'));
    }
    public function storeMenu(Request $request, $id)
    {
        $outlet = Outlet::find($id);
        $request->validate([
            'name' => 'required',
            'image' => 'required',
            'type' => 'required|in:beverage,food',
            'price' => 'required|numeric',
        ]);
        $renameImage = time() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(public_path('images'), $renameImage);
        $imagePath = 'images/' . $renameImage;
        $outlet->menus()->create([
            'outlet_id' => $outlet->id,
            'name' => $request->name,
            'image_url' => $imagePath,
            'type' => $request->type,
            'price' => $request->price,
        ]);
        return redirect()->route('outlets.show', $outlet->id);
    }
    public function deleteMenu($id)
    {
        $menu = Menu::find($id);
        $deleteFile = public_path($menu->image_url);
        unlink($deleteFile);
        $menu->delete();
        return redirect()->back();
    }
    public function updateTransaction($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction->status == 'waitlist') {
            $transaction->update(['status' => 'done']);
        } else if ($transaction->status == 'done') {
            $transaction->update(['status' => 'paid']);
        }
        return redirect()->back();
    }
}
