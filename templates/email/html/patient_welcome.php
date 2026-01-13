<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bine ați venit</title>
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
        .success-box {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .features-list {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .features-list ul {
            margin: 0;
            padding-left: 20px;
        }
        .features-list li {
            margin: 10px 0;
        }
        .button {
            display: inline-block;
            background-color: #2c5282;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #1a365d;
        }
        .footer {
            background-color: #6c757d;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= h($hospital['name']) ?></h1>
        <h2>Bine ați venit!</h2>
    </div>

    <div class="content">
        <p>Bună ziua <strong><?= h($patient->full_name) ?></strong>,</p>

        <div class="success-box">
            <h3>Contul dumneavoastră a fost activat cu succes!</h3>
        </div>

        <p>Vă mulțumim că v-ați înregistrat pe portalul pacienților al <?= h($hospital['name']) ?>. Acum puteți beneficia de toate funcționalitățile platformei noastre.</p>

        <div class="features-list">
            <h3>Ce puteți face pe portal:</h3>
            <ul>
                <li><strong>Programări online</strong> - Faceți programări rapid și simplu, oricând</li>
                <li><strong>Istoric programări</strong> - Vizualizați toate programările anterioare</li>
                <li><strong>Gestionare programări</strong> - Anulați sau modificați programările existente</li>
                <li><strong>Profil personal</strong> - Actualizați datele de contact</li>
            </ul>
        </div>

        <div style="text-align: center;">
            <a href="<?= $portalUrl ?>" class="button">ACCESEAZĂ PORTALUL</a>
        </div>

        <p>Pentru orice întrebare sau asistență, suntem aici să vă ajutăm:</p>
        <ul>
            <li><strong>Telefon:</strong> <?= h($hospital['phone']) ?></li>
            <li><strong>Email:</strong> <?= h($hospital['email']) ?></li>
            <li><strong>Adresa:</strong> <?= h($hospital['address']) ?></li>
        </ul>

        <p>Vă dorim sănătate și vă așteptăm cu drag!</p>
    </div>

    <div class="footer">
        <p>&copy; <?= date('Y') ?> <?= h($hospital['name']) ?></p>
        <p>Acest email a fost generat automat. Vă rugăm să nu răspundeți la acest mesaj.</p>
    </div>
</body>
</html>
