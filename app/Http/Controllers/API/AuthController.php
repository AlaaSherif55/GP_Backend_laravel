<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDoctorRequest;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use GuzzleHttp\Client;
use Laravel\Sanctum\PersonalAccessToken;

/*
request 
register for patient , nurse , doctor 
login for patient , nurse , doctor 
*/
class AuthController extends Controller
{
   
    public function doctorRegister(StoreDoctorRequest $request)
    {
        try {
            $validatedData = $request->validated();
            
            $image = null;
            
            if ($request->hasFile('image')) {
                $image = $this->uploadFileToCloudinary($request, 'image');
                if (!$image) {
                    throw new \Exception('Failed to upload image to Cloudinary');
                }
            }
    
            $doctor = new Doctor([
                'image' => $image,
            ]);
            
            $doctor->save();
    
            $user = new User([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'phone' => $validatedData['phone'],
                'role' => 'doctor', 
            ]);
      
            $user->save(); 
    
            $user->userable()->associate($doctor); 
            $user->save();
    
            $token = $user->createToken("API TOKEN")->plainTextToken;
    
            return response()->json([
                'status' => true,
                'message' => 'Doctor created successfully',
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to register doctor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function uploadFileToCloudinary($request, $field){
        $fileUrl = '';
        
        if ($request->hasFile($field)) {
            $client = new Client([
                'verify' => config('services.cloudinary.verify'),
            ]);
            
            try {
                $response = $client->request('POST', 'https://api.cloudinary.com/v1_1/deqwn8wr6/auto/upload', [
                    'multipart' => [
                        [
                            'name' => 'file',
                            'contents' => fopen($request->file($field)->getPathname(), 'r'),
                        ],
                        [
                            'name' => 'upload_preset',
                            'contents' => 'jdebs8xw', 
                        ],
                    ],
                ]);
                $cloudinaryResponse = json_decode($response->getBody()->getContents(), true);
                $url = $cloudinaryResponse['secure_url'] ?? null;
                
                $fileUrl = $url;
    
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => "Error uploading $field to Cloudinary",
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    
        return $fileUrl;
    }
    public function getRoleByToken($token){
        
        $currentRequestPersonalAccessToken = PersonalAccessToken::findToken($token);
        $user = $currentRequestPersonalAccessToken->tokenable;
    
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        if ($user->role) {
            return $user->role;
        }
        
    
        return response()->json(['error' => 'Invalid user role or no associated data found'], 400);
    }
}