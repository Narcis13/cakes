<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Patient $patient
 * @var array $specializations
 * @var array $services
 */

// Include appointments CSS
$this->Html->css('appointments', ['block' => true]);
?>

<div class="appointments-booking">
    <div class="container">
        <h1>Programare Online</h1>

        <!-- Progress Bar - Modern Design -->
        <div class="booking-progress-wrapper">
            <div class="progress-steps-container">
                <div class="progress-step active" data-step="1">
                    <div class="step-icon-wrapper">
                        <div class="step-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <div class="step-number-badge">1</div>
                    </div>
                    <div class="step-info">
                        <span class="step-title">Specialitate</span>
                        <span class="step-subtitle">Alegeți domeniul</span>
                    </div>
                </div>
                <div class="progress-connector"></div>
                <div class="progress-step" data-step="2">
                    <div class="step-icon-wrapper">
                        <div class="step-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="step-number-badge">2</div>
                    </div>
                    <div class="step-info">
                        <span class="step-title">Medic</span>
                        <span class="step-subtitle">Selectați doctorul</span>
                    </div>
                </div>
                <div class="progress-connector"></div>
                <div class="progress-step" data-step="3">
                    <div class="step-icon-wrapper">
                        <div class="step-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="step-number-badge">3</div>
                    </div>
                    <div class="step-info">
                        <span class="step-title">Data și Ora</span>
                        <span class="step-subtitle">Alegeți momentul</span>
                    </div>
                </div>
                <div class="progress-connector"></div>
                <div class="progress-step" data-step="4">
                    <div class="step-icon-wrapper">
                        <div class="step-icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div class="step-number-badge">4</div>
                    </div>
                    <div class="step-info">
                        <span class="step-title">Date Personale</span>
                        <span class="step-subtitle">Verificați datele</span>
                    </div>
                </div>
                <div class="progress-connector"></div>
                <div class="progress-step" data-step="5">
                    <div class="step-icon-wrapper">
                        <div class="step-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="step-number-badge">5</div>
                    </div>
                    <div class="step-info">
                        <span class="step-title">Confirmare</span>
                        <span class="step-subtitle">Finalizați</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <?= $this->Form->create(null, [
            'url' => ['action' => 'book'],
            'id' => 'booking-form',
            'class' => 'multi-step-form'
        ]) ?>

        <!-- Step 1: Select Medical Specialty -->
        <div class="form-step active" data-step="1">
            <div class="step-header">
                <h2><i class="fas fa-stethoscope"></i> Selectați Specialitatea Medicală</h2>
                <p class="step-description">Alegeți specialitatea de care aveți nevoie. Sunt afișate doar specialitățile cu medici disponibili pentru programare.</p>
            </div>

            <?php if (empty($specializations)): ?>
                <div class="no-specializations-message">
                    <i class="fas fa-calendar-times fa-3x"></i>
                    <h3>Nicio specialitate disponibilă</h3>
                    <p>Momentan nu există medici cu program de consultații definit. Vă rugăm să reveniți mai târziu sau să ne contactați telefonic.</p>
                </div>
            <?php else: ?>
                <div class="specialty-grid">
                    <?php foreach ($specializations as $spec): ?>
                        <div class="specialty-card" data-specialty="<?= h($spec['value']) ?>">
                            <div class="specialty-card-inner">
                                <div class="specialty-icon-container">
                                    <div class="specialty-icon">
                                        <?= $this->element('specialty_icon', ['specialty' => $spec['text']]) ?>
                                    </div>
                                </div>
                                <div class="specialty-content">
                                    <h3 class="specialty-name"><?= h($spec['text']) ?></h3>
                                    <?php if (!empty($spec['description'])): ?>
                                        <p class="specialty-description"><?= h(mb_strimwidth($spec['description'], 0, 100, '...')) ?></p>
                                    <?php endif; ?>
                                    <div class="specialty-meta">
                                        <span class="doctors-available">
                                            <i class="fas fa-user-md"></i>
                                            <?= $spec['doctor_count'] ?> <?= $spec['doctor_count'] == 1 ? 'medic disponibil' : 'medici disponibili' ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="specialty-card-arrow">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?= $this->Form->hidden('specialty', ['id' => 'selected-specialty']) ?>

            <div class="step-actions">
                <button type="button" class="btn btn-primary next-step" disabled>
                    Următorul Pas <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Step 2: View Available Doctors -->
        <div class="form-step" data-step="2">
            <div class="step-header">
                <h2><i class="fas fa-user-md"></i> Selectați Medicul</h2>
                <p class="step-description">Alegeți medicul la care doriți să vă programați.</p>
            </div>

            <div class="selected-specialty-badge">
                <i class="fas fa-stethoscope"></i>
                <span>Specialitate selectată: <strong id="display-specialty"></strong></span>
            </div>

            <div id="doctors-loading" class="loading-indicator" style="display: none;">
                <div class="spinner-wrapper">
                    <i class="fas fa-spinner fa-spin fa-3x"></i>
                    <p>Se încarcă medicii disponibili...</p>
                </div>
            </div>

            <div id="doctors-list" class="doctors-grid"></div>

            <?= $this->Form->hidden('doctor_id', ['id' => 'selected-doctor']) ?>
            <?= $this->Form->hidden('service_id', ['id' => 'selected-service']) ?>

            <div class="step-actions">
                <button type="button" class="btn btn-secondary prev-step">
                    <i class="fas fa-arrow-left"></i> Pasul Anterior
                </button>
                <button type="button" class="btn btn-primary next-step" disabled>
                    Următorul Pas <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Step 3: Select Date and Time - REDESIGNED -->
        <div class="form-step" data-step="3">
            <div class="step-header">
                <h2><i class="fas fa-calendar-check"></i> Selectați Data și Ora</h2>
                <p class="step-description">
                    Alegeți o dată disponibilă din calendar și apoi selectați ora dorită.
                </p>
            </div>

            <!-- Selected Info Card (keep existing if present) -->
            <div class="selected-info-card">
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-user-md"></i> Medic:</span>
                    <span class="info-value" id="step3-doctor-name">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-stethoscope"></i> Serviciu:</span>
                    <span class="info-value" id="step3-service-name">-</span>
                </div>
            </div>

            <!-- NEW: Calendar + Slots Container -->
            <div class="date-time-selection-v2">

                <!-- LEFT: Calendar Panel -->
                <div class="calendar-panel">
                    <div class="calendar-header">
                        <button type="button" class="calendar-nav-btn" id="prev-month" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div class="calendar-title">
                            <span id="calendar-month-year">Ianuarie 2026</span>
                        </div>
                        <button type="button" class="calendar-nav-btn" id="next-month">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <div class="calendar-weekdays">
                        <span>L</span><span>M</span><span>M</span><span>J</span>
                        <span>V</span><span>S</span><span>D</span>
                    </div>

                    <div id="calendar-days" class="calendar-days">
                        <!-- Days generated by JavaScript -->
                    </div>

                    <!-- Quick Navigation -->
                    <div class="calendar-quick-nav">
                        <button type="button" class="btn-quick-nav" id="goto-first-available">
                            <i class="fas fa-bolt"></i> Prima zi disponibilă
                        </button>
                    </div>

                    <!-- Legend -->
                    <div class="calendar-legend">
                        <div class="legend-item">
                            <span class="legend-dot available"></span>
                            <span>Disponibil</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot partial"></span>
                            <span>Parțial ocupat</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot full"></span>
                            <span>Complet ocupat</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot holiday"></span>
                            <span>Sărbătoare</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot weekend"></span>
                            <span>Weekend</span>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: Time Slots Panel -->
                <div class="time-slots-panel">
                    <div class="time-slots-header">
                        <h4><i class="fas fa-clock"></i> Ore Disponibile</h4>
                        <span id="selected-date-display" class="selected-date-badge">
                            Selectați o dată
                        </span>
                    </div>

                    <div id="time-slots-container-v2">
                        <!-- Initial State -->
                        <div id="slots-placeholder" class="slots-placeholder">
                            <i class="fas fa-hand-pointer fa-3x"></i>
                            <p>Selectați o dată din calendar pentru a vedea orele disponibile</p>
                        </div>

                        <!-- Loading State -->
                        <div id="slots-loading-v2" class="slots-loading" style="display: none;">
                            <div class="spinner-wrapper">
                                <i class="fas fa-spinner fa-spin fa-2x"></i>
                                <p>Se încarcă orele...</p>
                            </div>
                        </div>

                        <!-- No Slots State -->
                        <div id="no-slots-v2" class="no-slots-message" style="display: none;">
                            <i class="fas fa-calendar-times fa-3x"></i>
                            <p>Nu sunt ore disponibile pentru această zi.</p>
                            <button type="button" class="btn-suggestion" id="suggest-next-day">
                                <i class="fas fa-arrow-right"></i> Următoarea zi disponibilă
                            </button>
                        </div>

                        <!-- Time Slots Grid -->
                        <div id="time-slots-grid-v2" class="time-slots-grid-v2" style="display: none;">
                            <!-- Slots generated by JavaScript -->
                        </div>
                    </div>

                    <!-- Slots Legend -->
                    <div id="slots-legend-v2" class="slots-legend" style="display: none;">
                        <div class="legend-item">
                            <div class="legend-slot available"></div>
                            <span>Disponibil</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-slot selected"></div>
                            <span>Selectat</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-slot occupied"></div>
                            <span>Ocupat</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Fields -->
            <?= $this->Form->hidden('appointment_date', ['id' => 'appointment-date']) ?>
            <?= $this->Form->hidden('appointment_time', ['id' => 'selected-time']) ?>

            <!-- Step Actions -->
            <div class="step-actions">
                <button type="button" class="btn btn-secondary prev-step">
                    <i class="fas fa-arrow-left"></i> Pasul Anterior
                </button>
                <button type="button" class="btn btn-primary next-step" disabled>
                    Următorul Pas <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Step 4: Verify Patient Details -->
        <div class="form-step" data-step="4">
            <div class="step-header">
                <h2><i class="fas fa-id-card"></i> Verificați Datele Personale</h2>
                <p class="step-description">Datele dumneavoastră preluate din contul de pacient.</p>
            </div>

            <div class="verification-card">
                <div class="verification-card-header">
                    <div class="verification-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="verification-title">
                        <h3>Date Pacient Verificate</h3>
                        <span class="verification-badge">
                            <i class="fas fa-shield-alt"></i> Din contul dumneavoastră
                        </span>
                    </div>
                </div>

                <div class="verification-details">
                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-icon"><i class="fas fa-user"></i></div>
                            <div class="detail-content">
                                <span class="detail-label">Nume complet</span>
                                <span class="detail-value" id="display-patient-name"><?= h($patient->full_name) ?></span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon"><i class="fas fa-envelope"></i></div>
                            <div class="detail-content">
                                <span class="detail-label">Email</span>
                                <span class="detail-value" id="display-patient-email"><?= h($patient->email) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-icon"><i class="fas fa-phone"></i></div>
                            <div class="detail-content">
                                <span class="detail-label">Telefon</span>
                                <span class="detail-value" id="display-patient-phone"><?= h($patient->phone) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="verification-note">
                    <i class="fas fa-info-circle"></i>
                    <span>Pentru a modifica datele, accesați <a href="/portal/profile">profilul dumneavoastră</a>.</span>
                </div>
            </div>

            <!-- Hidden field pentru patient_id -->
            <?= $this->Form->hidden('patient_id', ['value' => $patient->id, 'id' => 'patient-id']) ?>

            <div class="notes-card">
                <div class="notes-header">
                    <i class="fas fa-sticky-note"></i>
                    <span>Observații pentru Medic (opțional)</span>
                </div>
                <?= $this->Form->control('notes', [
                    'type' => 'textarea',
                    'label' => false,
                    'class' => 'form-control notes-textarea',
                    'rows' => 3,
                    'id' => 'notes',
                    'placeholder' => 'Menționați aici orice informații relevante pentru consultație...'
                ]) ?>
            </div>

            <div class="step-actions">
                <button type="button" class="btn btn-secondary prev-step">
                    <i class="fas fa-arrow-left"></i> Pasul Anterior
                </button>
                <button type="button" class="btn btn-primary next-step">
                    Următorul Pas <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Step 5: Review and Confirm - REDESIGNED -->
        <div class="form-step" data-step="5">
            <div class="step-header">
                <h2><i class="fas fa-clipboard-check"></i> Verificare și Confirmare</h2>
                <p class="step-description">Verificați toate detaliile programării înainte de a confirma.</p>
            </div>

            <!-- Summary Cards Container -->
            <div class="confirmation-summary">
                <!-- Appointment Card -->
                <div class="summary-card appointment-card">
                    <div class="summary-card-header">
                        <div class="summary-card-icon appointment-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3>Detalii Programare</h3>
                    </div>
                    <div class="summary-card-body">
                        <div class="summary-grid">
                            <div class="summary-item">
                                <div class="summary-item-icon">
                                    <i class="fas fa-stethoscope"></i>
                                </div>
                                <div class="summary-item-content">
                                    <span class="summary-label">Specialitate</span>
                                    <span class="summary-value" id="summary-specialty">-</span>
                                </div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-item-icon">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <div class="summary-item-content">
                                    <span class="summary-label">Medic</span>
                                    <span class="summary-value" id="summary-doctor">-</span>
                                </div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-item-icon">
                                    <i class="fas fa-briefcase-medical"></i>
                                </div>
                                <div class="summary-item-content">
                                    <span class="summary-label">Serviciu</span>
                                    <span class="summary-value" id="summary-service">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="datetime-highlight">
                            <div class="datetime-item date-item">
                                <i class="fas fa-calendar-day"></i>
                                <div class="datetime-content">
                                    <span class="datetime-label">Data</span>
                                    <span class="datetime-value" id="summary-date">-</span>
                                </div>
                            </div>
                            <div class="datetime-divider"></div>
                            <div class="datetime-item time-item">
                                <i class="fas fa-clock"></i>
                                <div class="datetime-content">
                                    <span class="datetime-label">Ora</span>
                                    <span class="datetime-value" id="summary-time">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Patient Card -->
                <div class="summary-card patient-card">
                    <div class="summary-card-header">
                        <div class="summary-card-icon patient-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3>Date Pacient</h3>
                        <span class="verified-badge">
                            <i class="fas fa-shield-alt"></i> Verificat
                        </span>
                    </div>
                    <div class="summary-card-body">
                        <div class="patient-details">
                            <div class="patient-detail-row">
                                <div class="patient-detail">
                                    <i class="fas fa-user-circle"></i>
                                    <div class="detail-text">
                                        <span class="detail-label">Nume complet</span>
                                        <span class="detail-value" id="summary-name"><?= h($patient->full_name) ?></span>
                                    </div>
                                </div>
                                <div class="patient-detail">
                                    <i class="fas fa-envelope"></i>
                                    <div class="detail-text">
                                        <span class="detail-label">Email</span>
                                        <span class="detail-value" id="summary-email"><?= h($patient->email) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="patient-detail-row">
                                <div class="patient-detail">
                                    <i class="fas fa-phone-alt"></i>
                                    <div class="detail-text">
                                        <span class="detail-label">Telefon</span>
                                        <span class="detail-value" id="summary-phone"><?= h($patient->phone) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="notes-section" id="summary-notes-container">
                            <div class="notes-header-mini">
                                <i class="fas fa-sticky-note"></i>
                                <span>Observații</span>
                            </div>
                            <p class="notes-text" id="summary-notes">Fără observații</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms Agreement -->
            <div class="terms-agreement-card">
                <label class="terms-checkbox-wrapper">
                    <input type="checkbox" id="terms-agree" required>
                    <span class="custom-checkbox">
                        <i class="fas fa-check"></i>
                    </span>
                    <span class="terms-text">
                        Sunt de acord cu <a href="/pages/terms" target="_blank">termenii și condițiile</a>
                        și confirm că datele furnizate sunt corecte.
                    </span>
                </label>
            </div>

            <!-- Confirmation Notice -->
            <div class="confirmation-notice">
                <i class="fas fa-info-circle"></i>
                <p>După confirmare, veți primi un email cu detaliile programării și un link pentru confirmarea finală.</p>
            </div>

            <!-- Step Actions -->
            <div class="step-actions step-5-actions">
                <button type="button" class="btn btn-secondary prev-step">
                    <i class="fas fa-arrow-left"></i> Pasul Anterior
                </button>
                <button type="submit" class="btn btn-confirm" id="confirm-booking" disabled>
                    <span class="btn-text">
                        <i class="fas fa-check-circle"></i> Confirmă Programarea
                    </span>
                    <span class="btn-loading" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Se procesează...
                    </span>
                </button>
            </div>
        </div>

        <!-- STICKY SUMMARY BAR (outside form-step, fixed to bottom) -->
        <div id="booking-sticky-bar" class="booking-sticky-bar" style="display: none;">
            <div class="sticky-bar-content">
                <div class="sticky-bar-items">
                    <div class="sticky-item">
                        <i class="fas fa-user-md"></i>
                        <div class="sticky-item-content">
                            <span class="sticky-label">Medic</span>
                            <span class="sticky-value" id="sticky-doctor">-</span>
                        </div>
                    </div>
                    <div class="sticky-divider"></div>
                    <div class="sticky-item">
                        <i class="fas fa-calendar-day"></i>
                        <div class="sticky-item-content">
                            <span class="sticky-label">Data</span>
                            <span class="sticky-value" id="sticky-date">Selectați data</span>
                        </div>
                    </div>
                    <div class="sticky-divider"></div>
                    <div class="sticky-item">
                        <i class="fas fa-clock"></i>
                        <div class="sticky-item-content">
                            <span class="sticky-label">Ora</span>
                            <span class="sticky-value" id="sticky-time">Selectați ora</span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-sticky-continue" id="sticky-continue" disabled>
                    Continuă <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <?= $this->Form->end() ?>
    </div>
