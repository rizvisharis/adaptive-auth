<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdaptiveLogin extends Component
{
    public $email = '';
    public $password = '';
    public $leftClickCount = 0;
    public $rightClickCount = 0;

    public $keyboardMetrics = [
        'shiftCount' => 0,
        'capsLockCount' => 0,
        'dwellTimes' => [],
        'flightDurations' => [],
        'upDownTimes' => [],
    ];

    public $mouseMetrics = [
        'speed' => 0,
        'maxSpeed' => 0,
        'maxPositiveAcc' => 0,
        'maxNegativeAcc' => 0,
        'totalXDistance' => 0,
        'totalYDistance' => 0,
        'totalDistance' => 0,
    ];

    public $contextData = [];
    public $locationData = [];

    public function submit()
    {
        $typingEndTime = now()->timestamp * 1000;

        $usernameTypingTime = $typingEndTime - request()->session()->get('username_typing_start', $typingEndTime);
        $passwordTypingTime = $typingEndTime - request()->session()->get('password_typing_start', $typingEndTime);

        Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ]);

        if (Auth::check()) {

            $user = Auth::user();

            $user->mouseMetrics()->create([
                'mouse_speed' =>  $this->mouseMetrics['speed'],
                'max_speed' => $this->mouseMetrics['maxSpeed'],
                'max_positive_acc' => $this->mouseMetrics['maxPositiveAcc'],
                'max_negative_acc' => $this->mouseMetrics['maxNegativeAcc'],
                'total_x_distance' => $this->mouseMetrics['totalXDistance'],
                'total_y_distance' => $this->mouseMetrics['totalYDistance'],
                'total_distance' => $this->mouseMetrics['totalDistance'],
                'left_click_count' => $this->leftClickCount,
                'right_click_count' => $this->rightClickCount,
            ]);

            $user->keyboardMetrics()->create([
                'username_typing_time' => strlen($this->email) ? $usernameTypingTime / strlen($this->email) : 0,
                'password_typing_time' => strlen($this->password) ? $passwordTypingTime / strlen($this->password) : 0,
                'shift_count' => $this->keyboardMetrics['shiftCount'],
                'caps_lock_count' => $this->keyboardMetrics['capsLockCount'],
                'average_dwell_time' => collect($this->keyboardMetrics['dwellTimes'])->avg(),
                'average_flight_duration' => collect($this->keyboardMetrics['flightDurations'])->avg(),
                'average_up_down_time' => collect($this->keyboardMetrics['upDownTimes'])->avg(),
            ]);

            $user->contextData()->create([
                'browser_name' => $this->contextData['browserName'] ?? '',
                'browser_version' => $this->contextData['browserVersion'] ?? '',
                'user_agent' => $this->contextData['userAgent'] ?? '',
                'color_depth' => $this->contextData['colorDepth'] ?? '',
                'canvas_fingerprint' => $this->contextData['canvasFingerprint'] ?? '',
                'os' => $this->contextData['os'] ?? '',
                'cpu_class' => $this->contextData['cpuClass'] ?? '',
                'resolution' => $this->contextData['resolution'] ?? '',
            ]);

            $user->locationData()->create([
                'ip' => $this->locationData['ip'] ?? '',
                'country_name' => $this->locationData['country_name'] ?? '',
                'country_code' => $this->locationData['country_code'] ?? '',
                'region' => $this->locationData['region'] ?? '',
                'city' => $this->locationData['city'] ?? '',
            ]);

            session()->flash('message', 'Login successful!');
            return redirect()->to('/dashboard');
        } else {
            session()->flash('error', 'Invalid credentials.');
            return;
        }
    }

    public function render()
    {
        return view('livewire.adaptive-login');
    }
}
