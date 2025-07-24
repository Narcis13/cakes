<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programare Confirmată</title>
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
            background-color: #28a745;
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
            border-left: 4px solid #28a745;
        }
        .success-box {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= h($hospital['name']) ?></h1>
        <h2>✓ Programare confirmată</h2>
    </div>
    
    <div class="content">
        <p>Bună ziua <strong><?= h($appointment->patient_name) ?></strong>,</p>
        
        <div class="success-box">
            Programarea dumneavoastră a fost confirmată cu succes!
        </div>
        
        <div class="appointment-details">
            <h3>Detaliile programării confirmate:</h3>
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
            <p><strong>Adresa:</strong> <?= h($hospital['address']) ?></p>
        </div>
        
        <h3>Instrucțiuni importante:</h3>
        <ul>
            <li>Vă rugăm să vă prezentați cu <strong>15 minute înainte</strong> de ora programării</li>
            <li>Aveți la dumneavoastră actul de identitate</li>
            <li>Dacă aveți investigații medicale anterioare, vă rugăm să le aduceți</li>
            <li>În caz de întârziere, vă rugăm să ne anunțați telefonic</li>
        </ul>
        
        <p>Pentru modificări sau anulări, vă rugăm să ne contactați cu cel puțin 24 de ore înainte la <?= h($hospital['phone']) ?>.</p>
        
        <p>Vă mulțumim pentru încrederea acordată!</p>
    </div>
    
    <div style="background-color: #6c757d; color: white; padding: 20px; text-align: center; border-radius: 0 0 8px 8px; font-size: 14px;">
        <p>&copy; <?= date('Y') ?> <?= h($hospital['name']) ?></p>
    </div>
</body>
</html>