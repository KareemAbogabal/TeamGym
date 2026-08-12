# PROJECT STRUCTURE PROMPT

> Give this prompt to any AI. It will distribute files exactly according
> to this project's architecture. The AI must follow every rule below
> without deviation. If a file doesn't exist, create it within scope.

---

## 1. FILE DISTRIBUTION MAP

```
app/
├── Http/
│   ├── Controllers/
│   │   └── {Section}/{SubSection}/Pages/{Name}Controller.php
│   ├── Requesters/
│   │   └── {Section}/{SubSection}/{Name}/{Name}Request.php
│   └── Middleware/
├── Models/
│   └── {Section}/{Name}.php
├── Mail/
│   └── {Name}Mail.php
├── Services/
│   └── {Name}Service.php

resources/views/
├── {Section}/{SubSection}/
│   ├── main.blade.php                          # Layout (if extends pattern)
│   ├── Components/
│   │   ├── nav.blade.php
│   │   ├── sideBar.blade.php
│   │   └── {componentName}.blade.php
│   ├── Pages/
│   │   └── {pageName}.blade.php
│   ├── Sections/
│   │   └── {PageName}/
│   │       ├── section_1.blade.php
│   │       ├── section_2.blade.php
│   │       └── section_N.blade.php
│   └── Abbreviations/
│       └── menu.blade.php

public/
├── css/{section}/{subSection}/
│   ├── public.css                              # Shared styles
│   ├── pages/{pageName}.css                    # Per-page styles
│   └── components/{componentName}.css          # Per-component styles (if needed)
├── js/{section}/{subSection}/
│   ├── public.js                               # Shared scripts
│   ├── main.js                                 # Loaded via layout
│   └── pages/{pageName}.js                     # Per-page scripts

routes/
└── web.php
```

---

## PROJECT REFACTORING MODE

This prompt is intended primarily for reorganizing an EXISTING Laravel project.

Before creating any file, inspect the entire project.

Never assume the project is empty.

Analyze:

- Controllers
- Models
- Services
- Mail
- Middleware
- Routes
- Views
- CSS
- JavaScript
- Components

Reuse existing code whenever possible.

Only create missing files.

Do not regenerate existing implementations.

Do not duplicate functionality.

## 2. NAMING CONVENTIONS

| Type              | Pattern                       | Example                |
|-------------------|-------------------------------|------------------------|
| Controller        | `{Name}Controller`            | `AboutUsController`    |
| Requester         | `{Name}Request`               | `AboutUsRequest`       |
| Model             | `{Name}` (PascalCase)         | `Client`               |
| View (page)       | `{pageName}.blade.php`        | `aboutUs.blade.php`    |
| View (component)  | `{componentName}.blade.php`   | `formLogin.blade.php`  |
| View (section)    | `section_{N}.blade.php`       | `section_1.blade.php`  |
| Route name        | `{kebab-case}`                | `about-us`             |
| CSS file          | `{pageName}.css`              | `aboutUs.css`          |
| JS file           | `{pageName}.js`               | `aboutUs.js`           |

---

## 3. CONTROLLER LAYOUT

Every controller follows this exact template:

```php
<?php

namespace App\Http\Controllers\{Section}\{SubSection}\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requesters\{Section}\{SubSection}\{Name}\{Name}Request;

class {Name}Controller extends Controller
{
    public function index()
    {
        return view('{Section}.{SubSection}.Pages.{pageName}');
    }
}
```

### Rules
- One controller per page. No multi-method controllers except for auth flows.
- Every controller imports a corresponding `{Name}Request` (even if rules are empty).
- `index()` is the only method for standard pages.
- The view path uses dot notation matching the directory structure.

## EXISTING CONTROLLER REFACTORING

When organizing an existing project:

- Never delete controller methods.
- Never replace a controller with a template.
- Preserve all business logic.

For every controller action:

1. Detect validation.
2. Create a dedicated Form Request.
3. Move validation only.
4. Replace Request with the Form Request.

Do not modify:

