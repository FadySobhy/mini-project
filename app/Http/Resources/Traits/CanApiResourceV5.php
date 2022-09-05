<?php

namespace App\Http\Resources\Traits;

use Illuminate\Http\Resources\Json\JsonResource;

trait CanApiResourceV5
{
    public function formatArray(array $data)
    {
        $data = $data['data'];
        $response = [];

//        $this->setIncludeReturnedInputValue(false);

        if (request()->has('only'))
            $response = $this->getOnly($data, $response);
        else
            $response = $data;

        if (request()->has('includes') && !empty(request()->input('includes')))
            $response = $this->getIncludes($response);

        return $response;
    }

    private function getOnlyFromRequest(): array
    {
        $only = [];
        if (request()->has('only')){
            $only = request()->get('only');
            if (is_string($only))
                $only = array_values(explode(',', $only));
        }
        return $only;
    }

    private function setIncludeReturnedInputValue(bool $value): void
    {
        request()->request->add(['includeReturned' => $value]);
    }

    private function getOnly(array $data, array $response): array
    {
        $only = request()->get('only');

        if (!empty($only)) {
            if (is_string($only)) {
                $only = array_values(explode(',', $only));
            }

            foreach ($data as $key => $value) {
                if (in_array($key, $only))
                    $response[$key] = $value;
            }

            return $response;
        }

        return $data;
    }

    private function getIncludes(array $response): array
    {
//        dd(request()->all());
        $includes = request()->get('includes');

        foreach ($includes as $key => $value) {
            $only = !empty($value['only']) ? $value['only'] : [];
            $this->replaceInputInQueryParam('only', $only);
//            dd(request()->all());

            if (!request()->has('RelationReturned'))
                $response[$key] = $this->callIncluded($key, $response);

            if (!empty($value['nested_includes'])) {
                $response = $this->nestedIncludes($value['nested_includes'], $key, $response);
                request()->replace(request()->except('includes.'.$key));
                request()->replace(request()->except('RelationReturned'));
                request()->replace(request()->except('lastNestedIncludeCalled'));
            }
            else {
                request()->replace(request()->except('includes.'.$key));
//                dd($response, request()->all());
//                $response[$key] = $this->callIncluded($key, $response);
            }
        }

        return $response;
    }

    private function nestedIncludes(array $nestedIncludes, string $previousInclude, array $response)
    {
        foreach ($nestedIncludes as $key => $value) {
            $only = !empty($value['only']) ? $value['only'] : [];
            $this->replaceInputInQueryParam('only', $only);
            $this->replaceInputInQueryParam('lastNestedIncludeCalled', ['']);

            if (request()->has('lastNestedIncludeCalled') && request()->input('lastNestedIncludeCalled') != $key) {
                $this->replaceInputInQueryParam('lastNestedIncludeCalled', [$key]);
                $response[$previousInclude][$key] = $this->callIncluded($key, $response);
            }
        }
//        request()->request->add(['includes' => array_merge($nestedIncludes, request()->input('includes'))]);
//        request()->replace(request()->except('includes.previous_has_nested'));
//        request()->replace(request()->except('includes.'.$previousInclude));
        return $response;
    }

    private function replaceInputInQueryParam(string $input, array $only): void
    {
        request()->merge([$input => $only]);
    }

    private function setRelationReturnedInputValue(bool $value): void
    {
        request()->request->add(['RelationReturned' => $value]);
    }

//    private function setIncludeAsReturned(string $key, array $includes): void
//    {
//        request()->request->add(['includes' => array_merge(['previous_has_nested' => true], request()->input('includes'))]);
////        $includes[$key]['returned'] = true;
////        request()->request->add(['includes' => $includes]);
////        dd(request()->get('includes'));
//    }

    private function callIncluded(string $key, array $response): JsonResource
    {
        $this->setRelationReturnedInputValue(true);
        $callIncluded = $key.'Included';
//            if (method_exists(, $callIncluded))
        return $this->$callIncluded();
//        return  $response;
    }
}
