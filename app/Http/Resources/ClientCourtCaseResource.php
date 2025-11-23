<?php

namespace App\Http\Resources;

use App\Enums\CourtCaseStatusEnum;
use App\Enums\CourtCaseTypeEnum;
use App\Models\City;
use App\Models\Rate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ClientCourtCaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = Auth::guard('client_api')->user();

        $event_status = CourtCaseStatusEnum::NEW->value;
        if ($this->status == CourtCaseStatusEnum::NEW->value) {
            $event_status = $this->offerEvent ? $this->offerEvent->status : CourtCaseStatusEnum::NEW->value;
        } else {
            $event_status = $this->acceptedEvent ? $this->acceptedEvent->status : CourtCaseStatusEnum::NEW->value;
        }

        $updates = $this->courtCaseUpdates->groupBy(function ($update) {
            return Carbon::parse($update->date)->translatedFormat('F Y'); // ترجمة الشهر
        })->map(function ($group) {
            return CourtCaseUpdatesResource::collection($group);
        });

        if($this->city_id){
            $city = City::where("id" , $this->city_id)->first();
        } else {
            $city = null;
        }

        return [
            "id" => $this->id,
            "title" => $this->title,
            "case_number" => (int) $this->case_number   ,
            'type' => CourtCaseTypeEnum::from($this->type)->value,
            "seen" => (int) $this->seen,
            "lawyer_rate" => ($this->from_user_type == 'lawyer') ? CourtCaseRateResource::collection($this->from_user_id) : (($this->to_user_type == 'lawyer') ? CourtCaseRateResource::collection($this->to_user_id) : 'no rate found for the lawyer'),
            "client_rate" => ($this->from_user_type == 'client') ? CourtCaseRateResource::collection($this->from_user_id) : (($this->to_user_type == 'client') ? CourtCaseRateResource::collection($this->to_user_id) : 'no rate found for the lawyer'),
            "status" => CourtCaseStatusEnum::from($this->status)->value,
            "event_status"  => $event_status,
            "case_estimated_price" => (int) $this->case_estimated_price,
            "case_speciality" => $this->caseSpeciality ? CourtCaseLevelResource::make($this->caseSpeciality) : null,
            'sub_case_speciality' => $this->subCaseSpeciality ? SubCaseSpecializationResource::make($this->subCaseSpeciality) : null,
            "details" => $this->details,
            "case_final_price" => (int) $this->case_final_price,
            "created_at" => $this->created_at->format('Y-m-d H:i:s'),
            "speciality" => new SpecialityResource($this->speciality),
            "files" => CourtCaseFileResource::collection($this->courtCaseFiles),
            "updates" => $updates, // التحديثات مجمعة حسب الشهر والسنة
            "client" => new ClientResource($this->client),
            'lawyer_event' => CourtCaseEventResource::make($this->acceptedEvent),
            'all_events' => CourtCaseEventResource::collection($this->courtCaseEvents),
            "city_id" => $city ? CityResource::make($city) : [],
            //            'court_case_final_paid' => $this->court_case_final_paid,
            //            'court_case_final_unpaid' => $this->court_case_final_unpaid,
        ];
    }
    // Add this method to your class to simplify rate fetching logic
    protected function getRateForUserType($userType)
    {
        $userId = null;

        // Determine the user ID based on the user type
        if ($this->from_user_type === $userType) {
            $userId = $this->from_user_id;
        } elseif ($this->to_user_type === $userType) {
            $userId = $this->to_user_id;
        }

        // If no user ID is found, return an empty collection
        if (!$userId) {
            return CourtCaseRateResource::collection(collect()); // Return an empty collection
        }

        // Fetch the rate for the user ID
        $rate = Rate::where('court_case_id', $this->id)->where('user_id', $userId)->first();

        // If no rate is found, return an empty collection
        if (!$rate) {
            return CourtCaseRateResource::collection(collect()); // Return an empty collection
        }

        // Return the rate as a collection
        return CourtCaseRateResource::collection(collect([$rate]));
    }
}
