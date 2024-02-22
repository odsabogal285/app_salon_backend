<?php

namespace App\Http\Controllers;

use App\Http\Requests\Appointment\DestroyAppointmentRequest;
use App\Http\Requests\Appointment\EditAppointmentRequest;
use App\Http\Requests\Appointment\IndexAppointmentRequest;
use App\Http\Requests\Appointment\ShowAppointmentRequest;
use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Models\Appointment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexAppointmentRequest $request)
    {
        try {

            $appointments  = Appointment::query()
                ->select('id','time')
                ->where('date', $request->input('date'))
                ->get();

            return response()->json([
                'response' => 'success',
                'data' => [
                    'appointments' => $appointments
                ],
                'error' => null
            ]);

        } catch (Exception $exception) {
            return response()->json([
                'response' => 'error',
                'data' => null,
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
        try {

            DB::beginTransaction();

            $appointment = Appointment::query()->create([
                'user_id' => Auth::user()->id,
                'date' => $request->input('date'),
                'time' => $request->input('time'),
                'total_amount' => $request->input('total_amount')
            ]);

            $appointment->services()->sync($request->input('services'));

            DB::commit();

            return response()->json([
                'response' => 'success',
                'data' =>  [
                    'appointment' => $appointment,
                    'message' => 'Reservación creada con éxito'
                ],
                'error' => null
            ]);

        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'response' => 'error',
                'data' => null,
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowAppointmentRequest $appointment)
    {
        try {

            $appointment_query = Appointment::query()
                ->with('services')
                ->first('id', $appointment->id);

            return response()->json([
             'response' => 'success',
             'data' => [
                 'appointment' => $appointment_query,
                 'message' => ''
             ],
             'error' => null
            ]);

        } catch (Exception $exception) {
            return response()->json([
                'response' => 'error',
                'data' => null,
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditAppointmentRequest $request, $id)
    {
        try {

            $appointment = Appointment::query()->where('id', $id)
                                ->where('user_id', Auth::user()->id)
                                ->with(['services' => function ($query) {
                                    $query->select('id', 'name', 'price');
                                }])
                                ->first();

            $appointment->services->map(function ($service) {
                unset($service->pivot);
                return $service;
            });

            if (!$appointment) {
                return response()->json([
                    'response' => 'error',
                    'data' => null,
                    'error' => 'Cita no encontrada'
                ], 404);
            }

            return response()->json([
                'response' => 'success',
                'data' => [
                    'appointment' => $appointment,
                    'message' => ''
                ],
                'error' => null
            ]);

        } catch (Exception $exception) {
            return response()->json([
                'response' => 'error',
                'data' => null,
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        try {

            DB::beginTransaction();

            $appointment = Appointment::query()->find($id);

            $appointment->update([
                'date' => $request->input('date')??$appointment->name,
                'time' => $request->input('time')??$appointment->price,
                'total_amount' => $request->input('total_amount')??$appointment->total_amount
            ]);

            DB::commit();

            return response()->json([
                'response' => 'success',
                'data' => [
                    'appointment' => $appointment,
                    'message' => 'Cita actualizada correctamente'
                ],
                'error' => null
            ]);

        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'response' => 'error',
                'data' => null,
                'error' => $exception->getMessage()
            ], 500);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyAppointmentRequest $request, $id)
    {
        try {

            DB::beginTransaction();

            $appointment = Appointment::query()->find($id);

            $appointment->services()->detach();

            $appointment->delete();

            DB::commit();

            return response()->json([
                'response' => 'success',
                'data' => [
                    'appointment' => $appointment,
                    'message' => 'Cita cancelada correctamente'
                ],
                'error' => null
            ]);

        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'response' => 'error',
                'data' => null,
                'error' => $exception->getMessage()
            ], 500);

        }
    }
    public function getAppointmentsByUser (Request $request)
    {
        try {

            $appointments = Auth::user()->appointments()->select('id', 'date','time', 'total_amount')->with(['services' => function ($query) {
                $query->select('id', 'name', 'price');
            }])->orderBy('date', 'asc')->get();

            return response()->json([
                'response' => 'success',
                'data' => [
                    'appointment' => $appointments,
                    'message' => ''
                ],
                'error' => null
            ]);

        } catch (Exception $exception) {
            return response()->json([
                'response' => 'error',
                'data' => null,
                'error' => $exception->getMessage()
            ], 500);
        }
    }
}
