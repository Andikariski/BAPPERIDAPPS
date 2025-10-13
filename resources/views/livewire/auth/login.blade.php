<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();
          // 🔥 Tambahkan event Livewire untuk SweetAlert
        $this->dispatch('success-login', message: 'Berhasil login, ' , name:Auth::user()->name);

        // $this->redirectIntended(default: route('dashboard', absolute: false));
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}; ?>



<div class="card shadow-lg border-0 overflow-hidden" style="width: 100%; max-width: 100%; border-radius: 1rem;">
    <div class="row g-0">
       <div class="col-md-5 d-none d-md-flex p-4 text-white dashboard-page"
    style="background: linear-gradient(135deg, #467cce, #0090c1);">

    <div class="d-flex flex-column align-items-center justify-content-center w-100 text-center">
        <img src="{{ asset('assets/img/pps.png') }}" 
             alt="Logo Papua Selatan"
             class="img-fluid mb-3" 
             style="max-height: 250px; width: auto;">

        <h4 class=" mb-0 fs-6">
            Website Profile Bapperida Provinsi Papua Selatan
        </h4>
    </div>
    </div>
        <div class="col-md-7 p-5">
            <div class="text-center mb-4">
                <h3 class="fw-bold display-6">Masuk</h3>
                <p class="text-muted">Lanjutkan dengan Akun Anda</p>
            </div>

            <!-- Tambahkan wire:submit.prevent -->
            <form wire:submit.prevent="login">
                <div class="mb-3">
                    <label for="email" class="form-label visually-hidden">Alamat Email</label>
                    <input type="email" wire:model="email" class="form-control form-control-lg"
                        placeholder="Alamat Email" required>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label visually-hidden">Kata Sandi</label>
                    <input type="password" wire:model="password" class="form-control form-control-lg"
                        placeholder="Kata Sandi" required>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" wire:model="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">
                            {{ __('Remember me') }}
                        </label>
                    </div>
                </div>

                <!-- Ganti wire:click dengan type="submit" -->
                <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold" data-test="login-button">
                    <span wire:loading.remove wire:target="login">Login</span>
                    <span wire:loading wire:target="login">
                    </span>
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-decoration-none text-muted small">Kembali Ke Homepage</a>
            </div>
        </div>
    </div>
</div>

    