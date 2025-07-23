<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
    <div class="bg-white shadow-2xl rounded-2xl p-8 max-w-md w-full space-y-6">
        <h2 class="text-3xl font-bold text-center text-gray-800">Register</h2>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-400"
                    placeholder="Enter your full name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" :value="old('email')" required autocomplete="email"
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-400"
                    placeholder="Enter your email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl shadow-xs focus:ring-2 focus:ring-pink-400"
                    placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                    Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password"
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl shadow-xs focus:ring-2 focus:ring-pink-400"
                    placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Security Question -->
            <div>
                <label for="security_question" class="block text-sm font-medium text-gray-700">Security Question</label>
                <select id="security_question" name="security_question" required
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-400">
                    <option value="" disabled selected>Select a security question</option>
                    <option value="pet_name">What is your pet's name?</option>
                    <option value="mother_maiden">What is your mother's maiden name?</option>
                    <option value="first_school">What is the name of your first school?</option>
                    <option value="birth_city">In which city were you born?</option>
                    <option value="favorite_teacher">Who was your favorite teacher?</option>
                </select>
                <x-input-error :messages="$errors->get('security_question')" class="mt-2" />
            </div>

            <!-- Security Answer -->
            <div>
                <label for="security_answer" class="block text-sm font-medium text-gray-700">Your Answer</label>
                <input id="security_answer" type="text" name="security_answer" required
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-400"
                    placeholder="Enter your answer" />
                <x-input-error :messages="$errors->get('security_answer')" class="mt-2" />
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-pink-500 text-white py-2 rounded-xl font-semibold hover:from-purple-700 hover:to-pink-600 shadow-lg transition-all duration-200">
                Register
            </button>
        </form>

        <div class="text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('home') }}" class="text-indigo-600 hover:underline font-medium">
                Click here to login
            </a>
        </div>

        <p class="text-xs text-center text-gray-400">© {{ now()->year }} RS7 — Adaptive Security</p>
    </div>
</div>