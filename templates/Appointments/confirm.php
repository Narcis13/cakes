<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Appointment $appointment
 */
?>

<div class="appointment-confirm">
    <div class="container">
        <div class="confirm-content">
            <div class="confirm-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            
            <h1>Confirmare Programare</h1>
            
            <div class="appointment-info">
                <h2>Detalii Programare</h2>
                
                <div class="info-card">
                    <div class="info-item">
                        <i class="fas fa-user-md"></i>
                        <div>
                            <strong>Medic:</strong><br>
                            <?= h($appointment->doctors->first_name . ' ' . $appointment->doctors->last_name) ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-stethoscope"></i>
                        <div>
                            <strong>Serviciu:</strong><br>
                            <?= h($appointment->service->name) ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-calendar"></i>
                        <div>
                            <strong>Data:</strong><br>
                            <?= $appointment->appointment_date->i18nFormat('d MMMM yyyy', null, 'ro_RO') ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>Ora:</strong><br>
                            <?= $appointment->appointment_time->format('H:i') ?>
                        </div>
                    </div>
                </div>
                
                <div class="patient-info">
                    <p><strong>Pacient:</strong> <?= h($appointment->patient_name) ?></p>
                    <p><strong>Email:</strong> <?= h($appointment->patient_email) ?></p>
                    <p><strong>Telefon:</strong> <?= h($appointment->patient_phone) ?></p>
                </div>
            </div>
            
            <div class="confirm-message">
                <p>Vă rugăm să confirmați că doriți să păstrați această programare.</p>
            </div>
        </div>
    </div>
</div>

<style>
.appointment-confirm {
    padding: 40px 0;
    min-height: 80vh;
}

.confirm-content {
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
}

.confirm-icon {
    font-size: 60px;
    color: #007bff;
    margin-bottom: 20px;
}

.appointment-info {
    margin: 30px 0;
}

.info-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
}

.info-item {
    display: flex;
    align-items: center;
    margin: 15px 0;
    text-align: left;
}

.info-item i {
    font-size: 24px;
    color: #007bff;
    margin-right: 15px;
    width: 30px;
}

.patient-info {
    background: #e8f4fd;
    padding: 15px;
    border-radius: 8px;
    margin: 20px 0;
    text-align: left;
}

.patient-info p {
    margin: 5px 0;
}

.confirm-message {
    margin: 30px 0;
    font-size: 18px;
}
</style>