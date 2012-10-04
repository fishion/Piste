<?php
require_once('Piste.php');

class PisteTest extends Piste {

    protected $config = array(
        'default_view'  => 'HTML',
        'response_type_switch' => array(
            'application/json' => 'JSON',
        ),
    );

}

?>
