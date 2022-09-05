<?php

namespace App\Http\Resources\Traits;

use Illuminate\Http\Resources\Json\JsonResource;

trait CanApiResourceV4
{
    public function formatArray(array $data)
    {
        dd(request()->all());
        $this->replaceInputInQueryParam('mainResource', []);
        $this->reformatRequest();

        dd(request()->all());
        $data = $data['data'];
        $response = [];

        $response = $this->getOnly($data, $response);

        return $response;
    }

    private function reformatRequest(): void
    {
        $only = [];
        $relations = [];

        if (request()->has('only')){
            $onlyArray = request()->get('only');
            if (is_string($onlyArray)) {
                $onlyArray = array_values(explode(',', $onlyArray));
            }
            $only['main'] = $onlyArray;
        }

        if (request()->has('includes') && !empty(request()->input('includes'))) {
            $includes = request()->get('includes');

            foreach ($includes as $key => $value) {
                if (!empty($value['only']))
                    $only[$key] = $value['only'];

                if (!empty($value['nested_includes'])) {
                    foreach ($value['nested_includes'] as $nestedKey => $nestedValue) {
                        $relations[$key][$nestedKey] = [];
                        if (!empty($value['only']))
                            $only[$key][$nestedKey] = $value['only'];
                    }
                }
                else {
                    $relations[$key][] = [];
                }
            }
        }

        $this->replaceInputInQueryParam('only', $only);
        $this->replaceInputInQueryParam('relations', $relations);
    }

    private function getOnly(array $data, array $response): array
    {
        $onlyKey = request()->has('mainResource') ? 'main' : $resourceName;

        $only = request()->input('only')[$onlyKey];

        if (!empty($only)) {
            foreach ($data as $key => $value) {
                if (in_array($key, $only))
                    $response[$key] = $value;
            }
            return $response;
        }

        return $data;
    }

    public function getRelations(array $response, string $resourceName): array
    {
        $includes = request()->get('includes');

//        foreach ($includes as $key => $value) {
//            $this->checkInputOnlyInRequest($value);
//            if ($key)
//        }
        if (request()->has('firstResource')) {
            foreach ($includes as $key => $value)


            request()->replace(request()->except('firstResource'));
        }
        else {
            if (in_array($resourceName, $includes)) {
                $this->checkInputOnlyInRequest($includes[$resourceName]);
            }
        }


        return $response;
    }

    private function checkInputOnlyInRequest(array $value): void
    {
        $only = !empty($value['only']) ? $value['only'] : [];
        $this->replaceInputInQueryParam('only', $only);
    }

    private function replaceInputInQueryParam(string $input, array $value): void
    {
        request()->merge([$input => $value]);
    }

    private function callIncluded(string $key, array $response): JsonResource
    {
        $callIncluded = $key.'Included';
        $include = false;
        if (method_exists($this, $callIncluded))
            $include = true;
        return $this->$callIncluded($include);
    }

}
