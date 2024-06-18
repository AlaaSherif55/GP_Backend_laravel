<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IntensiveCareUnit;
use App\Models\IntensiveCareApplication;
use App\Models\IntensiveCareEquipment;
use App\Http\Requests\StoreIntensiveCareUnitRequest;
use App\Http\Resources\IntensiveCareUnitResource;

class IntensiveCareUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index()
        {
            $hospital_id =1;
            $icus = IntensiveCareUnit::with('equipments')
            ->where('hospital_id', $hospital_id)
            ->get();
            return IntensiveCareUnitResource::collection($icus);
        }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIntensiveCareUnitRequest $request)
    {
        $request_params = $request->validated();
        $hospital_id = $request_params['hospital_id'];

        $equipments =$request_params['equipments'];
        $icu = IntensiveCareUnit::create([
            'hospital_id' => $hospital_id,
            'capacity' => $request_params['capacity'],
        ]);
        $icu->equipments()->attach($equipments);
        return response()->json($icu, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $icu = IntensiveCareUnit::with('equipments')->find($id);
        return new IntensiveCareUnitResource($icu);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $icu = IntensiveCareUnit::findOrFail($id);
        $icu->equipments()->detach();
        $icu->delete();
        return response()->json('ICU deleted successfully', 204);
    }
}
