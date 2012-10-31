<?php
namespace Piste;
/*=head1 Name
Piste\Cookies

=head1 DESCRIPTION

*/
Class Cookies {

    public function set($name, $value = '', $expire = 0, $path = '/', $domain = null, $secure = false, $httponly = false){
        if ($domain === null){
            $domain = $_SERVER['HTTP_HOST']; #SERVER_NAME?
        }
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    public function get($name){
        if (isset($_COOKIE[$name])){
            return $_COOKIE[$name];
        }
        return null;
    }

    public function delete($name, $expire = 0, $path = '/', $domain = null, $secure = false, $httponly = false){
        if ($domain === null){
            $domain = $_SERVER['HTTP_HOST']; #SERVER_NAME?
        }
        setcookie($name, false, $expire, $path, $domain, $secure, $httponly);
    }
}

?>
