<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\CanApiResource;
use App\Http\Resources\Traits\CanApiResourceV6;
use Illuminate\Http\Resources\Json\JsonResource;

class UserApiResource extends JsonResource
{
    use CanApiResource;

    public $resourceName = 'user';

    public $only = [

    ];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        dd($request->all());
        $data =  $this->formatArray([
//          return  [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
//                'posts' => $this->whenLoaded($request->has('relations.posts'), $this->postsIncluded()),
////                'comments' => $this->when($this->relationIncluded, $this->commentsIncluded()),
////                'posts' => PostResource::collection($this->posts),
//                'comments' => CommentResource::collection($this->comments),
//              ];
        ]);

//        dd($data);
        return $data;
    }

    public function postInclude(bool $included = false): JsonResource
    {
//        return $this->when($included, PostResource::collection($this->posts));
        return PostResource::collection($this->posts);
    }

    public function commentInclude(bool $included = false): JsonResource
    {
//        return ['comments' => $this->when($included, CommentResource::collection($this->comments))];
        return CommentResource::collection($this->comments);
    }
}
