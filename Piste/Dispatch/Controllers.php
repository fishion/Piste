<?php
namespace Piste\Dispatch;
/*=head1 Name
Piste\Dispatch\Controllers

=head1 DESCRIPTION
Singleton object coordinating Contoller Actions

=head1 DEPENDENCIES
=cut*/
require_once('Piste/Path.php');
require_once('Piste/Controller.php');
require_once('Piste/Dispatch/Controller/Action.php');

Class Controllers {

    # This is a singleton object
    private static $singleton;
    private function __construct(){
        # private onstructor on singleton
    }
    # use this method to get singleton object instance
    public static function singleton(){
        if (!isset(self::$singleton)){
            $c = __CLASS__;
            self::$singleton = new $c;
        }
        return self::$singleton;
    }


    private $actions = array();
    private $special_actions = array();

    public function register($ob, $np, $act){
        if ($act->isPublic()){
            $this->register_action($ob, $np, $act);
        }
        elseif ($act->isProtected() && $act->name == 'fallback'){
            $this->register_action($ob, $np, $act, true);
        }
        elseif ($act->isProtected() && array_search($act->name, array('fallback', 'before', 'after', 'auto')) !== false ){
            $this->register_special($ob, $np, $act);
        }
        elseif ($act->isProtected()){
            die("Protected method '$act->name' not allowed in contoller class. So there.");
        }
        else {
            # private method probably. Do nothing
        }
    }


    public function run($pc) {
        $uripath = $pc->req()->path();
        $action = null;

        // Find defined action (low specifity is good)
        $action = $this->best_match($uripath);

        if ($action){
            # run 'before'-method
            $this->run_most_specific($action->namespace_path(), 'before', $pc);
            # run 'auto' methods
            $this->run_all_matching($action->namespace_path(), 'auto', $pc);
            # run main controllers
            $action->call($pc);
            # run 'after'-method
            $this->run_most_specific($action->namespace_path(), 'after', $pc);
            # set default template TODO - Controller probably isn't the right place to do this
            if (!$pc->stash('template')){
                $template = $action->namespace_path() . DIRECTORY_SEPARATOR . $action->method();
                $pc->stash('template', preg_replace('/^\//', '', $template));
            }
        } else {
            $pc->res()->return_404(true);
        }
    }



    private function register_action($object, $namespace_path, $action, $special = false){
        array_push(
            $this->actions,
            new Controller\Action($object, $namespace_path, $action, $special)
        );
    }

    private function register_special($object, $namespace_path, $action){
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
        \Logger::debug("Registered special action '$action->name' in path '$namespace_path' to ". get_class($object) ."\\$action->name\()");
    }

    private function best_match($uripath){
        $action = null;
        foreach ($this->actions as $act){
            $action = $act->better_match($uripath, $action);
        }
        return $action;
    }

    private function run_most_specific($path, $actionname, $pc){
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
            $action['object']->P_call_action($actionname,null,$pc);
        }
 
    }
    private function run_all_matching($path, $actionname, $pc){
        $path->reset();
        $saref = &$this->special_actions;
        if (isset($saref['-action'][$actionname])){
            $saref['-action'][$actionname]['object']->P_call_action($actionname,null,$pc);
        }
        // look for progressively more specific actions
        while ($part = $path->walkup()){
            if (!isset($saref[$part])){
                break;// not going to find any more
            }
            $saref = &$saref[$part];
            if (isset($saref['-action'][$actionname])){
                $saref['-action'][$actionname]['object']->P_call_action($actionname,null,$pc);
            }
        }
    }



}

?>
