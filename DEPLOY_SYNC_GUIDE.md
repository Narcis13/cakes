# Ghid Sincronizare Producție

**Data generare:** 30 Ianuarie 2026
**Bază de comparație:** Commit `02903d1` (deployment din 11.01.2026, 20:08)
**Commit curent:** `efa75c6` (varianta beta din 23.01.2026)

---

## Rezumat Modificări

| Categorie | Număr fișiere |
|-----------|---------------|
| Fișiere PHP noi | 34 |
| Fișiere PHP modificate | 39 |
| Fișiere PHP șterse | 1 |
| Fișiere webroot (CSS/img) | 2 |
| **Total fișiere de sincronizat** | **75** |

---

## 1. Baza de Date (RULEAZĂ PRIMUL!)

Înainte de a copia fișierele, rulează scriptul SQL pentru migrări:

```bash
mysql -u username -p database_name < migration_sync_production.sql
```

Sau importă `migration_sync_production.sql` prin phpMyAdmin.

---

## 2. Fișiere PHP NOI (de creat pe server)

### Controllers
```
src/Controller/PatientsController.php
```

### Models
```
src/Model/Entity/Patient.php
src/Model/Table/PatientsTable.php
```

### Services (folder nou!)
```
src/Service/AppointmentEmailService.php
src/Service/PatientAuthService.php
src/Service/PatientEmailService.php
src/Service/Email/AbstractEmailService.php
src/Service/Email/EmailTransportFactory.php
src/Service/Email/EmailTransportInterface.php
src/Service/Email/ResendTransport.php
src/Service/Email/SmtpTransport.php
```

### Mailers
```
src/Mailer/PatientMailer.php
```

### Templates - Admin
```
templates/Admin/Appointments/report.php
templates/Admin/Settings/email.php
```

### Templates - Portal Pacienți (folder nou!)
```
templates/Patients/appointments.php
templates/Patients/forgot_password.php
templates/Patients/login.php
templates/Patients/portal.php
templates/Patients/profile.php
templates/Patients/register.php
templates/Patients/reset_password.php
templates/Patients/verify_email.php
```

### Templates - Email Pacienți (fișiere noi)
```
templates/email/html/patient_password_reset.php
templates/email/html/patient_verification.php
templates/email/html/patient_welcome.php
templates/email/text/patient_password_reset.php
templates/email/text/patient_verification.php
templates/email/text/patient_welcome.php
```

### Templates - Layouts & Elements
```
templates/layout/portal.php
templates/layout/print.php
templates/element/specialty_icon.php
```

### Migrări (opțional - dacă vrei păstrarea lor)
```
config/Migrations/20260114000001_CreatePatients.php
config/Migrations/20260114000002_AddPatientIdToAppointments.php
config/Migrations/20260121144322_AddEmailPlatformSettings.php
```

---

## 3. Fișiere PHP MODIFICATE (de suprascris pe server)

### Config
```
config/app.php
config/routes.php
```

### Core Application
```
src/Application.php
src/Controller/AppController.php
src/Middleware/SecurityHeadersMiddleware.php
```

### Controllers - Admin
```
src/Controller/Admin/AppointmentsController.php
src/Controller/Admin/SettingsController.php
src/Controller/Admin/StaffController.php
src/Controller/Admin/UsersController.php
```

### Controllers - Public
```
src/Controller/AppointmentsController.php
```

### Models
```
src/Model/Entity/Appointment.php
src/Model/Entity/Staff.php
src/Model/Table/AppointmentsTable.php
```

### Services
```
src/Service/AvailabilityService.php
```

### Mailers
```
src/Mailer/AppointmentMailer.php
```

### Templates - Admin
```
templates/Admin/Appointments/index.php
templates/Admin/Appointments/view.php
templates/Admin/DoctorSchedules/index.php
templates/Admin/DoctorSchedules/view.php
templates/Admin/ScheduleExceptions/index.php
templates/Admin/ScheduleExceptions/view.php
templates/Admin/Services/add.php
templates/Admin/Services/edit.php
templates/Admin/Settings/index.php
templates/Admin/Staff/index.php
```

