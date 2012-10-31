<?php
namespace Piste;
/*=head1 Name
Piste\Request

=head1 DESCRIPTION

=head1 DEPENDENCIES
=cut*/
require_once('Piste/Path/URI.php');

Class Request {
    private $path;
    private $args;

    function __construct(){
        $this->path = new Path\URI();
    }

    public function path() {
        return $this->path;
    }

    public function get_param($param){
        # won't bitch if param not defined
        return isset($_GET[$param]) ? $_GET[$param] : null;
    }

    public function set_args($args = null){
        $this->args = $args;
        return $this->args;
    }
    public function args($index = null){
        if ($index !== null){
            return $this->args[$index];
        }
        return $this->args;
    }

}

?>
