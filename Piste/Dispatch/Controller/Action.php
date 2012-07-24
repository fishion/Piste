<?php
namespace Piste\Dispatch\Controller;
/*=head1 Name
Piste\Dispatch\Controller\Action

=head1 DESCRIPTION
A regular Controller action

=head1 DEPENDENCIES
=cut*/
require_once('Piste/Dispatch/Controller.php');

Class Action implements \Piste\Dispatch\Controller {
    private $pathre;
    private $object;
    private $method;
    private $namespace_path;
    private $arg_def;
    private $args;
    private $specifity;

    function __construct($object, $namespace_path, $action, $is_fallback = false){
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

        # escape any non-alphanum chars in path for regexp
        # TODO use a better list of regex chars to escape rather than all non alphanumeric
        # capture args from end
        $this->pathre    = '^' . preg_replace('/(\W)/','\\\$1',$controller_path) . '\/?(.+)?$';

        $this->object           = $object;
        $this->method           = $action->name;
        $this->namespace_path   = $namespace_path;
        $this->arg_def   = (isset($object->$defvar) &&
                            isset($object->{$defvar}['args']) &&
                            is_int($object->{$defvar}['args']) )
                                ? $object->{$defvar}['args']
                                : false;
        # the number of '/' chars in the path so far is a measure of it's specifity
        $this->specifity = (1 / count(explode('/', $controller_path))) + $fallback_specifity_offset;

        error_log("Registered path $controller_path to ". get_class($object) ."\\$action->name\()");
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
        $this->object->call_action($this->method, $this->args, $pc);
    }
}

?>