- Database logic
- Authentication
- Authorization
- Events
- Notifications
- Service calls
- Repository calls

Only move validation.

The application behavior must remain identical.

---

## 4. REQUESTER LAYOUT

Every requester follows this exact template:

```php
<?php

namespace App\Http\Requesters\{Section}\{SubSection}\{Name};

use Illuminate\Foundation\Http\FormRequest;

class {Name}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
```

### Rules
One Request class per controller action that accepts user input.

Examples:
- store()  → StoreClientRequest
- update() → UpdateClientRequest
- login() → LoginRequest
- resetPassword() → ResetPasswordRequest
- verifyCode() → VerifyCodeRequest

When organizing an existing project:

- Inspect every controller.
- For every action that accepts user input, create a dedicated Form Request if one does not already exist.
- Replace the generic Request parameter with the dedicated Form Request.
- Move all validation rules from the controller into that Form Request.
- Preserve all existing business logic.

Existing business logic inside controllers must remain unchanged.

Only move request validation into dedicated Form Request classes.
- Do not remove controller methods.
- Do not replace existing controllers with template controllers.

Only replace:
$request->validate(...)
with
Custom Form Request classes.

## FORM REQUEST DISCOVERY

If validation already exists:

$request->validate(...)

or

Validator::make(...)

move it into a dedicated Form Request.

Do not rewrite validation rules.

Do not optimize validation.

Move them exactly as they are.

---

## 5. VIEW FILE DISTRIBUTION

### Standalone pages (Website/Web)

Each page is a full HTML document. Files go to:

```
resources/views/{Section}/{SubSection}/Pages/{pageName}.blade.php
resources/views/{Section}/{SubSection}/Sections/{PageName}/section_1.blade.php
resources/views/{Section}/{SubSection}/Sections/{PageName}/section_2.blade.php
resources/views/{Section}/{SubSection}/Sections/{PageName}/section_N.blade.php
```

Each page includes components via `<x-components::...>` and sections via `@include`.

### Layout-based pages (Dashboard/Company)

Each page extends a layout. Files go to:

```
resources/views/{Section}/{SubSection}/Pages/{pageName}.blade.php
```

Page extends layout via `@extends("{Section}.{SubSection}.main")` and uses `@section("page")`.

### Components

```
resources/views/{Section}/{SubSection}/Components/{componentName}.blade.php
```

Components are included in pages via `<x-components::` syntax or `@include`.

## MAIN FORM EXTRACTION

Inspect every Blade page.

If any page contains

<main class="main-form">

extract it into a reusable Blade Component.

Never leave a complete main-form inside a page.

The page must contain only:

- trigger button
- component call

The component keeps all HTML.

The page keeps no duplicated popup markup.

## BUTTON LINKING

Every extracted component must receive

dataFollowButton

Every trigger button must contain

data-follow

Both values must match.

Never invent custom popup JavaScript.

Always use the shared popup system.

### Sections (partial includes)

```
resources/views/{Section}/{SubSection}/Sections/{PageName}/section_{N}.blade.php
```

Sequential numbering: `section_1.blade.php`, `section_2.blade.php`, etc.

## PAGE SECTION EXTRACTION

Inspect every Blade page.

If the page contains multiple logical blocks:

extract each block into

Sections/{PageName}/section_N.blade.php

Replace the extracted HTML with @include.

Do not leave very large Blade pages.

## COMPONENT DISCOVERY

Search for repeated Blade markup.

If the same HTML appears in multiple pages:

extract it into a reusable Component.

Replace duplicated markup with Blade component calls.

### Abbreviations (hamburger/mobile menu)

```
resources/views/{Section}/{SubSection}/Abbreviations/menu.blade.php
```

---

## 6. COMPONENT CALLING

Components are called via Blade component syntax:

```html
<x-components::{Section}.{componentName} />
```

### File path resolution

```
<x-components::company.popupForm />
  │          │        │
  │          │        └── resources/views/Company/Components/popupForm.blade.php
  │          └── Company = directory under views/
  └── components = registered namespace prefix
```

