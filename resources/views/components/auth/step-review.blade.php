<div x-show="step === 3" x-cloak x-transition:enter="{{ cls('fadeEnter') }}" x-transition:enter-start="{{ cls('fadeStart') }}" class="{{ cls('stepBody') }}">
    <div class="{{ cls('stepInner') }}">
        {{-- Summary --}}
        <div class="{{ cls('summaryCard') }}">
            <div class="{{ cls('summaryHead') }}">
                <div class="{{ cls('avatar') }}">
                    <span x-text="(name || '?').charAt(0).toUpperCase()"></span>
                </div>
                <div class="{{ cls('summaryId', 'flexCol', 'gap1') }}">
                    <p class="{{ cls('summaryName') }}" x-text="name.trim()"></p>
                    <p class="{{ cls('summaryEmail') }}" x-text="email"></p>
                </div>
            </div>
            <div class="{{ cls('summaryGrid') }}">
                <div>
                    <p class="{{ cls('summaryLabel') }}">PASSWORD STRENGTH</p>
                    <p class="{{ cls('summaryValue') }}" :style="{ color: strengthBarColor }" x-text="strengthLabel || '—'"></p>
                </div>
                <div>
                    <p class="{{ cls('summaryLabel') }}">BREACH STATUS</p>
                    <p class="{{ cls('summaryValue') }}" x-text="breachStatus" :class="breachFailed ? '{{ cls('textWarning') }}' : (passwordPwned ? '{{ cls('textDestructive') }}' : '{{ cls('textSuccess') }}')"></p>
                </div>
            </div>
        </div>

        {{-- Profile --}}
        <div class="{{ cls('summaryCard') }}">
            <div class="{{ cls('summaryHead') }}">
                <x-lucide-user class="{{ cls('iconMd', 'iconStroke', 'textMuted') }}" aria-hidden="true" />
                <div class="{{ cls('summaryId') }}">
                    <p class="{{ cls('summaryLabel') }}">Profile</p>
                </div>
            </div>
            <div class="{{ cls('summaryGrid') }}">
                <div>
                    <p class="{{ cls('summaryLabel') }}">FULL NAME</p>
                    <p class="{{ cls('summaryValue') }}" x-text="name || '—'"></p>
                </div>
                <div>
                    <p class="{{ cls('summaryLabel') }}">USERNAME</p>
                    <p class="{{ cls('summaryValue') }}" x-text="username || '—'"></p>
                </div>
                <div class="{{ cls('formColFull') }}">
                    <p class="{{ cls('summaryLabel') }}">WORK EMAIL</p>
                    <p class="{{ cls('summaryValue') }}" x-text="email || '—'"></p>
                </div>
                <div>
                    <p class="{{ cls('summaryLabel') }}">COUNTRY CODE</p>
                    <p class="{{ cls('summaryValue') }}" x-text="countryCode || '—'"></p>
                </div>
                <div>
                    <p class="{{ cls('summaryLabel') }}">PHONE NUMBER</p>
                    <p class="{{ cls('summaryValue') }}" x-text="phone || '—'"></p>
                </div>
            </div>
        </div>

        {{-- Password --}}
        <div class="{{ cls('summaryCard') }}">
            <div class="{{ cls('summaryHead') }}">
                <x-lucide-key class="{{ cls('iconMd', 'iconStroke', 'textMuted') }}" aria-hidden="true" />
                <div class="{{ cls('summaryId') }}">
                    <p class="{{ cls('summaryLabel') }}">Password</p>
                </div>
            </div>
            <div class="{{ cls('summaryGrid') }}">
                <div>
                    <p class="{{ cls('summaryLabel') }}">PASSWORD</p>
                    <p class="{{ cls('summaryValue') }}" x-text="password ? '•'.repeat(Math.min(password.length, 14)) : '—'"></p>
                </div>
                <div>
                    <p class="{{ cls('summaryLabel') }}">CONFIRMATION</p>
                    <p class="{{ cls('summaryValue') }}" x-text="passwordConfirmation ? (passwordMatches ? 'Matched' : 'Mismatch') : '—'"
                        :class="passwordConfirmation ? (passwordMatches ? '{{ cls('textSuccess') }}' : '{{ cls('textDestructive') }}') : ''"></p>
                </div>
            </div>
        </div>

        {{-- Social links --}}
        <div class="{{ cls('summaryCard') }}">
            <div class="{{ cls('summaryHead') }}">
                <x-lucide-globe class="{{ cls('iconMd', 'iconStroke', 'textMuted') }}" aria-hidden="true" />
                <div class="{{ cls('summaryId') }}">
                    <p class="{{ cls('summaryLabel') }}">Social links</p>
                </div>
            </div>
            <div class="{{ cls('summaryGrid') }}">
                <div>
                    <p class="{{ cls('summaryLabel') }}">FACEBOOK</p>
                    <p class="{{ cls('summaryValue') }}" x-text="facebook || '—'"></p>
                </div>
                <div>
                    <p class="{{ cls('summaryLabel') }}">WHATSAPP</p>
                    <p class="{{ cls('summaryValue') }}" x-text="whatsapp || '—'"></p>
                </div>
                <div>
                    <p class="{{ cls('summaryLabel') }}">TELEGRAM</p>
                    <p class="{{ cls('summaryValue') }}" x-text="telegram || '—'"></p>
                </div>
                <div>
                    <p class="{{ cls('summaryLabel') }}">WEBSITE</p>
                    <p class="{{ cls('summaryValue') }}" x-text="website || '—'"></p>
                </div>
            </div>
        </div>

        {{-- Address --}}
        <div class="{{ cls('summaryCard') }}">
            <div class="{{ cls('summaryHead') }}">
                <x-lucide-map-pin class="{{ cls('iconMd', 'iconStroke', 'textMuted') }}" aria-hidden="true" />
                <div class="{{ cls('summaryId') }}">
                    <p class="{{ cls('summaryLabel') }}">Address</p>
                </div>
            </div>
            <div class="{{ cls('summaryGrid') }}">
                <div class="{{ cls('formColFull') }}">
                    <p class="{{ cls('summaryLabel') }}">STREET ADDRESS</p>
                    <p class="{{ cls('summaryValue') }}" x-text="street_address || '—'"></p>
                </div>
                <div>
                    <p class="{{ cls('summaryLabel') }}">CITY</p>
                    <p class="{{ cls('summaryValue') }}" x-text="city || '—'"></p>
                </div>
                <div>
                    <p class="{{ cls('summaryLabel') }}">STATE</p>
                    <p class="{{ cls('summaryValue') }}" x-text="state || '—'"></p>
                </div>
                <div>
                    <p class="{{ cls('summaryLabel') }}">POSTAL CODE</p>
                    <p class="{{ cls('summaryValue') }}" x-text="postal_code || '—'"></p>
                </div>
                <div>
                    <p class="{{ cls('summaryLabel') }}">COUNTRY</p>
                    <p class="{{ cls('summaryValue') }}" x-text="country || '—'"></p>
                </div>
            </div>
        </div>

        {{-- Security decision --}}
        <div class="{{ cls('decisionCard') }}">
            <div class="{{ cls('decisionHead') }}">
                <div class="{{ cls('decisionBody') }}">
                    <x-lucide-lock class="{{ cls('decisionIcon', 'iconStroke') }}" aria-hidden="true" />
                    <div>
                        <p class="{{ cls('decisionTitle') }}">Security decision</p>
                        <p class="{{ cls('decisionText') }}" x-text="securityDecisionText()"></p>
                    </div>
                </div>
                <x-lucide-check class="{{ cls('decisionIcon', 'iconStroke') }}" aria-hidden="true" />
            </div>
        </div>

        {{-- Terms --}}
        <label class="{{ cls('termsWrap') }}">
            <input type="checkbox" x-model="agreedToTerms" x-ref="termsCheckbox" id="terms" class="{{ cls('termsBox') }}">
            <span class="{{ cls('termsText') }}">
                I agree to the Terms of Service and acknowledge the Privacy Policy.
            </span>
        </label>

        {{-- Submit --}}
        <div class="{{ cls('stepFooterRev') }}">
            <x-auth.button type="button" variant="outline" @click="prevStep()">
                <x-lucide-arrow-left class="{{ cls('iconSm', 'iconStroke') }}" aria-hidden="true" />
                Back
            </x-auth.button>
            <x-auth.button type="submit" x-bind:disabled="!canSubmit" class="{{ cls('wFull', 'btnGradient', 'wAuto') }}">
                <x-lucide-lock x-show="!isSubmitting" class="{{ cls('iconSm', 'iconStroke') }}" aria-hidden="true" />
                <x-lucide-loader-circle x-show="isSubmitting" x-cloak class="{{ cls('iconSm', 'spinner', 'iconStroke') }}" aria-hidden="true" />
                <span x-text="isSubmitting ? 'Creating account…' : 'Create account'"></span>
            </x-auth.button>
        </div>
    </div>
</div>