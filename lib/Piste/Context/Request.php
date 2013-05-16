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

    # http param functions. All designed not to complain if params not defined
    # POST wins over GET if param names clash
    public function params($param = null){
        $all = array_merge($_GET, $_POST);
        if ($param){
            return isset($all[$param]) ? $all[$param] : null;
        }
        return $all;
    }
    public function get_params($param = null){
        if ($param){
            return isset($_GET[$param]) ? $_GET[$param] : null;
        }
        return $_GET;
    }
    public function post_params($param = null){
        if ($param){
            return isset($_POST[$param]) ? $_POST[$param] : null;
        }
        return $_POST;
    }


    # The args available to the action are set by Piste just before
    # action call. Can't think why you'd want to overwrite them yourself
    # but nothing stopping you if you really want to.
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
