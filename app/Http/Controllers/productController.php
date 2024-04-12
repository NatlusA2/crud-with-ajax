<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Datatables;

class productController extends Controller
{
    public function index() {
        if(request()->ajax()) {
            return datatables()->of(product::select('*'))
            ->addColumn('action', 'product-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('index');

    }

    public function store(Request $request) {
        $productId = $request->id;

        $product = product::updateOrCreate(
            [
                'id' => $productId

            ],
            [
                'name' => $request->name,
                'desc' => $request->desc,
                'harga' => $request->harga
            ]);

            return Response()->json($product);
    }

    public function edit(Request $request)
    {   
        $where = array('id' => $request->id);
        $product  = product::where($where)->first();
       
        return Response()->json($product);
    }

    public function destroy(Request $request)
    {
        $product = product::where('id',$request->id)->delete();
       
        return Response()->json($product);
    }

}
