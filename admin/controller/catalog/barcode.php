<?php
class ControllerCatalogBarcode extends Controller {
	private $error = array();

	public function index() {

		// ##############################
		$filetype = 'PNG';
		$dpi = 72;
		$rotation = 0;
		$font_family = 'Arial.ttf';
		$font_size = 11;
		$scale = 2.5;
		$thickness=30;
		$cid = '';
		$scid='';
		$cname = '';
		$sname='';
		define('IN_CB', true);
		if (!defined('IN_CB')) { die('You are not allowed to access to this page.'); }

		if (version_compare(phpversion(), '5.0.0', '>=') !== true) {
			exit('Sorry, but you have to run this script with PHP5... You currently have the version <b>' . phpversion() . '</b>.');
		}

		if (!function_exists('imagecreate')) {
			exit('Sorry, make sure you have the GD extension installed before running this script.');
		}

		include_once('../vendor/barcodegen/html/include/function.php');

		// FileName & Extension
		$system_temp_array = explode('/', $_SERVER['PHP_SELF']);
		$filename = $system_temp_array[count($system_temp_array) - 1];
		$system_temp_array2 = explode('.', $filename);
		$availableBarcodes = listBarcodes();
		$barcodeName = findValueFromKey($availableBarcodes, $filename);
		$code = $system_temp_array2[0];

		// Check if the code is valid
		if (file_exists('config' . DIRECTORY_SEPARATOR . $code . '.php')) {
			include_once('config' . DIRECTORY_SEPARATOR . $code . '.php');
		}

		$default_value['start'] = '';
		$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : $default_value['start'];
		registerImageKey('start', $start);
		registerImageKey('code', 'BCGcode128');
    
        $default_value = array();
        $default_value['filetype'] = //$filetype;
        $default_value['dpi'] = $dpi;
        // $default_value['scale'] = isset($defaultScale) ? $defaultScale : 1;
        $default_value['rotation'] = $rotation;
        $default_value['font_family'] = $font_family;
        $default_value['font_size'] = $font_size;
        $default_value['scale'] = $scale;
        $default_value['text'] = '';
        $default_value['thickness']=$thickness;
        $default_value['a1'] = '';
        $default_value['a2'] = '';
        $default_value['a3'] = '';

        $filetype = isset($_REQUEST['filetype']) ? $_REQUEST['filetype'] : $default_value['filetype'];
        $dpi = isset($_REQUEST['dpi']) ? $_REQUEST['dpi'] : $default_value['dpi'];
        $scale = intval(isset($_REQUEST['scale']) ? $_REQUEST['scale'] : $default_value['scale']);
        $rotation = intval(isset($_REQUEST['rotation']) ? $_REQUEST['rotation'] : $default_value['rotation']);
        $font_family = isset($_REQUEST['font_family']) ? $_REQUEST['font_family'] : $default_value['font_family'];
        $font_size = intval(isset($_REQUEST['font_size']) ? $_REQUEST['font_size'] : $default_value['font_size']);
        $text = isset($_REQUEST['text']) ? $_REQUEST['text'] : $default_value['text'];

        registerImageKey('filetype', $filetype);
        registerImageKey('dpi', $dpi);
        registerImageKey('scale', $scale);
        registerImageKey('rotation', $rotation);
        registerImageKey('font_family', $font_family);
        registerImageKey('font_size', $font_size);
        registerImageKey('text', stripslashes($text));

        // Text in form is different than text sent to the image
        $text = convertText($text);
        // start
        $default_value['start'] = '';
        $start = isset($_GET['start']) ? $_GET['start'] : $default_value['start'];
        registerImageKey('start', $start);
        registerImageKey('code', 'BCGcode128');

        $vals = array();
        for($i = 0; $i <= 127; $i++) {
            $vals[] = '%' . sprintf('%02X', $i);
        }
        $characters = array(
            'NUL', 'SOH', 'STX', 'ETX', 'EOT', 'ENQ', 'ACK', 'BEL', 'BS', 'TAB', 'LF', 'VT', 'FF', 'CR', 'SO', 'SI', 'DLE', 'DC1', 'DC2', 'DC3', 'DC4', 'NAK', 'SYN', 'ETB', 'CAN', 'EM', 'SUB', 'ESC', 'FS', 'GS', 'RS', 'US',
            '&nbsp;', '!', '"', '#', '$', '%', '&', '\'', '(', ')', '*', '+', ',', '-', '.', '/', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ':', ';', '<', '=', '>', '?',
            '@', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '[', '\\', ']', '^', '_',
            '`', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '{', '|', '}', '~', 'DEL'
        );

    	if (!defined('IN_CB')) { die('You are not allowed to access to this page.'); }
		// ###########################################
		
		$this->load->language('catalog/barcode');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/barcode');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		$this->getList();
	}
	
