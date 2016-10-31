<?php
/**
 * codeigniter-ispconfig-new
 *
 * @package  codeigniter-ispconfig-new
 * @author   Natan Felles <natanfelles@gmail.com>
 */
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class Ispconfig_server
 */
class Ispconfig_server extends Ispconfig {


	/**
	 * Ispconfig_server constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Get one record from System > Server Config
	 *
	 * @param int    $server_id
	 * @param string $section   permissions, ids, systemcheck, global, server, mail, getmail, web,
	 *                          dns, fastcgi, jailkit, vlogger, cron, rescue, xmpp
	 *
	 * @return array server.config or error
	 */
	public function get($server_id, $section = '')
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'server_id' => $server_id,
				'section'   => $section,
			]);
			$rules = array(
				$this->prepare_validate_pk('server_id'),
				[
					'field' => 'section',
					'label' => 'section',
					'rules' => 'trim|in_list[permissions,ids,systemcheck,global,server,mail,getmail,web,dns,fastcgi,jailkit,vlogger,cron,rescue,xmpp]',
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

			return $this->SoapClient->server_get($this->ID, $server_id, $section);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get all records from System > Server Config
	 *
	 * @return array server.(server_id, server_name) or error
	 */
	public function get_all()
	{
		try
		{
			$this->login();

			return $this->SoapClient->server_get_all($this->ID);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get server_id by server_name
	 *
	 * @param string $server_name
	 *
	 * @return array server.server_id or error
	 */
	public function get_serverid_by_name($server_name)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['server_name' => $server_name]);
			$rules = array(
				[
					'field' => 'server_name',
					'label' => 'server_name',
					'rules' => 'trim|valid_url',
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

			return $this->get_empty($this->SoapClient->server_get_serverid_by_name($this->ID, $server_name));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get the server_id by server_ip.ip_address
	 *
	 * @param string $ipaddress
	 *
	 * @return array server_ip.server_id or error
	 */
	public function get_serverid_by_ip($ipaddress)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['ipaddress' => $ipaddress]);
			$rules = array(
				[
					'field' => 'ipaddress',
					'label' => 'ipaddress',
					'rules' => 'trim|valid_ip',
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

			return $this->get_empty($this->SoapClient->server_get_serverid_by_ip($this->ID, $ipaddress));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from System > Server Services
	 *
	 * @param int $server_id
	 *
	 * @return array server.(mail_server, web_server, dns_server, file_server, db_server,
	 *               vserver_server, proxy_server, firewall_server) or error
	 */
	public function get_functions($server_id)
	{
		if (is_array($validation = $this->validate_primary_key('server_id', $server_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->server_get_functions($this->ID, $server_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from System > Server IP adresses
	 *
	 * @param int $server_ip_id
	 *
	 * @return array server_ip.* or error
	 */
	public function ip_get($server_ip_id)
	{
		if (is_array($validation = $this->validate_primary_key('server_ip_id', $server_ip_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->server_ip_get($this->ID, $server_ip_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on System > Server IP adresses
	 *
	 * @param int   $client_id
	 * @param array $params    server_id, client_id, ip_type, ip_address, virtualhost,
	 *                         virtualhost_port
	 *
	 * @return int|array server_ip.server_ip_id or error
	 */
	public function ip_add($client_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'params'    => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('params[server_id]'),
				[
					'field' => 'params[ip_type]',
					'label' => 'params[ip_type]',
					'rules' => 'trim|in_list[IPv4,IPv6]',
				],
				[
					'field' => 'params[ip_address]',
					'label' => 'params[ip_address]',
					'rules' => 'trim|required|valid_ip',
				],
				[
					'field' => 'params[virtualhost]',
					'label' => 'params[virtualhost]',
					'rules' => 'trim|in_list[n,y]',
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
			$params = array(
				'server_id'        => isset($params['server_id']) ? $params['server_id'] : 0,
				'client_id'        => $client_id,
				'ip_type'          => isset($params['ip_type']) ? $params['ip_type'] : 'IPv4',
				'ip_address'       => isset($params['ip_address']) ? $params['ip_address'] : NULL,
				'virtualhost'      => isset($params['virtualhost']) ? $params['virtualhost'] : 'y',
				'virtualhost_port' => isset($params['virtualhost_port']) ? $params['virtualhost_port'] : '80,443',
			);

			// Todo: Debug: always given the error "ip_error_wrong"
			return $this->SoapClient->server_ip_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in System > Server IP adresses
	 *
	 * @param int   $client_id
	 * @param int   $server_ip_id
	 * @param array $params server_id, client_id, ip_type, ip_address, virtualhost,
	 *                      virtualhost_port
	 *
	 * @return bool|array TRUE or error
	 */
	public function ip_update($client_id, $server_ip_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('server_ip_id'),
				$this->prepare_validate_pk('params[server_id]'),
				[
					'field' => 'params[ip_type]',
					'label' => 'params[ip_type]',
					'rules' => 'trim|in_list[IPv4,IPv6]',
				],
				[
					'field' => 'params[ip_address]',
					'label' => 'params[ip_address]',
					'rules' => 'trim|required|valid_ip',
				],
				[
					'field' => 'params[virtualhost]',
					'label' => 'params[virtualhost]',
					'rules' => 'trim|in_list[n,y]',
				],
			);
			$this->CI->form_validation->set_data([
				'client_id'    => $client_id,
				'server_ip_id' => $server_ip_id,
				'params'       => $params,
			]);
			$this->CI->form_validation->set_rules($rules);
			if ( ! $this->CI->form_validation->run())
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->server_ip_update($this->ID, $client_id, $server_ip_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in System > Server IP adresses
	 *
	 * @param int $server_ip_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function ip_delete($server_ip_id)
	{
		if (is_array($validation = $this->validate_primary_key('server_ip_id', $server_ip_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->server_ip_delete($this->ID, $server_ip_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

}
