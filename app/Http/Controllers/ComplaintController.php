<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    // Create pengaduan publik
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'required|image|max:2048',
        ]);

        $path = $request->file('photo')->store('complaints', 'public');

        $complaint = Complaint::create([
            'title' => $request->title,
            'description' => $request->description,
            'photo_path' => $path,
        ]);

        return response()->json([
            'message' => 'Pengaduan berhasil dikirim.',
            'data' => $complaint
        ], 201);
    }

    // Read semua pengaduan publik
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10); // default 10 jika tidak ditentukan
        $data = Complaint::latest()->paginate($limit);

        return response()->json([
            'message' => 'List semua pengaduan',
            'data' => $data
        ]);
    }
}
