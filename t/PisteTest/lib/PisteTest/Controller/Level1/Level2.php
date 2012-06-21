<?php
namespace PisteTest\Controller\Level1;
require_once('PisteTest/Controller.php');
/*=head1 Name
PisteTest\Controller\Level1\Level1

*/
Class Level2 extends \PisteTest\Controller {

    # special methods
    protected function before($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    protected function auto($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    protected function after($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }

    # test that 'level1/' redirects as expected to index
    public function index($pc) {
        $this->track_execution_stack($pc, __METHOD__);
    }

    # test explicitly defined absolute path
    public $absolutepath_def = array(
        'path' => '/absolute/path/in/level2'
    );
    public function absolutepath($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }

    # test explicitly defined relative path
    public $relativepath_def = array(
        'path' => 'relative/path/in/level2'
    );
    public function relativepath($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    
}

?>
