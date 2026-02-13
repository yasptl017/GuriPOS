<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $homeCategories    = Category::where('show_homepage', 1)->where('status', 1)
            ->orderBy('home_sort_order')->orderBy('id')->get();
        $posCategories     = Category::where('status', 1)
            ->orderBy('pos_sort_order')->orderBy('id')->get();
        $receiptCategories = Category::where('status', 1)
            ->orderBy('receipt_sort_order')->orderBy('id')->get();

        return view('admin.category_order', compact('homeCategories', 'posCategories', 'receiptCategories'));
    }

    public function save(Request $request)
    {
        $context = $request->input('context');
        $column  = match ($context) {
            'home'    => 'home_sort_order',
            'pos'     => 'pos_sort_order',
            'receipt' => 'receipt_sort_order',
            default   => null,
        };

        if (!$column) {
            return response()->json(['error' => 'Invalid context'], 422);
        }

        foreach ($request->input('order', []) as $position => $id) {
            Category::where('id', (int) $id)->update([$column => $position + 1]);
        }

        return response()->json(['message' => 'Order saved successfully']);
    }
}
