<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SummaryController extends Controller
{
    public function index()
    {
        return view('admin.order.summary.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Order $order)
    {

        return view('admin.order.summary.detail', compact('order'));
    }




    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function datatable()
    {
        $query = Order::with([
            'user:id,name',
            'bdm_field:id,name', 
            'status_transaction:id,name_admin'
        ]);

        return DataTables::eloquent($query)
            ->editColumn('total', function ($query) {
                return ($query->hours * $query->price);
            })
            ->editColumn('play_date', function ($query) {
                return Carbon::parse($query->play_date)->locale('id')->translatedFormat('l, d F Y');
            })
            ->editColumn('start_at', function ($query) {
                return Carbon::parse($query->start_at)->format('H:i');
            })
            ->editColumn('end_at', function ($query) {
                return Carbon::parse($query->end_at)->format('H:i');
            })
            ->editColumn('created_at', function ($query) {
                return Carbon::parse($query->created_at)->locale('id')->translatedFormat('l, d F Y | H:i') . " WIB";
            })
            ->editColumn('updated_at', function ($query) {
                return Carbon::parse($query->updated_at)->locale('id')->diffForHumans();
            })
            ->make(true);
    }
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
