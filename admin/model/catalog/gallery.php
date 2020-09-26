<?php

class ModelCatalogGallery extends Model {

	public function addGallery($data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "gallery SET  status = '" . $this->db->escape($data['status']) . "', sort_order = '" . (int)$data['sort_order'] . "'");

		$gallery_id = $this->db->getLastId();

		if (isset($data['image'])) {

			$this->db->query("UPDATE " . DB_PREFIX . "gallery SET image = '" . $this->db->escape($data['image']) . "' WHERE gallery_id = '" . (int)$gallery_id . "'");

		}

		foreach ($data['gallery_description'] as $language_id => $value) {

			$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_description SET gallery_id = '" . (int)$gallery_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");

		}

		$this->cache->delete('gallery');

		return $gallery_id;

	}

	public function getGalleryDescriptions($gallery_id) {
		$gallery_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_description WHERE gallery_id = '" . (int)$gallery_id . "'");

		foreach ($query->rows as $result) {
			$gallery_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			);
		}

		return $gallery_description_data;
	}

	public function editGallery($gallery_id, $data) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "gallery SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "' WHERE gallery_id = '" . (int)$gallery_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_description WHERE gallery_id = '" . (int)$gallery_id . "'");
		
		foreach ($data['gallery_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_description SET gallery_id = '" . (int)$gallery_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}
		if (isset($data['image']) || $data['image'] != null || $data['image'] != '') {

			$this->db->query("UPDATE " . DB_PREFIX . "gallery SET image = '" . $this->db->escape($data['image']) . "' WHERE gallery_id = '" . (int)$gallery_id . "'");

		}
		$this->cache->delete('gallery');

	}


	public function deleteGallery($gallery_id) {

		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery WHERE gallery_id = '" . (int)$gallery_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_description WHERE gallery_id = '" . (int)$gallery_id . "'");
		$this->cache->delete('gallery');

	}

	public function getBranch($gallery_id) {

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gallery WHERE gallery_id = '" . (int)$gallery_id . "'");
		return $query->row;

	}

	public function getBranchs($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "gallery i LEFT JOIN " . DB_PREFIX . "gallery_description id ON (i.gallery_id = id.gallery_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";
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

	}


	public function getTotalBranchs() {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gallery");



		return $query->row['total'];

	}

}

