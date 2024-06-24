<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IntensiveCareApplication;
use App\Http\Requests\StoreIntensiveCareApplicationRequest;
use App\Http\Resources\IntensiveCareApplicationResource;
use App\Models\Hospital;
class IntensiveCareApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIntensiveCareApplicationRequest $request)
    {
        $application = $request->validated();
        $application['status'] = 'pending';
        IntensiveCareApplication::create($application);    
        return response()->json('Intensive Care Application created successfully');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $application = IntensiveCareApplication::with('intensiveCareUnit.hospital.user', 'intensiveCareUnit.equipments')->find($id);
        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }
        return new IntensiveCareApplicationResource($application);

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
        //
    }
    public function getApplications(Request $request, Hospital $hospital) {
        $perPage = $request->input('per_page', 5); // Default to 10 items per page if not provided
        $page = $request->input('page', 1);
        $status = $request->input('status', 'pending'); // Default to 'pending' status if not provided
    
        $ICUapplications = $hospital->units()
                                    ->with(['applications.intensiveCareUnit.equipments'])
                                    ->get()
                                    ->pluck('applications')
                                    ->flatten()
                                    ->filter(function ($application) use ($status) {
                                        return $application->status === $status;
                                    })
                                    ->values();
    
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $ICUapplications->forPage($page, $perPage),
            $ICUapplications->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    
        return response()->json($paginated);
    }
    
    
    public function updateStatus(Request $request, IntensiveCareApplication $application)
    {
        // Validate the request
        $request->validate([
            'status' => 'required|string|max:255',
        ]);
    
        // Update the application status
        $application->update(['status' => $request->status]);
    
        if ($request->status === 'accepted') {
            
            $icu = $application->intensiveCareUnit;
            if ($icu->capacity > 0) {
                $icu->decrement('capacity');
            } else {
                return response()->json(['message' => 'ICU capacity is already at 0 and cannot be decreased further'], 400);
            }
        }
    
        return response()->json(['message' => 'Application status updated successfully']);
    }
    

}
