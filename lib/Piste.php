<?php
/*=head1 NAME
Piste

=head1 DESCRIPTION
Provides a simple dispatch layer and template wrapper mechanism.

=head1 DEPENDENCIES
=cut*/
require_once('File.php');
require_once('Logger.php');
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

Additionally it gives you customised 404 handling, the ability to add standard wrappers to your templates and can capture requested respone formats.

=cut*/

abstract class Piste {
    private $pc;
    private $dispatch;

/*=head1 Constructor

Your application base class should inherit from this class

=over
=cut*/
    function __construct(){
        # TODO get log file from config
        \Logger::init('/var/log/php/logger.log');
        $this->pc = new Piste\Context(get_class($this));
        \Logger::debug("Initialising " . $this->pc->env()->app_name() . " application in " . $this->pc->env()->app_lib());

        # TODO do something better with config
        # - get it from regular PHP config files and
        # put attributes into class to you don't have to keep checking
        # isset() on everything. maybe $config->get('path/to/thing/0/whatever')
        # as that allows arbitrary things to be stored and accessed without knowing type?

        # Register all installed application MVC classes
        $this->dispatch = new Piste\Dispatch(isset($this->config) ? $this->config : null);
        $this->dispatch->register_all($this->pc);
    }

/*=head2 run()
Runs dispatch methods and responds with the page output
=cut*/
    public function run(){
        $this->dispatch->dispatch($this->pc);
    }

}

?>
