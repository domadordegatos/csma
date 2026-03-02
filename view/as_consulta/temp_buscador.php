<?php
session_start();
 ?>
      <?php
      if (isset($_SESSION['buscador'])):
        foreach (@$_SESSION['buscador'] as $key) {
        $dat=explode("||", $key);
       ?>

                <option value="<?php echo $dat[0]; ?>"><?php echo $dat[1]." ".$dat[2]; ?></option>
                

<?php }  endif;?>
</table>
    