### Component with props and slot

```html
<x-components::company.popupForm
  header="Add Client"
  description="Fill in client details"
  dataFollowButton="add-client"
  pathPostForm="{{ route('company.store.client') }}"
>
  <!-- slot content (form fields) -->
</x-components::company.popupForm>
```

### Component without props

```html
<x-components::website.web.form-login />
```

### Include syntax (for simple partials)

```html
@include("{Section}.{SubSection}.Components.{componentName}")
```

---

## 7. BUTTON → COMPONENT LINKING (data-follow)

### Core Rule

Button has `data-follow="unique-id"`.
Component has `data-follow="unique-id"` (via `dataFollowButton` prop).
Same string on both = they are linked. **No custom JS needed.**

### Button syntax

```html
<button class="button-form" data-follow="add-client">Add Client</button>
```

### Component syntax

```html
<x-components::company.popupForm
  dataFollowButton="add-client"
  header="Add Client"
  pathPostForm="#"
/>
```

### Multiple buttons → same component

```html
<button class="button-form" data-follow="show-task">Task A</button>
<button class="button-form" data-follow="show-task">Task B</button>
<!-- Both open the same component -->
```

### Multiple buttons → different components

```html
<button class="button-form" data-follow="add-client">Add Client</button>
<button class="button-form" data-follow="add-branch">Add Branch</button>

<x-components::company.popupForm dataFollowButton="add-client" ... />
<x-components::company.popupForm dataFollowButton="add-branch" ... />
```

### How it works

1. Button has `class="button-form"` + `data-follow="unique-id"`
2. Component has `class="main-form"` + `data-follow="unique-id"`
3. Click button → shared `public.js` finds matching `main-form[data-follow]` → adds `show-main-card`
4. Click `.close` → removes `show-main-card`

---

### Mandatory extraction rule for main-form

Every `<main class="main-form">`, regardless of its purpose (popup, modal, dialog, login, register, forgot password, reset password, verification, confirmation, side panel, or any floating form), MUST be extracted into its own reusable Blade component under:

```
resources/views/{Section}/{SubSection}/Components/
```

The page itself must never contain the full `main-form` implementation.

Each page must only contain:

1. The trigger button.
2. The component call.

The trigger button MUST use:

```html
<button class="button-form" data-follow="unique-id"></button>
```

The component MUST receive the same identifier:

```html
<x-components::{Section}.{componentName}
    dataFollowButton="unique-id"
/>
```

The shared `public.js` is responsible for linking the button and the component through `data-follow`. Do not generate custom JavaScript for opening or closing individual forms.

### Forbidden

```html
<main class="main-form">
    ...
</main>
```

directly inside any page.

### Required

Create the `main-form` as a reusable component, include it in the page, and link it through the matching `data-follow` / `dataFollowButton` values.

- Never place a complete `<main class="main-form">` directly inside any page.
- Every `main-form` must be reusable.
- Every `main-form` must be included via `<x-components::...>`.
- Every `main-form` must be opened through a trigger button using `data-follow`.
- The component must receive the same identifier through `dataFollowButton`.
- The shared `public.js` handles opening and closing the component. Do not create page-specific JavaScript for this behavior.

## 8. MULTI-FORM COMPONENT STRUCTURE (formLogin pattern)

Components that contain multiple forms (like login/register/forgot/reset)
follow this exact structure. Any new project must replicate this pattern.

### HTML structure

```html
<main class="main-form">
  <button class="close"><!-- X icon --></button>
  <div class="card">
    <h1>Title</h1>
    <p>Description</p>

    <!-- Form 1: visible by default -->
    <form action="{{ route('...') }}" method="post">
      @csrf
      <!-- fields -->
      <button type="submit">Send</button>
      <button type="button" class="btn-forget">Forgot Password?</button>
    </form>

    <!-- Form 2: hidden by default -->
    <form action="{{ route('...') }}" method="post" class="hidden">
      @csrf
      <!-- fields -->
      <button type="submit">Send</button>
    </form>
  </div>
</main>
```

