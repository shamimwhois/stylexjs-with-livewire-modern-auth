<div
    class="@stylex('themeWrap')"
    x-data="{
        open: false,
        active: window.themePreset ? window.themePreset.outlet() : 'terminal',
        catalog: window.themePreset ? window.themePreset.catalogKeys : [],
        pad(n) {
            return (n < 10 ? '0' : '') + n;
        },
        label(key) {
            return window.themePreset ? window.themePreset.label(key) : key;
        },
        ordinal(key) {
            var i = this.catalog.indexOf(key);
            return i >= 0 ? this.pad(i + 1) : '00';
        },
        dotStyle(key) {
            var t = window.themePreset && window.themePreset.themes ? window.themePreset.themes[key] : null;

            if (! t) {
                return '';
            }

            return '--dot:' + t.b + ';--dot-edge:' + t.a + ';--num:' + t.n + ';';
        },
        apply(key) {
            if (window.themePreset) {
                window.themePreset.choose(key);
                this.active = window.themePreset.outlet();
            }
        },
        choose(key) {
            this.apply(key);
            this.open = false;
        },
        step(dir) {
            var i = this.catalog.indexOf(this.active);

            if (i < 0) {
                i = 0;
            }

            var next = (i + dir + this.catalog.length) % this.catalog.length;
            this.apply(this.catalog[next]);
        },
        random() {
            var n = this.catalog.length;

            if (n <= 1) {
                return;
            }

            var i = this.catalog.indexOf(this.active);
            var j = i;

            while (j === i) {
                j = Math.floor(Math.random() * n);
            }

            this.choose(this.catalog[j]);
        },
        onKey(e) {
            if (e.repeat || e.metaKey || e.ctrlKey || e.altKey) {
                return;
            }

            var t = e.target;

            if (t && (t.tagName === 'INPUT' || t.tagName === 'TEXTAREA' || t.tagName === 'SELECT' || t.isContentEditable)) {
                return;
            }

            if (e.key === 't' || e.key === 'T') {
                this.step(1);
            } else if (e.key === 'r' || e.key === 'R') {
                this.random();
            }
        },
    }"
    @keydown.escape.window="open = false"
    @keydown.window="onKey($event)"
>
    <button
        type="button"
        class="@stylex('themeIndicator')"
        @click="open = !open"
        :aria-expanded="open.toString()"
        aria-haspopup="listbox"
        aria-controls="theme-dropdown"
        aria-label="Switch theme"
    >
        <span class="@stylex('tnum')"><span x-text="ordinal(active)"></span> / 21</span>
        <span class="@stylex('themeIndicSep')" aria-hidden="true">—</span>
        <span class="@stylex('themeIndicName')" x-text="label(active)"></span>
        <svg class="@stylex('caretIcon')" viewBox="0 0 12 12" width="10" height="10" aria-hidden="true">
            <path d="M2 4l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
    </button>

    <div
        class="@stylex('themeMenu')"
        id="theme-dropdown"
        role="listbox"
        aria-label="Theme picker"
        x-show="open"
        x-cloak
        x-transition:enter="@stylex('fadeEnter')"
        x-transition:enter-start="@stylex('fadeStart')"
        x-transition:leave
        @click.outside="open = false"
    >
        <div class="@stylex('themeDotGrid')">
            <template x-for="(key, i) in catalog" :key="key">
                <button
                    type="button"
                    class="@stylex('themeDot')"
                    :class="active === key && '{{ cls('themeDotActive') }}'"
                    role="option"
                    :aria-selected="(active === key).toString()"
                    :aria-label="pad(i + 1) + ' · ' + label(key)"
                    :style="dotStyle(key)"
                    @click="choose(key)"
                >
                    <span class="@stylex('themeDotNum')" x-text="pad(i + 1)"></span>
                </button>
            </template>
        </div>
    </div>

    <button
        type="button"
        class="@stylex('themeShuffle')"
        aria-label="Random theme · T cycles · R randomises"
        title="Click → random · T cycles · R randomises"
        @click="random()"
    >
        <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M16 3h5v5"></path>
            <path d="M4 20 21 3"></path>
            <path d="M21 16v5h-5"></path>
            <path d="m15 15 6 6"></path>
            <path d="M4 4l5 5"></path>
        </svg>
        <span class="@stylex('themeShuffleKey')">T</span>
    </button>
</div>