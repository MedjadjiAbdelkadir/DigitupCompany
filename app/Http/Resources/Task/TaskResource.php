<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'user_id'  => $this->user_id,
            'titre'        => $this->titre,
            'description' => $this->description,
            'statut'       => $this->statut,
            'due_date'       => $this->due_date,
        ];
    }
}
