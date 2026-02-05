<?php

use App\Models\PrintJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/print-jobs', function (Request $request) {
    $key = $request->get('key');
    if ($key !== env('API_KEY')) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    return PrintJob::where('printed', false)->get();
});

Route::delete('/print-jobs/{printJob}', function (Request $request, PrintJob $printJob) {
    $key = $request->get('key');
    if ($key !== env('API_KEY')) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $printJob->delete();
    return response()->json(['message' => 'Print job deleted']);
});

