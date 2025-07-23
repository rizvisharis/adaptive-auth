<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserBehaviorResource extends JsonResource
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

            // Mouse Metrics
            'mouse_speed' => $this->mouse_speed,
            'max_speed' => $this->max_speed,
            'max_positive_acc' => $this->max_positive_acc,
            'max_negative_acc' => $this->max_negative_acc,
            'total_x_distance' => $this->total_x_distance,
            'total_y_distance' => $this->total_y_distance,
            'total_distance' => $this->total_distance,
            'left_click_count' => $this->left_click_count,
            'right_click_count' => $this->right_click_count,

            // Keyboard Metrics
            'email_typing_time' => $this->email_typing_time,
            'password_typing_time' => $this->password_typing_time,
            'shift_count' => $this->shift_count,
            'caps_lock_count' => $this->caps_lock_count,
            'average_dwell_time' => $this->average_dwell_time,
            'average_flight_duration' => $this->average_flight_duration,
            'average_up_down_time' => $this->average_up_down_time,
        ];
    }
}
