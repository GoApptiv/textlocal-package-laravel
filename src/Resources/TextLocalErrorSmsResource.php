<?php

namespace GoApptiv\TextLocal\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TextLocalErrorSmsResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'errors' => $this['errors'],
            'warnings' => $this['warnings'] ?? [],
            'status' => $this['status'],
        ];
    }
}
