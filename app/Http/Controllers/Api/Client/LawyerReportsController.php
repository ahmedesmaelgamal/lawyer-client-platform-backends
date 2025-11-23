<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Services\Api\Client\LawyerReportsService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LawyerReportsController extends Controller
{
    public function __construct(protected LawyerReportsService $mainService) {}

    public function makeReport(Request $request)
    {
        try {
            $validated = $request->validate([
                'lawyer_id' => 'required|exists:lawyers,id',
                'body'      => 'required|string|max:1000',
            ]);

            return $this->mainService->makeReport($validated);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
