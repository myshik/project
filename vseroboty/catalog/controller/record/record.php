<?php
class ControllerRecordRecord extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('record/record');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		$this->load->model('catalog/blog');

		if (isset($this->request->get['blog_id'])) {
			$path = '';

			foreach (explode('_', $this->request->get['blog_id']) as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}

				$blog_info = $this->model_catalog_blog->getBlog($path_id);

				if ($blog_info) {
					$this->data['breadcrumbs'][] = array(
						'text'      => $blog_info['name'],
						'href'      => $this->url->link('record/blog', 'blog_id=' . $path),
						'separator' => $this->language->get('text_separator')
					);
				}
			}
		}
		else
		{         $path = '';
		}
/*
		$this->load->model('catalog/manufacturer');

		if (isset($this->request->get['manufacturer_id'])) {
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_brand'),
				'href'      => $this->url->link('record/manufacturer'),
				'separator' => $this->language->get('text_separator')
			);

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if ($manufacturer_info) {
				$this->data['breadcrumbs'][] = array(
					'text'	    => $manufacturer_info['name'],
					'href'	    => $this->url->link('record/manufacturer/record', 'manufacturer_id=' . $this->request->get['manufacturer_id']),
					'separator' => $this->language->get('text_separator')
				);
			}
		}
         */
		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_tag'])) {
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			}

			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}

			if (isset($this->request->get['filter_blog_id'])) {
				$url .= '&filter_blog_id=' . $this->request->get['filter_blog_id'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_search'),
				'href'      => $this->url->link('record/search', $url),
				'separator' => $this->language->get('text_separator')
			);
		}

		if (isset($this->request->get['record_id'])) {
			$record_id = $this->request->get['record_id'];
		} else {
			$record_id = 0;
		}

		$this->load->model('catalog/record');

		$record_info = $this->model_catalog_record->getRecord($record_id);

		$this->data['record_info'] = $record_info;

		if ($record_info) {
			$url = '';

			if (isset($this->request->get['blog_id'])) {
				$url .= '&blog_id=' . $this->request->get['blog_id'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			}

			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}

			if (isset($this->request->get['filter_blog_id'])) {
				$url .= '&filter_blog_id=' . $this->request->get['filter_blog_id'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'      => $record_info['name'],
				'href'      => $this->url->link('record/record', $url . '&record_id=' . $this->request->get['record_id']),
				'separator' => $this->language->get('text_separator')
			);

			$this->document->setTitle($record_info['name']);
			$this->document->setDescription($record_info['meta_description']);
			$this->document->setKeywords($record_info['meta_keyword']);
			$this->document->addLink($this->url->link('record/record', 'record_id=' . $this->request->get['record_id']), 'canonical');

			$this->data['heading_title'] = $record_info['name'];


         	$advertising_link="http://borinfo.com.ua/opencart/";

			$opts = array('http'=>array('method'=>"GET",'header'=>"Content-Type: text/xml; charset=utf-8"));
			$context = stream_context_create($opts);

			require_once 'Exceptionizer.php';

			//error_reporting(E_ALL);

			 $exceptionizer = new PHP_Exceptionizer(E_ALL);
			// И оставьте эту переменную, чтобы она не удалялась до окончания
			// скрипта. Удаление переменной вызовет отключение PHP_Exceptionizer.



			try {
			    $advertising_content=false;
			    $advertising_content=file_get_contents($advertising_link, false, $context);

			} catch (E_WARNING $e) {
			    //echo "Warning or better raised: " . $e->getMessage();
			}

             if ($advertising_content)
             {
				$advertising_pattern='|<div class=\"advertising\">(.*?)<\/div>|is';
		    	preg_match_all($advertising_pattern, $advertising_content , $advertising_data, PREG_OFFSET_CAPTURE);
            	$this->data['text_advertising']=$advertising_data[0][0][0];
             }
             else
             {
              $this->data['text_advertising']='';
             }



			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['text_model'] = $this->language->get('text_model');
			$this->data['text_reward'] = $this->language->get('text_reward');
			$this->data['text_points'] = $this->language->get('text_points');
			$this->data['text_discount'] = $this->language->get('text_discount');
			$this->data['text_stock'] = $this->language->get('text_stock');
			$this->data['text_price'] = $this->language->get('text_price');
			$this->data['text_tax'] = $this->language->get('text_tax');
			$this->data['text_discount'] = $this->language->get('text_discount');
			$this->data['text_option'] = $this->language->get('text_option');
			$this->data['text_qty'] = $this->language->get('text_qty');
			$this->data['text_minimum'] = sprintf($this->language->get('text_minimum'), $record_info['minimum']);
			$this->data['text_or'] = $this->language->get('text_or');
			$this->data['text_write'] = $this->language->get('text_write');
			$this->data['text_note'] = $this->language->get('text_note');
			$this->data['text_share'] = $this->language->get('text_share');
			$this->data['text_wait'] = $this->language->get('text_wait');
			$this->data['text_tags'] = $this->language->get('text_tags');

			$this->data['text_viewed'] = $this->language->get('text_viewed');

			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_comment'] = $this->language->get('entry_comment');
			$this->data['entry_rating'] = $this->language->get('entry_rating');
			$this->data['entry_good'] = $this->language->get('entry_good');
			$this->data['entry_bad'] = $this->language->get('entry_bad');
			$this->data['entry_captcha'] = $this->language->get('entry_captcha');

			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_compare'] = $this->language->get('button_compare');
			$this->data['button_upload'] = $this->language->get('button_upload');
			$this->data['button_continue'] = $this->language->get('button_continue');

			$this->load->model('catalog/comment');

			$this->data['tab_description'] = $this->language->get('tab_description');
			$this->data['tab_attribute'] = $this->language->get('tab_attribute');
			$this->data['tab_advertising'] = $this->language->get('tab_advertising');

            $this->data['comment_count']=$this->model_catalog_comment->getTotalCommentsByRecordId($this->request->get['record_id']);
			$this->data['tab_comment'] = $this->language->get('tab_comment');

			$this->data['tab_images'] =$this->language->get('tab_images');
			$this->data['tab_related'] = $this->language->get('tab_related');

			$this->data['record_id'] = $this->request->get['record_id'];
			$this->data['manufacturer'] = $record_info['manufacturer'];
			$this->data['manufacturers'] = $this->url->link('record/manufacturer/record', 'manufacturer_id=' . $record_info['manufacturer_id']);
			$this->data['model'] = $record_info['model'];
			$this->data['reward'] = $record_info['reward'];
			$this->data['points'] = $record_info['points'];

			if ($record_info['quantity'] <= 0) {
				$this->data['stock'] = $record_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$this->data['stock'] = $record_info['quantity'];
			} else {
				$this->data['stock'] = $this->language->get('text_instock');
			}

			$this->load->model('tool/image');

			if ($record_info['image']) {
				$this->data['popup'] = $this->model_tool_image->resize($record_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			} else {
				$this->data['popup'] = '';
			}

			if ($record_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($record_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			} else {
				$this->data['thumb'] = '';
			}

			$this->data['images'] = array();

			$results = $this->model_catalog_record->getRecordImages($this->request->get['record_id']);

			foreach ($results as $result) {
				$this->data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
				);
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$this->data['price'] = $this->currency->format($this->tax->calculate($record_info['price'], $record_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['price'] = false;
			}

			if ((float)$record_info['special']) {
				$this->data['special'] = $this->currency->format($this->tax->calculate($record_info['special'], $record_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['special'] = false;
			}

			if ($this->config->get('config_tax')) {
				$this->data['tax'] = $this->currency->format((float)$record_info['special'] ? $record_info['special'] : $record_info['price']);
			} else {
				$this->data['tax'] = false;
			}

			/*
			$discounts = $this->model_catalog_record->getRecordDiscounts($this->request->get['record_id']);

			$this->data['discounts'] = array();

			foreach ($discounts as $discount) {
				$this->data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $record_info['tax_class_id'], $this->config->get('config_tax')))
				);
			}
            */
			$this->data['options'] = array();

			foreach ($this->model_catalog_record->getRecordOptions($this->request->get['record_id']) as $option) {
				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
					$option_value_data = array();

					foreach ($option['option_value'] as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
							$option_value_data[] = array(
								'record_option_value_id' => $option_value['record_option_value_id'],
								'option_value_id'         => $option_value['option_value_id'],
								'name'                    => $option_value['name'],
								'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
								'price'                   => (float)$option_value['price'] ? $this->currency->format($this->tax->calculate($option_value['price'], $record_info['tax_class_id'], $this->config->get('config_tax'))) : false,
								'price_prefix'            => $option_value['price_prefix']
							);
						}
					}

					$this->data['options'][] = array(
						'record_option_id' => $option['record_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option_value_data,
						'required'          => $option['required']
					);
				} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$this->data['options'][] = array(
						'record_option_id' => $option['record_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option['option_value'],
						'required'          => $option['required']
					);
				}
			}

			if ($record_info['minimum']) {
				$this->data['minimum'] = $record_info['minimum'];
			} else {
				$this->data['minimum'] = 1;
			}



			$this->data['text_comments'] = sprintf($this->language->get('text_comments'), (int)$record_info['comments']);
			$this->data['comments'] =  (int)$record_info['comments'];
			$this->data['comment_status'] = $record_info['comment_status'];

			if ($this->customer->isLogged())
			{
			$this->data['text_login'] = $this->customer->getFirstName();
			$this->data['captcha_status']=false;
			}
			else
			{			  $this->data['text_login'] = $this->language->get('text_anonymus');
			  $this->data['captcha_status']=true;
			}

			$this->data['viewed'] = $record_info['viewed'];
            $this->data['date_added'] = $record_info['date_added'];


			$this->data['rating'] = (int)$record_info['rating'];
			$this->data['description'] = html_entity_decode($record_info['description'], ENT_QUOTES, 'UTF-8');
			$this->data['attribute_groups'] = $this->model_catalog_record->getRecordAttributes($this->request->get['record_id']);

			$this->data['records'] = array();

			$results = $this->model_catalog_record->getRecordRelated($this->request->get['record_id']);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
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


				if ($result['comment_status']) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}



				$this->data['records'][] = array(
					'record_id' =>  $result['record_id'],
					'thumb'   	 => $image,
					'name'    	 => $result['name'],
					'viewed'   	 => $result['viewed'],
					'rating'     => $rating,
					'comment_status' =>$result['comment_status'],
					'comments'    => sprintf($this->language->get('text_comments'), (int)$result['comments']),
					'href'    	 => $this->url->link('record/record', 'blog_id=' . $path.'&record_id=' . $result['record_id']),
				);
			}

			$this->data['tags'] = array();

			$results = $this->model_catalog_record->getRecordTags($this->request->get['record_id']);

			foreach ($results as $result) {
				$this->data['tags'][] = array(
					'tag'  => $result['tag'],
					'href' => $this->url->link('record/search', 'filter_tag=' . $result['tag'])
				);
			}

			$this->model_catalog_record->updateViewed($this->request->get['record_id']);

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/record/record.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/record/record.tpl';
			} else {
				$this->template = 'default/template/record/record.tpl';
			}

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);

			$this->response->setOutput($this->render());
		} else {
			$url = '';

			if (isset($this->request->get['blog_id'])) {
				$url .= '&blog_id=' . $this->request->get['blog_id'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			}

			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}

			if (isset($this->request->get['filter_blog_id'])) {
				$url .= '&filter_blog_id=' . $this->request->get['filter_blog_id'];
			}

      		$this->data['breadcrumbs'][] = array(
        		'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('record/record', $url . '&record_id=' . $record_id),
        		'separator' => $this->language->get('text_separator')
      		);

      		$this->document->setTitle($this->language->get('text_error'));

      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);

			$this->response->setOutput($this->render());
    	}
  	}

	public function comment() {
    	$this->language->load('record/record');

		$this->load->model('catalog/comment');

		$this->data['text_no_comments'] = $this->language->get('text_no_comments');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$this->data['comments'] = array();

		$comment_total = $this->model_catalog_comment->getTotalCommentsByRecordId($this->request->get['record_id']);

		$results = $this->model_catalog_comment->getCommentsByRecordId($this->request->get['record_id'], ($page - 1) * 5, 5);

		foreach ($results as $result) {
        	$this->data['comments'][] = array(
        		'author'     => $result['author'],
				'text'       => strip_tags($result['text']),
				'rating'     => (int)$result['rating'],
        		'comments'    => sprintf($this->language->get('text_comments'), (int)$comment_total),
        		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}

		$pagination = new Pagination();
		$pagination->total = $comment_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('record/record/comment', 'record_id=' . $this->request->get['record_id'] . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/record/comment.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/record/comment.tpl';
		} else {
			$this->template = 'default/template/record/comment.tpl';
		}

		$this->response->setOutput($this->render());
	}

	public function write() {
		$json = array();

		$this->language->load('record/record');

		$this->load->model('catalog/record');

		if (isset($this->request->get['record_id'])) {
			$record_id = $this->request->get['record_id'];
		} else {
			$record_id = 0;
		}

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

		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
			$captcha_status=false;
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
			$captcha_status=true;
		}


         $record_blog = $this->model_catalog_record->getRecordCategories($record_id);

 		if (isset($record_blog) && count($record_blog)>0 )
		{
			foreach ($record_blog as $k => $blog_id)
			{

			$data = array(
				'filter_blog_id' 	 => $blog_id,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

		 $cache = md5(http_build_query($data));

         $key='record.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache;

         $this->cache->delete($key);


		}


		}












		$record_info = $this->model_catalog_record->getRecord($record_id);

        $this->data['comment_status'] = $record_info['comment_status'];
        $this->data['comment_status_reg'] = $record_info['comment_status_reg'];

        $this->request->post['status']= $record_info['comment_status_now'];

		$this->load->model('catalog/comment');





		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
			$json['error'] = $this->language->get('error_name');
		}

		if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
			$json['error'] = $this->language->get('error_text');
		}

		if (!$this->request->post['rating']) {
			$json['error'] = $this->language->get('error_rating');
		}

		if ( !isset($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
            if ($captcha_status)
            {
			 $json['error'] = $this->language->get('error_captcha');
			}
		}


		if ($record_info['comment_status_reg'] && !$this->customer->isLogged()) {
			$json['error'] = $this->language->get('error_reg');
		}

		if ($this->customer->isLogged()) {
			$json['login'] = $this->customer->getFirstName();
		}
		else
		{			$json['login'] = $this->language->get('text_anonymus');
		}



		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !isset($json['error'])) {

			$this->model_catalog_comment->addComment($this->request->get['record_id'], $this->request->post);

            $this->data['comment_count']=$this->model_catalog_comment->getTotalCommentsByRecordId($record_id);
			$json['comment_count']=$this->data['comment_count'];


			if ($record_info['comment_status_now'])
			{			  $json['success'] = $this->language->get('text_success_now');
			}
			else
			{
			  $json['success'] = $this->language->get('text_success');
			}
		}




		$this->response->setOutput(json_encode($json));
	}

	public function captcha()
	{

		$this->load->library('captcha');

		if ($this->customer->isLogged()) {

		}
		else
		{

		$this->data['captcha_status']=true;

		$captcha = new Captcha();

		$this->session->data['captcha'] = $captcha->getCode();

		$captcha->showImage();

		}

	}

	public function upload() {
		$this->language->load('record/record');

		$json = array();

		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

			if ((strlen($filename) < 3) || (strlen($filename) > 128)) {
        		$json['error'] = $this->language->get('error_filename');
	  		}

			$allowed = array();

			$filetypes = explode(',', $this->config->get('config_upload_allowed'));

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
       		}

			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !isset($json['error'])) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$file = basename($filename) . '.' . md5(rand());

				// Hide the uploaded file name sop people can not link to it directly.
				$this->load->library('encryption');

				$encryption = new Encryption($this->config->get('config_encryption'));

				$json['file'] = $encryption->encrypt($file);

				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
			}

			$json['success'] = $this->language->get('text_upload');
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>