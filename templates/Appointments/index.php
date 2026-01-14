<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Patient $patient
 * @var array $specializations
 * @var array $services
 */
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
                        <div class="time-slots-legend" id="time-slots-legend" style="display: none;">
                            <div class="legend-item">
                                <div class="legend-box available"></div>
                                <span>Disponibil</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-box unavailable"></div>
                                <span>Ocupat</span>
                            </div>
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

        <!-- Step 4: Verify Patient Details -->
        <div class="form-step" data-step="4">
            <h2>Pasul 4: Verificați Datele Personale</h2>
            <p class="step-description">Datele dumneavoastră de contact preluate din cont.</p>

            <div class="patient-info-display">
                <div class="patient-info-card">
                    <div class="patient-info-row">
                        <div class="patient-info-item">
                            <i class="fas fa-user"></i>
                            <div>
                                <span class="info-label">Nume complet</span>
                                <span class="info-value" id="display-patient-name"><?= h($patient->full_name) ?></span>
                            </div>
                        </div>
                        <div class="patient-info-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <span class="info-label">Email</span>
                                <span class="info-value" id="display-patient-email"><?= h($patient->email) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="patient-info-row">
                        <div class="patient-info-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <span class="info-label">Telefon</span>
                                <span class="info-value" id="display-patient-phone"><?= h($patient->phone) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="patient-info-note">
                    <i class="fas fa-info-circle"></i>
                    Pentru a modifica datele de contact, accesați <a href="/portal/profile">profilul dumneavoastră</a>.
                </p>
            </div>

            <!-- Hidden fields for patient data -->
            <?= $this->Form->hidden('patient_id', ['value' => $patient->id, 'id' => 'patient-id']) ?>

            <div class="form-group">
                <?= $this->Form->control('notes', [
                    'type' => 'textarea',
                    'label' => 'Observații (opțional)',
                    'class' => 'form-control',
                    'rows' => 3,
                    'id' => 'notes',
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
                        <dd class="col-sm-8" id="summary-name"><?= h($patient->full_name) ?></dd>

                        <dt class="col-sm-4">Email:</dt>
                        <dd class="col-sm-8" id="summary-email"><?= h($patient->email) ?></dd>

                        <dt class="col-sm-4">Telefon:</dt>
                        <dd class="col-sm-8" id="summary-phone"><?= h($patient->phone) ?></dd>

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
    background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
}

/* Ensure container uses full width */
.appointments-booking .container {
    max-width: 1200px;
    width: 100%;
    padding: 0 15px;
    margin: 0 auto;
}

.appointments-booking h1 {
    text-align: center;
    margin-bottom: 30px;
    color: #1a365d;
    font-size: 32px;
    font-weight: 700;
    letter-spacing: -0.5px;
}

/* ===== NEW PROGRESS BAR STYLES ===== */
.booking-progress-wrapper {
    margin-bottom: 35px;
    padding: 20px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.progress-steps-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1000px;
    margin: 0 auto;
}

.progress-step {
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    flex-shrink: 0;
}

.step-icon-wrapper {
    position: relative;
}

.step-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: #e8eef4;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #8896a6;
    transition: all 0.3s ease;
}

.progress-step.active .step-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #fff;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
}

.progress-step.completed .step-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #fff;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
}

.step-number-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #cbd5e1;
    color: #64748b;
    font-size: 11px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
}

.progress-step.active .step-number-badge {
    background: #1d4ed8;
    color: #fff;
}

.progress-step.completed .step-number-badge {
    background: #059669;
    color: #fff;
}

.step-info {
    display: flex;
    flex-direction: column;
}

.step-title {
    font-size: 14px;
    font-weight: 600;
    color: #334155;
    line-height: 1.2;
}

.step-subtitle {
    font-size: 11px;
    color: #94a3b8;
    margin-top: 2px;
}

.progress-step.active .step-title {
    color: #1d4ed8;
}