### Templates - Public
```
templates/Appointments/index.php
templates/Appointments/success.php
```

### Templates - Flash Messages
```
templates/element/flash/default.php
templates/element/flash/error.php
templates/element/flash/info.php
templates/element/flash/success.php
templates/element/flash/warning.php
```

### Templates - Email
```
templates/email/html/appointment_admin_notification.php
templates/email/html/appointment_cancellation.php
templates/email/html/appointment_confirmation.php
templates/email/html/appointment_confirmed.php
templates/email/html/appointment_reminder.php
templates/email/text/appointment_admin_notification.php
templates/email/text/appointment_confirmation.php
```

---

## 4. Fișiere ȘTERSE (de șters de pe server)

```
config/app_local.example.php
```

---

## 5. Fișiere Webroot (CSS/Imagini)

```
webroot/css/appointments.css
webroot/img/default-doctor.png
```

---

## 6. Foldere NOI de creat pe server

```bash
# Creează folderele înainte de copiere
mkdir -p src/Service/Email
mkdir -p templates/Patients
```

---

## 7. Comandă rapidă pentru copiere (rsync)

Dacă ai acces SSH, poți folosi rsync pentru sincronizare selectivă:

```bash
# De pe mașina locală către server
rsync -avz --progress \
  src/Controller/ user@server:/path/to/app/src/Controller/

rsync -avz --progress \
  src/Model/ user@server:/path/to/app/src/Model/

rsync -avz --progress \
  src/Service/ user@server:/path/to/app/src/Service/

rsync -avz --progress \
  src/Mailer/ user@server:/path/to/app/src/Mailer/

rsync -avz --progress \
  src/Middleware/ user@server:/path/to/app/src/Middleware/

rsync -avz --progress \
  templates/ user@server:/path/to/app/templates/

rsync -avz --progress \
  config/app.php config/routes.php user@server:/path/to/app/config/

rsync -avz --progress \
  webroot/css/appointments.css user@server:/path/to/app/webroot/css/

rsync -avz --progress \
  webroot/img/default-doctor.png user@server:/path/to/app/webroot/img/

rsync -avz --progress \
  src/Application.php user@server:/path/to/app/src/
```

---

## 8. Ordine de Deployment Recomandată

1. **Backup** - Fă backup la baza de date și fișierele de pe server
2. **Mod Mentenanță** - Activează pagina de mentenanță (opțional)
3. **Baza de date** - Rulează `migration_sync_production.sql`
4. **Foldere noi** - Creează `src/Service/Email` și `templates/Patients`
5. **Fișiere noi** - Copiază toate fișierele noi
6. **Fișiere modificate** - Suprascrie fișierele existente
7. **Fișiere șterse** - Șterge `config/app_local.example.php`
8. **Cache** - Șterge cache-ul CakePHP:
   ```bash
   bin/cake cache clear_all
   ```
9. **Testare** - Verifică funcționalitățile noi
10. **Dezactivare mentenanță** - Reactivează site-ul

---

## 9. Funcționalități Noi Adăugate

| Feature | Descriere |
|---------|-----------|
| **Portal Pacienți** | Autentificare, înregistrare, resetare parolă |
| **Programări Online** | Flux complet de programare pentru pacienți autentificați |
| **Sistem Email SMTP** | Suport pentru SMTP pe lângă Resend API |
| **Setări Email Admin** | Pagină nouă pentru configurare email în admin |
| **Rapoarte Programări** | Pagină nouă de raportare în admin |

---

## 10. După Deployment - Verificări

- [ ] Verifică login admin funcționează
- [ ] Verifică pagina de programări publică
- [ ] Verifică trimitere email (test din admin)
- [ ] Verifică înregistrare pacient nou
- [ ] Verifică login pacient
- [ ] Verifică programare ca pacient autentificat
- [ ] Verifică raportul de programări în admin
