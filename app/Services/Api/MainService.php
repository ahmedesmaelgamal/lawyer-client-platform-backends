<?php

namespace App\Services\Api;

use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\CourtCaseLevelResource;
use App\Http\Resources\LevelResource;
use App\Http\Resources\OtherAppResource;
use App\Http\Resources\RefuseReasonResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\SpecialityResource;
use App\Http\Resources\SubCaseSpecializationResource;
use App\Models\CaseSpecialization;
use App\Models\CourtCaseLevel;
use App\Models\Lawyer;
use App\Models\Setting;
use App\Services\Admin\LevelService;
use App\Services\Api\Client\LawyerService;
use App\Services\BaseService;
use App\Models\Lawyer as ObjModel;
use App\Models\OtherApp;
use App\Models\SubCaseSpecializations;
use App\Services\Admin\CityService;
use App\Services\Admin\CountryService;
use App\Services\Api\Lawyer\CourtCaseService;
use App\Services\Admin\MarketOfferService;
use App\Services\Admin\RefuseReasonService;
use App\Services\Admin\SpecialityService;
use App\Traits\FirebaseNotification;
use Exception;

class MainService extends BaseService
{
    use FirebaseNotification;

    /**
     * Constructor method
     *
     * @param ObjModel $model
     *
     * @return void
     */
    public function __construct(
        ObjModel                      $model,
        protected MarketOfferService  $marketOfferService,
        public LawyerService          $lawyerService,
        protected Lawyer              $lawyer,
        protected CourtCaseService    $courtCaseService,
        protected SpecialityService   $specialityService,
        protected CountryService      $countryService,
        protected CityService         $cityService,
        public LevelService           $levelService,
        protected RefuseReasonService $refuseReasonService,
        protected Setting             $setting,
        protected CourtCaseLevel      $courtCaseLevel,
        protected CaseSpecialization $caseSpecialization,
        protected SubCaseSpecializations $subCaseSpecializations,
        protected OtherApp $otherApp
    ) {
        parent::__construct($model);
    }

    public function specialities()
    {
        try {
            $data['specialities'] = SpecialityResource::collection($this->specialityService->getAll());
            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to get data');
        }
    }

    public function getLawyers()
    {
        try {
            $data['lawyers'] = $this->lawyer->where('status', 'active')->get();
            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to get data');
        }
    }

