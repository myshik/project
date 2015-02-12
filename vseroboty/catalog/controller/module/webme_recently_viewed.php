<?php 
class ControllerModuleWebmeRecentlyViewed extends Controller {
	
	protected $personalRecentlyViewed = array();
	protected $personalRecentlyViewedLimit = "";
	protected $overallRecentlyViewedStatus = "0";
	protected $overallRecentlyViewedLimit = "";
	
	protected function index() {
		$this->language->load('module/webme_recently_viewed');
		
		$this->load->model('catalog/product');
		$this->load->model('catalog/review');
		$this->load->model('tool/seo_url');
		$this->load->model('tool/image');
		
		$this->data['text_empty'] = $this->language->get('text_webme_recently_viewedEmpty');
		$this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');
		
		$this->load->model('module/webme_recently_viewed');
		
		$this->personalRecentlyViewedLimit = $this->config->get('webme_recently_viewed_personal_limit');
		$this->overallRecentlyViewedStatus = $this->config->get('webme_recently_viewed_overall_status');
		$this->overallRecentlyViewedLimit = $this->config->get('webme_recently_viewed_overall_limit');
		
		$this->personalRecentlyViewed = $this->initPersonalRecentlyViewed();
		
		/*
		if (isset($this->session->data['personalRecentlyViewed'])) {
			$this->personalRecentlyViewed = $this->session->data['personalRecentlyViewed'];
		}
		*/
		
		$recentlyViewed = array();
		$this->data['products'] = array();
		
		if ($this->personalRecentlyViewed) {
			$this->data['heading_title'] = $this->language->get('heading_title_personal');
			$recentlyViewed = $this->model_module_webme_recently_viewed->wrv_getPersonalViewedProducts($this->personalRecentlyViewed);
		} else {
			if ($this->overallRecentlyViewedStatus == 1) {
				$this->data['heading_title'] = $this->language->get('heading_title_overall');
				$recentlyViewed = $this->model_module_webme_recently_viewed->wrv_getOverallViewedProducts($this->overallRecentlyViewedLimit);
			}
		}
		
		foreach ($recentlyViewed as $result) {
			if ($result['image']) {
				$image = $result['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			if ($this->config->get('config_review')) {
				$rating = $this->model_catalog_review->getAverageRating($result['product_id']);
			} else {
				$rating = false;
			}
			
			$special = FALSE;
			
			$discount = $this->model_catalog_product->getProductDiscount($result['product_id']);
			
			if ($discount) {
				$price = $this->currency->format($this->tax->calculate($discount, $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				
				$special = $this->model_catalog_product->getProductSpecial($result['product_id']);
				
				if ($special) {
					$special = $this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
				}
			}
			
			$options = $this->model_catalog_product->getProductOptions($result['product_id']);
			
			if ($options) {
				$add = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/product&amp;product_id=' . $result['product_id']);
			} else {
				$add = HTTPS_SERVER . 'index.php?route=checkout/cart&amp;product_id=' . $result['product_id'];
			}
			
			/*
			$this->data['products'][] = array(
				'product_id' =>$result['product_id'],
				'name'       => $result['name'],
				'href'       => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/product&product_id=' . $result['product_id']),
			);
			*/
			
			$this->data['products'][] = array(
				'product_id'    => $result['product_id'],
				'name'    		=> $result['name'],
				'model'   		=> $result['model'],
				'rating'  		=> $rating,
				'stars'   		=> sprintf($this->language->get('text_stars'), $rating),
				'price'   		=> $price,
				'options'   	=> $options,
				'special' 		=> $special,
				'image'   		=> $this->model_tool_image->resize($image, 38, 38),
				'thumb'   		=> $this->model_tool_image->resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
				'href'    		=> $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/product&product_id=' . $result['product_id']),
				'add'    		=> $add
			);
		}
		
		
		if (!$this->config->get('config_customer_price')) {
			$this->data['display_price'] = TRUE;
		} elseif ($this->customer->isLogged()) {
			$this->data['display_price'] = TRUE;
		} else {
			$this->data['display_price'] = FALSE;
		}
		
		$this->id = 'webme_recently_viewed';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/webme_recently_viewed.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/webme_recently_viewed.tpl';
		} else {
			$this->template = 'default/template/module/webme_recently_viewed.tpl';
		}
		
		$this->render();
	}
	
	public function initPersonalRecentlyViewed() {
		$prv_limit = $this->personalRecentlyViewedLimit;
		$prv = array();
		
		if (isset($this->request->cookie['personalRecentlyViewed'])) {
			$this->session->data['personalRecentlyViewed'] = explode("_", $this->request->cookie['personalRecentlyViewed']);
		}
		
		if ($prv_limit > 0) {
			if (isset($this->request->get['product_id'])) {
				$prv[] = $this->request->get['product_id'];
				
				if (isset($this->session->data['personalRecentlyViewed'])) {
					foreach($this->session->data['personalRecentlyViewed'] as $i => $new_prv) {
						if (count($prv) == $prv_limit) {
							break;
						}
						
						if (!in_array($new_prv, $prv)) {
							$prv[] = $new_prv;
						}
					}
				}
			} else {
				if (isset($this->session->data['personalRecentlyViewed'])) {
					foreach($this->session->data['personalRecentlyViewed'] as $i => $new_prv) {
						if (count($prv) == $prv_limit) {
							break;
						}
						
						if (!in_array($new_prv, $prv)) {
							$prv[] = $new_prv;
						}
					}
				}
			}
			
			$this->session->data['personalRecentlyViewed'] = $prv;
			
			//$cookietime = 24*3600;
			//SetCookie("personalRecentlyViewed", "".$prv."", time()+$cookietime, "/", "".$_SERVER["SERVER_NAME"]."");
			//SetCookie("personalRecentlyViewed", "", time()-$cookietime, "/", "".$_SERVER["SERVER_NAME"]."");
			
			$cookie_prv = implode("_", $prv);
			
			setcookie('personalRecentlyViewed', $cookie_prv, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
		}
		
		return $prv;
	}
}
?>