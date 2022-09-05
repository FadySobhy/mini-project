<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\CanApiResourceV6;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    use CanApiResourceV6;

    public $resourceName = 'post';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        dd($request->all());
        return $this->formatArray([
//           return [
                'id' => $this->id,
                'name' => $this->name,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
//                'comments' => $this->when($request->has('includes.posts.nested_includes.comments'), $this->commentsIncluded())
//               ];
        ]);
    }

//    public function commentsIncluded()
//    {
//        return CommentResource::collection($this->comments);
//    }

    public function commentsIncluded(bool $included): array
    {
        return ['comments' => $this->when($included, CommentResource::collection($this->comments))];
//        return CommentResource::collection($this->comments);
    }
}
