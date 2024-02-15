<?php

namespace App\Http\Controllers;

use App\Http\Requests\Service\DestroyServiceRequest;
use App\Http\Requests\Service\ShowServiceRequest;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Models\Service;
use Exception;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $services = Service::all();

            return response()->json([
                'response' => 'success',
                'data' => $services,
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
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        try {

            $service = Service::query()->create([
                'name' => $request->input('name'),
                'price' => $request->input('price')
            ]);

            return response()->json([
                'response' => 'success',
                'data' => $service,
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
     * Display the specified resource.
     */
    public function show(ShowServiceRequest $request, $id)
    {
        try {

            $service = Service::query()->find($id);

            return response()->json([
                'response' => 'success',
                'data' => $service,
                'error' => null
            ]);

        } catch (\Exception $exception) {
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
    public function update(UpdateServiceRequest $request, $id)
    {

        try {

            $service = Service::query()->find($id);

            $service->update([
                'name' => $request->input('name')??$service->name,
                'price' => $request->input('price')??$service->price
            ]);

            return response()->json([
                'response' => 'success',
                'data' => $service,
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
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyServiceRequest $request, $id)
    {
        try {

            $service = Service::query()->find($id);

            $service->delete();

            return response()->json([
                'response' => 'success',
                'data' => $service,
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
