<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anulare Programare</title>
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
            background-color: #dc3545;
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
            border-left: 4px solid #dc3545;
        }
        .reason-box {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= h($hospital['name']) ?></h1>
        <h2>Programare anulată</h2>
    </div>
    
    <div class="content">
        <p>Bună ziua <strong><?= h($appointment->patient_name) ?></strong>,</p>
        
        <p>Ne pare rău să vă informăm că programarea dumneavoastră a fost anulată.</p>
        
        <div class="appointment-details">
            <h3>Programarea anulată:</h3>
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
        </div>
        
        <?php if ($reason): ?>
            <div class="reason-box">
                <strong>Motivul anulării:</strong> <?= h($reason) ?>
            </div>
        <?php endif; ?>
        
        <p>Pentru a programa o nouă întâlnire, vă rugăm să ne contactați la:</p>
        <ul>
            <li><strong>Telefon:</strong> <?= h($hospital['phone']) ?></li>
            <li><strong>Email:</strong> <?= h($hospital['email']) ?></li>
        </ul>
        
        <p>Ne cerem scuze pentru inconveniențele create.</p>
    </div>
    
    <div style="background-color: #6c757d; color: white; padding: 20px; text-align: center; border-radius: 0 0 8px 8px; font-size: 14px;">
        <p>&copy; <?= date('Y') ?> <?= h($hospital['name']) ?></p>
    </div>
</body>
</html>