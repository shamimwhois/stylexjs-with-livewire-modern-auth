<?php

namespace App\Livewire\Pages\Auth;

use App\Actions\Fortify\PasswordValidationRules;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Reset password')]
class ResetPassword extends Component
{
    use PasswordValidationRules;

    public string $token = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Mount the component with the password reset token.
     */
    public function mount(string $token): void
    {
        $this->token = $token;
    }

    /**
     * Reset the user's forgotten password.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => $this->passwordRules(),
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user, $password) {
                app(ResetUserPassword::class)->reset($user, [
                    'password' => $password,
                    'password_confirmation' => $password,
                ]);
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        session()->flash('status', __($status));

        $this->redirect(route('login', absolute: false), navigate: true);
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('pages.auth.reset-password');
    }
}
