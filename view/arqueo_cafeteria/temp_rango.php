<?php
session_start();
?>
<table class="table table-bordered table-sm text-dark mt-4">
    <tr class="table-light text-dark text-uppercase">
        <td>#Id</td>
        <td>Nombre</td>
        <td>Movimiento</td>
        <td>Admin</td>
        <td>Valor</td>
        <td>Medio Pago</td>
        <td>Fecha</td>
    </tr>
<?php
if (isset($_SESSION['datos_rango_arqueo']) && !empty($_SESSION['datos_rango_arqueo'])):
    $total_rango = 0;
    foreach ($_SESSION['datos_rango_arqueo'] as $key) {
        $dat = explode("||", $key);
        $total_rango += (isset($dat[4])) ? $dat[4] : 0;
?>
    <tr class="table-sm bg-white">
        <td><?php echo $dat[0]; ?></td>
        <td class="text-uppercase"><?php echo $dat[1] . ' ' . $dat[2]; ?></td>
        <td><?php echo $dat[3]; ?></td>
        <td class="text-center"><?php echo $dat[8]; ?></td>
        <td>$<?php echo number_format($dat[4]); ?></td>
        <td><?php echo $dat[5]; ?></td>
        <td><?php echo $dat[6]; ?></td>
    </tr>
<?php } ?>
    <tr class="table-info font-weight-bold">
        <td colspan="4" class="text-right">TOTAL EN RANGO:</td>
        <td colspan="3">$<?php echo number_format($total_rango); ?></td>
    </tr>
<?php else: ?>
    <tr><td colspan="7" class="text-center text-white">No hay datos disponibles en la sesión</td></tr>
<?php endif; ?>
</table>