### Rules
- Only ONE form is visible at a time.
- Hidden forms use `class="hidden"` (CSS: `display: none !important`).
- First form is visible by default (no `hidden` class).
- All subsequent forms start with `class="hidden"`.
- Each form has its own `action` route, `@csrf`, and submit button.

### JS toggle pattern (in public.js)

```javascript
let mainForm = document.querySelectorAll(".main-form");
let forms = mainForm[0].querySelectorAll("form");
let btnForget = document.querySelector(".btn-forget");

if (btnForget) {
  btnForget.addEventListener("click", () => {
    forms[0].classList.add("hidden");    // hide form 1
    forms[1].classList.remove("hidden"); // show form 2
  });
}
```

## SHARED JAVASCRIPT

Search the entire project.

If multiple popup implementations exist:

merge them into

public.js

Delete duplicated popup logic only after confirming the shared implementation behaves identically.

Never duplicate popup code between pages.

### Standard forms in login component

Every login component must contain these forms in order:

| # | Form purpose          | Default state | Route name          | Fields                                    |
|---|-----------------------|---------------|---------------------|-------------------------------------------|
| 1 | Register/Login        | visible       | `web.login`         | full-name, email, phone, password, password_confirmation, send_copy |
| 2 | Forgot Password       | hidden        | `web.foget`         | email                                     |
| 3 | Verify Code           | cookie-based  | `web.check`         | code                                      |
| 4 | Reset Password        | cookie-based  | `web.reset.passowrd`| password, password_confirmation           |

### Password strength indicator

Password fields must include the password strength popup:

```html
<div class="perant-input" style="position:relative">
  <input type="text" placeholder="password" name="password"
    class="password-input" data-type="register"
    autocomplete="new-password"
    oncopy="return false" onpaste="return false" oncut="return false">
  <label>password</label>
  <div class="password-strength" data-type="register">
    <div class="password-strength-bar">
      <div class="password-strength-fill"></div>
    </div>
    <div class="password-strength-text"></div>
    <div class="password-strength-rules">
      <div class="rule rule-length">...</div>
      <div class="rule rule-upper">...</div>
      <div class="rule rule-lower">...</div>
      <div class="rule rule-number">...</div>
      <div class="rule rule-symbol">...</div>
      <div class="rule rule-name">...</div>
    </div>
  </div>
</div>
```

### Copy/paste prevention

All password fields must have:

```html
autocomplete="new-password"
oncopy="return false"
onpaste="return false"
oncut="return false"
```

### Social login buttons

Login form must include social login at the bottom:

```html
<div class="row">
  <div class="line"></div>
  <p>or</p>
  <div class="line"></div>
</div>
<div class="social-login">
  <a href="{{ route('google.login') }}" class="btn google-btn">
    <!-- Google SVG icon -->
    <span>Continue with Google</span>
  </a>
</div>
```

### Submit button (shared across all forms)

```html
<button type="submit" class="submit-btn">
  <svg><!-- arrow icon --></svg>
  <div class="text">Send</div>
</button>
```

### CSS (in public.css — shared, do not duplicate)

```css
.hidden {
  display: none !important;
}
```

---

## 9. CSS FILE DISTRIBUTION

### Per-page CSS

```
public/css/{section}/{subSection}/pages/{pageName}.css
```

Every page has its own CSS file. Even if empty, create it.

### Shared CSS (per area)

```
public/css/{section}/{subSection}/public.css
```

Styles shared across all pages in that section.

### Per-component CSS (if needed)

```
public/css/{section}/{subSection}/components/{componentName}.css
```

Only create when the component needs its own styles.

---

## 10. CSS LINKING

### How pages load CSS

Each page loads its own CSS + the shared CSS in the `<head>`:

```html
<head>
  <link rel="stylesheet" href="{{ asset('css/{section}/{subSection}/public.css') }}">
  <link rel="stylesheet" href="{{ asset('css/{section}/{subSection}/pages/{pageName}.css') }}">
</head>
```

