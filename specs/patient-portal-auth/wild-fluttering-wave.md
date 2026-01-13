# Plan: Patient Authentication System for Online Appointments

## Summary
Implement a patient authentication system that restricts `/appointments` to logged-in patients only. Includes registration with email verification, login with security lockout, password reset, and a patient portal for managing appointments. All UI in Romanian.

---

## Requirements Confirmed

| Aspect | Decision |
|--------|----------|
| Database | Separate `patients` table (not extending users) |
| Registration fields | Full name, email, phone, password (8 chars min) |
| Email verification | Required before login/booking |
| Password complexity | 8 characters minimum only |
| Login security | 3 failed attempts = 1 hour lockout |
| Password reset | Email-based, token valid 1 hour |
| Guest booking | Removed - only logged-in patients |
| Appointment linking | Add `patient_id` FK, auto-fill patient data |
| Patient dashboard | Full: view appointments, cancel anytime before |
| URL prefix | `/portal` |
| Language | Hardcoded Romanian |

---

## Implementation Steps

### Phase 1: Database Migrations

**1.1 Create `patients` table**
- File: `config/Migrations/20260114000001_CreatePatients.php`
- Fields: `id`, `full_name`, `email` (unique), `phone`, `password`, `email_verified_at`, `verification_token`, `password_reset_token`, `password_reset_expires`, `failed_login_attempts`, `locked_until`, `last_login_at`, `last_login_ip`, `is_active`, `created`, `modified`

**1.2 Add `patient_id` to appointments**
- File: `config/Migrations/20260114000002_AddPatientIdToAppointments.php`
- Add `patient_id` integer nullable with FK to patients

### Phase 2: Models

**2.1 Create Patient entity**
- File: `src/Model/Entity/Patient.php`
- Password hashing via `_setPassword()` mutator
- Virtual properties: `is_email_verified`, `is_locked`

**2.2 Create PatientsTable**
- File: `src/Model/Table/PatientsTable.php`
- Validation: full_name (required), email (unique), phone, password (min 8 chars)
- Association: `hasMany('Appointments')`
- Finders: `findByEmail`, `findByVerificationToken`, `findByPasswordResetToken`, `findActive`

**2.3 Update AppointmentsTable**
- File: `src/Model/Table/AppointmentsTable.php`
- Add: `belongsTo('Patients')`
- Add `patient_id` to accessible fields in entity

### Phase 3: Services

**3.1 Create PatientAuthService**
- File: `src/Service/PatientAuthService.php`
- Methods:
  - `isLoginAllowed()` - rate limiting check
  - `recordLoginAttempt()` - track attempts
  - `generateVerificationToken()` - for registration
  - `verifyEmail()` - validate token, set verified_at
  - `generatePasswordResetToken()` - for forgot password
  - `resetPassword()` - validate token, update password
  - `updateLastLogin()` - track login info
- Constants: `MAX_FAILED_ATTEMPTS = 3`, `LOCKOUT_DURATION = 3600`

**3.2 Create PatientMailer**
- File: `src/Mailer/PatientMailer.php`
- Methods: `verification()`, `passwordReset()`, `welcome()`

### Phase 4: Authentication Configuration

**4.1 Update Application.php**
- File: `src/Application.php`
- Modify `getAuthenticationService()` to detect path:
  - `/portal/*` or `/appointments/*` → Patient auth (Patients table, session key `PatientAuth`)
  - Other routes → Admin auth (Users table, existing)

### Phase 5: Routes

**5.1 Update routes.php**
- File: `config/routes.php`
- Add/update portal routes:
  ```
  /portal → Patients::portal (dashboard)
  /portal/login → Patients::login
  /portal/register → Patients::register
  /portal/logout → Patients::logout
  /portal/forgot-password → Patients::forgotPassword
  /portal/reset-password/{token} → Patients::resetPassword
  /portal/verify-email/{token} → Patients::verifyEmail
  /portal/appointments → Patients::appointments
  /portal/appointments/cancel/{id} → Patients::cancelAppointment (POST)
  /portal/profile → Patients::profile
  ```

### Phase 6: Controllers

**6.1 Create PatientsController**
- File: `src/Controller/PatientsController.php`
- Public actions: `login`, `register`, `logout`, `forgotPassword`, `resetPassword`, `verifyEmail`
- Protected actions: `portal`, `appointments`, `cancelAppointment`, `profile`
- Romanian flash messages

