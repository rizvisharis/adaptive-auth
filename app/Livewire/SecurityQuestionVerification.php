<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class SecurityQuestionVerification extends Component
{
    public $answer = '';

    public function verify()
    {
        $email = session('pending_user');
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($this->answer, $user->security_answer)) {
            session()->flash('error', 'Incorrect answer.');
            return;
        }

        auth()->login($user);
        session()->forget('pending_user');
        session()->flash('message', 'Security question verified successfully!');
        return redirect()->to('/dashboard');
    }

    public function render()
    {
        $email = session('pending_user');
        $user = User::where('email', $email)->first();
        return view('livewire.security-question-verification', ['question' => $user?->security_question ?? '']);
    }
}
