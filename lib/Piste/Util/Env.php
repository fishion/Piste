<?php
namespace Piste\Util;
/*=head1 Name
Piste\Util\Env

=head1 DESCRIPTION
Encapsulates the running environment

=head1 DEPENDENCIES
=cut*/
require_once('File.php');

Class Env {
    private $app_name;
    private $app_lib;
    private $app_base;

    function __construct($app_name){
        $this->app_name = $app_name;
        $baseclass = new \File($app_name . '.php');
        $this->app_lib = $baseclass->find_absolute_path();
        $this->app_base = preg_replace('/lib\/?$/','', $this->app_lib);
    }

    public function app_name() {return $this->app_name;}
    public function app_lib() {return $this->app_lib;}
    public function app_base() {return $this->app_base;}

    public function add_include_path($path){
        set_include_path(get_include_path() . PATH_SEPARATOR . $path);
    }
}

?>
