<?php
class ModelBlogPost extends Model {
	public function addPost($data) {
		//$this->event->trigger('pre.admin.post.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "post SET sort_order = '" . (int)$data['sort_order'] . "', date = '" . $this->db->escape($data['date']) . "', status = '" . (int)$data['status'] . "'");

		$post_id = $this->db->getLastId();

		foreach ($data['post_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "post_description SET post_id = '" . (int)$post_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', summary = '" . $this->db->escape($value['summary']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// SEO URL
		if (isset($data['post_seo_url'])) {
			foreach ($data['post_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (trim($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'post_id=" . (int)$post_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}
        
        if (isset($data['post_image'])) {
			foreach ($data['post_image'] as $post_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "post_image SET post_id = '" . (int)$post_id . "', image = '" . $this->db->escape($post_image['image']) . "', sort_order = '" . (int)$post_image['sort_order'] . "'");
			}
		}
        
		$this->cache->delete('post');

		//$this->event->trigger('post.admin.post.add', $post_id);
        
		return $post_id;
	}

	public function editPost($post_id, $data) {
		//$this->event->trigger('pre.admin.post.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "post SET sort_order = '" . (int)$data['sort_order'] . "', date = '" . $this->db->escape($data['date']) . "', status = '" . (int)$data['status'] . "' WHERE post_id = '" . (int)$post_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "post_description WHERE post_id = '" . (int)$post_id . "'");

		foreach ($data['post_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "post_description SET post_id = '" . (int)$post_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', summary = '" . $this->db->escape($value['summary']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'post_id=" . (int)$post_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'post_id=" . (int)$post_id . "'");

		if (isset($data['post_seo_url'])) {
			foreach ($data['post_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (trim($keyword)) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'post_id=" . (int)$post_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}
        
        $this->db->query("DELETE FROM " . DB_PREFIX . "post_image WHERE post_id = '" . (int)$post_id . "'");

		if (isset($data['post_image'])) {
			foreach ($data['post_image'] as $post_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "post_image SET post_id = '" . (int)$post_id . "', image = '" . $this->db->escape($post_image['image']) . "', sort_order = '" . (int)$post_image['sort_order'] . "'");
			}
		}

		$this->cache->delete('post');

		//$this->event->trigger('post.admin.post.edit', $post_id);
	}

	public function deletePost($post_id) {
		//$this->event->trigger('pre.admin.post.delete', $post_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "post WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_description WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'post_id=" . (int)$post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "post_image WHERE post_id = '" . (int)$post_id . "'");

		$this->cache->delete('post');

		//$this->event->trigger('post.admin.post.delete', $post_id);
	}

	public function getPost($post_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE query = 'post_id=" . (int)$post_id . "') AS keyword FROM " . DB_PREFIX . "post WHERE post_id = '" . (int)$post_id . "'");

		return $query->row;
	}

	public function getPosts($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "post i LEFT JOIN " . DB_PREFIX . "post_description id ON (i.post_id = id.post_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sort_data = array(
				'id.title',
				'i.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
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
		} else {
			$post_data = $this->cache->get('post.' . (int)$this->config->get('config_language_id'));

			if (!$post_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post i LEFT JOIN " . DB_PREFIX . "post_description id ON (i.post_id = id.post_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");

				$post_data = $query->rows;

				$this->cache->set('post.' . (int)$this->config->get('config_language_id'), $post_data);
			}
            
			return $post_data;
		}
	}

	public function getPostDescriptions($post_id) {
		$post_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_description WHERE post_id = '" . (int)$post_id . "'");

		foreach ($query->rows as $result) {
			$post_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'description'      => $result['description'],
                'summary'          => $result['summary'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			);
		}

		return $post_description_data;
	}

    public function getPostImages($post_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_image WHERE post_id = '" . (int)$post_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}
    
	public function getTotalPosts() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "post");

		return $query->row['total'];
	}
    
    public function getPostSeoUrls($post_id) {
		$post_seo_url_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'post_id=" . (int)$post_id . "'");

		foreach ($query->rows as $result) {
			$post_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $post_seo_url_data;
	}
}