## CSS AND JAVASCRIPT REFACTORING

Inspect every CSS and JavaScript file.

Split shared code into:

public.css

public.js

Move page-specific code into:

pages/{page}.css

pages/{page}.js

Never duplicate styles.

Never duplicate scripts.

Reuse existing code whenever possible.

### CSS loading order

1. `public.css` — loaded first (shared base styles)
2. `pages/{pageName}.css` — loaded second (page-specific overrides)
3. `components/{componentName}.css` — loaded inside the component if it has its own styles

### Standalone pages (Website/Web)

Each page loads its own CSS directly in `<head>`:

```html
<link rel="stylesheet" href="{{ asset('css/website/web/public.css') }}">
<link rel="stylesheet" href="{{ asset('css/website/web/pages/aboutUs.css') }}">
```

### Layout-based pages (Dashboard/Company)

Layout file loads shared CSS. Each page loads its own CSS in `@section`:

```html
<!-- In main.blade.php layout -->
<link rel="stylesheet" href="{{ asset('css/{section}/{subSection}/public.css') }}">

<!-- In page blade -->
@section("page")
  <link rel="stylesheet" href="{{ asset('css/{section}/{subSection}/pages/{pageName}.css') }}">
@endsection
```

---

## 11. JS FILE DISTRIBUTION

### Per-page JS

```
public/js/{section}/{subSection}/pages/{pageName}.js
```

Every page has its own JS file. Even if empty, create it.

### Shared JS (per area)

```
public/js/{section}/{subSection}/public.js
```

Scripts shared across all pages in that section.

### Layout JS

```
public/js/{section}/{subSection}/main.js
```

Loaded on every sub-page via the layout file.

---

## 12. ROUTE REGISTRATION

All routes go in `routes/web.php`. Pattern:

```php
use App\Http\Controllers\{Section}\{SubSection}\Pages\{Name}Controller;

Route::controller({Name}Controller::class)->group(function () {
    Route::get('/{route-slug}', 'index')->name('{route-name}');
});
```

### POST routes (forms):

```php
Route::post('/{route-slug}', '{Method}Controller@{method}')->name('{route-name}');
```

### Naming convention
- URL: kebab-case (`/about-us`, `/discount-code`)
- Route name: kebab-case (`about-us`, `discount-code`)
- Group prefix: use when controllers share a prefix (`/company`, `/dashbord`)

---

## 13. QUICK COMMAND REFERENCE

When the user says **"add a new page"**, create:
1. `{Name}Controller.php` in `Pages/`
2. `{Name}Request.php` in `Requesters/{Section}/{SubSection}/{Name}/`
3. `{pageName}.blade.php` in `Pages/`
4. `section_1.blade.php` (and more) in `Sections/{PageName}/`
5. `{pageName}.css` in `public/css/.../pages/`
6. `{pageName}.js` in `public/js/.../pages/`
7. Route in `routes/web.php`

When the user says **"add a component"**, create:
1. `{componentName}.blade.php` in `Components/`
2. Include it in target page(s) via `<x-components::...>`
3. Add `dataFollowButton="unique-id"` on component, `data-follow="unique-id"` on button
4. Add component styles in `public/css/.../components/` if needed

When the user says **"add a section"**, create:
1. `section_{N}.blade.php` in `Sections/{PageName}/`
2. Include it in the page via `@include`

## IMPORT REFACTORING
Whenever a class is moved:

Update every

use

statement automatically.

Update:

Controllers

Requests

Services

Mail

Models

Blade references

Namespaces

Component references

Route references

No broken imports are allowed.

---

## DEPENDENCY ANALYSIS
Before moving any file:

Analyze dependencies.

Determine every place where the file is used.

Update all references automatically.

Never leave broken namespaces.

Never leave broken imports.

Never leave broken Blade includes.

## 14. API UTILITY (public/js/api.js)

All API calls must use the shared API utility. Never use raw `fetch()` for internal or external API calls.

### Loading the API utility

