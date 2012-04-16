<?php
namespace Piste;
/*=head1 Name
Piste\Dispatch

=head1 DESCRIPTION
Manages URL mapping and dispatch to controller logic

*/
Class Dispatch {
    private $dmap = array();

    function register_all($appname){
        $installed = get_declared_classes();
        foreach ($installed as $class){
            if ( preg_match("/$appname\\\\Controller\\\\/", $class) ){
                $this->register($class);
            }
        }
    }

    function register($class){
        $path = split('/\\/', preg_replace('/^.*?\\\\Controller\\\\/','',$class));
        # Root.pm is special, not part of path.
        if ($path[0] == 'Root') {
            array_shift($path);
        }

        # lower case what's left
        $path = array_map('mb_strtolower', $path);

        $dref = &$this->dmap;
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

    function get_uri_path() {
        $path = $_SERVER["REQUEST_URI"];
        $path = preg_replace("/^\//",'',$path); # no leading slash
        $path = preg_replace("/\?.*/", '', $path); # strip off GET params
        $path = preg_replace("/\.(json|xml|html)$/", '', $path); # strip off response format
        if ( !$path || preg_match("/\/$/", $path) ){
            $path = $path . 'index';
        }
        return $path;
    }

    function run_controller($pob) {
        $pathparts = split('/', $this->get_uri_path());
        $dref = &$this->dmap;
        foreach ($pathparts as $pp){
            $dref = @$dref[$pp];
            if (!$dref){
                error_log('no controller path found for ' . $this->get_uri_path());
                break;
            }
        }
        if ($dref['class'] && $dref['method']){
            $class = new $dref['class']();
            $class->$dref['method']($pob);
        }
    }
}

?>
