<?php
/**
 * CakePHP Hospital Contact Form Page
 * File: templates/Pages/contact_form.php
 * 
 * @var \App\View\AppView $this
 * @var array $contactInfo
 * @var bool $success
 * @var string $message
 */
?>

<?php $this->assign('title', 'Formular de Contact - Spitalul Militar Pitesti'); ?>

<style>
    .contact-hero {
        background: linear-gradient(135deg, var(--hospital-primary, #2c5aa0), var(--hospital-secondary, #4a90e2));
        color: white;
        padding: 4rem 0;
        text-align: center;
        margin-bottom: 4rem;
    }
    
    .contact-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: 1px solid rgba(44, 90, 160, 0.1);
        transition: all 0.3s ease;
    }
    
    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
    
    .form-control:focus {
        border-color: var(--hospital-secondary, #4a90e2);
        box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
    }
    
    .btn-contact {
        background: linear-gradient(45deg, var(--hospital-primary, #2c5aa0), var(--hospital-secondary, #4a90e2));
        border: none;
        padding: 12px 30px;
        font-weight: 600;
        border-radius: 25px;
        transition: all 0.3s ease;
        color: white !important;
    }
    
    .btn-contact:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    /* Fallback button styling */
    .btn-primary {
        background-color: #2c5aa0 !important;
        border-color: #2c5aa0 !important;
        color: white !important;
    }
    
    #submit-btn {
        min-width: 200px;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: 600;
        background: #2c5aa0;
        border: 2px solid #2c5aa0;
        color: white;
        border-radius: 25px;
        cursor: pointer;
    }
    
    #submit-btn:hover {
        background: #1e3f73;
        border-color: #1e3f73;
        transform: translateY(-2px);
    }
    
    .success-message {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        border-radius: 15px;
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        margin-bottom: 2rem;
    }
    
    .success-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        display: block;
    }
</style>

<!-- Contact Hero -->
<section class="contact-hero">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">
            <i class="fas fa-envelope me-3"></i>Formular de Contact
        </h1>
        <p class="lead">Completați formularul de mai jos și vă vom contacta în cel mai scurt timp</p>
    </div>
</section>

<!-- Contact Form Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if (isset($success) && $success): ?>
                    <!-- Success Message -->
                    <div class="success-message">
                        <i class="fas fa-check-circle success-icon"></i>
                        <h3 class="mb-3">Mesaj trimis cu succes!</h3>
                        <p class="mb-3"><?= h($message) ?></p>
                        <a href="/formular-contact" class="btn btn-light btn-lg mt-2">
                            <i class="fas fa-envelope me-2"></i>Trimite un alt mesaj
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Contact Form -->
                    <div class="contact-card">
                        <h3 class="mb-4"><i class="fas fa-envelope me-2 text-primary"></i>Trimite-ne un mesaj</h3>
                        <?= $this->Form->create(null, ['class' => 'contact-form']) ?>
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <?= $this->Form->control('nume_prenume', [
                                    'label' => 'Nume și prenume *',
                                    'class' => 'form-control',
                                    'required' => true,
                                    'placeholder' => 'Introduceți numele și prenumele dumneavoastră'
                                ]) ?>
                            </div>
                            <div class="col-12">
                                <?= $this->Form->control('email', [
                                    'label' => 'Adresă e-mail *',
                                    'type' => 'email',
                                    'class' => 'form-control',
                                    'required' => true,
                                    'placeholder' => 'exemplu@email.com'
                                ]) ?>
                            </div>
                            <div class="col-12">
                                <?= $this->Form->control('mesaj', [
                                    'label' => 'Mesaj *',
                                    'type' => 'textarea',
                                    'class' => 'form-control',
                                    'required' => true,
                                    'rows' => 5,
                                    'placeholder' => 'Scrieți mesajul dumneavoastră aici...'
                                ]) ?>
                            </div>
                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn btn-contact btn-primary btn-lg" id="submit-btn">
                                    <i class="fas fa-paper-plane me-2"></i>Trimite mesaj
                                </button>
                            </div>
                        </div>
                        
                        <?= $this->Form->end() ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Additional Information -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="contact-card">
                    <h3 class="text-center mb-4">Alte modalități de contact</h3>
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-envelope-open text-primary" style="font-size: 2rem;"></i>
                            <h5 class="mt-2">Email</h5>
                            <p><a href="mailto:<?= h($contactInfo['email']) ?>"><?= h($contactInfo['email']) ?></a></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-phone text-primary" style="font-size: 2rem;"></i>
                            <h5 class="mt-2">Telefon</h5>
                            <p><a href="tel:<?= h($contactInfo['phone']) ?>"><?= h($contactInfo['phone']) ?></a></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-map-marker-alt text-primary" style="font-size: 2rem;"></i>
                            <h5 class="mt-2">Adresă</h5>
                            <p><?= h($contactInfo['address']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>