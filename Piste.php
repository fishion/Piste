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

/*=head1 Synopsis

 require_once('Piste.php');
 $template = new Piste(array(
     'DEBUG_SERVER'      => 0,
     'wrapper'           => 'layout/wrapper.php',
 ));
 $template->render();

=head1 Overview

Class designed to be used as the dispatch layer in an environment where the web server has used rewrite rules to process all php requests to a single endpoint, which might then use this class as described in the Synopsis example.

It allows for the urls to omit the '.php' suffix (usually considered good practice) and can resolve url directory paths to any arbitrary file within that directory (index.php by default)

Additionally it gives you customised 404 handling, the ability to add standard wrappers to your templates (useful for standard page furnature, menus etc) and can capture requested respone formats (though more work is planned in this area).

=cut*/

class Piste {
    private $application_name = null;
    private $response_data = null;
    private $is_initialised = false;
    private $file_ob;
    private $dispatch;

/*=head1 Constructor

 new Piste(<array()>)

The constructor looks for config defined in application base class and sets defaults for others. The config can defined the following optional keys
=over
=cut*/
    function __construct(){
        # sort out config
        if (!$this->config) {$this->config = array();}
        if ($this->config && !is_array($this->config)){ throw new Exception("Application config not an array"); }
        $this->config = array_replace($this->base_config, $this->config);

        # add other useful stuff to object config
        $this->application_name = get_class($this);
        $this->file_ob = new File($this->application_name . '.php');
        $this->config['application_lib'] = $this->file_ob->find_absolute_path();
    }

    private $base_config = array(
/*=item DEBUG_SERVER
Boolean providing a quick and easy way to dump the contents of $_SERVER.
Default 0
=cut*/
        'DEBUG_SERVER'      => 0,
/*=item template_base
Directory within the DOCUMENT_ROOT where you expect to find templates.
Default 'page/'.
=cut*/
        'template_base'     => 'page/',
/*=item wrapper
path to php file (relative to include_path) containing a wrapper. The wrapper should 'echo $MM_content' at some stage to pull in the main page contents.
Default value is null.
=cut*/
        'wrapper'           => null,
/*=item default_template
If trying to dispatch a directory path, the name of the file to try to provide within the directory.
Default index
=cut*/
        'default_template'  => 'index',
/*=item 404
Path to a 404 template if dispatch file not found
default '404.php'
=cut*/
        '404'               => '404.php',
/*=item template_suffix
Suffix to add to dispatch path to find filename
Default php
=back
=cut*/
        'template_suffix'   => '.php'
    );

/*=head1 Object Methods

=head2 init()
Discovers and registers defined Models, Controllers, Views 
=cut*/
    function init(){
        # Just initialise once.
        if ($this->is_initialised) {return;}
        error_log("Initialising application in " . $this->config['application_lib']);

        # require all application packages
        $this->file_ob->filename($this->config['application_lib']);
        $required = $this->file_ob->require_once_all_files('php');
        error_log("Required files ".join(', ', $required));

        # Register all installed application controller class
        $this->dispatch = new Piste\Dispatch();
        $this->dispatch->register_all($this->application_name);

        # OK, we're ready to rock!
        $this->is_initialised = true;
    }

/*=head2 run()
Runs dispatch methods and responds with the page output
=cut*/
    function run(){
        $this->init();

        # require page content and store in output buffer
        ob_start();
        require $this->get_page();
        if ($this->config['DEBUG_SERVER']) {
            echo '<pre>';
            #Var_Dump($_SERVER);
            print_r($_SERVER);
            echo '</pre>';
        }
        # pull output buffer into variable
        $MM_content = ob_get_clean();

        if ($this->response_data() && $this->get_response_format() == 'json'){
            header('Content-type: application/json');;
            echo json_encode($this->response_data());
        } elseif ($this->config['wrapper']){
            require($this->config['wrapper']);
        } else {
            echo $MM_content;
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

/*=head2 get_page()
The main dispatch method used by render() method.
You probably don't need to call this directly, 
=cut*/
    function get_page(){
        $page = $_SERVER["REQUEST_URI"];
        $page = preg_replace("/\?.*/", '', $page); # strip off GET params
        $page = preg_replace("/\.(json|xml|html)$/", '', $page); # strip off response format
        if ( !$page || preg_match("/\/$/", $page) ){
            $page = $page . $this->config['default_template'];
        }
        $page = $page . $this->config['template_suffix'];
        $this->file_ob->filename($this->config['template_base'] . $page);
        if (!$this->file_ob->is_file()){
            $page = $this->config['404'];
        }
        return $this->config['template_base'] . $page;
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
