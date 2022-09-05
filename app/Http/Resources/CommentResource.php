<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\CanApiResourceV6;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    use CanApiResourceV6;

    public $resourceName = 'comment';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->formatArray([
//            return  [
                'id' => $this->id,
                'name' => $this->name,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
//                ];
        ]);
    }
}
