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
            color: #1a1a1a;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #ffffff;
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid #e5e5e5;
        }
        .hospital-name {
            font-size: 12px;
            font-weight: 600;
            color: #1e3a5f;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 8px 0;
        }
        .email-title {
            font-size: 24px;
            font-weight: 400;
            color: #1a1a1a;
            margin: 0;
        }
        .content {
            padding: 30px;
        }
        .content p {
            margin: 0 0 16px 0;
            color: #1a1a1a;
        }
        .reminder-highlight {
            background-color: #fafafa;
            border: 1px solid #e5e5e5;
            border-left: 3px solid #1e3a5f;
            border-radius: 0 6px 6px 0;
            padding: 16px 20px;
            margin: 24px 0;
            text-align: center;
            font-weight: 600;
        }
        .details-box {
            background-color: #fafafa;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            padding: 20px;
            margin: 24px 0;
        }
        .details-box h3 {
            font-size: 11px;
            font-weight: 600;
            color: #666666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 16px 0;
        }
        .details-row {
            margin: 10px 0;
        }
        .details-row:last-child {
            margin-bottom: 0;
        }
        .footer {
            background-color: #fafafa;
            padding: 24px 30px;
            text-align: center;
            border-top: 1px solid #e5e5e5;
        }
        .footer p {
            margin: 0 0 8px 0;
            font-size: 13px;
            color: #888888;
        }
        .footer p:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <p class="hospital-name"><?= h($hospital['name']) ?></p>
            <h1 class="email-title">Reamintire programare</h1>
        </div>

        <div class="content">
            <p>Bună ziua <strong><?= h($appointment->patient_name) ?></strong>,</p>

            <div class="reminder-highlight">
                Programarea dumneavoastră este în <?= $hoursUntil ?> ore.
            </div>

            <div class="details-box">
                <h3>Detaliile programării</h3>
                <div class="details-row"><strong>Data și ora:</strong> <?= $appointment->appointment_date->format('d.m.Y') ?> la <?= $appointment->appointment_time->format('H:i') ?></div>
                <?php if (!empty($appointment->doctors)): ?>
                    <div class="details-row"><strong>Doctor:</strong> <?= h($appointment->doctors->first_name . ' ' . $appointment->doctors->last_name) ?></div>
                <?php endif; ?>
                <?php if (!empty($appointment->doctors->departments)): ?>
                    <div class="details-row"><strong>Departament:</strong> <?= h($appointment->doctors->departments->name) ?></div>
                <?php endif; ?>
                <?php if (!empty($appointment->service)): ?>
                    <div class="details-row"><strong>Serviciu:</strong> <?= h($appointment->service->name) ?></div>
                <?php endif; ?>
            </div>

            <p><strong>Vă rugăm să vă prezentați cu 15 minute înainte de ora programării.</strong></p>

            <p>Pentru modificări sau anulări, vă rugăm să ne contactați la <?= h($hospital['phone']) ?>.</p>
        </div>

        <div class="footer">
            <p>&copy; <?= date('Y') ?> <?= h($hospital['name']) ?></p>
            <p>Acest email a fost generat automat. Vă rugăm să nu răspundeți la acest mesaj.</p>
        </div>
    </div>
</body>
</html>
