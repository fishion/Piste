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
require_once('Logger.php');
require_once('Piste/Dispatch/Controllers.php');
require_once('Piste/Dispatch/Views.php');

Class Dispatch {
    private $controllers;
    private $views;
    private $config = array();

    function __construct($config = array()){
        if (is_array($config)){$this->config=$config;}
        $this->controllers = Dispatch\Controllers::singleton();
        $this->views = Dispatch\Views::singleton();
    }

    public function register_all($pc){
        $installed = get_declared_classes();
        $this->require_packages($pc);
        $this->register_packages($pc, array_diff(get_declared_classes(), $installed));
    }

    public function dispatch($pc){
        # set view to default view initially
        # that way it can stil be overridden to nothing
        \Logger::debug('*****************');
        \Logger::debug('* Finding dispatch for ' .$pc->req()->path());
        \Logger::debug('*****************');
        if (isset($this->config['default_view'])){
            $pc->response()->view($this->config['default_view']);
        }
        $this->controllers->run($pc);
        $this->views->run($pc);
    }



    private function require_packages($pc){
        foreach (array('Controller', 'C', 'View', 'V') as $dir){
            $dirpath = $pc->env()->app_lib() . DIRECTORY_SEPARATOR . $pc->env()->app_name() . DIRECTORY_SEPARATOR . $dir;
            \Logger::debug("Requiring Piste Packages from $dirpath");
            $applib_ob = new \File($dirpath);
            if ($applib_ob->is_dir()){
                $reg_files = $applib_ob->require_once_all_files('php');
                foreach ($reg_files as $file){
                    \Logger::debug(" * Found $file");
                }
            }
        }
    }
    private function register_packages($pc, $packages){
        $app_name = $pc->env()->app_name();
        foreach ($packages as $class){
            if ( (is_subclass_of($class, 'Piste\Controller') &&
                  preg_match("/^$app_name\\\\Controller\\\\/", $class)) ||
                 (is_subclass_of($class, 'Piste\View') &&
                  preg_match("/^$app_name\\\\View\\\\/", $class)) ){
                $object = new $class($pc);
                $object->P_register();
            }
        }
    }
}

?>
