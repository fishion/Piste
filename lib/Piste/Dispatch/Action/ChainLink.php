<?php
namespace Piste\Dispatch\Action;
/*=head1 Name
Piste\Dispatch\Action\ChainLink

=head1 DESCRIPTION
A chained Controller action

chained => '' or '/' means start of chain
chained => '/foo' means chain from 'foo' controller in Root
chained => '/foo/bar' means chain from 'bar' controller in 'Foo'
                      controller package
chained => 'foo' means chained from 'foo' controller in this package
                  or most specific parent package
chained => 'foo/bar' means chained from 'bar' controller in $thiscontroller::foo controller package. So relaive like

path => '/foo' only has meaning for root chained action and means take
               'foo' from start as action path. Global like.
path => '/' means take no pathpart for this controller, even if it's
            the root chained action
path => 'foo' means take 'blah' as action path. Unless it's the root
              chained action, in which case take $namespace_path/foo.
path => '' means take no pathpart for this controller, or $namespace
           if it's the root chained action

=head1 DEPENDENCIES
=cut*/
require_once('Piste/Dispatch/Action.php');
require_once('Piste/Dispatch/Action/Chained.php');
require_once('Logger.php');

Class ChainLink extends \Piste\Dispatch\Action {
    private $chain = array(); # list of all links back to the start

    public function action_path(){
        if (!isset($this->action_path)){
            $ap = isset($this->def['path'])
                    ? $this->def['path']
                    : $this->method_name;
            if ($this->is_start_of_chain() &&
                $ap == ''){
                # starting out an just using namespace path
                $this->action_path = $this->namespace_path()->no_trailing_slash();
            } elseif ($this->is_start_of_chain() &&
                !preg_match('/^\//', $ap)){
                # starting out and adding $ap
                $this->action_path = $this->namespace_path()->extend($ap);
            }
            elseif ($ap == '/' || $ap == ''){
                # add nothing
                $this->action_path = '';
            }
            elseif (!preg_match('/^\//', $ap)){
                # adding some path - must start with path separator
                $this->action_path = '/' . $ap;
            }
            else {
                # adding some path or starting one with a '/'
                $this->action_path = $ap;
            }
            \Logger::info("Action path for chain link " . $this->namespace_path() .":" . $this->method_name() . " is " .$this->action_path );
        }
        return $this->action_path;
    }

    public function pathre(){
        if (!isset($this->pathre)){
            $this->pathre_base()
                 ->pathre_params();
        }
        return $this->pathre;
    }

    public final function args_match($args){
        $args = preg_replace('/^\//', '', $args);
        $this->args = $args ? split('/',$args) : array();
        return ($this->arg_def() === false ||
                $this->arg_def() == count($this->args) );
    }

    # gets the 'chained' attribute as set in the method definition
    public final function chainedto(){
        return $this->def['chained'];
    }
    public final function chainscope(){
        return preg_replace('/[^\/]*$/', '', $this->chainedto());
    }
    public final function chainmethod(){
        return array_pop(split('/', $this->chainedto()));
    }
    public final function is_start_of_chain(){
        return ($this->chainedto() == '' || $this->chainedto() == '/');
    }
    public final function is_end_of_chain(){
        return (isset($this->def['endchain']) && $this->def['endchain']);
    }


    /* Recursive function to try to find a route to 
    the beginning of a chain */
    public final function attach(&$links, &$actions = null){
        # have we already checked this?
        if ($this->chain === false){
            return $this->chain;
        } elseif (count($this->chain)){
            return $this->chain;
        }
        # start of chains are easy
        if ($this->is_start_of_chain()){
            array_push($this->chain, $this);
            return $this->chain;
        }
        # find next link
        $next_link = null;
        foreach ($links as $link) {
            $next_link = $this->better_parent($link, $next_link);
        }

        # Did we find one? Yay. Follow it back to the start
        if (isset($next_link)
            && $this->chain = $next_link->attach($links)){
            # success - linked all the way to a chain start
            array_push($this->chain, $this);
            if ($this->is_end_of_chain()){
                \Logger::info("Found start of chain for " . $this->namespace_path()->extend($this->method_name()));
                # Add a chained action to actions ref
                array_push($actions, new Chained($this->chain));
            }
        } else {
            # failed - no next_link or chain broke somewhere
            \Logger::warn("Failed to follow chain to start for " . $this->namespace_path()->extend($this->method_name()));
            $this->chain = false;
        }
        return $this->chain;
    }

    private final function better_parent(ChainLink $new, ChainLink $old = null){
        if ($this->chainmethod() != $new->method_name()){
            return $old; # method names always have to match
        }

        if (
            # If is it a globally namespaced chain, must match exactly
            $this->chainscope() &&
            preg_match('/^\//', $this->chainedto()) &&
            $this->chainscope() == $new->namespace_path()){
            \Logger::info("Matched chain parent globally " . $this->chainscope() . " == " . $new->namespace_path());
            return $new;
        } elseif (
            # If is it a locally namespaced chain, must match exactly
            $this->chainscope() &&
            $this->namespace_path()->extend($this->chainscope()) == $new->namespace_path()){
            \Logger::info("Matched chain parent locally " . $this->namespace_path()->extend($this->chainscope()) . " == " . $new->namespace_path());
            return $new;
        } elseif (
            # is this one better than what we already have?
            !$this->chainscope() &&
            (!$old || $new->specifity() < $old->specifity()) &&
            # does namespace fit instide this one?
            preg_match('/'.preg_quote($new->namespace_path(), '/').'/',
                       $this->namespace_path())){
            return $new;
        }
        return $old;
    }
}

?>