**In standalone pages (Website/Web):** Add before any page-specific JS:
```html
<script src="{{ asset('js/api.js') }}"></script>
<script src="{{ asset('js/website/web/fingerprint.js') }}"></script>
```

**In layout-based pages (Dashboard/Company):** Add in the layout `main.blade.php`:
```html
<script src="{{ asset('js/api.js') }}"></script>
```

**In ES module files (company pages):** Import from the utility:
```javascript
import { apiPost, apiGet, apiExternal } from "../../api.js";
```

### Available functions

| Function | Method | Use Case |
|---|---|---|
| `api.get(url, options)` | GET | Fetch data from server |
| `api.post(url, body, options)` | POST | Send data to server |
| `api.put(url, body, options)` | PUT | Update resource |
| `api.patch(url, body, options)` | PATCH | Partial update |
| `api.delete(url, options)` | DELETE | Delete resource |
| `api.external(url, options)` | any | External API calls with retry |
| `api.request(url, options)` | any | Custom method |
| `api.fetchWithRetry(url, options, attempts, delay)` | any | Low-level retry fetch |

### ES module imports

```javascript
import { apiGet, apiPost, apiPut, apiPatch, apiDelete, apiExternal, fetchWithRetry } from "../../api.js";
```

### Internal API calls (same-origin)

```javascript
// Global script (after api.js loaded)
api.post("/fingerprint/browser-data", data)
  .then(response => { /* handle success */ })
  .catch(error => { /* handle error */ });

// ES module
import { apiPost } from "../../api.js";
apiPost("/endpoint", { key: value })
  .then(data => { ... })
  .catch(err => { ... });
```

### External API calls

```javascript
// Global script
api.external("https://api.example.com/data")
  .then(data => { ... })
  .catch(err => { ... });

// ES module
import { apiExternal } from "../../api.js";
apiExternal("https://api.example.com/data", { retryAttempts: 3 })
  .then(data => { ... });
```

### Request options

```javascript
api.post("/endpoint", body, {
  headers: { "X-Custom": "value" },   // extra headers merged with defaults
  retry: false,                        // disable retry (default: true)
  retryAttempts: 3,                    // custom retry count
  retryDelay: 1000,                    // custom retry delay ms
  signal: abortController.signal,      // AbortSignal for cancellation
});
```

### Response handling

- **JSON responses:** Automatically parsed, returned as object
- **Non-JSON responses:** Returned as text string
- **HTTP errors (non-2xx):** Throws `Error` with `.status` and `.data` properties
- **Network errors:** Throws after retry exhaustion

### Default headers (internal requests)

```
Content-Type: application/json
Accept: application/json
X-CSRF-TOKEN: <from meta tag>
```

### Rules

1. **Never use raw `fetch()`** for API calls — always use the API utility
2. **Internal requests** use `credentials: "same-origin"` and include CSRF token
3. **External requests** use `credentials: "omit"` and no CSRF token
4. **Retry logic** is built-in (2 attempts, 500ms delay) — override via options
5. **Error handling** is standardized — always check `.status` and `.data` on errors

## MINIMAL CODE MODIFICATION POLICY

Refactor only what is required to satisfy this architecture.

Do not rewrite functions.

Do not optimize algorithms.

Do not rename variables.

Do not change coding style.

Do not improve formatting unless required by moved code.

Only perform structural refactoring.

The generated code should preserve the original implementation whenever possible.

## FINAL VERIFICATION

Before finishing:

Verify:

✓ Controllers compile.

✓ Form Requests compile.

✓ Services compile.

✓ Mail compile.

✓ Routes compile.

✓ Views compile.

✓ Blade Components resolve correctly.

✓ CSS paths are valid.

✓ JS paths are valid.

✓ All namespaces are valid.

✓ All imports are valid.

✓ No duplicate popup implementations remain.

✓ Every main-form became a reusable component.

✓ Every popup uses data-follow.

✓ Every controller still behaves exactly the same.

✓ No business logic was removed.

✓ No feature was lost.

The final application must behave IDENTICALLY to the original project.

Only the architecture may change.