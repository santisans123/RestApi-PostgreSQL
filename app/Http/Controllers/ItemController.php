<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // GET /items
    public function index(Request $request)
    {
        return $request->user()->items()->get();
    }

    // POST /items
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric',
            'stock'        => 'required|integer',
            'is_available' => 'boolean',
            'tags'         => 'array'
        ]);

        $item = $request->user()->items()->create($data);

        return response()->json(['message' => 'Item created', 'item' => $item], 201);
    }

    // PUT /items/{id}
    public function update(Request $request, $id)
    {
        $item = $request->user()->items()->findOrFail($id);

        $request->validate([
            'name'         => 'sometimes|string',
            'description'  => 'nullable|string',
            'price'        => 'sometimes|numeric',
            'stock'        => 'sometimes|integer',
            'is_available' => 'boolean',
            'tags'         => 'array'
        ]);

        $item->update($request->input());
        $item->save();

        return response()->json(['message' => 'Item updated', 'item' => $item]);
    }

    // DELETE /items/{id}
    public function destroy(Request $request, $id)
    {
        $item = $request->user()->items()->findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Item deleted']);
    }
}

