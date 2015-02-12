<?php
class ControllerCatalogRecord extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/record');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/record');

		$this->getList();
  	}

  	public function insert() {
    	$this->load->language('catalog/record');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/record');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_record->addRecord($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_blog'])) {
				$url .= '&filter_blog=' . $this->request->get['filter_blog'];
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/record', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('catalog/record');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/record');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_record->editRecord($this->request->get['record_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_blog'])) {
				$url .= '&filter_blog=' . $this->request->get['filter_blog'];
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/record', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->load->language('catalog/record');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/record');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $record_id) {
				$this->model_catalog_record->deleteRecord($record_id);
	  		}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_blog'])) {
				$url .= '&filter_blog=' . $this->request->get['filter_blog'];
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/record', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getList();
  	}

  	public function copy() {
    	$this->load->language('catalog/record');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/record');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $record_id) {
				$this->model_catalog_record->copyRecord($record_id);
	  		}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_blog'])) {
				$url .= '&filter_blog=' . $this->request->get['filter_blog'];
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/record', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getList();
  	}

  	private function getList() {

  	    $this->data['url_back'] = $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL');
   		$this->data['url_back_text'] = $this->language->get('url_back_text');


		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_blog'])) {
			$filter_blog = $this->request->get['filter_blog'];
		} else {
			$filter_blog = null;
		}

		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = null;
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_blog'])) {
			$url .= '&filter_blog=' . $this->request->get['filter_blog'];
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/record', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = $this->url->link('catalog/record/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['copy'] = $this->url->link('catalog/record/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/record/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['records'] = array();

		$data = array(
			'filter_name'	  => $filter_name,
			'filter_blog'	  => $filter_blog,
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

		$this->load->model('tool/image');

		$record_total = $this->model_catalog_record->getTotalRecords($data);

		$results = $this->model_catalog_record->getRecords($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/record/update', 'token=' . $this->session->data['token'] . '&record_id=' . $result['record_id'] . $url, 'SSL')
			);

			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}

			$special = false;

			$record_specials = $this->model_catalog_record->getRecordSpecials($result['record_id']);
/*
print_r("<PRE>");
		print_r($result);
print_r("</PRE>");
*/
			foreach ($record_specials  as $record_special) {
				if (($record_special['date_start'] == '0000-00-00' || $record_special['date_start'] > date('Y-m-d')) && ($record_special['date_end'] == '0000-00-00' || $record_special['date_end'] < date('Y-m-d'))) {
					$special = $record_special['price'];

					break;
				}
			}

      		$this->data['records'][] = array(
				'record_id' => $result['record_id'],
				'name'       => $result['name'],
				'blog'       => $result['blog_name'],
				'price'      => $result['price'],
				'special'    => $special,
				'image'      => $image,
				'quantity'   => $result['quantity'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'comment_status'     => ($result['comment_status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'comment_status_reg'     => ($result['comment_status_reg'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'comment_status_now'     => ($result['comment_status_now'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   => isset($this->request->post['selected']) && in_array($result['record_id'], $this->request->post['selected']),
				'action'     => $action
			);
    	}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');

		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_blog'] = $this->language->get('column_blog');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_copy'] = $this->language->get('button_copy');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

 		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_blog'])) {
			$url .= '&filter_blog=' . $this->request->get['filter_blog'];
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_name'] = $this->url->link('catalog/record', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$this->data['sort_blog'] = $this->url->link('catalog/record', 'token=' . $this->session->data['token'] . '&sort=p.blog' . $url, 'SSL');
		$this->data['sort_price'] = $this->url->link('catalog/record', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
		$this->data['sort_quantity'] = $this->url->link('catalog/record', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('catalog/record', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
		$this->data['sort_order'] = $this->url->link('catalog/record', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_blog'])) {
			$url .= '&filter_blog=' . $this->request->get['filter_blog'];
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $record_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/record', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_blog'] = $filter_blog;
		$this->data['filter_price'] = $filter_price;
		$this->data['filter_quantity'] = $filter_quantity;
		$this->data['filter_status'] = $filter_status;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/record_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');

  	    $this->data['url_back'] = $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL');
   		$this->data['url_back_text'] = $this->language->get('url_back_text');


    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_none'] = $this->language->get('text_none');
    	$this->data['text_yes'] = $this->language->get('text_yes');
    	$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$this->data['text_plus'] = $this->language->get('text_plus');
		$this->data['text_minus'] = $this->language->get('text_minus');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['text_option'] = $this->language->get('text_option');
		$this->data['text_option_value'] = $this->language->get('text_option_value');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_percent'] = $this->language->get('text_percent');
		$this->data['text_amount'] = $this->language->get('text_amount');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
    	$this->data['entry_model'] = $this->language->get('entry_model');
/*
		$this->data['entry_sku'] = $this->language->get('entry_sku');
		$this->data['entry_upc'] = $this->language->get('entry_upc');
		$this->data['entry_location'] = $this->language->get('entry_location');
		$this->data['entry_minimum'] = $this->language->get('entry_minimum');
		$this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
    	$this->data['entry_shipping'] = $this->language->get('entry_shipping');
*/

  	$this->data['entry_date_available'] = $this->language->get('entry_date_available');
    $this->data['entry_comment_status_reg'] = $this->language->get('entry_comment_status_reg');
    $this->data['entry_comment_status_now'] = $this->language->get('entry_comment_status_now');
    	/*
    	$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['entry_stock_status'] = $this->language->get('entry_stock_status');
    	$this->data['entry_price'] = $this->language->get('entry_price');
		$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$this->data['entry_points'] = $this->language->get('entry_points');
		  */


		$this->data['entry_option_points'] = $this->language->get('entry_option_points');
		$this->data['entry_subtract'] = $this->language->get('entry_subtract');
    	$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
    	$this->data['entry_weight'] = $this->language->get('entry_weight');
		$this->data['entry_dimension'] = $this->language->get('entry_dimension');
		$this->data['entry_length'] = $this->language->get('entry_length');
    	$this->data['entry_image'] = $this->language->get('entry_image');
    	$this->data['entry_download'] = $this->language->get('entry_download');
    	$this->data['entry_blog'] = $this->language->get('entry_blog');
		$this->data['entry_related'] = $this->language->get('entry_related');
		$this->data['entry_attribute'] = $this->language->get('entry_attribute');
		$this->data['entry_text'] = $this->language->get('entry_text');
		$this->data['entry_option'] = $this->language->get('entry_option');
		$this->data['entry_option_value'] = $this->language->get('entry_option_value');
		$this->data['entry_required'] = $this->language->get('entry_required');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_comment_status'] = $this->language->get('entry_comment_status');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_priority'] = $this->language->get('entry_priority');
		$this->data['entry_tag'] = $this->language->get('entry_tag');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_reward'] = $this->language->get('entry_reward');
		$this->data['entry_layout'] = $this->language->get('entry_layout');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_attribute'] = $this->language->get('button_add_attribute');
		$this->data['button_add_option'] = $this->language->get('button_add_option');
		$this->data['button_add_option_value'] = $this->language->get('button_add_option_value');
		$this->data['button_add_discount'] = $this->language->get('button_add_discount');
		$this->data['button_add_special'] = $this->language->get('button_add_special');
		$this->data['button_add_image'] = $this->language->get('button_add_image');
		$this->data['button_remove'] = $this->language->get('button_remove');

    	$this->data['tab_general'] = $this->language->get('tab_general');
    	$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_attribute'] = $this->language->get('tab_attribute');
		$this->data['tab_option'] = $this->language->get('tab_option');
		$this->data['tab_discount'] = $this->language->get('tab_discount');
		$this->data['tab_special'] = $this->language->get('tab_special');
    	$this->data['tab_image'] = $this->language->get('tab_image');
		$this->data['tab_links'] = $this->language->get('tab_links');
		$this->data['tab_reward'] = $this->language->get('tab_reward');
		$this->data['tab_design'] = $this->language->get('tab_design');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
		}

 		if (isset($this->error['meta_description'])) {
			$this->data['error_meta_description'] = $this->error['meta_description'];
		} else {
			$this->data['error_meta_description'] = array();
		}

   		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = array();
		}

   		if (isset($this->error['model'])) {
			$this->data['error_model'] = $this->error['model'];
		} else {
			$this->data['error_model'] = '';
		}

		if (isset($this->error['date_available'])) {
			$this->data['error_date_available'] = $this->error['date_available'];
		} else {
			$this->data['error_date_available'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_blog'])) {
			$url .= '&filter_blog=' . $this->request->get['filter_blog'];
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/record', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['record_id'])) {
			$this->data['action'] = $this->url->link('catalog/record/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/record/update', 'token=' . $this->session->data['token'] . '&record_id=' . $this->request->get['record_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/record', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['record_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$record_info = $this->model_catalog_record->getRecord($this->request->get['record_id']);
    	}

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['record_description'])) {
			$this->data['record_description'] = $this->request->post['record_description'];
		} elseif (isset($this->request->get['record_id'])) {
			$this->data['record_description'] = $this->model_catalog_record->getRecordDescriptions($this->request->get['record_id']);
		} else {
			$this->data['record_description'] = array();
		}

		if (isset($this->request->post['model'])) {
      		$this->data['model'] = $this->request->post['model'];
    	} elseif (!empty($record_info)) {
			$this->data['model'] = $record_info['model'];
		} else {
      		$this->data['model'] = '';
    	}

		if (isset($this->request->post['sku'])) {
      		$this->data['sku'] = $this->request->post['sku'];
    	} elseif (!empty($record_info)) {
			$this->data['sku'] = $record_info['sku'];
		} else {
      		$this->data['sku'] = '';
    	}

		if (isset($this->request->post['upc'])) {
      		$this->data['upc'] = $this->request->post['upc'];
    	} elseif (!empty($record_info)) {
			$this->data['upc'] = $record_info['upc'];
		} else {
      		$this->data['upc'] = '';
    	}

		if (isset($this->request->post['location'])) {
      		$this->data['location'] = $this->request->post['location'];
    	} elseif (!empty($record_info)) {
			$this->data['location'] = $record_info['location'];
		} else {
      		$this->data['location'] = '';
    	}

		$this->load->model('setting/store');

		$this->data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['record_store'])) {
			$this->data['record_store'] = $this->request->post['record_store'];
		} elseif (isset($this->request->get['record_id'])) {
			$this->data['record_store'] = $this->model_catalog_record->getRecordStores($this->request->get['record_id']);
		} else {
			$this->data['record_store'] = array(0);
		}

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($record_info)) {
			$this->data['keyword'] = $record_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}

		if (isset($this->request->post['record_tag'])) {
			$this->data['record_tag'] = $this->request->post['record_tag'];
		} elseif (isset($this->request->get['record_id'])) {
			$this->data['record_tag'] = $this->model_catalog_record->getRecordTags($this->request->get['record_id']);
		} else {
			$this->data['record_tag'] = array();
		}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($record_info)) {
			$this->data['image'] = $record_info['image'];
		} else {
			$this->data['image'] = '';
		}

		$this->load->model('tool/image');

		if (!empty($record_info) && $record_info['image'] && file_exists(DIR_IMAGE . $record_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($record_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->load->model('catalog/manufacturer');

    	$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

    	if (isset($this->request->post['manufacturer_id'])) {
      		$this->data['manufacturer_id'] = $this->request->post['manufacturer_id'];
		} elseif (!empty($record_info)) {
			$this->data['manufacturer_id'] = $record_info['manufacturer_id'];
		} else {
      		$this->data['manufacturer_id'] = 0;
    	}

    	if (isset($this->request->post['shipping'])) {
      		$this->data['shipping'] = $this->request->post['shipping'];
    	} elseif (!empty($record_info)) {
      		$this->data['shipping'] = $record_info['shipping'];
    	} else {
			$this->data['shipping'] = 1;
		}

    	if (isset($this->request->post['price'])) {
      		$this->data['price'] = $this->request->post['price'];
    	} else if (!empty($record_info)) {
			$this->data['price'] = $record_info['price'];
		} else {
      		$this->data['price'] = '';
    	}

		$this->load->model('localisation/tax_class');

		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['tax_class_id'])) {
      		$this->data['tax_class_id'] = $this->request->post['tax_class_id'];
    	} else if (!empty($record_info)) {
			$this->data['tax_class_id'] = $record_info['tax_class_id'];
		} else {
      		$this->data['tax_class_id'] = 0;
    	}

		if (isset($this->request->post['date_available'])) {
       		$this->data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($record_info)) {
			$this->data['date_available'] = date('Y-m-d', strtotime($record_info['date_available']));
		} else {
			$this->data['date_available'] = date('Y-m-d', time() - 86400);
		}

    	if (isset($this->request->post['quantity'])) {
      		$this->data['quantity'] = $this->request->post['quantity'];
    	} elseif (!empty($record_info)) {
      		$this->data['quantity'] = $record_info['quantity'];
    	} else {
			$this->data['quantity'] = 1;
		}

		if (isset($this->request->post['minimum'])) {
      		$this->data['minimum'] = $this->request->post['minimum'];
    	} elseif (!empty($record_info)) {
      		$this->data['minimum'] = $record_info['minimum'];
    	} else {
			$this->data['minimum'] = 1;
		}

		if (isset($this->request->post['subtract'])) {
      		$this->data['subtract'] = $this->request->post['subtract'];
    	} elseif (!empty($record_info)) {
      		$this->data['subtract'] = $record_info['subtract'];
    	} else {
			$this->data['subtract'] = 1;
		}

		if (isset($this->request->post['sort_order'])) {
      		$this->data['sort_order'] = $this->request->post['sort_order'];
    	} elseif (!empty($record_info)) {
      		$this->data['sort_order'] = $record_info['sort_order'];
    	} else {
			$this->data['sort_order'] = '';
		}

		$this->load->model('localisation/stock_status');

		$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

		if (isset($this->request->post['stock_status_id'])) {
      		$this->data['stock_status_id'] = $this->request->post['stock_status_id'];
    	} else if (!empty($record_info)) {
      		$this->data['stock_status_id'] = $record_info['stock_status_id'];
    	} else {
			$this->data['stock_status_id'] = $this->config->get('config_stock_status_id');
		}

    	if (isset($this->request->post['status'])) {
      		$this->data['status'] = $this->request->post['status'];
    	} else if (!empty($record_info)) {
			$this->data['status'] = $record_info['status'];
		} else {
      		$this->data['status'] = 1;
    	}


    	if (isset($this->request->post['comment_status'])) {
      		$this->data['comment_status'] = $this->request->post['comment_status'];
    	} else if (!empty($record_info)) {
			$this->data['comment_status'] = $record_info['comment_status'];
		} else {
      		$this->data['comment_status'] = 1;
    	}

    	if (isset($this->request->post['comment_status_reg'])) {
      		$this->data['comment_status_reg'] = $this->request->post['comment_status_reg'];
    	} else if (!empty($record_info)) {
			$this->data['comment_status_reg'] = $record_info['comment_status_reg'];
		} else {
      		$this->data['comment_status_reg'] = 0;
    	}

    	if (isset($this->request->post['comment_status_now'])) {
      		$this->data['comment_status_now'] = $this->request->post['comment_status_now'];
    	} else if (!empty($record_info)) {
			$this->data['comment_status_now'] = $record_info['comment_status_now'];
		} else {
      		$this->data['comment_status_now'] = 0;
    	}


    	if (isset($this->request->post['weight'])) {
      		$this->data['weight'] = $this->request->post['weight'];
		} else if (!empty($record_info)) {
			$this->data['weight'] = $record_info['weight'];
    	} else {
      		$this->data['weight'] = '';
    	}

		$this->load->model('localisation/weight_class');

		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['weight_class_id'])) {
      		$this->data['weight_class_id'] = $this->request->post['weight_class_id'];
    	} elseif (!empty($record_info)) {
      		$this->data['weight_class_id'] = $record_info['weight_class_id'];
		} else {
      		$this->data['weight_class_id'] = $this->config->get('config_weight_class_id');
    	}

		if (isset($this->request->post['length'])) {
      		$this->data['length'] = $this->request->post['length'];
    	} elseif (!empty($record_info)) {
			$this->data['length'] = $record_info['length'];
		} else {
      		$this->data['length'] = '';
    	}

		if (isset($this->request->post['width'])) {
      		$this->data['width'] = $this->request->post['width'];
		} elseif (!empty($record_info)) {
			$this->data['width'] = $record_info['width'];
    	} else {
      		$this->data['width'] = '';
    	}

		if (isset($this->request->post['height'])) {
      		$this->data['height'] = $this->request->post['height'];
		} elseif (!empty($record_info)) {
			$this->data['height'] = $record_info['height'];
    	} else {
      		$this->data['height'] = '';
    	}

		$this->load->model('localisation/length_class');

		$this->data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		if (isset($this->request->post['length_class_id'])) {
      		$this->data['length_class_id'] = $this->request->post['length_class_id'];
    	} elseif (!empty($record_info)) {
      		$this->data['length_class_id'] = $record_info['length_class_id'];
    	} else {
      		$this->data['length_class_id'] = $this->config->get('config_length_class_id');
		}

		if (isset($this->request->post['record_attribute'])) {
			$this->data['record_attributes'] = $this->request->post['record_attribute'];
		} elseif (isset($this->request->get['record_id'])) {
			$this->data['record_attributes'] = $this->model_catalog_record->getRecordAttributes($this->request->get['record_id']);
		} else {
			$this->data['record_attributes'] = array();
		}

		if (isset($this->request->post['record_option'])) {
			$record_options = $this->request->post['record_option'];
		} elseif (isset($this->request->get['record_id'])) {
			$record_options = $this->model_catalog_record->getRecordOptions($this->request->get['record_id']);
		} else {
			$record_options = array();
		}

		$this->data['record_options'] = array();

		foreach ($record_options as $record_option) {
			if ($record_option['type'] == 'select' || $record_option['type'] == 'radio' || $record_option['type'] == 'checkbox' || $record_option['type'] == 'image') {
				$record_option_value_data = array();

				foreach ($record_option['record_option_value'] as $record_option_value) {
					$record_option_value_data[] = array(
						'record_option_value_id' => $record_option_value['record_option_value_id'],
						'option_value_id'         => $record_option_value['option_value_id'],
						'quantity'                => $record_option_value['quantity'],
						'subtract'                => $record_option_value['subtract'],
						'price'                   => $record_option_value['price'],
						'price_prefix'            => $record_option_value['price_prefix'],
						'points'                  => $record_option_value['points'],
						'points_prefix'           => $record_option_value['points_prefix'],
						'weight'                  => $record_option_value['weight'],
						'weight_prefix'           => $record_option_value['weight_prefix']
					);
				}

				$this->data['record_options'][] = array(
					'record_option_id'    => $record_option['record_option_id'],
					'option_id'            => $record_option['option_id'],
					'name'                 => $record_option['name'],
					'type'                 => $record_option['type'],
					'record_option_value' => $record_option_value_data,
					'required'             => $record_option['required']
				);
			} else {
				$this->data['record_options'][] = array(
					'record_option_id' => $record_option['record_option_id'],
					'option_id'         => $record_option['option_id'],
					'name'              => $record_option['name'],
					'type'              => $record_option['type'],
					'option_value'      => $record_option['option_value'],
					'required'          => $record_option['required']
				);
			}
		}

		$this->load->model('sale/customer_group');

		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		if (isset($this->request->post['record_discount'])) {
			$this->data['record_discounts'] = $this->request->post['record_discount'];
		} elseif (isset($this->request->get['record_id'])) {
			$this->data['record_discounts'] = $this->model_catalog_record->getRecordDiscounts($this->request->get['record_id']);
		} else {
			$this->data['record_discounts'] = array();
		}

		if (isset($this->request->post['record_special'])) {
			$this->data['record_specials'] = $this->request->post['record_special'];
		} elseif (isset($this->request->get['record_id'])) {
			$this->data['record_specials'] = $this->model_catalog_record->getRecordSpecials($this->request->get['record_id']);
		} else {
			$this->data['record_specials'] = array();
		}

		if (isset($this->request->post['record_image'])) {
			$record_images = $this->request->post['record_image'];
		} elseif (isset($this->request->get['record_id'])) {
			$record_images = $this->model_catalog_record->getRecordImages($this->request->get['record_id']);
		} else {
			$record_images = array();
		}

		$this->data['record_images'] = array();

		foreach ($record_images as $record_image) {
			if ($record_image['image'] && file_exists(DIR_IMAGE . $record_image['image'])) {
				$image = $record_image['image'];
			} else {
				$image = 'no_image.jpg';
			}

			$this->data['record_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($image, 100, 100),
				'sort_order' => $record_image['sort_order'],
			);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		$this->load->model('catalog/download');

		$this->data['downloads'] = $this->model_catalog_download->getDownloads();

		if (isset($this->request->post['record_download'])) {
			$this->data['record_download'] = $this->request->post['record_download'];
		} elseif (isset($this->request->get['record_id'])) {
			$this->data['record_download'] = $this->model_catalog_record->getRecordDownloads($this->request->get['record_id']);
		} else {
			$this->data['record_download'] = array();
		}

		$this->load->model('catalog/blog');

		$this->data['categories'] = $this->model_catalog_blog->getCategories(0);

		if (isset($this->request->post['record_blog'])) {
			$this->data['record_blog'] = $this->request->post['record_blog'];
		} elseif (isset($this->request->get['record_id'])) {
			$this->data['record_blog'] = $this->model_catalog_record->getRecordCategories($this->request->get['record_id']);
		} else {
			$this->data['record_blog'] = array();
		}

		if (isset($this->request->post['record_related'])) {
			$records = $this->request->post['record_related'];
		} elseif (isset($this->request->get['record_id'])) {
			$records = $this->model_catalog_record->getRecordRelated($this->request->get['record_id']);
		} else {
			$records = array();
		}

		$this->data['record_related'] = array();

		foreach ($records as $record_id) {
			$related_info = $this->model_catalog_record->getRecord($record_id);

			if ($related_info) {
				$this->data['record_related'][] = array(
					'record_id' => $related_info['record_id'],
					'name'       => $related_info['name']
				);
			}
		}

    	if (isset($this->request->post['points'])) {
      		$this->data['points'] = $this->request->post['points'];
    	} else if (!empty($record_info)) {
			$this->data['points'] = $record_info['points'];
		} else {
      		$this->data['points'] = '';
    	}

		if (isset($this->request->post['record_reward'])) {
			$this->data['record_reward'] = $this->request->post['record_reward'];
		} elseif (isset($this->request->get['record_id'])) {
			$this->data['record_reward'] = $this->model_catalog_record->getRecordRewards($this->request->get['record_id']);
		} else {
			$this->data['record_reward'] = array();
		}

		if (isset($this->request->post['record_layout'])) {
			$this->data['record_layout'] = $this->request->post['record_layout'];
		} elseif (isset($this->request->get['record_id'])) {
			$this->data['record_layout'] = $this->model_catalog_record->getRecordLayouts($this->request->get['record_id']);
		} else {
			$this->data['record_layout'] = array();
		}

		$this->load->model('design/layout');

		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'catalog/record_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/record')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	foreach ($this->request->post['record_description'] as $language_id => $value) {
      		if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
        		$this->error['name'][$language_id] = $this->language->get('error_name');
      		}
    	}

