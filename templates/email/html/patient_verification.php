<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificare Cont</title>
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
        <h2>Verificare adresă de email</h2>
    </div>

    <div class="content">
        <p>Bună ziua <strong><?= h($patient->full_name) ?></strong>,</p>

        <p>Vă mulțumim pentru înregistrarea pe portalul pacienților al <?= h($hospital['name']) ?>.</p>

        <p>Pentru a vă activa contul și a putea face programări online, vă rugăm să confirmați adresa de email accesând butonul de mai jos:</p>

        <div class="info-box">
            <h3>Detaliile contului:</h3>
            <p><strong>Nume:</strong> <?= h($patient->full_name) ?></p>
            <p><strong>Email:</strong> <?= h($patient->email) ?></p>
            <p><strong>Telefon:</strong> <?= h($patient->phone) ?></p>
        </div>

        <div style="text-align: center;">
            <a href="<?= $verifyUrl ?>" class="button">VERIFICĂ ADRESA DE EMAIL</a>
        </div>

        <div class="warning">
            <strong>Important:</strong> Acest link de verificare este valabil 24 de ore. Dacă nu verificați adresa de email în acest interval, va trebui să vă înregistrați din nou.
        </div>

        <p>Dacă nu ați solicitat crearea acestui cont, puteți ignora acest email.</p>

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
