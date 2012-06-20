<?php
namespace PisteTest\Controller;
require_once('PisteTest/Controller.php');
/*=head1 Name
PisteTest\Controller\Root

*/
Class Root extends \PisteTest\Controller {

    # test special methods.
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

    # test that '/' redirects as expected to index
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

    # show results
    public function results($pc){
    }

    
}

?>