/*
    	if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
      		$this->error['model'] = $this->language->get('error_model');
    	}
  */
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

    	if (!$this->error) {
			return true;
    	} else {
      		return false;
    	}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/record')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

  	private function validateCopy() {
    	if (!$this->user->hasPermission('modify', 'catalog/record')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function option() {
		$output = '';

		$this->load->model('catalog/option');

		$results = $this->model_catalog_option->getOptionValues($this->request->get['option_id']);

		foreach ($results as $result) {
			$output .= '<option value="' . $result['option_value_id'] . '"';

			if (isset($this->request->get['option_value_id']) && ($this->request->get['option_value_id'] == $result['option_value_id'])) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		$this->response->setOutput($output);
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_blog']) || isset($this->request->get['filter_blog_id'])) {
			$this->load->model('catalog/record');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_blog'])) {
				$filter_blog = $this->request->get['filter_blog'];
			} else {
				$filter_blog = '';
			}

			if (isset($this->request->get['filter_blog_id'])) {
				$filter_blog_id = $this->request->get['filter_blog_id'];
			} else {
				$filter_blog_id = '';
			}

			if (isset($this->request->get['filter_sub_blog'])) {
				$filter_sub_blog = $this->request->get['filter_sub_blog'];
			} else {
				$filter_sub_blog = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'filter_name'     => $filter_name,
				'filter_blog'     => $filter_blog,
				'filter_blog_id'  => $filter_blog_id,
				'filter_sub_blog' => $filter_sub_blog,
				'start'           => 0,
				'limit'           => $limit
			);

			$results = $this->model_catalog_record->getRecords($data);

			foreach ($results as $result) {
				$option_data = array();

				$record_options = $this->model_catalog_record->getRecordOptions($result['record_id']);

				foreach ($record_options as $record_option) {
					if ($record_option['type'] == 'select' || $record_option['type'] == 'radio' || $record_option['type'] == 'checkbox' || $record_option['type'] == 'image') {
						$option_value_data = array();

						foreach ($record_option['record_option_value'] as $record_option_value) {
							$option_value_data[] = array(
								'record_option_value_id' => $record_option_value['record_option_value_id'],
								'option_value_id'         => $record_option_value['option_value_id'],
								'name'                    => $record_option_value['name'],
								'price'                   => (float)$record_option_value['price'] ? $this->currency->format($record_option_value['price'], $this->config->get('config_currency')) : false,
								'price_prefix'            => $record_option_value['price_prefix']
							);
						}

						$option_data[] = array(
							'record_option_id' => $record_option['record_option_id'],
							'option_id'         => $record_option['option_id'],
							'name'              => $record_option['name'],
							'type'              => $record_option['type'],
							'option_value'      => $option_value_data,
							'required'          => $record_option['required']
						);
					} else {
						$option_data[] = array(
							'record_option_id' => $record_option['record_option_id'],
							'option_id'         => $record_option['option_id'],
							'name'              => $record_option['name'],
							'type'              => $record_option['type'],
							'option_value'      => $record_option['option_value'],
							'required'          => $record_option['required']
						);
					}
				}

				$json[] = array(
					'record_id' => $result['record_id'],
					'name'       => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),
					'blog'      => $result['blog_name'],
					'option'     => $option_data,
					'price'      => $result['price']
				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>