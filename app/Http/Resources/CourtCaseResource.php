<?php

namespace App\Http\Resources;

use App\Enums\CourtCaseStatusEnum;
use App\Enums\CourtCaseTypeEnum;
use App\Models\Rate;
use App\Models\CourtCaseEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CourtCaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        if ($this->checkGuard() == 'client_api') {
            $user = Auth::guard('client_api')->user();
        } elseif ($this->checkGuard() == 'lawyer_api') {
            $user = Auth::guard('lawyer_api')->user();
        }
        $event = CourtCaseEvent::where('lawyer_id', $user->id)
            ->where('court_case_id', $this->id)
            ->latest()->first();

        $updates = $this->courtCaseUpdates->groupBy(function ($update) {
            return Carbon::parse($update->date)->translatedFormat('F Y'); // ترجمة الشهر
        })->map(function ($group) {
            return CourtCaseUpdatesResource::collection($group);
        });

        return [
            "id" => $this->id,
            "title" => $this->title,
            "case_number" => (int) $this->case_number,
            "court_case_level" => $this->courtCaseLevel ? $this->courtCaseLevel->title : null,
            "seen" => (int) $this->seen,
            'type' => CourtCaseTypeEnum::from($this->type)->value,
            "lawyer_rate" => ($this->from_user_type == 'lawyer') ? CourtCaseRateResource::collection($this->from_user_id) : (($this->to_user_type == 'lawyer') ? CourtCaseRateResource::collection($this->to_user_id) : 'no rate found for the lawyer'),
            "client_rate" => ($this->from_user_type == 'client') ? CourtCaseRateResource::collection($this->from_user_id) : (($this->to_user_type == 'client') ? CourtCaseRateResource::collection($this->to_user_id) : 'no rate found for the lawyer'),
            "status" => CourtCaseStatusEnum::from($this->status)->value,
            "case_estimated_price" => (int) $this->case_estimated_price,
            "details" => $this->details,
            "case_speciality" => $this->caseSpeciality ? CourtCaseLevelResource::make($this->caseSpeciality) : null,
            'sub_case_speciality' => $this->subCaseSpeciality ? SubCaseSpecializationResource::make($this->subCaseSpeciality) : null,
            "case_final_price" => (int) $this->case_final_price,
            "created_at" => $this->created_at->format('Y-m-d H:i:s'),
            "speciality" => new SpecialityResource($this->speciality),
            "files" => CourtCaseFileResource::collection($this->courtCaseFiles),
            "updates" => $updates,
            "client" => new ClientResource($this->client),
            'lawyer_event' => $event ? CourtCaseEventResource::make($event) : null,
            'all_events' => CourtCaseEventResource::collection($this->courtCaseEvents),
        ];
    }

    protected function checkGuard()
    {
        // Check if the client guard is authenticated
        if (Auth::guard('client_api')->check()) {
            return 'client_api'; // Return the guard name
        }

        // Check if the lawyer guard is authenticated
        if (Auth::guard('lawyer_api')->check()) {
            return 'lawyer_api'; // Return the guard name
        }

        // If neither guard is authenticated, throw an exception or return false
        return false;
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
