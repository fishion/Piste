<?php
    require_once('PisteTest.php');
    # TODO - make it a singleton object that can stay in memory or something?
    $app = new PisteTest;
    $app->run();
?>
