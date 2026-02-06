<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cod de verificare</title>
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
        .code-box {
            background-color: #fafafa;
            border: 2px solid #1e3a5f;
            border-radius: 8px;
            padding: 24px;
            margin: 24px 0;
            text-align: center;
        }
        .code-label {
            font-size: 12px;
            font-weight: 600;
            color: #666666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 12px 0;
        }
        .code-value {
            font-size: 36px;
            font-weight: 700;
            color: #1e3a5f;
            letter-spacing: 8px;
            margin: 0;
            font-family: 'Courier New', monospace;
        }
        .notice {
            background-color: #fafafa;
            border: 1px solid #e5e5e5;
            border-left: 3px solid #dc3545;
            border-radius: 0 6px 6px 0;
            padding: 16px 20px;
            margin: 24px 0;
        }
        .notice strong {
            color: #1a1a1a;
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
            <h1 class="email-title">Verificare în doi pași</h1>
        </div>

        <div class="content">
            <p>Bună ziua,</p>

            <p>Ați solicitat autentificarea în panoul de administrare al <?= h($hospital['name']) ?>. Folosiți codul de mai jos pentru a finaliza autentificarea:</p>

            <div class="code-box">
                <p class="code-label">Codul dumneavoastră de verificare</p>
                <p class="code-value"><?= h($code) ?></p>
            </div>

            <p>Acest cod este valabil <strong>5 minute</strong>. După expirare, va trebui să solicitați un cod nou.</p>

            <div class="notice">
                <strong>Important:</strong> Nu partajați acest cod cu nimeni. Echipa noastră nu vă va solicita niciodată acest cod.
            </div>

            <p>Dacă nu ați solicitat acest cod, vă rugăm să ignorați acest email și să vă asigurați că contul dumneavoastră este securizat.</p>
        </div>

        <div class="footer">
            <p>&copy; <?= date('Y') ?> <?= h($hospital['name']) ?></p>
            <p>Acest email a fost generat automat. Vă rugăm să nu răspundeți la acest mesaj.</p>
        </div>
    </div>
</body>
</html>
