<?php
namespace Piste;
/*=head1 Name
Piste\Path

=head1 DESCRIPTION
Provides methods you might want to perform on a path string

*/
Class Path {
    private $pathparts = array();
    private $dir = '';

    function __construct($path){
        if (preg_match('/\/$/', $path)){
            $path = preg_replace('/\/$/', '', $path);
            $this->dir = '/';
        }
        if (!$path || $path == ''){
            return;
        }
        $this->pathparts = split('[\/|//]', $path);
    }

    public function __toString(){
        if (count($this->pathparts)>0){
            return '/' . join('/', $this->pathparts) . $this->dir;
        }
        return $this->dir;
    }
}

?>
