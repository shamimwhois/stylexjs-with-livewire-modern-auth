---
paths:
  - 'resources/views/**'
---

# Views

## Typography pairing: Teko + Plus Jakarta + IBM Plex Mono only
Typography is a hallmark 2+1 pairing with a 3-family ceiling: Display Teko (500-700), Body Plus Jakarta Sans (400-800), Outlier IBM Plex Mono — the banner's wordmark slash/version/theme indicator/shuffle key + dot ordinals, nav category labels (adminNavLabel), error code/chip, and stats/metrics. FONT_SANS/FONT_HEADING/FONT_JAKARTA sanitized in stylex.js + body rule in resources/css/app.css. Instrument Sans is retired — do not re-add it to vite.config.js (bunny fonts) or any font-family stack. Text stacks never combine all three; the mono outlier dominates the banner and chrome, body text stays Plus Jakarta Sans.
