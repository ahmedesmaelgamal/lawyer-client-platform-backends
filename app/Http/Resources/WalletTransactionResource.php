<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type'=>$this->credit ? 'credit' : 'debit',
            'comment'=>$this->comment,
            'time'=>Carbon::parse($this->created_at)->format('d F Y'),
            'amount'=> $this->credit??$this->debit,
        ];
    }
}
