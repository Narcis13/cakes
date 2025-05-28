<?php
/**
 * CakePHP Hospital Index Page
 * File: templates/Pages/index.php
 */
?>

<?php $this->assign('title', 'Welcome to City General Hospital'); ?>

<style>
    .hero-section {
        background: linear-gradient(rgba(44, 90, 160, 0.8), rgba(74, 144, 226, 0.8)), 
                    url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><rect fill="%23f0f8ff" width="1200" height="600"/><circle fill="%23e8f4fd" cx="200" cy="100" r="50" opacity="0.3"/><circle fill="%23d1e9f6" cx="800" cy="200" r="80" opacity="0.2"/><circle fill="%23b8ddf0" cx="400" cy="400" r="60" opacity="0.25"/></svg>');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 5rem 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(44, 90, 160, 0.1), rgba(74, 144, 226, 0.1));
        animation: shimmer 3s ease-in-out infinite;
    }
    
    @keyframes shimmer {
        0%, 100% { opacity: 0.1; }
        50% { opacity: 0.3; }
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
    }
    
    .hero-section h1 {
        font-size: 3.5rem;
        font-weight: bold;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .hero-section p {
        font-size: 1.3rem;
        margin-bottom: 2rem;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    }
    
    .btn-hero {
        background: linear-gradient(45deg, #28a745, #20c997);
        border: none;
        padding: 15px 30px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }
    
    .btn-hero:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    }
    
    .service-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        height: 100%;
        border: 1px solid rgba(44, 90, 160, 0.1);
    }
    
    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
    
    .service-icon {
        background: linear-gradient(45deg, var(--hospital-primary), var(--hospital-secondary));
        color: white;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
    }
    
    .stats-section {
        background: var(--hospital-accent);
        padding: 4rem 0;
        margin: 4rem 0;
    }
    
    .stat-item {
        text-align: center;
        padding: 2rem;
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: bold;
        color: var(--hospital-primary);
        display: block;
    }
    
    .stat-label {
        color: #666;
        font-size: 1.1rem;
        margin-top: 0.5rem;
    }
    
    .news-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .news-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    .news-image {
        height: 200px;
        background: linear-gradient(45deg, var(--hospital-accent), var(--hospital-secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--hospital-primary);
        font-size: 3rem;
    }
    
    .news-content {
        padding: 1.5rem;
    }
    
    .news-date {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1><i class="fas fa-heart-pulse me-3"></i>Your Health, Our Priority</h1>
        <p class="lead">Excellence in healthcare with compassionate care for over 50 years</p>
        <?= $this->Html->link(
            '<i class="fas fa-calendar-check me-2"></i>Book Appointment',
            '/appointments',
            ['class' => 'btn btn-hero btn-lg text-white', 'escape' => false]
        ) ?>
    </div>
</section>

<!-- Services Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-4 fw-bold text-primary mb-3">Our Medical Services</h2>
                <p class="lead text-muted">Comprehensive healthcare services with state-of-the-art facilities</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-ambulance"></i>
                    </div>
                    <h4 class="mb-3">Emergency Care</h4>
                    <p class="text-muted">24/7 emergency services with rapid response team and advanced life support.</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4 class="mb-3">Cardiology</h4>
                    <p class="text-muted">Comprehensive heart care including diagnostics, treatment, and cardiac surgery.</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-baby"></i>
                    </div>
                    <h4 class="mb-3">Pediatrics</h4>
                    <p class="text-muted">Specialized care for children from newborns to adolescents with dedicated pediatric unit.</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-x-ray"></i>
                    </div>
                    <h4 class="mb-3">Imaging</h4>
                    <p class="text-muted">Advanced diagnostic imaging including MRI, CT scans, ultrasound, and X-rays.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <span class="stat-number">50+</span>
                    <div class="stat-label">Years of Service</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <span class="stat-number">200+</span>
                    <div class="stat-label">Expert Doctors</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <span class="stat-number">1000+</span>
                    <div class="stat-label">Beds Available</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <span class="stat-number">50k+</span>
                    <div class="stat-label">Patients Served</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- News Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-4 fw-bold text-primary mb-3">Latest News & Updates</h2>
                <p class="lead text-muted">Stay informed about our latest medical advancements and community health initiatives</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="news-card">
                    <div class="news-image">
                        <i class="fas fa-microscope"></i>
                    </div>
                    <div class="news-content">
                        <div class="news-date">December 15, 2024</div>
                        <h5 class="mb-3">New Cancer Treatment Center Opens</h5>
                        <p class="text-muted">Our state-of-the-art cancer treatment facility now offers the latest in oncology care with advanced radiation therapy.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Read More</a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="news-card">
                    <div class="news-image">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="news-content">
                        <div class="news-date">December 10, 2024</div>
                        <h5 class="mb-3">Robotic Surgery Program Launched</h5>
                        <p class="text-muted">We're proud to introduce minimally invasive robotic surgery for improved precision and faster recovery times.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Read More</a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="news-card">
                    <div class="news-image">
                        <i class="fas fa-heart-pulse"></i>
                    </div>
                    <div class="news-content">
                        <div class="news-date">December 5, 2024</div>
                        <h5 class="mb-3">Free Health Screening Event</h5>
                        <p class="text-muted">Join us for our annual community health screening event offering free blood pressure, diabetes, and cholesterol checks.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: linear-gradient(45deg, var(--hospital-primary), var(--hospital-secondary)); color: white;">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-3">Ready to Schedule Your Appointment?</h2>
        <p class="lead mb-4">Our friendly staff is here to help you get the care you need</p>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="row g-3">
                    <div class="col-md-4">
                        <?= $this->Html->link(
                            '<i class="fas fa-phone me-2"></i>Call Us',
                            'tel:+15551234567',
                            ['class' => 'btn btn-light btn-lg w-100', 'escape' => false]
                        ) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Html->link(
                            '<i class="fas fa-calendar me-2"></i>Book Online',
                            '/appointments',
                            ['class' => 'btn btn-success btn-lg w-100', 'escape' => false]
                        ) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Html->link(
                            '<i class="fas fa-envelope me-2"></i>Contact Us',
                            '/contact',
                            ['class' => 'btn btn-outline-light btn-lg w-100', 'escape' => false]
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>