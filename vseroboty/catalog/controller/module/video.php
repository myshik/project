<?php
class ControllerModuleVideo extends Controller {
	protected function index($setting) {
		$code = $this->config->get('video_module');
		$this->data['code'] = html_entity_decode($code['video_code'], ENT_QUOTES, 'UTF-8');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/video.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/video.tpl';
		} else {
			$this->template = 'default/template/module/video.tpl';
		}

		$this->render();
	}
}