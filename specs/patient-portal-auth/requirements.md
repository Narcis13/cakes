# Requirements: Patient Portal Authentication System

## Overview

Implement a patient authentication system that restricts online appointment booking (`/appointments`) to registered and verified patients only. This replaces the current guest booking flow with a secure, account-based system.

## Why

- **Security**: Verified patients reduce spam/fake bookings
- **Patient Experience**: Patients can view and manage their appointments
- **Data Quality**: Linked appointments enable better patient history tracking
- **Accountability**: Authenticated users are less likely to no-show

## Features

### Patient Registration
- Collect: Full name, email, phone number, password
- Password requirement: 8 characters minimum (no complexity rules)
- Send email verification link after registration
- Account inactive until email verified

### Patient Login
- Email + password authentication
- Security: 3 failed attempts = 1 hour account lockout
- Track login attempts for security monitoring
- Separate authentication context from admin/staff

### Password Reset
- Email-based password reset flow
- Reset token valid for 1 hour
- Secure token generation (64 characters)

### Patient Portal (`/portal`)
- Dashboard showing upcoming and past appointments
- View all appointment history
- Cancel upcoming appointments (anytime before appointment starts)
- View and edit profile information

### Appointment Booking Changes
- Remove guest booking - only authenticated patients can book
- Auto-fill patient data (name, email, phone) from profile
- Link appointments to `patient_id` for history tracking
- Remove CAPTCHA (authentication provides spam protection)

### User Interface
- All text in Romanian language (hardcoded, no translation files)
- Clean portal layout with navigation

## Acceptance Criteria

### Registration
- [ ] Patient can register with full_name, email, phone, password
- [ ] Password validation enforces 8 character minimum
- [ ] Email uniqueness is enforced
- [ ] Verification email is sent after registration
- [ ] Patient cannot login until email is verified
- [ ] Verification token expires after 24 hours

### Login
- [ ] Patient can login with email and password
- [ ] Failed login attempts are tracked
- [ ] Account locks after 3 failed attempts for 1 hour
- [ ] Last login time and IP are recorded
- [ ] Patient is redirected to portal dashboard after login

### Password Reset
- [ ] Patient can request password reset via email
- [ ] Reset email contains secure token link
- [ ] Token expires after 1 hour
- [ ] Patient can set new password with valid token
- [ ] Old password is invalidated after reset

### Portal Dashboard
- [ ] Shows count of upcoming appointments
- [ ] Shows recent/past appointments summary
- [ ] Provides navigation to full appointment list and profile

### Appointment Management
- [ ] Patient can view all their appointments (past and upcoming)
- [ ] Patient can cancel any appointment before it starts
- [ ] Cancelled appointments show in history with status

### Booking Flow
- [ ] Unauthenticated users are redirected to login
- [ ] Patient data is auto-filled in booking form
- [ ] Booking creates appointment linked to patient_id
- [ ] Confirmation shows appointment linked to patient account

## Dependencies

- Existing CakePHP Authentication plugin (already configured for admin)
- Existing LoginSecurityService pattern for rate limiting
- Existing AppointmentMailer pattern for email templates
- Existing appointments table and booking flow

## Out of Scope

- Social login (Google, Facebook)
- SMS verification
- Two-factor authentication
- Patient medical records
- Multi-language support (Romanian only for now)
