<?php
class ControllerModuleFastorder extends Controller {
	
	public function index() { 
		$this->load->language("module/fastorder");
		
		$this->load->model('setting/setting');

		$this->data["heading_title"] = $this->language->get("heading_title");
		$this->data["email"] = $this->config->get('config_email');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get("config_template") . "/template/module/fastorder.tpl")) {
			$this->template = $this->config->get("config_template") . "/template/module/fastorder.tpl";
		} else {
			$this->template = "default/template/module/fastorder.tpl";
		}
		
		$this->children = array(
			"common/column_left",
			"common/column_right",
			"common/content_top",
			"common/content_bottom",
			"common/footer",
			"common/header"
		);
										
		$this->response->setOutput($this->render());
	}
}
?>