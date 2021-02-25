
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="navbar">
  <a class="navbar-brand" href="<?=getenv('APP_BASE')?>">
    <img src="../assets/brand.png" height="50" alt="">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto mr-3 d-lg-none">
      <li class="nav-item mb-2">
        <a href="<?=getenv('APP_BASE')?>" class="nav-link">
            Home
        </a>
      </li>
      <li class="nav-item mb-2">
          <a href="<?=getenv('APP_BASE')?>admin" class="nav-link">
              Dashboard
          </a>
      </li>
      <li class="nav-item mb-2">
        <a href="#" class="nav-link" data-toggle="collapse" data-target="#navbarCollapse" aria-expanded="false" aria-controls="navbarCollapse" id="collapseToggler">
          Patients
        </a>
        <div id="navbarCollapse" class="collapse ml-2" style="font-size: 0.8em;">
          <ul class="nav flex-column" style="align-items: flex-start !important">
              <li class="nav-item mb-1">
                  <a href="<?=getenv('APP_BASE')?>admin/patient?status=1" class="nav-link">
                      Infected
                  </a>
              </li>
              <li class="nav-item mb-1">
                  <a href="<?=getenv('APP_BASE')?>admin/patient?status=2" class="nav-link">
                      Recovered
                  </a>
              </li>
              <li class="nav-item mb-1">
                  <a href="<?=getenv('APP_BASE')?>admin/patient?status=3" class="nav-link">
                      Dead
                  </a>
              </li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a href="<?=getenv('APP_BASE')?>admin/form" class="nav-link">
            New Case
        </a>
      </li>
      <?php if(!strcmp($_SESSION['user']->superuser, '1')):?>
      <li class="nav-item">
        <a href="<?=getenv('APP_BASE')?>admin/users" class="nav-link">
            Users
        </a>
      </li>
      <?php endif?>
      <li class="nav-item">
        <a href="<?=getenv('APP_BASE')?>admin/change_password" class="nav-link">
            Change Password
        </a>
      </li>
    </ul>
  </div>
  <ul class="navbar-nav ml-auto mr-3 d-none d-lg-block">
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" id="dropdownBtn" href data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?=strcmp($_SESSION['user']->superuser, '1') ? $_SESSION['user']->barangay_name : 'Admin'?>
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownBtn">
          <a href="<?=getenv('APP_BASE')?>admin/logout" class="dropdown-item">Log Out</a>
      </div>
    </li>
  </ul>
</nav>