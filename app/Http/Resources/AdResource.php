<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class AdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
//            'lawyer' => OfficeTeamResource::make($this->lawyer),
//            'ad_offer_package' => AdOfferPackageResource::make($this->offerPackage),
//            'ad_offer_package' => $this->offerPackage->id,
            'status' => $this->status,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'image' => getFile($this->image),
            'ad_confirmation' => $this->ad_confirmation,
        ];
    }
}
