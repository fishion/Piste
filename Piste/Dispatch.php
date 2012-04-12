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
        error_log("Registering controller " . $class);
        $path = split('/\\/', preg_replace('/^.*?\\\\Controller\\\\/','',$class));

        # Root.pm is special, not part of path.
        if ($path[0] == 'Root') {
            array_shift($path);
        }

        $dref = &$this->dmap;
        foreach ($path as $pathpart){
            $dref[$pathpart] = array();
            $dref = $dref[$pathpart];
        }


        $methods = get_class_methods($class);
        foreach ($methods as $method){
            # create object instance.
            # Sure I'll want to rethink this sometime
            $dref[$method] = array('class' => $class, 'method' => $method);
            error_log('Registered path /' . join('/', $path) . $method . ' ');
        }
    }

}

?>
