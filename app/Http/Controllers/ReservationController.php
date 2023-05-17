<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\ReservationManager;

class ReservationController extends Controller
{
    private const RESERVATIONS_PER_PAGE = 15;
    public function index()
    {
        return ReservationResource::collection(Reservation::query()->paginate(self::RESERVATIONS_PER_PAGE));
    }

    public function store(ReservationRequest $request)
    {
        return new ReservationResource(
            app(ReservationManager::class, ['reservation' => null])->create($request->validated())
        );
    }

    public function show(Reservation $reservation)
    {
        return new ReservationResource($reservation);
    }

    public function update(ReservationRequest $request, Reservation $reservation)
    {
        $reservation->update($request->validated());

        return new ReservationResource($reservation);
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return response()->json();
    }
}
