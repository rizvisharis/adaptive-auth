<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class OtpVerification extends Component
{
    public $otp = '';
    public $generatedOtp;

    public function mount()
    {
        $email = session('pending_user');
        $user = User::where('email', $email)->first();

        if (!$user) {
            abort(403);
        }

        $this->generatedOtp = rand(100000, 999999);
        session()->put('otp_code', $this->generatedOtp);

        if (!session()->has('otp_sent')) {
            Mail::raw("Your OTP is: {$this->generatedOtp}", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Your OTP Code');
            });
            session()->put('otp_sent', true);
        }
    }

    public function verify()
    {
        if ($this->otp != session('otp_code')) {
            session()->flash('error', 'Incorrect OTP.');
            return;
        }

        $user = User::where('email', session('pending_user'))->first();
        auth()->login($user);
        session()->forget(['pending_user', 'otp_code']);
        session()->flash('message', 'OTP verified successfully!');
        return redirect()->to('/dashboard');
    }

    public function render()
    {
        return view('livewire.otp-verification');
    }
}
