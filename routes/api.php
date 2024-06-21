<?php

use App\Http\Controllers\API\AuthController;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Registeration
Route::post('DoctorRegister', [AuthController::class, 'doctorRegister']);
Route::post('PatientRegister', [AuthController::class, 'patientRegister']);
Route::post('NurseRegister', [AuthController::class, 'nurseRegister']);

// Login
Route::post('login', [AuthController::class, 'login'] );  //without token

// Get user data from token (nurse-doctor-patient)
Route::get('user', [AuthController::class, 'getUser'] )->middleware('auth:sanctum'); //token any role

// Routes for email verification

Route::get('/email/verify/{id}', function () {

    // return redirect(env('FRONT_URL'));
return redirect(env('FRONT_URL').'verify');

})->name('verification.verify');


Route::post('/email/verified', function (Request $request) {
    $request->validate([
        'timestamp' => 'required|date',
        'email' => 'required|email', 
    ]);

    // Find the user by email
    $user = App\Models\User::where('email', $request->email)->first();

    if ($user) {
        $user->email_verified_at = now();
        $user->save();

        return response()->json(['message' => 'Email verified successfully']);
    } else {
        return response()->json(['error' => 'User not found'], 404);
    }
})->middleware('auth:sanctum')->name('verified');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
 
    $status = Password::sendResetLink(
        $request->only('email')
    );
 
    return $status === Password::RESET_LINK_SENT
                ? response()->json(['status' => __($status)])
                : response()->json(['error' => __($status)]);
})->middleware('guest')->name('password.email');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60), 
            ]);

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? response()->json(['status', __($status)])
        : response()->json(['error' => [__($status)]]);
})->middleware('guest')->name('password.update');