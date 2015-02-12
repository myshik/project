<?php  
class ControllerModuletestimonial extends Controller {
	protected function index($setting) {
		$this->language->load('module/testimonial');

		$this->data['testimonial_title'] = html_entity_decode($setting['testimonial_title'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');

      	$this->data['heading_title'] = $this->language->get('heading_title');
      	$this->data['text_more'] = $this->language->get('text_more');
		$this->data['isi_testimonial'] = $this->language->get('isi_testimonial');
		
		$this->load->model('catalog/testimonial');
		
		$this->data['testimonials'] = array();
		
		$this->data['total'] = $this->model_catalog_testimonial->getTotalTestimonials();
		$results = $this->model_catalog_testimonial->getTestimonials(0, $setting['testimonial_limit'] );
		
		foreach ($results as $result) {
				$theText = $result['description'];

				//$theText = str_replace("\n","<br />",$result['description']);
				//$theText = str_replace("&lt;b&gt;","<b>",$theText);
				//$theText = str_replace("&lt;/b&gt;","</b>",$theText);
				//$theText = str_replace("&lt;i&gt;","<i>",$theText);
				//$theText = str_replace("&lt;/i&gt;","</i>",$theText);
			
			$this->data['testimonials'][] = array(
				'id'			=> $result['testimonial_id'],											  
				'title'			=> '<b>'.$result['name'] . '</b> ' . $result['date_added'],
				'description'	=> html_entity_decode($result['description'])
			);
		}
		
		$this->data['more'] = $this->url->link('product/testimonial', 'testimonial_id='); 
		$this->data['isitesti'] = $this->url->link('product/isitestimonial');


		$this->id = 'testimonial';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/testimonial.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/testimonial.tpl';
		} else {
			$this->template = 'default/template/module/testimonial.tpl';
		}
		
		$this->render();
	}
}
?>