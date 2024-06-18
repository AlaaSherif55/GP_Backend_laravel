<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoctorRequest;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
/*
request 
register for patient , nurse , doctor 
login for patient , nurse , doctor 
*/
class AuthController extends Controller
{
   
    public function empRegister(StoreDoctorRequest $request){

        $validatedData = $request->validated();
        
        $logo = null;
        if($request['logo']){
            $logo = $this->uploadFileToCloudinary($request,'logo');
        }

        $doctor = new Doctor([
        ]);
        
        $doctor->save();
        
        $image = null;
        if($request['image']){
            $image = $this->uploadFileToCloudinary($request,'image');
        }

        $user = new User([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'image' => $image,
            'role' => 'doctor', 
        ]);
  
   
        $user->save(); 

        $user->userable()->associate($doctor); 
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Employer Created Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }
}
