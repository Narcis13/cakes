<?= $hospital['name'] ?> - ADMIN PANEL
========================================

📅 PROGRAMARE NOUĂ

O programare nouă a fost făcută în sistem.

DETALIILE PROGRAMĂRII:
----------------------------------------
Status: <?= strtoupper($appointment->status) ?>

Pacient: <?= $appointment->patient_name ?>
Telefon: <?= $appointment->phone ?>
Email: <?= $appointment->email ?>

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
<?php if (!empty($appointment->notes)): ?>
Observații: <?= $appointment->notes ?>

<?php endif; ?>
Data creării: <?= $appointment->created->format('d.m.Y H:i') ?>

LINK ADMIN:
<?= $adminUrl ?>

⚠️  NOTĂ: Dacă statusul programării este "pending", pacientul trebuie să confirme programarea prin email în termen de 24 de ore.

========================================
© <?= date('Y') ?> <?= $hospital['name'] ?> - Admin Panel