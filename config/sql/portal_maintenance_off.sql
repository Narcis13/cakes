-- =============================================================================
-- DEZACTIVEAZĂ modul MENTENANȚĂ — portalul revine la funcționare normală
-- =============================================================================
-- Rulare:
--   mysql -u UTILIZATOR -p NUME_BAZA < portal_maintenance_off.sql
-- =============================================================================

INSERT INTO `site_settings` (`key_name`, `value`, `description`, `created`, `modified`) VALUES
    ('PORTAL_MAINTENANCE_MODE', '0',
     'Mod mentenanță portal pacienți (1 = activ, 0 = inactiv)', NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `value`    = '0',
    `modified` = NOW();

-- Verificare: PORTAL_MAINTENANCE_MODE trebuie să fie 0.
SELECT `key_name`, `value`, `modified`
FROM `site_settings`
WHERE `key_name` IN ('PORTAL_MAINTENANCE_MODE', 'PORTAL_MAINTENANCE_TITLE',
                     'PORTAL_MAINTENANCE_MESSAGE', 'PORTAL_MAINTENANCE_ETA')
ORDER BY `key_name`;
