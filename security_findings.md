# Comprehensive Security Audit Report
## CakePHP 5.1 Hospital Management System

**Audit Date:** 2026-01-11
**Overall Risk Assessment:** MEDIUM-HIGH

---

## Executive Summary

This security audit of the CakePHP 5.1 hospital management system reveals several security concerns ranging from **Critical** to **Low** severity. The application handles sensitive patient data (appointments, contact information) and requires immediate attention on identified vulnerabilities.

---

## Detailed Findings

### 1. CRITICAL: Stored XSS Vulnerability in News/Page Content

**Severity:** Critical
**CVSS Score:** 8.0 (High)

**Description:** Rich HTML content from the CMS is rendered without escaping, allowing administrators to inject malicious scripts that execute for all visitors.

**Affected Files:**

1. `templates/Admin/News/view.php` (Line 72)
```php
<?= $newsItem->content ?>
```

2. `templates/News/view.php` (Line 62)
```php
<?= $news->content ?>
```

3. `templates/Pages/page.php` (Lines 31-32)
```php
<?php if ($component->type === 'html'): ?>
    <?= $component->content ?>
```

**Impact:**
- Session hijacking of site visitors
- Credential theft
- Malware distribution
- Defacement
- Administrative account compromise if admin views poisoned content

**Recommendation:**
- Use `h()` helper for all untrusted content
- Implement Content Security Policy (CSP) headers
- If HTML is required, use a whitelist-based HTML sanitizer like HTMLPurifier

---

### 2. HIGH: FormProtection Component Disabled

**Severity:** High
**Location:** `src/Controller/AppController.php` (Lines 50-54)

```php
/*
 * Enable the following component for recommended CakePHP form protection settings.
 * see https://book.cakephp.org/5/en/controllers/components/form-protection.html
 */
//$this->loadComponent('FormProtection');
```

**Description:** The FormProtection component is commented out. This component provides protection against:
- Form tampering
- CSRF tokens in form hidden fields
- Double submit prevention

**Impact:** Forms may be vulnerable to tampering attacks where attackers modify form field values.

**Recommendation:** Uncomment and enable `$this->loadComponent('FormProtection');`

---

### 3. HIGH: Mass Assignment Vulnerability - Appointment Status

**Severity:** High
**Location:** `src/Model/Entity/Appointment.php` (Lines 48-70)

```php
protected array $_accessible = [
    'patient_name' => true,
    'patient_phone' => true,
    'patient_email' => true,
    'patient_cnp' => true,
    'service_id' => true,
    'doctor_id' => true,
    'appointment_date' => true,
    'appointment_time' => true,
    'end_time' => true,
    'status' => true,           // DANGEROUS
    'notes' => true,
    'confirmation_token' => true, // DANGEROUS
    'confirmed_at' => true,      // DANGEROUS
    'cancelled_at' => true,      // DANGEROUS
    // ...
];
```

**Description:** The `status`, `confirmation_token`, `confirmed_at`, and `cancelled_at` fields are mass-assignable. An attacker could directly set `status => 'confirmed'` when booking an appointment, bypassing the email confirmation workflow.

**Impact:**
- Bypass appointment confirmation process
- Create fake "confirmed" appointments
- Data integrity issues

**Recommendation:** Set these sensitive fields to `false`:
```php
'status' => false,
'confirmation_token' => false,
'confirmed_at' => false,
'cancelled_at' => false,
```

---

### 4. HIGH: SSL/TLS Certificate Verification Disabled

**Severity:** High
**Location:** `config/app_local.php` (Lines 102-107)

```php
'context' => [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    ]
],
```

**Description:** SSL certificate verification is disabled for email transport. This makes the application vulnerable to Man-in-the-Middle (MITM) attacks.

**Impact:**
- Email credentials could be intercepted
- Email content (including patient data) could be read or modified

**Recommendation:** Enable SSL verification in production:
```php
'verify_peer' => true,
'verify_peer_name' => true,
'allow_self_signed' => false
```

---

### 5. HIGH: Hardcoded Credentials in Configuration

**Severity:** High
**Location:** `config/app_local.php`

```php
'Security' => [
    'salt' => env('SECURITY_SALT', '97de4793d5e250d6a5ac5032627eb2ad425ae674a91153553ae353daf2f8689e'),
],
'ApiKeys' => [
    'tinymce' => env('TINYMCE_API_KEY', 'mw6ldaj3x35183lcdhla0dtj3uqtuv8fxharylsurnqxyy1c'),
],
'Datasources' => [
    'default' => [
        'username' => 'root',
        'password' => 'root',
```

