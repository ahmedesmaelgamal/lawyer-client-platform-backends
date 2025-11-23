<?php

namespace App\Services\Api\Client;

use App\Services\BaseService;
use App\Models\LawyerReport as ObjModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LawyerReportsService extends BaseService
{
    public function __construct(ObjModel $model)
    {
        parent::__construct($model);
    }

    public function makeReport(array $data)
    {
        try {
            $client = Auth::guard('client_api')->user();

            if (!$client) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access. Client not authenticated.',
                    'data' => [],
                ], 401);
            }

            $report = $this->model->create([
                'client_id' => $client->id,
                'lawyer_id' => $data['lawyer_id'],
                'body'      => $data['body'],
                'status'      => "inactive",
            ]);

            return $this->responseMsg(
                'Report created successfully',
                collect($report->toArray())->except(['created_at', 'updated_at']),
                201
            );

        } catch (\Exception $e) {
            Log::error('Error in makeReport: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to create report' . $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }
}
