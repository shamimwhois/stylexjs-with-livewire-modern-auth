<div x-show="step === 2" x-cloak x-transition:enter="{{ cls('fadeEnter') }}" x-transition:enter-start="{{ cls('fadeStart') }}" class="{{ cls('stepBody') }}">
    <div class="{{ cls('stepInner') }}">
        <div class="{{ cls('formGrid2') }}">
            <!-- Social profile links -->
            <div>
                <label for="facebook" class="{{ cls('formLabel') }}">FACEBOOK</label>
                <input
                    type="text"
                    id="facebook"
                    name="facebook"
                    x-model="facebook"
                    x-ref="facebookInput"
                    x-on:input="errorBanner = false"
                    autocomplete="facebook"
                    placeholder="https://www.facebook.com/$username"
                    class="{{ cls($errors->has('facebook') ? ['inputInvalid', 'inputInvalidPad'] : ['input', 'inputPad']) }}"
                >
                @error('facebook')
                    <p class="{{ cls('errorText') }}">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="whatsapp" class="{{ cls('formLabel') }}">WHATSAPP</label>
                <input
                    type="text"
                    id="whatsapp"
                    name="whatsapp"
                    x-model="whatsapp"
                    x-ref="whatsappInput"
                    x-on:input="errorBanner = false"
                    autocomplete="whatsapp"
                    placeholder="https://wa.me/$number"
                    class="{{ cls($errors->has('whatsapp') ? ['inputInvalid', 'inputInvalidPad'] : ['input', 'inputPad']) }}"
                >
                @error('whatsapp')
                    <p class="{{ cls('errorText') }}">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="telegram" class="{{ cls('formLabel') }}">TELEGRAM</label>
                <input
                    type="text"
                    id="telegram"
                    name="telegram"
                    x-model="telegram"
                    x-ref="telegramInput"
                    x-on:input="errorBanner = false"
                    autocomplete="telegram"
                    placeholder="https://t.me/$username"
                    class="{{ cls($errors->has('telegram') ? ['inputInvalid', 'inputInvalidPad'] : ['input', 'inputPad']) }}"
                >
                @error('telegram')
                    <p class="{{ cls('errorText') }}">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="website" class="{{ cls('formLabel') }}">WEBSITE</label>
                <input
                    type="text"
                    id="website"
                    name="website"
                    x-model="website"
                    x-ref="websiteInput"
                    x-on:input="errorBanner = false"
                    autocomplete="website"
                    placeholder="https://www.example.com"
                    class="{{ cls($errors->has('website') ? ['inputInvalid', 'inputInvalidPad'] : ['input', 'inputPad']) }}"
                >
                @error('website')
                    <p class="{{ cls('errorText') }}">{{ $message }}</p>
                @enderror
            </div>

            <!--address-->
            <div class="{{ cls('formColFull') }}">
                <label for="street_address" class="{{ cls('formLabel') }}">STREET ADDRESS</label>
                <input
                    type="text"
                    id="street_address"
                    name="street_address"
                    x-model="street_address"
                    x-ref="streetAddressInput"
                    x-on:input="errorBanner = false"
                    autocomplete="street-address"
                    placeholder="123 Main St"
                    class="{{ cls($errors->has('street_address') ? ['inputInvalid', 'inputInvalidPad'] : ['input', 'inputPad']) }}"
                >
                @error('street_address')
                    <p class="{{ cls('errorText') }}">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="city" class="{{ cls('formLabel') }}">CITY</label>
                <input
                    type="text"
                    id="city"
                    name="city"
                    x-model="city"
                    x-ref="cityInput"
                    x-on:input="errorBanner = false"
                    autocomplete="address-level2"
                    placeholder="City"
                    class="{{ cls($errors->has('city') ? ['inputInvalid', 'inputInvalidPad'] : ['input', 'inputPad']) }}"
                >
                @error('city')
                    <p class="{{ cls('errorText') }}">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="state" class="{{ cls('formLabel') }}">STATE</label>
                <input
                    type="text"
                    id="state"
                    name="state"
                    x-model="state"
                    x-ref="stateInput"
                    x-on:input="errorBanner = false"
                    autocomplete="address-level1"
                    placeholder="State"
                    class="{{ cls($errors->has('state') ? ['inputInvalid', 'inputInvalidPad'] : ['input', 'inputPad']) }}"
                >
                @error('state')
                    <p class="{{ cls('errorText') }}">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="postal_code" class="{{ cls('formLabel') }}">POSTAL CODE</label>
                <input
                    type="text"
                    id="postal_code"
                    name="postal_code"
                    x-model="postal_code"
                    x-ref="postalCodeInput"
                    x-on:input="errorBanner = false"
                    autocomplete="postal-code"
                    placeholder="Postal Code"
                    class="{{ cls($errors->has('postal_code') ? ['inputInvalid', 'inputInvalidPad'] : ['input', 'inputPad']) }}"
                >
                @error('postal_code')
                    <p class="{{ cls('errorText') }}">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="country" class="{{ cls('formLabel') }}">COUNTRY</label>
                <input
                    type="text"
                    id="country"
                    name="country"
                    x-model="country"
                    x-ref="countryInput"
                    x-on:input="errorBanner = false"
                    autocomplete="country"
                    placeholder="Country"
                    class="{{ cls($errors->has('country') ? ['inputInvalid', 'inputInvalidPad'] : ['input', 'inputPad']) }}"
                >
                @error('country')
                    <p class="{{ cls('errorText') }}">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="{{ cls('stepFooter') }}">
            <x-auth.button type="button" variant="outline" @click="prevStep()">
                <x-lucide-arrow-left class="{{ cls('iconSm', 'iconStroke') }}" aria-hidden="true" />
                Back
            </x-auth.button>
            <x-auth.button type="submit" x-bind:disabled="!canContinueSocial">
                Continue
                <x-lucide-arrow-right class="{{ cls('iconSm', 'iconStroke') }}" aria-hidden="true" />
            </x-auth.button>
        </div>
    </div>
</div>