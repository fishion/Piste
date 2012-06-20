<?php
namespace Piste;
/*=head1 Name
Piste\ControllerManager

=head1 DESCRIPTION
Coordinates Contoller Actions

=head1 DEPENDENCIES
=cut*/
require_once('Piste/Path.php');
require_once('Piste/Controller.php');
require_once('Piste/ReflectionClass.php');

Class ControllerManager {
    private $reflect_controller_base;
    private $actions = array();
    private $special_actions = array();

    function __construct(){
        $this->reflect_controller_base = new \ReflectionClass('Piste\Controller'); 
    }


    public function register($class){
        $object = new $class();

        # ensure that it's a Piste Controller
        if (!$this->reflect_controller_base->isInstance($object)){
            error_log("$class is not a Piste Controller. Ignoring.");
            return;
        }

        $namespace_path = new Path(mb_strtolower(preg_replace('/^.*?\\\\Controller\\\\(Root)?/i','',$class)));

        # get all the methods
        $reflection     = new \Piste\ReflectionClass($class);
        $public         = $reflection->getNonInheritedMethods(\ReflectionMethod::IS_PUBLIC);
        $protected      = $reflection->getNonInheritedMethods(\ReflectionMethod::IS_PROTECTED);

        foreach ($public as $action){
            # register regular actions
            $defvar = $action->name . '_def';

            # is path explicitally set?
            $path = isset($object->$defvar) &&
                    isset($object->{$defvar}['path']) ?
                        $object->{$defvar}['path'] :
                        $action->name;
            # is that global or local?
            if (preg_match('/^\//', $path)){
                # global. Do nothing
            } else {
                # local, Make it global
                $path = $namespace_path . '/' . $path;
            }
            array_push(
                $this->actions,
                array(
                    'path'      => $path,
                    'object'    => $object,
                    'method'    => $action->name,
                    'namespace' => $namespace_path
                )
            );
            error_log("Registered path $path to ". get_class($object) ."\\$action->name\()");
        }

        # deal with special methods
        $saref = &$this->special_actions;
        while ($part = $namespace_path->walkup()){
            if (!isset($saref[$part])){
                $saref[$part] = array();
            }
            $saref = &$saref[$part];
        }
        foreach ($protected as $special){
            if (array_search($special->name, array('fallback', 'before', 'after', 'auto')) === false){
                die("Protected method '$special->name' not allowed in contoller class. So there.");
            } else {
                $saref['-action'][$special->name] = array(
                    'object'    => $object,
                    'method'    => $special->name,
                    'namespace' => $namespace_path
                );
                error_log("Registered special action '$special->name' in path '$namespace_path' to ". get_class($object) ."\\$special->name\()");
            }
        }

    }


    public function run($pc) {
        $uripath = $pc->req()->path();
        $action = null;

        // Find defined action
        foreach ($this->actions as $act){
            if ($act['path'] == $uripath){
                $action = $act;
                break;
            }
        } 

        // look for fallback action
        if (!$action){
            $action = $uripath->find_most_specific( &$this->special_actions, 'fallback' );
        }
        if ($action){
            # run 'before'-method
            $action['namespace']->run_most_specific( &$this->special_actions, 'before', $pc );
            # run 'auto' methods
            $action['namespace']->run_all_matching( &$this->special_actions, 'auto', $pc );
            # run main controller
            $action['object']->call_action($action['method'],$pc);
            # run 'after'-method
            $action['namespace']->run_most_specific( &$this->special_actions, 'after', $pc );
            # set default template TODO - Controller probably isn't the right place to do this
            if (!$pc->stash('template')){
                $template = $action['namespace'] . DIRECTORY_SEPARATOR . $action['method'];
                $pc->stash('template', preg_replace('/^\//', '', $template));
            }
        } else {
            $pc->res()->return_404(true);
        }
    }

}

?>
