<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\HomeService as ObjService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;


class HomeController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function index()
    {
        return $this->objService->index();
    }

    public function clearCache(): JsonResponse
    {
        $logPath = storage_path('logs/laravel.log');

        if (File::exists($logPath)) {
            File::put($logPath, ''); // يمسح المحتوى فقط
            return response()->json(['status' => 200, 'message' => 'Log file cleared successfully.']);
        }
        Artisan::call('optimize:clear');
        return response()->json([
            'status' => 200,
        ]);
    }
}
