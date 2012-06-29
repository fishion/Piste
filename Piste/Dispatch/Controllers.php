<?php
namespace Piste\Dispatch;
/*=head1 Name
Piste\ControllerManager

=head1 DESCRIPTION
Coordinates Contoller Actions

=head1 DEPENDENCIES
=cut*/
require_once('Piste/Path.php');
require_once('Piste/Controller.php');
require_once('Piste/ReflectionClass.php');

Class Controllers {
    private $reflect_controller_base;
    private $actions = array();
    private $special_actions = array();

    function __construct(){
        $this->reflect_controller_base = new \ReflectionClass('\Piste\Controller'); 
    }


    public function register($class){
        $object = new $class();

        # ensure that it's a Piste Controller
        if (!$this->reflect_controller_base->isInstance($object)){
            error_log("$class is not a Piste Controller. Ignoring.");
            return;
        }

        # get all the methods
        $reflection     = new \Piste\ReflectionClass($class);
        $public         = $reflection->getNonInheritedMethods(\ReflectionMethod::IS_PUBLIC);
        $protected      = $reflection->getNonInheritedMethods(\ReflectionMethod::IS_PROTECTED);

        # Know our namespace
        $namespace_path = new \Piste\Path(mb_strtolower(preg_replace('/^.*?\\\\Controller\\\\(Root)?/i','',$class)));

        # Find place in special actions data structure
        $saref = &$this->special_actions;
        while ($part = $namespace_path->walkup()){
            if (!isset($saref[$part])){
                $saref[$part] = array();
            }
            $saref = &$saref[$part];
        }

        # register main actions
        foreach ($public as $action){
            $this->register_main_action($object, $namespace_path, $action);
        }

        # register special actions
        foreach ($protected as $action){
            $this->register_special_action(&$saref, $object, $namespace_path, $action);
        }

    }

    private function register_main_action($object, $namespace_path, $action, $is_fallback = false){
        # register regular actions
        $defvar = $action->name . '_def';

        # is path explicitally set?
        $controller_path = $is_fallback
                            ? ''
                            : (isset($object->$defvar) &&
                               isset($object->{$defvar}['path'])
                                 ? $object->{$defvar}['path']
                                 : $action->name);
        # is that global or local?
        if (!preg_match('/^\//', $controller_path)){
            # local, Make it global
            $controller_path = $namespace_path . '/' . $controller_path;
        }

        $fallback_specifity_offset = $is_fallback ? 1 : 0;
        array_push(
            $this->actions,
            array(
                # escape any non-alphanum chars in path for regexp
                # TODO use a better list of regex chars to escape rather than all non alphanumeric
                # capture args from end
                'pathre'    => '^' . preg_replace('/(\W)/','\\\$1',$controller_path) . '\/?(.+)?$',
                'object'    => $object,
                'method'    => $action->name,
                'namespace' => $namespace_path,
                'args'      => (isset($object->$defvar) &&
                                isset($object->{$defvar}['args']) &&
                                is_int($object->{$defvar}['args']) ) ? $object->{$defvar}['args'] : false,
                # the number of '/' chars in the path so far is a measure of it's specifity
                'specifity' => (1 / count(explode('/', $controller_path))) + $fallback_specifity_offset,
            )
        );
        error_log("Registered path $controller_path to ". get_class($object) ."\\$action->name\()");
    }

    private function register_special_action($saref, $object, $namespace_path, $action){
        if (array_search($action->name, array('fallback', 'before', 'after', 'auto')) === false){
            die("Protected method '$action->name' not allowed in contoller class. So there.");
        } elseif ($action->name == 'fallback'){
            # Will behave as lower specifity main action
            $this->register_main_action($object, $namespace_path, $action, true);
        } else {
            $saref['-action'][$action->name] = array(
                'object'    => $object,
                'method'    => $action->name,
                'namespace' => $namespace_path
            );
            error_log("Registered special action '$action->name' in path '$namespace_path' to ". get_class($object) ."\\$action->name\()");
        }
    }

    public function run($pc) {
        $uripath = $pc->req()->path();
        $action = null;

        // Find defined action (low specifity is good)
        foreach ($this->actions as $act){
            if ((!$action || $act['specifity'] < $action['specifity'])
                && preg_match('/'.$act['pathre'].'/', $uripath, $matches)){
                $act['set_args'] = isset($matches[1]) ? split('/',$matches[1]) : array();
                if ($act['args'] === false || $act['args'] == count($act['set_args'])){
                    $action = $act;
                }
            }
        } 

        if ($action){
            # run 'before'-method
            $action['namespace']->run_most_specific( &$this->special_actions, 'before', $pc );
            # run 'auto' methods
            $action['namespace']->run_all_matching( &$this->special_actions, 'auto', $pc );
            # run main controllers
            $action['object']->call_action($action,$pc);
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
