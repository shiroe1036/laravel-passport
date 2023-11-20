<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserController::class)->group(function(){
    Route::post('login', 'loginUser');
    Route::get('/redirect', function (Request $request) {
        // $request->session()->put('state', $state = Str::random(40));
     
        // $request->session()->put(
        //     'code_verifier', $code_verifier = Str::random(128)
        // );
    
        $state = Str::random(40);
    
        $code_verifier = Str::random(128);
     
        $codeChallenge = strtr(rtrim(
            base64_encode(hash('sha256', $code_verifier, true))
        , '='), '+/', '-_');
     
        $query = http_build_query([
            'client_id' => '1',
            'redirect_uri' => 'http://localhost',
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
            'prompt' => 'consent', // "none", "consent", or "login"
        ]);
     
        return redirect('http://localhost/oauth/authorize?'.$query);
    });

});

Route::controller(UserController::class)->group(function(){
    Route::get('user', 'getUserDetail');
    Route::get('logout', 'userLogout');
})->middleware('auth:api');
