<?php
/** Pesan flash (success / error / info) */
foreach (array('success', 'error', 'info') as $tipe) {
    $msg = Session::flash($tipe);
    if ($msg) {
        $map = array(
            'success' => 'alert-success',
            'error'   => 'alert-danger',
            'info'    => 'alert-info',
        );
        $icon = array(
            'success' => 'bi-check-circle',
            'error'   => 'bi-exclamation-circle',
            'info'    => 'bi-info-circle',
        );
        echo '<div class="alert ' . $map[$tipe] . ' alert-dismissible fade show" role="alert">'
           . '<i class="bi ' . $icon[$tipe] . ' me-2"></i>' . $msg
           . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
}