</div>

<!-- Inline styles moved to webroot/css/appointments.css -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('booking-form');
    const steps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.progress-step');
    let currentStep = 1;
    let bookingData = {};

    // Calendar State
    let calendarState = {
        currentMonth: new Date(),
        calendarData: {},
        firstAvailableDate: null,
        selectedDate: null,
        isLoading: false
    };

    // Step 1: Specialty Selection
    document.querySelectorAll('.specialty-card').forEach(card => {
        card.addEventListener('click', function() {
            console.log('Specialty card clicked:', this.dataset.specialty);
            document.querySelectorAll('.specialty-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            
            const specialty = this.dataset.specialty;
            document.getElementById('selected-specialty').value = specialty;
            bookingData.specialty = specialty;
            
            console.log('Booking data updated:', bookingData);
            enableNextButton(1);
        });
    });

    // Step 2: Load doctors when entering step
    function loadDoctors() {
        const specialty = bookingData.specialty;
        document.getElementById('display-specialty').textContent = specialty;
        
        const loadingDiv = document.getElementById('doctors-loading');
        const doctorsList = document.getElementById('doctors-list');
        
        loadingDiv.style.display = 'block';
        doctorsList.innerHTML = '';
        
        console.log('Loading doctors for specialty:', specialty);
        
        fetch('/appointments/check-availability', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': getCsrfToken()
            },
            body: JSON.stringify({
                specialty: specialty
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            loadingDiv.style.display = 'none';
            
            console.log('Response data:', data);
            
            if (data.success && data.doctors && data.doctors.length > 0) {
                data.doctors.forEach(doctor => {
                    const doctorCard = createDoctorCard(doctor);
                    doctorsList.appendChild(doctorCard);
                });
            } else if (data.message) {
                doctorsList.innerHTML = `<p class="text-center text-warning">${data.message}</p>`;
            } else {
                doctorsList.innerHTML = '<p class="text-center">Nu sunt medici disponibili pentru această specialitate.</p>';
            }
        })
        .catch(error => {
            loadingDiv.style.display = 'none';
            console.error('Error loading doctors:', error);
            doctorsList.innerHTML = '<p class="text-center text-danger">A apărut o eroare. Vă rugăm să încercați din nou.</p>';
        });
    }

    function createDoctorCard(doctor) {
        const card = document.createElement('div');
        card.className = 'doctor-card';
        card.dataset.doctorId = doctor.id;

        const photoUrl = doctor.photo || '/img/default-doctor.png';
        const serviceCount = doctor.services.length;

        card.innerHTML = `
            <div class="doctor-card-inner">
                <div class="doctor-photo-container">
                    <img src="${photoUrl}" alt="${doctor.name}" class="doctor-photo" onerror="this.src='/img/default-doctor.png'">
                </div>
                <div class="doctor-content">
                    <h3 class="doctor-name">${doctor.name}</h3>
                    <div class="doctor-meta">
                        <span class="services-count">
                            <i class="fas fa-briefcase-medical"></i>
                            ${serviceCount} ${serviceCount === 1 ? 'serviciu disponibil' : 'servicii disponibile'}
                        </span>
                    </div>
                    <div class="service-list">
                        ${doctor.services.map(service => `
                            <div class="service-item" data-service-id="${service.id}" data-service-name="${service.name}">
                                <span class="service-name">${service.name}</span>
                                <span class="service-details">${service.duration_minutes} min${service.price ? ' • ' + service.price + ' RON' : ''}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
                <div class="doctor-card-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        `;
        
        // Add click handlers
        const serviceItems = card.querySelectorAll('.service-item');
        serviceItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.stopPropagation();
                
                // Deselect all other services across all doctors
                document.querySelectorAll('.service-item').forEach(s => s.classList.remove('selected'));
                document.querySelectorAll('.doctor-card').forEach(d => d.classList.remove('selected'));
                
                // Select this service and doctor
                this.classList.add('selected');
                card.classList.add('selected');
                
                // Store selection
                bookingData.doctorId = doctor.id;
                bookingData.doctorName = doctor.name;
                bookingData.serviceId = this.dataset.serviceId;
                bookingData.serviceName = this.dataset.serviceName;
                
                document.getElementById('selected-doctor').value = doctor.id;
                document.getElementById('selected-service').value = this.dataset.serviceId;
                
                enableNextButton(2);
            });
        });
        
        return card;
    }

    // ===========================================
    // STEP 3: CALENDAR AND TIME SLOT LOGIC
    // ===========================================

    // Initialize calendar when entering step 3
    function initializeCalendar() {
        if (!bookingData.doctorId || !bookingData.serviceId) return;

        const today = new Date();
        today.setHours(0, 0, 0, 0);
        calendarState.currentMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        calendarState.selectedDate = null;

        // Update step 3 display info
        document.getElementById('step3-doctor-name').textContent = bookingData.doctorName || '-';
        document.getElementById('step3-service-name').textContent = bookingData.serviceName || '-';

        // Show sticky bar
        const stickyBar = document.getElementById('booking-sticky-bar');
        if (stickyBar) {
            stickyBar.style.display = 'block';
            stickyBar.classList.add('visible');
            document.getElementById('sticky-doctor').textContent = bookingData.doctorName || '-';
            document.getElementById('sticky-date').textContent = 'Selectați data';
            document.getElementById('sticky-time').textContent = 'Selectați ora';
            document.getElementById('sticky-continue').disabled = true;
        }

        loadCalendarMonth();
    }

    // Recursion guard for auto-selection
    let isAutoSelecting = false;

    // Load calendar availability data from API
    function loadCalendarMonth() {
        if (calendarState.isLoading) return;
        calendarState.isLoading = true;

        const startDate = formatDate(calendarState.currentMonth);

        fetch('/appointments/get-calendar-availability', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': getCsrfToken()
            },
            body: JSON.stringify({
                doctor_id: bookingData.doctorId,
                service_id: bookingData.serviceId,
                start_date: startDate,
                days: 31
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                calendarState.calendarData = data.calendar;
                calendarState.firstAvailableDate = data.first_available_date;
                renderCalendar();

                // Auto-select first available date if no date selected yet
                if (!calendarState.selectedDate && calendarState.firstAvailableDate) {
                    if (isAutoSelecting) {
                        // We navigated to this month to select the first available date
                        // The date should now be in view, so select it directly
                        selectCalendarDate(calendarState.firstAvailableDate);
                        isAutoSelecting = false;
                    } else {
                        autoSelectFirstAvailable();
                    }
                }
            } else {
                console.error('Calendar API error:', data.message);
                renderCalendarError(data.message || 'Eroare la încărcarea calendarului');
                isAutoSelecting = false;
            }
        })
        .catch(error => {
            console.error('Calendar fetch error:', error);
            renderCalendarError('Eroare la încărcarea calendarului');
            isAutoSelecting = false;
        })
        .finally(() => {
            calendarState.isLoading = false;
        });
    }

    // Render the calendar grid
    function renderCalendar() {
        const calendarDays = document.getElementById('calendar-days');
        const monthYearDisplay = document.getElementById('calendar-month-year');

        if (!calendarDays || !monthYearDisplay) return;

        // Update month/year display
        monthYearDisplay.textContent = formatMonthYear(calendarState.currentMonth);

        // Generate days array including padding from prev/next months
        const days = generateMonthDays(calendarState.currentMonth);

        // Clear and rebuild calendar
        calendarDays.innerHTML = '';

        days.forEach(day => {
            const dateStr = formatDate(day.date);
            const dayData = calendarState.calendarData[dateStr] || { status: 'out-of-range' };

            const dayEl = document.createElement('div');
            dayEl.className = 'calendar-day';
            dayEl.dataset.date = dateStr;

            // Add status class
            if (day.isOtherMonth) {
                dayEl.classList.add('out-of-range');
            } else {
                dayEl.classList.add(dayData.status);
            }

            // Mark today
            if (day.isToday && !day.isOtherMonth) {
                dayEl.classList.add('today');
            }

            // Mark selected
            if (calendarState.selectedDate === dateStr) {
                dayEl.classList.add('selected');
            }

            // Determine if clickable
            const isClickable = !day.isOtherMonth && ['available', 'partial'].includes(dayData.status);
            if (!isClickable) {
                dayEl.classList.add('disabled');
                dayEl.style.pointerEvents = 'none';
            }

            // Build inner HTML
            dayEl.innerHTML = `
                <span class="day-number">${day.dayNumber}</span>
                ${!day.isOtherMonth ? '<span class="day-indicator"></span>' : ''}
            `;

            // Add tooltip for holidays/unavailable
            if (dayData.label && !day.isOtherMonth) {
                dayEl.title = dayData.label;
            }

            // Click handler for available days
            if (isClickable) {
                dayEl.addEventListener('click', function() {
                    selectCalendarDate(dateStr);
                });
            }

            calendarDays.appendChild(dayEl);
        });

        // Update navigation buttons
        updateCalendarNavigation();
    }

    // Render error state in calendar
    function renderCalendarError(message) {
        const calendarDays = document.getElementById('calendar-days');
        if (calendarDays) {
            calendarDays.innerHTML = `
                <div style="grid-column: span 7; text-align: center; padding: 40px 20px; color: #dc2626;">
                    <i class="fas fa-exclamation-triangle fa-2x" style="margin-bottom: 10px;"></i>
                    <p>${message}</p>
                </div>
            `;
        }
    }

    // Generate array of days for the month
    function generateMonthDays(date) {
        const year = date.getFullYear();
        const month = date.getMonth();
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startDayOfWeek = firstDay.getDay() || 7; // Convert Sunday 0 to 7
        const days = [];

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Add days from previous month (padding)
        for (let i = 1; i < startDayOfWeek; i++) {
            const d = new Date(year, month, 1 - (startDayOfWeek - i));
            days.push({
                date: d,
                dayNumber: d.getDate(),
                isOtherMonth: true,
                isToday: false
            });
        }

        // Add current month days
        for (let i = 1; i <= lastDay.getDate(); i++) {
            const d = new Date(year, month, i);
            days.push({
                date: d,
                dayNumber: i,
                isOtherMonth: false,
                isToday: d.getTime() === today.getTime()
            });
        }

        // Add days from next month (padding to fill 6 rows)
        const remaining = 42 - days.length;
        for (let i = 1; i <= remaining; i++) {
            const d = new Date(year, month + 1, i);
            days.push({
                date: d,
                dayNumber: i,
                isOtherMonth: true,
                isToday: false
            });
        }

        return days;
    }

    // Select a date in the calendar
    function selectCalendarDate(dateStr) {
        // Update state
        calendarState.selectedDate = dateStr;
        bookingData.appointmentDate = dateStr;
        bookingData.appointmentTime = null; // Reset time when date changes

        // Update hidden field
        document.getElementById('appointment-date').value = dateStr;
        document.getElementById('selected-time').value = '';

        // Update visual selection in calendar
        document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
        const dayEl = document.querySelector(`.calendar-day[data-date="${dateStr}"]`);
        if (dayEl) {
            dayEl.classList.add('selected');
        }

        // Update date display badge
        const displayEl = document.getElementById('selected-date-display');
        if (displayEl) {
            displayEl.textContent = formatDisplayDate(dateStr);
        }

        // Update sticky bar
        updateStickyBarDate(dateStr);
        updateStickyBarTime(null);

        // Disable next button until time is selected
        const nextBtn = steps[2].querySelector('.next-step');
        if (nextBtn) nextBtn.disabled = true;

        // Load time slots for this date
        loadTimeSlotsV2(bookingData.doctorId, dateStr, bookingData.serviceId);
    }

    // Auto-select first available date
    function autoSelectFirstAvailable() {
        // Guard against recursive calls (loadCalendarMonth -> autoSelectFirstAvailable -> loadCalendarMonth)
        if (isAutoSelecting) return;

        if (calendarState.firstAvailableDate) {
            // Check if the date is in the current month view
            const firstAvail = new Date(calendarState.firstAvailableDate + 'T00:00:00');
            const viewMonth = calendarState.currentMonth.getMonth();
            const viewYear = calendarState.currentMonth.getFullYear();

            if (firstAvail.getMonth() !== viewMonth || firstAvail.getFullYear() !== viewYear) {
                // Navigate to the month containing first available date
                isAutoSelecting = true;
                calendarState.currentMonth = new Date(firstAvail.getFullYear(), firstAvail.getMonth(), 1);
                loadCalendarMonth();
                // Note: isAutoSelecting will be reset after selectCalendarDate is called
            } else {
                selectCalendarDate(calendarState.firstAvailableDate);
                isAutoSelecting = false;
            }
        }
    }

    // Update calendar navigation buttons
    function updateCalendarNavigation() {
        const prevBtn = document.getElementById('prev-month');
        const nextBtn = document.getElementById('next-month');

        if (!prevBtn || !nextBtn) return;

        const today = new Date();
        const currentViewMonth = new Date(calendarState.currentMonth.getFullYear(), calendarState.currentMonth.getMonth(), 1);
        const thisMonth = new Date(today.getFullYear(), today.getMonth(), 1);

        // Disable prev if viewing current month
        prevBtn.disabled = currentViewMonth <= thisMonth;

        // Disable next if viewing month + 2 (limit to ~2 months ahead)
        const maxMonth = new Date(today.getFullYear(), today.getMonth() + 2, 1);
        nextBtn.disabled = currentViewMonth >= maxMonth;
    }

    // Helper: Format date as YYYY-MM-DD
    function formatDate(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    // Helper: Format month/year for display (Romanian)
    function formatMonthYear(date) {
        const months = ['Ianuarie', 'Februarie', 'Martie', 'Aprilie', 'Mai', 'Iunie',
                       'Iulie', 'August', 'Septembrie', 'Octombrie', 'Noiembrie', 'Decembrie'];
        return `${months[date.getMonth()]} ${date.getFullYear()}`;
    }

    // Helper: Format date for display (Romanian)
    function formatDisplayDate(dateStr) {
        const d = new Date(dateStr + 'T00:00:00');
        const days = ['Duminică', 'Luni', 'Marți', 'Miercuri', 'Joi', 'Vineri', 'Sâmbătă'];
        const months = ['ianuarie', 'februarie', 'martie', 'aprilie', 'mai', 'iunie',
                       'iulie', 'august', 'septembrie', 'octombrie', 'noiembrie', 'decembrie'];
        return `${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
    }

    // Helper: Update sticky bar date
    function updateStickyBarDate(dateStr) {
        const el = document.getElementById('sticky-date');
        if (el) {
            el.textContent = dateStr ? formatDisplayDate(dateStr) : 'Selectați data';
        }
    }

    // Helper: Update sticky bar time
    function updateStickyBarTime(time) {
        const el = document.getElementById('sticky-time');
        if (el) {
            el.textContent = time || 'Selectați ora';
        }

        // Update continue button state
        const continueBtn = document.getElementById('sticky-continue');
        if (continueBtn) {
            continueBtn.disabled = !(calendarState.selectedDate && time);
        }
    }

    // Load time slots for selected date (using v2 containers)
    function loadTimeSlotsV2(doctorId, date, serviceId) {
        const placeholder = document.getElementById('slots-placeholder');
        const loading = document.getElementById('slots-loading-v2');
        const grid = document.getElementById('time-slots-grid-v2');
        const noSlots = document.getElementById('no-slots-v2');
        const legend = document.getElementById('slots-legend-v2');

        // Show loading state
        if (placeholder) placeholder.style.display = 'none';
        if (loading) loading.style.display = 'block';
        if (grid) grid.style.display = 'none';
        if (noSlots) noSlots.style.display = 'none';
        if (legend) legend.style.display = 'none';

        fetch('/appointments/get-available-slots', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': getCsrfToken()
            },
            body: JSON.stringify({
                doctor_id: doctorId,
                date: date,
                service_id: serviceId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (loading) loading.style.display = 'none';

            if (data.success && data.slots && data.slots.length > 0) {
                renderTimeSlotsV2(data.slots, date);
                if (grid) grid.style.display = 'grid';
                if (legend) legend.style.display = 'flex';
            } else {
                if (noSlots) noSlots.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Time slots error:', error);
            if (loading) loading.style.display = 'none';
            if (noSlots) {
                noSlots.innerHTML = `
                    <i class="fas fa-exclamation-triangle fa-3x"></i>
                    <p>Eroare la încărcarea orelor disponibile.</p>
                `;
                noSlots.style.display = 'block';
            }
        });
    }

    // Render time slots in v2 grid
    function renderTimeSlotsV2(slots, date) {
        const grid = document.getElementById('time-slots-grid-v2');
        if (!grid) return;

        grid.innerHTML = '';

        slots.forEach((slot, index) => {
            const slotEl = document.createElement('div');
            slotEl.className = 'time-slot-v2';
            slotEl.dataset.time = slot.time;

            if (!slot.available) {
                slotEl.classList.add('occupied');
            }

            // Parse time for display
            const [hour, minute] = slot.time.split(':');
            const hourNum = parseInt(hour);
            const period = hourNum >= 12 ? 'PM' : 'AM';
            const displayHour = hourNum > 12 ? hourNum - 12 : (hourNum === 0 ? 12 : hourNum);

            slotEl.innerHTML = `
                <div class="slot-time">${displayHour}:${minute}</div>
                <div class="slot-period">${period}</div>
            `;

            if (slot.available) {
                slotEl.addEventListener('click', function() {
                    selectTimeSlot(this, slot.time);
                });
            }

            // Staggered animation
            slotEl.style.opacity = '0';
            slotEl.style.transform = 'translateY(10px)';
            setTimeout(() => {
                slotEl.style.transition = 'all 0.3s ease';
                slotEl.style.opacity = '1';
                slotEl.style.transform = 'translateY(0)';
            }, index * 30);

            grid.appendChild(slotEl);
        });
    }

    // Select a time slot
    function selectTimeSlot(element, time) {
        // Remove previous selection
        document.querySelectorAll('.time-slot-v2').forEach(s => s.classList.remove('selected'));

        // Add selection to clicked slot
        element.classList.add('selected');

        // Update state
        bookingData.appointmentTime = time;
        document.getElementById('selected-time').value = time;

        // Update sticky bar
        updateStickyBarTime(time);

        // Enable next button
        enableNextButton(3);
    }

    // Navigate to previous month (create new Date to avoid mutation bugs)
    function goToPrevMonth() {
        const newDate = new Date(calendarState.currentMonth);
        newDate.setMonth(newDate.getMonth() - 1);
        calendarState.currentMonth = newDate;
        loadCalendarMonth();
    }

    // Navigate to next month (create new Date to avoid mutation bugs)
    function goToNextMonth() {
        const newDate = new Date(calendarState.currentMonth);
        newDate.setMonth(newDate.getMonth() + 1);
        calendarState.currentMonth = newDate;
        loadCalendarMonth();
    }

    // Hide sticky bar (when leaving step 3)
    function hideStickyBar() {
        const stickyBar = document.getElementById('booking-sticky-bar');
        if (stickyBar) {
            stickyBar.classList.remove('visible');
            setTimeout(() => {
                stickyBar.style.display = 'none';
            }, 300);
        }
    }

    // Calendar navigation event listeners
    document.getElementById('prev-month')?.addEventListener('click', goToPrevMonth);
    document.getElementById('next-month')?.addEventListener('click', goToNextMonth);
    document.getElementById('goto-first-available')?.addEventListener('click', autoSelectFirstAvailable);
    document.getElementById('suggest-next-day')?.addEventListener('click', function() {
        // Find next available date after current selection
        if (calendarState.selectedDate) {
            const dates = Object.keys(calendarState.calendarData).sort();
            const currentIndex = dates.indexOf(calendarState.selectedDate);
            for (let i = currentIndex + 1; i < dates.length; i++) {
                const status = calendarState.calendarData[dates[i]]?.status;
                if (status === 'available' || status === 'partial') {
                    selectCalendarDate(dates[i]);
                    break;
                }
            }
        } else {
            autoSelectFirstAvailable();
        }
    });

    // Sticky bar continue button
    document.getElementById('sticky-continue')?.addEventListener('click', function() {
        if (calendarState.selectedDate && bookingData.appointmentTime) {
            const nextBtn = steps[2].querySelector('.next-step');
            if (nextBtn && !nextBtn.disabled) {
                nextBtn.click();
            }
        }
    });

    // Keep old loadTimeSlots for backwards compatibility
    function loadTimeSlots(doctorId, date, serviceId) {
        loadTimeSlotsV2(doctorId, date, serviceId);
    }

    // Step navigation
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', function() {
            if (currentStep < 5) {
                // Special handling for step 3 to step 4
                if (currentStep === 3) {
                    updateDisplayInfo();
                }
                
                // Special handling for step 4 to step 5
                if (currentStep === 4) {
                    if (validatePatientData()) {
                        updateSummary();
                        goToStep(currentStep + 1);
                    }
                    return;
                }
                
                goToStep(currentStep + 1);
                
                // Load doctors when entering step 2
                if (currentStep === 2) {
                    loadDoctors();
                }
            }
        });
    });

    document.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', function() {
            if (currentStep > 1) {
                goToStep(currentStep - 1);
            }
        });
    });

    function goToStep(step) {
        steps.forEach(s => s.classList.remove('active'));
        progressSteps.forEach(p => {
            p.classList.remove('active');
            p.classList.remove('completed');
        });

        // Also reset connectors
        document.querySelectorAll('.progress-connector').forEach(c => {
            c.classList.remove('completed');
        });

        currentStep = step;
        steps[step - 1].classList.add('active');

        // Update progress steps and connectors
        const connectors = document.querySelectorAll('.progress-connector');
        for (let i = 0; i < progressSteps.length; i++) {
            if (i < step) {
                progressSteps[i].classList.add('active');
                if (i < step - 1) {
                    progressSteps[i].classList.add('completed');
                    // Mark the connector after this step as completed
                    if (connectors[i]) {
                        connectors[i].classList.add('completed');
                    }
                }
            }
        }

        // Handle step-specific logic
        if (step === 3) {
            // Initialize calendar when entering step 3
            initializeCalendar();
        } else {
            // Hide sticky bar when not on step 3
            hideStickyBar();
        }
    }

    function enableNextButton(step) {
        const button = steps[step - 1].querySelector('.next-step');
        if (button) {
            button.disabled = false;
        }
    }

    function updateDisplayInfo() {
        // Update step 3 info card (if visible)
        const step3Doctor = document.getElementById('step3-doctor-name');
        const step3Service = document.getElementById('step3-service-name');
        if (step3Doctor) step3Doctor.textContent = bookingData.doctorName || '-';
        if (step3Service) step3Service.textContent = bookingData.serviceName || '-';
    }

    function validatePatientData() {
        // Patient data is already validated and stored in session
        // No additional validation needed as data comes from authenticated patient
        return true;
    }

    function updateSummary() {
        // Medical details
        document.getElementById('summary-specialty').textContent = bookingData.specialty || '-';
        document.getElementById('summary-doctor').textContent = bookingData.doctorName || '-';
        document.getElementById('summary-service').textContent = bookingData.serviceName || '-';

        // Format date nicely in Romanian
        const date = new Date(bookingData.appointmentDate);
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('summary-date').textContent = date.toLocaleDateString('ro-RO', dateOptions);

        // Format time to be user-friendly
        document.getElementById('summary-time').textContent = bookingData.appointmentTime || '-';

        // Update notes section
        const notesValue = document.getElementById('notes').value;
        const summaryNotes = document.getElementById('summary-notes');
        const notesContainer = document.getElementById('summary-notes-container');

        if (notesValue && notesValue.trim()) {
            summaryNotes.textContent = notesValue;
            if (notesContainer) notesContainer.style.display = 'block';
        } else {
            summaryNotes.textContent = 'Fără observații';
            // Still show the section but with default text
            if (notesContainer) notesContainer.style.display = 'block';
        }
    }

    // Terms agreement checkbox
    document.getElementById('terms-agree').addEventListener('change', function() {
        document.getElementById('confirm-booking').disabled = !this.checked;
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Always prevent default first

        const submitButton = document.getElementById('confirm-booking');
        const btnText = submitButton.querySelector('.btn-text');
        const btnLoading = submitButton.querySelector('.btn-loading');

        if (!document.getElementById('terms-agree').checked) {
            alert('Vă rugăm să acceptați termenii și condițiile.');
            return;
        }

        // Disable submit button and show loading state
        submitButton.disabled = true;
        if (btnText) btnText.style.display = 'none';
        if (btnLoading) btnLoading.style.display = 'inline-flex';
        
        // Log form data for debugging
        const formData = {
            specialty: document.getElementById('selected-specialty').value,
            doctor_id: document.getElementById('selected-doctor').value,
            service_id: document.getElementById('selected-service').value,
            appointment_date: document.getElementById('appointment-date').value,
            appointment_time: document.getElementById('selected-time').value,
            patient_id: document.getElementById('patient-id').value,
            notes: document.getElementById('notes').value
        };
        
        console.log('Submitting form with data:', formData);
        
        // Submit form using fetch to see the response
        const form = e.target;
        const formDataObj = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formDataObj,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response URL:', response.url);
            
            // Check if we were redirected
            if (response.redirected) {
                console.log('Redirected to:', response.url);
                // If redirected to success page, go there
                if (response.url.includes('/appointments/success/')) {
                    window.location.href = response.url;
                    return null;
                }
            }
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                // For HTML responses, check the URL first
                return response.text().then(text => ({
                    html: text,
                    url: response.url
                }));
            }
        })
        .then(data => {
            if (!data) return; // Already redirected
            
            if (data.html) {
                // HTML response
                console.log('Response URL:', data.url);
                console.log('Response HTML preview:', data.html.substring(0, 500));
                
                // Check if we're already on the success page by URL
                if (data.url && data.url.includes('/appointments/success/')) {
                    window.location.href = data.url;
                    return;
                }
                
                // Check if the response contains success page elements
                if (data.html.includes('appointment-success') || data.html.includes('Programare Înregistrată cu Succes')) {
                    // Extract the appointment ID from the HTML if possible
                    const match = data.html.match(/\/appointments\/success\/(\d+)/);
                    if (match && match[1]) {
                        window.location.href = `/appointments/success/${match[1]}`;
                    } else {
                        // Try to find appointment ID in the response
                        const idMatch = data.html.match(/appointment-(\d+)/);
                        if (idMatch && idMatch[1]) {
                            window.location.href = `/appointments/success/${idMatch[1]}`;
                        } else {
                            // Just go to appointments page and let server redirect handle it
                            window.location.href = '/appointments';
                        }
                    }
                } else if (data.html.includes('Flash__error') || data.html.includes('eroare')) {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data.html, 'text/html');
                    const errorDiv = doc.querySelector('.alert-danger, .message.error');
                    if (errorDiv) {
                        alert('Eroare: ' + errorDiv.textContent.trim());
                    } else {
                        alert('A apărut o eroare. Verificați consola pentru detalii.');
                    }
                    console.error('Full response:', data.html);
                    // Restore button state on error
                    resetSubmitButton();
                } else {
                    // Unknown HTML response - log it and show error
                    console.error('Unexpected HTML response, not success or error page');
                    console.log('Full HTML:', data.html);
                    alert('A apărut o eroare neașteptată. Vă rugăm să încercați din nou.');
                    // Restore button state
                    resetSubmitButton();
                }
            } else {
                // JSON response
                console.log('JSON response:', data);
                if (data.success) {
                    console.log('Success! Redirecting to:', data.redirect);
                    // Use the redirect URL directly from the response
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else if (data.appointment_id) {
                        // Fallback: build URL with appointment ID
                        window.location.href = `/appointments/success/${data.appointment_id}`;
                    } else {
                        // Last fallback
                        window.location.href = '/appointments';
                    }
                } else {
                    // Show errors
                    let errorMessage = data.message || 'A apărut o eroare.';
                    if (data.errors) {
                        console.error('Validation errors:', data.errors);
                        // Extract first error message
                        for (let field in data.errors) {
                            for (let rule in data.errors[field]) {
                                errorMessage = data.errors[field][rule];
                                break;
                            }
                            break;
                        }
                    }
                    alert(errorMessage);
                    // Restore button state on error
                    resetSubmitButton();
                }
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Eroare de rețea: ' + error.message);
            // Restore button state on error
            resetSubmitButton();
        });

        // Helper function to reset submit button state
        function resetSubmitButton() {
            const submitBtn = document.getElementById('confirm-booking');
            const btnTextEl = submitBtn.querySelector('.btn-text');
            const btnLoadingEl = submitBtn.querySelector('.btn-loading');
            submitBtn.disabled = false;
            if (btnTextEl) btnTextEl.style.display = 'inline-flex';
            if (btnLoadingEl) btnLoadingEl.style.display = 'none';
        }
    });

    function getCsrfToken() {
        // Look for CSRF token in meta tag first
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            return token.getAttribute('content');
        }

        // If not found, get it from the form
        const csrfField = document.querySelector('input[name="_csrfToken"]');
        return csrfField ? csrfField.value : '';
    }
});
</script>