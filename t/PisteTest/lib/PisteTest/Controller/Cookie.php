<?php
namespace PisteTest\Controller;
require_once('PisteTest/Controller.php');
/*=head1 Name
PisteTest\Controller\Cookie

*/
Class Cookie extends \PisteTest\Controller {

    public $set_def = array(
        'args'  => 1,
    );
    public function set($pc){
        $time = $pc->args(0);
        $pc->stash('timetomatch', $time);
        $pc->cookies()->set('mytime', $time);
        $this->track_execution_stack($pc, __METHOD__, 'index');
    }

    public function get($pc){
        $pc->stash('timetomatch', $pc->args(0));
        $pc->stash('mytime', $pc->cookies()->get('mytime'));
        $this->track_execution_stack($pc, __METHOD__, 'index');
    }

    public function delete($pc){
        $pc->cookies()->delete('mytime');
        $this->track_execution_stack($pc, __METHOD__, 'index');
    }
}

?>
