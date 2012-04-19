<?php
namespace Piste\View;
/*=head1 Name
Piste\View\PHP

=head1 DESCRIPTION
PHP view is the basic view for php html output

=head1 DEPENDENCIES
File
=cut*/
require_once('Piste/View.php');

abstract Class PHP extends \Piste\View {

/*=head1 Constructor

=over
=cut*/
    function __construct(){
        # sort out config
        if (!isset($this->config)) {$this->config = array();}
        if ($this->config && !is_array($this->config)){
            throw new Exception("Application config should be an associative array");
        }
        $this->config = array_replace($this->base_config, $this->config);
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

    public function render($pc){
        # set $data for templates
        $data = $pc->data();

        # require page content & store in output buffer
        # By rendering the page innards before the 
        # wrapper, you can set variables in the page
        # which will be used in the rendered wrapper.
        # This is useful
        ob_start();
        require $this->get_page($pc);
        if ($this->config['DEBUG_SERVER']) {
            echo '<pre>';
            print_r($_SERVER);
            echo '</pre>';
        }
        if ($this->config['wrapper']){
            $Pcontent = ob_get_clean();
            $pc->response()->body(require($this->config['wrapper']));
        } else {
            $pc->response()->body(ob_get_clean());
        }
    }    

/*=head2 get_page()
=cut*/
    function get_page($pc){
        $page = $pc->request()->uri_path();
        $page = $page . $this->config['template_suffix'];
        $template = new \File($this->config['template_base'] . $page);
        if (!$template->is_file()){
            $page = $this->config['404'];
        }
        return $this->config['template_base'] . $page;
    }
}

?>
