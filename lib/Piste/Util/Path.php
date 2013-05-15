<?php
namespace Piste\Util;
/*=head1 Name
Piste\Util\Path

=head1 DESCRIPTION
Provides methods you might want to perform on a path string

*/
Class Path {
    private $pathparts = array();
    private $dir = '';
    private $is_dir = false;

    function __construct($path){
        if (preg_match('/\/$/', $path)){
            $this->is_dir();
            $path = preg_replace('/\/$/', '', $path);
        }
        if (!$path || $path == ''){
            return;
        }
        $this->pathparts = split('[\/|//]', $path);
    }

    public function no_trailing_slash(){
        if (count($this->pathparts)>0){
            return '/' . join('/', $this->pathparts);
        }
        return '';
    }

    public function __toString(){
        return $this->no_trailing_slash() . $this->dir;
    }

    public function is_dir(){
        $this->is_dir = true;
        $this->dir = '/';
    }

    public function extend($new){
        if ($new && $this->is_dir){
            return $this->__tostring() . $new;
        } elseif ($new) {
            return $this->__tostring() . '/' . $new;
        }
        return $this->__tostring();
    }

}

?>
