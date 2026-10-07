<script>
    (function () {
        var themes = {
            hum: { h:'#ff3a5d', f1:'#fefcf3', f2:'#edebe5', c1:'#ff3a5d', c2:'#fd6000', a:'#ff3a5d', b:'#f7f5ec', t:'#442324', tm:'#635454', s:'#e4e3dd', n:'#442324' },
            specimen: { h:'#ad9b83', f1:'#fff7eb', f2:'#ede7de', c1:'#ad9b83', c2:'#9da286', a:'#ad9b83', b:'#f8f1e5', t:'#3c2a0e', tm:'#5f574c', s:'#e5dfd6', n:'#3c2a0e' },
            midnight: { h:'#00bae9', f1:'#030810', f2:'#0f1924', c1:'#00bae9', c2:'#67a3ff', a:'#00bae9', b:'#050c14', t:'#c2e5f1', tm:'#7a898f', s:'#0d1721', n:'#c2e5f1' },
            brutal: { h:'#e23532', f1:'#ffffff', f2:'#efeeee', c1:'#e23532', c2:'#ce5b00', a:'#e23532', b:'#f9f8f8', t:'#442320', tm:'#635452', s:'#e7e6e6', n:'#442320' },
            garden: { h:'#2c6b1c', f1:'#fcf7e7', f2:'#eae6d9', c1:'#2c6b1c', c2:'#00715e', a:'#2c6b1c', b:'#f5f0e0', t:'#20341b', tm:'#525b50', s:'#e2ded1', n:'#20341b' },
            atelier: { h:'#873e00', f1:'#f4f1ee', f2:'#e2e1e0', c1:'#873e00', c2:'#675500', a:'#873e00', b:'#eeeae8', t:'#402712', tm:'#61564d', s:'#dededd', n:'#402712' },
            newsprint: { h:'#5b1713', f1:'#ffe3d1', f2:'#f2d4c3', c1:'#5b1713', c2:'#522500', a:'#5b1713', b:'#ffddca', t:'#44241f', tm:'#635452', s:'#f6d7c6', n:'#44241f' },
            terminal: { h:'#75d350', f1:'#010301', f2:'#091209', c1:'#75d350', c2:'#00ddb7', a:'#75d350', b:'#020602', t:'#cee6c6', tm:'#80897e', s:'#081108', n:'#cee6c6' },
            manifesto: { h:'#e62c2c', f1:'#020201', f2:'#100c09', c1:'#e62c2c', c2:'#d15800', a:'#e62c2c', b:'#040302', t:'#f8d5d0', tm:'#928280', s:'#0f0b08', n:'#f8d5d0' },
            almanac: { h:'#004286', f1:'#edf2f7', f2:'#dfe1e4', c1:'#004286', c2:'#442f83', a:'#004286', b:'#e7ecf0', t:'#192f46', tm:'#505963', s:'#dcdee0', n:'#192f46' },
            sport: { h:'#d23f18', f1:'#feffff', f2:'#eeeeef', c1:'#d23f18', c2:'#b96200', a:'#d23f18', b:'#f7f9fa', t:'#43241c', tm:'#635451', s:'#e6e6e7', n:'#43241c' },
            studio: { h:'#0a6b1d', f1:'#fdfaf0', f2:'#ebeae2', c1:'#0a6b1d', c2:'#006f62', a:'#0a6b1d', b:'#f6f4e9', t:'#1d341e', tm:'#515b51', s:'#e3e1da', n:'#1d341e' },
            riso: { h:'#008dbf', f1:'#ffe3de', f2:'#ebd1cc', c1:'#008dbf', c2:'#3876dd', a:'#008dbf', b:'#f7dad4', t:'#07333f', tm:'#4c5b60', s:'#f1d7d2', n:'#07333f' },
            bloom: { h:'#b3543c', f1:'#fffbf5', f2:'#eeebe7', c1:'#b3543c', c2:'#a06700', a:'#b3543c', b:'#f9f4ee', t:'#43241c', tm:'#635451', s:'#e5e2df', n:'#43241c' },
            coral: { h:'#de5d50', f1:'#fdf9f7', f2:'#eae9e9', c1:'#de5d50', c2:'#cb7400', a:'#de5d50', b:'#f6f2f0', t:'#44241f', tm:'#635452', s:'#e2e1e1', n:'#44241f' },
            aurora: { h:'#00c2ce', f1:'#000404', f2:'#001314', c1:'#00c2ce', c2:'#00afff', a:'#00c2ce', b:'#000607', t:'#b8e9eb', tm:'#798a8b', s:'#001213', n:'#b8e9eb' },
            editorial: { h:'#cd5537', f1:'#faf0e3', f2:'#e8e0d6', c1:'#cd5537', c2:'#b66e00', a:'#cd5537', b:'#f3eadd', t:'#43241c', tm:'#635451', s:'#e4ddd3', n:'#43241c' },
            carnival: { h:'#7f2021', f1:'#ffe8b2', f2:'#ead9b6', c1:'#7f2021', c2:'#753300', a:'#7f2021', b:'#fce2ac', t:'#442321', tm:'#635452', s:'#eddcb9', n:'#442321' },
            lumen: { h:'#ed9c55', f1:'#05070e', f2:'#141822', c1:'#ed9c55', c2:'#c4b345', a:'#ed9c55', b:'#080b12', t:'#f8d7be', tm:'#90847a', s:'#12161f', n:'#f8d7be' },
            cobalt: { h:'#0076ed', f1:'#fdffff', f2:'#eff0f1', c1:'#0076ed', c2:'#8857e0', a:'#0076ed', b:'#f8fafd', t:'#1d2f47', tm:'#515963', s:'#e7e8e8', n:'#1d2f47' },
            grid: { h:'#d01e1c', f1:'#feffff', f2:'#f1f2f2', c1:'#d01e1c', c2:'#bc4c00', a:'#d01e1c', b:'#fafcfe', t:'#47211c', tm:'#635452', s:'#e9e9ea', n:'#47211c' },
        };

        var catalogKeys = ['hum', 'specimen', 'midnight', 'brutal', 'garden', 'atelier', 'newsprint',
            'terminal', 'manifesto', 'almanac', 'sport', 'studio', 'riso', 'bloom', 'coral', 'aurora',
            'editorial', 'carnival', 'lumen', 'cobalt', 'grid'];

        var names = {
            hum: 'Hum', specimen: 'Specimen', midnight: 'Midnight', brutal: 'Brutal', garden: 'Garden',
            atelier: 'Atelier', newsprint: 'Newsprint', terminal: 'Terminal', manifesto: 'Manifesto',
            almanac: 'Almanac', sport: 'Sport', studio: 'Studio', riso: 'Riso', bloom: 'Bloom',
            coral: 'Coral', aurora: 'Aurora', editorial: 'Editorial', carnival: 'Carnival',
            lumen: 'Lumen', cobalt: 'Cobalt', grid: 'Grid',
        };

        var storage = (function () {
            try {
                var key = '__gwl_probe__';
                localStorage.setItem(key, '1');
                localStorage.removeItem(key);
                return localStorage;
            } catch (e) {
                return null;
            }
        })();

        function get(name) { return storage ? storage.getItem(name) : null; }
        function set(name, value) { if (storage) storage.setItem(name, value); }
        function remove(name) { if (storage) storage.removeItem(name); }

        function hx(h) {
            var r = 0, g = 0, b = 0;
            if (h.length === 4) { r = parseInt(h[1]+h[1],16); g = parseInt(h[2]+h[2],16); b = parseInt(h[3]+h[3],16); }
            else if (h.length === 7) { r = parseInt(h.slice(1,3),16); g = parseInt(h.slice(3,5),16); b = parseInt(h.slice(5,7),16); }
            return r+','+g+','+b;
        }

        function apply(p) {
            var s = document.documentElement.style;
            s.setProperty('--pf-header', p.h);
            s.setProperty('--pf-f1', p.f1);
            s.setProperty('--pf-f2', p.f2);
            s.setProperty('--pf-c1', p.c1);
            s.setProperty('--pf-c2', p.c2);
            s.setProperty('--pf-accent', p.a);
            s.setProperty('--pf-bg', p.b);
            s.setProperty('--pf-text', p.t);
            s.setProperty('--pf-text-muted', p.tm);
            s.setProperty('--pf-surface', p.s);
            s.setProperty('--pf-c1-rgb', hx(p.c1));
            s.setProperty('--pf-c2-rgb', hx(p.c2));
            s.setProperty('--pf-accent-rgb', hx(p.a));
            s.setProperty('--pf-header-rgb', hx(p.h));
        }

        function current() {
            var pref = get('prefTheme');
            return pref && themes[pref] ? pref : 'terminal';
        }

        function outlet() {
            return current();
        }

        function pad(n) {
            return (n < 10 ? '0' : '') + n;
        }

        function ordinal(key) {
            var i = catalogKeys.indexOf(key);
            return i < 0 ? '00' : pad(i + 1);
        }

        function label(key) {
            return names[key] || key;
        }

        function sync() {
            var key = current();

            document.documentElement.classList.add('dark');
            document.documentElement.setAttribute('data-theme', key);
            document.documentElement.setAttribute('data-pref', key);
            apply(themes[key]);
        }

        function choose(key) {
            if (themes[key]) {
                set('prefTheme', key);
            }
            sync();
        }

        window.themePreset = {
            themes: themes,
            catalogKeys: catalogKeys,
            current: current,
            outlet: outlet,
            choose: choose,
            ordinal: ordinal,
            label: label,
            pad: pad,
        };

        try {
            sync();
        } catch (e) {}
    })();
</script>