<?php
namespace Piste\Util\Path;
/*=head1 Name
Piste\Path\Util\URI

=head1 DESCRIPTION
Provides some extra cleansing to Path object
=head1 DEPENDENCIES
=cut*/
require_once('Piste/Util/Path.php');

Class URI extends \Piste\Util\Path {
    function __construct($path = null){
        if ($path === null){
            $path = $_SERVER["REQUEST_URI"];
        }
        $path = preg_replace("/^\//",'',$path); # no leading slash
        $path = preg_replace("/\?.*/", '', $path); # strip off GET params
        $path = preg_replace("/\.(json|xml|html)$/", '', $path); # strip off response format
        if ( !$path || preg_match("/\/$/", $path) ){
            $path = $path . 'index';
        }
        parent::__construct($path);
    }
}

?>
