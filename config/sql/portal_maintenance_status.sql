-- =============================================================================
-- STATUS mentenanță portal (doar citire)
-- =============================================================================
SELECT
    `key_name`,
    `value`,
    `modified`,
    CASE
        WHEN `key_name` = 'PORTAL_MAINTENANCE_MODE' AND `value` = '1' THEN 'MENTENANȚĂ ACTIVĂ'
        WHEN `key_name` = 'PORTAL_MAINTENANCE_MODE' THEN 'FUNCȚIONAL'
        ELSE ''
    END AS stare
FROM `site_settings`
WHERE `key_name` IN ('PORTAL_MAINTENANCE_MODE', 'PORTAL_MAINTENANCE_TITLE',
                     'PORTAL_MAINTENANCE_MESSAGE', 'PORTAL_MAINTENANCE_ETA')
ORDER BY `key_name`;
