<?php
class ControllerModuleCR2GridEnabler extends Controller {
	private $_name = "cr2gridenabler";
	private $_version = "0.5";

	protected function index() {

         	$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();

         	// failsafe output:
         	$this->data['status'] = 0;
         	$this->data['mode'] = 0;
         	$this->data['layouts'] = array();
         	// end failsafe
         	$this->data['status'] = $this->config->get($this->_name . '_status');
         	$this->data['mode'] = $this->config->get($this->_name . '_mode');
         	//$this->data['layouts'] = $this->config->get($this->_name . '_layouts');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/cr2gridenabler.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/cr2gridenabler.tpl';
		} else {
			$this->template = 'default/template/module/cr2gridenabler.tpl';
		}

		$this->render();
  	}
}
?>