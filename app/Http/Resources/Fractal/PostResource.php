<?php

namespace App\Http\Resources\Fractal;

use JsonSerializable;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Arrayable;
use App\Http\Resources\Fractal\Support\FractalApiResource;

class PostResource extends FractalApiResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        $data = $this->getOnlyData([
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ], 'posts');

        return array_merge($data, $this->relations());
    }

    /**
     * @return array
     */
    public function relations(): array
    {
        return [
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
