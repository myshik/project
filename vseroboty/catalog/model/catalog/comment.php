<?php
class ModelCatalogComment extends Model {
	public function addComment($record_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "comment SET
		author = '" . $this->db->escape($data['name']) . "',
		customer_id = '" . (int)$this->customer->getId() . "',
		record_id = '" . (int)$record_id . "',
		text = '" . $this->db->escape(strip_tags($data['text'])) . "',
		status = '" . $this->db->escape(strip_tags($data['status'])) . "',
		rating = '" . (int)$data['rating'] . "', date_added = NOW()");
	}

	public function getCommentsByRecordId($record_id, $start = 0, $limit = 20) {
		$query = $this->db->query("SELECT r.comment_id, r.author, r.rating, r.text, p.record_id, pd.name, p.price, p.image, r.date_added FROM " . DB_PREFIX . "comment r LEFT JOIN " . DB_PREFIX . "record p ON (r.record_id = p.record_id) LEFT JOIN " . DB_PREFIX . "record_description pd ON (p.record_id = pd.record_id) WHERE p.record_id = '" . (int)$record_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getAverageRating($record_id) {
		$query = $this->db->query("SELECT AVG(rating) AS total FROM " . DB_PREFIX . "comment WHERE status = '1' AND record_id = '" . (int)$record_id . "' GROUP BY record_id");

		if (isset($query->row['total'])) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}

	public function getTotalComments() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comment r LEFT JOIN " . DB_PREFIX . "record p ON (r.record_id = p.record_id) WHERE p.date_available <= NOW() AND p.status = '1' AND r.status = '1'");

		return $query->row['total'];
	}

	public function getTotalCommentsByRecordId($record_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comment r LEFT JOIN " . DB_PREFIX . "record p ON (r.record_id = p.record_id) LEFT JOIN " . DB_PREFIX . "record_description pd ON (p.record_id = pd.record_id) WHERE p.record_id = '" . (int)$record_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}
}
?>