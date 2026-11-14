# Handoff

## 1) Goal
Complete a store rebrand across the codebase from **مناحل وادي الجرفة** (`Wadi Al-Jarfah`) to **متجر رِواء** (`Riwaa Honey`), update the store logo image to match the new brand while preserving colors, UI layout, CSS/Tailwind styling, favicons, and the footer developer credit (`تم التطوير بواسطة KMG | AI` / `https://github.com/alghzwanik9`), verify locally, and prepare/attempt deployment to Railway.

---

## 2) Current State
- **Rebranding & Logo:** Complete across all Blade views, legal pages, emails, PDF invoices, AI prompts, payment descriptions, config/env files, and [public/images/logo.png](public/images/logo.png). Zero remaining occurrences of old branding (`وادي الجرفة`, `aljorfah`, `wadialjarfah`, `0550360118`, `966550360118`, `honey-store.com`) in source/config/view files.
- **Laravel Caches & Local DB:** `php artisan optimize:clear` succeeded after local XAMPP MySQL was started (`config`, `cache`, `compiled`, `events`, `routes`, `views`, `blade-icons`, `filament` cleared). The `settings` table query via `tinker` ran cleanly with `0` rows updated (the local `settings` table is currently empty `[]`).
- **Local Servers:** Running in background for local preview:
  - Laravel dev server: `http://127.0.0.1:8000` (`php artisan serve --host=127.0.0.1 --port=8000`)
  - Vite dev server: `http://localhost:5173/` (`npm run dev`)
- **Railway Deployment (Paused by User):**
  - Railway CLI (`5.74.0`) installed globally and authenticated as `alghzwanyk7@gmail.com` (workspace `alghzwanik9's Projects`, ID `334fb56e-cf74-4424-b3ed-aa6308cffe70`).
  - Existing project **`practical-harmony`** (`014622c1-168a-460c-b189-331c19b273f5`, environment `production` `a45df733-0cea-4a65-8a65-3573a88016c7`) contains:
    - Service `honey-store` (`2d06f219-6b7d-40b3-bb17-511a5db4043a`) — domain `https://honey-store-production.up.railway.app`, 72 environment variables configured, and `honey-store-volume` (`50.69 MB` mounted at `/var/www/storage/app/public`). `APP_NAME` on this service was updated to `Riwaa Honey | متجر رِواء`.
    - Service `MySQL` (`de1e614e-f9dd-4e93-b8f7-09692081ab2c`) — `mysql-volume` (`158.6 MB` mounted at `/var/lib/mysql`), previous deployment `REMOVED`.
  - Deployment is blocked until a paid Railway plan is selected (`Your trial has expired. Please select a plan to continue using Railway.`). User chose to defer deployment until funds are available.

---

## 3) Active Files
- **Config & Environment:**
  - `.env` (gitignored; updated `APP_NAME` and `APP_URL`)
  - `.env.example`
  - `config/app.php`
- **Backend / Services / Auth / Mail / Payments:**
  - `app/Services/AIService.php`
  - `app/Traits/HasZatcaQr.php`
  - `app/Mail/InvoiceMail.php`
  - `app/Mail/OrderInvoice.php`
  - `app/Http/Controllers/TabbyController.php`
  - `app/Http/Controllers/TamaraController.php`
  - `app/Http/Middleware/AdminRedirect.php`
  - `app/Models/User.php`
- **Views & Components:**
  - `resources/views/components/layout.blade.php`
  - `resources/views/components/brand-logo.blade.php`
  - `resources/views/components/chat-widget.blade.php`
  - `resources/views/layouts/app.blade.php`
  - `resources/views/layouts/guest.blade.php`
  - `resources/views/layouts/navigation.blade.php`
  - `resources/views/welcome.blade.php`
  - `resources/views/dashboard.blade.php`
  - `resources/views/checkout/success.blade.php`
  - `resources/views/privacy-policy.blade.php`
  - `resources/views/refund-policy.blade.php`
  - `resources/views/terms.blade.php`
  - `resources/views/emails/orders/invoice.blade.php`
  - `resources/views/invoices/order.blade.php`
- **Assets & Deployment Config (reviewed):**
  - `public/images/logo.png`
  - `Dockerfile`, `start.sh`, `.docker/nginx.conf`, `.dockerignore`, `public/robots.txt`

---

## 4) Changes Made
1. **Store Identity & Titles:**
   - Updated `.env` (`APP_NAME="Riwaa Honey | متجر رِواء"`, `APP_URL=https://riwaa.store`), `.env.example`, and `config/app.php` fallback name.
   - Updated `resources/views/components/layout.blade.php` `<title>` so `الرئيسية` renders `الرئيسية - متجر رِواء للعسل الطبيعي`, other titled views append ` - متجر رِواء`, and default falls back to `متجر رِواء للعسل الطبيعي`.
   - Replaced all `مناحل وادي الجرفة` / `وادي الجرفة` occurrences across headers, navbars, mobile drawer, footers, JSON-LD metadata, `<meta>` tags, `brand-logo.blade.php`, `welcome.blade.php`, `dashboard.blade.php`, `checkout/success.blade.php`, and legal pages (`privacy-policy`, `refund-policy`, `terms`).
