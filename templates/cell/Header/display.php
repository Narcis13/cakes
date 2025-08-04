<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\NavbarItem> $navbarItems
 */
?>
<header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">

      <!-- <h1 class="logo me-auto"><a href="/">Medilab</a></h1> -->
      <!-- Uncomment below if you prefer to use an image logo -->
      <div class="logo me-auto d-flex align-items-center">
        <a href="/"><img src="/img/sigla.jpg" alt="Logo" class="img-fluid me-3" style="max-height: 60px;"></a>
        <a href="/"><img src="/img/logoanmcs_mica.png" alt="ANMCS Logo" class="img-fluid" style="max-height: 60px; margin-left: 15px;"></a>
      </div>

      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
            <?= $this->Menu->renderMenu($navbarItems) ?>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

      <a href="/appointments" class="appointment-btn"><span class="d-none d-md-inline">Programare</span> online</a>

    </div>
</header><!-- End Header -->
