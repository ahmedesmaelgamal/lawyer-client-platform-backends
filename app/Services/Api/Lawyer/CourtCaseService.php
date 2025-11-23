<?php

namespace App\Services\Api\Lawyer;

use App\Enums\CourtCaseStatusEnum;
use App\Enums\EventStatusEnum;
use App\Enums\StatusEnum;
use App\Enums\UserTypeEnum;
use App\Http\Resources\CourtCaseDueResource;
use App\Http\Resources\CourtCaseEventResource;
use App\Http\Resources\CourtCaseResource;
use App\Http\Resources\RefuseReasonResource;
use App\Http\Resources\SosRequestResource;
use App\Models\Client;
use App\Models\CourtCase;
use App\Models\CourtCase as ObjModel;
use App\Models\CourtCaseUpdate;
use App\Models\Lawyer;
use App\Services\Admin\CourtCaseDueService;
use App\Services\Admin\CourtCaseEventService;
use App\Services\Admin\CourtCaseUpdateFilesService;
use App\Services\Admin\CourtCaseUpdateService;
use App\Services\Admin\RefuseReasonService;
use App\Services\Admin\SOSRequestService;
use App\Services\BaseService;
use App\Traits\FirebaseNotification;
use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CourtCaseService extends BaseService
{
    use FirebaseNotification;

    public function __construct(
        ObjModel                              $objModel,
        protected SOSRequestService           $sosRequestService,
        protected CourtCaseEventService       $courtCaseEventService,
        protected RefuseReasonService         $refuseReasonService,
        protected CourtCaseDueService         $courtCaseDueService,
        protected CourtCaseUpdateService      $courtCaseUpdateService,
        protected CourtCaseUpdateFilesService $courtCaseUpdateFilesService,
        protected Lawyer                      $lawyer,
        protected Client                      $client,
        protected CourtCase                   $courtCase,
//        protected CourtCaseService $courtCaseService
    )
    {
        parent::__construct($objModel);
    }

    public function newCourtCases($request)
    {
        try {
            // Validate the request
            $validator = $this->apiValidator($request->all(), [
                'search' => 'nullable|string',
                'speciality_id' => 'nullable|integer|exists:specialities,id',
            ]);

            if ($validator) {
                return $validator;
            }

            $search = $request->search;
            $speciality = $request->speciality_id;

            // Build the base query
            $query = $this->model->query();

            // Apply search filters
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('details', 'like', "%{$search}%")
                        ->orWhereHas('speciality', function ($query) use ($search) {
                            $query->where('title', 'like', "%{$search}%");
                        });
                });
            }

            // Apply speciality filter
            if ($speciality) {
                $query->whereHas('speciality', function ($query) use ($speciality) {
                    $query->where('id', $speciality);
                });
            }

            // Filter by status (NEW or PRIVATE)
            $query->where(function ($query) {
                $query->where('status', CourtCaseStatusEnum::NEW->value)
                    ->orWhere(function ($query) {
                        $query->where('status', CourtCaseStatusEnum::PRIVATE->value)
                            ->whereHas('courtCaseEvents', function ($query) {
                                $query->where('lawyer_id', auth('lawyer_api')->user()->id);
                            });
                    });
            });

            // Order by latest and get results
            return $query->latest('created_at')->get();
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function findcourtCase($id)
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
            return $this->successResponse(new CourtCaseResource($courtCase));
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function myCourtCases($request)
    {
        try {
            $user = auth('lawyer_api')->user();
            $status = $request->status;
            $search = $request->search;
            $speciality = $request->speciality_id;

            // Validate the request
            $validator = $this->apiValidator($request->all(), [
                'status' => 'required|in:' . EventStatusEnum::ACCEPTED->value . ',' . EventStatusEnum::FINISHED->value . ',' . 'sos',
            ]);

            if ($validator) {
                return $validator;
            }

            // Base query for court cases or SOS requests
            $query = $status === 'sos'
                ? $this->sosRequestService->model->where('lawyer_id', $user->id)
                : $this->model->query()->whereHas('courtCaseEvents', function ($query) use ($user, $status) {
                    $query->where('lawyer_id', $user->id)->where('status', $status);
                });

            // Apply search filters
            if ($search) {
                $query->where(function ($query) use ($search, $status) {
                    if ($status === 'sos') {
                        $query->where('problem', 'like', '%' . $search . '%')
                            ->orWhere('phone', 'like', '%' . $search . '%')
                            ->orWhere('address', 'like', '%' . $search . '%');
                    } else {
                        $query->where('title', 'like', '%' . $search . '%')
                            ->orWhere('details', 'like', '%' . $search . '%')
                            ->orWhereHas('speciality', function ($query) use ($search) {
                                $query->where('title', 'like', '%' . $search . '%');
                            });
                    }
                });
            }

            // Apply speciality filter (only for court cases)
            if ($speciality && $status !== 'sos') {
                $query->where('speciality_id', $speciality);
            }

            // Fetch results
            $results = $query->get();
//            dd($results);

            // Return the appropriate response
            return $status === 'sos'
                ? $this->successResponse(SosRequestResource::collection($results))
                : $this->successResponse(CourtCaseResource::collection($results));
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function addEventCourtCase($request)
    {
        try {
            // validate the request
            $validator = $this->apiValidator($request->all(), [
                'court_case_id' => 'required|exists:court_cases,id',
                'price' => 'required|int',
            ]);

            if ($validator) {
                return $validator;
            }
            // handle action on court case
            $user = auth('lawyer_api')->user();

//dd($request->all());
            $checkEvent = $this->courtCaseEventService->model->where('lawyer_id', $user->id)
                ->where('court_case_id', $request->court_case_id)
                ->where('status', EventStatusEnum::OFFER->value)
                ->first();

            if ($checkEvent) {
                return $this->responseMsg('event already added, wait client reply', CourtCaseEventResource::make($checkEvent), 201);
            }
            $addEvent = $this->courtCaseEventService->model->create([
                'lawyer_id' => $user->id,
                'status' => EventStatusEnum::OFFER->value,
                'price' => $request->price,
                'court_case_id' => $request->court_case_id,
            ]);
            $courtCase = $this->courtCase->where('id', $addEvent->court_case_id)->first();
            $data1 = [
                'title' => 'lawyer court case offer',
                'body' => 'a lawyer has placed an offer on your court case',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type' => 'court_case_lawyer_event',
                'model' => 'court_cases',
                'module_id' => $courtCase->id,
            ];
            $this->sendFcm($data1, [$courtCase->client_id], 'client_api');

            $data2 = [
                'title' => 'offer placement',
                'body' => 'the court case offer has been placed successfully',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type' => 'court_case_lawyer_event',
                'model' => 'court_cases',
                'module_id' => $courtCase->id,
            ];
            $this->sendFcm($data2, [$user->id], 'lawyer_api');


            if ($addEvent) {
                return $this->responseMsg('event added successfully', CourtCaseEventResource::make($addEvent), 200);
            } else {
                return $this->responseMsg('Failed to add event', null, 422);
            }
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function deleteEventCourtCase($id)
    {
        try {
            $event = $this->courtCaseEventService->model->find($id);
            if ($event) {
                if ($event->courtCase->acceptedEvent == $event->id) {
                    $event->courtCase->status = CourtCaseStatusEnum::NEW->value;
                    $event->courtCase->save();
                }
                $event->delete();
                return $this->responseMsg('event deleted successfully', null, 200);
            } else {
                return $this->responseMsg('event not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function transferCourtCaseToAnotherLawyerRequest($request)
    {
        try {
            $lawyer_id = auth('lawyer_api')->user()->id;
            $validator = $this->apiValidator($request->all(), [
                'court_case_id' => 'required|exists:court_cases,id',
                'transfer_lawyer_id' => 'required|exists:lawyers,id,status,active',
                'price' => 'nullable|numeric',
            ]);
            if ($validator) {
                return $validator;
            }
            //check if the court case event is already created
            $courtCaseEventCheck = $this->courtCaseEventService->model->where('court_case_id', $request->court_case_id)->where('lawyer_id', $request->transfer_lawyer_id)->first();
            if ($courtCaseEventCheck) {
                return $this->responseMsg('court case transfer request has already been sent to this lawyer', null, 500);
            }
            $courtCaseEvent = $this->courtCaseEventService->model->where('court_case_id', $request->court_case_id)->where('lawyer_id', $lawyer_id)->where('status', EventStatusEnum::ACCEPTED->value)->first();//get the court case i want to transfer
            if ($courtCaseEvent) {//check if the court case is assigned to the lawyer transferred from and the status is accepted
                if ($courtCaseEvent->case_final_price == 0) {
                    return $this->responseMsg('this court case does not has any dues yet,dues should be added to be able to transfer it', null, 200);
                }
                $transferEvent = $this->courtCaseEventService->model->create([
                    'court_case_id' => $request->court_case_id,
                    'status' => EventStatusEnum::OFFER->value,
                    'lawyer_id' => $request->transfer_lawyer_id,
                    'price' => $courtCaseEvent->price ?? 0.0,
                    'transfer_lawyer_id' => $lawyer_id,
                ]);
                $courtCaseDues = $this->courtCaseDueService->model->where('court_case_id', $request->court_case_id)->get();
                foreach ($courtCaseDues as $courtCaseDue) {
                    $courtCaseDue->update([
                        'court_case_event_id' => $transferEvent->id,
                    ]);
                }
                $data = [
                    'title' => 'Transfer Court Case',
                    'body' => 'You have a new court case transfer request',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'case_transfer_request',
                    'model' => 'court_case_transfer',
                    'module_id' => $transferEvent->id,
                ];

                $this->sendFcm($data, [$this->courtCase->where('id', $request->court_case_id)->first()->client_id], 'client_api');
                return $this->responseMsg('court case transfer request has been sent successfully', $transferEvent, 200);
            } else {

                return $this->responseMsg('this court case does not has any events (you are not associated with this court case)', null, 500);
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

    public function courtCaseContributionRequest($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'court_case_event_id' => 'required|exists:court_case_events,id',
            ]);
            if ($validator) {
                return $validator;
            }

            $courtCaseEvent = $this->courtCaseEventService->model->where('id', $request->court_case_event_id)->where('partner_id', '!=', null)->first();
            if (!$courtCaseEvent) {
                return $this->responseMsg('no contribution request found', null, 404);
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


    public function transferCourtCaseToAnotherLawyerResponse($request)
    {
//        DB::beginTransaction();
        try {
            $validator = $this->apiValidator($request->all(), [
                'court_case_event_id' => 'required|exists:court_case_events,id',
                'transfer_lawyer_status' => 'required|in:1,2',
            ]);
            if ($validator) {
                return $validator;
            }

            $newCourtCaseEvent = $this->courtCaseEventService->model->where('id', $request->court_case_event_id)->first();
            $oldCourtCaseEvent = $this->courtCaseEventService->model->where('lawyer_id', $newCourtCaseEvent->transfer_lawyer_id)->where('court_case_id', $newCourtCaseEvent->court_case_id)->where('status', EventStatusEnum::ACCEPTED->value)->first();

            if ($newCourtCaseEvent->transfer_client_status == 0 || $newCourtCaseEvent->transfer_client_status == 2) {
                return $this->responseMsg('this court case transfer has not been accepted from the client', null, 500);
            } elseif ($newCourtCaseEvent->transfer_client_status == 1) {

                if ($request->transfer_lawyer_status == 1) {
                    $newCourtCaseEvent->update([
                        'transfer_lawyer_status' => 1,
                        'status' => EventStatusEnum::ACCEPTED->value,
                        'price' => $oldCourtCaseEvent->price,
                    ]);
                    $checkTransfer = $oldCourtCaseEvent->update([
//                        'transfer_lawyer_id' => $newCourtCaseEvent->lawyer_id,
//                        'transfer_lawyer_status' => $request->transfer_lawyer_status,
                        'status' => EventStatusEnum::TRANSFERRED->value,
                    ]);

                    if ($checkTransfer) {
                        $data = [
                            'title' => 'transfer court case',
                            'body' => 'court case transfer has been accepted from the lawyer',
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'type' => 'court_case_transfer',
                        ];
                        $this->sendFcm($data, [$oldCourtCaseEvent->lawyer_id, $this->courtCase->where('id', $newCourtCaseEvent->court_case_id)->first()->client_id], 'client_api');
                        DB::commit();
                        return $this->responseMsg('court case transfer has been accepted successfully', null, 200);
                    } else {
                        DB::rollBack();
                        return $this->responseMsg('error happened while updating the transfer request status', null, 200);
                    }

                } elseif ($request->transfer_lawyer_status == 2) {
                    $checkTransfer = $newCourtCaseEvent->update([
                        'transfer_lawyer_status' => 2,
                        'status' => EventStatusEnum::REJECTED,
                    ]);
                    if ($checkTransfer) {
                        $data = [
                            'title' => 'transfer Court Case',
                            'body' => 'the lawyer has rejected the court case transfer',
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'type' => 'court_case_transfer',
                        ];
                        $this->sendFcm($data, [$oldCourtCaseEvent->lawyer_id, $this->courtCase->where('id', $newCourtCaseEvent->court_case_id)->first()->client_id], 'client_api');
                        DB::commit();
                        return $this->responseMsg('court case transfer has been rejected successfully', null, 200);
                    } else {
                        DB::rollBack();
                        return $this->responseMsg('error happened while updating the transfer request status', null, 200);
                    }
                } else {
                    DB::rollBack();
                    return $this->responseMsg('please enter a valid status', null, 200);
                }
            } else {
                return $this->responseMsg('unexpected error happened', null, 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse();
        }
    }

    public function getAllTransferCourtCases()
    {
        try {
            $user = auth('lawyer_api')->user();
            $courtCases = $this->courtCaseEventService->model->where('lawyer_id', $user->id)
                ->where('transfer_lawyer_id', '!=', null)
                ->where('transfer_client_status', '=', '1')->where('transfer_lawyer_status', '0')->get();
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
                    'transfer_client_status' => (int)$courtCase->transfer_client_status,
                    'transfer_lawyer_status' => (int)$courtCase->transfer_lawyer_status,
                ];
            };
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }


    public function getAllContributionCourtCases()
    {
        try {
            $user = auth('lawyer_api')->user();
            $courtCases = $this->courtCaseEventService->model->where('lawyer_id', $user->id)
                ->where('status', EventStatusEnum::OFFER->value)
                ->where('partner_id', '!=', null)->get();
            if ($courtCases->count() == 0) {
                return $this->responseMsg('no contribution requests found', null, 404);
            }
            $data = [];
            foreach ($courtCases as $courtCase) {
                $data[] = [
                    'event_id' => $courtCase->id,
                    'court_case_name' => $courtCase->courtCase->title,
                    'court_case_id' => $courtCase->court_case_id,
                    'partner_id' => $courtCase->partner_id,
                    'contribution_price' => $courtCase->price,
                    'contribution_comment' => $courtCase->contribution_comment,
                ];
            }

            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function actionCourtCase($request)
    {
        try {
            // validate the request
            $validator = $this->apiValidator($request->all(), [
                'status' => 'required|in:' . EventStatusEnum::ACCEPTED->value . ',' . EventStatusEnum::REJECTED->value,
                'event_id' => 'required|exists:court_case_events,id',
                'refuse_reason_id' => 'required_if:status,' . EventStatusEnum::REJECTED->value . '|exists:refuse_reasons,id',
                'refuse_note' => 'nullable',
            ]);

            if ($validator) {
                return $validator;
            }

            // handle action on court case
            $user = auth('lawyer_api')->user();
            $event = $this->courtCaseEventService->model->find($request->event_id);
            $courtCase = $this->model->findOrFail($event->court_case_id);

            if ($event) {
                $event->status = $request->status;
                if ($request->status == EventStatusEnum::REJECTED->value) {
                    $courtCase->status = CourtCaseStatusEnum::NEW->value;
                    $courtCase->save();
                    $event->refuse_reason_id = $request->refuse_reason_id;
                    $event->refuse_note = $request->refuse_note;
                }
                $event->save();

                if ($request->status == EventStatusEnum::ACCEPTED->value) {
                    $courtCase->update([
                        'status' => CourtCaseStatusEnum::OFFERED->value
                    ]);


                    $data = [
                        'title' => 'court case accepted',
                        'body' => 'court case has been accepted from the lawyer',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'type' => 'court_case_action',
                        'model' => 'court_cases',
                        'module_id' => $courtCase->id,
                    ];
                    $this->sendFcm($data, [$courtCase->client_id], 'client_api');
                }

                return $this->responseMsg('Court Case Event Status Updated Successfully', CourtCaseEventResource::make($event));
            } else {
                return $this->responseMsg('Court Case Event Not Found', CourtCaseEventResource::make($event));
            }
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function addCourtCaseDues($request)
    {
        try {
            $lawyer = auth('lawyer_api')->user();
            $validator = $this->apiValidator($request->all(), [
                'event_id' => 'required|exists:court_case_events,id',
                'title' => 'required',
                'date' => 'required',
                'price' => 'required',
            ]);

            if ($validator) {
                return $validator;
            }

            $getEvent = $this->courtCaseEventService->model->find($request->event_id);

            $getDuesDates = $this->courtCaseDueService->model
                ->where('court_case_event_id', $request->event_id)
                ->pluck('date')
                ->toArray();

            // Standardize date formats
            $requestDate = Carbon::parse($request->date)->format('Y-m-d');
            $getDuesDates = array_map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            }, $getDuesDates);

            // Check for duplicate dates
            if (in_array($requestDate, $getDuesDates)) {
                return $this->responseMsg('There is already a due with this date', null, 422);
            }

            // Create new due
            $addAddDue = $this->courtCaseDueService->model->create([
                'title' => $request->title,
                'from_user_id' => $getEvent->courtCase->client_id,
                'to_user_id' => $lawyer->id,
                'from_user_type' => UserTypeEnum::CLIENT->value,
                'to_user_type' => UserTypeEnum::LAWYER->value,
                'court_case_id' => $getEvent->court_case_id,
                'court_case_event_id' => $getEvent->id,
                'date' => $request->date,
                'price' => $request->price,
            ]);

            $data = [
                'title' => 'court case due',
                'body' => 'new court case dues have been added',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type' => 'court_case_due',
            ];

            $this->sendFcm($data, [$addAddDue->from_user_id], 'client_api');


            if ($addAddDue) {
                $getEvent->courtCase->status = CourtCaseStatusEnum::ACCEPTED->value;
                $getEvent->courtCase->case_final_price += $addAddDue->price;
                $getEvent->courtCase->save();
                return $this->responseMsg('Due added successfully', CourtCaseEventResource::make($getEvent));
            } else {
                return $this->responseMsg("Something went wrong while creating the due for event: {$getEvent->id}");
            }
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function addNewUpdate($request)
    {
        DB::beginTransaction();
        try {
            $validator = $this->apiValidator($request->all(), [
                'court_case_id' => 'required|exists:court_cases,id',
                'title' => 'required',
                'details' => 'required',
                'date' => 'required',
                'files' => 'nullable|array',
            ]);

            if ($validator) {
                return $validator;
            }

            $newUpdate = new $this->courtCaseUpdateService->model();
            $newUpdate->title = $request->title;
            $newUpdate->court_case_id = $request->court_case_id;
            $newUpdate->details = $request->details;
            $newUpdate->date = $request->date;
            $newUpdate->save();

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    if ($file) {
                        $fileExtension = $file->getClientOriginalExtension(); // Get file extension
                        $fileName = $file->getClientOriginalName();
                        $fileStore = $this->handleFile($file, 'updates', 'file'); // Handle file storage

                        // Use the relationship method to save the file record
                        $newUpdate->courtCaseUpdateFiles()->create([
                            'case_update_id' => $newUpdate->id,
                            'type' => $fileExtension,
                            'file' => $fileStore,
                            'name' => $fileName,
                        ]);


                        $data = [
                            'title' => 'court case update',
                            'body' => 'new court case updates have been added',
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'type' => 'court_case_update',
                        ];
                        $this->sendFcm($data, [$this->courtCase->where('id', $request->court_case_id)->first()->client_id], 'client_api');
                    }
                }
            }

            DB::commit(); // Commit the transaction if everything is successful
            return $this->responseMsg('Court Case Update Added Successfully', CourtCaseResource::make($newUpdate->courtCase));
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function deleteCourtCaseUpdateFile($id)
    {
        try {
            $file = $this->courtCaseUpdateFilesService->model->find($id);
            if ($file) {
                Storage::delete($file->file);
                $file->delete();
                $courtCase = $this->courtCase->model('id', $this->courtCaseUpdateService->model->where('id', $file->case_update_id)->court_case_id);
                $data = [
                    'title' => 'court case file deleted',
                    'body' => 'court case update file has been deleted',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'court_case_delete_update_file',
                    'model' => 'court_cases',
                    'module_id' => $courtCase->id,
                ];

                $this->sendFcm($data, [$courtCase->client_id], 'client_api');
                return $this->responseMsg('File deleted successfully');
            } else {
                return $this->responseMsg('file not found');
            }
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function updateCourtCaseUpdate($request, $id)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'title' => 'required',
                'details' => 'required',
                'date' => 'required',
            ]);
            if ($validator) {
                return $validator;
            }

            $updateCourtCaseUpdate = $this->courtCaseUpdateService->model->find($id);
            if (!$updateCourtCaseUpdate) {
                return $this->responseMsg('Court Case Update not found', null, 404);
            }
            $updateCourtCaseUpdate->update([
                'title' => $request->title,
                'details' => $request->details,
                'date' => $request->date
            ]);


            $courtCase = $this->courtCase->model('id', $updateCourtCaseUpdate->court_case_id);
            $data = [
                'title' => 'court case update',
                'body' => 'court case update has been updated',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type' => 'court_case_update_update',
                'model' => 'court_cases',
                'module_id' => $courtCase->id,
            ];
            $this->sendFcm($data, [$courtCase->client_id], 'client_api');

            return $this->responseMsg('Court Case Update Added Successfully', CourtCaseResource::make($updateCourtCaseUpdate->courtCase));
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function deleteCourtCaseUpdate($id)
    {
        try {
            \DB::beginTransaction();
            $courtCaseUpdate = $this->courtCaseUpdateService->model->find($id);
            if ($courtCaseUpdate) {
                $courtCaseUpdate->delete();

                $courtCase = $this->courtCase->model('id', $courtCaseUpdate->court_case_id);
                $data = [
                    'title' => 'court case update',
                    'body' => 'court case update has been deleted',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'court_case_delete_update',
                    'model' => 'court_cases',
                    'module_id' => $courtCase->id,
                ];
                $this->sendFcm($data, [$courtCase->client_id], 'client_api');

                \DB::commit();
                return $this->responseMsg('court case update deleted successfully');
            } else {
                \DB::rollBack();
                return $this->responseMsg('please enter a valid id');
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->errorResponse();
        }
    }


    public function addLawyerToCourtCaseRequest($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'court_case_id' => 'required|exists:court_cases,id',
                'price' => 'required|numeric',
                'lawyer_id' => 'required|exists:lawyers,id',
                'comment' => 'nullable|string',
            ]);

            if ($validator) {
                return $validator;
            }

            $lawyer = $this->lawyer->where('id', $request->lawyer_id)->whereNull('deleted_at')->first();
            if ($lawyer && $lawyer->status != StatusEnum::ACTIVE->value) {
                return $this->responseMsg('error sending partnership request to this lawyer', null, 422);
            }


            $sendingLawyerLastStatus = $this->courtCaseEventService->model
                ->where('court_case_id', $request->court_case_id)
                ->where('lawyer_id', auth('lawyer_api')->user()->id)
                ->where('status', EventStatusEnum::ACCEPTED->value)
                ->orderBy('id', 'desc')
                ->first();

            $acceptedLawyerLastStatus = $this->courtCaseEventService->model
                ->where('court_case_id', $request->court_case_id)
                ->where('lawyer_id', $request->lawyer_id)
                ->where('status', EventStatusEnum::ACCEPTED->value)
                ->exists();

            $receivedLawyerLastStatusOffer = $this->courtCaseEventService->model
                ->where('court_case_id', $request->court_case_id)
                ->where('lawyer_id', $request->lawyer_id)
                ->where('status', EventStatusEnum::OFFER->value)
                ->exists();
            if (!$sendingLawyerLastStatus) {
                return $this->responseMsg('you are not currently in this case', null, 422);
            } elseif ($acceptedLawyerLastStatus) {
                return $this->responseMsg('this lawyer is already in this case', null, 422);

            } elseif ($receivedLawyerLastStatusOffer) {
                return $this->responseMsg('this lawyer has already received a contribution request in this case', null, 422);
            }


            //check if the price is less than the sending lawyer price
            if ($request->price > $sendingLawyerLastStatus->price) {
                return $this->responseMsg('the price should be less than your assigned price for this case', null, 404);
            }
//            dd($lawyer);
            $contributionEvent = $this->courtCaseEventService->model->create([
                'court_case_id' => $sendingLawyerLastStatus->court_case_id,
                'lawyer_id' => $lawyer->id,
                'price' => $request->price,
                'status' => EventStatusEnum::OFFER->value,
                'partner_id' => auth('lawyer_api')->user()->id,
                'contribution_comment' => $request->comment,
            ]);
//            dd('$contributionEvent');

            $data = [
                'title' => 'contribution in Court Case',
                'body' => 'You have a new court case contribution request',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type' => 'court_case_contribution',
                'model' => 'court_case_contribution',
                'module_id' => $contributionEvent->id,
            ];
            $this->sendFcm($data, [$request->lawyer_id], 'lawyer_api');

            return $this->responseMsg('the contribution request has been sent successfully to the lawyer', null, 200);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public
    function addLawyerToCourtCaseResponse($request)
    {
        try {
            $validator = $this->apiValidator($request->all(), [
                'court_case_event_id' => 'required|exists:court_case_events,id',
                'status' => 'required|in:1,2'
            ]);

            if ($validator) {
                return $validator;
            }
            $newCourtCaseEvent = $this->courtCaseEventService->model->where('id', $request->court_case_event_id)->where('status', EventStatusEnum::OFFER)->first();
            $oldCourtCaseEvent = $this->courtCaseEventService->model->where('court_case_id', $newCourtCaseEvent->court_case_id)->where('status', EventStatusEnum::ACCEPTED)->first();

            if (!$newCourtCaseEvent || !$oldCourtCaseEvent) {
                return $this->responseMsg('court case event not found', null, 404);
            }
            if ($request->status == 1) {
                $data = [
                    'title' => 'contribution in Court Case',
                    'body' => 'the court case contribution request has been accepted',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'court_case_contribution',
                ];
                $this->sendFcm($data, [$oldCourtCaseEvent->lawyer_id], 'lawyer_api');

                $newCourtCaseEvent->status = EventStatusEnum::ACCEPTED->value;
                $newCourtCaseEvent->save();


                return $this->responseMsg('the court case contribution request has been accepted successfully', null, 200);
            } elseif ($request->status == 2) {
                $data = [
                    'title' => 'contribution in Court Case',
                    'body' => 'the court case contribution request has been accepted from the user' . $this->lawyer->where('id', $oldCourtCaseEvent->lawyer_id)->first()->name,
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'court_case_contribution',
                ];
                $this->sendFcm($data, [$oldCourtCaseEvent->lawyer_id], 'lawyer_api');

                $newCourtCaseEvent->status = EventStatusEnum::REJECTED->value;
                $newCourtCaseEvent->save();

                return $this->responseMsg('the court case contribution request has been rejected successfully', null, 200);
            } else {
                return $this->responseMsg('please enter a valid status', null, 422);
            }

        } catch (\Exception $e) {
            return $this->errorResponse();

        }
    }


}