.progress-step.completed .step-title {
    color: #059669;
}

.progress-connector {
    flex: 1;
    height: 3px;
    background: #e2e8f0;
    margin: 0 8px;
    border-radius: 2px;
    position: relative;
}

.progress-connector.completed {
    background: linear-gradient(90deg, #10b981 0%, #10b981 100%);
}

/* Mobile responsive for progress bar */
@media (max-width: 900px) {
    .step-info {
        display: none;
    }

    .progress-step {
        gap: 0;
    }

    .step-icon {
        width: 44px;
        height: 44px;
        font-size: 18px;
    }
}

@media (max-width: 600px) {
    .progress-steps-container {
        padding: 0 10px;
    }

    .step-icon {
        width: 38px;
        height: 38px;
        font-size: 16px;
        border-radius: 10px;
    }

    .step-number-badge {
        width: 18px;
        height: 18px;
        font-size: 10px;
    }

    .progress-connector {
        height: 2px;
        margin: 0 4px;
    }
}

/* ===== FORM STEP STYLES ===== */
.form-step {
    display: none;
    background: #fff;
    padding: 35px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
}

.form-step.active {
    display: block;
}

.step-header {
    margin-bottom: 30px;
    text-align: center;
}

.step-header h2 {
    font-size: 24px;
    font-weight: 700;
    color: #1a365d;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.step-header h2 i {
    color: #3b82f6;
}

.step-header .step-description {
    color: #64748b;
    font-size: 15px;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.step-actions {
    margin-top: 35px;
    display: flex;
    justify-content: space-between;
    gap: 15px;
}

/* ===== SPECIALTY CARDS ===== */
.specialty-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    margin: 25px 0;
}

.specialty-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.specialty-card-inner {
    display: flex;
    align-items: center;
    padding: 20px;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    background: #fff;
    transition: all 0.3s ease;
    gap: 18px;
}

.specialty-card:hover .specialty-card-inner {
    border-color: #3b82f6;
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
}

.specialty-card.selected .specialty-card-inner {
    border-color: #3b82f6;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
}

.specialty-icon-container {
    flex-shrink: 0;
}

.specialty-icon {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    color: #0284c7;
    transition: all 0.3s ease;
}

.specialty-card:hover .specialty-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #fff;
}

.specialty-card.selected .specialty-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.specialty-content {
    flex: 1;
    min-width: 0;
}

.specialty-name {
    font-size: 17px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 6px 0;
    line-height: 1.3;
}

.specialty-description {
    font-size: 13px;
    color: #64748b;
    margin: 0 0 10px 0;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.specialty-meta {
    display: flex;
    align-items: center;
    gap: 12px;
}

.doctors-available {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #10b981;
    font-weight: 500;
    background: #ecfdf5;
    padding: 5px 12px;
    border-radius: 20px;
}

.doctors-available i {
    font-size: 12px;
}

.specialty-card-arrow {
    flex-shrink: 0;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    transition: all 0.3s ease;
}

.specialty-card:hover .specialty-card-arrow {
    background: #3b82f6;
    color: #fff;
    transform: translateX(3px);
}

.specialty-card.selected .specialty-card-arrow {
    background: #3b82f6;
    color: #fff;
}

/* No specializations message */
.no-specializations-message {
    text-align: center;
    padding: 60px 20px;
    background: #fef3cd;
    border-radius: 12px;
    border: 1px solid #ffc107;
}

.no-specializations-message i {
    color: #856404;
    margin-bottom: 20px;
}

.no-specializations-message h3 {
    color: #856404;
    font-size: 20px;
    margin-bottom: 10px;
}

.no-specializations-message p {
    color: #856404;
    font-size: 15px;
}

/* Mobile responsive for specialty cards */
@media (max-width: 768px) {
    .specialty-grid {
        grid-template-columns: 1fr;
    }

    .specialty-card-inner {
        padding: 16px;
    }

    .specialty-icon {
        width: 52px;
        height: 52px;
        font-size: 22px;
    }

    .specialty-name {
        font-size: 16px;
    }

    .specialty-card-arrow {
        display: none;
    }
}

/* ===== SELECTED SPECIALTY BADGE ===== */
.selected-specialty-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
    padding: 12px 20px;
    border-radius: 30px;
    margin-bottom: 25px;
    color: #0284c7;
    font-size: 14px;
}

