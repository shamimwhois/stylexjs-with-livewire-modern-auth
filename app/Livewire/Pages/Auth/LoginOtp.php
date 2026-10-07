<?php

namespace App\Livewire\Pages\Auth;

use App\Models\User;
use App\Services\Auth\DeviceService;
use App\Services\Auth\OtpService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Login with code')]
class LoginOtp extends Component
{
    public string $email = '';

    public string $code = '';

    public bool $codeSent = false;

    /**
     * Redirect away when the OTP feature is disabled.
     */
    public function mount(): void
    {
        abort_if(! app(OtpService::class)->enabled(), 404);
    }

    /**
     * Email a one-time login code to the given address.
     */
    public function sendCode(OtpService $otp): void
    {
        $this->validate([
            'email' => ['required', 'string', 'lowercase', 'email'],
        ]);

        $otp->issue($this->email);

        $this->codeSent = true;
        $this->code = '';
    }

    /**
     * Verify the supplied code and sign the user in.
     */
    public function verifyCode(OtpService $otp): void
    {
        $this->validate([
            'email' => ['required', 'string', 'lowercase', 'email'],
            'code' => ['required', 'string'],
        ]);

        $otp->verify($this->email, $this->code);

        $user = User::query()->where('email', $this->email)->firstOrFail();

        Auth::login($user);

        session()->regenerate();

        app(DeviceService::class)->recordLogin($user);

        $this->redirectIntended(default: route($user->homeRouteName(), absolute: false), navigate: true);
    }

    /**
     * Go back to the email step to request a different code.
     */
    public function resetFlow(): void
    {
        $this->codeSent = false;
        $this->code = '';
    }

    /**
     * Render the component.
     */
    public function render(OtpService $otp)
    {
        return view('pages.auth.otp-login', ['ttl' => $otp->ttl()]);
    }
}
