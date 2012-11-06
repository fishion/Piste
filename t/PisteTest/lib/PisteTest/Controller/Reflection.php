<?php
namespace PisteTest\Controller;
require_once('Piste/Controller.php');
/*=head1 Name
PisteTest\Controller\Reflection

*/
Class Reflection extends \Piste\Controller {

    protected function fallback($pc){
    }

    public function pub1($pc) {
    }
    public function pub2($pc) {
    }

    private function priv1($pc){
    }
    private function priv2($pc){
    }
    private function priv3($pc){
    }
}

?>
