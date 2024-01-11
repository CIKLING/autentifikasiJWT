<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArtikelShow extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        date_default_timezone_set("Asia/Jakarta");
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'judul' => $this->judul,
            'body' => $this->body,
            'created_at' => $this->created_at->format("d F Y H:i:s"),
            'updated_at' => $this->updated_at->format("d F Y H:i:s"),
            'komentar' => $this->komentar,
        ];
    }
}
