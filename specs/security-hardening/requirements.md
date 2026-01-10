# Requirements: Security Hardening

## Overview

Implement military-grade security hardening for the CakePHP 5.1 Hospital Management System. This application handles sensitive healthcare data (patient appointments, contact information) and requires comprehensive security measures to protect against common attack vectors identified in the security audit.

## Why This Is Needed

A security audit identified **13 vulnerabilities** (4 Critical, 5 High, 4 Medium) including:
- No brute force protection on admin login
- Weak password requirements (only 6 characters)
- XSS vulnerabilities in CMS content rendering
- Disabled form protection component
- Mass assignment vulnerabilities
- Hardcoded credentials with insecure defaults
- Missing security headers
- Client-side MIME type validation (bypassable)

## User Requirements

1. **Rate Limiting on Admin Login**: Block login for 1 hour after 3 failed attempts
2. **Strong Password Policy**: Enforce complex passwords when creating accounts

## Acceptance Criteria

### Login Security
- [ ] After 3 failed login attempts for an email, that account is locked for 1 hour
- [ ] After 10 failed login attempts from an IP, that IP is blocked for 1 hour
- [ ] All login attempts (success/failure) are logged with timestamp, email, IP, and user agent
- [ ] Locked accounts display "Account temporarily locked. Try again later." message
- [ ] Successful login resets the failed attempt counter

### Password Policy
- [ ] Minimum 12 characters required
- [ ] At least 1 uppercase letter (A-Z) required
- [ ] At least 1 lowercase letter (a-z) required
- [ ] At least 1 number (0-9) required
- [ ] At least 1 special character (!@#$%^&*(),.?":{}|<>) required
- [ ] Clear error messages indicate which requirements are not met
- [ ] Client-side validation hints guide users

### XSS Protection
- [ ] All CMS content (news, pages) is sanitized before rendering
- [ ] HTML Purifier allows safe tags (p, br, strong, em, ul, ol, li, h2-h6, a, img, table)
- [ ] Scripts, iframes, and dangerous attributes are stripped
- [ ] Content Security Policy headers are set

### Form & Session Security
- [ ] FormProtection component is enabled (prevents form tampering)
- [ ] Session cookies have `httponly`, `secure`, and `samesite=Strict` flags
- [ ] Sessions timeout after 30 minutes of inactivity
- [ ] Sensitive entity fields cannot be mass-assigned

### File Upload Security
- [ ] MIME type validation uses server-side `finfo_file()`, not client-provided headers
- [ ] File extension must match detected MIME type
- [ ] Upload rejected with clear error if validation fails

### Security Headers
- [ ] Content-Security-Policy header restricts script/style sources
- [ ] X-Frame-Options: SAMEORIGIN prevents clickjacking
- [ ] X-Content-Type-Options: nosniff prevents MIME sniffing
- [ ] Referrer-Policy: strict-origin-when-cross-origin limits referrer leakage
- [ ] Strict-Transport-Security enabled in production

### Configuration Security
- [ ] Debug mode defaults to false
- [ ] SSL certificate verification enabled for email transport
- [ ] No hardcoded credentials with fallback values
- [ ] Sensitive data (emails, patient PII) removed from debug logs

### Access Control
- [ ] Appointment success page requires token or session verification (no enumeration)
- [ ] Doctor email/phone removed from public API responses
- [ ] Open redirect vulnerability fixed (only internal redirects allowed)

## Dependencies

- **ezyang/htmlpurifier** - HTML sanitization library (composer package)
- Existing CakePHP Authentication plugin (already installed)

## Related Features

- Admin panel authentication system
- Appointment booking system
- CMS (news/pages management)
- File upload management

## Security Standards Reference

- OWASP Top 10 2021
- NIST Password Guidelines (SP 800-63B)
- CakePHP Security Best Practices
