<?php 
class ControllerForumBoard extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('forum/board');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('forum/board');
		
		$this->getList();
	}
	
	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$url = '';
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['action'] = HTTP_SERVER . 'index.php?route=forum/board/insert';
		
		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home',
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=forum/board' . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['forums'] = array();
		
		$data = array(
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$this->load->model('tool/seo_url'); 
		$this->load->model('tool/image');
			
		$results = $this->model_forum_board->getForums($data);
		
		foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=forum/board/view&forum_id=' . $result['forum_id'] . $url
			);
						
			$this->data['forums'][] = array(
				'forum_id'  => $result['forum_id'],
				'name'       => $result['title'],
				'author'     => $result['author'],
				'image'		 => ($result['image']=='')? 'forum_new.png' : $result['image'],
				'sticky'	 => $result['sticky'],
				'preview' => $result['preview'],
				'post' 		 => $this->model_forum_board->getTotalPost($result['forum_id']),
				'read'		 => $this->model_forum_board->getTotalRead($result['forum_id']),
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])), 
				'time_added' => date($this->language->get('time_format'), strtotime($result['date_added'])),
				'action'     => $action
			);
		}	
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_post'] = $this->language->get('column_post');
				
		$this->data['button_createtopic'] = $this->language->get('button_createtopic');
		
		$this->data['logged'] = $this->customer->isLogged();
		
		
		
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
		
		$forum_total = $this->model_forum_board->getTotalForums();
		
		$pagination = new Pagination();
		$pagination->total = $forum_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=forum/board' . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['int'] = 0;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/forum/board.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/forum/board.tpl';
		} else {
			$this->template = 'default/template/forum/board.tpl';
		}
			
		
		$this->children = array(
			'common/column_right',
			'common/column_left',
			'common/footer',
			'common/header'
		);		
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));	
	}
	
	public function view() {
		$this->load->language('forum/board');
		$this->load->model('account/customer');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('forum/board');

		$this->getPost();
	}
	
	private function getPost() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_post'] = $this->language->get('entry_title');
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_captcha']  = $this->language->get('entry_captcha');
		$this->data['text_write'] = $this->language->get('text_write');
		$this->data['text_note']  = $this->language->get('text_note');
		$this->data['text_wait']  = $this->language->get('text_wait');
		
		$this->data['button_createpost'] = $this->language->get('button_createpost');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['logged'] = $this->customer->isLogged();
		if($this->customer->isLogged()){
			if ($this->request->server['REQUEST_METHOD'] != 'POST') {
				$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());			
			}
		}
		
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($customer_info)) {
			$this->data['name'] = $customer_info['firstname'] . " " . $customer_info['lastname'];
		} else {
			$this->data['name'] = '';
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$forum_id = $this->request->get['forum_id'];
		$myip = $_SERVER["REMOTE_ADDR"];
		$this->data['myip'] = $myip;
		$this->data['forum_id'] = $forum_id;
		
		$data = array(
			'forum_id' 	=> $forum_id,
			'ip'	=>	$myip
		);
		
		$this->model_forum_board->setView($data);
		
		
		$this->data['forums'] = array();
		$this->data['posts'] = array();
		$this->data['int'] = 0;
		$this->data['forums'] = $this->model_forum_board->getForumDescription($forum_id);
		$this->data['posts'] = $this->model_forum_board->getPostDescription($forum_id);
		
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/forum/view.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/forum/view.tpl';
		} else {
			$this->template = 'default/template/forum/view.tpl';
		}
			
		
		$this->children = array(
			'common/column_right',
			'common/column_left',
			'common/footer',
			'common/header'
		);		
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));	
	}
	
	public function insert() {
		$this->load->language('forum/board');
		$this->load->model('account/customer');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('forum/board');
		
		
		$this->getForm();
	}
	
	public function getForm(){
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_post'] = $this->language->get('entry_post');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_captcha']  = $this->language->get('entry_captcha');
		$this->data['text_write'] = $this->language->get('text_write');
		$this->data['text_note']  = $this->language->get('text_note');
		$this->data['text_wait']  = $this->language->get('text_wait');
		
		$this->data['button_createpost'] = $this->language->get('button_createpost');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['logged'] = $this->customer->isLogged();
		
		if ($this->request->server['REQUEST_METHOD'] != 'GET') {
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($customer_info)) {
			$this->data['name'] = $customer_info['firstname'] . " " . $customer_info['lastname'];
		} else {
			$this->data['name'] = '';
		}
		
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/forum/board_form.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/forum/board_form.tpl';
		} else {
			$this->template = 'default/template/forum/board_form.tpl';
		}
			
		
		$this->children = array(
			'common/column_right',
			'common/column_left',
			'common/footer',
			'common/header'
		);		
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));	
	}
	
	public function review() {
    	$this->language->load('forum/board');
		
		$this->load->model('forum/board');

		$this->data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  
		
		$forum_id = $this->request->get['forum_id'];
		$myip = $_SERVER["REMOTE_ADDR"];
		$this->data['myip'] = $myip;
		$this->data['forum_id'] = $forum_id;
		
		$this->data['forums'] = array();
		$this->data['posts'] = array();
		$this->data['int'] = 0;
		$this->data['forums'] = $this->model_forum_board->getForumDescription($forum_id);
		$this->data['posts'] = $this->model_forum_board->getPostDescription($forum_id);		
		
		$data = array(
			'forum_id' 	=> $forum_id,
			'ip'	=>	$myip
		);
		
		$this->model_forum_board->setView($data);
		
		$forum_total = $this->model_forum_board->getTotalForumsById($forum_id);
			
		$pagination = new Pagination();
		$pagination->total = $forum_total;
		$pagination->page = $page;
		$pagination->limit = 5; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTP_SERVER . 'index.php?route=forum/board/review&forum_id=' . $forum_id . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/forum/post.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/forum/post.tpl';
		} else {
			$this->template = 'default/template/forum/post.tpl';
		}
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function captcha() {
		$this->load->library('captcha');
		
		$captcha = new Captcha();
		
		$this->session->data['captcha'] = $captcha->getCode();
		
		$captcha->showImage();
	}
	
	public function write() {
		$this->language->load('forum/board');
		
		$this->load->model('forum/board');
		
		$json = array();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_forum_board->addPost($this->request->get['forum_id'], $this->request->post);
			
			$json['success'] = $this->language->get('text_success');
		} else {
			$json['error'] = $this->error['message'];
		}	
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
		
	}
	
	public function create() {
		$this->language->load('forum/board');
		
		$this->load->model('forum/board');
		
		$json = array();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->load->model('localisation/language');
		
			$this->data['languages'] = array();
		
			$results = $this->model_localisation_language->getLanguages();
		
			foreach ($results as $result) {
				if ($result['status']) {
					$this->data['languages'][] = array(
						'id'  => $result['language_id']
						);	
				}
			}
			
			$this->model_forum_board->addForum($this->data['languages'], $this->request->post);
			
			$json['success'] = $this->language->get('text_postsuccess');
		} else {
			$json['error'] = $this->error['message'];
		}	
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
		
	}
	
	private function validate() {	
		if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 64)) {
			$this->error['message'] = $this->language->get('error_name');
		}
			
		if ((strlen(utf8_decode($this->request->post['text'])) < 25) || (strlen(utf8_decode($this->request->post['text'])) > 1000)) {
			$this->error['message'] = $this->language->get('error_text');
		}

		if (!isset($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
			$this->error['message'] = $this->language->get('error_captcha');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	
	private function validateForm() {	
		if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 64)) {
			$this->error['message'] = $this->language->get('error_name');
		}
		
		if ((strlen(utf8_decode($this->request->post['title'])) < 3) || (strlen(utf8_decode($this->request->post['title'])) > 300)) {
			$this->error['message'] = $this->language->get('error_title');
		}
			
		if ((strlen(utf8_decode($this->request->post['text'])) < 25) || (strlen(utf8_decode($this->request->post['text'])) > 1000)) {
			$this->error['message'] = $this->language->get('error_text');
		}

		if (!isset($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
			$this->error['message'] = $this->language->get('error_captcha');
		}

		if (!$this->error) {
			return TRUE;
		} else {
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