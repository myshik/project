<?php
#############################################################################
#  LegionStudio.NET
#  info@legionstudio.net
#############################################################################
class ControllerModuleSyoutube  extends Controller {		 

	protected function index() {

		$this->language->load('module/s_youtube');

      	$this->data['heading_title'] = $this->language->get('heading_title');
      	$this->data['youtube_extension'] = $this->language->get('youtube_extension');
		

        $this->data['code']= html_entity_decode($this->config->get('s_youtube_code'), ENT_QUOTES);

		$this->id = 's_youtube';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/s_youtube.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/s_youtube.tpl';
		} else {
			$this->template = 'default/template/module/s_youtube.tpl';
		}
		$this->render();
	}
}
?>