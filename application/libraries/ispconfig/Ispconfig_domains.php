<?php
/**
 * codeigniter-ispconfig-new
 *
 * @package  codeigniter-ispconfig-new
 * @author   Natan Felles <natanfelles@gmail.com>
 */
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class Ispconfig_domains
 */
class Ispconfig_domains extends Ispconfig {


	// Todo: domains_update não existe?

	/**
	 * Ispconfig_domains constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Get one record from Client > Domains
	 *
	 * @param int $domain_id
	 *
	 * @return array domain.* or error
	 */
	public function domain_get($domain_id)
	{
		if (is_array($validation = $this->validate_primary_key('domain_id', $domain_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->domains_domain_get($this->ID, $domain_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Client > Domains > Domain
	 *
	 * @param int    $client_id
	 * @param string $domain
	 *
	 * @return int|array domain.domain_id or error
	 */
	public function domain_add($client_id, $domain)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'domain'    => $domain,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				[
					'field' => 'domain',
					'label' => 'domain',
					'rules' => 'trim|required|valid_url',
				],
			);
			$this->CI->form_validation->set_rules($rules);
			if ( ! $this->CI->form_validation->run())
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array('domain' => $domain);

			return $this->SoapClient->domains_domain_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			$errors = $this->get_error($e->getMessage());
			if (trim($errors['error'][0]) == 'domain_error_unique')
			{
				return array(
					'error' => ['domain' => 'The domain field must contain a unique value.'],
				);
			}
			elseif (trim($errors['error'][0]) == 'domain_error_regex')
			{
				return array(
					'error' => ['domain' => 'The domain field is not in the correct format.'],
				);
			}

			return $errors;
		}
	}


	/**
	 * Delete one record in Client > Domains > Domain
	 *
	 * @param int $domain_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function domain_delete($domain_id)
	{
		if (is_array($validation = $this->validate_primary_key('domain_id', $domain_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->domains_domain_delete($this->ID, $domain_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get all records from Client > Domains by group_id
	 *
	 * @param int $group_id
	 *
	 * @return array domain.(domain_id, domain) or error
	 */
	public function domain_get_all_by_user($group_id)
	{
		if (is_array($validation = $this->validate_primary_key('group_id', $group_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->domains_get_all_by_user($this->ID, $group_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

}