    public function levels()
    {
        try {
            $data['levels'] = LevelResource::collection($this->levelService->getAll());
            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }


    public function specialitiesById($id)
    {
        try {
            $data['specialities'] = SpecialityResource::collection($this->specialityService->model->where('level_id', '=', $id)->get());
            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }

    public function country()
    {
        try {
            $data['countries'] = CountryResource::collection($this->countryService->getAll());
            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }

    public function city($id)
    {
        try {
            $data['cities'] = CityResource::collection($this->cityService->model->where('country_id', $id)->get());
            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to get data');
        }
    }

    public function getCancelReasons()
    {
        try {
            $reasons = $this->refuseReasonService->model->where('type', 'cancel')->latest()->get();
            return $this->successResponse(RefuseReasonResource::collection($reasons));
        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }

    public function getFinishReasons()
    {
        try {
            $reasons = $this->refuseReasonService->model->where('type', 'complete')->latest()->get();
            return $this->successResponse(RefuseReasonResource::collection($reasons));
        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }

    public function getSettings()
    {
        try {
            $apps = $this->otherApp->where('status', 1)->get();
            $settings = $this->setting->pluck('value', 'key');
            $settings['apps'] = OtherAppResource::collection($apps);

            return $this->successResponse($settings);
        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }

    public function testFcm()
    {
        $user_type = 'client_api';
        $data = [
            'title' => 'hiiiiii',
            'body' => 'hiiiiiiiiiiiiiii',
            'court_case_id' => 1,
        ];
        return $this->sendFcm($data, ['11'], $user_type);
    }

    public function testFcmResponse($request)
    {
        try {
            $data = null;
            if ($request->type == 'court_case_private_offer') {
                $data['title'] = 'court case offer';
                $data['body'] = 'you have a new court case offer';
                $data['type'] = $request->type;
                $data['model'] = 'court_cases';
                $data['modal_id'] = null;
            } elseif ($request->type == 'court_case_cancel') {
                $data['title'] = 'cancel court case';
                $data['body'] = 'cancel court case has been canceled from the client';
                $data['type'] = $request->type;
                $data['model'] = 'court_cases';
                $data['modal_id'] = null;
            } elseif ($request->type == 'court_case_finish') {
                $data = null;
            } elseif ($request->type == 'court_case_client_reject') {
                $data = null;
            } elseif ($request->type == 'court_case_client_accept') {
                $data = null;
            } elseif ($request->type == 'case_transfer_request') {
                $data['title'] = 'Transfer Court Case';
                $data['body'] = 'You have a new court case transfer request';
                $data['type'] = $request->type;
                $data['model'] = 'court_case_transfer';
                $data['modal_id'] = null;
            } elseif ($request->type == 'court_case_transfer') {
                $data['title'] = 'Transfer Court Case';
                $data['body'] = 'You have a new court case transfer request';
                $data['type'] = $request->type;
                $data['model'] = 'court_case_transfer';
                $data['modal_id'] = null;
            } elseif ($request->type == 'withdraw_request') {
                $data = null;
            } elseif ($request->type == 'court_case_sos') {
                $data['title'] = 'SOS request';
                $data['body'] = 'you have a new sos request';
                $data['type'] = $request->type;
                $data['model'] = 'sos_request';
                $data['modal_id'] = null;
            } elseif ($request->type == 'commercial_points_receiver') {
                $data = null;
            } elseif ($request->type == 'commercial_points_sender') {
                $data = null;
            } elseif ($request->type == 'point_to_cash') {
                $data = null;
            } elseif ($request->type == 'court_case_lawyer_event') {
                $data['title'] = 'lawyer court case offer';
                $data['body'] = 'a lawyer has placed an offer on your court case';
                $data['type'] = $request->type;
                $data['model'] = 'court_case_lawyer_event';
                $data['modal_id'] = null;
            } elseif ($request->type == 'court_case_action') {
                $data['title'] = 'court case accepted';
                $data['body'] = 'court case has been accepted from the lawyer';
                $data['type'] = $request->type;
                $data['model'] = 'court_cases';
                $data['modal_id'] = null;
            } elseif ($request->type == 'court_case_update') {
                $data = null;
            } elseif ($request->type == 'court_case_delete_update_file') {
                $data['title'] = 'court case file deleted';
                $data['body'] = 'court case update file has been deleted';
                $data['type'] = $request->type;
                $data['model'] = 'court_cases';
                $data['modal_id'] = null;
            } elseif ($request->type == 'court_case_update_update') {
                $data['title'] = 'court case update';
                $data['body'] = 'court case update has been updated';
                $data['type'] = $request->type;
                $data['model'] = 'court_cases';
                $data['modal_id'] = null;
            } elseif ($request->type == 'court_case_delete_update') {
                $data['title'] = 'court case update';
                $data['body'] = 'court case update has been deleted';
                $data['type'] = $request->type;
                $data['model'] = 'court_cases';
                $data['modal_id'] = null;
            } elseif ($request->type == 'court_case_contribution') {
                $data['title'] = 'contribution in Court Case';
                $data['body'] = 'You have a new court case contribution request';
                $data['type'] = $request->type;
                $data['model'] = 'court_case_contribution';
                $data['modal_id'] = null;
            } elseif ($request->type == 'office_response') {
                $data = null;
            } elseif ($request->type == 'change_type') {
                $data = null;
            } elseif ($request->type == 'court_case_due') {
                $data = null;
            } elseif ($request->type == 'office_delete_request') {
                $data = null;
            } elseif ($request->type == 'office_request') {
                $data = null;
            }
            if (auth()->guard('lawyer_api')->check()) {
                $this->sendFcm($data, [auth()->user()], 'lawyer_api');
            } else {
                $this->sendFcm($data, [auth()->guard('client_api')->user()], 'client_api');
            }


            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }

    public function getCourtCaseLevel()
    {
        try {
            $levels = $this->courtCaseLevel->where('status', 'active')->get();
            $data = CourtCaseLevelResource::collection($levels);
            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }

    public function getCaseSpecializations()
    {
        try {
            $data = CourtCaseLevelResource::collection($this->caseSpecialization->get());
            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }

    public function getSubCaseSpecializations($id)
    {
        try {
            $data = SubCaseSpecializationResource::collection($this->subCaseSpecializations->where('Case_Specializations_id', $id)->get());
            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }
}
