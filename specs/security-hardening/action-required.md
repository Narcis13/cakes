# Action Required: Security Hardening

Manual steps that must be completed by a human. These cannot be automated.

## Before Implementation

- [ ] **Install HTML Purifier** - Run `composer require ezyang/htmlpurifier` to add XSS protection library

## After Implementation

- [ ] **Rotate Security Salt** - Generate a new 64-character hex string and set `SECURITY_SALT` environment variable. The current salt is exposed in `app_local.php` and should be considered compromised.

- [ ] **Rotate TinyMCE API Key** - Generate a new API key from TinyMCE dashboard and set `TINYMCE_API_KEY` environment variable. The current key (`mw6ldaj3x35183lcdhla0dtj3uqtuv8fxharylsurnqxyy1c`) is exposed.

- [ ] **Change Database Credentials** - The current credentials are `root`/`root`. Create a dedicated database user with limited privileges and update environment variables:
  - `DATABASE_USERNAME`
  - `DATABASE_PASSWORD`

- [ ] **Set Up Environment Variables** - Ensure all required environment variables are set in production:
  ```bash
  SECURITY_SALT=<new-64-char-hex>
  DATABASE_HOST=<host>
  DATABASE_USERNAME=<user>
  DATABASE_PASSWORD=<password>
  DATABASE_NAME=<database>
  TINYMCE_API_KEY=<new-api-key>
  DEBUG=false
  ```

- [ ] **Run Database Migrations** - After implementation, run migrations to create new tables:
  ```bash
  bin/cake migrations migrate
  ```

- [ ] **Test Rate Limiting** - Manually verify that login is blocked after 3 failed attempts

- [ ] **Verify Security Headers** - Check headers at https://securityheaders.com after deployment

- [ ] **Update Existing User Passwords** - Inform admin users they need to update passwords to meet new requirements (12+ chars with complexity)

---

> **Note:** These tasks are also listed in context within `implementation-plan.md`
