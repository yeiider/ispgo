<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'price' => $this->price,
            'special_price' => $this->special_price,
            'cost_price' => $this->cost_price,
            'brand' => $this->brand,
            'qty' => $this->qty,
            'image' => $this->image,
            'description' => $this->description,
            'reference' => $this->reference,
            'taxes' => $this->taxes,
            'status' => $this->status,
            'url_key' => $this->url_key,
            'warehouse_id' => $this->warehouse_id,
            'category_id' => $this->category_id,
        ];
    }
}
