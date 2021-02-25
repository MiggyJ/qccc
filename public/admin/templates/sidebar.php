<div style="height:100vh;" class="bg-light">
    <div class="container pt-1">
        <div class="row">
            <a href="<?=getenv('APP_BASE')?>">
                <img src="../assets/logo.png" alt="Logo" class="img-fluid px-4">
            </a>
        </div>
        <hr style="margin-top: 0.6rem;">
        <div class="row">
            <div class="col">
            <ul class="nav flex-column justify-content-center" id="sidenav" style="color: #ccc">
                <li class="nav-item mb-2">
                    <a href="<?=getenv('APP_BASE')?>" class="nav-link p-0 d-flex flex-column align-items-center" style="font-size: 1.5em">
                        <i class="bi bi-house-door" title="Home"></i>
                        <div style="font-size: 0.5em" class="short-text">Home</div>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="<?=getenv('APP_BASE')?>admin" class="nav-link p-0 d-flex flex-column align-items-center" style="font-size: 1.5em">
                        <i class="bi bi-speedometer2" title="Admin Dashboard"></i>
                        <div style="font-size: 0.5em" class="short-text">Dashboard</div>
                    </a>
                </li>
                <?php if(strcmp($_SESSION['user']->superuser, '1')):?>
                <li class="nav-item mb-2">
                    <a href="<?=getenv('APP_BASE')?>admin/form" class="nav-link p-0 d-flex flex-column align-items-center" style="font-size: 1.5em">
                        <i class="bi bi-file-earmark-plus" title="Add New Case"></i>
                        <div style="font-size: 0.5em" class="short-text">New Case</div>
                    </a>
                </li>
                <?php endif?>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link p-0 d-flex flex-column align-items-center" style="font-size: 1.5em" data-toggle="collapse" data-target="#navCollapse" aria-expanded="false" aria-controls="navCollapse" id="collapseToggler">
                        <i class="bi bi-list-nested" title="Patients"></i>
                        <div style="font-size: 0.5em" class="short-text">Patients</div>
                    </a>
                    <div id="navCollapse" class="collapse" style="font-size: 0.8em;">
                        <ul class="nav flex-column align-items-center">
                            <li class="nav-item mb-1 mt-1">
                                <a href="<?=getenv('APP_BASE')?>admin/patient?status=1" class="nav-link p-0 text-dark collapsed">
                                    Infected
                                </a>
                            </li>
                            <li class="nav-item mb-1">
                                <a href="<?=getenv('APP_BASE')?>admin/patient?status=2" class="nav-link p-0 text-dark collapsed">
                                    Recovered
                                </a>
                            </li>
                            <li class="nav-item mb-1">
                                <a href="<?=getenv('APP_BASE')?>admin/patient?status=3" class="nav-link p-0 text-dark collapsed">
                                    Dead
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php if($_SESSION['user']->superuser === '1'): ?>
                <li class="nav-item">
                    <a href="<?=getenv('APP_BASE')?>admin/users" class="nav-link p-0 d-flex flex-column align-items-center" style="font-size: 1.5em">
                    <i class="bi bi-person" title="Users"></i>
                        <div style="font-size: 0.5em" class="short-text">Users</div>
                    </a>
                </li>
                <?php endif ?>
                <li class="nav-item mb-2">
                    <a href="<?=getenv('APP_BASE')?>admin/change_password" class="nav-link p-0 d-flex flex-column align-items-center" style="font-size: 1.5em">
                        <i class="bi bi-key" title="Change Password"></i>
                        <div style="font-size: 0.5em" class="short-text text-center">Change Password</div>
                    </a>
                </li>
            </ul>
            </div>
        </div>
    </div>
</div>