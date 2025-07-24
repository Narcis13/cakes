<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmare Programare</title>
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
            background-color: #2c5282;
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
            border-left: 4px solid #2c5282;
        }
        .button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #218838;
        }
        .footer {
            background-color: #6c757d;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 14px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= h($hospital['name']) ?></h1>
        <h2>Confirmare programare necesară</h2>
    </div>
    
    <div class="content">
        <p>Bună ziua <strong><?= h($appointment->patient_name) ?></strong>,</p>
        
        <p>Ați făcut o programare la <?= h($hospital['name']) ?>. Pentru a finaliza procesul de programare, vă rugăm să confirmați programarea accesând link-ul de mai jos.</p>
        
        <div class="appointment-details">
            <h3>Detaliile programării:</h3>
            <p><strong>Pacient:</strong> <?= h($appointment->patient_name) ?></p>
            <p><strong>Telefon:</strong> <?= h($appointment->patient_phone) ?></p>
            <p><strong>Email:</strong> <?= h($appointment->patient_email) ?></p>
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
            <?php if (!empty($appointment->notes)): ?>
                <p><strong>Observații:</strong> <?= h($appointment->notes) ?></p>
            <?php endif; ?>
        </div>
        
        <?php if ($token): ?>
            <div style="text-align: center;">
                <a href="<?= $confirmationUrl ?>" class="button">CONFIRMĂ PROGRAMAREA</a>
            </div>
            
            <div class="warning">
                <strong>Important:</strong> Această programare trebuie confirmată în termen de 24 de ore. Dacă nu confirmați programarea în acest interval, aceasta va fi anulată automat.
            </div>
        <?php endif; ?>
        
        <p>Pentru modificări sau anulări, vă rugăm să ne contactați la:</p>
        <ul>
            <li><strong>Telefon:</strong> <?= h($hospital['phone']) ?></li>
            <li><strong>Email:</strong> <?= h($hospital['email']) ?></li>
            <li><strong>Adresa:</strong> <?= h($hospital['address']) ?></li>
        </ul>
    </div>
    
    <div class="footer">
        <p>&copy; <?= date('Y') ?> <?= h($hospital['name']) ?></p>
        <p>Acest email a fost generat automat. Vă rugăm să nu răspundeți la acest mesaj.</p>
    </div>
</body>
</html>