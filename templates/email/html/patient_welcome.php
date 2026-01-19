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
        .details-box ul {
            margin: 0;
            padding-left: 20px;
        }
        .details-box li {
            margin: 10px 0;
            color: #1a1a1a;
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
        .contact-list {
            margin: 16px 0;
            padding-left: 0;
            list-style: none;
        }
        .contact-list li {
            margin: 8px 0;
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
            <h1 class="email-title">Bine ați venit</h1>
        </div>

        <div class="content">
            <p>Bună ziua <strong><?= h($patient->full_name) ?></strong>,</p>

            <p>Contul dumneavoastră a fost activat cu succes.</p>

            <p>Vă mulțumim că v-ați înregistrat pe portalul pacienților al <?= h($hospital['name']) ?>. Acum puteți beneficia de toate funcționalitățile platformei noastre.</p>

            <div class="details-box">
                <h3>Ce puteți face pe portal</h3>
                <ul>
                    <li><strong>Programări online</strong> — Faceți programări rapid și simplu, oricând</li>
                    <li><strong>Istoric programări</strong> — Vizualizați toate programările anterioare</li>
                    <li><strong>Gestionare programări</strong> — Anulați sau modificați programările existente</li>
                    <li><strong>Profil personal</strong> — Actualizați datele de contact</li>
                </ul>
            </div>

            <div class="button-container">
                <a href="<?= $portalUrl ?>" class="button">Accesează portalul</a>
            </div>

            <p>Pentru orice întrebare sau asistență, suntem aici să vă ajutăm:</p>
            <ul class="contact-list">
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
    </div>
</body>
</html>
