<div x-data="adaptiveAuth()" x-init="initListeners()"
    class="min-h-screen flex items-center justify-center bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"
    @keydown.window="recordKeyDown($event)" @keyup.window="recordKeyUp($event)"
    @mousemove.window="recordMouseMove($event)" @mousedown.window="recordMouseClick($event)">

    <div class="bg-white shadow-2xl rounded-2xl p-8 max-w-md w-full space-y-6">
        <h2 class="text-3xl font-bold text-center text-gray-800">Adaptive Login</h2>

        @if (session()->has('message'))
        <div class="p-3 bg-green-100 text-green-700 rounded-lg text-center text-sm font-medium">
            {{ session('message') }}
        </div>
        @endif

        @if(session()->has('error'))
        <div class="p-3 bg-red-100 text-red-700 rounded-lg text-center text-sm font-medium">
            {{ session('error') }}
        </div>
        @endif


        <form wire:submit.prevent="submit" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" wire:model.defer="email" @input="startEmailTimer()" required
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-400"
                    placeholder="Enter your email" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" wire:model.defer="password" @input="startPasswordTimer()" required
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl shadow-xs focus:ring-2 focus:ring-pink-400"
                    placeholder="••••••••" />
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-pink-500 text-white py-2 rounded-xl font-semibold hover:from-purple-700 hover:to-pink-600 shadow-lg transition-all duration-200">
                Login
            </button>
        </form>

        <div class="text-center text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('registration') }}" class="text-indigo-600 hover:underline font-medium">
                Click here to register
            </a>
        </div>

        <p class="text-xs text-center text-gray-400">© {{ now()->year }} RS7 — Adaptive Security</p>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/2.1.0/fingerprint2.min.js"></script>

<script>
    function adaptiveAuth() {
        return {
            mouseData: [],
            prevSpeed: 0,
            emailStart: null,
            passwordStart: null,
            keyPresses: [],
            keyDownTime: null,
            prevKeyTime: null,

            startEmailTimer() {
                if (!this.emailStart) {
                    this.emailStart = Date.now();
                    fetch('/api/start-typing-session', {
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        body: JSON.stringify({ type: 'email', time: this.emailStart })
                    });
                }
            },
            startPasswordTimer() {
                if (!this.passwordStart) {
                    this.passwordStart = Date.now();
                    fetch('/api/start-typing-session', {
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        body: JSON.stringify({ type: 'password', time: this.passwordStart })
                    });
                }
            },
            initListeners() {
                this.initContextData();

                setInterval(() => {
                    if (this.mouseData.length < 2) return;

                    const prev = this.mouseData[this.mouseData.length - 2];
                    const curr = this.mouseData[this.mouseData.length - 1];

                    const dx = curr.x - prev.x;
                    const dy = curr.y - prev.y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    const speed = distance * 10;
                    const acceleration = 10 * (speed - this.prevSpeed);
                    this.prevSpeed = speed;

                    @this.mouseMetrics.speed = speed;
                    @this.mouseMetrics.totalXDistance += Math.abs(dx);
                    @this.mouseMetrics.totalYDistance += Math.abs(dy);
                    @this.mouseMetrics.totalDistance += distance;
                    @this.mouseMetrics.maxSpeed = Math.max(@this.mouseMetrics.maxSpeed, speed);
                    if (acceleration > 0) {
                        @this.mouseMetrics.maxPositiveAcc = Math.max(@this.mouseMetrics.maxPositiveAcc, acceleration);
                    } else {
                        @this.mouseMetrics.maxNegativeAcc = Math.min(@this.mouseMetrics.maxNegativeAcc, acceleration);
                    }
                }, 100);
            },
            initContextData() {
                // Browser/device info
                new Fingerprint2().get((result, components) => {
                    const context = {
                        browserName: navigator.appName,
                        browserVersion: navigator.appVersion,
                        userAgent: navigator.userAgent,
                        colorDepth: screen.colorDepth,
                        resolution: `${screen.width}x${screen.height}`,
                        canvasFingerprint: result,
                        os: navigator.platform,
                        cpuClass: navigator.hardwareConcurrency || 'Unknown',
                    };
                    @this.set('contextData', context);
                });

                // Location info
                fetch('https://ipapi.co/json/')
                    .then(res => res.json())
                    .then(data => {
                        @this.set('locationData', {
                            ip: data.ip,
                            country_name: data.country_name,
                            country_code: data.country,
                            region: data.region,
                            city: data.city
                        });
                    });
            },
            recordMouseMove(e) {
                this.mouseData.push({ x: e.pageX, y: e.pageY, time: Date.now() });
            },
            recordMouseClick(e) {
                if (e.button === 0) @this.leftClickCount++;
                if (e.button === 2) @this.rightClickCount++;
            },
            recordKeyDown(e) {
                const now = Date.now();
                if (e.key === 'Shift') @this.keyboardMetrics.shiftCount++;
                if (e.key === 'CapsLock') @this.keyboardMetrics.capsLockCount++;
                if (this.keyDownTime !== null) {
                    @this.keyboardMetrics.flightDurations.push(now - this.keyDownTime);
                }
                this.keyDownTime = now;
                this.keyPresses.push({ key: e.key, downTime: now });
            },
            recordKeyUp(e) {
                const now = Date.now();
                const keyPress = this.keyPresses.find(k => k.key === e.key);
                if (keyPress) {
                    @this.keyboardMetrics.dwellTimes.push(now - keyPress.downTime);
                }
                if (this.prevKeyTime !== null) {
                    @this.keyboardMetrics.upDownTimes.push(now - this.prevKeyTime);
                }
                this.prevKeyTime = now;
            }
        }
    }
</script>