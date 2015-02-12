<?php
class ModelCatalogBlog extends Model {
	public function getBlog($blog_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "blog c LEFT JOIN " . DB_PREFIX . "blog_description cd ON (c.blog_id = cd.blog_id) LEFT JOIN " . DB_PREFIX . "blog_to_store c2s ON (c.blog_id = c2s.blog_id) WHERE c.blog_id = '" . (int)$blog_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");

		return $query->row;
	}

	public function getBlogies($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog c LEFT JOIN " . DB_PREFIX . "blog_description cd ON (c.blog_id = cd.blog_id) LEFT JOIN " . DB_PREFIX . "blog_to_store c2s ON (c.blog_id = c2s.blog_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

		return $query->rows;
	}





	public function getBlogiesByParentId($blog_id) {
		$blog_data = array();

		$blog_query = $this->db->query("SELECT blog_id FROM " . DB_PREFIX . "blog WHERE parent_id = '" . (int)$blog_id . "'");

		foreach ($blog_query->rows as $blog) {
			$blog_data[] = $blog['blog_id'];

			$children = $this->getBlogiesByParentId($blog['blog_id']);

			if ($children) {
				$blog_data = array_merge($children, $blog_data);
			}
		}

		return $blog_data;
	}

	public function getBlogLayoutId($blog_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_to_layout WHERE blog_id = '" . (int)$blog_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return $this->config->get('config_layout_blog');
		}
	}

	public function getTotalBlogiesByBlogId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog c LEFT JOIN " . DB_PREFIX . "blog_to_store c2s ON (c.blog_id = c2s.blog_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");

		return $query->row['total'];
	}
}
?>