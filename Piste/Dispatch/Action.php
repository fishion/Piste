<?php
namespace Piste\Dispatch;
/*=head1 Name
Piste\Dispatch\Action

=head1 DESCRIPTION
Acts as a base class for all Dispatch Controller Actions
=head1 DEPENDENCIES

=cut*/
require_once('Logger.php');

abstract class Action {

    abstract public function action_path($object, $namespace_path, $method, $def);
    abstract public function arg_def($object, $def);
    protected $specifity_offset = 0;
    protected $capture_args = true;

    private $pathre;
    private $object;
    private $method;
    private $namespace_path;
    private $arg_def;
    private $args;
    private $specifity;

    function __construct($object, $namespace_path, $method, $def){
        $action_path = $this->action_path($object, $namespace_path, $method, $def);

        $this->object         = $object;
        $this->method         = $method->name;
        $this->namespace_path = $namespace_path;
        $this->arg_def        = $this->arg_def($object, $def);
        $this->specifity      = (1 / count(explode('/', $action_path))) + $this->specifity_offset;

        # escape any non-alphanum chars in path for regexp
        # TODO use a better list of regex chars to escape rather than all non alphanumeric
        $this->pathre =
            '^' .
            preg_replace('/(\W)/','\\\$1',$action_path) .
            ($this->capture_args ? '\/?(.+)?$' : '')
        ;

        \Logger::debug("Registered path $action_path to ". get_class($object) ."\\$method->name\()");
    }


    # accessors
    public function specifity(){return $this->specifity;}
    public function namespace_path(){return $this->namespace_path;}
    public function method(){ return $this->method;}

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
