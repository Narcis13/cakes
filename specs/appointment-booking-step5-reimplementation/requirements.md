# Requirements: Reimplementare Pas Final Flux Programare Online

## Summary

Reimplementarea completă a pașilor 4 și 5 din fluxul de programare online în portalul pacienților. Funcționalitatea actuală are defecte critice care împiedică salvarea programărilor, trimiterea emailurilor și afișarea în portalul pacientului.

## Problem Statement

### Probleme Identificate

1. **Salvarea eșuează** - Programările nu se salvează în baza de date
2. **Email nu se trimite** - Pacientul nu primește email de confirmare
3. **Nu apare în "Programările mele"** - Programările nu apar în portalul pacientului
4. **Stil vizual inconsistent** - Steps 4 și 5 nu respectă stilul vizual (VSS) al Steps 1-3

### Cauza Principală

În `/src/Model/Entity/Appointment.php`, câmpurile `status` și `confirmation_token` sunt setate ca non-mass-assignable (`false`), dar controller-ul încearcă să le seteze prin `patchEntity()` care le ignoră. Rezultatul: entitatea nu primește valorile necesare → validarea eșuează → `save()` returnează `false`.

## Requirements

### Functional Requirements

1. **FR-01**: Programarea trebuie să se salveze corect în baza de date cu toate câmpurile populate
2. **FR-02**: Emailul de confirmare trebuie trimis pacientului cu detaliile programării
3. **FR-03**: Programarea nouă trebuie să apară în lista "Programările mele" din portalul pacientului
4. **FR-04**: Status-ul inițial al programării trebuie să fie "pending"
5. **FR-05**: Un token de confirmare unic trebuie generat pentru fiecare programare

### Non-Functional Requirements

1. **NFR-01**: Steps 4 și 5 trebuie să respecte exact stilul vizual (VSS) al Steps 1-3
2. **NFR-02**: Interfața trebuie să fie responsive (mobile-friendly)
3. **NFR-03**: Logging detaliat pentru debugging în caz de erori

## Acceptance Criteria

### AC-01: Salvare Corectă
- [ ] Când utilizatorul confirmă programarea, aceasta se salvează în DB
- [ ] Câmpurile `status`, `confirmation_token`, `patient_id` sunt populate corect
- [ ] Nu apar erori de validare

### AC-02: Email Confirmare
- [ ] Emailul se trimite la adresa pacientului
- [ ] Emailul conține toate detaliile programării (medic, serviciu, data, ora)
- [ ] Emailul conține link-ul de confirmare funcțional

### AC-03: Afișare în Portal
- [ ] Programarea nouă apare imediat în "/portal/appointments"
- [ ] Status-ul "În așteptare" este afișat corect
- [ ] Datele afișate corespund cu cele introduse

### AC-04: Stil Vizual Consistent
- [ ] Step 4 folosește structura `.step-header` cu icon și descriere
- [ ] Step 5 folosește carduri stilizate pentru rezumat
- [ ] Design-ul este consistent cu Steps 1-3

## Dependencies

- CakePHP 5.1 framework
- AppointmentMailer (existent)
- AvailabilityService (existent)
- Font Awesome icons

## Related Features

- Flux programare online (Steps 1-3) - funcțional
- Portal pacienți - funcțional
- Sistem email - configurat

## Technical Constraints

- Nu se modifică entitatea Appointment (mass-assignment protection intenționat)
- Se păstrează compatibilitatea cu codul existent
- Se folosește stilul CSS existent ca bază
