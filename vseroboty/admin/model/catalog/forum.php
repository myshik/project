<?php
class ModelCatalogForum extends Model {
	public function checkTable() {
		$sql = "SHOW TABLES WHERE Tables_in_" . DB_DATABASE ." = '" . DB_PREFIX ."forum_upgrade'";
		$query = $this->db->query($sql);
		$results =$query->num_rows;
		
		if($results==0){
			$this->upgradeTable();
		}else{
			return true;
		}
	}
	
	public function upgradeTable() {
		
		$this->db->query("CREATE TABLE  `" . DB_DATABASE . "`.`" . DB_PREFIX . "forum_upgrade` (
						  `version` VARCHAR(10) COLLATE utf8_bin NOT NULL DEFAULT ''
						  ) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_bin");
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "forum_upgrade SET version = '1.0'");
						
		$this->db->query("ALTER TABLE `" . DB_DATABASE . "`.`" . DB_PREFIX . "forum` 
						ADD COLUMN `sticky` INT(1) NOT NULL DEFAULT 0 AFTER `status`,
						ADD COLUMN `image` VARCHAR(64) COLLATE utf8_bin NOT NULL DEFAULT '' AFTER `sticky`");
	}
	
	public function addForum($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "forum SET sort_order = '" . (int)$this->request->post['sort_order'] . "', author = '" . $this->request->post['author'] . "', status = '" . (int)$data['status'] . "', sticky = '" . (int)$this->request->post['sticky'] . "', image = '" . $this->request->post['image'] . "'");

		$forum_id = $this->db->getLastId(); 
			
		foreach ($data['forum_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "forum_description SET forum_id = '" . (int)$forum_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', preview = '" . $this->db->escape($value['preview']) . "', date_added = NOW()" . ", date_modified = NOW()");
		}
		
		$this->cache->delete('forum');
	}
	
	public function editForum($forum_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "forum SET sort_order = '" . (int)$data['sort_order'] . "', author = '" . $this->request->post['author'] . "', status = '" . (int)$data['status'] . "', sticky = '" . (int)$this->request->post['sticky'] . "', image = '" . $this->request->post['image'] . "' WHERE forum_id = '" . (int)$forum_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "forum_description WHERE forum_id = '" . (int)$forum_id . "'");
					
		foreach ($data['forum_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "forum_description SET forum_id = '" . (int)$forum_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', preview = '" . $this->db->escape($value['preview']) . "', date_added = NOW()" . ", date_modified = NOW()");
			
		}
		
		$this->cache->delete('forum');
	}
	
	public function deleteForum($forum_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "forum WHERE forum_id = '" . (int)$forum_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "forum_description WHERE forum_id = '" . (int)$forum_id . "'");
		
		$this->cache->delete('forum');
	}
	
	public function getForum($forum_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "forum WHERE forum_id = '" . (int)$forum_id . "'");
		
		return $query->row;
	}

	public function getForums($data = array()) {
		/*$sql = "SELECT r.review_id, pd.name, r.author, r.rating, r.status, r.date_added FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";*/
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
			$sql .= " ORDER BY fid.date_added";	
		}
			
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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
	
	public function getForumDescriptions($forum_id) {
		$forum_description_data = array();
		$author = $this->getForum($forum_id);
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "forum_description WHERE forum_id = '" . (int)$forum_id . "'");

		foreach ($query->rows as $result) {
			$forum_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
				'preview'       => $result['preview'],
				'description' => $result['description'],
				'author' => $author['author']
			);
		}
		
		return $forum_description_data;
	}
	
	public function getTotalForums() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "forum");
		
		return $query->row['total'];
	}
	
	public function getTotalPost($forum_id) {
		$query = $this->db->query("SELECT COUNT(*) AS post FROM " . DB_PREFIX . "forum_post WHERE forum_id='" . $forum_id . "'");
		
		return $query->row['post'];
	}
	
	public function getTotalRead($forum_id) {
		$query = $this->db->query("SELECT COUNT(*) AS view FROM " . DB_PREFIX . "forum_view WHERE forum_id='" . $forum_id . "'");
		
		return $query->row['view'];
	}
	
	public function getTotalForumsAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "forum WHERE status = '0'");
		
		return $query->row['total'];
	}	
}
?>