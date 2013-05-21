<?php
namespace Piste\Context;
/*=head1 Name
Piste\Context\KeyValStore

=head1 DESCRIPTION
An abstract base class for Stash and Content objects
accessible through the context object

=head1 DEPENDENCIES

=cut*/

abstract class KeyValStore {
    private $store = array();

    public function getset($params = null, $value = null){
        if ($value !== null & !is_string($params)){
            throw new Exception('Bad parameters to' . __CLASS__ . '::' . __METHOD__ . '() method. Using 2 parameter form, the first value must be a string key');
        }
        if ($value !== null){
            # setting in 2 param form
            $this->store[$params] = $value;
        } elseif (is_string($params)) {
            # getting a value
            return isset($this->store[$params]) ? $this->store[$params] : null;
        } elseif ($params){
            # setting in one param form
            $this->store = array_merge($this->store, $params);
        }
        # getting everything
        return $this->store;
    }

    # TODO no way of accessing this currently!
    protected function clear(){
        $this->store = array();
    }

}

?>
