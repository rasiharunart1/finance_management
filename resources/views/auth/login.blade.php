<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div style="margin-bottom: 1.25rem;">
            <label for="email" style="display: block; font-size: 0.85rem; font-weight: 600; color: #94a3b8; margin-bottom: 0.5rem;">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', 'superadmin@nhfinance.id') }}" required autofocus autocomplete="username" 
                style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.03); color: #fff; font-size: 0.9rem;">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div style="margin-bottom: 1.25rem;">
            <label for="password" style="display: block; font-size: 0.85rem; font-weight: 600; color: #94a3b8; margin-bottom: 0.5rem;">Password</label>
            <input id="password" type="password" name="password" value="password" required autocomplete="current-password"
                style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.03); color: #fff; font-size: 0.9rem;">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
            <label for="remember_me" style="display: inline-flex; align-items: center; cursor: pointer;">
                <input id="remember_me" type="checkbox" name="remember" style="border-radius: 4px; background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.2); color: #10b981;">
                <span style="margin-left: 0.5rem; font-size: 0.8rem; color: #94a3b8;">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size: 0.8rem; color: #10b981; text-decoration: none; font-weight: 600;">
                    Lupa Password?
                </a>
            @endif
        </div>

        <button type="submit" style="width: 100%; padding: 0.85rem; border-radius: 12px; background: linear-gradient(135deg, #10b981, #059669); color: #fff; border: none; font-weight: 700; font-size: 0.95rem; cursor: pointer; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.25); transition: all 0.2s;">
            <i class="fa-solid fa-right-to-bracket" style="margin-right: 0.5rem;"></i> Masuk ke Sistem
        </button>
    </form>
</x-guest-layout>
