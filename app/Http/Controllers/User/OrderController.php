<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\checkScheduleRequest;
use App\Models\BallType;
use App\Models\BdmField;
use App\Models\BdmImage;
use App\Models\Order;
use App\Models\PaymentType;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function detail(BdmField $field)
    {
        $ball_types = BallType::select("name", "is_available")
            ->where('is_available', '1')
            ->get();

        $imagesQuery = BdmImage::where('bdm_field_id', $field->id);
        $imageExist = $imagesQuery->exists();
        $images = $imagesQuery->get();

        return view('user.order.detail', compact('field', 'ball_types', 'images', 'imageExist'));
    }

    public function order(BdmField $field)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            $base64 = request()->schedule;
            $schedule = json_decode(base64_decode($base64));

            if (!$schedule || !isset($schedule->day, $schedule->start_at, $schedule->end_at)) {
                return redirect()->back()->with('error', 'Jadwal tidak valid!');
            }

            $start = Carbon::parse($schedule->start_at, 'Asia/Jakarta');
            $end = Carbon::parse($schedule->end_at, 'Asia/Jakarta');
            $hours = $start->diffInHours($end);

            if ($hours < 1) {
                return redirect()->back()->with('error', 'Durasi minimum 1 jam.');
            }

            $pricePerHour = $field->price;
            $priceTotal = $pricePerHour * $hours;
            $downPayment = $priceTotal * 0.5;
            $dateReadable = Carbon::parse($schedule->day, 'Asia/Jakarta')->locale('id')->translatedFormat('l, d F Y');

            $paymentTypes = PaymentType::select('id', 'bank_name')
                ->where('is_active', '1')
                ->get();

            return view('user.order.order', compact(
                'schedule',
                'hours',
                'field',
                'dateReadable',
                'priceTotal',
                'downPayment',
                'paymentTypes'
            ));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function booking(BdmField $field, Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu.',
            ], 401);
        }

        if (!$field->available()) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'schedule' => 'required',
            'transaction_type_id' => 'required|numeric|in:1,2',
            'payment_type_id' => 'required|numeric|exists:payment_types,id',
        ], [
            'schedule.required' => 'Jadwal tidak valid.',
            'transaction_type_id.required' => 'Pilih jenis pembayaran.',
            'payment_type_id.required' => 'Pilih metode pembayaran.',
        ]);

        if ($validator->fails()) {
            $errors = Helpers::setErrors($validator->errors()->messages());
            return response()->json(['success' => false, 'error' => true, 'message' => $errors]);
        }

        try {
            $req = $validator->validated();

            $decoded = json_decode(base64_decode($req['schedule']));
            if (!$decoded || !isset($decoded->day, $decoded->start_at, $decoded->end_at)) {
                return response()->json(['success' => false, 'error' => true, 'message' => 'Data jadwal tidak valid.']);
            }

            $start = Carbon::parse($decoded->start_at, 'Asia/Jakarta');
            $end = Carbon::parse($decoded->end_at, 'Asia/Jakarta');
            $hours = $start->diffInHours($end);

            if ($hours < 1) {
                return response()->json(['success' => false, 'error' => true, 'message' => 'Durasi minimum 1 jam.']);
            }

            $day = Carbon::parse($decoded->day, 'Asia/Jakarta')->endOfDay();

            if ($day->lt(Carbon::now('Asia/Jakarta'))) {
                return response()->json(['success' => false, 'error' => true, 'message' => 'Tidak bisa booking di tanggal yang sudah lewat.']);
            }

            if (Order::isScheduleExist($field->id, $decoded->day, $start, $end)) {
                return response()->json(['success' => false, 'error' => true, 'message' => 'Waktu sudah dibooking.']);
            }

            if ($field->price < 1) {
                return response()->json(['success' => false, 'message' => 'Harga belum ditentukan. Hubungi admin.']);
            }

            $pricePerHour = $field->price;
            $totalPrice = $pricePerHour * $hours;
            $downPayment = $totalPrice * 0.5;
            $expiredPayment = Carbon::now('Asia/Jakarta')->addHours(2);

            $order = Order::create([
                'user_id' => auth()->id(),
                'bdm_field_id' => $field->id,
                'hours' => $hours,
                'price' => $pricePerHour,
                'play_date' => $decoded->day,
                'start_at' => $start,
                'end_at' => $end,
            ]);

            $isDownPayment = $req['transaction_type_id'] == 1;

            $trx = Transaction::create([
                'order_id' => $order->id,
                'transaction_type_id' => $req['transaction_type_id'],
                'payment_type_id' => $req['payment_type_id'],
                'code' => rand(100, 999),
                'amount' => $isDownPayment ? $downPayment : $totalPrice,
                'expired_payment' => $expiredPayment,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil booking. Segera lakukan pembayaran.',
                'data' => [
                    'orderId' => $order->id,
                    'transactionId' => $trx->id,
                    'expired_at' => $expiredPayment->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function checkSchedule(BdmField $field)
    {
        if (!$field->available()) {
            abort(404);
        }

        $request = new checkScheduleRequest();
        $validator = Validator::make(request()->all(), $request->rules(), $request->messages());

        if ($validator->fails()) {
            $errors = Helpers::setErrors($validator->errors()->messages());
            return response()->json(['success' => false, 'error' => true, 'message' => $errors]);
        }

        try {
            $req = $validator->validated();
            $start = Carbon::parse($req['start_at'], 'Asia/Jakarta');
            $end = Carbon::parse($req['end_at'], 'Asia/Jakarta');
            $hours = $start->diffInHours($end);

            if ($hours < 1) {
                return response()->json(['success' => false, 'error' => true, 'message' => 'Durasi minimum 1 jam.']);
            }

            if (Order::isScheduleExist($field->id, $req['day'], $start, $end)) {
                return response()->json(['success' => false, 'error' => true, 'message' => 'Waktu sudah dibooking.']);
            }

            $req['start_at'] = $start->format('Y-m-d H:i:s');
            $req['end_at'] = $end->format('Y-m-d H:i:s');
            $data = base64_encode(json_encode($req));

            return response()->json(['success' => true, 'message' => 'Jadwal tersedia.', 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
