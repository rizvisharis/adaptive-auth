<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserContextResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'email' => $this->email,
            'browser_name' => $this->browser_name,
            'browser_version' => $this->browser_version,
            'user_agent' => $this->user_agent,
            'color_depth' => $this->color_depth,
            'canvas_fingerprint' => $this->canvas_fingerprint,
            'os' => $this->os,
            'cpu_class' => $this->cpu_class,
            'resolution' => $this->resolution,
            'ip' => $this->ip,
            'country_name' => $this->country_name,
            'country_code' => $this->country_code,
            'region' => $this->region,
            'city' => $this->city,
        ];
    }
}
