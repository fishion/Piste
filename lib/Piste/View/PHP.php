<?php
namespace Piste\View;
/*=head1 Name
Piste\View\PHP

=head1 DESCRIPTION
PHP view is the basic view for php html output

=head1 DEPENDENCIES
File
=cut*/
require_once('Logger.php');
require_once('File.php');
require_once('Piste/View.php');

abstract Class PHP extends \Piste\View {

    function __construct($pc){
        parent::__construct($pc);
        # include the include path
        if ($this->config['template_include']){
            $dirpath = $pc->env()->app_base() . $this->config['template_include'];
            $dir = new \File($dirpath);
            if ($dir->is_dir()){
                $pc->env()->add_include_path($dirpath);
            } else {
                throw new \Exception("Include path '$dirpath' not found");
            }
        }
    }

    protected $base_config = array(
/*=item DEBUG_SERVER
Boolean providing a quick and easy way to dump the contents of $_SERVER.
Default 0
=cut*/
        'DEBUG_SERVER'      => 0,
/*=item template_base
Directory relative to application root where you expect to find templates.
Default 'template/base/'.
=cut*/
        'template_base'     => 'template/base/',
/*=item template_include
Directory relative to application root where you want to put included templates.
Default 'template/base/'.
=cut*/
        'template_include'  => 'template/include/',
/*=item wrapper
path to php file (relative to include_path) containing a wrapper. The wrapper should 'echo $Pcontent' at some stage to pull in the main page contents.
Default value is null.
=cut*/
        'wrapper'           => null,
/*=item 404
Path to a 404 template if dispatch file not found
default: null
=cut*/
        '404'               => null,
/*=item template_suffix
Suffix to add to dispatch path to find filename
Default php
=back
=cut*/
        'template_suffix'   => '.php'
    );

    public function render($pc){
        # require page content & store in output buffer
        # By rendering the page innards before the 
        # wrapper, you can set variables in the page
        # which will be used in the rendered wrapper.
        # This is useful
        ob_start();

        # make sure we have a template set
        if (!$pc->template() && $pc->action()){
            \Logger::debug('Setting default template to ' . $pc->action()->default_template());
            $pc->template($pc->action()->default_template());
        }

        # make stash available as global vars in template
        foreach ($pc->response()->stash() as $key => $val){
            $GLOBALS[$key] = $val;
        }
        # make Piste Context object available too. WHy not.
        $GLOBALS['pc'] = $pc;

        try {
            require $this->get_template($pc);
        } catch(\Exception $e){
            throw new \Exception("File Error - couldn't find template: $e<br>");
        }
        if ($this->config['DEBUG_SERVER']) {
            echo '<pre>';
            print_r($_SERVER);
            echo '</pre>';
        }
        if ($this->config['wrapper']){
            $Pcontent = ob_get_clean();
            ob_start();
            require($this->config['wrapper']);
            $pc->res()->body(ob_get_clean());
        } else {
            $pc->res()->body(ob_get_clean());
        }
    }    

    public function render_404($pc){
        if (isset($this->config['404'])){
            $pc->template($this->config['404']);
            $this->render($pc);
        } else {
            parent::render_404($pc);
        }
    }

/*=head2 get_template()
=cut*/
    private function get_template($pc){
        $page = $pc->env()->app_base()
              . $this->config['template_base']
              . $pc->template()
              . $this->config['template_suffix'];

        $template = new \File($page);
        if ($template->is_file()){
            return $page;
        }
        throw new \Exception("Can't find $page");
    }
}

?>
