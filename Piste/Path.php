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
}

?>
