<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'market_product' => MarketProductResource::make($this->marketProduct),
//            'lawyer' => OfficeTeamResource::make($this->lawyer),
            'qty' => $this->qty,
            'phone' => (string) $this->phone,
            'address' => (string) $this->address,
            'total_price'=>$this->total_price,
            'status'=> (string) $this->status,
            'created_at'=>$this->created_at->format('d/m/Y'),
        ];
    }
}
