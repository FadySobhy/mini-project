<?php

namespace App\Http\Resources\Fractal\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FractalApiResource extends JsonResource
{
    /**
     * @param Builder|LengthAwarePaginator $data
     * @param string $resourceClass
     * @return AnonymousResourceCollection
     */
    public static function fractal(
        Builder|LengthAwarePaginator $data,
        string $resourceClass
    ): AnonymousResourceCollection
    {
        return $resourceClass::collection($data);
    }

    /**
     * @param array $relations
     * @return array
     */
    public static function getRelations(
        array $relations,
    ): array
    {
        $relationsArray = FractalApiResource::checkRelations($relations);
        request()->merge(['currentRelations' => $relationsArray]);
        return array_keys($relationsArray);
    }

    /**
     * @param array $relations
     * @param string|null $parentRelation
     * @return array
     */
    private static function checkRelations(
        array $relations,
        string $parentRelation = null
    ): array
    {
        $relationsArray = [];
        foreach ($relations as $relation => $value) {
            $relationName = $relation;
            if ($parentRelation !== null)
                $relationName = $parentRelation. '.' .$relation;

            $relationsArray[$relationName] = $value['only'] ?? [];
            if (in_array('relations', array_keys($value)))
                $relationsArray = array_merge($relationsArray, self::checkRelations($value['relations'], $relation));
        }

        return $relationsArray;
    }

    /**
     * @param array $data
     * @param string $resourceName
     * @return array
     */
    protected function getOnlyData(array $data, string $resourceName): array
    {
        /*
         * check request has previousRelation ?
         * previousRelation exists in the currentRelations array in the request
         * (previousRelation.resourceName) = users.posts => exists in the currentRelations array ?
         * get the only data of this key (users.posts) : check the resourceName exists in the currentRelations array
         * get only data of this key
         * get the main only input in the request
         * set the previousRelation as resourceName
         * check resourceName exists currentRelations array and previousRelation => resourceName
         *
         */

        if(request()->has('previousRelation')){
            $relations = request()->input('currentRelations');
            $relationKey = request()->input('previousRelation'). '.' . $resourceName;

            if (in_array($relationKey, array_keys($relations)))
                $only = $relations[$relationKey];
            elseif (in_array($resourceName, array_keys($relations)))
                $only = $relations[$resourceName];

        } else {
            $only = request()->input('only');
        }

        request()->merge(['previousRelation' => $resourceName]);

        if (!empty($only)) {
            $response = [];
            if (is_string($only))
                $only = array_values(explode(',', $only));

            foreach ($data as $key => $value)
                if (in_array($key, $only))
                    $response[$key] = $value;

            return $response;
        }

        return $data;
    }

}
