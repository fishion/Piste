<?php
namespace PisteTest\View;
require_once('Piste/View/PHP.php');

Class HTML extends \Piste\View\PHP {

    protected $config = array(
        'wrapper'   => 'layout/wrapper.php',
    );

}

?>
