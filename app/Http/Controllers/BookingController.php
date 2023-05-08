<?php

namespace App\Http\Controllers;

use App\Mail\SendBookingMail;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    //TODO: поменыять название на Reserve
    public function addBooking(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|string',
            'amount' => 'nullable|integer',
            'date' => 'nullable|date',
            'time' => 'nullable|string'
        ]);

        //TODO:Погуглить как сделать формат даты другим 22.05.2023 и посмотреть как отправить ошибку на конкретное поле
        //TODO:Проверить все вариации для отправки

        if ($validator->fails()){
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ]
            ], 422);
        }

        $booking = new Booking();

        $booking->name = $request->name;
        $booking->phone = $request->phone;
        $booking->amount = $request->amount ?? null;
        $booking->date = $request->date ?? null;
        $booking->time = $request->time ?? null;

        $booking->save();
        //TODO:Создать поле в базе HTML_template поместить всю верстку в виде строки и как отправить верстку не из блейда(на всякий случай)

        Mail::to(config('app.app_settings.email'))->send(new SendBookingMail($request->all()));

        return response()->json([], 204);
    }
}
