<?php
class ModelBlogPost extends Model {
	public function getPost($post_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "post p LEFT JOIN " . DB_PREFIX . "post_description id ON (p.post_id = id.post_id) WHERE p.post_id = '" . (int)$post_id . "' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1'");

		return $query->row;
	}

	public function getPosts() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post p LEFT JOIN " . DB_PREFIX . "post_description id ON (p.post_id = id.post_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' ORDER BY p.date DESC, p.post_id DESC");

		return $query->rows;
	}

    public function getPostImages($post_id){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_image WHERE post_id = '" . (int)$post_id . "'");
        
        return $query->rows;
    }
}