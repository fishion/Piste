<?php
namespace Piste\Dispatch\Action;
/*=head1 Name
Piste\Dispatch\Action\ChainLink

=head1 DESCRIPTION
A chained Controller action

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
            # Only start of chain actions can be local/global
            if ($this->is_start_of_chain() &&
                !preg_match('/^\//', $ap)){
                # local, Make it global
                $ap = $this->namespace_path . $ap;
            }
            elseif (!$this->is_start_of_chain()) {
                # don't want a leading /
                $ap = preg_replace('/^\//','',$ap);
            }
            $this->action_path = $ap;
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

    public function specifity(){
        if (!isset($this->specifity)){
            # the specifity of the chained action is determined
            # only by the length of the namespace
            $this->specifity = (1 / count(explode('/', $this->namespace_path)));
        }
        return $this->specifity;
    }

    public function chained(){
        return $this->def['chained'];
    }

    public function is_start_of_chain(){
        return ($this->chained() == '' || $this->chained() == '/');
    }

    public function is_end_of_chain(){
        return (isset($this->def['endchain']) && $this->def['endchain']);
    }



/* Recursive function to try to find a route to 
   the beginning of a chain */
    public function attach(&$links, &$actions = null){
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
        foreach ($links as $link) {
            if ($this->chained() == $link->method_name() &&
                # TODO - not sure we can just match method name accross all links
                #      - they may be in completely the wrong namespace
                #      - or even in a sub-namespace of the previous link
                # match $self->chained() and $link->method_name
                # may be more than one - find most specific
                (!isset($next_link) || $link->specifity() < $next_link->specifity() )){
                $next_link = $link;
            }
        }

        # Did we find one? Yay. Follow it back to the start
        if (isset($next_link)
            && $this->chain = $next_link->attach($links)){
            # success - linked all the way to a chain start
            array_push($this->chain, $this);
            if ($this->is_end_of_chain()){
                \Logger::debug("Found start of chain!");
                # Add a chained action to actions ref
                array_push($actions, new Chained($this->chain));
            }
        } else {
            # failed - no next_link or chain broke somewhere
            \Logger::warn("Failed to follow chain to start");
            $this->chain = false;
        }
        return $this->chain;
    }
}

?>
