<?= $hospital['name'] ?>
========================================

PROGRAMARE ANULATĂ

Bună ziua <?= $appointment->patient_name ?>,

Ne pare rău să vă informăm că programarea dumneavoastră a fost anulată.

PROGRAMAREA ANULATĂ:
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

<?php if ($reason): ?>
MOTIVUL ANULĂRII:
<?= $reason ?>

<?php endif; ?>

Pentru a programa o nouă întâlnire, vă rugăm să ne contactați la:

Telefon: <?= $hospital['phone'] ?>
Email: <?= $hospital['email'] ?>

Ne cerem scuze pentru inconveniențele create.

========================================
© <?= date('Y') ?> <?= $hospital['name'] ?>