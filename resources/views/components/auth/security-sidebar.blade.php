<aside class="{{ cls('sidebar') }}">
    <div class="{{ cls('sidebarCard') }}">
        <div class="{{ cls('sidebarHead') }}">
            <h3 class="{{ cls('sidebarTitle') }}">Security Center</h3>
            <x-lucide-shield-check class="accent-fg {{ cls('iconMd', 'iconStroke') }}" aria-hidden="true" />
        </div>

        <div class="{{ cls('sidebarList') }}">
            {{-- Identity --}}
            <div class="{{ cls('sidebarRow') }}">
                <span class="{{ cls('sidebarKey') }}">Identity</span>
                <span class="{{ cls('sidebarVal') }}" x-text="name ? 'Provided' : 'Pending'"></span>
            </div>
            {{-- Length --}}
            <div class="{{ cls('sidebarRow') }}">
                <span class="{{ cls('sidebarKey') }}">Length 8+</span>
                <span :class="checklist.length ? '{{ cls('textSuccess') }}' : '{{ cls('sidebarValDim') }}'">
                    <x-lucide-check x-show="checklist.length" x-cloak class="{{ cls('iconSm', 'iconStroke') }}" aria-hidden="true" />
                    <span x-show="!checklist.length">—</span>
                </span>
            </div>
            {{-- Strength --}}
            <div class="{{ cls('sidebarRow') }}">
                <span class="{{ cls('sidebarKey') }}">Password strength</span>
                <span class="{{ cls('sidebarVal', 'strengthValue') }}" :style="{ color: strengthBarColor }" x-text="strengthLabel || '—'"></span>
            </div>
            {{-- Breach --}}
            <div class="{{ cls('sidebarRow') }}">
                <span class="{{ cls('sidebarKey') }}">Breach check</span>
                <span class="{{ cls('sidebarStatus') }}">
                    <x-lucide-loader-circle x-show="breachChecking" x-cloak class="{{ cls('iconSm', 'spinner', 'textMuted') }}" aria-hidden="true" />
                    <span x-show="breachChecking" x-cloak class="{{ cls('textMuted') }}">Checking…</span>
                    <span x-show="!breachChecking" :class="breachFailed ? '{{ cls('textWarning') }}' : (passwordPwned ? '{{ cls('textDestructive') }}' : '{{ cls('textSuccess') }}')" x-text="breachStatus"></span>
                </span>
            </div>
            {{-- Verification --}}
            <div class="{{ cls('sidebarRow', 'sidebarRowLast') }}">
                <span class="{{ cls('sidebarKey') }}">Verification</span>
                <span class="{{ cls('sidebarVal') }}" :class="passwordConfirmation.length > 0 ? (passwordMatches ? '{{ cls('textSuccess') }}' : '{{ cls('textDestructive') }}') : '{{ cls('sidebarValDim') }}'" x-text="passwordConfirmation.length > 0 ? (passwordMatches ? 'Matched' : 'Mismatch') : 'Pending'"></span>
            </div>
        </div>

        {{-- Strength checklist --}}
        <div x-show="password.length > 0" x-cloak class="{{ cls('checklistBlock') }}">
            <p class="{{ cls('checklistHead') }}">PASSWORD CHECKLIST</p>
            <ul class="{{ cls('spaceY2') }}">
                <template x-for="(ok, key) in checklist" :key="key">
                    <li class="{{ cls('checklistItem') }}" :class="ok ? '{{ cls('textSuccess') }}' : '{{ cls('sidebarValDim') }}'">
                        <x-lucide-check x-show="ok" x-cloak class="{{ cls('iconSm', 'iconStroke') }}" aria-hidden="true" />
                        <x-lucide-minus x-show="!ok" x-cloak class="{{ cls('iconSm', 'iconStroke') }}" aria-hidden="true" />
                        <span x-text="key === 'length' ? 'At least 8 characters' : key === 'uppercase' ? 'Uppercase letter (A–Z)' : key === 'lowercase' ? 'Lowercase letter (a–z)' : key === 'number' ? 'Number (0–9)' : 'Special character'"></span>
                    </li>
                </template>
            </ul>
        </div>

        {{-- Breach mini card --}}
        <div class="{{ cls('miniCard') }}"
             :class="passwordPwned
                 ? '{{ cls('miniCardDanger') }}'
                 : ''">
            <div class="{{ cls('miniCardBody') }}">
                <x-lucide-triangle-alert x-show="passwordPwned" x-cloak x-bind:class="passwordPwned ? '{{ cls('textDestructive') }}' : '{{ cls('textSuccess') }}'" class="{{ cls('miniCardIcon', 'iconStroke') }}" aria-hidden="true" />
                <x-lucide-shield-check x-show="!passwordPwned" x-cloak x-bind:class="passwordPwned ? '{{ cls('textDestructive') }}' : '{{ cls('textSuccess') }}'" class="{{ cls('miniCardIcon', 'iconStroke') }}" aria-hidden="true" />
                <div class="{{ cls('minW0') }}">
                    <p class="{{ cls('miniCardTitle') }}" :class="passwordPwned ? '{{ cls('textDestructive') }}' : '{{ cls('textSuccess') }}'" x-text="breachTitleText"></p>
                    <p class="{{ cls('miniCardText') }}" x-text="breachSubtitleText"></p>
                </div>
                {{-- Password actions --}}
                <div class="{{ cls('flexRow', 'itemsCenter', 'gap1', 'ml2', 'shrink0') }}">
                    <x-auth.generate-password icon-only />
                    
                </div>
            </div>
        </div>
    </div>
</aside>