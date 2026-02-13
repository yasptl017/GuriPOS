<?php

use App\Models\PrintJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ─── Print Agent API ───────────────────────────────────────────────────────
// All routes protected by a simple API key (set API_KEY in .env)

Route::middleware('api')->prefix('print-agent')->group(function () {

    // GET /api/print-agent/jobs?key=XXX
    // Returns all pending print jobs (status=0)
    Route::get('/jobs', function (Request $request) {
        if ($request->get('key') !== env('PRINT_AGENT_KEY')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $jobs = PrintJob::where('status', 0)
            ->orderBy('id')
            ->get(['id', 'order_id', 'printer', 'content', 'created_at']);

        return response()->json($jobs);
    });

    // POST /api/print-agent/jobs/{id}/ack?key=XXX
    // Mark a job as printed (status=1) or failed (status=2)
    Route::post('/jobs/{id}/ack', function (Request $request, $id) {
        if ($request->get('key') !== env('PRINT_AGENT_KEY')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $job = PrintJob::findOrFail($id);

        $success = $request->input('success', true);
        $error   = $request->input('error', null);

        $job->status     = $success ? 1 : 2;
        $job->printed_at = $success ? now() : null;
        $job->error      = $error;
        $job->save();

        return response()->json(['message' => 'Acknowledged', 'status' => $job->status]);
    });

    // GET /api/print-agent/ping?key=XXX
    // Health check - lets the agent verify connection + credentials
    Route::get('/ping', function (Request $request) {
        if ($request->get('key') !== env('PRINT_AGENT_KEY')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'status'  => 'ok',
            'server'  => config('app.name'),
            'time'    => now()->toDateTimeString(),
        ]);
    });
});
