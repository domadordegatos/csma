<?php
session_start();
 ?>
            
      <?php
      if (isset($_SESSION['registros_user'])):
        $i = 0;
        foreach (@$_SESSION['registros_user'] as $key) {
        $dat=explode("||", $key);
        $i++;
       ?>
       <div class="row">
                    <div class="col-sm-6 text-center border bg-primary">Registro <?php echo $i ?></div>
                    <div class="col-sm-6 border"><?php echo $dat[3]; ?></div>
        </div>

<?php }  endif;?>
    