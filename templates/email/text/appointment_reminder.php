<?= $hospital['name'] ?>
========================================

REAMINTIRE PROGRAMARE

Bună ziua <?= $appointment->patient_name ?>,

⏰ Programarea dumneavoastră este programată în <?= $hoursUntil ?> ore!

DETALIILE PROGRAMĂRII:
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

📍 Vă rugăm să vă prezentați cu 15 minute înainte de ora programării.

Pentru modificări sau anulări, vă rugăm să ne contactați la <?= $hospital['phone'] ?>.

========================================
© <?= date('Y') ?> <?= $hospital['name'] ?>