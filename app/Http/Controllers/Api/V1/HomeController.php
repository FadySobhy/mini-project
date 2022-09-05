<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserApiResource;
use App\Http\Resources\Fractal\UserResource;
use App\Http\Resources\Fractal\Support\FractalApiResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HomeController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return UserApiResource::collection(User::with(['posts', 'posts.comments', 'comments'])->paginate());
    }

    public function fractal(): Fractal
    {
        return fractal(User::with(['posts', 'posts.comments', 'comments'])->paginate());
    }

    public function fractalAPiResource(Request $request): AnonymousResourceCollection
    {
        return FractalApiResource::fractal(
            User::with(FractalApiResource::getRelations($request->get('relations')))->paginate(),
            UserResource::class,
        );
    }
}
