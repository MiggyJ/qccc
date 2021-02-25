<?php
    // Check for previous login error
    global $error;
?>
<div class="modal fade" id="loginModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="loginModalLabel">QCCC Admin</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="<?=$_SERVER['REQUEST_URI']?>" method="post">
            <div class="modal-body">
                <center class="mb-4">
                    <img src="./assets/logo.png" height="150" alt="">
                </center>
                <?php if(isset($error)):?>
                <center class="alert alert-danger"><?=$error?></center>
                <?php endif?>
                <div class="form-group">
                    <label>E-Mail</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="mt-4 text-center">
                    <a href="<?=getenv('APP_BASE')?>forgot_password" class="btn btn-link">
                        Forgot Password?
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <input name="loginBtn" value="Log In" type="submit" class="btn btn-primary"/>
            </div>
        </form>
    </div>
  </div>
</div>