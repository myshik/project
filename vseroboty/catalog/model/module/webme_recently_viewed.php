<?php
class ModelModuleWebmeRecentlyViewed extends Model {
	public function wrv_getPersonalViewedProducts($prods_ids = array()) {
		$product_data = array();
		if (count($prods_ids) < 1) {
			return ( $res );
		} else {
			foreach($prods_ids as $prod_id) {
				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.product_id = '" . (int)$prod_id . "' AND p.status = '1' AND p.date_available <= NOW() AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
				
				if ($product_query->num_rows) {
					$product_data[] = $product_query->row;
				}
			}
			
			return $product_data;
		}
	}
	
	public function wrv_getOverallViewedProducts($limit=7) {
		$total = 0;
		$product_data = array();
		
		$query = $this->db->query("SELECT SUM(viewed) AS total FROM " . DB_PREFIX . "product");
		$total = $query->row['total'];
		
		if ($limit < 1) {
			$limit = 7;
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY viewed DESC LIMIT 0,".(int)$limit."");
		
		if ($query->num_rows) {
			foreach ($query->rows as $result) {
				if ($result['viewed']) {
					$percent = round(($result['viewed'] / $total) * 100, 2) . '%';
				} else {
					$percent = '0%';
				}
				
				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.product_id = '" . (int)$result['product_id'] . "' AND p.status = '1' AND p.date_available <= NOW() AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
				
				if ($product_query->num_rows) {
					$product_data[] = $product_query->row;
				}
			}
		}
		
		return $product_data;
	}
}
?>