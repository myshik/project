<?php
class ModelToolLorigin extends Model {
	
	public function getRandomProduct($limit_count) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		$q = "		
		SELECT 
			DISTINCT *, 
			pd.name AS name,
			p.image, 
			m.name AS manufacturer, 
			(SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, 
			(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$customer_group_id . "') AS reward, 
			(SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, 
			(SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, 
			(SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews 
		FROM " . DB_PREFIX . "product p 
		LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
		LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
		LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
		WHERE 
			pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND 
			p.status = '1' AND p.date_available <= NOW() AND 
			p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'" .
		"ORDER BY RAND() LIMIT $limit_count";
		
		$query = $this->db->query($q);
		
		if ($query->num_rows) {
			$rows = $query->rows;
			$items = array();
			foreach ($rows as $row) {
				$items[] = array(
					'product_id'       => $row['product_id'],
					'name'             => $row['name'],
					'description'      => $row['description'],
					'meta_description' => $row['meta_description'],
					'meta_keyword'     => $row['meta_keyword'],
					'model'            => $row['model'],
					'sku'              => $row['sku'],
					'upc'              => $row['upc'],
					'location'         => $row['location'],
					'quantity'         => $row['quantity'],
					'stock_status'     => $row['stock_status'],
					'image'            => $row['image'],
					'manufacturer_id'  => $row['manufacturer_id'],
					'manufacturer'     => $row['manufacturer'],
					'price'            => ($row['discount'] ? $row['discount'] : $row['price']),
					'special'          => $row['special'],
					'reward'           => $row['reward'],
					'points'           => $row['points'],
					'tax_class_id'     => $row['tax_class_id'],
					'date_available'   => $row['date_available'],
					'weight'           => $row['weight'],
					'weight_class_id'  => $row['weight_class_id'],
					'length'           => $row['length'],
					'width'            => $row['width'],
					'height'           => $row['height'],
					'length_class_id'  => $row['length_class_id'],
					'subtract'         => $row['subtract'],
					'rating'           => (int)$row['rating'],
					'reviews'          => $row['reviews'],
					'minimum'          => $row['minimum'],
					'sort_order'       => $row['sort_order'],
					'status'           => $row['status'],
					'date_added'       => $row['date_added'],
					'date_modified'    => $row['date_modified'],
					'viewed'           => $row['viewed']
				);
			}
			return $items;
		} else {
			return false;
		}
	}
	
}
?>