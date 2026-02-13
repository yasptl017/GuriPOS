<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\POS\POSTable;

class POSTableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $tables = POSTable::orderBy('id')->get();
        return view('admin.pos_tables', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:p_o_s_tables,name',
        ]);

        POSTable::create([
            'name'           => trim($request->name),
            'meta'           => [],
            'cart'           => [],
            'resolved_order' => [],
        ]);

        return redirect()->route('admin.pos-tables')->with([
            'messege'    => 'Table created successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function update(Request $request, $id)
    {
        $table = POSTable::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100|unique:p_o_s_tables,name,' . $id,
        ]);

        $table->name = trim($request->name);
        $table->save();

        return redirect()->route('admin.pos-tables')->with([
            'messege'    => 'Table updated successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function clearCart($id)
    {
        $table = POSTable::findOrFail($id);
        $table->cart           = [];
        $table->resolved_order = [];
        $table->meta           = [];
        $table->save();

        return redirect()->route('admin.pos-tables')->with([
            'messege'    => 'Cart cleared for table: ' . $table->name,
            'alert-type' => 'success',
        ]);
    }

    public function destroy($id)
    {
        $table = POSTable::findOrFail($id);

        // Prevent deleting the last table
        if (POSTable::count() <= 1) {
            return redirect()->route('admin.pos-tables')->with([
                'messege'    => 'Cannot delete the last table.',
                'alert-type' => 'error',
            ]);
        }

        $table->delete();

        return redirect()->route('admin.pos-tables')->with([
            'messege'    => 'Table deleted successfully.',
            'alert-type' => 'success',
        ]);
    }
}
