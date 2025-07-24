<?= $hospital['name'] ?>
========================================

✓ PROGRAMARE CONFIRMATĂ

Bună ziua <?= $appointment->patient_name ?>,

Programarea dumneavoastră a fost confirmată cu succes!

DETALIILE PROGRAMĂRII CONFIRMATE:
----------------------------------------
Data și ora: <?= $appointment->appointment_date->format('d.m.Y') ?> la <?= $appointment->appointment_time->format('H:i') ?>

<?php if (!empty($appointment->staff)): ?>
Doctor: <?= $appointment->staff->full_name ?>

<?php endif; ?>
<?php if (!empty($appointment->department)): ?>
Departament: <?= $appointment->department->name ?>

<?php endif; ?>
<?php if (!empty($appointment->service)): ?>
Serviciu: <?= $appointment->service->name ?>

<?php endif; ?>
Adresa: <?= $hospital['address'] ?>

INSTRUCȚIUNI IMPORTANTE:
----------------------------------------
• Vă rugăm să vă prezentați cu 15 minute înainte de ora programării
• Aveți la dumneavoastră actul de identitate
• Dacă aveți investigații medicale anterioare, vă rugăm să le aduceți
• În caz de întârziere, vă rugăm să ne anunțați telefonic

Pentru modificări sau anulări, vă rugăm să ne contactați cu cel puțin 24 de ore înainte la <?= $hospital['phone'] ?>.

Vă mulțumim pentru încrederea acordată!

========================================
© <?= date('Y') ?> <?= $hospital['name'] ?>