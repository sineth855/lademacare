<?php

class ControllerCatalogGallery extends Controller {

	private $error = array();

	public function index() {

		$this->load->language('catalog/gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/gallery');

		$this->getList();

	}

	public function add() {

		$this->load->language('catalog/gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/gallery');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			print_r($this->request->post);
			$this->model_catalog_gallery->addGallery($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {

				$url .= '&sort=' . $this->request->get['sort'];

			}

			if (isset($this->request->get['order'])) {

				$url .= '&order=' . $this->request->get['order'];

			}

			if (isset($this->request->get['page'])) {

				$url .= '&page=' . $this->request->get['page'];

			}

			$this->response->redirect($this->url->link('catalog/gallery', 'user_token=' . $this->session->data['user_token'] . $url, true));

		}

		$this->getForm();

	}

	public function edit() {

		$this->load->language('catalog/gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/gallery');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			// print_r($this->request->post);

			$this->model_catalog_gallery->editGallery($this->request->get['gallery_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {

				$url .= '&sort=' . $this->request->get['sort'];

			}

			if (isset($this->request->get['order'])) {

				$url .= '&order=' . $this->request->get['order'];

			}

			if (isset($this->request->get['page'])) {

				$url .= '&page=' . $this->request->get['page'];

			}

			$this->response->redirect($this->url->link('catalog/gallery', 'user_token=' . $this->session->data['user_token'] . $url, true));

		}

		$this->getForm();

	}

