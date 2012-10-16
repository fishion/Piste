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
    
    #####
    # chained methods
    #####

    # This one deliberately named the same as one in Level1 to ensure
    # hierarchy is respected.
    public $chained2_def = array(
        'chained'   => 'chained1',
    );
    public function chained2($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    public $chained2_3_def = array(
        'chained'   => 'chained2',
        'endchain'  => true,
    );
    public function chained2_3($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }

    # This one sets the 'path' attribute to not use the method name
    # directly in the matching regex
    # TODO what happens if the 'path' is relative vs global?
    public $chained_path1_def = array(
        'chained'   => '/chained1',
        'path'      => 'bitofapath',
        'args'      => 1,
    );
    public function chained_path1($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    public $chained_path2_def = array(
        'chained'   => 'chained_path1',
        'path'      => '',
        # this one gets args as no arge param defined
    );
    public function chained_path2($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    public $chained_path3_def = array(
        'chained'   => 'chained_path2',
        'path'      => '',
        # this one gets no args, because it's chained off
        # another method which will greedily take them all
    );
    public function chained_path3($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    public $chained_path4_def = array(
        'chained'   => 'chained_path3',
        'path'      => 'bitofapath/anotherbitofapath',
        'endchain'  => 'true',
    );
    public function chained_path4($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    
}

?>
