<?php
class ControllerModuleBlog extends Controller {
	protected function index() {
		$this->language->load('module/blog');

                $this->data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->request->get['blog_id'])) {
//                        var_dump($this->request->get['blog_id']);
			$parts = explode('_', (string)$this->request->get['blog_id']);
//                        var_dump($parts);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$this->data['blog_id'] = $parts[0];
		} else {
			$this->data['blog_id'] = 0;
		}

		if (isset($parts[1])) {
			$this->data['child_id'] = $parts[1];
		} else {
			$this->data['child_id'] = 0;
		}

                $this->load->model('catalog/blog');
		$this->load->model('catalog/record');

		$this->data['blogies'] = array();

		$blogies = $this->model_catalog_blog->getBlogies(0);

		foreach ($blogies as $blog) {

        $blog_info = $this->model_catalog_blog->getBlog($blog['blog_id']);

        $this->load->model('tool/image');

         if ($blog_info) {
             	if ($blog_info['image']) {
				$thumb = $this->model_tool_image->resize($blog_info['image'],$this->config->get('config_image_wishlist_width'), $this->config->get('config_image_wishlist_height'), 1);
			} else {
				$thumb = '';
			}

         }


			$children_data = array();

			$children = $this->model_catalog_blog->getBlogies($blog['blog_id']);

			foreach ($children as $child) {
				$data = array(
					'filter_blog_id'  => $child['blog_id'],
					'filter_sub_blog' => true
				);

				$record_total = $this->model_catalog_record->getTotalRecords($data);


				 $blog_child_info = $this->model_catalog_blog->getBlog($child['blog_id']);



		         if ($blog_child_info) {
		             	if ($blog_child_info['image']) {
						$thumb_child = $this->model_tool_image->resize($blog_child_info['image'], $this->config->get('config_image_wishlist_width'), $this->config->get('config_image_wishlist_height'), 1);
					} else {
						$thumb_child = '';
					}

		         }

				$children_data[] = array(
					'blog_id' => $child['blog_id'],
					'name'        => $child['name'],
					'count'		  => $record_total,
					'thumb' 	  => $thumb_child,
					'href'        => $this->url->link('record/blog', 'blog_id=' . $blog['blog_id'] . '_' . $child['blog_id'])
				);
			}

			$data = array(
				'filter_blog_id'  => $blog['blog_id'],
				'filter_sub_blog' => true
			);

			$record_total = $this->model_catalog_record->getTotalRecords($data);

			$this->data['blogies'][] = array(
				'blog_id' => $blog['blog_id'],
				'name'        => $blog['name'],
				'children'    => $children_data,
				'count'		  => $record_total,
				'meta'		  => $blog['meta_description'],
				'thumb'		  => $thumb,
				'href'        => $this->url->link('record/blog', 'blog_id=' . $blog['blog_id'])
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/blog.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/blog.tpl';
		} else {
			$this->template = 'default/template/module/blog.tpl';
		}

		$this->render();
  	}
}
?>