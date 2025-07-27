<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $security_question = '';
    public $security_answer = '';

    public function register()
    {
        $validated = Validator::make([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'security_question' => $this->security_question,
            'security_answer' => $this->security_answer,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'security_question' => 'required|string',
            'security_answer' => 'required|string|max:255',
        ])->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'security_question' => $this->security_question,
            'security_answer' => Hash::make($this->security_answer),
        ]);

        auth()->login($user);

        return redirect()->route('dashboard')->with('message', 'Registration successful!');
    }

    public function render()
    {
        return view('livewire.register');
    }
}
