# Settings Logo And Landing Page Design

## Goal

Allow admins to manage store branding and editable landing page content from the existing admin settings page. The landing page must keep working with sensible defaults when settings are empty.

## Scope

- Add store logo management for storefront navbar, footer, and admin sidebar branding.
- Add editable landing page fields for hero badge, hero headline, highlighted headline text, hero description, primary and secondary button labels, hero image, bottom CTA headline, bottom CTA description, and CTA button labels.
- Add featured product mode:
  - `default`: automatically show active products by current ranking logic.
  - `manual`: show admin-selected active products, capped at 4 items.
- Keep owner access read-only.

## Architecture

The feature extends the existing `app_settings` key-value storage. Text settings are stored directly as strings. Uploaded images are stored on the `public` disk under `settings`, and their public URLs are saved as setting values.

No new database table is required. Manual featured product selection is saved as a comma-separated list of product IDs because the current settings model only stores string values.

## Components

- `AdminController::settings()` loads existing store settings, new landing settings, and active products for the manual selector.
- `AdminController::saveSettings()` validates text inputs, image uploads, featured product mode, and selected product IDs.
- `StorefrontController::landing()` reads landing settings and resolves featured products based on the selected mode.
- Shared layout components read branding settings so the logo and store name appear consistently.
- `resources/views/pages/admin/settings.blade.php` gains grouped sections for branding, landing page content, and featured products.

## Data Flow

1. Admin opens `/admin/pengaturan`.
2. Controller loads current settings and active products.
3. Admin submits the form with optional uploaded logo or hero image.
4. Controller validates and persists settings.
5. Storefront landing page reads settings and renders either automatic featured products or selected manual products.

## Error Handling

- Uploaded logo and hero images must be valid images and limited to 2 MB.
- Manual featured products must reference existing active products.
- If manual mode has no valid selected products, the landing page falls back to the default product query.
- If an uploaded image is replaced and the previous image was stored locally, the old file is deleted.
- Existing defaults remain available when a setting has not been saved.

## Testing

Feature tests will cover:

- Admin can save logo, landing text, hero image, featured product mode, and manual product IDs.
- Landing page renders customized hero content and uses the uploaded hero image.
- Default featured product mode still returns automatic products.
- Manual featured product mode renders selected products.
- Manual mode falls back to default when no valid products are selected.
