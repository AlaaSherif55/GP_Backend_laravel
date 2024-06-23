<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\StoreHospitalRequest;
use App\Http\Requests\StoreNurseRequest;
use App\Http\Requests\StorePatientRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\HospitalResource;
use App\Http\Resources\PatientRegister;
use App\Http\Resources\NurseResource;
use App\Http\Resources\PatientResource;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Nurse;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

/*
request 
register for patient , nurse , doctor 
login for patient , nurse , doctor 
*/
class AuthController extends Controller
{
   
    public function doctorRegister(StoreDoctorRequest $request){
        try {
            $validatedData = $request->validated();
            $image = null;
            
            if ($request->hasFile('image')) {
                $image = $this->uploadFileToCloudinary($request, 'image');
                if (!$image) {
                    throw new \Exception('Failed to upload image to Cloudinary');
                }
            }/*else {
                return response()->json([
                    'success' => false,
                    'message' => 'No image file uploadedaa',
                ], 400);
            }*/
            $doctor = Doctor::create([
                'image' => $image,
            ]);
    

            $user = new User([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'phone' => $validatedData['phone'],
                'role' => 'doctor', 
            ]);
            
         
            $user->save(); 
            event(new Registered($user));
            $user->sendEmailVerificationNotification(); 
    
          
            $doctor->user()->save($user);
    

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

    public function patientRegister(StorePatientRequest $request){
        try {
            $validatedData = $request->validated();

            $patient = Patient::create([
                'history'=> $validatedData['history'],
                'gender' => $validatedData['gender'],
                'birth_date' =>$validatedData['birth_date']
            ]);
    

            $user = new User([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'phone' => $validatedData['phone'],
                'role' => 'patient', 
            ]);
            
         
            $user->save(); 
            event(new Registered($user));
            $user->sendEmailVerificationNotification(); 
          
            $patient->user()->save($user);
    

            $token = $user->createToken("API TOKEN")->plainTextToken;
    
            return response()->json([
                'status' => true,
                'message' => 'Patient created successfully',
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to register patient',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function nurseRegister(StoreNurseRequest $request){
        try {
            $validatedData = $request->validated();
    
            $image = null;
            if ($request->hasFile('image')) {
                $image = $this->uploadFileToCloudinary($request, 'image');
                if (!$image) {
                    throw new \Exception('Failed to upload image to Cloudinary');
                }
            }
    
            $nurse = Nurse::create([
                'image' => $image,
                'university' => $validatedData['university']??null,
                'qualifications' => $validatedData['qualifications']??null,
                'city' => $validatedData['city']??null,
                'fees' => $validatedData['fees']??null,
                'work_start' => $validatedData['work_start']??null,
                'work_end' => $validatedData['work_end']??null,
                'work_days' => $validatedData['work_days']??null,
            ]);
    
      
            $user = new User([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'phone' => $validatedData['phone'],
                'role' => 'nurse', 
            ]);
    
         
            $user->save();
            event(new Registered($user));
            $user->sendEmailVerificationNotification(); 
   
            $nurse->user()->save($user);
    
   
            $token = $user->createToken('API_TOKEN')->plainTextToken;
    
            return response()->json([
                'status' => true,
                'message' => 'Nurse created successfully',
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => 'Failed to register nurse',
                'error' => $e->getMessage() 
            ], 500);
        }
    }
    
    public function hospitalRegister(StoreHospitalRequest $request){
        try {
            $validatedData = $request->validated();
            
            $hospital = Hospital::create([
                'address' => $validatedData['address'],
            ]);
    
            $user = new User([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'phone' => $validatedData['phone'],
                'role' => 'hospital', 
            ]);
            
         
            $user->save(); 
            event(new Registered($user));
            $user->sendEmailVerificationNotification(); 
    
          
            $hospital->user()->save($user);
    

            $token = $user->createToken("API TOKEN")->plainTextToken;
    
            return response()->json([
                'status' => true,
                'message' => 'Hospital created successfully',
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to register Hospital',
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
        try {
            $currentRequestPersonalAccessToken = PersonalAccessToken::findToken($token);
            
            if (!$currentRequestPersonalAccessToken) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            $user = $currentRequestPersonalAccessToken->tokenable;
    
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
            if ($user->role) {
                return $user->role;
            }
    
            return response()->json(['error' => 'Invalid user role or no associated data found'], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch user role',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request){

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $token = $user->createToken($request->email)->plainTextToken;
    
        return $this->getUserDataByRole($token);
    }

    public function getUserDataByRole($token){
        
        $currentRequestPersonalAccessToken = PersonalAccessToken::findToken($token);
        $user = $currentRequestPersonalAccessToken->tokenable;
    
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        if ($user->role == "admin") {
            return [
                'token' => $token,
                'user' => new UserResource($user),
            ];
        }
        elseif ($user->role == "hospital") {
            $hospital = Hospital::find($user->userable_id);
            return [
                'token' => $token,
                'user' => new HospitalResource($hospital),
            ];
        }
        elseif ($user->role == "nurse") {
            $nurse = Nurse::find($user->userable_id);
            if ( $nurse) {
                return [
                    'token' => $token,
                    'user' => new NurseResource($nurse),
                ];
            } 
        } 
        elseif ($user->role == "doctor") {
            $doctor = Doctor::find($user->userable_id);
            if ($doctor) {
                return [
                    'token' => $token,
                    'user' => new DoctorResource($doctor),
                ];
            }
        }
        elseif ($user->role == "patient") {
            $patient = Patient::find($user->userable_id);
            if ($patient) {
                return [
                    'token' => $token,
                    'user' => new PatientResource($patient),
                ];
            }
        }
    
        return response()->json(['error' => 'Invalid user role or no associated data found'], 400);
    }

    public function getUser(Request $request){
        
            $user = $request->user();
    
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        if ($user->role == "admin") {
            return [
                'user' => new UserResource($user),
            ];
        }
        elseif ($user->role == "doctor") {
            $Doctor= Doctor::find($user->userable_id);
            if ($Doctor) {
                return [
                    'user' => new DoctorResource($Doctor),
                ];
            } 
        } 
        elseif ($user->role == "patient") {
            $Patient = Patient::find($user->userable_id);
            if ($Patient) {
                return [

                    'user' => new PatientResource($Patient),
                ];
            }
        }
        elseif ($user->role == "nurse") {
            $Nurse = Nurse::find($user->userable_id);
            if ($Nurse) {
                return [

                    'user' => new NurseResource($Nurse),
                ];
            }
        }
    
        return response()->json(['error' => 'Invalid user role or no associated data found'], 400);
    }
}