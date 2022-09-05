<?php


namespace App\Http\Resources\Traits;


use Illuminate\Http\Resources\Json\JsonResource;

trait CanApiResourceV2
{
    public function formatArray(array $data)
    {
        $data = $data['data'];
        $response = [];

        if (request()->has('only'))
            $response = $this->getOnly($data, $response);
        else
            $response = $data;

        if (request()->has('nested_includes'))
            $this->nestedIncludes(request()->input('nested_includes'), $response);

        if (!request()->has('RelationReturned'))
            if (request()->has('includes') && !empty(request()->input('includes')))
                $response = $this->getIncludes($response);

        return $response;
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
        $includes = request()->get('includes');

        foreach ($includes as $key => $value) {
            $this->checkInputOnlyInRequest($value);

            if (!empty($value['nested_includes'])) {
                $this->replaceInputInQueryParam('nested_includes', [$key => $value['nested_includes']]);
            }
            else {
                $this->removeReturnedIncludeFromRequest($key);
            }

            $response[$key] = $this->callIncluded($key, $response);
        }

        return $response;
    }

    private function nestedIncludes(array $nestedIncludes, array $response)
    {
        $count = 0;
        foreach ($nestedIncludes as $previousInclude => $nestedInclude) {
            foreach ($nestedInclude as $key => $value) {
                if (count($nestedInclude) == ++$count)
                    request()->replace(request()->except('nested_includes'));

                $this->checkInputOnlyInRequest($value);
                $response[$previousInclude][$key] = $this->callIncluded($key, $response);
            }
        }
//        request()->request->add(['includes' => array_merge($nestedIncludes, request()->input('includes'))]);
//        request()->replace(request()->except('includes.previous_has_nested'));
//        request()->replace(request()->except('includes.'.$previousInclude));
        return $response;
    }

    private function setRelationReturnedInputValue(bool $value): void
    {
        request()->request->add(['RelationReturned' => $value]);
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

    private function removeReturnedIncludeFromRequest(string $key):void
    {
        $this->setRelationReturnedInputValue(true);
        request()->replace(request()->except('includes.'.$key));
    }

    private function callIncluded(string $key, array $response): JsonResource
    {
        $callIncluded = $key.'Included';
        return $this->$callIncluded();
    }
}
