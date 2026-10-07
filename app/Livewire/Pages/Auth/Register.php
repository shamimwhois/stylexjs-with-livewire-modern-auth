<?php

namespace App\Livewire\Pages\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Models\Address;
use App\Models\Country;
use App\Models\SocialProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Create an account')]
class Register extends Component
{
    public string $name = '';

    public string $username = '';

    public string $email = '';

    public string $country_code = '';

    public string $phone = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $facebook = '';

    public string $whatsapp = '';

    public string $telegram = '';

    public string $website = '';

    public string $street_address = '';

    public string $city = '';

    public string $state = '';

    public string $postal_code = '';

    public string $country = '';

    /**
     * Fields that failed server-side validation, surfaced to the wizard so
     * it can jump back to the step containing the error.
     *
     * @var array<int, string>
     */
    public array $errorFields = [];

    /**
     * Create a newly registered user and sign them in.
     */
    public function register(): void
    {
        try {
            $user = app(CreateNewUser::class)->create([
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'country_code' => $this->country_code,
                'phone' => $this->phone,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
            ]);
        } catch (ValidationException $e) {
            $this->setErrorBag($e->errors());
            $this->errorFields = array_keys($e->errors());

            return;
        }

        $this->errorFields = [];

        $this->storeSocialProfiles($user);
        $this->storeAddress($user);

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('pages.auth.register', [
            'countryCodes' => $this->countryCodes(),
            'countryOptions' => collect($this->countryCodes())
                ->map(fn (string $name, string $code) => ['code' => $code, 'label' => "{$name} ({$code})"])
                ->values()
                ->all(),
        ]);
    }

    /**
     * Country dialing codes shown in the profile step.
     *
     * @return array<string, string>
     */
    private function countryCodes(): array
    {
        return Cache::rememberForever('register.country_codes', function () {
            return Country::query()
                ->orderBy('name')
                ->get()
                ->mapWithKeys(fn (Country $country) => [$country->dial_code => $country->name])
                ->all();
        });
    }

    /**
     * Persist the optional social profile links collected in the social step.
     *
     * @param  User  $user
     */
    private function storeSocialProfiles($user): void
    {
        $networks = [
            'facebook' => $this->facebook,
            'whatsapp' => $this->whatsapp,
            'telegram' => $this->telegram,
            'website' => $this->website,
        ];

        foreach ($networks as $network => $url) {
            if (! $url || blank(trim($url))) {
                continue;
            }

            SocialProfile::query()->create([
                'user_id' => $user->id,
                'input_type' => $network,
                'url' => trim($url),
            ]);
        }
    }

    /**
     * Persist the optional address collected in the social step.
     *
     * @param  User  $user
     */
    private function storeAddress($user): void
    {
        $address = [
            'street' => $this->street_address,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
        ];

        if (! collect($address)->filter()->isNotEmpty()) {
            return;
        }

        Address::query()->create([
            'user_id' => $user->id,
            ...$address,
        ]);
    }
}
