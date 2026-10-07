<?php

namespace App\Livewire\Pages\Auth;

use App\Services\Auth\MagicLinkService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Passwordless sign in')]
class MagicLink extends Component
{
    public string $email = '';

    public bool $linkSent = false;

    /**
     * Redirect away when the magic link feature is disabled.
     */
    public function mount(): void
    {
        abort_if(! app(MagicLinkService::class)->enabled(), 404);
    }

    /**
     * Email a temporary signed sign-in link to the given address.
     */
    public function sendLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'lowercase', 'email'],
        ]);

        app(MagicLinkService::class)->send($this->email);

        $this->linkSent = true;
    }

    /**
     * Render the component.
     */
    public function render(MagicLinkService $magicLink)
    {
        return view('pages.auth.magic-link', ['ttl' => $magicLink->ttl()]);
    }
}