**Description:** While `app_local.php` is in `.gitignore`, hardcoded fallback values for security salt and API keys pose a risk. If environment variables are not set, the fallback values are used.

**Impact:**
- If these defaults are used in production, attackers knowing the salt could forge session data
- Exposed API keys could be abused

**Recommendation:**
- Remove all fallback default values
- Ensure environment variables are mandatory
- Rotate the exposed TinyMCE API key and security salt

---

### 6. MEDIUM: Debug Mode May Be Enabled in Production

**Severity:** Medium
**Location:** `config/app_local.php` (Line 18)

```php
'debug' => filter_var(env('DEBUG', true), FILTER_VALIDATE_BOOLEAN),
```

**Description:** The default value for DEBUG is `true`. If the `DEBUG` environment variable is not explicitly set to `false`, debug mode will be enabled in production.

**Impact:**
- Detailed error messages exposed to users
- Stack traces revealing file paths and code structure
- Potential exposure of sensitive configuration

**Recommendation:** Change default to `false`:
```php
'debug' => filter_var(env('DEBUG', false), FILTER_VALIDATE_BOOLEAN),
```

---

### 7. MEDIUM: Sensitive Data in Debug Logs

**Severity:** Medium
**Location:** `src/Controller/Admin/UsersController.php` (Lines 54-60)

```php
if ($this->request->is('post')) {
    $data = $this->request->getData();
    $this->log('Login attempt for: ' . ($data['email'] ?? 'no email'), 'debug');
    $this->log('Authentication result: ' . ($result ? $result->getStatus() : 'no result'), 'debug');
    if ($result) {
        $this->log('Result errors: ' . json_encode($result->getErrors()), 'debug');
    }
}
```

**Description:** Login attempts including email addresses are logged. While passwords are not directly logged, this creates a security concern as user email addresses should not be in debug logs.

**Impact:**
- Email enumeration through log analysis
- Compliance issues (GDPR, HIPAA)

**Recommendation:** Remove or mask email in debug logs:
```php
$this->log('Login attempt for: [REDACTED]', 'debug');
```

---

### 8. MEDIUM: Session Configuration Uses PHP Defaults

**Severity:** Medium
**Location:** `config/app.php` (Lines 412-414)

```php
'Session' => [
    'defaults' => 'php',
],
```

**Description:** Session configuration uses PHP defaults which may not be secure. Missing explicit secure cookie settings.

**Impact:**
- Session cookies may be transmitted over HTTP
- Session hijacking through network sniffing

**Recommendation:** Add secure session settings:
```php
'Session' => [
    'defaults' => 'php',
    'ini' => [
        'session.cookie_secure' => true,
        'session.cookie_httponly' => true,
        'session.cookie_samesite' => 'Strict',
        'session.use_strict_mode' => true,
    ],
],
```

---

### 9. MEDIUM: File Upload - MIME Type Client-Provided

**Severity:** Medium
**Location:** `src/Controller/Admin/FilesController.php` (Lines 288-290)

```php
$mimeType = $uploadedFile->getClientMediaType();
if (!in_array($mimeType, $allowedTypes)) {
```

**Description:** MIME type validation relies on `getClientMediaType()` which uses the client-provided Content-Type header. This can be spoofed.

**Impact:**
- Malicious files could be uploaded by spoofing MIME type
- Potential for remote code execution if uploaded files are executed

**Recommendation:**
- Use `finfo_file()` for server-side MIME type detection
- Verify file content with magic bytes
- Store uploads outside webroot or with randomized names without extensions

---

### 10. MEDIUM: Open Redirect Potential

**Severity:** Medium
**Location:** `src/Controller/Admin/UsersController.php` (Lines 65-71)

```php
$redirect = $this->request->getQuery('redirect', [
    'controller' => 'Dashboard',
    'action' => 'index',
    'prefix' => 'Admin',
]);

return $this->redirect($redirect);
```

**Description:** The redirect parameter from query string is used without validation. An attacker could craft a malicious URL to redirect users to phishing sites.

**Impact:**
- Phishing attacks using legitimate domain in initial URL
- Credential harvesting

**Recommendation:** Validate redirect URLs are internal:
```php
$redirect = $this->request->getQuery('redirect');
if ($redirect && !$this->Url->isInternal($redirect)) {
    $redirect = ['controller' => 'Dashboard', 'action' => 'index', 'prefix' => 'Admin'];
}
```

---

### 11. LOW: Missing Security Headers

**Severity:** Low
**Location:** Application-wide

