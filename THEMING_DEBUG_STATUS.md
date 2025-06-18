# DaisyUI/Tailwind Theming Debug Status

## Current Situation
- The plugin uses Tailwind CSS v4 and DaisyUI v5, built via PostCSS with the new CSS Layer API syntax as per official docs.
- The admin CSS is loaded and present in the browser.
- The DaisyUI theme is set to `dim` by default using the new `@plugin` block in the CSS.
- The `important: '#wpbody-content'` option is set in `tailwind.config.js` to increase specificity and override WordPress admin styles.
- The admin UI (e.g., Add New Bureau page) shows some elements styled by DaisyUI, but many elements still appear with default WordPress admin styles.
- Changing the theme (e.g., from `light` to `dim`) does not fully update the look of the page as expected.

## What We've Tried
- **PostCSS-based build** with all official DaisyUI/Tailwind v4+ recommendations.
- **DaisyUI theme set via CSS** using the new `@plugin` block.
- **Removed all classic Tailwind directives** from the input CSS, using only the new Layer API.
- **Ensured `data-theme` is not being forced** on the `<html>` element (so DaisyUI can control it).
- **Set `important: '#wpbody-content'`** in Tailwind config to increase specificity of all utility classes.
- **Rebuilt CSS after every config change.**
- **Confirmed CSS is loaded and present in the browser.**
- **Checked that DaisyUI classes are present in the markup.**

## Observations
- Some elements (e.g., cards, buttons) use DaisyUI styles, but many form fields, labels, and layout containers still look like default WordPress admin.
- Theming (e.g., switching from `light` to `dim`) does not affect the whole page—backgrounds, text, and some components remain unchanged.
- The main admin wrapper is `#wpbody-content`, and all plugin UI is inside this container.
- WordPress admin CSS is still overriding or leaking into some elements.

## Things Left to Try / Next Steps
1. **Audit all markup:**
   - Ensure every container, form, and component uses DaisyUI/Tailwind classes for background, text, and layout (e.g., `bg-base-100`, `text-base-content`, `card`, etc.).
   - Remove any hardcoded colors or default HTML elements without classes.
2. **Check for deeply nested WordPress wrappers:**
   - Some WordPress admin wrappers may have higher specificity or reset styles. Consider wrapping your entire plugin UI in a `div` with a unique ID/class and using that as the `important` selector.
3. **Inspect with DevTools:**
   - Use browser DevTools to see which CSS rules are being applied/overridden for problematic elements.
   - Look for WordPress styles with higher specificity or `!important`.
4. **Try a more aggressive important selector:**
   - Instead of `#wpbody-content`, try a more specific selector or add a custom wrapper (e.g., `#bhm-admin-root`).
5. **Test with a minimal admin page:**
   - Create a test admin page with only DaisyUI/Tailwind classes and no WordPress markup to confirm theming works in isolation.
6. **Consider using Shadow DOM or iframe:**
   - For complete style isolation, consider rendering your UI in a Shadow DOM or iframe (advanced, but guarantees no WP CSS leakage).
7. **Review DaisyUI/Tailwind GitHub issues:**
   - Check for any open issues or advice about using DaisyUI in WordPress admin contexts.

## Summary
- The build and config are correct per official docs, but WordPress admin CSS is still leaking through.
- The next step is to ensure all markup uses DaisyUI/Tailwind classes and to further increase specificity or isolation if needed.
- This document should be updated as new debugging steps are tried or as the situation changes. 