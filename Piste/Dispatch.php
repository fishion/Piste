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

Class Dispatch {
    private $cmap = array();
    private $vmap = array();
    private $config = array();

    function __construct($config = array()){
        if (is_array($config)){$this->config=$config;}
    }

    public function register_all($applib, $appname){
        # require all application packages
        $applib_ob = new \File($applib);
        $required = $applib_ob->require_once_all_files('php');

        # Register all installed application MVC classes
        $installed = get_declared_classes();
        foreach ($installed as $class){
            if ( preg_match("/$appname\\\\Controller\\\\/", $class) ){
                $this->reg_controller($class);
            } elseif ( preg_match("/$appname\\\\View\\\\/", $class) ){
                $this->reg_view($class);
            }
        }
    }

    public function dispatch($pc){
        # set view to default view initially
        # that way it can stil be overridden to nothing
        if (isset($this->config['default_view'])){
            $pc->view($this->config['default_view']);
        }
        $this->run_controller($pc);
        $this->run_view($pc);
    }




    private function reg_controller($class){
        $path = split('/\\/', preg_replace('/^.*?\\\\Controller\\\\/','',$class));
        # Root.pm is special, not part of path.
        if ($path[0] == 'Root') {
            array_shift($path);
        }

        # lower case what's left
        $path = array_map('mb_strtolower', $path);

        $dref = &$this->cmap;
        foreach ($path as $pathpart){
            $dref[$pathpart] = array();
            $dref = &$dref[$pathpart];
        }

        $methods = get_class_methods($class);
        foreach ($methods as $method){
            # create object instance.
            # Sure I'll want to rethink this sometime
            $dref[$method] = array('class' => $class, 'method' => $method);
            error_log('Registered path /' . join('/', $path) . (count($path)?'/':'') . $method . ' ');
        }
    }

    private function reg_view($class){
        $path = preg_replace('/^.*?\\\\View\\\\/','',$class);
        #TODO: ensure view class ISA Piste\View
        $this->vmap[$path] = $this->vmap[$class] = new $class();
    }

    private function run_controller($pc) {
        $pathparts = split('/', $pc->request()->uri_path());
        $dref = &$this->cmap;
        foreach ($pathparts as $pp){
            $dref = @$dref[$pp];
            if (!$dref){
                error_log('no controller path found for ' . $pc->request()->uri_path());
                break;
            }
        }
        if ($dref['class'] && $dref['method']){
            $class = new $dref['class']();
            $class->$dref['method']($pc);
        }
    }

    private function run_view($pc){
        $view = $pc->view();
        if ($view && !isset($this->vmap[$view])){
            throw new \Exception("View '$view' not installed");
        } elseif ($view){
            $this->vmap[$view]->render($pc);
        }
        $pc->response()->respond();
    }
}

?>
