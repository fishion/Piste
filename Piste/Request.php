<?php
namespace Piste;
/*=head1 Name
Piste\Request

=head1 DESCRIPTION

*/
Class Request {

    public function uri_path() {
        $path = $_SERVER["REQUEST_URI"];
        $path = preg_replace("/^\//",'',$path); # no leading slash
        $path = preg_replace("/\?.*/", '', $path); # strip off GET params
        $path = preg_replace("/\.(json|xml|html)$/", '', $path); # strip off response format
        if ( !$path || preg_match("/\/$/", $path) ){
            $path = $path . 'index';
        }
        return $path;
    }

}

?>
