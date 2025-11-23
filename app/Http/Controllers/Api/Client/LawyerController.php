<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Services\Api\Client\LawyerService;
use Illuminate\Http\Request;

class LawyerController extends Controller
{
    public function __construct(protected LawyerService $mainService) {}
    public function lawyerDetails($lawyer_id)
    {
        return $this->mainService->lawyerDetails($lawyer_id);
    }

   
}
