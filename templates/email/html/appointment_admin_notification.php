<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programare Nouă</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border: 1px solid #dee2e6;
        }
        .appointment-details {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= h($hospital['name']) ?></h1>
        <h2>📅 Programare nouă</h2>
    </div>
    
    <div class="content">
        <p>O programare nouă a fost făcută în sistem.</p>
        
        <div class="appointment-details">
            <h3>Detaliile programării:</h3>
            <p><strong>Status:</strong> <span class="status-badge status-pending"><?= h($appointment->status) ?></span></p>
            <p><strong>Pacient:</strong> <?= h($appointment->patient_name) ?></p>
            <p><strong>Telefon:</strong> <?= h($appointment->phone) ?></p>
            <p><strong>Email:</strong> <?= h($appointment->email) ?></p>
            <p><strong>Data și ora:</strong> <?= $appointment->appointment_date->format('d.m.Y') ?> la <?= $appointment->appointment_time->format('H:i') ?></p>
            <?php if (!empty($appointment->staff)): ?>
                <p><strong>Doctor:</strong> <?= h($appointment->staff->full_name) ?></p>
            <?php endif; ?>
            <?php if (!empty($appointment->department)): ?>
                <p><strong>Departament:</strong> <?= h($appointment->department->name) ?></p>
            <?php endif; ?>
            <?php if (!empty($appointment->service)): ?>
                <p><strong>Serviciu:</strong> <?= h($appointment->service->name) ?></p>
            <?php endif; ?>
            <?php if (!empty($appointment->notes)): ?>
                <p><strong>Observații:</strong> <?= nl2br(h($appointment->notes)) ?></p>
            <?php endif; ?>
            <p><strong>Data creării:</strong> <?= $appointment->created->format('d.m.Y H:i') ?></p>
        </div>
        
        <div style="text-align: center;">
            <a href="<?= $adminUrl ?>" class="button">VIZUALIZEAZĂ ÎN ADMIN</a>
        </div>
        
        <p><strong>Notă:</strong> Dacă statusul programării este "pending", pacientul trebuie să confirme programarea prin email în termen de 24 de ore.</p>
    </div>
    
    <div style="background-color: #6c757d; color: white; padding: 20px; text-align: center; border-radius: 0 0 8px 8px; font-size: 14px;">
        <p>&copy; <?= date('Y') ?> <?= h($hospital['name']) ?> - Admin Panel</p>
    </div>
</body>
</html>