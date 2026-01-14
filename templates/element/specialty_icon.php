<?php
/**
 * Specialty Icon Element
 * Returns an appropriate icon based on the medical specialty name
 *
 * @var string $specialty The specialty name
 */

$specialty = strtolower($specialty ?? '');

// Map specialties to icons
$iconMap = [
    'cardiologie' => 'fa-heartbeat',
    'dermatologie' => 'fa-hand-paper',
    'endocrinologie' => 'fa-flask',
    'gastroenterologie' => 'fa-stomach',
    'neurologie' => 'fa-brain',
    'oftalmologie' => 'fa-eye',
    'ortopedie' => 'fa-bone',
    'orl' => 'fa-ear',
    'otorinolaringologie' => 'fa-ear',
    'pediatrie' => 'fa-baby',
    'pneumologie' => 'fa-lungs',
    'psihiatrie' => 'fa-head-side-brain',
    'chirurgie' => 'fa-cut',
    'chirurgie generală' => 'fa-cut',
    'chirurgie plastica' => 'fa-magic',
    'radiologie' => 'fa-x-ray',
    'reumatologie' => 'fa-hand-holding-medical',
    'urologie' => 'fa-kidneys',
    'ginecologie' => 'fa-venus',
    'obstetrica' => 'fa-baby-carriage',
    'oncologie' => 'fa-ribbon',
    'anestezie' => 'fa-syringe',
    'anestezie și terapie intensivă' => 'fa-syringe',
    'medicina interna' => 'fa-stethoscope',
    'medicină internă' => 'fa-stethoscope',
    'medicina de familie' => 'fa-home',
    'medicină de familie' => 'fa-home',
    'medicina muncii' => 'fa-briefcase-medical',
    'alergologie' => 'fa-allergies',
    'hematologie' => 'fa-tint',
    'nefrologie' => 'fa-kidneys',
    'infectioase' => 'fa-virus',
    'geriatrie' => 'fa-user-clock',
    'recuperare' => 'fa-walking',
    'fizioterapie' => 'fa-running',
    'stomatologie' => 'fa-tooth',
];

$icon = 'fa-stethoscope'; // Default icon

foreach ($iconMap as $key => $value) {
    if (str_contains($specialty, $key)) {
        $icon = $value;
        break;
    }
}
?>
<i class="fas <?= $icon ?>"></i>
