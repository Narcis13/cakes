# Ghid Complet de Deployment - SMU Pitești

## Site-ul Spitalului Municipal de Urgență Pitești
**Domeniu:** smupitesti.org
**Framework:** CakePHP 5.1
**Mediu țintă:** Shared Hosting cu cPanel

---

## Cuprins

1. [Cerințe și Verificări Pre-Deployment](#1-cerinte-si-verificari-pre-deployment)
2. [Pregătirea Fișierelor Locale](#2-pregatirea-fisierelor-locale)
3. [Backup Site Vechi](#3-backup-site-vechi)
4. [Configurare Bază de Date](#4-configurare-baza-de-date)
5. [Upload Fișiere via FTP](#5-upload-fisiere-via-ftp)
6. [Crearea Fișierelor de Configurare](#6-crearea-fisierelor-de-configurare)
7. [Setare Permisiuni Directoare](#7-setare-permisiuni-directoare)
8. [Verificare .htaccess](#8-verificare-htaccess)
9. [Ștergere Cache](#9-stergere-cache)
10. [Verificare și Testare](#10-verificare-si-testare)
11. [Troubleshooting](#11-troubleshooting)

---

## 1. Cerințe și Verificări Pre-Deployment

### Cerințe Hosting

| Cerință | Minim | Recomandat |
|---------|-------|------------|
| PHP | 8.1 | 8.2+ |
| MySQL | 5.7 | 8.0+ |
| mod_rewrite | Activat | Activat |
| Memory Limit | 128MB | 256MB |

### Verificări în cPanel

1. **PHP Version** - din cPanel → "MultiPHP Manager" sau "PHP Selector"
   - Selectează PHP 8.1 sau 8.2 pentru domeniu

2. **PHP Extensions** - verifică că sunt active:
   - `intl`
   - `mbstring`
   - `simplexml`
   - `pdo_mysql`
   - `fileinfo`

3. **mod_rewrite** - verifică că Apache mod_rewrite este activ
   - De obicei este activ implicit pe cPanel

---

## 2. Pregătirea Fișierelor Locale

### Fișiere și Foldere de EXCLUS de la upload

**NU uploada următoarele:**
```
/.git/                    # Istoricul Git (mare și inutil)
/tests/                   # Teste unitare (doar dezvoltare)
/config/app_local.php     # Configurare locală (vei crea altul)
/config/.env              # Variabile locale (vei crea altul)
/logs/*                   # Fișiere log locale
/tmp/*                    # Fișiere temporare locale
.gitignore
.phpunit.cache
tests.sqlite
*.DS_Store
.idea/
.vscode/
```

### Fișiere și Foldere OBLIGATORII pentru upload

**TREBUIE să uploadezi:**
```
/bin/                     # Comenzi CakePHP
/config/                  # Configurări (fără app_local.php și .env)
/plugins/                 # Plugin-uri
/resources/               # Resurse
/src/                     # Codul sursă aplicație
/templates/               # Template-uri
/vendor/                  # Dependențe Composer (OBLIGATORIU!)
/webroot/                 # Fișiere publice
/.htaccess                # Regulă redirecționare root
composer.json
composer.lock
```

### Structura Finală pentru Upload

```
public_html/
├── bin/
├── config/
│   ├── app.php
│   ├── app_local.example.php
│   ├── bootstrap.php
│   ├── Migrations/
│   ├── paths.php
│   ├── requirements.php
│   ├── routes.php
│   └── Seeds/
├── plugins/
├── resources/
├── src/
├── templates/
├── vendor/              ← FOARTE IMPORTANT!
├── webroot/
│   ├── css/
│   ├── font/
│   ├── img/
│   ├── js/
│   ├── files/
│   ├── .htaccess
│   └── index.php
├── logs/                ← Creezi gol pe server
├── tmp/                 ← Creezi gol pe server
├── .htaccess
├── composer.json
└── composer.lock
```

---

## 3. Backup Site Vechi

### Opțional dar Recomandat

1. **În cPanel** → File Manager → navigare la `public_html`
2. Selectează toate fișierele și folderele
3. Click dreapta → **Compress** → ZIP
4. Denumește arhiva: `backup_site_vechi_[data].zip`
5. Descarcă arhiva pe calculatorul local
6. **După backup**, șterge conținutul din `public_html` (dar păstrează arhiva dacă ai salvat-o în altă parte)

---

## 4. Configurare Bază de Date

### Pas 4.1: Creează Baza de Date în cPanel

1. **cPanel** → **MySQL Databases**
2. **Create New Database:**
   - Nume: `smupitesti` (sau ce prefix pune hostingul, ex: `username_smupitesti`)
   - Click "Create Database"

3. **Create New User:**
   - Username: `smuuser` (sau similar)
   - Password: **Generează parolă puternică** (salvează-o!)
   - Click "Create User"

4. **Add User to Database:**
   - Selectează userul creat
   - Selectează baza de date creată
   - La "Privileges" selectează **ALL PRIVILEGES**
   - Click "Add"

### Pas 4.2: Import Baza de Date

1. **cPanel** → **phpMyAdmin**
2. Din stânga, selectează baza de date creată
3. Click tab **Import**
4. Click "Choose File" și selectează fișierul `.sql` exportat
5. **Encoding:** UTF-8
6. Click **Go**
7. Așteaptă finalizarea importului

### Notează Credentialele

```
Host:     localhost (de obicei)
Database: [prefix_]smupitesti
Username: [prefix_]smuuser
Password: [parola generată]
Port:     3306 (implicit)
```

---

## 5. Upload Fișiere via FTP

### Configurare Client FTP

**Recomandare:** FileZilla, Cyberduck sau WinSCP

**Date conectare FTP** (din cPanel → FTP Accounts):
```
Host: smupitesti.org sau ftp.smupitesti.org
Port: 21
Protocol: FTP sau FTPS
Username: [userul FTP]
Password: [parola FTP]
```

### Procedura de Upload

1. **Conectează-te** la serverul FTP
2. **Navighează** în folderul `public_html`
3. **Șterge** tot conținutul vechi (dacă nu ai făcut backup și șters înainte)
4. **Începe upload-ul** - ordinea recomandată:

   **Prima rundă (fișiere mici):**
   - `.htaccess` (din root)
   - `composer.json`
   - `composer.lock`

   **A doua rundă (foldere mici):**
   - `/bin/`
   - `/config/` (fără app_local.php și .env)
   - `/plugins/`
   - `/resources/`

   **A treia rundă (foldere mari):**
   - `/src/`
   - `/templates/`
   - `/webroot/`

   **A patra rundă (cel mai mare):**
   - `/vendor/` ← Acest folder poate dura mult (are multe fișiere mici)

5. **Creează directoare goale:**
   - `/logs/`
   - `/tmp/`
   - `/tmp/cache/`
   - `/tmp/cache/models/`
   - `/tmp/cache/persistent/`
   - `/tmp/cache/views/`
   - `/tmp/sessions/`
   - `/webroot/files/uploads/` (dacă nu există)

### Sfat pentru Upload vendor/

Folderul `vendor/` conține mii de fișiere mici. Pentru upload mai rapid:

1. **Local**, arhivează `vendor/` într-un ZIP:
   ```
   vendor.zip
   ```
2. Uploadează `vendor.zip` în `public_html`
3. În cPanel → File Manager → selectează `vendor.zip`
4. Click **Extract**
5. După extragere, șterge `vendor.zip`

---

## 6. Crearea Fișierelor de Configurare

### Pas 6.1: Generează SECURITY_SALT

Accesează: https://www.random.org/strings/

Setări:
- Generate 1 string
- Each string should be 64 characters long
- Only letters (a-f) and digits (0-9) - pentru format hex

**Salvează** string-ul generat. Exemplu:
```
a7b3c9d2e1f0123456789abcdef0123456789abcdef0123456789abcdef01234
```

### Pas 6.2: Creează config/.env

În cPanel → File Manager → navighează la `public_html/config/`

1. Click **+ File** (nou fișier)
2. Denumire: `.env`
3. Click "Create New File"
4. Selectează fișierul → Click **Edit**
5. Adaugă conținutul:

```env
# Security Configuration - PRODUCȚIE
SECURITY_SALT=INLOCUIESTE_CU_SALT_GENERAT_64_CARACTERE

# TinyMCE API Key (pentru editorul vizual din admin)
TINYMCE_API_KEY=7o75j136zek0t4fvcnljgjbo90am8amip5gg7fxksexv4trz

# Debug Mode - OBLIGATORIU false în producție!
DEBUG=false
```

6. Click **Save Changes**

### Pas 6.3: Creează config/app_local.php

În cPanel → File Manager → navighează la `public_html/config/`

1. Click **+ File**
2. Denumire: `app_local.php`
3. Click "Create New File"
4. Selectează fișierul → Click **Edit**
5. Adaugă conținutul următor:

```php
<?php
/**
 * Configurare Locală - SMU Pitești Producție
 *
 * ATENȚIE: Acest fișier conține date sensibile!
 * NU îl adăuga în Git sau orice sistem de versionare.
 */
return [
    /*
     * Debug Mode - OBLIGATORIU false în producție
     */
    'debug' => filter_var(env('DEBUG', false), FILTER_VALIDATE_BOOLEAN),

    /*
     * Security Salt - OBLIGATORIU unic pentru fiecare instalare
     */
    'Security' => [
        'salt' => env('SECURITY_SALT'),
    ],

    /*
     * API Keys - pentru servicii terțe
     */
    'ApiKeys' => [
        'tinymce' => env('TINYMCE_API_KEY'),
        'resend' => null,  // Email dezactivat
    ],

    /*
     * Conexiune Bază de Date - MODIFICĂ CU DATELE TALE!
     */
    'Datasources' => [
        'default' => [
            'host' => 'localhost',
            'port' => '3306',
            'username' => 'INLOCUIESTE_CU_USERNAME_DB',   // ex: smupitesti_smuuser
            'password' => 'INLOCUIESTE_CU_PAROLA_DB',     // parola de la pas 4.1
            'database' => 'INLOCUIESTE_CU_NUME_DB',       // ex: smupitesti_smupitesti
        ],
    ],

    /*
     * Email - DEZACTIVAT (nu sunt necesare funcționalități email)
     */
    'EmailTransport' => [
        'default' => [
            'className' => 'Smtp',
            'host' => 'localhost',
            'port' => 25,
            'username' => null,
            'password' => null,
            'tls' => false,
        ],
    ],

    'Email' => [
        'default' => [
            'transport' => 'default',
            'from' => ['noreply@smupitesti.org' => 'SMU Pitești'],
            'charset' => 'utf-8',
        ],
    ],

    /*
     * Configurare Programări
     */
    'Appointments' => [
        'min_advance_hours' => 1,
        'max_advance_days' => 90,
        'slot_interval' => 30,
        'default_start_time' => '09:00:00',
        'default_end_time' => '17:00:00',
        'default_buffer_minutes' => 0,
        'confirmation_token_expiry' => 24,
        'default_appointment_status' => 'pending',
        'allow_weekend_appointments' => false,
        'business_hours' => [
            'start' => '08:00',
            'end' => '18:00'
        ],
        'rate_limit' => [
            'attempts' => 10,
            'window' => 3600
        ]
    ],

    /*
     * Date Spital
     */
    'Hospital' => [
        'name' => 'Spitalul Municipal de Urgență Pitești',
        'phone' => '0248 XXX XXX',
        'address' => 'Str. Victoriei, Nr. X, Pitești',
        'email' => 'contact@smupitesti.org'
    ],
];
```

6. **IMPORTANT:** Înlocuiește valorile marcate cu `INLOCUIESTE_CU_...` cu datele reale!
7. Click **Save Changes**

---

## 7. Setare Permisiuni Directoare

### Via File Manager în cPanel

1. **cPanel** → **File Manager**
2. Navighează la `public_html`
3. Pentru fiecare director de mai jos, click dreapta → **Change Permissions**

### Directoare cu Permisiuni Speciale

| Director | Permisiune | Notă |
|----------|------------|------|
| `/logs/` | 755 sau 775 | Fișiere log |
| `/tmp/` | 755 sau 775 | Cache și sesiuni |
| `/tmp/cache/` | 755 sau 775 | Cache aplicație |
| `/tmp/cache/models/` | 755 sau 775 | Cache modele |
| `/tmp/cache/persistent/` | 755 sau 775 | Cache persistent |
| `/tmp/cache/views/` | 755 sau 775 | Cache view-uri |
| `/tmp/sessions/` | 755 sau 775 | Date sesiuni |
| `/webroot/files/` | 755 | Fișiere uploadate |
| `/webroot/files/uploads/` | 755 sau 775 | Upload utilizatori |

### Cum Setezi Permisiunile

1. Click dreapta pe director → **Change Permissions**
2. Bifează conform permisiunii dorite:

   **Pentru 755:**
   - Owner: Read, Write, Execute (rwx)
   - Group: Read, Execute (r-x)
   - World: Read, Execute (r-x)

   **Pentru 775:**
   - Owner: Read, Write, Execute (rwx)
   - Group: Read, Write, Execute (rwx)
   - World: Read, Execute (r-x)

3. Click **Change Permissions**

---

## 8. Verificare .htaccess

### Verifică Fișierul Root .htaccess

Calea: `public_html/.htaccess`

Conținut necesar:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine on
    RewriteRule    ^(\.well-known/.*)$ $1 [L]
    RewriteRule    ^$    webroot/    [L]
    RewriteRule    (.*) webroot/$1    [L]
</IfModule>
```

### Verifică Fișierul webroot/.htaccess

Calea: `public_html/webroot/.htaccess`

Conținut necesar:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Dacă URL-urile Nu Funcționează

Dacă primești erori 404 sau 500 pentru orice pagină în afară de index:

1. Verifică că mod_rewrite este activ (întreabă hostingul)
2. În cPanel → PHP Selector → verifică "allow_url_fopen" este ON
3. Poți adăuga în `.htaccess` principal:
   ```apache
   Options +FollowSymLinks
   ```

---

## 9. Ștergere Cache

### Prima Dată După Deployment

1. **cPanel** → **File Manager**
2. Navighează la `public_html/tmp/cache/`
3. Intră în fiecare subdirector și **șterge toate fișierele** (nu directoarele):
   - `/tmp/cache/models/` - șterge tot conținutul
   - `/tmp/cache/persistent/` - șterge tot conținutul
   - `/tmp/cache/views/` - șterge tot conținutul

### Când Trebuie Șters Cache-ul

- După orice modificare în fișierele de configurare
- După modificări în baza de date (structură)
- Când observi comportament ciudat al site-ului

---

## 10. Verificare și Testare

### Checklist Verificare Inițială

Deschide browserul și verifică:

| Test | URL | Rezultat Așteptat |
|------|-----|-------------------|
| Homepage | https://smupitesti.org | Pagina principală se încarcă |
| Admin Login | https://smupitesti.org/smupa1881 | Pagina de login admin |
| Departamente | https://smupitesti.org/departments | Lista departamentelor |
| Servicii | https://smupitesti.org/services | Lista serviciilor |
| Contact | https://smupitesti.org/contact | Pagina de contact |

### Test Admin Panel

1. Accesează: `https://smupitesti.org/smupa1881`
2. Loghează-te cu credențialele admin
3. Verifică că poți:
   - [ ] Vedea dashboard-ul
   - [ ] Naviga prin meniuri
   - [ ] Edita o pagină (test salvare)
   - [ ] Uploada un fișier (test permisiuni)

### Test Fișiere Statice

Verifică că se încarcă:
- CSS (stiluri aplicate corect)
- JavaScript (funcționalități interactive)
- Imagini (se afișează corect)

---

## 11. Troubleshooting

### Eroare 500 - Internal Server Error

**Cauze posibile:**

1. **Fișier .htaccess incorect**
   - Verifică sintaxa în ambele fișiere .htaccess
   - Temporar, poți redenumi .htaccess în .htaccess.bak pentru test

2. **PHP Version**
   - Verifică că PHP 8.1+ este selectat în cPanel

3. **Permisiuni incorecte**
   - Verifică că directoarele tmp/ și logs/ au permisiuni de scriere

4. **Fișier app_local.php incorect**
   - Verifică sintaxa PHP (fără erori)
   - Verifică că nu ai caractere speciale în parolă neescapate

### Eroare 404 - Page Not Found

**Cauze posibile:**

1. **mod_rewrite dezactivat**
   - Contactează suportul hostingului

2. **.htaccess nu funcționează**
   - Verifică că AllowOverride este setat (de obicei e OK pe cPanel)

3. **Fișiere lipsă**
   - Verifică că toate fișierele s-au uploadat corect

### Eroare "Database connection failed"

**Cauze posibile:**

1. **Credentiale greșite**
   - Verifică username, password, database name în app_local.php
   - Username-ul include de obicei prefixul contului

2. **Host greșit**
   - De obicei e `localhost`, dar verifică cu hostingul

3. **Baza de date nu există**
   - Verifică în cPanel → MySQL Databases

### Pagina se încarcă goală (blank)

**Cauze posibile:**

1. **DEBUG mode dezactivat și erori**
   - Temporar, setează `DEBUG=true` în .env
   - Verifică logs/error.log pentru detalii

2. **Lipsă vendor/**
   - Verifică că folderul vendor/ există și are conținut

### Fișierele CSS/JS nu se încarcă

**Cauze posibile:**

1. **Căi greșite**
   - Verifică că folderul webroot/ este la locul potrivit

2. **Permisiuni**
   - Verifică permisiunile pe webroot/css/, webroot/js/

### Upload fișiere nu funcționează

**Cauze posibile:**

1. **Permisiuni insuficiente**
   - Setează 775 pe webroot/files/uploads/

2. **PHP upload_max_filesize**
   - În cPanel → PHP Selector → setează upload_max_filesize și post_max_size

---

## Verificare Finală

După ce totul funcționează:

1. [ ] Setează `DEBUG=false` în .env (dacă l-ai schimbat pentru debug)
2. [ ] Verifică că logs/error.log nu crește rapid
3. [ ] Testează toate paginile publice
4. [ ] Testează funcționalitățile admin

---

## Suport

Dacă întâmpini probleme:

1. Verifică secțiunea Troubleshooting de mai sus
2. Consultă logs/error.log pentru detalii
3. Verifică documentația CakePHP: https://book.cakephp.org/5/en/index.html

---

*Document creat pentru deployment-ul SMU Pitești - CakePHP 5.1*
