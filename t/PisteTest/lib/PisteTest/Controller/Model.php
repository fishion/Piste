<?php
namespace PisteTest\Controller;
require_once('PisteTest/Controller.php');
/*=head1 Name
PisteTest\Controller\Model

*/
Class Model extends \PisteTest\Controller {

    public function index($pc){
        $ds = $pc->model('TestDataSource');
        $pc->stash('testdata', $ds->data());
        $this->track_execution_stack($pc, __METHOD__, 'index');
    }

}

?>
