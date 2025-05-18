<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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
        $usernameTypingTime = $typingEndTime - session('username_typing_start', $typingEndTime);
        $passwordTypingTime = $typingEndTime - session('password_typing_start', $typingEndTime);

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->flash('error', 'Invalid credentials.');
            return;
        }

        $user = Auth::user();

        if (config('services.data_collection_mode')) {
            $this->storeUserMetrics($user, $usernameTypingTime, $passwordTypingTime);
            session()->flash('message', 'Login successful!');
            return redirect()->to('/dashboard');
        }

        $result = $this->callAuthScoreAPI($usernameTypingTime, $passwordTypingTime);

        if (!$result) {
            session()->flash('error', 'Authentication service error.');
            return;
        }

        $this->handleNextStep($result['next_step'] ?? 'otp_verification');
    }

    private function storeUserMetrics($user, $usernameTypingTime, $passwordTypingTime)
    {
        $user->mouseMetrics()->create([
            'mouse_speed' => $this->mouseMetrics['speed'],
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

        $user->contextData()->create($this->contextData + [
            'browser_name' => $this->contextData['browserName'] ?? '',
            'browser_version' => $this->contextData['browserVersion'] ?? '',
            'user_agent' => $this->contextData['userAgent'] ?? '',
            'color_depth' => $this->contextData['colorDepth'] ?? '',
            'canvas_fingerprint' => $this->contextData['canvasFingerprint'] ?? '',
            'os' => $this->contextData['os'] ?? '',
            'cpu_class' => $this->contextData['cpuClass'] ?? '',
            'resolution' => $this->contextData['resolution'] ?? '',
        ]);

        $user->locationData()->create($this->locationData + [
            'ip' => $this->locationData['ip'] ?? '',
            'country_name' => $this->locationData['country_name'] ?? '',
            'country_code' => $this->locationData['country_code'] ?? '',
            'region' => $this->locationData['region'] ?? '',
            'city' => $this->locationData['city'] ?? '',
        ]);
    }

    private function callAuthScoreAPI($usernameTypingTime, $passwordTypingTime)
    {
        $behaviorInput = [
            'mouse_speed' => $this->safeFloat($this->mouseMetrics['speed']),
            'max_speed' => $this->safeFloat($this->mouseMetrics['maxSpeed']),
            'max_positive_acc' => $this->safeFloat($this->mouseMetrics['maxPositiveAcc']),
            'max_negative_acc' => $this->safeFloat($this->mouseMetrics['maxNegativeAcc']),
            'total_x_distance' => $this->safeFloat($this->mouseMetrics['totalXDistance']),
            'total_y_distance' => $this->safeFloat($this->mouseMetrics['totalYDistance']),
            'total_distance' => $this->safeFloat($this->mouseMetrics['totalDistance']),
            'left_click_count' => $this->safeInt($this->leftClickCount),
            'right_click_count' => $this->safeInt($this->rightClickCount),
            'username_typing_time' => strlen($this->email) ? $usernameTypingTime / strlen($this->email) : 0,
            'password_typing_time' => strlen($this->password) ? $passwordTypingTime / strlen($this->password) : 0,
            'shift_count' => $this->safeInt($this->keyboardMetrics['shiftCount']),
            'caps_lock_count' => $this->safeInt($this->keyboardMetrics['capsLockCount']),
            'average_dwell_time' => $this->safeAvg($this->keyboardMetrics['dwellTimes']),
            'average_flight_duration' => $this->safeAvg($this->keyboardMetrics['flightDurations']),
            'average_up_down_time' => $this->safeAvg($this->keyboardMetrics['upDownTimes']),
        ];

        $contextInput = [
            'browser_name' => $this->contextData['browserName'] ?? '',
            'browser_version' => $this->contextData['browserVersion'] ?? '',
            'user_agent' => $this->contextData['userAgent'] ?? '',
            'color_depth' => $this->contextData['colorDepth'] ?? '',
            'canvas_fingerprint' => $this->contextData['canvasFingerprint'] ?? '',
            'os' => $this->contextData['os'] ?? '',
            'cpu_class' => $this->contextData['cpuClass'] ?? '',
            'resolution' => $this->contextData['resolution'] ?? '',
        ];

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post('http://127.0.0.1:5000/api/auth-score', [
                'behavior_input' => $behaviorInput,
                'context_input' => $contextInput,
                'expected_user' => $this->email,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        logger()->error('Flask model error: ' . $response->body());
        return null;
    }

    private function handleNextStep(string $step)
    {
        switch ($step) {
            case 'simple_auth':
                session()->flash('message', 'Login successful!');
                return redirect()->to('/dashboard');
            case 'security_question':
                session()->put('pending_user', $this->email);
                return redirect()->to('/security-question');
            default:
                session()->put('pending_user', $this->email);
                return redirect()->to('/otp-verification');
        }
    }

    private function safeFloat($value): float
    {
        return is_numeric($value) ? (float) $value : 0.0;
    }

    private function safeInt($value): int
    {
        return is_numeric($value) ? (int) $value : 0;
    }

    private function safeAvg(array $values): float
    {
        return is_numeric($avg = collect($values)->avg()) ? $avg : 0.0;
    }

    public function render()
    {
        return view('livewire.adaptive-login');
    }
}