**6.2 Update AppointmentsController**
- File: `src/Controller/AppointmentsController.php`
- Remove from `allowUnauthenticated`: `index`, `book`
- In `beforeFilter`: check patient auth, redirect to `/portal/login` if not logged in
- In `book()`:
  - Remove CAPTCHA validation
  - Get patient from session
  - Auto-fill patient_name, patient_email, patient_phone
  - Set `patient_id` on appointment

### Phase 7: Templates (Romanian)

**7.1 Create portal layout**
- File: `templates/layout/portal.php`
- Navigation: Dashboard, Programările mele, Profil, Deconectare

**7.2 Create Patients templates**
- Directory: `templates/Patients/`
- Files:
  - `login.php` - "Autentificare", email/parola form
  - `register.php` - "Înregistrare", full form
  - `forgot_password.php` - "Am uitat parola"
  - `reset_password.php` - "Resetare parola"
  - `verify_email.php` - "Verificare email"
  - `portal.php` - Dashboard with appointment summary
  - `appointments.php` - Full history with cancel buttons
  - `profile.php` - View/edit profile

**7.3 Update Appointments templates**
- File: `templates/Appointments/index.php`
- Remove Step 4 (patient data) - now auto-filled
- Simplify to 4 steps: Specialitate → Medic → Data/Oră → Confirmare
- Show logged-in patient info (read-only)
- Remove CAPTCHA section

**7.4 Create email templates**
- Files:
  - `templates/email/html/patient_verification.php`
  - `templates/email/html/patient_password_reset.php`
  - `templates/email/html/patient_welcome.php`
  - (plus text/ versions)

### Phase 8: Testing

**8.1 Create fixtures**
- File: `tests/Fixture/PatientsFixture.php`

**8.2 Write tests**
- `tests/TestCase/Model/Table/PatientsTableTest.php`
- `tests/TestCase/Service/PatientAuthServiceTest.php`
- `tests/TestCase/Controller/PatientsControllerTest.php`
- Update `tests/TestCase/Controller/AppointmentsControllerTest.php`

---

## Critical Files to Modify

| File | Changes |
|------|---------|
| `src/Application.php` | Add dual authentication (patient vs admin) |
| `src/Controller/AppointmentsController.php` | Require patient auth, remove CAPTCHA, link patient_id |
| `config/routes.php` | Add complete portal routes |
| `templates/Appointments/index.php` | Remove Step 4, show patient info read-only |

## New Files to Create

| File | Purpose |
|------|---------|
| `config/Migrations/20260114000001_CreatePatients.php` | Patients table |
| `config/Migrations/20260114000002_AddPatientIdToAppointments.php` | FK to patients |
| `src/Model/Entity/Patient.php` | Patient entity with password hashing |
| `src/Model/Table/PatientsTable.php` | Patient model |
| `src/Service/PatientAuthService.php` | Auth logic (follows LoginSecurityService pattern) |
| `src/Mailer/PatientMailer.php` | Patient emails |
| `src/Controller/PatientsController.php` | Portal controller |
| `templates/layout/portal.php` | Portal layout |
| `templates/Patients/*.php` | 8 template files |
| `templates/email/html/patient_*.php` | 3 email templates |

---

## Verification Plan

1. **Run migrations**: `bin/cake migrations migrate`
2. **Test registration flow**:
   - Register at `/portal/register`
   - Check email received with verification link
   - Click link, verify account activated
3. **Test login flow**:
   - Login at `/portal/login`
   - Verify redirect to `/portal` dashboard
   - Test 3 failed logins → lockout
4. **Test password reset**:
   - Request reset at `/portal/forgot-password`
   - Check email with reset link
   - Reset password, verify can login
5. **Test appointment booking**:
   - Navigate to `/appointments`
   - Verify patient data auto-filled
   - Complete booking, verify `patient_id` set
6. **Test appointment management**:
   - View appointments at `/portal/appointments`
   - Cancel an appointment
   - Verify cancellation reflected
7. **Run test suite**: `composer check`

---

## Romanian UI Labels Reference

| English | Romanian |
|---------|----------|
| Login | Autentificare |
| Register | Înregistrare |
| Logout | Deconectare |
| Full name | Nume complet |
| Email | Adresă de email |
| Phone | Număr de telefon |
| Password | Parolă |
| Confirm password | Confirmă parola |
| Forgot password | Am uitat parola |
| Reset password | Resetare parolă |
| My appointments | Programările mele |
| Profile | Profil |
| Dashboard | Panou de control |
| Cancel | Anulează |
| Book appointment | Programare |
| Verify email | Verificare email |
