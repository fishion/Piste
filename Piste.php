<?php
/*=head1 NAME
Piste

=head1 DESCRIPTION
Provides a simple dispatch layer and template wrapper mechanism.

=head1 DEPENDENCIES
File
=cut*/
require_once('File.php');
require_once('Piste/Dispatch.php');
require_once('Piste/Context.php');

/*=head1 Synopsis

 require_once('Piste.php');
 $template = new Piste(array(
     'DEBUG_SERVER'      => 0,
     'wrapper'           => 'layout/wrapper.php',
 ));
 $template->render();

=head1 Overview

Class designed to be used as the dispatch layer in an environment where the web server has used rewrite rules to process all php requests to a single endpoint, which might then use this class as described in the Synopsis example.

It allows for the urls to omit the '.php' suffix (usually considered good practice) and will resolve url directory paths to index

Additionally it gives you customised 404 handling, the ability to add standard wrappers to your templates (useful for standard page furnature, menus etc) and can capture requested respone formats (though more work is planned in this area).

=cut*/

abstract class Piste {
    private $application_name = null;
    private $application_lib = null;
    private $dispatch;

/*=head1 Constructor

Your application base class should inherit from this class

=over
=cut*/
    function __construct(){
        $this->application_name = get_class($this);
        $baseclass = new File($this->application_name . '.php');
        $this->application_lib = $baseclass->find_absolute_path();

        error_log("Initialising ". $this->application_name ." application in " . $this->application_lib);

        # Register all installed application MVC classes
        $this->dispatch = new Piste\Dispatch(isset($this->config) ? $this->config : null);
        $this->dispatch->register_all($this->application_lib, $this->application_name);
    }

/*=head2 run()
Runs dispatch methods and responds with the page output
=cut*/
    function run(){
        # TODO: Should this be initialised here or something more persistent?
        $pc = new Piste\Context();
        $this->dispatch->dispatch($pc);

        return;

        if ($this->response_data() && $this->get_response_format() == 'json'){
            header('Content-type: application/json');;
            echo json_encode($this->response_data());
        } else {
        }


    }

/*=head2 get_response_format()
Used by the render() method return a response format. Callable from templates for alternative behaviour if alternatove response formats are allowed.
=cut*/
    function get_response_format(){
        if ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) {
# tODO: check HTTP ACCEPT to to distinguish AJAX from AJAJ
#        if ( preg_match("/application\/json/", $_SERVER['HTTP_ACCEPT']) ||
#             preg_match("/\.json(\?.*)?$/", $_SERVER["REQUEST_URI"]) ){
            return 'json';
        }
        return 'html';
    }

/*=head2 response_data($data)
Public method to Get/Set response data if non-html response is required
=cut*/
    function response_data($data=null){
        if ($data){
            $this->response_data = $data;
        }
        return $this->response_data;
    }
}

/*=head1 TODO

=over
=item template_suffix as a list?
=back

=cut*/
?>
