<?= $hospital['name'] ?>
========================================

<?= $token ? 'CONFIRMARE PROGRAMARE NECESARĂ' : 'PROGRAMARE CONFIRMATĂ' ?>

Bună ziua <?= $appointment->patient_name ?>,

<?php if ($token) : ?>
Ați făcut o programare la <?= $hospital['name'] ?>. Pentru a finaliza procesul de programare, vă rugăm să confirmați programarea accesând link-ul de mai jos.
<?php else : ?>
Programarea dumneavoastră la <?= $hospital['name'] ?> a fost confirmată cu succes.
<?php endif; ?>

DETALIILE PROGRAMĂRII:
----------------------------------------
Pacient: <?= $appointment->patient_name ?>

Telefon: <?= $appointment->patient_phone ?>

Email: <?= $appointment->patient_email ?>

Data și ora: <?= $appointment->appointment_date->format('d.m.Y') ?> la <?= $appointment->appointment_time->format('H:i') ?>

<?php if (!empty($appointment->doctor)) : ?>
Doctor: <?= $appointment->doctor->first_name . ' ' . $appointment->doctor->last_name ?>

<?php endif; ?>
<?php if (!empty($appointment->doctor->department)) : ?>
Departament: <?= $appointment->doctor->department->name ?>

<?php endif; ?>
<?php if (!empty($appointment->service)) : ?>
Serviciu: <?= $appointment->service->name ?>

<?php endif; ?>
<?php if (!empty($appointment->notes)) : ?>
Observații: <?= $appointment->notes ?>

<?php endif; ?>

<?php if ($token) : ?>
LINK CONFIRMARE:
    <?= $confirmationUrl ?>

⚠️  IMPORTANT: Această programare trebuie confirmată în termen de 24 de ore. 
Dacă nu confirmați programarea în acest interval, aceasta va fi anulată automat.

<?php endif; ?>

CONTACT:
----------------------------------------
Pentru modificări sau anulări:

Telefon: <?= $hospital['phone'] ?>
Email: <?= $hospital['email'] ?>
Adresa: <?= $hospital['address'] ?>

========================================
© <?= date('Y') ?> <?= $hospital['name'] ?>

Acest email a fost generat automat. 
Vă rugăm să nu răspundeți la acest mesaj.