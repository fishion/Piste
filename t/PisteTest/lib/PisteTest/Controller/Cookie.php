<?php
namespace PisteTest\Controller;
require_once('Piste/Controller.php');
/*=head1 Name
PisteTest\Controller\Cookie

*/
Class Cookie extends \Piste\Controller {

    public $set_def = array(
        'args'  => 1,
    );
    public function set($pc){
        $time = $pc->args(0);
        $pc->stash('timetomatch', $time);
        $pc->cookies()->set('mytime', $time);
        $pc->template('index');
    }

    public function get($pc){
        $pc->stash('timetomatch', $pc->args(0));
        $pc->stash('mytime', $pc->cookies()->get('mytime'));
        $pc->template('index');
    }

    public function delete($pc){
        $pc->cookies()->delete('mytime');
        $pc->template('index');
    }
}

?>
