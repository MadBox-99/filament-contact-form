{{-- Plain CSS shipped with the plugin. No external CDN. Every rule is
     prefixed with `.fcf-scope` so the styles never affect the host page.

     Customisable tokens are consumed via `var(--fcf-*, fallback)`. The
     fallbacks match the shipped defaults, so a form with no design_tokens
     set renders identically to a form before tokens existed. --}}
<style>
    .fcf-scope { box-sizing: border-box; color: #111827; font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; line-height: 1.5; width: 100%; max-width: var(--fcf-max-width, 42rem); margin: 0 auto; -webkit-font-smoothing: antialiased; }
    .fcf-scope *, .fcf-scope *::before, .fcf-scope *::after { box-sizing: inherit; }

    .fcf-scope .fcf-card { background: #ffffff; border: var(--fcf-card-border, 0 solid transparent); border-radius: var(--fcf-radius, 0.5rem); box-shadow: var(--fcf-card-shadow, none); padding: 2rem 1rem; }
    .fcf-scope .fcf-card--success { text-align: center; padding: 2.5rem 1rem; }

    .fcf-scope .fcf-header { margin-bottom: 1.25rem; }
    .fcf-scope .fcf-title { font-size: 1.25rem; font-weight: 700; line-height: 1.3; letter-spacing: -0.01em; margin: 0 0 0.5rem; color: #111827; }
    .fcf-scope .fcf-description { margin: 0; color: #6b7280; font-size: 0.875rem; line-height: 1.5; }

    .fcf-scope .fcf-form { display: grid; grid-template-columns: 1fr; gap: 1.25rem; }
    .fcf-scope .fcf-field { display: flex; flex-direction: column; grid-column: 1 / -1; }
    .fcf-scope .fcf-label { display: block; font-size: 0.875rem; font-weight: 500; line-height: 1.25; color: #111827; margin-bottom: 0.5rem; }
    .fcf-scope .fcf-required { color: #e02424; margin-left: 0.125rem; }

    .fcf-scope .fcf-input { display: block; width: 100%; padding: 0.625rem 0.75rem; font-size: 0.875rem; font-family: inherit; line-height: 1.25; color: #111827; background-color: var(--fcf-input-bg, #f9fafb); border: 1px solid #d1d5db; border-radius: var(--fcf-radius, 0.5rem); appearance: none; transition: border-color 120ms ease, box-shadow 120ms ease, background-color 120ms ease; }
    .fcf-scope .fcf-input::placeholder { color: #9ca3af; }
    .fcf-scope .fcf-input:hover { border-color: #9ca3af; }
    .fcf-scope .fcf-input:focus { outline: none; border-color: var(--fcf-primary, #1c64f2); background-color: #ffffff; box-shadow: 0 0 0 3px var(--fcf-primary-ring, rgba(164, 202, 254, 0.55)); }
    .fcf-scope .fcf-input:disabled { background: #f3f4f6; color: #6b7280; cursor: not-allowed; }

    .fcf-scope .fcf-textarea { min-height: 10rem; resize: vertical; line-height: 1.5; padding: 0.75rem; }
    .fcf-scope .fcf-select { background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 20 20' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 8l4 4 4-4'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1rem 1rem; padding-right: 2.25rem; }

    .fcf-scope .fcf-checkbox-row { display: inline-flex; align-items: flex-start; gap: 0.625rem; cursor: pointer; user-select: none; }
    .fcf-scope .fcf-checkbox { margin-top: 0.125rem; width: 1rem; height: 1rem; accent-color: var(--fcf-primary, #1c64f2); border-radius: 0.25rem; cursor: pointer; }
    .fcf-scope .fcf-checkbox-label { font-size: 0.875rem; line-height: 1.25; color: #111827; }

    .fcf-scope .fcf-radio-group, .fcf-scope .fcf-checkbox-group { display: flex; flex-direction: column; gap: 0.5rem; }
    .fcf-scope .fcf-option { display: inline-flex; align-items: flex-start; gap: 0.625rem; cursor: pointer; user-select: none; }
    .fcf-scope .fcf-radio { margin-top: 0.125rem; width: 1rem; height: 1rem; accent-color: var(--fcf-primary, #1c64f2); cursor: pointer; }
    .fcf-scope .fcf-option-label { font-size: 0.875rem; line-height: 1.25; color: #111827; }

    .fcf-scope .fcf-submit { display: inline-flex; align-items: center; justify-content: center; width: 100%; padding: 0.625rem 1.25rem; font-size: 0.875rem; font-weight: 500; color: #ffffff; background: var(--fcf-primary, #1a56db); border: 1px solid transparent; border-radius: var(--fcf-radius, 0.5rem); cursor: pointer; transition: background 120ms ease, box-shadow 120ms ease; }
    .fcf-scope .fcf-submit:hover { background: var(--fcf-primary-hover, #1e429f); }
    .fcf-scope .fcf-submit:focus { outline: none; box-shadow: 0 0 0 4px var(--fcf-primary-ring, rgba(164, 202, 254, 0.6)); }
    .fcf-scope .fcf-submit:disabled { opacity: 0.7; cursor: not-allowed; background: #76a9fa; }

    .fcf-scope .fcf-error { margin: 0.25rem 0 0; color: #e02424; font-size: 0.8125rem; line-height: 1.25; }
    .fcf-scope .fcf-field.fcf-field--has-error .fcf-input { border-color: #f05252; background-color: #fdf2f2; }
    .fcf-scope .fcf-field.fcf-field--has-error .fcf-input:focus { border-color: #e02424; box-shadow: 0 0 0 3px rgba(248, 180, 180, 0.6); }

    .fcf-scope .fcf-alert { padding: 1rem; border-radius: var(--fcf-radius, 0.5rem); margin-bottom: 1rem; font-size: 0.875rem; line-height: 1.4; }
    .fcf-scope .fcf-alert--error { background: #fdf2f2; color: #9b1c1c; }

    .fcf-scope .fcf-icon-circle { margin: 0 auto 1rem; display: inline-flex; width: 3rem; height: 3rem; align-items: center; justify-content: center; border-radius: 9999px; background: #def7ec; color: #0e9f6e; }
    .fcf-scope .fcf-icon-circle svg { width: 1.5rem; height: 1.5rem; }
    .fcf-scope .fcf-heading { margin: 0 0 0.5rem; font-size: 1.25rem; font-weight: 600; color: #111827; }
    .fcf-scope .fcf-text { margin: 0; color: #6b7280; font-size: 0.875rem; line-height: 1.5; }

    .fcf-scope .fcf-honeypot { position: absolute !important; left: -9999px !important; top: auto !important; width: 1px !important; height: 1px !important; overflow: hidden !important; }

    @media (min-width: 640px) {
        .fcf-scope .fcf-form { grid-template-columns: repeat(2, minmax(0, 1fr)); column-gap: 1.5rem; }
        .fcf-scope .fcf-field--half { grid-column: span 1; }
        .fcf-scope .fcf-submit { width: var(--fcf-submit-width, auto); justify-self: var(--fcf-submit-justify, start); align-self: flex-start; grid-column: 1 / -1; }
    }
</style>