**Description:** Security headers are not configured in the application:
- Content-Security-Policy (CSP)
- X-Frame-Options
- X-Content-Type-Options
- Referrer-Policy
- Permissions-Policy

**Impact:**
- Clickjacking attacks possible
- XSS mitigation reduced
- MIME sniffing attacks possible

**Recommendation:** Add middleware or configure web server to include:
```
Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' cdn.jsdelivr.net cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' cdn.jsdelivr.net cdnjs.cloudflare.com;
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
Referrer-Policy: strict-origin-when-cross-origin
```

---

### 12. LOW: Admin Path Obscurity (Not Security)

**Severity:** Low/Informational
**Location:** `config/routes.php` (Line 118)

```php
$builder->prefix('Admin', ['path' => '/smupa1881'], function (RouteBuilder $routes) {
```

**Description:** The admin path is obscured (`/smupa1881`) rather than the standard `/admin`. While this provides minimal security through obscurity, it should not be relied upon.

**Impact:**
- Once discovered, the path is known
- Search engine indexing could expose it

**Recommendation:**
- Obscured paths are fine as an additional layer
- Ensure strong authentication and authorization remain the primary defense
- Add robots.txt to prevent indexing

---

### 13. LOW: Appointment Data Logged

**Severity:** Low
**Location:** `src/Controller/AppointmentsController.php` (Lines 322, 445)

```php
Log::debug('Book appointment data received: ' . json_encode($data));
Log::error('Appointment save failed. Errors: ' . json_encode($appointment->getErrors()));
```

**Description:** Patient appointment data including personal information is logged in debug/error logs.

**Impact:**
- Patient PII in log files
- HIPAA/GDPR compliance concerns

**Recommendation:** Sanitize or remove PII from log messages in production.

---

## Security Requirements Checklist Summary

| Requirement | Status | Notes |
|-------------|--------|-------|
| All inputs validated and sanitized | PARTIAL | Validation exists but MIME type checking is client-side |
| No hardcoded secrets or credentials | FAIL | Default values in app_local.php |
| Proper authentication on all endpoints | PASS | Admin routes protected by middleware |
| SQL queries use parameterization | PASS | CakePHP ORM handles this |
| XSS protection implemented | FAIL | Raw HTML output in CMS content |
| HTTPS enforced where needed | FAIL | SSL verification disabled |
| CSRF protection enabled | PASS | CsrfProtectionMiddleware is loaded |
| Security headers properly configured | FAIL | No security headers |
| Error messages don't leak sensitive info | PARTIAL | Debug mode default is true |
| Dependencies up-to-date | UNKNOWN | Needs `composer audit` check |

---

## Prioritized Remediation Roadmap

### Immediate (Within 24-48 hours)
1. **Fix XSS vulnerability** - Sanitize all CMS content output
2. **Rotate compromised credentials** - Change security salt and TinyMCE API key
3. **Set debug mode default to false** - Prevent accidental production exposure

### Short-term (Within 1 week)
4. **Enable FormProtection component** - Prevent form tampering
5. **Fix mass assignment vulnerabilities** - Protect status fields in entities
6. **Enable SSL verification** - Secure email transport
7. **Implement proper MIME type validation** - Server-side file type checking

### Medium-term (Within 1 month)
8. **Add security headers** - Implement CSP, X-Frame-Options, etc.
9. **Secure session configuration** - Add secure cookie settings
10. **Validate redirect URLs** - Prevent open redirect

### Ongoing
11. **Remove sensitive data from logs** - Sanitize PII in log messages
12. **Regular dependency audits** - Run `composer audit` regularly
13. **Security testing** - Implement automated security scanning in CI/CD

---

## Positive Security Observations

1. **CSRF Protection is enabled** via `CsrfProtectionMiddleware` with httponly cookies
2. **Password hashing** uses bcrypt via `DefaultPasswordHasher`
3. **Authentication middleware** is properly configured with session and form authenticators
4. **Role-based access control** is implemented for admin area (admin/staff roles)
5. **Input validation** exists in Table classes with proper validators
6. **DELETE operations require POST method** - Proper use of `allowMethod()`
7. **Parameterized queries** - CakePHP ORM prevents SQL injection
8. **XSS protection in templates** - Most views use `h()` helper correctly
9. **File upload restrictions** - Type and size limits are enforced
10. **Rate limiting** is implemented for appointment booking
11. **CAPTCHA protection** on appointment forms
12. **Sensitive files properly gitignored** - `app_local.php` is not in version control

---

This report represents a point-in-time security assessment. Regular security audits and penetration testing are recommended for a healthcare application handling sensitive patient data.
