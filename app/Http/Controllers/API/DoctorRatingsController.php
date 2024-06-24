<?php

namespace App\Http\Controllers;

use App\Models\DoctorRating;
use App\Http\Resources\DoctorRatingResource;
use Illuminate\Http\Request;

class DoctorRatingsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string',
        ]);

        $rating = DoctorRating::create([
            'patient_id' => auth()->id(),      // double check
            'doctor_id' => $request->doctor_id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return response()->json(['message' => 'Rating submitted successfully', 'rating' => $rating], 201);
    }

    public function index($doctor_id)
    {
        $doctor = Doctor::findOrFail($doctor_id);
        $ratings = $doctor->ratings()->with('patient')->get();
        $averageRating = $doctor->averageRating();

        return response()->json(['ratings' => $ratings, 'average_rating' => $averageRating]);
    }

    public function getDoctorReviews($doctorId)
    {
        $ratings = DoctorRating::with('patient')
            ->where('doctor_id', $doctorId)
            ->get();

        return response()->json([
            "status" => "success",
            "data" => DoctorRatingResource::collection($ratings)
        ]);
    }
}
