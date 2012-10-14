<?php
namespace PisteTest\Controller;
require_once('PisteTest/Controller.php');
/*=head1 Name
PisteTest\Controller\Root

*/
Class Root extends \PisteTest\Controller {

    # special methods.
    protected function fallback($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    protected function before($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    protected function auto($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    protected function after($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }

    # test index
    public function index($pc) {
        $this->track_execution_stack($pc, __METHOD__);
    }

    # test explicitly defined absolute path
    public $absolutepath_def = array(
        'path' => '/absolute/path/in/root'
    );
    public function absolutepath($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }

    # test explicitly defined relative path
    # which is really same as absolute in Root controller
    public $relativepath_def = array(
        'path' => 'relative/path/in/root'
    );
    public function relativepath($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }


    # url param testing
    public function nofixedargs($pc){
        $this->track_execution_stack($pc, __METHOD__, 'index');
    }
    public $fixedargs0_def = array(
        'args' => 0,
    );
    public function fixedargs0($pc){
        $this->track_execution_stack($pc, __METHOD__, 'index');
    }
    public $fixedargs1_def = array(
        'args' => 1,
    );
    public function fixedargs1($pc){
        $this->track_execution_stack($pc, __METHOD__, 'index');
    }

    # redirection
    public $redirect_def = array(
        'args' => 1,
    );
    public function redirect($pc){
        $this->track_execution_stack($pc, __METHOD__, 'index');
        $pc->res()->redirect('/redirected/'.join('/', $pc->args()));
    }
    public $redirected_def = array(
        'args' => 1,
    );
    public function redirected($pc){
        $this->track_execution_stack($pc, __METHOD__, 'index');
    }

    # a chained action
    public $chained1_def = array(
        'chained'   => '',
        'args'      => 1,
    );
    public function chained1($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    

    # show results
    public function results($pc){
    }
}

?>
