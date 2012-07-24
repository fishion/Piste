<?php
namespace Piste;
/*=head1 Name
Piste\Dispatch

=head1 DESCRIPTION
Manages URL mapping and dispatch to controller logic

=head1 DEPENDENCIES
File
=cut*/
require_once('File.php');
require_once('Piste/Dispatch/Controllers.php');
require_once('Piste/Dispatch/Views.php');

Class Dispatch {
    private $controllers;
    private $views;
    private $config = array();

    function __construct($config = array()){
        if (is_array($config)){$this->config=$config;}
        $this->controllers = new Dispatch\Controllers();
        $this->views = new Dispatch\Views();
    }

    public function register_all($pc){
        $app_name = $pc->env()->app_name();
        # require all application packages
        $applib_ob = new \File($pc->env()->app_lib());
        $required = $applib_ob->require_once_all_files('php');

        # Register all installed application MVC classes
        $installed = get_declared_classes();
        foreach ($installed as $class){
            # TODO use ISA instead of string matching
            if ( preg_match("/$app_name\\\\Controller\\\\/", $class) ){
                $this->controllers->register($class);
            } elseif ( preg_match("/$app_name\\\\View\\\\/", $class) ){
                $this->views->register($class);
            }
        }
    }

    public function dispatch($pc){
        # set view to default view initially
        # that way it can stil be overridden to nothing
        error_log('*****************');
        error_log('* Finding dispatch for ' .$pc->req()->path());
        error_log('*****************');
        if (isset($this->config['default_view'])){
            $pc->response()->view($this->config['default_view']);
        }
        $this->controllers->run($pc);
        $this->views->run($pc);
    }

}

?>
