<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Appointment $appointment
 */
use Cake\Core\Configure;
?>

<div class="appointment-success">
    <div class="container">
        <div class="success-content">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h1>Programare Înregistrată cu Succes!</h1>
            
            <div class="alert alert-success">
                <i class="fas fa-check"></i>
                <p>Programarea dumneavoastră a fost înregistrată cu succes!</p>
                <p>Veți fi contactat pentru confirmare la numărul de telefon furnizat.</p>
            </div>
            
            <div class="appointment-details">
                <h2>Detalii Programare</h2>
                
                <div class="detail-card">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Informații Medicale</h3>
                            <dl>
                                <dt>Medic:</dt>
                                <dd><?= h($appointment->doctor->first_name . ' ' . $appointment->doctor->last_name) ?></dd>
                                
                                <dt>Specialitate:</dt>
                                <dd><?= h($appointment->doctor->specialization) ?></dd>
                                
                                <dt>Serviciu:</dt>
                                <dd><?= h($appointment->service->name) ?></dd>
                                
                                <dt>Data:</dt>
                                <dd><?= $appointment->appointment_date->i18nFormat('EEEE, d MMMM yyyy', null, 'ro_RO') ?></dd>
                                
                                <dt>Ora:</dt>
                                <dd><?= $appointment->appointment_time->format('H:i') ?></dd>
                                
                                <dt>Durata:</dt>
                                <dd><?= $appointment->service->duration_minutes ?> minute</dd>
                            </dl>
                        </div>
                        
                        <div class="col-md-6">
                            <h3>Informații Personale</h3>
                            <dl>
                                <dt>Nume:</dt>
                                <dd><?= h($appointment->patient_name) ?></dd>
                                
                                <dt>Email:</dt>
                                <dd><?= h($appointment->patient_email) ?></dd>
                                
                                <dt>Telefon:</dt>
                                <dd><?= h($appointment->patient_phone) ?></dd>
                                
                                <?php if ($appointment->notes): ?>
                                    <dt>Observații:</dt>
                                    <dd><?= h($appointment->notes) ?></dd>
                                <?php endif; ?>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <div class="appointment-code">
                    <p><strong>Cod Programare:</strong></p>
                    <h3><?= h($appointment->id) ?></h3>
                    <p class="text-muted">Păstrați acest cod pentru referință</p>
                </div>
            </div>
            
            <div class="next-steps">
                <h2>Următorii Pași</h2>
                <ol>
                    <li>Salvați codul programării pentru referință</li>
                    <li>Prezentați-vă cu 10 minute înainte de ora programată</li>
                    <li>Vă rugăm să aveți la dumneavoastră documentul de identitate, cardul de sănătate, bilet de trimitere și alte documente din istoricul dumneavoastră medical</li>
                </ol>
            </div>
            
            <div class="important-info">
                <h3><i class="fas fa-exclamation-triangle"></i> Informații Importante</h3>
                <ul>
                    <li>Dacă nu puteți ajunge la programare, vă rugăm să ne anunțați cu cel puțin 24 de ore înainte</li>
                    <li>Pentru anulare sau reprogramare, sunați la: <strong><?= Configure::read('Hospital.phone', '0123 456 789') ?></strong></li>
                    <li>Adresa spitalului: <strong><?= Configure::read('Hospital.address', 'Strada Sănătății, Nr. 1') ?></strong></li>
                </ul>
            </div>
            
            <div class="action-buttons">
                <button onclick="window.print()" class="btn btn-secondary">
                    <i class="fas fa-print"></i> Printează Detaliile
                </button>
                <a href="/" class="btn btn-primary">
                    <i class="fas fa-home"></i> Înapoi la Pagina Principală
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.appointment-success {
    padding: 40px 0;
    min-height: 80vh;
}

.success-content {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}

.success-icon {
    font-size: 80px;
    color: #28a745;
    margin-bottom: 20px;
}

.appointment-success h1 {
    color: #28a745;
    margin-bottom: 30px;
}

.alert {
    text-align: left;
    margin: 30px 0;
}

.appointment-details {
    margin: 40px 0;
}

.appointment-details h2 {
    margin-bottom: 20px;
    color: #333;
}

.detail-card {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: left;
}

.detail-card h3 {
    color: #007bff;
    margin-bottom: 15px;
    font-size: 20px;
}

.detail-card dl {
    margin: 0;
}

.detail-card dt {
    font-weight: 600;
    color: #666;
    margin-top: 10px;
}

.detail-card dd {
    margin-left: 0;
    margin-bottom: 10px;
    color: #333;
}

.appointment-code {
    background: #007bff;
    color: white;
    padding: 20px;
    border-radius: 8px;
    margin: 30px 0;
}

.appointment-code h3 {
    font-size: 36px;
    margin: 10px 0;
    font-family: monospace;
}

.next-steps {
    text-align: left;
    background: #e8f4fd;
    padding: 30px;
    border-radius: 8px;
    margin: 30px 0;
}

.next-steps h2 {
    color: #007bff;
    margin-bottom: 20px;
}

.next-steps ol {
    margin: 0;
    padding-left: 20px;
}

.next-steps li {
    margin-bottom: 10px;
    line-height: 1.6;
}

.important-info {
    text-align: left;
    background: #fff3cd;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #ffeeba;
    margin: 30px 0;
}

.important-info h3 {
    color: #856404;
    margin-bottom: 15px;
}

.important-info ul {
    margin: 0;
    padding-left: 20px;
}

.important-info li {
    margin-bottom: 8px;
}

.action-buttons {
    margin-top: 40px;
}

.action-buttons .btn {
    margin: 0 10px;
}

@media print {
    .action-buttons,
    .navbar,
    .footer,
    .success-icon,
    .alert {
        display: none !important;
    }

    .appointment-success {
        padding: 0 !important;
        min-height: auto !important;
    }

    .success-content {
        max-width: 100%;
    }

    .appointment-success h1 {
        font-size: 18px !important;
        margin-bottom: 10px !important;
        color: #000 !important;
    }

    .appointment-details {
        margin: 10px 0 !important;
    }

    .appointment-details h2,
    .next-steps h2,
    .important-info h3 {
        font-size: 14px !important;
        margin-bottom: 8px !important;
    }

    .detail-card {
        padding: 10px !important;
        box-shadow: none !important;
        border: 1px solid #ccc;
    }

    .detail-card h3 {
        font-size: 12px !important;
        margin-bottom: 5px !important;
        color: #000 !important;
    }

    .detail-card dt {
        margin-top: 3px !important;
        font-size: 11px !important;
    }

    .detail-card dd {
        margin-bottom: 3px !important;
        font-size: 11px !important;
    }

    .row {
        display: flex !important;
        gap: 20px;
    }

    .col-md-6 {
        flex: 1;
    }

    .appointment-code {
        padding: 10px !important;
        margin: 10px 0 !important;
        background: #f0f0f0 !important;
        color: #000 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .appointment-code h3 {
        font-size: 20px !important;
        margin: 5px 0 !important;
    }

    .appointment-code p {
        font-size: 10px !important;
        margin: 2px 0 !important;
    }

    .next-steps,
    .important-info {
        padding: 10px !important;
        margin: 10px 0 !important;
        background: #f9f9f9 !important;
        border: 1px solid #ccc !important;
    }

    .next-steps ol,
    .important-info ul {
        margin: 0 !important;
        padding-left: 15px !important;
        font-size: 11px !important;
    }

    .next-steps li,
    .important-info li {
        margin-bottom: 3px !important;
        line-height: 1.3 !important;
    }

    * {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>