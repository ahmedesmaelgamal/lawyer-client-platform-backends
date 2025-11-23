<?php

namespace App\Http\Resources;

use App\Models\Client;
use App\Models\ContractFile;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'file_path' => getFile($this->file_path),
            'file_name'=> $this->file_name,
            'file_extension'=>$this->file_extension,
            "is_paid" => WalletTransaction::where("model_id" , $this->id)
                                        ->where("user_id" , auth('client_api')->user()->id)
                                        ->where("user_type" , "client")
                                        ->where("model_type" , ContractFile::class)
                                        ->exists(),

//            'contract_category_id'=>$this->contract_category_id
        ];
    }
}
