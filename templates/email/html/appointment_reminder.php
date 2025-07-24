<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reamintire Programare</title>
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
            background-color: #17a2b8;
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
            border-left: 4px solid #17a2b8;
        }
        .reminder-box {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
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
        <h2>Reamintire programare</h2>
    </div>
    
    <div class="content">
        <p>Bună ziua <strong><?= h($appointment->patient_name) ?></strong>,</p>
        
        <div class="reminder-box">
            Programarea dumneavoastră este programată în <?= $hoursUntil ?> ore!
        </div>
        
        <div class="appointment-details">
            <h3>Detaliile programării:</h3>
            <p><strong>Data și ora:</strong> <?= $appointment->appointment_date->format('d.m.Y') ?> la <?= $appointment->appointment_time->format('H:i') ?></p>
            <?php if (!empty($appointment->doctors)): ?>
                <p><strong>Doctor:</strong> <?= h($appointment->doctors->first_name . ' ' . $appointment->doctors->last_name) ?></p>
            <?php endif; ?>
            <?php if (!empty($appointment->doctors->departments)): ?>
                <p><strong>Departament:</strong> <?= h($appointment->doctors->departments->name) ?></p>
            <?php endif; ?>
            <?php if (!empty($appointment->service)): ?>
                <p><strong>Serviciu:</strong> <?= h($appointment->service->name) ?></p>
            <?php endif; ?>
        </div>
        
        <p><strong>Vă rugăm să vă prezentați cu 15 minute înainte de ora programării.</strong></p>
        
        <p>Pentru modificări sau anulări, vă rugăm să ne contactați la <?= h($hospital['phone']) ?>.</p>
    </div>
    
    <div style="background-color: #6c757d; color: white; padding: 20px; text-align: center; border-radius: 0 0 8px 8px; font-size: 14px;">
        <p>&copy; <?= date('Y') ?> <?= h($hospital['name']) ?></p>
    </div>
</body>
</html>