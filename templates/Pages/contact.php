<?php
/**
 * CakePHP Hospital Contact Page
 * File: templates/Pages/contact.php
 */
?>

<?php $this->assign('title', 'Contact Us - City General Hospital'); ?>

<style>
    .contact-hero {
        background: linear-gradient(135deg, var(--hospital-primary), var(--hospital-secondary));
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
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
    
    .contact-icon {
        background: linear-gradient(45deg, var(--hospital-primary), var(--hospital-secondary));
        color: white;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 1.5rem;
    }
    
    .form-control:focus {
        border-color: var(--hospital-secondary);
        box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
    }
    
    .btn-contact {
        background: linear-gradient(45deg, var(--hospital-primary), var(--hospital-secondary));
        border: none;
        padding: 12px 30px;
        font-weight: 600;
        border-radius: 25px;
        transition: all 0.3s ease;
    }
    
    .btn-contact:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .map-container {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        height: 400px;
        background: linear-gradient(45deg, var(--hospital-accent), #f8f9fa);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--hospital-primary);
        font-size: 3rem;
    }
    
    .emergency-alert {
        background: linear-gradient(45deg, #dc3545, #e74c3c);
        color: white;
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        margin-bottom: 3rem;
        animation: pulse-emergency 2s infinite;
    }
    
    @keyframes pulse-emergency {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }
    
    .department-card {
        background: var(--hospital-accent);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid var(--hospital-primary);
        transition: all 0.3s ease;
    }
    
    .department-card:hover {
        background: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>

<!-- Contact Hero -->
<section class="contact-hero">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">
            <i class="fas fa-envelope me-3"></i>Contact Us
        </h1>
        <p class="lead">We're here to help you with all your healthcare needs</p>
    </div>
</section>

<!-- Emergency Alert -->
<div class="container">
    <div class="emergency-alert">
        <h3><i class="fas fa-exclamation-triangle me-2"></i>Medical Emergency?</h3>
        <p class="mb-3">For life-threatening emergencies, call 911 immediately or visit our Emergency Department</p>
        <a href="tel:911" class="btn btn-light btn-lg fw-bold">
            <i class="fas fa-phone me-2"></i>Call 911 Now
        </a>
    </div>
</div>

<!-- Contact Information -->
<section class="py-5">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-lg-4 col-md-6">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h4 class="text-center mb-3">Phone Numbers</h4>
                    <div class="text-center">
                        <p><strong>Main Hospital:</strong><br>(555) 123-4567</p>
                        <p><strong>Appointments:</strong><br>(555) 123-4568</p>
                        <p><strong>Emergency:</strong><br>911</p>
                        <p><strong>Patient Services:</strong><br>(555) 123-4569</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4 class="text-center mb-3">Location</h4>
                    <div class="text-center">
                        <p><strong>City General Hospital</strong><br>
                        123 Medical Center Drive<br>
                        Healthcare City, HC 12345</p>
                        <p><strong>Parking:</strong> Free patient parking available</p>
                        <p><strong>Public Transit:</strong> Bus routes 12, 15, 23</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4 class="text-center mb-3">Hours</h4>
                    <div class="text-center">
                        <p><strong>Emergency Department:</strong><br>24/7 - Always Open</p>
                        <p><strong>Main Hospital:</strong><br>24/7</p>
                        <p><strong>Outpatient Services:</strong><br>Mon-Fri: 7:00 AM - 8:00 PM<br>Sat-Sun: 8:00 AM - 6:00 PM</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Contact Form -->
                <div class="contact-card">
                    <h3 class="mb-4"><i class="fas fa-envelope me-2 text-primary"></i>Send Us a Message</h3>
                    <?= $this->Form->create(null, ['class' => 'contact-form']) ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <?= $this->Form->control('first_name', [
                                'label' => 'First Name *',
                                'class' => 'form-control',
                                'required' => true,
                                'placeholder' => 'Enter your first name'
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $this->Form->control('last_name', [
                                'label' => 'Last Name *',
                                'class' => 'form-control',
                                'required' => true,
                                'placeholder' => 'Enter your last name'
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $this->Form->control('email', [
                                'label' => 'Email Address *',
                                'type' => 'email',
                                'class' => 'form-control',
                                'required' => true,
                                'placeholder' => 'your.email@example.com'
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $this->Form->control('phone', [
                                'label' => 'Phone Number',
                                'class' => 'form-control',
                                'placeholder' => '(555) 123-4567'
                            ]) ?>
                        </div>
                        <div class="col-12">
                            <?= $this->Form->control('subject', [
                                'label' => 'Subject *',
                                'class' => 'form-control',
                                'required' => true,
                                'options' => [
                                    'general' => 'General Inquiry',
                                    'appointment' => 'Appointment Request',
                                    'billing' => 'Billing Question',
                                    'medical_records' => 'Medical Records',
                                    'insurance' => 'Insurance Question',
                                    'complaint' => 'Patient Complaint',
                                    'compliment' => 'Compliment/Feedback',
                                    'other' => 'Other'
                                ],
                                'empty' => 'Select a subject...',
                                'type' => 'select'
                            ]) ?>
                        </div>
                        <div class="col-12">
                            <?= $this->Form->control('message', [
                                'label' => 'Message *',
                                'type' => 'textarea',
                                'class' => 'form-control',
                                'required' => true,
                                'rows' => 5,
                                'placeholder' => 'Please provide details about your inquiry...'
                            ]) ?>
                        </div>
                        <div class="col-12">
                            <div class="form-check mb-3">
                                <?= $this->Form->checkbox('privacy_consent', [
                                    'class' => 'form-check-input',
                                    'required' => true
                                ]) ?>
                                <label class="form-check-label" for="privacy-consent">
                                    I consent to the collection and use of my personal information as outlined in the 
                                    <a href="/privacy-policy" target="_blank">Privacy Policy</a> *
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <?= $this->Form->button(
                                '<i class="fas fa-paper-plane me-2"></i>Send Message',
                                [
                                    'type' => 'submit',
                                    'class' => 'btn btn-contact btn-lg text-white',
                                    'escape' => false
                                ]
                            ) ?>
                        </div>
                    </div>
                    
                    <?= $this->Form->end() ?>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Department Directory -->
                <div class="contact-card">
                    <h3 class="mb-4"><i class="fas fa-building me-2 text-primary"></i>Department Directory</h3>
                    
                    <div class="department-card">
                        <h6><i class="fas fa-user-md me-2"></i>Patient Services</h6>
                        <p class="mb-1">General inquiries, appointments</p>
                        <small class="text-muted">(555) 123-4569</small>
                    </div>
                    
                    <div class="department-card">
                        <h6><i class="fas fa-file-invoice-dollar me-2"></i>Billing Department</h6>
                        <p class="mb-1">Insurance, payments, financial assistance</p>
                        <small class="text-muted">(555) 123-4570</small>
                    </div>
                    
                    <div class="department-card">
                        <h6><i class="fas fa-folder-medical me-2"></i>Medical Records</h6>
                        <p class="mb-1">Patient records, test results</p>
                        <small class="text-muted">(555) 123-4571</small>
                    </div>
                    
                    <div class="department-card">
                        <h6><i class="fas fa-user-nurse me-2"></i>Nursing Administration</h6>
                        <p class="mb-1">Patient care concerns</p>
                        <small class="text-muted">(555) 123-4572</small>
                    </div>
                    
                    <div class="department-card">
                        <h6><i class="fas fa-briefcase me-2"></i>Human Resources</h6>
                        <p class="mb-1">Employment, careers</p>
                        <small class="text-muted">(555) 123-4573</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Map Section -->
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="text-center mb-4">Find Us</h3>
                <div class="map-container">
                    <div class="text-center">
                        <i class="fas fa-map-marked-alt"></i>
                        <h5 class="mt-3">Interactive Map</h5>
                        <p class="text-muted">Map integration would be implemented here<br>
                        (Google Maps, OpenStreetMap, etc.)</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Contact Options -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="contact-card">
                    <h3 class="text-center mb-4">Other Ways to Reach Us</h3>
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-envelope-open text-primary" style="font-size: 2rem;"></i>
                            <h5 class="mt-2">Email</h5>
                            <p><a href="mailto:info@citygeneralhospital.com">info@citygeneralhospital.com</a></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-comments text-primary" style="font-size: 2rem;"></i>
                            <h5 class="mt-2">Live Chat</h5>
                            <p>Available on our website<br>Mon-Fri: 8 AM - 6 PM</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fab fa-facebook text-primary" style="font-size: 2rem;"></i>
                            <h5 class="mt-2">Social Media</h5>
                            <p>Follow us for health tips and updates</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>