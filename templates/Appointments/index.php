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
            
            <div class="selected-info">
                <p><strong>Medic:</strong> <span id="display-doctor"></span></p>
                <p><strong>Serviciu:</strong> <span id="display-service"></span></p>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= $this->Form->control('appointment_date', [
                            'type' => 'date',
                            'label' => 'Selectați Data',
                            'min' => date('Y-m-d'),
                            'max' => date('Y-m-d', strtotime('+90 days')),
                            'class' => 'form-control',
                            'id' => 'appointment-date'
                        ]) ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="time-slots-container">
                        <label>Selectați Ora</label>
                        <div id="time-slots-loading" class="text-center" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Se încarcă orele disponibile...
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

.booking-progress {
    margin-bottom: 40px;
}

.progress-bar {
    display: flex;
    justify-content: space-between;
    position: relative;
    align-items: flex-start;
    max-width: 800px;
    margin: 0 auto;
}

.progress-bar::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 2px;
    background: #e0e0e0;
    z-index: -1;
}

.progress-step {
    text-align: center;
    position: relative;
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
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
    margin: 0 auto 10px;
    font-weight: bold;
    border: 3px solid #fff;
    position: relative;
    z-index: 1;
}

.progress-step.active .step-number,
.progress-step.completed .step-number {
    background: #007bff;
    color: #fff;
}

.progress-step.completed .step-number::after {
    content: '✓';
    position: absolute;
    font-size: 20px;
}

.step-label {
    font-size: 14px;
    color: #666;
}

.progress-step.active .step-label {
    color: #007bff;
    font-weight: bold;
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
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 10px;
    margin-top: 15px;
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

.time-slot.selected {
    background: #007bff;
    color: #fff;
    border-color: #007bff;
}

.time-slot.unavailable {
    background: #f8f9fa;
    color: #999;
    cursor: not-allowed;
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
        
        // Get tomorrow's date as default
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const dateStr = tomorrow.toISOString().split('T')[0];
        
        fetch('/appointments/check-availability', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': getCsrfToken()
            },
            body: JSON.stringify({
                specialty: specialty,
                date: dateStr
            })
        })
        .then(response => response.json())
        .then(data => {
            loadingDiv.style.display = 'none';
            
            if (data.success && data.doctors.length > 0) {
                data.doctors.forEach(doctor => {
                    const doctorCard = createDoctorCard(doctor);
                    doctorsList.appendChild(doctorCard);
                });
            } else {
                doctorsList.innerHTML = '<p class="text-center">Nu sunt medici disponibili pentru această specialitate.</p>';
            }
        })
        .catch(error => {
            loadingDiv.style.display = 'none';
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
        
        loadingDiv.style.display = 'block';
        slotsDiv.innerHTML = '';
        
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
                data.slots.forEach(slot => {
                    const slotDiv = document.createElement('div');
                    slotDiv.className = 'time-slot' + (slot.available ? '' : ' unavailable');
                    slotDiv.textContent = slot.display;
                    slotDiv.dataset.time = slot.time;
                    
                    if (slot.available) {
                        slotDiv.addEventListener('click', function() {
                            document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                            this.classList.add('selected');
                            
                            document.getElementById('selected-time').value = slot.time;
                            bookingData.appointmentTime = slot.time;
                            bookingData.appointmentDate = date;
                            
                            enableNextButton(3);
                        });
                    }
                    
                    slotsDiv.appendChild(slotDiv);
                });
            } else {
                slotsDiv.innerHTML = '<p class="text-center">Nu sunt ore disponibile pentru această dată.</p>';
            }
        })
        .catch(error => {
            loadingDiv.style.display = 'none';
            slotsDiv.innerHTML = '<p class="text-center text-danger">A apărut o eroare la încărcarea orelor disponibile.</p>';
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
        if (!document.getElementById('terms-agree').checked) {
            e.preventDefault();
            alert('Vă rugăm să acceptați termenii și condițiile.');
            return;
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