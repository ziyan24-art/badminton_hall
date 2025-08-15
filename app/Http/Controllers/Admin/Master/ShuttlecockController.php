<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Shuttlecock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShuttlecockController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;

        $shuttles = Shuttlecock::query()
            ->when($q, fn($query) => $query->where('brand', 'like', "%$q%"))
            ->latest()
            ->paginate(9);

        return view('admin.master.shuttlecock.index', compact('shuttles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'stock' => 'nullable|integer|min:0',
            'price' => 'required|integer|min:0',
            'is_available' => 'required|boolean',
        ]);

        try {
            Shuttlecock::create([
                'brand' => $request->brand,
                'stock' => $request->stock ?? 0,
                'price' => $request->price,
                'is_available' => $request->is_available,
            ]);

            return redirect()->back()->with('success', 'Shuttlecock berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating shuttlecock: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan shuttlecock');
        }
    }

    public function update(Request $request, $id)
    {
        $shuttle = Shuttlecock::findOrFail($id);

        $request->validate([
            'brand' => 'required|string|max:255',
            'stock' => 'nullable|integer|min:0',
            'price' => 'required|integer|min:0',
            'is_available' => 'required|boolean',
        ]);

        try {
            $shuttle->update([
                'brand' => $request->brand,
                'stock' => $request->stock ?? 0,
                'price' => $request->price,
                'is_available' => $request->is_available,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Shuttlecock berhasil diperbarui',
                'data' => $shuttle
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating shuttlecock: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $shuttle = Shuttlecock::find($id);

            if (!$shuttle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data shuttlecock tidak ditemukan'
                ], 404);
            }

            $shuttle->delete();

            return response()->json([
                'success' => true,
                'message' => 'Shuttlecock berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting shuttlecock: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus shuttlecock: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $shuttle = Shuttlecock::find($id);

            if (!$shuttle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $shuttle
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching shuttlecock: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data shuttlecock'
            ], 500);
        }
    }
}
