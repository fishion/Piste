<?php
namespace Piste;
/*=head1 Name
Piste\Path

=head1 DESCRIPTION
Provides methods you might want to perform on a path string

*/
Class Path {
    private $pathparts = array();
    private $pp_index = false;

    function __construct($path){
        if (!$path || $path == ''){
            return;
        }
        $this->pathparts = split('[\/|//]', $path);
    }

    public function __toString(){
        if (count($this->pathparts)>0){
            return '/' . join('/', $this->pathparts);
        }
        return '';
    }

    public function reset(){
        $this->pp_index = false;
        return $this;
    }

    public function walkup(){
        if ($this->pathparts &&
            $this->pp_index === false){
            $this->pp_index = 0;
            return $this->pathparts[$this->pp_index];
        } elseif (
            $this->pathparts &&
            $this->pp_index +1 < count($this->pathparts)) {
            return $this->pathparts[++$this->pp_index];
        } else {
            return false;
        }
    }

    public function find_most_specific($ref,$actionname){
        $this->reset();
        if (isset($ref['-action'][$actionname])){
            $return = $ref['-action'][$actionname];
        }
        // look for progressively more specific action
        while ($part = $this->walkup()){
            if (!isset($ref[$part])){
                break;// not going to find any more
            }
            $ref = &$ref[$part];
            if (isset($ref['-action'][$actionname])){
                $return = $ref['-action'][$actionname];
            }
        }
        return $return;
    }
    public function run_most_specific($ref,$actionname,$pc){
        $action = $this->find_most_specific($ref, $actionname);
        if ($action){
            $action['object']->call_action($actionname,$pc);
        }
    }
    public function run_all_matching( $ref, $actionname, $pc ){
        $this->reset();
        if (isset($ref['-action'][$actionname])){
            $ref['-action'][$actionname]['object']->call_action($actionname,$pc);
        }
        // look for progressively more specific actions
        while ($part = $this->walkup()){
            if (!isset($ref[$part])){
                break;// not going to find any more
            }
            $ref = &$ref[$part];
            if (isset($ref['-action'][$actionname])){
                $ref['-action'][$actionname]['object']->call_action($actionname,$pc);
            }
        }
    }
}

?>