	public function delete() {

		$this->load->language('catalog/gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/gallery');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {

			foreach ($this->request->post['selected'] as $branch_id) {

				$this->model_catalog_gallery->deleteGallery($branch_id);

			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {

				$url .= '&sort=' . $this->request->get['sort'];

			}

			if (isset($this->request->get['order'])) {

				$url .= '&order=' . $this->request->get['order'];

			}

			if (isset($this->request->get['page'])) {

				$url .= '&page=' . $this->request->get['page'];

			}

			$this->response->redirect($this->url->link('catalog/gallery', 'user_token=' . $this->session->data['user_token'] . $url, true));

		}

		$this->getList();

	}

	protected function getList() {

		if (isset($this->request->get['sort'])) {

			$sort = $this->request->get['sort'];

		} else {

			$sort = 'name';

		}

		if (isset($this->request->get['order'])) {

			$order = $this->request->get['order'];

		} else {

			$order = 'ASC';

		}

		if (isset($this->request->get['page'])) {

			$page = $this->request->get['page'];

		} else {

			$page = 1;

		}

		$url = '';

		if (isset($this->request->get['sort'])) {

			$url .= '&sort=' . $this->request->get['sort'];

		}

		if (isset($this->request->get['order'])) {

			$url .= '&order=' . $this->request->get['order'];

		}

		if (isset($this->request->get['page'])) {

			$url .= '&page=' . $this->request->get['page'];

		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(

			'text' => $this->language->get('text_home'),

			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)

		);

		$data['breadcrumbs'][] = array(

			'text' => $this->language->get('heading_title'),

			'href' => $this->url->link('catalog/gallery', 'user_token=' . $this->session->data['user_token'] . $url, true)

		);

		$data['add'] = $this->url->link('catalog/gallery/add', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['delete'] = $this->url->link('catalog/gallery/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['branchs'] = array();

		$filter_data = array(

			'sort'  => $sort,

			'order' => $order,

			'start' => ($page - 1) * $this->config->get('config_limit_admin'),

			'limit' => $this->config->get('config_limit_admin')

		);

		$branch_total = $this->model_catalog_gallery->getTotalBranchs();

		$results = $this->model_catalog_gallery->getBranchs($filter_data);

		foreach ($results as $result) {

			$data['galleries'][] = array(

				'gallery_id' => $result['gallery_id'],

				'title'            => $result['title'],

				'sort_order'      => $result['sort_order'],

				'edit'            => $this->url->link('catalog/gallery/edit', 'user_token=' . $this->session->data['user_token'] . '&gallery_id=' . $result['gallery_id'] . $url, true)

			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');

		$data['column_sort_order'] = $this->language->get('column_sort_order');

		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');

		$data['button_edit'] = $this->language->get('button_edit');

		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {

			$data['error_warning'] = $this->error['warning'];

		} else {

			$data['error_warning'] = '';

		}

		if (isset($this->session->data['success'])) {

			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);

		} else {

			$data['success'] = '';

		}

		if (isset($this->request->post['selected'])) {

			$data['selected'] = (array)$this->request->post['selected'];

		} else {

			$data['selected'] = array();

		}

		$url = '';

		if ($order == 'ASC') {

			$url .= '&order=DESC';

		} else {

			$url .= '&order=ASC';

		}

		if (isset($this->request->get['page'])) {

			$url .= '&page=' . $this->request->get['page'];

		}

		$data['sort_name'] = $this->url->link('catalog/gallery', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);

		$data['sort_sort_order'] = $this->url->link('catalog/gallery', 'user_token=' . $this->session->data['user_token'] . '&sort=sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {

			$url .= '&sort=' . $this->request->get['sort'];

		}

		if (isset($this->request->get['order'])) {

			$url .= '&order=' . $this->request->get['order'];

		}

		$pagination = new Pagination();

		$pagination->total = $branch_total;

		$pagination->page = $page;

		$pagination->limit = $this->config->get('config_limit_admin');

		$pagination->url = $this->url->link('catalog/gallery', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($branch_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($branch_total - $this->config->get('config_limit_admin'))) ? $branch_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $branch_total, ceil($branch_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;

		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');

		$data['column_left'] = $this->load->controller('common/column_left');

		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/gallery_list', $data));

	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['gallery_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}

		if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = array();
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/gallery', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['gallery_id'])) {
			$data['action'] = $this->url->link('catalog/gallery/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/gallery/edit', 'user_token=' . $this->session->data['user_token'] . '&gallery_id=' . $this->request->get['gallery_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/gallery', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['gallery_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$information_info = $this->model_catalog_gallery->getBranch($this->request->get['gallery_id']);
			
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['gallery_description'])) {
			$data['gallery_description'] = $this->request->post['gallery_description'];
		} elseif (isset($this->request->get['gallery_id'])) {
			$data['gallery_description'] = $this->model_catalog_gallery->getGalleryDescriptions($this->request->get['gallery_id']);
		} else {
			$data['gallery_description'] = array();
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($information_info)) {
			$data['status'] = $information_info['status'];
		} else {
			$data['status'] = true;
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($information_info) && is_file(DIR_IMAGE . $information_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($information_info['image'], 100, 100);
			$data['image'] = $information_info['image'];
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($information_info)) {
			$data['sort_order'] = $information_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}
	

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/gallery_form', $data));
	}

	protected function validateForm() {

		if (!$this->user->hasPermission('modify', 'catalog/gallery')) {

			$this->error['warning'] = $this->language->get('error_permission');

		}

		foreach ($this->request->post['gallery_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 1) || (utf8_strlen($value['title']) > 255)) {
				$this->error['title'][$language_id] = $this->language->get('error_name');
			}

			if ((utf8_strlen($value['meta_title']) < 1) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}
		}	

		return !$this->error;

	}

	protected function validateDelete() {

		if (!$this->user->hasPermission('modify', 'catalog/gallery')) {

			$this->error['warning'] = $this->language->get('error_permission');

		}



		$this->load->model('catalog/product');



		foreach ($this->request->post['selected'] as $branch_id) {

			// $product_total = $this->model_catalog_product->getTotalProductsBybranchId($branch_id);



			// if ($product_total) {

			// 	$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);

			// }

		}



		return !$this->error;

	}

	public function autocomplete() {

		$json = array();



		if (isset($this->request->get['filter_name'])) {

			$this->load->model('catalog/gallery');



			$filter_data = array(

				'filter_name' => $this->request->get['filter_name'],

				'start'       => 0,

				'limit'       => 5

			);



			$results = $this->model_catalog_gallery->getBranchs($filter_data);



			foreach ($results as $result) {

				$json[] = array(

					'branch_id' => $result['branch_id'],

					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))

				);

			}

		}



		$sort_order = array();



		foreach ($json as $key => $value) {

			$sort_order[$key] = $value['name'];

		}



		array_multisort($sort_order, SORT_ASC, $json);



		$this->response->addHeader('Content-Type: application/json');

		$this->response->setOutput(json_encode($json));

	}

}