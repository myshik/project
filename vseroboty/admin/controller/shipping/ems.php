<?php
class ControllerShippingEms extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('shipping/ems');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('ems', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/shipping');
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_max_weight'] = $this->language->get('entry_max_weight');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home',
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/shipping',
       		'text'      => $this->language->get('text_shipping'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=shipping/ems',
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=shipping/ems';
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/shipping';
	
		if (isset($this->request->post['ems_status'])) {
			$this->data['ems_status'] = $this->request->post['ems_status'];
		} else {
			$this->data['ems_status'] = $this->config->get('ems_status');
		}
		
		if (isset($this->request->post['ems_max_weight'])) {
			 $this->data['ems_max_weight'] = $this->request->post['ems_max_weight'];
		} else {
			$url = 'http://emspost.ru/api/rest/?method=ems.get.max.weight';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch); 
			$response_array = json_decode($response, TRUE);
			curl_close($ch);
			$this->data['ems_max_weight'] = $response_array['rsp']['max_weight'];
		}
		
		if (isset($this->request->post['ems_sort_order'])) {
			$this->data['ems_sort_order'] = $this->request->post['ems_sort_order'];
		} else {
			$this->data['ems_sort_order'] = $this->config->get('ems_sort_order');
		}				
		
		$this->load->model('localisation/geo_zone');
		
		$this->template = 'shipping/ems.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/ems')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>