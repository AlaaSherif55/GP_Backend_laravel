<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IntensiveCareUnit;
use App\Models\IntensiveCareApplication;
use App\Models\IntensiveCareEquipment;
use App\Http\Requests\StoreIntensiveCareUnitRequest;
use App\Http\Requests\UpdateIntensiveCareUnitRequest;
use App\Http\Resources\IntensiveCareUnitResource;
use App\Models\Hospital;


class IntensiveCareUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getHospitalICUs(Request $request, Hospital $hospital)
    {
        $itemsPerPage = $request->query('itemsPerPage', 5); 
        $icus = IntensiveCareUnit::with('equipments', 'hospital.user')
                    ->where('hospital_id', $hospital->id)
                    ->paginate($itemsPerPage);
    
        return $icus;
    }
    


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIntensiveCareUnitRequest $request)
    {
        $request_params = $request->validated();
        $hospital_id = $request_params['hospital_id'];
        $code = $request_params['code'];
        $equipments = $request_params['equipments'];
        $icu = IntensiveCareUnit::create([
            'hospital_id' => $hospital_id,
            'capacity' => $request_params['capacity'],
            'code' => $code 
        ]);
    
        $icu->equipments()->attach($equipments);
    
        return response()->json($icu, 201);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $icu = IntensiveCareUnit::with('equipments', 'hospital.user')->find($id);
        return new IntensiveCareUnitResource($icu);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIntensiveCareUnitRequest $request, string $id)
    {
        $icu = IntensiveCareUnit::findOrFail($id);
        $request_params = $request->validated();
        $request_params['hospital_id'] = $icu->hospital_id;
        
        $code = $request_params['code'];
        $equipments = $request_params['equipments'];
    
      
            $icu->update([
                'capacity' => $request_params['capacity'],
                'code' => $code 
            ]);
    
            $icu->equipments()->sync($equipments);
    
            return response()->json(new IntensiveCareUnitResource($icu), 200);
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

    public function getAllICUs(Request $request) {
        $address = $request->input('address');
        $page = $request->input('page', 1); 
        $itemsPerPage = $request->input('itemsPerPage', 5); 
    
        $icus = IntensiveCareUnit::with('equipments', 'hospital.user');
    
        if ($address) {
            $icus = $icus->whereHas('hospital', function ($query) use ($address) {
                $query->where('address', 'like', '%' . $address . '%');
            });
        }
    
        $icus = $icus->where('capacity', '>', 0);
    
        // Pagination
        $totalICUs = $icus->count();
        $icus = $icus->skip(($page - 1) * $itemsPerPage)->take($itemsPerPage)->get();
    
        return response()->json([
            'data' => IntensiveCareUnitResource::collection($icus),
            'current_page' => (int) $page,
            'per_page' => (int) $itemsPerPage,
            'total' => (int) $totalICUs,
            'last_page' => (int) ceil($totalICUs / $itemsPerPage),
        ]);
    }

}
