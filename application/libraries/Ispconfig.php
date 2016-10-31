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
 * Class Ispconfig
 * This class enables you to use the ISPConfig 3.1 Remote API
 */
class Ispconfig {


	/**
	 * Reference to CodeIgniter instance
	 *
	 * @var CI_Controller
	 */
	protected $CI;

	/**
	 * Defines if will test params with CI Form Validation before start SOAP Session
	 *
	 * @var bool
	 */
	protected $use_form_validation = TRUE;

	/**
	 * SoapClient objects
	 *
	 * @var mixed
	 */
	protected $SoapClient;

	/**
	 * Session ID from SoapClient Login
	 *
	 * @var string
	 */
	protected $ID;


	/**
	 * Ispconfig constructor
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->config->load('ispconfig');
		$this->CI->load->helper('language');
		$this->CI->load->language('ispconfig');
		$this->CI->load->library('form_validation');
		$this->CI->load->library('ispconfig/ispconfig_billing');
		$this->CI->load->library('ispconfig/ispconfig_client');
		$this->CI->load->library('ispconfig/ispconfig_dns');
		$this->CI->load->library('ispconfig/ispconfig_domains');
		$this->CI->load->library('ispconfig/ispconfig_mail');
		$this->CI->load->library('ispconfig/ispconfig_openvz');
		$this->CI->load->library('ispconfig/ispconfig_server');
		$this->CI->load->library('ispconfig/ispconfig_sites');
		$this->use_form_validation = $this->CI->config->item('ispconfig_use_form_validation');
	}


	/**
	 * Start the Remote Session
	 */
	protected function login()
	{
		if ($this->CI->config->item('ispconfig_verify_ssl') == FALSE)
		{
			$context = stream_context_create(array(
				'ssl' => array(
					'verify_peer'       => FALSE,
					'verify_peer_name'  => FALSE,
					'allow_self_signed' => TRUE,
				),
			));
		}
		else
		{
			$context = NULL;
		}

		$this->SoapClient = new SoapClient(NULL, array(
			'location'       => $this->CI->config->item('ispconfig_soap_location'),
			'uri'            => $this->CI->config->item('ispconfig_soap_uri'),
			'trace'          => 1,
			'exceptions'     => 1,
			'stream_context' => $context,
		));
		if ($this->ID = $this->SoapClient->login($this->CI->config->item('ispconfig_username'), $this->CI->config->item('ispconfig_password')))
		{
			log_message('info', 'ISPConfig: Logged successfull. Session ID: ' . $this->ID);
		}
	}


	/**
	 * Get no results response
	 *
	 * @param null $response The Client Object Function
	 *
	 * @return mixed $response
	 */
	protected function get_empty($response = NULL)
	{
		if ($response == '' or $response == [])
		{
			$response = [];
		}

		return $response;
	}


	/**
	 * Get errors
	 *
	 * @param null $error The SOAP Message
	 *
	 * @return mixed $reponse
	 */
	protected function get_error($error = NULL)
	{
		log_message('error', 'ISPConfig: ' . $error);

		$errors = strip_tags($error);
		$errors = explode("\n", $errors);
		array_pop($errors);
		$response['error'] = [$error];
		for ($i = 0; $i < count($errors); $i++)
		{
			$response['error'][$i] = lang(trim($errors[$i]));
			if ($response['error'][$i] == '')
			{
				$response['error'][$i] = $errors[$i];
			}
		}

		return $response;
	}


	/**
	 * Update sys_perm_user in any table
	 *
	 * @internal
	 *
	 * @param string $tablename
	 * @param string $index_field
	 * @param string $index_value
	 * @param string $permissions
	 *
	 * @return bool|array TRUE or error
	 */
	public function update_record_permissions($tablename = '', $index_field = '', $index_value = '', $permissions = 'riud')
	{
		try
		{
			$this->login();
			$this->SoapClient->update_record_permissions($this->ID, $tablename, $index_field, $index_value, $permissions);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get all records from available Remote API Functions
	 *
	 * @return array
	 */
	public function get_function_list()
	{
		try
		{
			$this->login();

			return $this->SoapClient->get_function_list($this->ID);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get all details from Config File
	 *
	 * @return array
	 */
	public function get_config()
	{
		if ($this->CI->config->item('ispconfig_development_mode') == TRUE)
		{
			$data['username'] = $this->CI->config->item('ispconfig_username');
			$data['password'] = $this->CI->config->item('ispconfig_password');
			$data['soap_uri'] = $this->CI->config->item('ispconfig_soap_uri');
			$data['soap_location'] = $this->CI->config->item('ispconfig_soap_location');
			$data['invoices_dir'] = $this->CI->config->item('ispconfig_invoices_dir');
			$data['verify_ssl'] = $this->CI->config->item('ispconfig_verify_ssl');
		}
		else
		{
			$data = ['info' => 'Development Mode is FALSE'];
		}

		return $data;
	}


	/**
	 * Destructor - Close the Remote Session
	 */
	public function __destruct()
	{
		if ($this->ID)
		{
			$this->SoapClient->logout($this->ID);
			log_message('info', 'ISPConfig: Logged out.');
		}
	}


	/**
	 * Prepare array to validate Primary Keys · INT(11) UNSIGNED
	 *
	 * @param string $field_name
	 *
	 * @return array
	 */
	protected function prepare_validate_pk($field_name)
	{
		return [
			'field' => $field_name,
			'label' => $field_name,
			'rules' => 'trim|required|integer|max_length[11]|greater_than[0]',
		];
	}


	/**
	 * Validate Table Primary Key · INT(11) UNSIGNED
	 *
	 * @param string $field_name
	 * @param mixed  $value
	 *
	 * @return array|string Array if has error
	 */
	protected function validate_primary_key($field_name, $value)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([$field_name => $value]);
			$this->CI->form_validation->set_rules([$this->prepare_validate_pk($field_name)]);
			if ( ! $this->CI->form_validation->run())
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}

		return 'success';
	}

}
