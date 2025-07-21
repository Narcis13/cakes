<?php
/**
 * @var \App\View\AppView $this
 * @var array $specializations
 * @var array $services
 */
?>

<div class="appointments-booking">
    <div class="container">
        <h1>Programare Online</h1>
        
        <!-- Progress Bar -->
        <div class="booking-progress">
            <div class="progress-bar">
                <div class="progress-step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-label">Specialitate</div>
                </div>
                <div class="progress-step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label">Medic</div>
                </div>
                <div class="progress-step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-label">Data și Ora</div>
                </div>
                <div class="progress-step" data-step="4">
                    <div class="step-number">4</div>
                    <div class="step-label">Date Personale</div>
                </div>
                <div class="progress-step" data-step="5">
                    <div class="step-number">5</div>
                    <div class="step-label">Confirmare</div>
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
            <h2>Pasul 1: Selectați Specialitatea Medicală</h2>
            <p class="step-description">Vă rugăm să selectați specialitatea medicală de care aveți nevoie.</p>
            
            <div class="specialty-grid">
                <?php foreach ($specializations as $spec): ?>
                    <div class="specialty-card" data-specialty="<?= h($spec['value']) ?>">
                        <div class="specialty-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h3><?= h($spec['text']) ?></h3>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?= $this->Form->hidden('specialty', ['id' => 'selected-specialty']) ?>
            
            <div class="step-actions">
                <button type="button" class="btn btn-primary next-step" disabled>
                    Următorul Pas <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Step 2: View Available Doctors -->
        <div class="form-step" data-step="2">
            <h2>Pasul 2: Selectați Medicul</h2>
            <p class="step-description">Alegeți medicul la care doriți să vă programați.</p>
            
            <div class="selected-specialty-info">
                <strong>Specialitate selectată:</strong> <span id="display-specialty"></span>
            </div>
            
            <div id="doctors-loading" class="text-center" style="display: none;">
                <i class="fas fa-spinner fa-spin fa-3x"></i>
                <p>Se încarcă medicii disponibili...</p>
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

        <!-- Step 3: Select Date and Time -->
        <div class="form-step" data-step="3">
            <h2>Pasul 3: Selectați Data și Ora</h2>
            <p class="step-description">Alegeți data și ora convenabilă pentru programare.</p>
            
            <div class="selected-info-card">
                <div class="selected-doctor-info">
                    <i class="fas fa-user-md"></i>
                    <div>
                        <p class="info-label">Medic selectat</p>
                        <p class="info-value" id="display-doctor"></p>
                    </div>
                </div>
                <div class="selected-service-info">
                    <i class="fas fa-stethoscope"></i>
                    <div>
                        <p class="info-label">Serviciu selectat</p>
                        <p class="info-value" id="display-service"></p>
                    </div>
                </div>
            </div>
            
            <div class="date-time-selection">
                <div class="date-selection-section">
                    <h4><i class="fas fa-calendar-alt"></i> Selectați Data</h4>
                    <div class="calendar-wrapper">
                        <?= $this->Form->control('appointment_date', [
                            'type' => 'date',
                            'label' => false,
                            'min' => date('Y-m-d'),
                            'max' => date('Y-m-d', strtotime('+90 days')),
                            'class' => 'form-control date-input',
                            'id' => 'appointment-date'
                        ]) ?>
                        <div class="date-helper-text">Puteți programa pentru următoarele 90 de zile</div>
                    </div>
                </div>
                
                <div class="time-selection-section">
                    <h4><i class="fas fa-clock"></i> Selectați Ora</h4>
                    <div id="time-slots-container">
                        <div id="time-slots-loading" class="slots-loading" style="display: none;">
                            <div class="spinner-wrapper">
                                <i class="fas fa-spinner fa-spin fa-2x"></i>
                                <p>Se încarcă orele disponibile...</p>
                            </div>
                        </div>
                        <div id="no-slots-message" class="no-slots-message" style="display: none;">
                            <i class="fas fa-calendar-times fa-3x"></i>
                            <p>Nu sunt ore disponibile pentru această dată.</p>
                            <p class="text-muted">Vă rugăm să selectați o altă dată.</p>
                        </div>
                        <div id="time-slots" class="time-slots-grid"></div>
                        <?= $this->Form->hidden('appointment_time', ['id' => 'selected-time']) ?>
                    </div>
                </div>
            </div>
            
            <div class="step-actions">
                <button type="button" class="btn btn-secondary prev-step">
                    <i class="fas fa-arrow-left"></i> Pasul Anterior
                </button>
                <button type="button" class="btn btn-primary next-step" disabled>
                    Următorul Pas <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Step 4: Enter Patient Details -->
        <div class="form-step" data-step="4">
            <h2>Pasul 4: Introduceți Datele Personale</h2>
            <p class="step-description">Vă rugăm să completați datele dumneavoastră de contact.</p>
            
            <div class="row">
                <div class="col-md-6">
                    <?= $this->Form->control('patient_name', [
                        'label' => 'Nume și Prenume *',
                        'class' => 'form-control',
                        'required' => true,
                        'maxlength' => 100
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $this->Form->control('patient_email', [
                        'type' => 'email',
                        'label' => 'Adresă Email *',
                        'class' => 'form-control',
                        'required' => true,
                        'maxlength' => 100
                    ]) ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <?= $this->Form->control('patient_phone', [
                        'label' => 'Număr de Telefon *',
                        'class' => 'form-control',
                        'required' => true,
                        'maxlength' => 20,
                        'placeholder' => 'Ex: 0722 123 456'
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $this->Form->control('patient_cnp', [
                        'label' => 'CNP (opțional)',
                        'class' => 'form-control',
                        'maxlength' => 13
                    ]) ?>
                </div>
            </div>
            
            <div class="form-group">
                <?= $this->Form->control('notes', [
                    'type' => 'textarea',
                    'label' => 'Observații (opțional)',
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Menționați aici orice informații relevante pentru consultație'
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

        <!-- Step 5: Review and Confirm -->
        <div class="form-step" data-step="5">
            <h2>Pasul 5: Verificați și Confirmați</h2>
            <p class="step-description">Vă rugăm să verificați toate detaliile programării înainte de confirmare.</p>
            
            <div class="booking-summary">
                <h3>Rezumat Programare</h3>
                
                <div class="summary-section">
                    <h4>Detalii Medicale</h4>
                    <dl class="row">
                        <dt class="col-sm-4">Specialitate:</dt>
                        <dd class="col-sm-8" id="summary-specialty"></dd>
                        
                        <dt class="col-sm-4">Medic:</dt>
                        <dd class="col-sm-8" id="summary-doctor"></dd>
                        
                        <dt class="col-sm-4">Serviciu:</dt>
                        <dd class="col-sm-8" id="summary-service"></dd>
                        
                        <dt class="col-sm-4">Data:</dt>
                        <dd class="col-sm-8" id="summary-date"></dd>
                        
                        <dt class="col-sm-4">Ora:</dt>
                        <dd class="col-sm-8" id="summary-time"></dd>
                    </dl>
                </div>
                
                <div class="summary-section">
                    <h4>Date Personale</h4>
                    <dl class="row">
                        <dt class="col-sm-4">Nume:</dt>
                        <dd class="col-sm-8" id="summary-name"></dd>
                        
                        <dt class="col-sm-4">Email:</dt>
                        <dd class="col-sm-8" id="summary-email"></dd>
                        
                        <dt class="col-sm-4">Telefon:</dt>
                        <dd class="col-sm-8" id="summary-phone"></dd>
                        
                        <dt class="col-sm-4">CNP:</dt>
                        <dd class="col-sm-8" id="summary-cnp"></dd>
                        
                        <dt class="col-sm-4">Observații:</dt>
                        <dd class="col-sm-8" id="summary-notes"></dd>
                    </dl>
                </div>
            </div>
            
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="terms-agree" required>
                <label class="form-check-label" for="terms-agree">
                    Sunt de acord cu <a href="/pages/terms" target="_blank">termenii și condițiile</a> și confirm că datele furnizate sunt corecte.
                </label>
            </div>
            
            <div class="step-actions">
                <button type="button" class="btn btn-secondary prev-step">
                    <i class="fas fa-arrow-left"></i> Pasul Anterior
                </button>
                <button type="submit" class="btn btn-success" id="confirm-booking" disabled>
                    <i class="fas fa-check"></i> Confirmă Programarea
                </button>
            </div>
        </div>
        
        <?= $this->Form->end() ?>
    </div>
</div>

<style>
.appointments-booking {
    padding: 40px 0;
    min-height: 80vh;
}

/* Ensure container uses full width */
.appointments-booking .container {
    max-width: 1200px;
    width: 100%;
    padding: 0 15px;
    margin: 0 auto;
}

/* Clearfix for progress bar */
.booking-progress::after {
    content: "";
    display: table;
    clear: both;
}

.appointments-booking h1 {
    text-align: center;
    margin-bottom: 40px;
    color: #333;
    font-size: 32px;
    font-weight: 600;
}

.booking-progress {
    margin-bottom: 40px;
    background: #f8f9fa;
    padding: 30px 20px;
    border-radius: 8px;
    overflow: hidden;
}

.progress-bar {
    display: flex !important;
    flex-direction: row !important;
    justify-content: space-between;
    align-items: center;
    position: relative;
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
    width: 100%;
}

.progress-bar::before {
    content: '';
    position: absolute;
    top: 40px;
    left: 10%;
    right: 10%;
    height: 2px;
    background: #e0e0e0;
    z-index: 0;
}

.progress-step {
    position: relative;
    flex: 1;
    text-align: center;
    z-index: 1;
}

.progress-step:first-child {
    flex: 1;
}

.progress-step:last-child {
    flex: 1;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e0e0e0;
    color: #666;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
    font-weight: bold;
    border: 3px solid #fff;
    position: relative;
    z-index: 2;
    box-shadow: 0 0 0 4px #f8f9fa;
}

.progress-step.active .step-number,
.progress-step.completed .step-number {
    background: #007bff;
    color: #fff;
}

.progress-step.completed .step-number {
    background: #28a745;
}

.progress-step.completed .step-number::after {
    content: '✓';
    position: absolute;
    font-size: 16px;
    color: #fff;
}

.progress-step.completed::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 2px;
    background: #28a745;
    left: 50%;
    top: 20px;
    z-index: 0;
}

.progress-step:last-child.completed::after {
    display: none;
}

.step-label {
    font-size: 13px;
    color: #666;
    line-height: 1.2;
    max-width: 90px;
    word-wrap: break-word;
}

.progress-step.active .step-label {
    color: #007bff;
    font-weight: 600;
}

.progress-step.completed .step-label {
    color: #28a745;
    font-weight: 500;
}

.form-step {
    display: none;
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.form-step.active {
    display: block;
}

.step-actions {
    margin-top: 30px;
    display: flex;
    justify-content: space-between;
}

.specialty-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.specialty-card {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
}

.specialty-card:hover {
    border-color: #007bff;
    transform: translateY(-2px);
}

.specialty-card.selected {
    border-color: #007bff;
    background: #f0f8ff;
}

.specialty-icon {
    font-size: 40px;
    color: #007bff;
    margin-bottom: 10px;
}

.doctors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.doctor-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    cursor: pointer;
    transition: all 0.3s;
}

.doctor-card:hover {
    border-color: #007bff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.doctor-card.selected {
    border-color: #007bff;
    background: #f0f8ff;
}

.doctor-info {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.doctor-photo {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    margin-right: 15px;
    object-fit: cover;
}

.doctor-details h4 {
    margin: 0 0 5px 0;
    color: #333;
}

.doctor-details p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.service-list {
    margin-top: 15px;
}

.service-item {
    padding: 8px 12px;
    background: #f8f9fa;
    border-radius: 4px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.service-item:hover {
    background: #e9ecef;
}

.service-item.selected {
    background: #007bff;
    color: #fff;
}

.time-slots-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 12px;
}

.time-slot {
    padding: 10px;
    text-align: center;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
}

.time-slot:hover:not(.unavailable) {
    border-color: #007bff;
    background: #f0f8ff;
}

/* Responsive Styles for Progress Bar */
@media (max-width: 768px) {
    .progress-bar {
        padding: 0 5px;
        display: flex !important;
        flex-wrap: nowrap !important;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
    }
    
    .progress-bar::before {
        display: none;
    }
    
    .progress-step {
        min-width: 80px;
        flex: 0 0 auto;
        display: inline-flex !important;
        padding: 0 5px;
    }
    
    .progress-step:first-child,
    .progress-step:last-child {
        flex: 0 0 auto;
    }
    
    .step-number {
        width: 35px;
        height: 35px;
        font-size: 14px;
        box-shadow: 0 0 0 2px #f8f9fa;
    }
    
    .step-label {
        font-size: 11px;
        max-width: 70px;
    }
    
    .progress-step.completed::after {
        display: none;
    }
}

@media (max-width: 480px) {
    .progress-step .step-label {
        display: none;
    }
    
    .progress-step.active .step-label {
        display: block;
        position: absolute;
        top: 50px;
        white-space: nowrap;
        background: #fff;
        padding: 2px 5px;
        border-radius: 3px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
}

@media (max-width: 768px) {
    .date-time-selection {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .selected-info-card {
        flex-direction: column;
        gap: 15px;
    }
    
    .time-slots-grid {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    }
}

/* Date and Time Selection Styles */
.selected-info-card {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.selected-doctor-info,
.selected-service-info {
    display: flex;
    align-items: center;
    gap: 15px;
    flex: 1;
}

.selected-info-card i {
    font-size: 30px;
    color: #007bff;
    opacity: 0.8;
}

.info-label {
    margin: 0;
    font-size: 12px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #212529;
}

.date-time-selection {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 30px;
    align-items: start;
}

.date-selection-section,
.time-selection-section {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.date-selection-section h4,
.time-selection-section h4 {
    margin: 0 0 20px 0;
    color: #212529;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.calendar-wrapper {
    position: relative;
}

.date-input {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: border-color 0.3s;
}

.date-input:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.date-helper-text {
    margin-top: 8px;
    font-size: 13px;
    color: #6c757d;
}

.slots-loading {
    padding: 60px 20px;
    text-align: center;
}

.spinner-wrapper {
    color: #007bff;
}

.spinner-wrapper p {
    margin-top: 15px;
    color: #6c757d;
}

.no-slots-message {
    padding: 40px 20px;
    text-align: center;
    color: #6c757d;
}

.no-slots-message i {
    color: #dee2e6;
    margin-bottom: 15px;
}

.time-slot {
    padding: 15px 10px;
    text-align: center;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    background: #fff;
    position: relative;
    overflow: hidden;
}

.time-slot::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent, rgba(0,123,255,0.1), transparent);
    transform: translateX(-100%);
    transition: transform 0.6s;
}

.time-slot:hover:not(.unavailable)::before {
    transform: translateX(100%);
}

.time-slot:hover:not(.unavailable) {
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,123,255,0.15);
}

.time-slot.selected {
    background: #007bff;
    color: #fff;
    border-color: #007bff;
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}

.time-slot.selected::after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 12px;
}

.time-slot.unavailable {
    background: #f8f9fa;
    color: #adb5bd;
    cursor: not-allowed;
    border-color: #e9ecef;
    opacity: 0.6;
}

.time-slot .time-text {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 5px;
}

.time-slot .time-period {
    font-size: 12px;
    opacity: 0.8;
    text-transform: uppercase;
}

.booking-summary {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.summary-section {
    margin-bottom: 20px;
}

.summary-section h4 {
    color: #007bff;
    margin-bottom: 15px;
}

.selected-info,
.selected-specialty-info {
    background: #f0f8ff;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('booking-form');
    const steps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.progress-step');
    let currentStep = 1;
    let bookingData = {};

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
        
        card.innerHTML = `
            <div class="doctor-info">
                <img src="${photoUrl}" alt="${doctor.name}" class="doctor-photo">
                <div class="doctor-details">
                    <h4>${doctor.name}</h4>
                    <p>${doctor.specialization}</p>
                </div>
            </div>
            <div class="service-list">
                <p><strong>Servicii disponibile:</strong></p>
                ${doctor.services.map(service => `
                    <div class="service-item" data-service-id="${service.id}" data-service-name="${service.name}">
                        ${service.name} - ${service.duration_minutes} min - ${service.price} RON
                    </div>
                `).join('')}
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

    // Step 3: Date and Time Selection
    document.getElementById('appointment-date').addEventListener('change', function() {
        if (this.value && bookingData.doctorId && bookingData.serviceId) {
            loadTimeSlots(bookingData.doctorId, this.value, bookingData.serviceId);
        }
    });

    function loadTimeSlots(doctorId, date, serviceId) {
        const loadingDiv = document.getElementById('time-slots-loading');
        const slotsDiv = document.getElementById('time-slots');
        const noSlotsDiv = document.getElementById('no-slots-message');
        
        loadingDiv.style.display = 'block';
        slotsDiv.innerHTML = '';
        noSlotsDiv.style.display = 'none';
        
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
            loadingDiv.style.display = 'none';
            
            if (data.success && data.slots.length > 0) {
                // Group slots by hour for better organization
                const slotsByHour = {};
                data.slots.forEach(slot => {
                    const hour = slot.time.split(':')[0];
                    if (!slotsByHour[hour]) {
                        slotsByHour[hour] = [];
                    }
                    slotsByHour[hour].push(slot);
                });
                
                // Create time slots with enhanced UI
                data.slots.forEach(slot => {
                    const slotDiv = document.createElement('div');
                    slotDiv.className = 'time-slot' + (slot.available ? '' : ' unavailable');
                    
                    // Parse time for better display
                    const [hour, minute] = slot.time.split(':');
                    const hourNum = parseInt(hour);
                    const period = hourNum >= 12 ? 'PM' : 'AM';
                    const displayHour = hourNum > 12 ? hourNum - 12 : (hourNum === 0 ? 12 : hourNum);
                    
                    slotDiv.innerHTML = `
                        <div class="time-text">${displayHour}:${minute}</div>
                        <div class="time-period">${period}</div>
                    `;
                    
                    slotDiv.dataset.time = slot.time;
                    
                    if (slot.available) {
                        slotDiv.addEventListener('click', function() {
                            // Remove previous selection
                            document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                            
                            // Add selection to current slot
                            this.classList.add('selected');
                            
                            // Update form values
                            document.getElementById('selected-time').value = slot.time;
                            bookingData.appointmentTime = slot.time;
                            bookingData.appointmentDate = date;
                            
                            // Visual feedback
                            this.style.transform = 'scale(1.05)';
                            setTimeout(() => {
                                this.style.transform = '';
                            }, 200);
                            
                            enableNextButton(3);
                        });
                        
                        // Add hover effect feedback
                        slotDiv.addEventListener('mouseenter', function() {
                            if (!this.classList.contains('selected')) {
                                this.style.transform = 'translateY(-2px)';
                            }
                        });
                        
                        slotDiv.addEventListener('mouseleave', function() {
                            if (!this.classList.contains('selected')) {
                                this.style.transform = '';
                            }
                        });
                    }
                    
                    slotsDiv.appendChild(slotDiv);
                });
                
                // Add animation to slots
                const slots = slotsDiv.querySelectorAll('.time-slot');
                slots.forEach((slot, index) => {
                    slot.style.opacity = '0';
                    slot.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        slot.style.transition = 'opacity 0.3s, transform 0.3s';
                        slot.style.opacity = '1';
                        slot.style.transform = 'translateY(0)';
                    }, index * 30);
                });
            } else {
                noSlotsDiv.style.display = 'block';
            }
        })
        .catch(error => {
            loadingDiv.style.display = 'none';
            console.error('Error loading time slots:', error);
            noSlotsDiv.innerHTML = `
                <i class="fas fa-exclamation-triangle fa-3x"></i>
                <p>A apărut o eroare la încărcarea orelor disponibile.</p>
                <p class="text-muted">Vă rugăm să încercați din nou.</p>
            `;
            noSlotsDiv.style.display = 'block';
        });
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
        progressSteps.forEach(p => p.classList.remove('active'));
        
        currentStep = step;
        steps[step - 1].classList.add('active');
        
        for (let i = 0; i < step; i++) {
            progressSteps[i].classList.add('active');
            if (i < step - 1) {
                progressSteps[i].classList.add('completed');
            }
        }
        
        // Update display info when entering step 3
        if (step === 3) {
            updateDisplayInfo();
            // Auto-trigger date change if date is already selected
            const dateInput = document.getElementById('appointment-date');
            if (dateInput.value && bookingData.doctorId && bookingData.serviceId) {
                loadTimeSlots(bookingData.doctorId, dateInput.value, bookingData.serviceId);
            }
        }
    }

    function enableNextButton(step) {
        const button = steps[step - 1].querySelector('.next-step');
        if (button) {
            button.disabled = false;
        }
    }

    function updateDisplayInfo() {
        document.getElementById('display-doctor').textContent = bookingData.doctorName;
        document.getElementById('display-service').textContent = bookingData.serviceName;
    }

    function validatePatientData() {
        const name = document.getElementById('patient-name').value.trim();
        const email = document.getElementById('patient-email').value.trim();
        const phone = document.getElementById('patient-phone').value.trim();
        
        if (!name || !email || !phone) {
            alert('Vă rugăm să completați toate câmpurile obligatorii.');
            return false;
        }
        
        // Basic email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Vă rugăm să introduceți o adresă de email validă.');
            return false;
        }
        
        // Basic phone validation
        const phoneRegex = /^[\d\s\-\+\(\)]+$/;
        if (!phoneRegex.test(phone)) {
            alert('Vă rugăm să introduceți un număr de telefon valid.');
            return false;
        }
        
        return true;
    }

    function updateSummary() {
        // Medical details
        document.getElementById('summary-specialty').textContent = bookingData.specialty;
        document.getElementById('summary-doctor').textContent = bookingData.doctorName;
        document.getElementById('summary-service').textContent = bookingData.serviceName;
        
        const date = new Date(bookingData.appointmentDate);
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('summary-date').textContent = date.toLocaleDateString('ro-RO', dateOptions);
        document.getElementById('summary-time').textContent = bookingData.appointmentTime;
        
        // Personal details
        document.getElementById('summary-name').textContent = document.getElementById('patient-name').value;
        document.getElementById('summary-email').textContent = document.getElementById('patient-email').value;
        document.getElementById('summary-phone').textContent = document.getElementById('patient-phone').value;
        document.getElementById('summary-cnp').textContent = document.getElementById('patient-cnp').value || 'Necompletat';
        document.getElementById('summary-notes').textContent = document.getElementById('notes').value || 'Fără observații';
    }

    // Terms agreement checkbox
    document.getElementById('terms-agree').addEventListener('change', function() {
        document.getElementById('confirm-booking').disabled = !this.checked;
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Always prevent default first
        
        if (!document.getElementById('terms-agree').checked) {
            alert('Vă rugăm să acceptați termenii și condițiile.');
            return;
        }
        
        // Log form data for debugging
        const formData = {
            specialty: document.getElementById('selected-specialty').value,
            doctor_id: document.getElementById('selected-doctor').value,
            service_id: document.getElementById('selected-service').value,
            appointment_date: document.getElementById('appointment-date').value,
            appointment_time: document.getElementById('selected-time').value,
            patient_name: document.getElementById('patient-name').value,
            patient_email: document.getElementById('patient-email').value,
            patient_phone: document.getElementById('patient-phone').value,
            patient_cnp: document.getElementById('patient-cnp').value,
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
            console.log('Response headers:', response.headers);
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                return response.text().then(text => ({html: text}));
            }
        })
        .then(data => {
            if (data.html) {
                // HTML response
                console.log('Response HTML preview:', data.html.substring(0, 500));
                if (data.html.includes('Flash__error') || data.html.includes('eroare')) {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data.html, 'text/html');
                    const errorDiv = doc.querySelector('.alert-danger, .message.error');
                    if (errorDiv) {
                        alert('Eroare: ' + errorDiv.textContent.trim());
                    } else {
                        alert('A apărut o eroare. Verificați consola pentru detalii.');
                    }
                    console.error('Full response:', data.html);
                } else {
                    // Form was submitted normally, redirect to home
                    window.location.href = '/appointments';
                }
            } else {
                // JSON response
                console.log('JSON response:', data);
                if (data.success) {
                    // Build the success URL
                    if (data.redirect) {
                        let redirectUrl;
                        if (typeof data.redirect === 'object' && data.redirect.action) {
                            // Handle CakePHP array format redirect
                            const appointmentId = data.redirect[0] || data.redirect.id;
                            redirectUrl = `/appointments/${data.redirect.action}/${appointmentId}`;
                        } else if (typeof data.redirect === 'string') {
                            redirectUrl = data.redirect;
                        } else {
                            console.log('Redirect data:', data.redirect);
                            redirectUrl = '/appointments';
                        }
                        window.location.href = redirectUrl;
                    } else {
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
                }
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Eroare de rețea: ' + error.message);
        });
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