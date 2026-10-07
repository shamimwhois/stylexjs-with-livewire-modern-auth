@props([
    'countryCodes' => [],
    'countryOptions' => [],
])

<div x-show="step === 1" x-transition:enter="{{ cls('fadeEnter') }}" x-transition:enter-start="{{ cls('fadeStart') }}" class="{{ cls('stepBody') }}">
    <div class="{{ cls('stepInner') }}">
        <div class="{{ cls('formGrid2') }}">
            <div>
                <label for="name" class="{{ cls('formLabel') }}">FULL NAME</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    x-model="name"
                    x-ref="nameInput"
                    x-on:input="errorBanner = false"
                    autocomplete="name"
                    autofocus
                    required
                    placeholder="Jane Doe"
                    class="{{ cls($errors->has('name') ? ['inputInvalid', 'inputInvalidPad'] : ['input', 'inputPad']) }}"
                >
                @error('name')
                    <p class="{{ cls('errorText') }}">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="username" class="{{ cls('formLabel') }}">USERNAME</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    x-model="username"
                    x-ref="usernameInput"
                    x-on:input="errorBanner = false"
                    autocomplete="username"
                    required
                    placeholder="Username"
                    class="{{ cls($errors->has('username') ? ['inputInvalid', 'inputInvalidPad'] : ['input', 'inputPad']) }}"
                >
                @error('username')
                    <p class="{{ cls('errorText') }}">{{ $message }}</p>
                @enderror
            </div>
            <div class="{{ cls('formColFull') }}">
                <label for="email" class="{{ cls('formLabel') }}">WORK EMAIL</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    x-model="email"
                    x-ref="emailInput"
                    x-on:input="errorBanner = false"
                    x-on:blur="emailBlurred = true"
                    autocomplete="username"
                    required
                    placeholder="you@company.com"
                    class="{{ cls($errors->has('email') ? ['inputInvalid', 'inputInvalidPad'] : ['input', 'inputPad']) }}"
                >
                <p x-show="emailBlurred && emailInvalid" x-cloak class="{{ cls('errorText') }}">Enter a valid email address.</p>
                @error('email')
                    <p class="{{ cls('errorText') }}">{{ $message }}</p>
                @enderror
            </div>
            <div class="{{ cls('formColFull', 'phoneRow', 'itemsStart') }}">
                <!-- country code -->
                <div class="{{ cls('phoneCode') }}">
                    <label for="country_code" class="{{ cls('formLabel') }}">Country Code</label>
                    <div class="{{ cls('fieldWrap') }}" x-on:click.outside="countryOpen = false">
                        <input
                            id="country_code"
                            name="country_code"
                            type="text"
                            x-model="countryQuery"
                            x-ref="countryCodeInput"
                            x-on:focus="countryOpen = true"
                            x-on:input="countryOpen = true; errorBanner = false"
                            x-on:keydown.escape.window="countryOpen = false"
                            autocomplete="off"
                            placeholder="Search country code"
                            class="{{ cls($errors->has('country_code') ? ['inputInvalid', 'inputInvalidPad'] : ['input', 'inputPad'], 'inputPadPr') }}"
                        >
                        <span class="{{ cls('phoneCodeChevron') }}" aria-hidden="true">
                            <x-lucide-chevron-down class="{{ cls('iconSm', 'iconStroke') }}" />
                        </span>
                        <ul
                            x-ref="countryMenu"
                            x-show="countryOpen"
                            x-cloak
                            x-transition:enter="{{ cls('fadeEnter') }}"
                            x-transition:enter-start="{{ cls('fadeStart') }}"
                            class="{{ cls('phoneCodeMenu') }}"
                        >
                            @foreach ($countryOptions as $option)
                                <li
                                    x-show="countryOptionVisible({{ Js::from($option['label']) }}, {{ Js::from($option['code']) }})"
                                    x-on:click="selectCountryCode({{ Js::from($option['code']) }}, {{ Js::from($option['label']) }})"
                                    x-on:keydown.enter.prevent="selectCountryCode({{ Js::from($option['code']) }}, {{ Js::from($option['label']) }})"
                                    role="option"
                                    tabindex="0"
                                    class="{{ cls('phoneCodeOption') }}"
                                >{{ $option['label'] }}</li>
                            @endforeach
                            <li x-show="countryNoResults" x-cloak class="{{ cls('phoneCodeEmpty') }}">No matching countries found.</li>
                        </ul>
                    </div>
                    @error('country_code')
                        <p class="{{ cls('errorText') }}">{{ $message }}</p>
                    @enderror
                </div>
                <div class="{{ cls('phoneGrow') }}">
                    <label for="phone" class="{{ cls('formLabel') }}">Phone number</label>
                    <x-auth.phone-input
                        x-model="phone"
                        x-ref="phoneInput"
                        x-on:input="errorBanner = false"
                        x-on:blur="phoneBlurred = true"
                        required
                        placeholder="(555) 555-5555"
                        class="{{ cls($errors->has('phone') ? ['inputInvalid', 'inputInvalidPad'] : ['input', 'inputPad']) }}"
                    />
                    <p x-show="phoneBlurred && phoneInvalid" x-cloak class="{{ cls('errorText') }}">Enter a valid phone number.</p>
                </div>
            </div>

            <div>
                <label for="password" class="{{ cls('formLabel') }}">PASSWORD</label>
                <div class="{{ cls('fieldWrap') }}">
                    <input
                        type="password"
                        :type="showPassword ? 'text' : 'password'"
                        id="password"
                        name="password"
                        x-model="password"
                        x-ref="passwordInput"
                        x-on:input="errorBanner = false"
                        x-on:keyup="evaluatePassword()"
                        autocomplete="new-password"
                        required
                        placeholder="Minimum 8 characters"
                        class="{{ cls($errors->has('password') ? ['inputInvalid', 'inputInvalidPad'] : ['input', 'inputPad'], 'inputPadPr') }}"
                    >
                    <button type="button" x-on:click="toggleShowPassword('password')" class="{{ cls('iconBtn') }}" aria-label="Toggle password visibility">
                        <x-lucide-eye x-show="!showPassword" class="{{ cls('iconSm', 'iconStroke') }}" aria-hidden="true" />
                        <x-lucide-eye-off x-show="showPassword" x-cloak class="{{ cls('iconSm', 'iconStroke') }}" aria-hidden="true" />
                    </button>
                </div>
                @error('password')
                    <p class="{{ cls('errorText') }}">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="{{ cls('formLabel') }}">CONFIRM PASSWORD</label>
                <div class="{{ cls('fieldWrap') }}">
                    <input
                        type="password"
                        :type="showConfirmPassword ? 'text' : 'password'"
                        id="password_confirmation"
                        name="password_confirmation"
                        x-model="passwordConfirmation"
                        x-ref="confirmInput"
                        x-on:input="errorBanner = false"
                        autocomplete="new-password"
                        required
                        placeholder="Repeat password"
                        class="{{ cls($errors->has('password_confirmation') ? ['inputInvalid', 'inputInvalidPad'] : ['input', 'inputPad'], 'inputPadPr') }}"
                    >
                    
                    <button type="button" x-on:click="toggleShowPassword('password_confirmation')" class="{{ cls('iconBtn') }}" aria-label="Toggle password visibility">
                        <x-lucide-eye x-show="!showConfirmPassword" class="{{ cls('iconSm', 'iconStroke') }}" aria-hidden="true" />
                        <x-lucide-eye-off x-show="showConfirmPassword" x-cloak class="{{ cls('iconSm', 'iconStroke') }}" aria-hidden="true" />
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="{{ cls('errorText') }}">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="{{ cls('flexRow', 'justifyEnd', 'pt2') }}">
            <x-auth.button type="submit" x-bind:disabled="!canContinueProfile">
                Continue
                <x-lucide-arrow-right class="{{ cls('iconSm', 'iconStroke') }}" aria-hidden="true" />
            </x-auth.button>
        </div>
    </div>
</div>