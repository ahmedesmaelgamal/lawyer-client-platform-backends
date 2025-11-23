<?php

namespace App\Http\Resources;

use App\Models\ProductDiscount;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarketProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $discount = $this->productDiscount ? (int) $this->productDiscount->discount : 0;
        $price = (int) $this->price;
        $totalAfterDiscount = $price - ($price * $discount / 100);

        return [
            'id'=>$this->id,
            'title' => $this->title,
            'image' => getFile($this->image),
            'description' => $this->description,
            'location' => $this->location,
            'stock' => (int) $this->stock,
            'price' => (int)$this->price,
            'discount' => $this->productDiscount ? (int) $this->productDiscount->discount : 0,
            'price_after_discount' => (int) $totalAfterDiscount,
            'market_product_category' => new MarketProductCategoryResource($this->marketProductCategory),
            'status' => $this->status,
        ];
    }
}