.selected-specialty-badge i {
    font-size: 18px;
}

.selected-specialty-badge strong {
    color: #0369a1;
}

/* ===== LOADING INDICATOR ===== */
.loading-indicator {
    text-align: center;
    padding: 60px 20px;
}

.loading-indicator .spinner-wrapper {
    color: #3b82f6;
}

.loading-indicator .spinner-wrapper p {
    margin-top: 15px;
    color: #64748b;
    font-size: 15px;
}

/* ===== DOCTORS GRID - MATCHING SPECIALTY STYLE ===== */
.doctors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    margin: 25px 0;
}

.doctor-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.doctor-card-inner {
    display: flex;
    align-items: flex-start;
    padding: 20px;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    background: #fff;
    transition: all 0.3s ease;
    gap: 18px;
}

.doctor-card:hover .doctor-card-inner {
    border-color: #3b82f6;
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
}

.doctor-card.selected .doctor-card-inner {
    border-color: #3b82f6;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
}

.doctor-photo-container {
    flex-shrink: 0;
}

.doctor-photo {
    width: 70px;
    height: 70px;
    border-radius: 14px;
    object-fit: cover;
    background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
    transition: all 0.3s ease;
}

.doctor-card:hover .doctor-photo {
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.doctor-card.selected .doctor-photo {
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.doctor-content {
    flex: 1;
    min-width: 0;
}

.doctor-name {
    font-size: 17px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 8px 0;
    line-height: 1.3;
}

.doctor-meta {
    margin-bottom: 12px;
}

.services-count {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #10b981;
    font-weight: 500;
    background: #ecfdf5;
    padding: 5px 12px;
    border-radius: 20px;
}

.services-count i {
    font-size: 12px;
}

.service-list {
    margin-top: 12px;
}

.service-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 14px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.service-item:last-child {
    margin-bottom: 0;
}

.service-item:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
}

.service-item.selected {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border-color: #3b82f6;
    color: #fff;
}

.service-name {
    font-weight: 500;
    font-size: 14px;
}

.service-details {
    font-size: 12px;
    color: #64748b;
}

.service-item.selected .service-details {
    color: rgba(255, 255, 255, 0.85);
}

.doctor-card-arrow {
    flex-shrink: 0;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    transition: all 0.3s ease;
    align-self: center;
}

.doctor-card:hover .doctor-card-arrow {
    background: #3b82f6;
    color: #fff;
    transform: translateX(3px);
}

.doctor-card.selected .doctor-card-arrow {
    background: #3b82f6;
    color: #fff;
}

/* Mobile responsive for doctor cards */
@media (max-width: 768px) {
    .doctors-grid {
        grid-template-columns: 1fr;
    }

    .doctor-card-inner {
        padding: 16px;
        flex-wrap: wrap;
    }

    .doctor-photo {
        width: 60px;
        height: 60px;
    }

    .doctor-card-arrow {
        display: none;
    }

    .service-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
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

/* Responsive Styles for Date-Time Selection */
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
    color: #6c757d;
    cursor: not-allowed;
    border-color: #dee2e6;
    opacity: 0.7;
    position: relative;
    overflow: hidden;
}

.time-slot.unavailable::before {
    content: '';
    position: absolute;
    top: 50%;
    left: -10%;
    right: -10%;
    height: 1px;
    background: #dc3545;
    transform: rotate(-15deg);
    opacity: 0.5;
}

.time-slot.unavailable::after {
    content: 'Ocupat';
    position: absolute;
    top: 2px;
    right: 5px;
    font-size: 10px;
    color: #dc3545;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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

/* Patient Info Display Styles */
.patient-info-display {
    margin-bottom: 30px;
}

.patient-info-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    padding: 25px;
    border: 1px solid #dee2e6;
}

.patient-info-row {
    display: flex;
    gap: 30px;
    margin-bottom: 20px;
}

.patient-info-row:last-child {
    margin-bottom: 0;
}

.patient-info-item {
    display: flex;
    align-items: center;
    gap: 15px;
    flex: 1;
}

.patient-info-item i {
    font-size: 24px;
    color: #007bff;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 123, 255, 0.1);
    border-radius: 50%;
}

.patient-info-item .info-label {
    display: block;
    font-size: 12px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}

.patient-info-item .info-value {
    display: block;
    font-size: 16px;
    font-weight: 600;
    color: #212529;
}

.patient-info-note {
    margin-top: 15px;
    font-size: 14px;
    color: #6c757d;
    display: flex;
    align-items: center;
    gap: 8px;
}

.patient-info-note i {
    color: #17a2b8;
}

.patient-info-note a {
    color: #007bff;
    text-decoration: none;
}

.patient-info-note a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .patient-info-row {
        flex-direction: column;
        gap: 20px;
    }
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

/* Tooltip animation for unavailable slots */
@keyframes fadeInOut {
    0% {
        opacity: 0;
        transform: translateY(10px);
    }
    15% {
        opacity: 1;
        transform: translateY(0);
    }
    85% {
        opacity: 1;
        transform: translateY(0);
    }
    100% {
        opacity: 0;
        transform: translateY(-10px);
    }
}

.slot-tooltip {
    pointer-events: none;
}

.slot-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 6px solid transparent;
    border-top-color: #dc3545;
}

