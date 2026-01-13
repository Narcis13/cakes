<?= $hospital['name'] ?>
========================================

BINE AȚI VENIT!

Bună ziua <?= $patient->full_name ?>,

Contul dumneavoastră a fost activat cu succes!

Vă mulțumim că v-ați înregistrat pe portalul pacienților al <?= $hospital['name'] ?>. Acum puteți beneficia de toate funcționalitățile platformei noastre.

CE PUTEȚI FACE PE PORTAL:
----------------------------------------
- Programări online - Faceți programări rapid și simplu, oricând
- Istoric programări - Vizualizați toate programările anterioare
- Gestionare programări - Anulați sau modificați programările existente
- Profil personal - Actualizați datele de contact

ACCESEAZĂ PORTALUL:
<?= $portalUrl ?>


CONTACT:
----------------------------------------
Pentru orice întrebare sau asistență:

Telefon: <?= $hospital['phone'] ?>

Email: <?= $hospital['email'] ?>

Adresa: <?= $hospital['address'] ?>


Vă dorim sănătate și vă așteptăm cu drag!

========================================
© <?= date('Y') ?> <?= $hospital['name'] ?>

Acest email a fost generat automat.
Vă rugăm să nu răspundeți la acest mesaj.
