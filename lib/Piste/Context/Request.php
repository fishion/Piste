<?php
namespace Piste\Context;
/*=head1 Name
Piste\Context\Request

=head1 DESCRIPTION

=head1 DEPENDENCIES
=cut*/
require_once('Piste/Util/Path/URI.php');

Class Request {
    private $path;
    private $args;

    function __construct(){
        $this->path = new \Piste\Util\Path\URI();
    }

    public function path() {
        return $this->path;
    }

    # http param functions. All designed not to complain if paramss not defined
    # POST wins over get if param names clash
    public function params(){
        return array_merge($_GET, $_POST);
    }
    public function param($param){
        $all = $this->params();
        return isset($all[$param]) ? $all[$param] : null;
    }
    public function get_params(){
        return $_GET;
    }
    public function get_param($param){
        return isset($_GET[$param]) ? $_GET[$param] : null;
    }
    public function post_params(){
        return $_POST;
    }
    public function post_param($param){
        return isset($_POST[$param]) ? $_POST[$param] : null;
    }


    public function set_args($args = null){
        $this->args = $args;
        return $this->args;
    }
    public function args($index = null){
        if ($index !== null){
            return (isset($this->args[$index])) ? $this->args[$index] : null;
        }
        return $this->args;
    }

}

?>
