<?php
namespace Piste\Util;
/*=head1 Name
Piste\Util\Cookies

=head1 DESCRIPTION

*/
Class Cookies {

    public function set(){
        $args = func_get_args();
        if (!isset($args[0])){
            throw new \Exception("Gonna need to give that cookie a name.");
        }
        $args[1] = isset($args[1]) ? $args[1] : false;
        $args[2] = isset($args[2]) ? $args[2] : 0;
        $args[3] = isset($args[3]) ? $args[3] : '/';
        call_user_func_array('setcookie', $args);
    }

    public function get($name){
        if (isset($_COOKIE[$name])){
            return $_COOKIE[$name];
        }
        return null;
    }

    public function delete(){
        $args = func_get_args();
        array_splice($args, 1, 0, false);
        $args[2] = isset($args[2]) ? $args[2] : 0;
        $args[3] = isset($args[3]) ? $args[3] : '/';
        call_user_func_array('setcookie', $args);
    }
}

?>
