<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resetare Parolă</title>
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
        .info-box {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
        .button {
            display: inline-block;
            background-color: #dc3545;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #c82333;
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
        .security-notice {
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
        <h2>Resetare parolă</h2>
    </div>

    <div class="content">
        <p>Bună ziua <strong><?= h($patient->full_name) ?></strong>,</p>

        <p>Am primit o solicitare de resetare a parolei pentru contul dumneavoastră de pe portalul pacienților.</p>

        <div class="info-box">
            <h3>Cont asociat:</h3>
            <p><strong>Email:</strong> <?= h($patient->email) ?></p>
        </div>

        <p>Pentru a vă schimba parola, accesați butonul de mai jos:</p>

        <div style="text-align: center;">
            <a href="<?= $resetUrl ?>" class="button">RESETEAZĂ PAROLA</a>
        </div>

        <div class="warning">
            <strong>Important:</strong> Acest link de resetare este valabil doar 1 oră. După expirare, va trebui să solicitați un nou link de resetare.
        </div>

        <div class="security-notice">
            <strong>Atenție la securitate:</strong> Dacă nu ați solicitat resetarea parolei, vă rugăm să ignorați acest email. Contul dumneavoastră rămâne în siguranță. Pentru orice suspiciune, contactați-ne imediat.
        </div>

        <p>Pentru asistență, ne puteți contacta la:</p>
        <ul>
            <li><strong>Telefon:</strong> <?= h($hospital['phone']) ?></li>
            <li><strong>Email:</strong> <?= h($hospital['email']) ?></li>
        </ul>
    </div>

    <div class="footer">
        <p>&copy; <?= date('Y') ?> <?= h($hospital['name']) ?></p>
        <p>Acest email a fost generat automat. Vă rugăm să nu răspundeți la acest mesaj.</p>
    </div>
</body>
</html>
