<?php

namespace App\Services\Api\Client;

use App\Enums\CourtCaseStatusEnum;
use App\Enums\CourtCaseTypeEnum;
use App\Enums\DuePaidEnum;
use App\Enums\EventStatusEnum;
use App\Enums\ReasonEnum;
use App\Enums\UserTypeEnum;
use App\Http\Resources\ClientCourtCaseResource;
use App\Http\Resources\CourtCaseDueResource;
use App\Http\Resources\CourtCaseEventResource;
use App\Http\Resources\CourtCaseResource;
use App\Http\Resources\SosRequestResource;
use App\Models\Client;
use App\Models\CourtCaseUpdate;
use App\Services\BaseService;
use App\Models\CourtCase as ObjModel;
use App\Models\CourtCaseCancellation;
use App\Models\CourtCaseHistory;
use App\Models\SOSRequest;
use App\Services\Admin\CourtCaseDueService;
use App\Services\Admin\CourtCaseEventService;
use App\Services\Admin\RateService;
use App\Traits\FirebaseNotification;
use Illuminate\Support\Facades\DB;

class CourtCaseService extends BaseService
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
        ObjModel                        $model,
        protected SOSRequest            $sosRequest,
        protected RateService           $rateService,
        protected CourtCaseHistory      $courtCaseHistory,
        protected CourtCaseEventService $courtCaseEventService,
        protected CourtCaseDueService   $courtCaseDueService,
        protected Client                $client,
        protected CourtCaseCancellation $courtCaseCancellation
    ) {
        parent::__construct($model);
    }

    /**
     * Summary of addPrivateCase
     * @param mixed $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function addPrivateCase($request)
    {
        try {
            $user = auth('client_api')->user();
            $validator = $this->apiValidator($request->all(), [
                'lawyer_id' => 'required|exists:lawyers,id',
                'type' => 'required|in:' . implode(',', CourtCaseTypeEnum::values()),
                'title' => 'required',
                'details' => 'required',
                'price' => 'required',
                'files' => 'nullable',
                'speciality_id' => 'required',
                'case_number' => 'required|unique:court_cases,case_number',
                'case_speciality_id' => 'nullable|exists:case_specializations,id',
                'sub_case_speciality_id' => 'nullable|exists:sub_case_specializations,id',
            ]);

            if ($validator) {
                return $validator;
            }

            $addNewPrivateCase = new ObjModel();
            $addNewPrivateCase->type = $request->type;
            $addNewPrivateCase->title = $request->title;
            $addNewPrivateCase->details = $request->details;
            $addNewPrivateCase->case_estimated_price = $request->price;
            $addNewPrivateCase->status = CourtCaseStatusEnum::PRIVATE->value;
            $addNewPrivateCase->case_number = $request->case_number;
            $addNewPrivateCase->client_id = $user->id;
            $addNewPrivateCase->speciality_id = $request->speciality_id;
            $addNewPrivateCase->case_speciality_id = $request->case_speciality_id;
            $addNewPrivateCase->sub_case_speciality_id = $request->sub_case_speciality_id;

            if ($addNewPrivateCase->save()) {
                $addNewPrivateCase->courtCaseEvents()->create([
                    'lawyer_id' => $request->lawyer_id,
                    'status' => EventStatusEnum::OFFER->value,
                    'price' => $request->price,
                ]);

                $data = [
                    'title' => 'court case offer',
                    'body' => 'you have a new court case offer',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'court_case_private_offer',
                    'model' => 'court_cases',
                    'module_id' => $addNewPrivateCase->id,
                ];
                $this->sendFcm($data, [$addNewPrivateCase->courtCaseEvents()->latest()->first()->lawyer_id], 'lawyer_api');


                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        if ($file) {
                            $fileExtension = $file->getClientOriginalExtension();
                            $filename = $file->getClientOriginalName();
                            $savedFile = $this->handleFile($file, 'court_case_files', 'photo');
                            $addNewPrivateCase->courtCaseFiles()->create([
                                'file' => $savedFile,
                                'type' => $fileExtension,
                                'name' => $filename
                            ]);
                        }
                    }
                }
            }

            return $this->successResponse(ClientCourtCaseResource::make($addNewPrivateCase));
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    /**
     * @param mixed $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function addNewCourtCase($request)
    {
        // try {
            $user = auth('client_api')->user();

            $validator = $this->apiValidator($request->all(), [
                'title' => 'required',
                'details' => 'required',
                'case_estimated_price' => 'required',
                'files' => 'nullable',
                'speciality_id' => 'required',
                'case_number' => 'required|unique:court_cases,case_number',
                'court_case_level_id' => 'required|exists:court_case_levels,id',
                'case_speciality_id' => 'nullable|exists:case_specializations,id',
                'sub_case_speciality_id' => 'nullable|exists:sub_case_specializations,id',
                "city_id" => "required|exists:cities,id"
            ], [
                'case_number.unique' => 'Case number already exists',
            ]);

            if ($validator) {
                return $validator;
            }

            $newCourtCase = new ObjModel();
            $newCourtCase->title = $request->title;
            $newCourtCase->details = $request->details;
            $newCourtCase->case_estimated_price = $request->case_estimated_price;
            $newCourtCase->case_number = $request->case_number;
            $newCourtCase->speciality_id = $request->speciality_id;
            $newCourtCase->court_case_level_id = $request->court_case_level_id;
            $newCourtCase->case_speciality_id = $request->case_speciality_id;
            $newCourtCase->sub_case_speciality_id = $request->sub_case_speciality_id;
            $newCourtCase->client_id = $user->id;
            $newCourtCase->city_id = $request->city_id;

            if ($newCourtCase->save()) {

                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        if ($file) {
                            $fileExtension = $file->getClientOriginalExtension();
                            $filename = $file->getClientOriginalName();
                            $savedFile = $this->handleFile($file, 'court_case_files', 'photo');
                            $newCourtCase->courtCaseFiles()->create([
                                'file' => $savedFile,
                                'type' => $fileExtension,
                                'name' => $filename
                            ]);
                        }
                    }
                }
            }

            $newCourtCase = $this->model->find($newCourtCase->id);

            return $this->successResponse(ClientCourtCaseResource::make($newCourtCase));
        // } catch (\Exception $e) {
        //     return $this->errorResponse();
        // }
    }

    /**
     * Summary of getCourtCases
     * @param mixed $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCourtCases($type)
    {
        try {
            if (!in_array($type, ['new', 'old', 'sos'])) {
                return $this->responseMsg('type is invalid, use new or old or sos');
            }
            $user = auth('client_api')->user();
            $sos = null;

            if ($type != 'sos') {
                $courtCases = $this->model
                    ->query()
                    ->where('client_id', $user->id);
            }

            if ($type == 'new') {
                $courtCases->whereNotIn('status', ['rejected', 'cancelled', 'completed']);
            } elseif ($type == 'old') {
                $courtCases->whereIn('status', ['rejected', 'cancelled', 'completed']);
            } else {
                $sos = $this->sosRequest->where('client_id', $user->id)->latest()->get();
                if (!$sos) {
                    return $this->errorResponse();
                }
            }

            if ($sos) {
                return $this->successResponse(SosRequestResource::collection($sos));
            }
            $courtCases = $courtCases->latest()->get();
            return $this->successResponse(ClientCourtCaseResource::collection($courtCases));
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }


    /**
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelCourtCase($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'court_case_id' => 'required|exists:court_cases,id',
                'cancel_reason_id' => 'nullable|exists:refuse_reasons,id',
                'canel_note' => 'nullable',
            ]);

            if ($validator) {
                return $validator;
            }

            $user = auth('client_api')->user();
            $courtCase = $this->model->find($request->court_case_id);
            if (!$courtCase) {
                return $this->responseMsg('court case not found', null, 404);
            }

            if ($courtCase->client_id != $user->id) {
                return $this->responseMsg('you are not allowed to cancel this court case', null, 403);
            }

            // update status
            if ($courtCase->offerEvent) {
                $courtCase->status = CourtCaseStatusEnum::NEW->value;
                $courtCase->save();

                $courtCase->offerEvent->status = EventStatusEnum::CANCELLED->value;
                $courtCase->offerEvent->cancel_reason_id = $request->cancel_reason_id;
                $courtCase->offerEvent->canel_note = $request->canel_note;
                $courtCase->offerEvent->save();
            } else {

                // court case history
                $this->courtCaseHistory->create([
                    'court_case_id' => $courtCase->id,
                    'user_id' => $user->id,
                    'user_type' => UserTypeEnum::CLIENT->value,
                    //                    'status' => CourtCaseStatusEnum::CANCELLED->value,
                    'history' => 'court case cancelled'
                ]);


                $courtCase->delete();


                $data = [
                    'title' => 'cancel court case',
                    'body' => 'cancel court case has been canceled from the client',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'court_case_cancel',
                    'model' => 'court_cases',
                    'module_id' => $courtCase->id,
                ];
                $this->sendFcm($data, [$courtCase->courtCaseEvents()->latest()->first()->lawyer_id], 'lawyer_api');
                return $this->responseMsg('court case deleted', null);
            }


            return $this->responseMsg('court case cancelled', null);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    /**
     * @param mixed $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function finishCourtCase($request)
    {

        //        try {
        $validator = $this->apiValidator($request->all(), [
            'rate' => 'required',
            'reason_id' => 'required|exists:refuse_reasons,id',
            'comment' => 'required',
            'court_case_id' => 'required|exists:court_cases,id',
        ]);

        if ($validator) {
            return $validator;
        }


        $user = auth('client_api')->user();

        $courtCase = $this->getById($request->court_case_id);

        if (!$courtCase) {
            return $this->responseMsg('court case not found', null, 404);
        }

        if (!$courtCase->acceptedEvent) {
            return $this->responseMsg('court case event not found or already finished', null, 404);
        }

        // rate lawyer for court case
        $this->rateService->model->create([
            'court_case_id' => $request->court_case_id,
            'from_user_id' => $courtCase->client_id,
            'from_user_type' => UserTypeEnum::CLIENT->value,
            'to_user_id' => $courtCase->acceptedEvent->lawyer_id,
            'to_user_type' => UserTypeEnum::LAWYER->value,
            'rate' => $request->rate,
            'reason_id' => $request->reason_id,
            'comment' => $request->comment
        ]);

        $courtCase->status = CourtCaseStatusEnum::COMPLETED->value;
        $courtCase->save();

        $courtCase->acceptedEvent->status = EventStatusEnum::FINISHED->value;
        $courtCase->acceptedEvent->save();

        // confirm cancellation for court case
        $this->courtCaseCancellation->updateOrCreate([
            'court_case_id' => $request->court_case_id,
        ], [
            'court_case_id' => $request->court_case_id,
            'accept_lawyer' => 1,
            'accept_client' => 1,
        ]);

        $data = [
            'title' => 'finish court case',
            'body' => 'court case has been finished successfully',
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'type' => 'court_case_finish',
        ];
        $this->sendFcm($data, [$courtCase->courtCaseEvents()->latest()->first()->lawyer_id], 'lawyer_api');

        return $this->responseMsg('court case finished', ClientCourtCaseResource::make($courtCase));
        //        } catch (\Exception $e) {
        //            return $this->errorResponse();
        //        }
    }

    /**
     * Summary of getCourtCase
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCourtCase($id)
    {
        try {
            $courtCase = $this->model->where('id', $id)->with('courtCaseDues')->first();
            if (!$courtCase) {
                return $this->responseMsg('court case not found', null, 404);
            }
            $final_price_paid = 0;
            $final_price_unpaid = 0;
            foreach ($courtCase->getPaidCourtCaseDues() as $courtCaseDue) {
                $final_price_paid += $courtCaseDue->price;
            }
            foreach ($courtCase->getCourtCaseDuesUnPaid() as $courtCaseDue) {
                $final_price_unpaid += $courtCaseDue->price;
            }

            $courtCase->court_case_final_paid = $final_price_paid;
            $courtCase->court_case_final_unpaid = $final_price_unpaid;

            return $this->successResponse(ClientCourtCaseResource::make($courtCase));
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    /**
     * Summary of actionEvent
     * @param mixed $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function actionEvent($request)
    {
        try {

            $validator = $this->apiValidator($request->all(), [
                'status' => 'required|in:' . EventStatusEnum::ACCEPTED->value . ',' . EventStatusEnum::REJECTED->value,
                'court_case_event_id' => 'required|exists:court_case_events,id',
            ]);

            if ($validator) {
                return $validator;
            }

            //check if has event already to court case
            $event = $this->courtCaseEventService->model->find($request->court_case_event_id);
            $courtCase = $this->model->find($event->court_case_id);

            if (!$courtCase) {
                return $this->responseMsg('court case not found', null, 404);
            }

            if (!$event) {
                return $this->responseMsg('court case event not found', null, 404);
            }

            if (!$courtCase->acceptedEvent) {
                $event->status = $request->status;
                if ($event->save()) {
                    if ($event->status == EventStatusEnum::REJECTED->value) {
                        $data = [
                            'title' => 'finish court case',
                            'body' => 'court case offer has been rejected from the client',
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'type' => 'court_case_client_reject',
                        ];
                        $this->sendFcm($data, [$courtCase->courtCaseEvents()->latest()->first()->lawyer_id], 'lawyer_api');
                    } else {
                        $data = [
                            'title' => 'finish court case',
                            'body' => 'court case has been finished successfully',
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'type' => 'court_case_client_accept',
                        ];
                        $this->sendFcm($data, [$courtCase->courtCaseEvents()->latest()->first()->lawyer_id], 'lawyer_api');
                    }
                    $event->status == EventStatusEnum::ACCEPTED->value ? $courtCase->status = CourtCaseStatusEnum::OFFERED->value
                        : '';
                    $courtCase->save();
                }
                return $this->successResponse(CourtCaseEventResource::make($event));
            } else {
                return $this->responseMsg('court case already has an active event', null, 404);
            }
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    /**
     * Summary of getCourtCaseDeus
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCourtCaseDeus(int $id)
    {
        try {
            $courtCase = $this->model->find($id);
            $dues = $courtCase->courtCaseDues;
            return $this->successResponse(CourtCaseDueResource::collection($dues));
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    /**
     * Summary of payDue
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function payDue($id)
    {
        try {
            $user = $this->client->find(auth('client_api')->user()->id);
            $due = $this->courtCaseDueService->getById($id);

            if ($due->paid == DuePaidEnum::PAID->value) {
                return $this->responseMsg('this due already paid', null, 201);
            }

            // if wallet is not enough
            if ($user->wallet < $due->price) {
                return $this->responseMsg("your wallet is not enough , your wallet now is : {$user->walletFromat()}", null, 422);
            }

            $user->wallet -= $due->price;
            if ($user->save()) {
                $user->walletTransactions()->create([
                    'user_type' => UserTypeEnum::CLIENT->value,
                    'credit' => 0,
                    'debit' => $due->price,
                    'case_id' => $due->court_case_id,
                ]);
            }

            $due->paid = DuePaidEnum::PAID->value;
            $due->save();

            $courtCase = $this->model->where('id', $due->court_case_id)->latest()->first();
            $data1 = [
                'title' => 'pay Court Case due',
                'body' => 'court case due has been payed',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type' => 'case_transfer_request',
                'model' => 'court_cases',
                'module_id' => $courtCase->id,
            ];
            $this->sendFcm($data1, [$user->id], 'client_api');

            $data2 = [
                'title' => 'pay Court Case due',
                'body' => 'court case due has been payed',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type' => 'case_transfer_request',
                'model' => 'court_cases',
                'module_id' => $courtCase->id,
            ];
            $this->sendFcm($data2, [$courtCase->lawyer_id], 'lawyer_api');

            return $this->responseMsg("due paid , your wallet now is : {$user->walletFromat()}", CourtCaseDueResource::make($due));
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }


    //    public function transferCourtCaseToAnotherLawyerResponse($request)
    //    {
    //        try {
    //            $validator = $this->apiValidator($request->all(), [
    //                'court_case_event_id' => 'required|exists:court_case_events,id',
    //                'transfer_client_status' => 'boolean',//id of the lawyer transfered from
    //            ]);
    //            if ($validator) {
    //                return $validator;
    //            }
    //            $courtCaseEvenet = $this->courtCaseEventService->model->where('id', $request->court_case_event_id)->first();
    //            $transferEvent = $courtCaseEvenet->update([
    //                'transfer_client_status' => $request->transfer_client_status
    //            ]);
    //            return $this->responseMsg('court case transfer response has been sent successfully', $transferEvent, 200);
    //
    //        } catch (\Exception $e) {
    //            return $this->errorResponse();
    //        }
    //    }


    public function transferCourtCaseToAnotherLawyerResponse($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'court_case_event_id' => 'required|exists:court_case_events,id',
                'transfer_client_status' => 'required',
            ]);
            if ($validator) {
                return $validator;
            }
            $newCourtCaseEvent = $this->courtCaseEventService->model->where('id', $request->court_case_event_id)->first();
            $oldCourtCaseEvent = $this->courtCaseEventService->model->where('lawyer_id', $newCourtCaseEvent->transfer_lawyer_id)->where('court_case_id', $newCourtCaseEvent->court_case_id)->where('status', EventStatusEnum::ACCEPTED->value)->first();

            $newCourtCaseEvent->update([
                'transfer_client_status' => $request->transfer_client_status,
            ]);

            if ($request->transfer_client_status == 1) {
                $data = [
                    'title' => 'transfer court case',
                    'body' => 'court case transfer has been accepted from the client',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'court_case_transfer',
                ];
                $this->sendFcm($data, [$oldCourtCaseEvent->lawyer_id], 'lawyer_api');
                $data2 = [
                    'title' => 'Transfer Court Case',
                    'body' => 'You have a new court case transfer request',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'case_transfer_request',
                    'model' => 'court_case_transfer',
                    'module_id' => $newCourtCaseEvent->id,
                ];
                $this->sendFcm($data2, [$newCourtCaseEvent->lawyer_id], 'lawyer_api');
                return $this->responseMsg('court case transfer has been accepted from the client successfully', null, 200);
            } elseif ($request->transfer_client_status == 2) {
                $data = [
                    'title' => 'transfer court case',
                    'body' => 'court case transfer has been rejected from the client',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'court_case_transfer',
                ];
                $this->sendFcm($data, [$oldCourtCaseEvent->lawyer_id], 'lawyer_api');
                return $this->responseMsg('court case transfer has been rejected from the client successfully', null, 200);
            } else {
                return $this->responseMsg('please enter a valid status', null, 500);
            }
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }


    public function courtCaseTransferRequest($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'court_case_event_id' => 'required|exists:court_case_events,id',
            ]);
            if ($validator) {
                return $validator;
            }

            $courtCaseEvent = $this->courtCaseEventService->model->where('id', $request->court_case_event_id)->first();
            if (!$courtCaseEvent) {
                return $this->responseMsg('no transfer request found', null, 404);
            } else {
                $data['court_case'] = CourtCaseResource::make($this->model->where('id', $courtCaseEvent->court_case_id)->first());
                $data['court_case_updates'] = CourtCaseUpdate::where('court_case_id', $courtCaseEvent->court_case_id)->get();
                $data['court_case_dues'] = CourtCaseDueResource::collection($this->courtCaseDueService->model->where('court_case_id', $courtCaseEvent->court_case_id)->get());
            }
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function getAllTransferCourtCases()
    {
        try {
            $user = auth('client_api')->user();
            $courtCasesBulk = $user->courtCases()->pluck('id')->toArray();
            $courtCases = $this->courtCaseEventService->model->where('transfer_lawyer_id', '!=', null)
                ->where('transfer_client_status', '=', '0')->whereIn('court_case_id', $courtCasesBulk)->get();
            if ($courtCases->count() == 0) {
                return $this->responseMsg('no transfer request found', null, 404);
            }
            $data = [];
            foreach ($courtCases as $courtCase) {
                $data[] = [
                    'event_id' => $courtCase->id,
                    'old_lawyer' => $courtCase->transferedLawyer->name,
                    'new_lawyer' => $courtCase->lawyer->name,
                    'court_case_name' => $courtCase->courtCase->title,
                    'court_case_id' => $courtCase->court_case_id,
                    'transfer_client_status' => (int) $courtCase->transfer_client_status,
                    'transfer_lawyer_status' => (int) $courtCase->transfer_lawyer_status,
                ];
            }
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }
}
