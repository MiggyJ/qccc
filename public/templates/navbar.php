<?php
  session_start();
?>
<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark w-100" id="navbar">
  <a class="navbar-brand" href="<?=getenv('APP_BASE')?>">
    <img src="./assets/brand.png" height="50" alt="">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav nav-pills ml-auto mr-3">
      <li class="nav-item">
        <a class="nav-link" href="#home">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#faq">FAQs</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#about">About</a>
      </li>
      <!-- If not logged in, prompt to log in -->
      <?php if(!isset($_SESSION['user'])):?>
      <li class="nav-item">
        <a class="nav-link" data-toggle="modal" href data-target="#loginModal">Admin</a>
      </li>
      <!-- Otherwise, redirect to admin page -->
      <?php else:?>
      <li class="nav-item"><a href="<?=getenv('APP_BASE')?>admin" class="nav-link">Admin</a></li>
      <?php endif?>
    </ul>
  </div>
</nav>