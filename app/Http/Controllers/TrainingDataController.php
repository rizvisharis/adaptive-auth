<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserBehaviorResource;
use App\Models\User;
use App\Models\ContextData;
use App\Http\Resources\UserContextResource;

class TrainingDataController extends Controller
{
    public function getContextData()
    {
        $users = User::with(['contextData', 'locationData'])->get();

        $rows = collect();

        foreach ($users as $user) {
            foreach ($user->contextData as $context) {
                $location = $user->locationData->first();

                $rows->push((object)[
                    'email' => $user->email,
                    'browser_name' => $context->browser_name,
                    'browser_version' => $context->browser_version,
                    'user_agent' => $context->user_agent,
                    'color_depth' => $context->color_depth,
                    'canvas_fingerprint' => $context->canvas_fingerprint,
                    'os' => $context->os,
                    'cpu_class' => $context->cpu_class,
                    'resolution' => $context->resolution,
                    'ip' => optional($location)->ip,
                    'country_name' => optional($location)->country_name,
                    'country_code' => optional($location)->country_code,
                    'region' => optional($location)->region,
                    'city' => optional($location)->city,
                ]);
            }
        }

        $resource = UserContextResource::collection($rows);

        return response()->json($resource);
    }

    public function getBehaviorData()
    {
        $users = User::with(['mouseMetrics', 'keyboardMetrics'])->get();

        $rows = collect();

        foreach ($users as $user) {
            $mouseMetrics = $user->mouseMetrics;
            $keyboardMetrics = $user->keyboardMetrics;

            $max = max($mouseMetrics->count(), $keyboardMetrics->count());

            for ($i = 0; $i < $max; $i++) {
                $mouse = $mouseMetrics[$i] ?? null;
                $keyboard = $keyboardMetrics[$i] ?? null;

                $rows->push((object)[
                    'email' => $user->email,
                    'mouse_speed' => optional($mouse)->mouse_speed,
                    'max_speed' => optional($mouse)->max_speed,
                    'max_positive_acc' => optional($mouse)->max_positive_acc,
                    'max_negative_acc' => optional($mouse)->max_negative_acc,
                    'total_x_distance' => optional($mouse)->total_x_distance,
                    'total_y_distance' => optional($mouse)->total_y_distance,
                    'total_distance' => optional($mouse)->total_distance,
                    'left_click_count' => optional($mouse)->left_click_count,
                    'right_click_count' => optional($mouse)->right_click_count,
                    'email_typing_time' => optional($keyboard)->email_typing_time,
                    'password_typing_time' => optional($keyboard)->password_typing_time,
                    'shift_count' => optional($keyboard)->shift_count,
                    'caps_lock_count' => optional($keyboard)->caps_lock_count,
                    'average_dwell_time' => optional($keyboard)->average_dwell_time,
                    'average_flight_duration' => optional($keyboard)->average_flight_duration,
                    'average_up_down_time' => optional($keyboard)->average_up_down_time,
                ]);
            }
        }

        $resource =  UserBehaviorResource::collection($rows);

        return response()->json($resource);
    }
}
