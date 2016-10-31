<?php
/**
 * @package       CodeIgniter
 * @subpackage    ISPConfig
 * @category      Remote API
 * @version       2.0.0
 * @author        Natan Felles
 * @link          http://github.com/natanfelles/codeigniter-ispconfig
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Remote_api
 *
 * This class is an example using ISPConfig 3 Remote API
 */
class Remote_api extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('ispconfig');
	}


	/**
	 * Do your tests here
	 */
	public function index()
	{
		$data['response'] = $this->ispconfig_client->get(1);
		$this->load->view('ispconfig/remote_api', $data);
	}


	public function config()
	{
		$data['response'] = $this->ispconfig->get_config();
		$this->load->view('ispconfig/remote_api', $data);
	}


	public function function_list()
	{

		$data['response'] = $this->ispconfig->get_function_list();
		$this->load->view('ispconfig/remote_api', $data);
	}

}
