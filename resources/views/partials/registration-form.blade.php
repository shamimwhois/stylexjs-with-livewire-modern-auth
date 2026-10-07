<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('registrationForm', () => ({
            step: 1,
            name: '',
            username: '',
            email: '',
            countryCode: '',
            phone: '',
            password: '',
            passwordConfirmation: '',
            showPassword: false,
            showConfirmPassword: false,
            copied: false,
            phoneBlurred: false,
            countryQuery: '',
            countryOpen: false,
            countryNoResults: false,

            facebook: '',
            whatsapp: '',
            telegram: '',
            website: '',
            street_address: '',
            city: '',
            state: '',
            postal_code: '',
            country: '',

            passwordScore: 0,
            strengthLabel: '',
            strengthBarColor: '#e2e8f0',
            strengthBarWidth: 0,
            checklist: { length: false, uppercase: false, lowercase: false, number: false, special: false },

            breachChecking: false,
            breachFailed: false,
            breach: null,
            _breachTimer: null,

            agreedToTerms: false,
            isSubmitting: false,
            errorBanner: false,
            emailBlurred: false,

            get passwordMatches() {
                return this.password !== '' && this.password === this.passwordConfirmation;
            },

            get emailValid() {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email);
            },

            get emailInvalid() {
                return this.email !== '' && !this.emailValid;
            },

            get phoneValid() {
                return /^[0-9()+\-\s.]{7,20}$/.test(this.phone.trim());
            },

            get phoneInvalid() {
                return this.phoneBlurred && this.phone.trim() !== '' && !this.phoneValid;
            },

            get canContinueProfile() {
                return this.name.trim().length > 0
                    && this.username.trim().length > 0
                    && this.emailValid
                    && (this.phone.trim() === '' || this.phoneValid)
                    && this.password.length >= 8
                    && this.passwordMatches;
            },

            get canContinueSocial() {
                return true;
            },

            get canSubmit() {
                return this.canContinueProfile && this.agreedToTerms && !this.isSubmitting;
            },

            get passwordPwned() {
                return this.breach !== null && this.breach.pwned === true && !this.breachFailed;
            },

            get breachStatus() {
                if (this.breachChecking) return 'Checking…';
                if (this.breachFailed) return 'Check failed';
                if (!this.breach) return 'Not checked';
                return this.passwordPwned ? this.breach.label || 'Compromised' : 'Clear';
            },

            get breachTitleText() {
                if (this.breachFailed) return 'Check unavailable';
                if (this.passwordPwned) return 'Password exposed';
                return 'No breach found';
            },

            get breachSubtitleText() {
                if (this.breachFailed) return 'Could not reach the breach database.';
                if (this.passwordPwned) {
                    return `Appeared ${Number(this.breach.count || 0).toLocaleString()} times in known breaches.`;
                }
                return 'Your password appears safe to use.';
            },

            securityDecisionText() {
                if (!this.password) return 'Complete your security details to continue.';
                if (this.breachChecking) return 'Running breach check…';
                if (this.breachFailed) return 'Breach check unavailable — proceed at your own risk.';
                if (this.passwordPwned) return 'This password was exposed in a known breach.';
                return 'Your password passed all security checks.';
            },

            async evaluatePassword() {
                this.passwordScore = 0;
                this.checklist = { length: false, uppercase: false, lowercase: false, number: false, special: false };

                if (this.password.length >= 8) {
                    this.checklist.length = true;
                    this.passwordScore += 1;
                }
                if (/[A-Z]/.test(this.password)) { this.checklist.uppercase = true; this.passwordScore += 1; }
                if (/[a-z]/.test(this.password)) { this.checklist.lowercase = true; this.passwordScore += 1; }
                if (/[0-9]/.test(this.password)) { this.checklist.number = true; this.passwordScore += 1; }
                if (/[^A-Za-z0-9]/.test(this.password)) { this.checklist.special = true; this.passwordScore += 1; }

                const labels = ['Very weak', 'Weak', 'Fair', 'Good', 'Strong', 'Excellent'];
                const colors = ['#dc2626', '#ea580c', '#d97706', '#65a30d', '#16a34a', '#15803d'];
                const widths = [20, 40, 60, 80, 100, 100];

                this.strengthLabel = labels[this.passwordScore];
                this.strengthBarColor = colors[this.passwordScore];
                this.strengthBarWidth = widths[this.passwordScore];

                if (this.password.length > 0) {
                    clearTimeout(this._breachTimer);
                    this._breachTimer = setTimeout(() => this.checkBreach(), 600);
                }
            },

            async checkBreach() {
                if (this.password.trim() === '') return;
                this.breachChecking = true;
                this.breachFailed = false;
                try {
                    const response = await fetch('/api/v1/auth/check-breach', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                        },
                        body: JSON.stringify({ password: this.password }),
                    });
                    if (!response.ok) throw new Error('network');
                    const data = await response.json();
                    this.breach = data;
                } catch (e) {
                    this.breachFailed = true;
                } finally {
                    this.breachChecking = false;
                }
            },

            generatePassword() {
                const chars = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$%^&*()_+';
                let generated = '';
                for (let i = 0; i < 16; i++) {
                    generated += chars[Math.floor(Math.random() * chars.length)];
                }
                this.password = generated;
                this.passwordConfirmation = generated;
                this.showPassword = true;
                this.showConfirmPassword = true;
                this.copied = false;
                this.evaluatePassword();
            },

            async copyPassword() {
                const value = this.password;
                if (!value) return;

                let ok = false;

                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(value);
                        ok = true;
                    }
                } catch (e) {
                    ok = false;
                }

                if (!ok) {
                    const el = document.createElement('textarea');
                    el.value = value;
                    el.setAttribute('readonly', '');
                    el.style.position = 'absolute';
                    el.style.left = '-9999px';
                    document.body.appendChild(el);
                    el.select();
                    try {
                        ok = document.execCommand('copy');
                    } catch (e) {
                        ok = false;
                    }
                    document.body.removeChild(el);
                }

                if (ok) {
                    this.copied = true;
                    setTimeout(() => { this.copied = false; }, 1600);
                }
            },

            toggleShowPassword(which) {
                if (which === 'password') this.showPassword = !this.showPassword;
                else this.showConfirmPassword = !this.showConfirmPassword;
            },

            init() {
                this.$watch('countryQuery', () => this.$nextTick(() => this.refreshCountryResults()));
                this.$nextTick(() => this.refreshCountryResults());
            },

            countryOptionVisible(label, code) {
                const query = this.countryQuery.trim().toLowerCase().replace(/\s/g, '');
                if (query === '') return true;
                return `${label} ${code}`.toLowerCase().replace(/\s/g, '').includes(query);
            },

            selectCountryCode(code, label) {
                this.countryCode = code;
                this.countryQuery = label;
                this.countryOpen = false;
                this.errorBanner = false;
            },

            refreshCountryResults() {
                const list = this.$refs.countryMenu;
                if (!list) return;
                let visible = 0;
                list.querySelectorAll('li[role="option"]').forEach((el) => {
                    if (el.style.display !== 'none') visible += 1;
                });
                this.countryNoResults = visible === 0;
            },

            nextStep() {
                if (this.step >= 3) return;
                this.step += 1;
                this.errorBanner = false;
                this.$nextTick(() => {
                    const ref = this.step === 2 ? 'facebookInput' : 'termsCheckbox';
                    this.$refs[ref]?.focus();
                });
            },

            prevStep() {
                if (this.step <= 1) return;
                this.step -= 1;
                this.errorBanner = false;
                this.$nextTick(() => {
                    const ref = this.step === 2 ? 'confirmInput' : 'emailInput';
                    this.$refs[ref]?.focus();
                });
            },

            async handleStepSubmit() {
                if (this.step === 1) {
                    if (!this.canContinueProfile) {
                        this.errorBanner = true;
                        this.$nextTick(() => {
                            if (this.name.trim() === '') this.$refs.nameInput?.focus();
                            else if (this.username.trim() === '') this.$refs.usernameInput?.focus();
                            else if (!this.emailValid) this.$refs.emailInput?.focus();
                            else if (!this.checklist.length) this.$refs.passwordInput?.focus();
                            else if (!this.passwordMatches) this.$refs.confirmInput?.focus();
                        });
                        return;
                    }
                    this.nextStep();
                    return;
                }

                if (this.step === 2) {
                    this.nextStep();
                    return;
                }

                await this.submitRegistration();
            },

            async submitRegistration() {
                if (!this.canSubmit || !window.Livewire) return;

                this.isSubmitting = true;
                this.errorBanner = false;
                try {
                    const $wire = window.Livewire.first();
                    await $wire.set('name', this.name.trim());
                    await $wire.set('username', this.username.trim());
                    await $wire.set('email', this.email.trim());
                    await $wire.set('phone', this.phone.trim());
                    await $wire.set('password', this.password);
                    await $wire.set('password_confirmation', this.passwordConfirmation);
                    await $wire.set('facebook', this.facebook.trim());
                    await $wire.set('whatsapp', this.whatsapp.trim());
                    await $wire.set('telegram', this.telegram.trim());
                    await $wire.set('website', this.website.trim());
                    await $wire.set('street_address', this.street_address.trim());
                    await $wire.set('city', this.city.trim());
                    await $wire.set('state', this.state.trim());
                    await $wire.set('postal_code', this.postal_code.trim());
                    await $wire.set('country', this.country.trim());
                    await $wire.set('country_code', this.countryCode);
                    await $wire.register();
                    this.applyServerErrors($wire.get('errorFields') || []);
                } finally {
                    this.isSubmitting = false;
                }
            },

            applyServerErrors(errors) {
                if (!errors.length) return;
                this.errorBanner = true;
                if (errors.includes('password') || errors.includes('password_confirmation')) {
                    this.step = 1;
                    this.$nextTick(() => this.$refs.passwordInput?.focus());
                } else if (
                    errors.includes('name')
                    || errors.includes('username')
                    || errors.includes('email')
                    || errors.includes('phone')
                ) {
                    this.step = 1;
                    const focusRef = errors.includes('username')
                        ? 'usernameInput'
                        : errors.includes('phone')
                            ? 'phoneInput'
                            : errors.includes('email')
                                ? 'emailInput'
                                : 'nameInput';
                    this.$nextTick(() => this.$refs[focusRef]?.focus());
                } else if (
                    errors.includes('facebook')
                    || errors.includes('whatsapp')
                    || errors.includes('telegram')
                    || errors.includes('website')
                    || errors.includes('street_address')
                    || errors.includes('city')
                    || errors.includes('state')
                    || errors.includes('postal_code')
                    || errors.includes('country')
                ) {
                    this.step = 2;
                }
            },
        }));
    });
</script>