2. **About Us & Footer Copy:**
   - Replaced footer bio, copyright (`متجر رِواء`), quick link (`عن متجر رِواء`), and About Modal heading & body text in `resources/views/components/layout.blade.php`.
   - Kept developer credit `تم التطوير بواسطة KMG | AI` and `https://github.com/alghzwanik9` untouched in `layout.blade.php` and `layouts/app.blade.php`.
3. **Contact Info & Social Links:**
   - Replaced `aljorfah.honey@gmail.com` with `info@riwaa.store` across `layout.blade.php`, `privacy-policy.blade.php`, `invoices/order.blade.php`, `AdminRedirect.php`, and `User.php`.
   - Replaced `966550360118` / `0550360118` with `966500000000` / `0500000000` in `layout.blade.php` and `privacy-policy.blade.php`.
   - Replaced `https://tiktok.com/@aljorfahhoney` with `#` in `layout.blade.php`.
   - Replaced `www.honey-store.com` with `riwaa.store` in `invoices/order.blade.php`.
4. **AI Assistant, Invoices, Emails & Payment Gateways:**
   - Updated AI greeting in `resources/views/components/chat-widget.blade.php` and both system prompts in `app/Services/AIService.php`.
   - Updated ZATCA seller name in `app/Traits/HasZatcaQr.php`, email subjects in `app/Mail/InvoiceMail.php` and `app/Mail/OrderInvoice.php` (including fixing old typo `وادي الجربة`), email body in `resources/views/emails/orders/invoice.blade.php`, PDF invoice in `resources/views/invoices/order.blade.php`, and descriptions in `TabbyController.php` and `TamaraController.php`.
   - Verified syntax with `php -l` on all modified PHP files and compiled views with `php artisan view:cache` / `php artisan view:clear`.
5. **Logo Image (`public/images/logo.png`):**
   - Preserved the exact `340x734` transparent PNG canvas, golden hexagonal frame, mountains, hills, clouds, and bees; removed the old `مناحل وادي الجرفة` text pixels and rendered crisp `متجر رِواء` typography in matching metallic gold gradient (`Dubai-Bold` via headless Edge SVG render, 4x supersampled with Lanczos).
   - Verified `favicon*` and `android-chrome*` icons contain only the golden bee emblem with no text.
6. **Railway Preparation:**
   - Installed `@railway/cli` (`5.74.0`), authenticated CLI, and updated `APP_NAME` variable on the `honey-store` service in `practical-harmony` to `Riwaa Honey | متجر رِواء` with `--skip-deploys`.

---

## 5) Failed Attempts
- **Initial `php artisan optimize:clear` and `tinker` `Setting` update:** Failed initially with `SQLSTATE[HY000] [2002] No connection could be made because the target machine actively refused it` while XAMPP MySQL was stopped. Resolved once user started XAMPP (`optimize:clear` and `tinker` query both succeeded afterward).
- **PowerShell quote/BOM issues with `tinker`:** Piping `@'...'@ | php artisan tinker` prepended a UTF-8 BOM causing `Class "\uFEFF\App\Models\Setting" not found`; resolved by running `php artisan tinker --execute=...` with escaped `\"`.
- **Automated Chrome Browser Verification:** `browser` subagent failed to attach to Chrome (`ENOENT` on `DevToolsActivePort` because Chrome remote debugging was not enabled). User verified the site manually in Edge (`http://127.0.0.1:8000`).
- **Provided string in Railway prompt (`z3GRH1mhx8oZRofHSSzwP-u-OBo2212I4s6e7L4XQFK`):** Tested as `RAILWAY_API_TOKEN` via `railway whoami --json` -> returned `Unauthorized`. Authenticated via browser OAuth (`railway login`) instead.
- **Railway Redeploy / Upload:**
  - `railway redeploy` on `MySQL` returned `No deployment found for service` (previous deployment status was `REMOVED`).
  - GraphQL `serviceInstanceRedeploy` on `MySQL` and `railway up` on `honey-store` both failed with: `Your trial has expired. Please select a plan to continue using Railway.`

---

## 6) Next Steps
1. **Commit local rebrand changes to Git** (including `public/images/logo.png` and modified Blade/PHP/config files) when ready.
2. **When ready to deploy on Railway:**
   - Activate a plan (e.g., Hobby \$5/month) in Railway Billing: `https://railway.com/workspace/billing` (project `https://railway.com/project/014622c1-168a-460c-b189-331c19b273f5`).
   - Start/redeploy the `MySQL` service (`de1e614e-f9dd-4e93-b8f7-09692081ab2c`) in `practical-harmony` (`production`) so the `158.6 MB` DB volume is online.
   - Deploy the updated app via `railway up -p 014622c1-168a-460c-b189-331c19b273f5 -e a45df733-0cea-4a65-8a65-3573a88016c7 -s 2d06f219-6b7d-40b3-bb17-511a5db4043a --detach --json` and verify deployment reaches `SUCCESS`.
   - Check/update any rows in production MySQL `settings` table (`REPLACE(value, 'مناحل وادي الجرفة', 'متجر رِواء')`) and clear `ai_product_context` cache on production.
   - Update [public/robots.txt](public/robots.txt#L4) and Railway `APP_URL` if moving from `honey-store-production.up.railway.app` to `https://riwaa.store` (and configure custom domain in Railway).

