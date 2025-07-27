<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
    <div class="bg-white shadow-2xl rounded-2xl p-8 max-w-md w-full space-y-6">
        <h2 class="text-3xl font-bold text-center text-gray-800">Security Question</h2>

        @if (session()->has('error'))
            <div class="p-3 bg-red-100 text-red-700 rounded-lg text-center text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="verify" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700">Question</label>
                <p class="mt-1 px-4 py-2 bg-gray-100 rounded-xl text-gray-800 text-sm shadow-sm">
                    {{ $question }}
                </p>
            </div>

            <div>
                <label for="answer" class="block text-sm font-medium text-gray-700">Your Answer</label>
                <input id="answer" type="text" wire:model.defer="answer" required
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-400"
                    placeholder="Enter your answer" />
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-pink-500 text-white py-2 rounded-xl font-semibold hover:from-purple-700 hover:to-pink-600 shadow-lg transition-all duration-200">
                Verify
            </button>
        </form>

        <p class="text-xs text-center text-gray-400">© {{ now()->year }} RS7 — Adaptive Security</p>
    </div>
</div>
