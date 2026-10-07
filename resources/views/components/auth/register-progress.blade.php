<div class="{{ cls('progressWrap') }}">
    <div class="{{ cls('progressStep') }}">
        <div class="{{ cls('progressCol') }}">
            <div
                class="{{ cls('progressCircle') }}"
                :class="step > 1
                    ? '{{ cls('progressDone') }}'
                    : step === 1
                        ? '{{ cls('progressActive') }}'
                        : '{{ cls('progressIdle') }}'"
            >
                <x-lucide-check x-show="step > 1" x-cloak class="{{ cls('progressIcon', 'iconStroke') }}" aria-hidden="true" />
                <span x-show="step <= 1">01</span>
            </div>
            <span class="{{ cls('progressLabel') }}" :class="step >= 1 ? '{{ cls('progressLabelOn') }}' : ''">Profile</span>
        </div>
    </div>

    <div class="{{ cls('progressLine') }}" :class="step > 1 ? '{{ cls('progressLineOn') }}' : ''"></div>

    <div class="{{ cls('progressStep') }}">
        <div class="{{ cls('progressCol') }}">
            <div
                class="{{ cls('progressCircle') }}"
                :class="step > 2
                    ? '{{ cls('progressDone') }}'
                    : step === 2
                        ? '{{ cls('progressActive') }}'
                        : '{{ cls('progressIdle') }}'"
            >
                <x-lucide-check x-show="step > 2" x-cloak class="{{ cls('progressIcon', 'iconStroke') }}" aria-hidden="true" />
                <span x-show="step <= 2">02</span>
            </div>
            <span class="{{ cls('progressLabel') }}" :class="step >= 2 ? '{{ cls('progressLabelOn') }}' : ''">Social & Address</span>
        </div>
    </div>

    <div class="{{ cls('progressLine') }}" :class="step > 2 ? '{{ cls('progressLineOn') }}' : ''"></div>

    <div class="{{ cls('progressStep') }}">
        <div class="{{ cls('progressCol') }}">
            <div
                class="{{ cls('progressCircle') }}"
                :class="step > 3
                    ? '{{ cls('progressDone') }}'
                    : step === 3
                        ? '{{ cls('progressActive') }}'
                        : '{{ cls('progressIdle') }}'"
            >
                <span x-show="step <= 3">03</span>
            </div>
            <span class="{{ cls('progressLabel') }}" :class="step >= 3 ? '{{ cls('progressLabelOn') }}' : ''">Review</span>
        </div>
    </div>
</div>