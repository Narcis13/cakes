# Mod MENTENANȚĂ portal pacienți — ghid de deploy

Funcționalitate: din panoul de administrare se poate trece portalul de programări
online în modul **MENTENANȚĂ** (pacienții văd un mesaj de informare în loc de portal)
și se poate reveni la modul normal de funcționare.

Toate fișierele implicate sunt PHP. **Nu este necesară nicio migrare**, nicio
modificare în `config/` și nicio actualizare de `vendor/`.

---

## 1. Fișiere NOI (de urcat)

| Cale | Observație |
|---|---|
| `src/Service/PortalMaintenanceService.php` | citește/scrie setările în `site_settings` |
| `src/Controller/PortalMaintenanceTrait.php` | garda care oprește requestul în `beforeFilter()` |
| `templates/Admin/Settings/portal_maintenance.php` | pagina de administrare |
| `templates/Portal/maintenance.php` | mesajul afișat pacienților — **directorul `templates/Portal/` trebuie creat** |

## 2. Fișiere MODIFICATE (de suprascris)

| Cale | Ce s-a schimbat |
|---|---|
| `src/Controller/Admin/SettingsController.php` | +69 linii: acțiunile `portalMaintenance()` și `togglePortalMaintenance()` |
| `src/Controller/PatientsController.php` | +8 linii: `use PortalMaintenanceTrait` + apel în `beforeFilter()` |
| `src/Controller/AppointmentsController.php` | +8 linii: idem |
| `templates/layout/admin.php` | +7 linii: item nou în meniul lateral (secțiunea Sistem) |
| `templates/Admin/Settings/index.php` | +1 linie: buton „Mentenanță Portal" |
| `templates/Admin/Settings/email.php` | +5 linii: link în navigarea laterală |

**Total: 10 fișiere.**

---

## 3. Ordinea de urcare

Urcă **întâi cele 4 fișiere noi**, apoi cele 6 modificate.

Altfel, în intervalul dintre cele două seturi, `PatientsController` ar referi un
trait inexistent și portalul ar da eroare fatală câteva secunde.

## 4. După copiere, pe server

```bash
bin/cake cache clear_all
```

> **Atenție la OPcache:** în funcție de configurația serverului
> (`opcache.validate_timestamps`), poate fi nevoie de un restart PHP-FPM
> ca modificările să fie preluate.

## 5. Baza de date

Nu trebuie rulat nimic. Cele 4 setări se creează singure în tabela `site_settings`
la prima salvare din admin:

| Cheie | Rol |
|---|---|
| `PORTAL_MAINTENANCE_MODE` | `1` = mentenanță activă, `0` = funcționare normală |
| `PORTAL_MAINTENANCE_TITLE` | titlul afișat pe pagina de mentenanță |
| `PORTAL_MAINTENANCE_MESSAGE` | mesajul afișat pacienților |
| `PORTAL_MAINTENANCE_ETA` | estimare revenire (opțional) |

Până la prima salvare portalul funcționează normal (implicit modul mentenanță e oprit).

---

## 6. Cum se folosește

Admin → **Setări → Mentenanță Portal** (`/smupa1881/settings/portal-maintenance`):

- comutator ON/OFF plus buton rapid „Trece în mentenanță" / „Revino la modul normal" (cu confirmare)
- titlu, mesaj și estimare revenire configurabile
- badge de status: **MENTENANȚĂ** (roșu) / **FUNCȚIONAL** (verde)

### Ce se blochează cât timp mentenanța e activă

- `/portal/*` — autentificare, înregistrare, cont pacient, programările pacientului, profil
- `/appointments/*` — formularul de programare și endpoint-urile AJAX de disponibilitate

Pacienții primesc pagina de mentenanță cu HTTP **503**. Apelurile AJAX primesc `503`
cu JSON `{"maintenance": true, ...}` în loc de HTML.

Singura excepție este `logout`, ca un pacient deja autentificat să poată ieși din cont.

Restul site-ului public (știri, pagini, contact) rămâne neatins — mentenanța se
aplică doar portalului de programări.

### Bypass pentru admin/staff

Dacă ești autentificat în panoul de administrare, vezi portalul real chiar și în
mentenanță, ca să poți verifica înainte de redeschidere. În acest caz apare un mesaj
de avertizare pe paginile portalului, ca să nu pară că setarea nu a funcționat.

## 7. Testare după deploy

1. Activează mentenanța din admin.
2. Deschide `/portal` într-o fereastră privată (fără sesiune de admin) — trebuie să apară mesajul de mentenanță.
3. Dezactivează mentenanța și verifică faptul că programarea online funcționează din nou.
