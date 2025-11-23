<?php

namespace App\Services\Api\Client;

use App\Http\Resources\LawyerAdResource;
use App\Http\Resources\LawyerResource;
use App\Services\BaseService;
use App\Models\Lawyer as ObjModel;
use App\Services\Admin\AdService;


class LawyerService extends BaseService
{
    /**
     * Constructor method
     *
     * @param ObjModel $model
     *
     * @return void
     */
    public function __construct(ObjModel $model, protected AdService $adService)
    {
        parent::__construct($model);
    }

    public function lawyerDetails($lawyer_id)
    {
        try {
            $lawyer = $this->model->find($lawyer_id);
            $lawyer = LawyerResource::make($lawyer);

            $data['lawyer'] = $lawyer;
            $data['advertise'] = null;
            $randomAd = $this->adService->model->active()->inRandomOrder()->first();
            if ($randomAd) {
                $data['advertise'] = LawyerAdResource::make($randomAd);
            }
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }
    public function lawyerDetailsResponse($lawyer_id)
    {
        $lawyer = $this->model->find($lawyer_id);
        return $lawyer;

    }

}
