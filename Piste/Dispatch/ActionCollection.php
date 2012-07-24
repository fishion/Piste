<?php
namespace Piste\Dispatch;
/*=head1 Name
Piste\Dispatch\ActionCollection

=head1 DESCRIPTION
A collection of actions, methods to choose most suitable

=head1 DEPENDENCIES
=cut*/
require_once('Piste/Dispatch/Controller/Action.php');

Class ActionCollection {

    private $actions = array();
    private $special_actions = array();

    public function register($object, $namespace_path, $action, $special = false){
        array_push(
            $this->actions,
            new Controller\Action($object, $namespace_path, $action, $special)
        );
    }

    public function register_special($object, $namespace_path, $action){
        if (array_search($action->name, array('fallback', 'before', 'after', 'auto')) === false){
            die("Protected method '$action->name' not allowed in contoller class. So there.");
        } elseif ($action->name == 'fallback'){
            # Will behave as lower specifity main action
            $this->register($object, $namespace_path, $action, true);
        } else {
            $namespace_path->reset();
            # Find place in special actions data structure
            $saref = &$this->special_actions;
            while ($part = $namespace_path->walkup()){
                if (!isset($saref[$part])){
                    $saref[$part] = array();
                }
                $saref = &$saref[$part];
            }

            $saref['-action'][$action->name] = array(
                'object'    => $object,
                'namespace_path' => $namespace_path
            );
            error_log("Registered special action '$action->name' in path '$namespace_path' to ". get_class($object) ."\\$action->name\()");
        }
    }

    public function best_match($uripath){
        $action = null;
        foreach ($this->actions as $act){
            $action = $act->better_match($uripath, $action);
        }
        return $action;
    }

    public function run_most_specific($path, $actionname, $pc){
        $path->reset();
        $saref = &$this->special_actions;
        $action = null;
        if (isset($saref['-action'][$actionname])){
            $action = $saref['-action'][$actionname];
        }
        # look for progressively more specific action
        while ($part = $path->walkup()){
            if (!isset($saref[$part])){
                break; # not going to find any more
            }
            $saref = &$saref[$part];
            if (isset($saref['-action'][$actionname])){
                $action = $saref['-action'][$actionname];
            }
        }
        if ($action){
            $action['object']->call_action($actionname,null,$pc);
        }
 
    }
    public function run_all_matching($path, $actionname, $pc){
        $path->reset();
        $saref = &$this->special_actions;
        if (isset($saref['-action'][$actionname])){
            $saref['-action'][$actionname]['object']->call_action($actionname,null,$pc);
        }
        // look for progressively more specific actions
        while ($part = $path->walkup()){
            if (!isset($saref[$part])){
                break;// not going to find any more
            }
            $saref = &$saref[$part];
            if (isset($saref['-action'][$actionname])){
                $saref['-action'][$actionname]['object']->call_action($actionname,null,$pc);
            }
        }
    }
}

?>
