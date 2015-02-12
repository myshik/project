<?php
class ModelForumBoard extends Model {
	
	public function getForums($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "forum f LEFT JOIN " . DB_PREFIX . "forum_description fid ON (f.forum_id = fid.forum_id) WHERE fid.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$sort_data = array(
			'fid.title',
			'f.author',
			'f.sort_order',
			'fid.status',
			'fid.date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY f.sticky DESC, f.sort_order ASC, fid.date_added";	
		}
			
		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}																																							  
																																							  
		$query = $this->db->query($sql);																																				
		
		return $query->rows;	
	}
	
	public function getTotalRead($forum_id) {
		$query = $this->db->query("SELECT COUNT(*) AS view FROM " . DB_PREFIX . "forum_view WHERE forum_id='" . $forum_id . "'");
		
		return $query->row['view'];
	}
	
	public function getTotalPost($forum_id) {
		$query = $this->db->query("SELECT COUNT(*) AS post FROM " . DB_PREFIX . "forum_post WHERE forum_id='" . $forum_id . "'");
		
		return $query->row['post'];
	}
	
	public function getTotalForums() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "forum");
		
		return $query->row['total'];
	}
	
	public function getTotalForumsById($forum_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "forum_post");
		
		return $query->row['total'];
	}
	
	public function getForum($forum_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "forum WHERE forum_id = '" . (int)$forum_id . "'");
		
		return $query->row;
	}
	
	public function getForumDescription($forum_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "forum_description WHERE forum_id = '" . (int)$forum_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$forum_description_data = array();
		$author = $this->getForum($forum_id);
		$query = $this->db->query($sql);
		

		foreach ($query->rows as $result) {
			$forum_description_data = array(
				'forum_id'	  => $forum_id,
				'title'       => $result['title'],
				'description' => html_entity_decode($result['description'],ENT_QUOTES, 'UTF-8'),
				'author' => $author['author'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'time_added' => date($this->language->get('time_format'), strtotime($result['date_added'])) 
			);
		}
		
		return $forum_description_data;
	}
	
	public function setView($data = array()){
		$sql = "SELECT forum_id FROM " . DB_PREFIX ."forum_view WHERE forum_id='" . (int)$data['forum_id'] . "' AND ip = '" . $data['ip'] . "' AND DATE_FORMAT(date_added,'%Y-%m-%d') = CURDATE()";
		
		$query = $this->db->query($sql);
		$result = $query->rows;
		
		if(!$result){
			$sql = "INSERT INTO " . DB_PREFIX . "forum_view SET forum_id = '" . (int)$data['forum_id'] . "', ip = '" . $data['ip'] . "', date_added = NOW()";
			$this->db->query($sql);
		}
	}
	
	public function addPost($forum_id, $data) {
		//(int)$this->customer->getId()
		$this->db->query("INSERT INTO " . DB_PREFIX . "forum_post SET author = '" . $this->db->escape($data['name']) . "', ip = '" . $this->db->escape($data['myip']) . "', forum_id = '" . (int)$forum_id . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', date_added = NOW() , date_modified = NOW()");
	}
	
	public function addForum($languages, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "forum SET author = '" . $this->db->escape($data['name']) . "', status = '0', sort_order = '0'");
		$forum_id = $this->db->getLastId(); 
		
		foreach ($languages as $language){
			$sql = "INSERT INTO " . DB_PREFIX . "forum_description SET language_id = '" . (int)$language['id'] . "', title = '" . $this->db->escape($data['title']) . "', forum_id = '" . (int)$forum_id . "', description = '" . $this->db->escape(strip_tags($data['text'])) . "', date_added = NOW() , date_modified = NOW()";
			
			$this->db->query($sql);
		}
	}
	
	public function getPostDescription($forum_id){
		$post_description_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "forum_post WHERE forum_id = '" . (int)$forum_id . "'");
		
		foreach ($query->rows as $result) {
			$post_description_data[] = array(
				'forum_id'	=> $forum_id,
				'post_id'	=> $result['post_id'],
				'author'	=> $result['author'],
				'text'		=> html_entity_decode($result['text'],ENT_QUOTES, 'UTF-8'),
				'ip'		=> $result['ip'],
				'date_added'	=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'time_added'	=> date($this->language->get('time_format'), strtotime($result['date_added']))
			);
		}
		
		return $post_description_data;
	}
	
	public function viewForum($forum_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "forum SET sort_order = '" . (int)$data['sort_order'] . "', author = '" . $this->request->post['author'] . "', status = '" . (int)$data['status'] . "' WHERE forum_id = '" . (int)$forum_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "forum_description WHERE forum_id = '" . (int)$forum_id . "'");
					
		foreach ($data['forum_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "forum_description SET forum_id = '" . (int)$forum_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', preview = '" . $this->db->escape($value['preview']) . "', date_added = NOW()" . ", date_modified = NOW()");
			
		}
		
		$this->cache->delete('forum');
	}
}
?>