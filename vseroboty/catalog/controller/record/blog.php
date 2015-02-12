<?php
class ControllerRecordBlog extends Controller {
	public function index() {
		$this->language->load('record/blog');

		$this->load->model('catalog/blog');

		$this->load->model('catalog/record');

		$this->load->model('tool/image');

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

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
       		'separator' => false
   		);

		if (isset($this->request->get['blog_id'])) {
			$path = '';

			$parts = explode('_', (string)$this->request->get['blog_id']);

			foreach ($parts as $path_id) {
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

			$blog_id = array_pop($parts);
		} else {
			$blog_id = 0;
		}

		$blog_info = $this->model_catalog_blog->getBlog($blog_id);

		if ($blog_info) {
	  		$this->document->setTitle($blog_info['name']);
			$this->document->setDescription($blog_info['meta_description']);
			$this->document->setKeywords($blog_info['meta_keyword']);

			$this->data['heading_title'] = $blog_info['name'];

			$this->data['text_refine'] = $this->language->get('text_refine');
			$this->data['text_empty'] = $this->language->get('text_empty');
			$this->data['text_quantity'] = $this->language->get('text_quantity');
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['text_model'] = $this->language->get('text_model');
			$this->data['text_price'] = $this->language->get('text_price');
			$this->data['text_tax'] = $this->language->get('text_tax');
			$this->data['text_points'] = $this->language->get('text_points');
			$this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			$this->data['text_display'] = $this->language->get('text_display');
			$this->data['text_list'] = $this->language->get('text_list');
			$this->data['text_grid'] = $this->language->get('text_grid');
			$this->data['text_sort'] = $this->language->get('text_sort');
			$this->data['text_limit'] = $this->language->get('text_limit');
			$this->data['text_comments'] = $this->language->get('text_comments');
			$this->data['text_viewed'] = $this->language->get('text_viewed');

			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_compare'] = $this->language->get('button_compare');
			$this->data['button_continue'] = $this->language->get('button_continue');

			if ($blog_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($blog_info['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
			} else {
				$this->data['thumb'] = '';
			}

					if (!function_exists("truncate_words"))
                    {
                        function truncate_words($text, $limit=200)
                                                {
                                                        $text=mb_substr($text,0,$limit);
                                                        if(mb_substr($text,mb_strlen($text)-1,1) && mb_strlen($text)==$limit)
                                                        {
                                                                $textret=mb_substr($text,0,mb_strlen($text)-mb_strlen(strrchr($text,' ')));
                                                                if(!empty($textret))
                                                                {
                                                                        return $textret;
                                                                }
                                                        }
                                                        return $text;
                                                }

                    }
            if ($blog_info['description'])
             $this->data['description'] =mb_substr(strip_tags(html_entity_decode($blog_info['description'], ENT_QUOTES, 'UTF-8')), 0, 1000) . '..';
             else
             $this->data['description']=false;

//			$this->data['description'] = html_entity_decode($blog_info['description'], ENT_QUOTES, 'UTF-8');
			$this->data['compare'] = $this->url->link('record/compare');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
            $this->data['categories'] = array();

			$results = $this->model_catalog_blog->getBlogies($blog_id);

			foreach ($results as $result) {
				$data = array(
					'filter_blog_id'  => $result['blog_id'],
					'filter_sub_blog' => true
				);

				$record_total = $this->model_catalog_record->getTotalRecords($data);

				$this->data['categories'][] = array(
					'name'  => $result['name'] . ' (' . $record_total . ')',
					'href'  => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '_' . $result['blog_id'] . $url)
				);
			}

			$this->data['records'] = array();

			$data = array(
				'filter_blog_id' => $blog_id,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

			$record_total = $this->model_catalog_record->getTotalRecords($data);

			$results = $this->model_catalog_record->getRecords($data);

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

			$url = '';

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['sorts'] = array();

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] .'&sort=p.sort_order&order=ASC' . $url)
			);


			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_date_added_desc'),
				'value' => 'p.date_added-DESC',
				'href'  => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=p.date_added&order=DESC' . $url)
			);


			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_date_added_asc'),
				'value' => 'p.date_added-ASC',
				'href'  => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] .  '&sort=p.date_added&order=ASC' . $url)
			);




			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=pd.name&order=ASC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=pd.name&order=DESC' . $url)
			);



			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=rating&order=DESC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=rating&order=ASC' . $url)
			);



			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->data['limits'] = array();

			$this->data['limits'][] = array(
				'text'  => $this->config->get('config_catalog_limit'),
				'value' => $this->config->get('config_catalog_limit'),
				'href'  => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id']. $url . '&limit=' . $this->config->get('config_catalog_limit'))
			);

			$this->data['limits'][] = array(
				'text'  => 25,
				'value' => 25,
				'href'  => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . $url . '&limit=25')
			);

			$this->data['limits'][] = array(
				'text'  => 50,
				'value' => 50,
				'href'  => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . $url . '&limit=50')
			);

			$this->data['limits'][] = array(
				'text'  => 75,
				'value' => 75,
				'href'  => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . $url . '&limit=75')
			);

			$this->data['limits'][] = array(
				'text'  => 100,
				'value' => 100,
				'href'  => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . $url . '&limit=100')
			);

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $record_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id']. $url . '&page={page}');

			$this->data['pagination'] = $pagination->render();

			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['limit'] = $limit;

			$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/record/blog.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/record/blog.tpl';
			} else {
				$this->template = 'default/template/record/blog.tpl';
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
//           
                        $this->load->model('catalog/totalblog'); //загружаем нашу модель
                        
//			$this->document->setTitle($blog_info['name']);
//			$this->document->setDescription($blog_info['meta_description']);
//			$this->document->setKeywords($blog_info['meta_keyword']);

//			$this->data['heading_title'] = $blog_info['name'];

                        //Определяем наши языковые перменные
                        
			$this->data['text_refine'] = $this->language->get('text_refine'); //Уточнить поиск
			$this->data['text_sort'] = $this->language->get('text_sort'); //Сортировать по
			$this->data['text_limit'] = $this->language->get('text_limit'); //На странице
			$this->data['text_comments'] = $this->language->get('text_comments'); //Комментариев
			$this->data['text_viewed'] = $this->language->get('text_viewed'); //Просмотров

                        //производим сортировку
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
                        
                        //производим группировку
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
                        
                        //выставляем лимит
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
                     
			$this->data['records'] = array();

			$data = array(
				'sort'               => $sort,                  //Сортировка
				'order'              => $order,                 //группировка
				'start'              => ($page - 1) * $limit,   //начальная страница
				'limit'              => $limit                  //ограничения
			);
                        
                        $record_total = $this->model_catalog_record->getTotalRecords($data);
                        
			$results = $this->model_catalog_totalblog->getRecords($data);

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

			$url = '';

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['sorts'] = array();

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('record/blog','&sort=p.sort_order&order=ASC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_date_added_desc'),
				'value' => 'p.date_added-DESC',
				'href'  => $this->url->link('record/blog', '&sort=p.date_added&order=DESC' . $url)
			);


			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_date_added_asc'),
				'value' => 'p.date_added-ASC',
				'href'  => $this->url->link('record/blog', '&sort=p.date_added&order=ASC' . $url)
			);




			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('record/blog', '&sort=pd.name&order=ASC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('record/blog', '&sort=pd.name&order=DESC' . $url)
			);



			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => $this->url->link('record/blog', '&sort=rating&order=DESC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => $this->url->link('record/blog', '&sort=rating&order=ASC' . $url)
			);



			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->data['limits'] = array();

			$this->data['limits'][] = array(
				'text'  => $this->config->get('config_catalog_limit'),
				'value' => $this->config->get('config_catalog_limit'),
				'href'  => $this->url->link('record/blog',  $url . '&limit=' . $this->config->get('config_catalog_limit'))
			);

			$this->data['limits'][] = array(
				'text'  => 25,
				'value' => 25,
				'href'  => $this->url->link('record/blog',  $url . '&limit=25')
			);

			$this->data['limits'][] = array(
				'text'  => 50,
				'value' => 50,
				'href'  => $this->url->link('record/blog',  $url . '&limit=50')
			);

			$this->data['limits'][] = array(
				'text'  => 75,
				'value' => 75,
				'href'  => $this->url->link('record/blog',  $url . '&limit=75')
			);

			$this->data['limits'][] = array(
				'text'  => 100,
				'value' => 100,
				'href'  => $this->url->link('record/blog',  $url . '&limit=100')
			);

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $record_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('record/blog',  $url . '&page={page}');

			$this->data['pagination'] = $pagination->render();

			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['limit'] = $limit;

			$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/record/blog.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/record/blog.tpl';
			} else {
				$this->template = 'default/template/record/blog.tpl';
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
}
?>