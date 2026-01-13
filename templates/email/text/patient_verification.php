<?= $hospital['name'] ?>
========================================

VERIFICARE ADRESĂ DE EMAIL

Bună ziua <?= $patient->full_name ?>,

Vă mulțumim pentru înregistrarea pe portalul pacienților al <?= $hospital['name'] ?>.

Pentru a vă activa contul și a putea face programări online, vă rugăm să confirmați adresa de email accesând link-ul de mai jos.

DETALIILE CONTULUI:
----------------------------------------
Nume: <?= $patient->full_name ?>

Email: <?= $patient->email ?>

Telefon: <?= $patient->phone ?>


LINK VERIFICARE:
<?= $verifyUrl ?>


IMPORTANT: Acest link de verificare este valabil 24 de ore.
Dacă nu verificați adresa de email în acest interval, va trebui să vă înregistrați din nou.

Dacă nu ați solicitat crearea acestui cont, puteți ignora acest email.

CONTACT:
----------------------------------------
Pentru asistență:

Telefon: <?= $hospital['phone'] ?>

Email: <?= $hospital['email'] ?>


========================================
© <?= date('Y') ?> <?= $hospital['name'] ?>

Acest email a fost generat automat.
Vă rugăm să nu răspundeți la acest mesaj.
