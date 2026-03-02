<?php
session_start();
?>
<table class="table table-bordered table-sm text-white mt-4">
    <tr class="table-light text-dark text-uppercase">
        <td>#Id</td>
        <td>Nombre</td>
        <td>Movimiento</td>
        <td>Admin</td>
        <td>Valor</td>
        <td>Medio Pago</td>
        <td>Fecha</td>
        <td>Hora</td>
    </tr>

<?php
if (isset($_SESSION['datos_uno_varios_arqueo'])):
    $transferencia = 0; $efectivo = 0; $abonos = 0; $recargas = 0; $retiros = 0; $deudas = 0;
    $admins_recargas_efectivo = []; // Array para almacenar los administradores y el total de sus recargas en efectivo

    foreach (@$_SESSION['datos_uno_varios_arqueo'] as $key) {
        $dat = explode("||", $key);
?>
    <tr class="table-sm text-dark">
        <td><?php echo $dat[0]; ?></td>
        <td class="text-uppercase"><?php echo $dat[1] . ' ' . $dat[2]; ?></td>
        <td class="<?php if (in_array($dat[3], ['recarga', 'abono', 'saldar_deuda'])) { echo 'table-success'; } else { echo 'table-danger'; } ?>">
            <?php echo $dat[3]; ?>
        </td>
        <td class="text-center"><?php echo $dat[8]; ?></td>
        <td class="<?php if (in_array($dat[3], ['recarga', 'abono', 'saldar_deuda'])) { echo 'table-success'; } else { echo 'table-danger'; } ?>">
            <?php echo number_format($dat[4]); ?>
        </td>
        <td class="<?php if ($dat[5] == 'Efectivo') { echo 'table-info'; } else if ($dat[5] == 'Transferencia') { echo 'table-warning'; } ?>">
            <?php echo $dat[5]; ?>
        </td>
        <td><?php echo $dat[6]; ?></td>
        <td><?php echo $dat[7]; ?></td>
        <?php
            if ($dat[3] == 'abono') {
                $abonos += $dat[4];
            } elseif ($dat[3] == 'recarga') {
                $recargas += $dat[4];
            } elseif ($dat[3] == 'retiro') {
                $retiros += $dat[4];
            } elseif ($dat[3] == 'saldar_deuda') {
                $deudas += $dat[4];
            }

            if (in_array($dat[3], ['abono', 'recarga', 'saldar_deuda']) && $dat[5] == 'Efectivo') {
                $efectivo += $dat[4];
            }
            if (in_array($dat[3], ['abono', 'recarga', 'saldar_deuda']) && $dat[5] == 'Transferencia') {
                $transferencia += $dat[4];
            }

            // Almacenar el nombre del administrador y sumar su recarga en efectivo
            if ($dat[3] == 'recarga' && $dat[5] == 'Efectivo') {
                if (isset($admins_recargas_efectivo[$dat[8]])) {
                    // Si el administrador ya existe en el array, sumar el valor de la recarga
                    $admins_recargas_efectivo[$dat[8]] += $dat[4];
                } else {
                    // Si el administrador no existe, agregarlo con el valor de la recarga
                    $admins_recargas_efectivo[$dat[8]] = $dat[4];
                }
            }
        ?>
    </tr>
<?php } ?>

    <tr class="text-dark">
        <td class="table-secondary" colspan="4"></td>
        <td class="table-secondary">Abonos</td>
        <td class="table-light" colspan="2"><?php echo '$' . number_format($abonos); ?></td>
    </tr>
    <tr class="text-dark">
        <td class="table-secondary" colspan="4"></td>
        <td class="table-secondary">Recargas</td>
        <td class="table-light" colspan="2"><?php echo '$' . number_format($recargas); ?></td>
    </tr>
    <tr class="text-dark">
        <td class="table-secondary" colspan="4"></td>
        <td class="table-secondary">Retiros</td>
        <td class="table-light" colspan="2"><?php echo '$' . number_format($retiros); ?></td>
    </tr>
    <tr class="text-dark">
        <td class="table-secondary" colspan="4"></td>
        <td class="table-secondary">Pago de Deudas</td>
        <td class="table-light" colspan="2"><?php echo '$' . number_format($deudas); ?></td>
    </tr>
    <tr class="text-dark">
        <td class="table-secondary" colspan="4"></td>
        <td class="table-secondary">Total + Efectivo</td>
        <td class="table-light" colspan="2"><?php echo '$' . number_format($efectivo); ?></td>
    </tr>
    <tr class="text-dark">
        <td class="table-secondary" colspan="4"></td>
        <td class="table-secondary">Total Efectivo - retiros</td>
        <td class="table-light" colspan="2"><?php echo '$' . number_format($efectivo - $retiros); ?></td>
    </tr>
    <tr class="text-dark">
        <td class="table-secondary" colspan="4"></td>
        <td class="table-secondary">Total + Transferencias</td>
        <td class="table-light" colspan="2"><?php echo '$' . number_format($transferencia); ?></td>
    </tr>

    <!-- Mostrar los administradores que realizaron recargas en efectivo y sus totales -->
    <tr class="text-dark">
        <td class="table-secondary" colspan="4">Administradores que realizaron recargas en efectivo hoy:</td>
        <td class="table-light" colspan="4">
            <?php
            if (!empty($admins_recargas_efectivo)) {
                foreach ($admins_recargas_efectivo as $admin => $total) {
                    echo "$admin: $" . number_format($total) . "<br>";
                }
            } else {
                echo "No se realizaron recargas en efectivo hoy.";
            }
            ?>
        </td>
    </tr>

<?php endif; ?>
</table>
