<?php
namespace PisteTest\Controller;
require_once('Piste/Controller.php');
/*=head1 Name
PisteTest\Controller\Level1WithFallback

*/
Class Level1WithFallback extends \PisteTest\Controller {

    # test that anything in this namespace uses this fallback method over the root one
    protected function fallback($pc) {
        $this->track_execution_stack($pc, __METHOD__);
    }
}

?>
