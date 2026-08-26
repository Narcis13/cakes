-- =============================================================================
-- ACTIVEAZĂ modul MENTENANȚĂ pentru portalul de programări
-- =============================================================================
-- De rulat direct pe baza de date de producție (MySQL/MariaDB) atunci când nu
-- te poți autentifica în admin (ex. serverul de mail e picat și nu primești
-- codul 2FA) și trebuie totuși să oprești portalul pacienților.
--
-- Efect: /portal/* și /appointments/* răspund cu HTTP 503 și pagina de
-- mentenanță. Restul site-ului public (știri, pagini, contact) rămâne activ.
--
-- Rulare:
--   mysql -u UTILIZATOR -p NUME_BAZA < portal_maintenance_on.sql
-- sau lipit direct în phpMyAdmin / Adminer.
--
-- Setările sunt citite la fiecare request din `site_settings`, fără cache
-- persistent, deci efectul e imediat. Nu e nevoie de `bin/cake cache clear_all`.
-- =============================================================================

-- 1. Textele afișate pacienților.
--    Se creează DOAR dacă lipsesc; dacă ai deja texte configurate din admin,
--    acest INSERT nu le suprascrie (`modified = modified` este un no-op).
INSERT INTO `site_settings` (`key_name`, `value`, `description`, `created`, `modified`) VALUES
    ('PORTAL_MAINTENANCE_TITLE', 'Portalul de programări este în mentenanță',
     'Titlul afișat pe pagina de mentenanță a portalului', NOW(), NOW()),

    ('PORTAL_MAINTENANCE_MESSAGE', 'În acest moment efectuăm lucrări de mentenanță la portalul de programări online. Vă rugăm să reveniți puțin mai târziu. Ne cerem scuze pentru neplăcerile create.',
     'Mesajul afișat pe pagina de mentenanță a portalului', NOW(), NOW()),

    -- '-' este valoarea folosită de aplicație pentru „fără estimare”.
    ('PORTAL_MAINTENANCE_ETA', '-',
     'Estimare revenire portal din mentenanță', NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `modified` = `modified`;

-- 2. Comutatorul propriu-zis: 1 = mentenanță activă.
INSERT INTO `site_settings` (`key_name`, `value`, `description`, `created`, `modified`) VALUES
    ('PORTAL_MAINTENANCE_MODE', '1',
     'Mod mentenanță portal pacienți (1 = activ, 0 = inactiv)', NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `value`    = '1',
    `modified` = NOW();

-- 3. Verificare
SELECT `key_name`, `value`, `modified`
FROM `site_settings`
WHERE `key_name` IN ('PORTAL_MAINTENANCE_MODE', 'PORTAL_MAINTENANCE_TITLE',
                     'PORTAL_MAINTENANCE_MESSAGE', 'PORTAL_MAINTENANCE_ETA')
ORDER BY `key_name`;


-- -----------------------------------------------------------------------------
-- OPȚIONAL: personalizează mesajul / estimarea revenirii.
-- Decomentează și modifică textul dorit.
-- -----------------------------------------------------------------------------
-- UPDATE `site_settings`
-- SET `value` = 'Revenim astăzi în jurul orei 18:00.', `modified` = NOW()
-- WHERE `key_name` = 'PORTAL_MAINTENANCE_ETA';
--
-- UPDATE `site_settings`
-- SET `value` = 'Textul tău aici.', `modified` = NOW()
-- WHERE `key_name` = 'PORTAL_MAINTENANCE_MESSAGE';
