<?php
class ModelCatalogTotalblog extends Model {
	public function getRecord($record_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer,   (SELECT price FROM " . DB_PREFIX . "record_discount pd2 WHERE pd2.record_id = p.record_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "record_special ps WHERE ps.record_id = p.record_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "record_reward pr WHERE pr.record_id = p.record_id AND customer_group_id = '" . (int)$customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "comment r1 WHERE r1.record_id = p.record_id AND r1.status = '1' GROUP BY r1.record_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comment r2 WHERE r2.record_id = p.record_id AND r2.status = '1' GROUP BY r2.record_id) AS comments, p.sort_order FROM " . DB_PREFIX . "record p LEFT JOIN " . DB_PREFIX . "record_description pd ON (p.record_id = pd.record_id) LEFT JOIN " . DB_PREFIX . "record_to_store p2s ON (p.record_id = p2s.record_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.record_id = '" . (int)$record_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			$query->row['price'] = ($query->row['discount'] ? $query->row['discount'] : $query->row['price']);
			$query->row['rating'] = (int)$query->row['rating'];

			return $query->row;
		} else {
			return false;
		}
	}

	public function getRecords($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$cache = md5(http_build_query($data));

		$record_data = $this->cache->get('record.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache);

		if (!$record_data) {
			$sql = "SELECT p.record_id, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comment rc WHERE rc.record_id = p.record_id AND rc.status = '1' GROUP BY rc.record_id) AS comments, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "comment r1 WHERE r1.record_id = p.record_id AND r1.status = '1' GROUP BY r1.record_id) AS rating FROM " . DB_PREFIX . "record p LEFT JOIN " . DB_PREFIX . "record_description pd ON (p.record_id = pd.record_id) LEFT JOIN " . DB_PREFIX . "record_to_store p2s ON (p.record_id = p2s.record_id)";

			if (!empty($data['filter_tag'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "record_tag pt ON (p.record_id = pt.record_id)";
			}

			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

			if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
				$sql .= " AND (";

				if (!empty($data['filter_name'])) {
					$implode = array();

					$words = explode(' ', $data['filter_name']);

					foreach ($words as $word) {
						if (!empty($data['filter_description'])) {
							$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' OR LCASE(pd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
						} else {
							$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
						}
					}

					if ($implode) {
						$sql .= " " . implode(" OR ", $implode) . "";
					}
				}

				if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
					$sql .= " OR ";
				}

				if (!empty($data['filter_tag'])) {
					$implode = array();

					$words = explode(' ', $data['filter_tag']);

					foreach ($words as $word) {
						$implode[] = "LCASE(pt.tag) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%' AND pt.language_id = '" . (int)$this->config->get('config_language_id') . "'";
					}

					if ($implode) {
						$sql .= " " . implode(" OR ", $implode) . "";
					}
				}

				$sql .= ")";
			}

			if (!empty($data['filter_manufacturer_id'])) {
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
			}

			$sql .= " GROUP BY p.record_id";

			$sort_data = array(
				'pd.name',
				'p.model',
				'p.quantity',
				'p.price',
				'rating',
				'p.sort_order',
				'p.date_added'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
					$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
				} else {
					$sql .= " ORDER BY " . $data['sort'];
				}
			} else {
				$sql .= " ORDER BY p.sort_order DESC";
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

			$record_data = array();

			$query = $this->db->query($sql);

			foreach ($query->rows as $result) {
				$record_data[$result['record_id']] = $this->getRecord($result['record_id']);
			}

			$this->cache->set('record.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache, $record_data);
		}

		return $record_data;
	}

	public function getLatestRecords($limit) {
		$record_data = $this->cache->get('record.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$limit);

		if (!$record_data) {
			$query = $this->db->query("SELECT p.record_id FROM " . DB_PREFIX . "record p LEFT JOIN " . DB_PREFIX . "record_to_store p2s ON (p.record_id = p2s.record_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$record_data[$result['record_id']] = $this->getRecord($result['record_id']);
			}

			$this->cache->set('record.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$limit, $record_data);
		}

		return $record_data;
	}

	public function getPopularRecords($limit) {
		$record_data = array();

		$query = $this->db->query("SELECT p.record_id FROM " . DB_PREFIX . "record p LEFT JOIN " . DB_PREFIX . "record_to_store p2s ON (p.record_id = p2s.record_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed, p.date_added DESC LIMIT " . (int)$limit);

		foreach ($query->rows as $result) {
			$record_data[$result['record_id']] = $this->getRecord($result['record_id']);
		}

		return $record_data;
	}

	public function getBestSellerRecords($limit) {
		$record_data = $this->cache->get('record.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$limit);

		if (!$record_data) {
			$record_data = array();

			$query = $this->db->query("SELECT op.record_id, COUNT(*) AS total FROM " . DB_PREFIX . "order_record op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "record` p ON (op.record_id = p.record_id) LEFT JOIN " . DB_PREFIX . "record_to_store p2s ON (p.record_id = p2s.record_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.record_id ORDER BY total DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$record_data[$result['record_id']] = $this->getRecord($result['record_id']);
			}

			$this->cache->set('record.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$limit, $record_data);
		}

		return $record_data;
	}



	public function getTotalRecords($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.record_id) AS total FROM " . DB_PREFIX . "record p
		LEFT JOIN " . DB_PREFIX . "record_description pd ON (p.record_id = pd.record_id)
		LEFT JOIN " . DB_PREFIX . "record_to_store p2s ON (p.record_id = p2s.record_id)";

		if (!empty($data['filter_tag'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "record_tag pt ON (p.record_id = pt.record_id)";
		}

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', $data['filter_name']);

				foreach ($words as $word) {
					if (!empty($data['filter_description'])) {
						$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' OR LCASE(pd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
					} else {
						$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
					}
				}

				if ($implode) {
					$sql .= " " . implode(" OR ", $implode) . "";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', $data['filter_tag']);

				foreach ($words as $word) {
					$implode[] = "LCASE(pt.tag) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%' AND pt.language_id = '" . (int)$this->config->get('config_language_id') . "'";
				}

				if ($implode) {
					$sql .= " " . implode(" OR ", $implode) . "";
				}
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}


		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}
?>