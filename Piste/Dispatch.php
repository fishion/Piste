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

Class Dispatch {
    private $controllers;
    private $view_map = array();
    private $config = array();

    function __construct($config = array()){
        if (is_array($config)){$this->config=$config;}
        $this->controllers = new Dispatch\Controllers();
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
                $this->reg_view($class);
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
        $this->run_view($pc);
    }





    private function reg_view($class){
        $path = preg_replace('/^.*?\\\\View\\\\/','',$class);
        #TODO: ensure view class ISA Piste\View
        # Allow people to use fully qualified $class or strip
        # off <Appname>\View
        $this->view_map[$path] = $this->view_map[$class] = $class;
    }

    private function run_view($pc){
        $view = $pc->response()->view();
        if ($view && !isset($this->view_map[$view])){
            throw new \Exception("View '$view' not installed");
        } elseif ($view){
            $view = new $this->view_map[$view]($pc);
            if ($pc->res()->return_404()){
                $view->render_404($pc);
            } else {
                $view->render($pc);
            }
        }
        $pc->response()->respond();
    }
}

?>
