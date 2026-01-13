<?= $hospital['name'] ?>
========================================

RESETARE PAROLĂ

Bună ziua <?= $patient->full_name ?>,

Am primit o solicitare de resetare a parolei pentru contul dumneavoastră de pe portalul pacienților.

CONT ASOCIAT:
----------------------------------------
Email: <?= $patient->email ?>


Pentru a vă schimba parola, accesați link-ul de mai jos:

LINK RESETARE:
<?= $resetUrl ?>


IMPORTANT: Acest link de resetare este valabil doar 1 oră.
După expirare, va trebui să solicitați un nou link de resetare.

ATENȚIE LA SECURITATE:
Dacă nu ați solicitat resetarea parolei, vă rugăm să ignorați acest email.
Contul dumneavoastră rămâne în siguranță.
Pentru orice suspiciune, contactați-ne imediat.

CONTACT:
----------------------------------------
Pentru asistență:

Telefon: <?= $hospital['phone'] ?>

Email: <?= $hospital['email'] ?>


========================================
© <?= date('Y') ?> <?= $hospital['name'] ?>

Acest email a fost generat automat.
Vă rugăm să nu răspundeți la acest mesaj.