/* Enhanced unavailable slot indication */
.time-slot.unavailable .time-text {
    text-decoration: line-through;
    opacity: 0.6;
}

/* Additional visual cues for unavailable slots */
.time-slot.unavailable:hover {
    transform: none !important;
    box-shadow: none !important;
}

/* Legend for time slots */
.time-slots-legend {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
    font-size: 13px;
    color: #6c757d;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-box {
    width: 20px;
    height: 20px;
    border: 2px solid #e9ecef;
    border-radius: 4px;
}

.legend-box.available {
    background: #fff;
    border-color: #007bff;
}

.legend-box.unavailable {
    background: #f8f9fa;
    border-color: #dee2e6;
    position: relative;
    overflow: hidden;
}

.legend-box.unavailable::before {
    content: '';
    position: absolute;
    top: 50%;
    left: -5px;
    right: -5px;
    height: 1px;
    background: #dc3545;
    transform: rotate(-15deg);
}

/* Expiring soon slots */
.time-slot.expiring-soon {
    border-color: #ffc107;
    background: #fff8e1;
}

.time-slot.expiring-soon:hover {
    border-color: #ff9800;
    background: #fff3cd;
}

.time-slot.expiring-soon::after {
    content: 'Urgent';
    position: absolute;
    top: 2px;
    left: 5px;
    font-size: 9px;
    color: #ff6f00;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.legend-box.expiring {
    background: #fff8e1;
    border-color: #ffc107;
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
                // Show legend
                document.getElementById('time-slots-legend').style.display = 'flex';
                
                // Count available and unavailable slots
                const availableCount = data.slots.filter(s => s.available).length;
                const unavailableCount = data.slots.filter(s => !s.available).length;
                
                // Check if any slots are expiring soon (for today's appointments)
                let hasExpiringSoon = false;
                const selectedDate = new Date(date);
                const today = new Date();
                const isToday = selectedDate.toDateString() === today.toDateString();
                
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
                    let classNames = 'time-slot';
                    
                    if (!slot.available) {
                        classNames += ' unavailable';
                    } else if (isToday && slot.available) {
                        // Check if slot is expiring soon (within 2 hours)
                        const [slotHour, slotMinute] = slot.time.split(':').map(Number);
                        const slotTime = new Date();
                        slotTime.setHours(slotHour, slotMinute, 0, 0);
                        
                        const now = new Date();
                        const timeDiff = (slotTime - now) / (1000 * 60); // difference in minutes
                        
                        if (timeDiff > 0 && timeDiff <= 120) { // within 2 hours
                            classNames += ' expiring-soon';
                            hasExpiringSoon = true;
                        }
                    }
                    
                    slotDiv.className = classNames;
                    
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
                    } else {
                        // Add click handler for unavailable slots to show message
                        slotDiv.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            // Show a tooltip or alert
                            const tooltip = document.createElement('div');
                            tooltip.className = 'slot-tooltip';
                            tooltip.textContent = 'Acest interval este deja rezervat';
                            tooltip.style.cssText = `
                                position: absolute;
                                background: #dc3545;
                                color: white;
                                padding: 8px 12px;
                                border-radius: 4px;
                                font-size: 12px;
                                z-index: 1000;
                                white-space: nowrap;
                                animation: fadeInOut 2s ease-in-out;
                            `;
                            
                            // Position tooltip above the slot
                            const rect = this.getBoundingClientRect();
                            const containerRect = slotsDiv.getBoundingClientRect();
                            tooltip.style.left = (rect.left - containerRect.left) + 'px';
                            tooltip.style.top = (rect.top - containerRect.top - 40) + 'px';
                            
                            slotsDiv.style.position = 'relative';
                            slotsDiv.appendChild(tooltip);
                            
                            // Remove tooltip after animation
                            setTimeout(() => {
                                tooltip.remove();
                            }, 2000);
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
                
                // Update legend to include expiring soon if needed
                if (hasExpiringSoon) {
                    const legendDiv = document.getElementById('time-slots-legend');
                    if (!legendDiv.querySelector('.legend-item.expiring')) {
                        const expiringItem = document.createElement('div');
                        expiringItem.className = 'legend-item expiring';
                        expiringItem.innerHTML = `
                            <div class="legend-box expiring"></div>
                            <span>Expiră curând</span>
                        `;
                        legendDiv.appendChild(expiringItem);
                    }
                }
            } else {
                noSlotsDiv.style.display = 'block';
                // Hide legend when no slots
                document.getElementById('time-slots-legend').style.display = 'none';
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
        // Patient data is already validated and stored in session
        // No additional validation needed as data comes from authenticated patient
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

        // Personal details - patient name, email, phone are pre-filled via PHP
        // Only notes needs to be updated from the form
        document.getElementById('summary-notes').textContent = document.getElementById('notes').value || 'Fără observații';
    }

    // Terms agreement checkbox
    document.getElementById('terms-agree').addEventListener('change', function() {
        document.getElementById('confirm-booking').disabled = !this.checked;
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Always prevent default first
        
        const submitButton = document.getElementById('confirm-booking');
        
        if (!document.getElementById('terms-agree').checked) {
            alert('Vă rugăm să acceptați termenii și condițiile.');
            return;
        }
        
        // Disable submit button and show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Se procesează...';
        
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
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-check"></i> Confirmă Programarea';
                } else {
                    // Unknown HTML response - log it and show error
                    console.error('Unexpected HTML response, not success or error page');
                    console.log('Full HTML:', data.html);
                    alert('A apărut o eroare neașteptată. Vă rugăm să încercați din nou.');
                    // Restore button state
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-check"></i> Confirmă Programarea';
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
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-check"></i> Confirmă Programarea';
                }
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Eroare de rețea: ' + error.message);
            // Restore button state on error
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fas fa-check"></i> Confirmă Programarea';
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