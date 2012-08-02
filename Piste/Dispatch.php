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

        # get list of currently declared classes
        $installed = get_declared_classes();

        # Require all application MVC classes in MVC directories
        foreach (array('Controller', 'View', 'C', 'V') as $dir){
            $dirpath = $pc->env()->app_lib() . DIRECTORY_SEPARATOR . $app_name . DIRECTORY_SEPARATOR . $dir;
            error_log("Registering Piste Packages from $dirpath");
            $applib_ob = new \File($dirpath);
            if ($applib_ob->is_dir()){
                $reg_files = $applib_ob->require_once_all_files('php');
                foreach ($reg_files as $file){
                    error_log(" * Found $file");
                }
            }
        }

        # Register all installed application MVC classes
        $new_packages = array_diff(get_declared_classes(), $installed);
        foreach ($new_packages as $class){
            if (is_subclass_of($class, 'Piste\Controller') &&
                preg_match("/^$app_name\\\\Controller\\\\/", $class)){
                $object = new $class();
                $object->P_register();
            } elseif (is_subclass_of($class, 'Piste\View') &&
                      preg_match("/^$app_name\\\\View\\\\/", $class)){
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
