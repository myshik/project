<?php
class ControllerCatalogForum extends Controller {
	private $error = array();
 
	public function index() {
		$this->load->language('catalog/forum');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/forum');
		
		$this->getList();
	} 

	public function insert() {
		$this->load->language('catalog/forum');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/forum');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_forum->addForum($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
						
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/forum&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/forum');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/forum');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_forum->editForum($this->request->get['forum_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
						
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/forum&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function delete() { 
		$this->load->language('catalog/forum');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/forum');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $forum_id) {
				$this->model_catalog_forum->deleteForum($forum_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
						
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/forum&token=' . $this->session->data['token'] . $url);
		}

		$this->getList();
	}

	public function checkTableExit() {
		$this->load->language('catalog/forum');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/forum');
		
		$checkTable = $this->model_catalog_forum->checkTable();
		$this->data['checkTable'] = $checkTable;

		$this->getList();
	}
		
	private function getList() {
		$checkTable = $this->model_catalog_forum->checkTable();
		$this->data['checkTable'] = $checkTable;
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'r.date_added';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=catalog/forum&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=catalog/forum/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=catalog/forum/delete&token=' . $this->session->data['token'] . $url;
		$this->data['gen_table'] = HTTPS_SERVER . 'index.php?route=catalog/forum/checkTableExit&token=' . $this->session->data['token'] . $url;

		$this->data['forums'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$forum_total = $this->model_catalog_forum->getTotalForums();
		
		$results = $this->model_catalog_forum->getForums($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=catalog/forum/update&token=' . $this->session->data['token'] . '&forum_id=' . $result['forum_id'] . $url
			);
						
			$this->data['forums'][] = array(
				'forum_id'  => $result['forum_id'],
				'name'       => $result['title'],
				'author'     => $result['author'],
				'post' 		 => $this->model_catalog_forum->getTotalPost($result['forum_id']),
				'read'		 => $this->model_catalog_forum->getTotalRead($result['forum_id']),
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])), 
				'time_added' => date($this->language->get('time_format'), strtotime($result['date_added'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['forum_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_subject'] = $this->language->get('column_subject');
		$this->data['column_author'] = $this->language->get('column_author');
		$this->data['column_readwrite'] = $this->language->get('column_readwrite');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['column_post'] = $this->language->get('column_post');
		$this->data['column_read'] = $this->language->get('column_read');
		$this->data['column_createdby'] = $this->language->get('column_createdby');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_table'] = $this->language->get('button_table');
		
 		
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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_product'] = HTTPS_SERVER . 'index.php?route=catalog/forum&token=' . $this->session->data['token'] . '&sort=pd.name' . $url;
		$this->data['sort_author'] = HTTPS_SERVER . 'index.php?route=catalog/forum&token=' . $this->session->data['token'] . '&sort=r.author' . $url;
		//$this->data['sort_rating'] = HTTPS_SERVER . 'index.php?route=catalog/forum&token=' . $this->session->data['token'] . '&sort=r.rating' . $url;
		$this->data['sort_status'] = HTTPS_SERVER . 'index.php?route=catalog/forum&token=' . $this->session->data['token'] . '&sort=r.status' . $url;
		$this->data['sort_date_added'] = HTTPS_SERVER . 'index.php?route=catalog/forum&token=' . $this->session->data['token'] . '&sort=r.date_added' . $url;
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $forum_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=catalog/forum&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'catalog/forum_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_select'] = $this->language->get('text_select');

		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_author'] = $this->language->get('entry_author');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_preview'] = $this->language->get('entry_preview');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		//Upgrade 1.0
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_sticky'] = $this->language->get('entry_sticky');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
 		
		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}
		
		if (isset($this->error['preview'])) {
			$this->data['error_preview'] = $this->error['preview'];
		} else {
			$this->data['error_preview'] = '';
		}
		
		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}
				
 		if (isset($this->error['author'])) {
			$this->data['error_author'] = $this->error['author'];
		} else {
			$this->data['error_author'] = '';
		}
				
		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=catalog/forum&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
										
		if (!isset($this->request->get['forum_id'])) { 
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/forum/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/forum/update&token=' . $this->session->data['token'] . '&forum_id=' . $this->request->get['forum_id'] . $url;
		}
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=catalog/forum&token=' . $this->session->data['token'] . $url;

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['forum_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$forum_info = $this->model_catalog_forum->getForum($this->request->get['forum_id']);
		}
		
		if (isset($this->request->post['forum_description'])) {
			$this->data['forum_description'] = $this->request->post['forum_description'];
		} elseif (isset($this->request->get['forum_id'])) {
			$this->data['forum_description'] = $this->model_catalog_forum->getForumDescriptions($this->request->get['forum_id']);
		} else {
			$this->data['forum_description'] = array();
		}
		
		
		/*if (isset($this->request->post['preview'])) {
			$this->data['author'] = $this->request->post['preview'];
		}else{ 
			$this->data['preview'] = '';
		}*/
		
		if (isset($this->request->post['author'])) {
			$this->data['author'] = $this->request->post['author'];
		} elseif (isset($forum_info)) {
			$this->data['author'] = $forum_info['author'];
		} else {
			$this->data['author'] = '';
		}
				
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($forum_info)) {
			$this->data['sort_order'] = $forum_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (isset($forum_info)) {
			$this->data['image'] = $forum_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
		if (isset($this->request->post['sticky'])) {
			$this->data['sticky'] = $this->request->post['sticky'];
		} elseif (isset($forum_info)) {
			$this->data['sticky'] = $forum_info['sticky'];
		} else {
			$this->data['status'] = '';
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($forum_info)) {
			$this->data['status'] = $forum_info['status'];
		} else {
			$this->data['status'] = '';
		}
		
		$this->template = 'catalog/forum_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/forum')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['forum_description'] as $language_id => $value) {
			if ((strlen(utf8_decode($value['title'])) < 3) || (strlen(utf8_decode($value['title'])) > 100)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		
			if (strlen(utf8_decode($value['description'])) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}
			
			if ((strlen(utf8_decode($value['preview'])) < 3) || (strlen(utf8_decode($value['preview'])) > 300)) {
				$this->error['preview'][$language_id] = $this->language->get('error_preview');
			}
			
		}
		
		
		
		if (!$this->error) {
			return TRUE;
		} else {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/forum')) {
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