<?php

namespace GoApptiv\TextLocal\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TextLocalSuccessSmsResource extends ResourceCollection
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
            'balance' => $this['balance'],
            'batch_id' => $this['batch_id'],
            'cost' => $this['cost'],
            'status' => $this['status'],
            'messages' => $this['messages']
        ];
    }
}
