<?php
namespace PisteTest\Controller;
require_once('Piste/Controller.php');
/*=head1 Name
PisteTest\Controller\Model

*/
Class Model extends \Piste\Controller {

    public function index($pc){
        $ds = $pc->model('TestDataSource');
        $pc->stash('testdata', $ds->data());
        $pc->stash('template', 'index');
    }

}

?>
