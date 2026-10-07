<?php

namespace App\Livewire\Pages\Auth;

use App\Services\Auth\AuthAuditLogger;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

#[Layout('layouts.auth')]
#[Title('Verify email')]
class VerifyEmail extends Component
{
    public bool $noticeModal = false;

    public ?string $noticeType = null;

    public ?string $noticeTitle = null;

    public ?string $noticeMessage = null;

    /**
     * Send a new email verification notification.
     */
    public function resendNotification(): void
    {
        $user = auth()->user();

        if ($user->hasVerifiedEmail()) {
            $this->redirect(route('dashboard', absolute: false), navigate: true);

            return;
        }

        try {
            $user->sendEmailVerificationNotification();
        } catch (TransportExceptionInterface $e) {
            Log::warning('Verification email could not be delivered.', [
                'user_id' => $user->getKey(),
                'error' => $e->getMessage(),
            ]);

            app(AuthAuditLogger::class)->log('verification.send_failed', $user);

            session()->forget('status');

            $this->noticeType = 'send_failed';
            $this->noticeTitle = 'Verification email not sent';
            $this->noticeMessage = __('auth.verify.send_failed');
            $this->noticeModal = true;

            return;
        }

        app(AuthAuditLogger::class)->log('verification.sent', $user);

        session()->flash('status', 'verification-link-sent');
    }

    /**
     * Dismiss the resend failure modal.
     */
    public function dismissNotice(): void
    {
        $this->noticeModal = false;
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('pages.auth.verify-email');
    }
}
