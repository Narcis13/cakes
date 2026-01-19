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
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: #fafafa;
            border: 1px solid #e5e5e5;
            color: #666666;
        }
        .button-container {
            text-align: center;
            margin: 24px 0;
        }
        .button {
            display: inline-block;
            background-color: #1e3a5f;
            color: #ffffff;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
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
            <h1 class="email-title">Programare nouă</h1>
        </div>

        <div class="content">
            <p>O programare nouă a fost făcută în sistem.</p>

            <div class="details-box">
                <h3>Detaliile programării</h3>
                <div class="details-row"><strong>Status:</strong> <span class="status-badge"><?= h($appointment->status) ?></span></div>
                <div class="details-row"><strong>Pacient:</strong> <?= h($appointment->patient_name) ?></div>
                <div class="details-row"><strong>Telefon:</strong> <?= h($appointment->phone) ?></div>
                <div class="details-row"><strong>Email:</strong> <?= h($appointment->email) ?></div>
                <div class="details-row"><strong>Data și ora:</strong> <?= $appointment->appointment_date->format('d.m.Y') ?> la <?= $appointment->appointment_time->format('H:i') ?></div>
                <?php if (!empty($appointment->staff)) : ?>
                    <div class="details-row"><strong>Doctor:</strong> <?= h($appointment->staff->full_name) ?></div>
                <?php endif; ?>
                <?php if (!empty($appointment->department)) : ?>
                    <div class="details-row"><strong>Departament:</strong> <?= h($appointment->department->name) ?></div>
                <?php endif; ?>
                <?php if (!empty($appointment->service)) : ?>
                    <div class="details-row"><strong>Serviciu:</strong> <?= h($appointment->service->name) ?></div>
                <?php endif; ?>
                <?php if (!empty($appointment->notes)) : ?>
                    <div class="details-row"><strong>Observații:</strong> <?= nl2br(h($appointment->notes)) ?></div>
                <?php endif; ?>
                <div class="details-row"><strong>Data creării:</strong> <?= $appointment->created->format('d.m.Y H:i') ?></div>
            </div>

            <div class="button-container">
                <a href="<?= $adminUrl ?>" class="button">Vizualizează în admin</a>
            </div>

            <p><strong>Notă:</strong> Programarea a fost confirmată automat.</p>
        </div>

        <div class="footer">
            <p>&copy; <?= date('Y') ?> <?= h($hospital['name']) ?> — Admin Panel</p>
            <p>Acest email a fost generat automat.</p>
        </div>
    </div>
</body>
</html>