	protected function getList() {
		$data['barcode_url'] = "../../../vendor/barcodegen/html/image.php?filetype=PNG&dpi=72&scale=2&rotation=0&font_family=Arial.ttf&font_size=11&start=NULL&code=BCGcode128";//"../../../vendor/barcodegen/html/image.php?filetype=PNG&dpi=72&scale=2&rotation=0&font_family=Arial.ttf&font_size=11&text=345435345&start=NULL&code=BCGcode128";
		$data['user_token'] = $this->session->data['user_token'];
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = '';
		}

		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = '';
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
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

		
		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_model'	  => $filter_model,
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
		$results = $this->model_catalog_product->getProducts($filter_data);
		
		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$special = false;

			$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

			foreach ($product_specials  as $product_special) {
				if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
					$special = $this->currency->format($product_special['price'], $this->config->get('config_currency'));
					break;
				}
			}

			$data['products'][] = array(
				'product_id' => $result['product_id'],
				'image'      => $image,
				'name'       => $result['name'],
				'upc'       => $result['upc'],
				'model'      => $result['model'],
				'price'      => $this->currency->format($result['price'], $this->config->get('config_currency')),
				'special'    => $special,
				'quantity'   => $result['quantity'],
				'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'href'       => 'index.php?route=product/product&product_id='.$result['product_id']
			);
		}

		// print_r($data['products']);

		$data['filter_name'] = $filter_name;
		$data['filter_model'] = $filter_model;
		$data['filter_status'] = $filter_status;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/barcode_list', $data));
	}


	public function print_barcode() {

		// ##############################
		$filetype = 'PNG';
		$dpi = 72;
		$rotation = 0;
		$font_family = 'Arial.ttf';
		$font_size = 11;
		$scale = 2.5;
		$thickness=30;
		$cid = '';
		$scid='';
		$cname = '';
		$sname='';
		define('IN_CB', true);
		if (!defined('IN_CB')) { die('You are not allowed to access to this page.'); }

		if (version_compare(phpversion(), '5.0.0', '>=') !== true) {
			exit('Sorry, but you have to run this script with PHP5... You currently have the version <b>' . phpversion() . '</b>.');
		}

		if (!function_exists('imagecreate')) {
			exit('Sorry, make sure you have the GD extension installed before running this script.');
		}

		include_once('../vendor/barcodegen/html/include/function.php');

		// FileName & Extension
		$system_temp_array = explode('/', $_SERVER['PHP_SELF']);
		$filename = $system_temp_array[count($system_temp_array) - 1];
		$system_temp_array2 = explode('.', $filename);
		$availableBarcodes = listBarcodes();
		$barcodeName = findValueFromKey($availableBarcodes, $filename);
		$code = $system_temp_array2[0];

		// Check if the code is valid
		if (file_exists('config' . DIRECTORY_SEPARATOR . $code . '.php')) {
			include_once('config' . DIRECTORY_SEPARATOR . $code . '.php');
		}

		$default_value['start'] = '';
		$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : $default_value['start'];
		registerImageKey('start', $start);
		registerImageKey('code', 'BCGcode128');
    
        $default_value = array();
        $default_value['filetype'] = //$filetype;
        $default_value['dpi'] = $dpi;
        // $default_value['scale'] = isset($defaultScale) ? $defaultScale : 1;
        $default_value['rotation'] = $rotation;
        $default_value['font_family'] = $font_family;
        $default_value['font_size'] = $font_size;
        $default_value['scale'] = $scale;
        $default_value['text'] = '';
        $default_value['thickness']=$thickness;
        $default_value['a1'] = '';
        $default_value['a2'] = '';
        $default_value['a3'] = '';

        $filetype = isset($_REQUEST['filetype']) ? $_REQUEST['filetype'] : $default_value['filetype'];
        $dpi = isset($_REQUEST['dpi']) ? $_REQUEST['dpi'] : $default_value['dpi'];
        $scale = intval(isset($_REQUEST['scale']) ? $_REQUEST['scale'] : $default_value['scale']);
        $rotation = intval(isset($_REQUEST['rotation']) ? $_REQUEST['rotation'] : $default_value['rotation']);
        $font_family = isset($_REQUEST['font_family']) ? $_REQUEST['font_family'] : $default_value['font_family'];
        $font_size = intval(isset($_REQUEST['font_size']) ? $_REQUEST['font_size'] : $default_value['font_size']);
        $text = isset($_REQUEST['text']) ? $_REQUEST['text'] : $default_value['text'];

        registerImageKey('filetype', $filetype);
        registerImageKey('dpi', $dpi);
        registerImageKey('scale', $scale);
        registerImageKey('rotation', $rotation);
        registerImageKey('font_family', $font_family);
        registerImageKey('font_size', $font_size);
        registerImageKey('text', stripslashes($text));

        // Text in form is different than text sent to the image
        $text = convertText($text);
        // start
        $default_value['start'] = '';
        $start = isset($_GET['start']) ? $_GET['start'] : $default_value['start'];
        registerImageKey('start', $start);
        registerImageKey('code', 'BCGcode128');

        $vals = array();
        for($i = 0; $i <= 127; $i++) {
            $vals[] = '%' . sprintf('%02X', $i);
        }
        $characters = array(
            'NUL', 'SOH', 'STX', 'ETX', 'EOT', 'ENQ', 'ACK', 'BEL', 'BS', 'TAB', 'LF', 'VT', 'FF', 'CR', 'SO', 'SI', 'DLE', 'DC1', 'DC2', 'DC3', 'DC4', 'NAK', 'SYN', 'ETB', 'CAN', 'EM', 'SUB', 'ESC', 'FS', 'GS', 'RS', 'US',
            '&nbsp;', '!', '"', '#', '$', '%', '&', '\'', '(', ')', '*', '+', ',', '-', '.', '/', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ':', ';', '<', '=', '>', '?',
            '@', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '[', '\\', ']', '^', '_',
            '`', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '{', '|', '}', '~', 'DEL'
        );

    	if (!defined('IN_CB')) { die('You are not allowed to access to this page.'); }
		// ###########################################
		
		$this->load->language('catalog/barcode');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/barcode');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		$this->getListPrint();
	}
	
	protected function getListPrint() {
		$data['barcode_url'] = "../../../vendor/barcodegen/html/image.php?filetype=PNG&dpi=72&scale=2&rotation=0&font_family=Arial.ttf&font_size=11&start=NULL&code=BCGcode128";//"../../../vendor/barcodegen/html/image.php?filetype=PNG&dpi=72&scale=2&rotation=0&font_family=Arial.ttf&font_size=11&text=345435345&start=NULL&code=BCGcode128";
		$data['user_token'] = $this->session->data['user_token'];
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = '';
		}

		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = '';
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
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

		
		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_model'	  => $filter_model,
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
		$results = $this->model_catalog_product->getProducts($filter_data);
		
		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$special = false;

			$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

			foreach ($product_specials  as $product_special) {
				if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
					$special = $this->currency->format($product_special['price'], $this->config->get('config_currency'));
					break;
				}
			}

			$data['products'][] = array(
				'product_id' => $result['product_id'],
				'image'      => $image,
				'name'       => $result['name'],
				'upc'       => $result['upc'],
				'model'      => $result['model'],
				'price'      => $this->currency->format($result['price'], $this->config->get('config_currency')),
				'special'    => $special,
				'quantity'   => $result['quantity'],
				'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'href'       => 'index.php?route=product/product&product_id='.$result['product_id']
			);
		}

		// print_r($data['products']);

		$data['filter_name'] = $filter_name;
		$data['filter_model'] = $filter_model;
		$data['filter_status'] = $filter_status;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/barcode_print', $data));
	}

}
