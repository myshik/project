<?php  
class ControllerModuleGlavblog extends Controller {
    protected function index() {
        $this->load->language('module/glavblog');
        $this->data['news'] = $this->language->get('news');
        
        $this->load->model('catalog/glavblog');
        
        
        if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_catalog_limit');
		}
        $this->data['records'] = array();

			$data = array(
				'sort'               => 'p.date_added',                  //Сортировка
				'order'              => 'DESC',                 //группировка
				'start'              => '0',   //начальная страница
				'limit'              => '10'                  //ограничения
			);
                        
                        $record_total = $this->model_catalog_glavblog->getTotalRecords($data);
                        
			$results = $this->model_catalog_glavblog->getRecords($data);
                        
                        foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				} else {
					$image = false;
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_comment_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

				$this->data['records'][] = array(
					'record_id'  => $result['record_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => mb_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 1000) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $result['rating'],
					'date_added'        => $result['date_added'],
					'viewed'        => $result['viewed'],
					'comments'     => (int)$result['comments'],
					'href'        => $this->url->link('record/record', 'blog_id=' . $this->request->get['blog_id'] . '&record_id=' . $result['record_id'])
				);
			}
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/test.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/glavblog.tpl';
        } else {
            $this->template = 'default/template/module/glavblog.tpl';
        }		
        $this->render();
    }
}
?>