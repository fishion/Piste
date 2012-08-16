<?php
namespace Piste\Dispatch;
/*=head1 Name
Piste\Dispatch\Action

=head1 DESCRIPTION
Acts as a base class for all Dispatch Controller Actions

=cut*/
abstract class Action {

    abstract public function action_path($object, $action, $defvar);
    abstract public function specifity_offset();

    private $pathre;
    private $object;
    private $method;
    private $namespace_path;
    private $arg_def;
    private $args;
    private $specifity;

    function __construct($object, $namespace_path, $action){
        $defvar = $action->name . '_def';
        $action_path = $this->action_path($object, $action, $defvar);
        
        # is that global or local?
        if (!preg_match('/^\//', $action_path)){
            # local, Make it global
            $action_path = $namespace_path . '/' . $action_path;
        }

        # escape any non-alphanum chars in path for regexp
        # TODO use a better list of regex chars to escape rather than all non alphanumeric
        # capture args from end
        $this->pathre    = '^' . preg_replace('/(\W)/','\\\$1',$action_path) . '\/?(.+)?$';
        $this->object    = $object;
        $this->method    = $action->name;
        $this->namespace_path   = $namespace_path;
        $this->arg_def   = (isset($object->$defvar) &&
                            isset($object->{$defvar}['args']) &&
                            is_int($object->{$defvar}['args']) )
                                ? $object->{$defvar}['args']
                                : false;
        # the number of '/' chars in the path so far is a measure of it's specifity
        $this->specifity = (1 / count(explode('/', $action_path))) + $this->specifity_offset();

        \Logger::debug("Registered path $action_path to ". get_class($object) ."\\$action->name\()");
    }


    # accessors
    public function specifity(){ return $this->specifity; }
    public function namespace_path(){ return $this->namespace_path; }
    public function method(){ return $this->method; }

    # methods
    public function better_match($uripath, $that){
        if ($that && $this->specifity() > $that->specifity()){
            return $that; # even if this matches, that is better
        }
        return $this->match($uripath) ? $this : $that;
    }

    public function match($uripath){
        $match = preg_match('/'.$this->pathre.'/', $uripath, $remain);
        $this->args = isset($remain[1]) ? split('/',$remain[1]) : array();
        if ($match &&
            ( $this->arg_def === false
              || $this->arg_def == count($this->args) )
           ){
            return true;
        }
        return false;
    }

    public function call($pc){
        $this->object->P_call_action($this->method, $this->args, $pc);
    }

}

?>
