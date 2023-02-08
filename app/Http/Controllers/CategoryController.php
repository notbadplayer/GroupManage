<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('category.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                // ->addColumn('action', function($row){
                //     $actionBtn = '<a href="" action=delete() class="btn btn-outline-danger btn-sm"><i class="fa-regular fa-trash-can"></i></a>';
                //     return $actionBtn;
                // })
                // ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);

        $category = Category::create([
            'name' => $request['name'],
        ]);

    }
    public function update(Request $request, Category $Category)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);

        $Category->update([
            'name' => $request['name'],
        ]);
    }

    public function destroy(Category $Category)
    {
        $Category->forceDelete();
    }
}
