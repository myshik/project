<?php
class ModelCatalogComment extends Model {
	public function addComment($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "comment
		SET author = '" . $this->db->escape($data['author']) . "',
		record_id = '" . $this->db->escape($data['record_id']) . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	}

	public function editComment($comment_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "comment SET
		author = '" . $this->db->escape($data['author']) . "',
		record_id = '" . $this->db->escape($data['record_id']) . "',
		text = '" . $this->db->escape(strip_tags($data['text'])) . "',
		rating = '" . (int)$data['rating'] . "',
		status = '" . (int)$data['status'] . "',
		date_added = '" . $this->db->escape($data['date_available']) . "'
		WHERE comment_id = '" . (int)$comment_id . "'");
	}

	public function deleteComment($comment_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "comment WHERE comment_id = '" . (int)$comment_id . "'");
	}

	public function getComment($comment_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT pd.name FROM " . DB_PREFIX . "record_description pd WHERE pd.record_id = r.record_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS record FROM " . DB_PREFIX . "comment r WHERE r.comment_id = '" . (int)$comment_id . "'");

		return $query->row;
	}

	public function getComments($data = array()) {
		$sql = "SELECT r.comment_id, pd.name, r.author, r.rating, r.status, r.date_added FROM " . DB_PREFIX . "comment r
		LEFT JOIN " . DB_PREFIX . "record_description pd ON (r.record_id = pd.record_id)
		WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'pd.name',
			'r.author',
			'r.rating',
			'r.status',
			'r.date_added'
		);

			if (!empty($data['filter_name'])) {
				$sql .= " AND LCASE(pd.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY r.date_added";
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

	public function getTotalComments() {
        $sql="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comment";


		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalCommentsAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comment WHERE status = '0'");

		return $query->row['total'];
	}
}
?>