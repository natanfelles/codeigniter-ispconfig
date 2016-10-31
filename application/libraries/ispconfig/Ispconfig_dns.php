<?php
/**
 * codeigniter-ispconfig-new
 *
 * @package  codeigniter-ispconfig-new
 * @author   Natan Felles <natanfelles@gmail.com>
 */
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class Ispconfig_dns
 */
class Ispconfig_dns extends Ispconfig {


	/**
	 * Ispconfig_dns constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Add one record on DNS > Zones > DNS Zone Wizard
	 *
	 * @param int    $client_id
	 * @param int    $template_id
	 * @param string $domain
	 * @param string $ip
	 * @param string $ns1
	 * @param string $ns2
	 * @param string $email
	 *
	 * @return bool|array TRUE or error
	 */
	public function templatezone_add($client_id, $template_id, $domain, $ip, $ns1, $ns2, $email)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'   => $client_id,
				'template_id' => $template_id,
				'domain'      => $domain,
				'ip'          => $ip,
				'ns1'         => $ns1,
				'ns2'         => $ns2,
				'email'       => $email,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/templatezone_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->dns_templatezone_add($this->ID, $client_id, $template_id, $domain, $ip, $ns1, $ns2, $email);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from DNS > Zones
	 *
	 * @param int $id
	 *
	 * @return array dns_soa.* or error
	 */
	public function zone_get($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/zone_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_zone_get($this->ID, $id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from dns_soa.id by dns_soa.origin
	 *
	 * @param string $origin
	 *
	 * @return int|array dns_soa.id or error
	 */
	public function zone_get_id($origin)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['origin' => $origin]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/zone_get_id'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_zone_get_id($this->ID, $origin);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get dns_soa by client_id
	 *
	 * @param int $client_id
	 * @param int $server_id
	 *
	 * @return array dns_soa.* or error
	 */
	public function zone_get_by_user($client_id, $server_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'server_id' => $server_id,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/zone_get_by_user'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_zone_get_by_user($this->ID, $client_id, $server_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on DNS > Zones
	 *
	 * @param int   $client_id
	 * @param array $dns_soa server_id, origin, ns, mbox, serial, refresh, retry, expire, minimum,
	 *                       ttl, active, xfer, also_notify, update_acl
	 *
	 * @return int|array invoice_settings.invoice_settings_id or error
	 */
	public function zone_add($client_id, $dns_soa)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['client_id' => $client_id, 'dns_soa' => $dns_soa]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/zone_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id'   => isset($dns_soa['server_id']) ? $dns_soa['server_id'] : 0,
				'origin'      => isset($dns_soa['origin']) ? $dns_soa['origin'] : '',
				'ns'          => isset($dns_soa['ns']) ? $dns_soa['ns'] : '',
				'mbox'        => isset($dns_soa['mbox']) ? $dns_soa['mbox'] : '',
				'serial'      => isset($dns_soa['serial']) ? $dns_soa['serial'] : 1,
				'refresh'     => isset($dns_soa['refresh']) ? $dns_soa['refresh'] : 28800,
				'retry'       => isset($dns_soa['retry']) ? $dns_soa['retry'] : 7200,
				'expire'      => isset($dns_soa['expire']) ? $dns_soa['expire'] : 604800,
				'minimum'     => isset($dns_soa['minimum']) ? $dns_soa['minimum'] : 86400,
				'ttl'         => isset($dns_soa['ttl']) ? $dns_soa['ttl'] : 86400,
				'active'      => isset($dns_soa['active']) ? $dns_soa['active'] : 'N',
				'xfer'        => isset($dns_soa['xfer']) ? $dns_soa['xfer'] : '',
				'also_notify' => isset($dns_soa['also_notify']) ? $dns_soa['also_notify'] : '',
				'update_acl'  => isset($dns_soa['update_acl']) ? $dns_soa['update_acl'] : '',
			);

			return $this->SoapClient->dns_zone_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in DNS > Zones
	 *
	 * @param int   $client_id
	 * @param int   $id
	 * @param array $params  server_id, origin, ns, mbox, serial, refresh, retry, expire, minimum,
	 *                       ttl, active, xfer, also_notify, update_acl
	 *
	 * @return int|array invoice_settings.invoice_settings_id or error
	 */
	public function zone_update($client_id, $id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'id'        => $id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/zone_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_zone_update($this->ID, $client_id, $id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in DNS > Zones
	 *
	 * @param int $id
	 *
	 * @return bool|array TRUE or error
	 */
	public function zone_delete($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/zone_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->dns_zone_delete($this->ID, $id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Set status in DNS > Zones > DNS Zone.Active
	 *
	 * @param int    $id
	 * @param string $status active or inactive
	 *
	 * @return string dns_soa.active or error
	 */
	public function zone_set_status($id, $status)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id, 'status' => $status]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/zone_set_status'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->dns_zone_set_status($this->ID, $id, $status);

			return $status;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from DNS > Zones > Records.AAAA
	 *
	 * @param int $id
	 *
	 * @return array dns_rr.* or error
	 */
	public function aaaa_get($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/aaaa_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/aaaa_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_aaaa_get($this->ID, $id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on DNS > Zones > DNS Zone > Records.AAAA
	 *
	 * @param int   $client_id
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 *
	 * @return int|array dns_rr.id or error
	 */
	public function aaaa_add($client_id, $dns_rr)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['client_id' => $client_id, 'dns_rr' => $dns_rr]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/aaaa_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
				'zone'      => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
				'name'      => isset($dns_rr['name']) ? $dns_rr['name'] : '',
				'data'      => isset($dns_rr['data']) ? $dns_rr['data'] : '',
				'ttl'       => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
				'active'    => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
				'type'      => 'AAAA',
				'stamp'     => date('Y-m-d H:i:s'),
				'serial'    => date('Ymd') . '01',
			);

			return $this->SoapClient->dns_aaaa_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in DNS > Zones > DNS Zone > Records.AAAA
	 *
	 * @param int   $client_id
	 * @param int   $id
	 * @param array $params server_id, zone, name, data, ttl, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function aaaa_update($client_id, $id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'id'        => $id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/aaaa_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_aaaa_update($this->ID, $client_id, $id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in DNS > Zones > Records.AAAA
	 *
	 * @param int $id
	 *
	 * @return bool|array TRUE or error
	 */
	public function aaaa_delete($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/aaaa_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->dns_aaaa_delete($this->ID, $id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from DNS > Zones > Records.A
	 *
	 * @param int $id
	 *
	 * @return array dns_rr.* or error
	 */
	public function a_get($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/a_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_a_get($this->ID, $id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on DNS > Zones > DNS Zone > Records.A
	 *
	 * @param int   $client_id
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 *
	 * @return int|array dns_rr.id or error
	 */
	public function a_add($client_id, $dns_rr)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['client_id' => $client_id, 'dns_rr' => $dns_rr]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/a_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
				'zone'      => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
				'name'      => isset($dns_rr['name']) ? $dns_rr['name'] : '',
				'data'      => isset($dns_rr['data']) ? $dns_rr['data'] : '',
				'ttl'       => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
				'active'    => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
				'type'      => 'A',
				'stamp'     => date('Y-m-d H:i:s'),
				'serial'    => date('Ymd') . '01',
			);

			return $this->SoapClient->dns_a_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in DNS > Zones > DNS Zone > Records.A
	 *
	 * @param int   $client_id
	 * @param int   $id
	 * @param array $params server_id, zone, name, data, ttl, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function a_update($client_id, $id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'id'        => $id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/a_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_a_update($this->ID, $client_id, $id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in DNS > Zones > Records.A
	 *
	 * @param int $id
	 *
	 * @return bool|array TRUE or error
	 */
	public function a_delete($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/a_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->dns_a_delete($this->ID, $id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from DNS > Zones > Records.ALIAS
	 *
	 * @param int $id
	 *
	 * @return array dns_rr.* or error
	 */
	public function alias_get($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/alias_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_alias_get($this->ID, $id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on DNS > Zones > DNS Zone > Records.ALIAS
	 *
	 * @param int   $client_id
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 *
	 * @return int|array dns_rr.id or error
	 */
	public function alias_add($client_id, $dns_rr)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'dns_rr'    => $dns_rr,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/alias_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
				'zone'      => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
				'name'      => isset($dns_rr['name']) ? $dns_rr['name'] : '',
				'data'      => isset($dns_rr['data']) ? $dns_rr['data'] : '',
				'ttl'       => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
				'active'    => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
				'type'      => 'ALIAS',
				'stamp'     => date('Y-m-d H:i:s'),
				'serial'    => date('Ymd') . '01',
			);

			return $this->SoapClient->dns_alias_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in DNS > Zones > DNS Zone > Records.ALIAS
	 *
	 * @param int   $client_id
	 * @param int   $id
	 * @param array $params server_id, zone, name, data, ttl, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function alias_update($client_id, $id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'id'        => $id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/alias_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_alias_update($this->ID, $client_id, $id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in DNS > Zones > Records.ALIAS
	 *
	 * @param int $id
	 *
	 * @return bool|array TRUE or error
	 */
	public function alias_delete($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/alias_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->dns_alias_delete($this->ID, $id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from DNS > Zones > Records.CNAME
	 *
	 * @param int $id
	 *
	 * @return array dns_rr.* or error
	 */
	public function cname_get($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/cname_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/cname_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_cname_get($this->ID, $id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on DNS > Zones > DNS Zone > Records.CNAME
	 *
	 * @param int   $client_id
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 *
	 * @return int|array dns_rr.id or error
	 */
	public function cname_add($client_id, $dns_rr)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'dns_rr'    => $dns_rr,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/cname_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
				'zone'      => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
				'name'      => isset($dns_rr['name']) ? $dns_rr['name'] : '',
				'data'      => isset($dns_rr['data']) ? $dns_rr['data'] : '',
				'ttl'       => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
				'active'    => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
				'type'      => 'CNAME',
				'stamp'     => date('Y-m-d H:i:s'),
				'serial'    => date('Ymd') . '01',
			);

			return $this->SoapClient->dns_cname_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in DNS > Zones > DNS Zone > Records.CNAME
	 *
	 * @param int   $client_id
	 * @param int   $id
	 * @param array $params server_id, zone, name, data, ttl, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function cname_update($client_id, $id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'id'        => $id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/cname_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_cname_update($this->ID, $client_id, $id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in DNS > Zones > Records.CNAME
	 *
	 * @param int $id
	 *
	 * @return bool|array TRUE or error
	 */
	public function cname_delete($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/cname_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->dns_cname_delete($this->ID, $id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from DNS > Zones > Records.HINFO
	 *
	 * @param int $id
	 *
	 * @return array dns_rr.* or error
	 */
	public function hinfo_get($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/hinfo_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_hinfo_get($this->ID, $id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on DNS > Zones > DNS Zone > Records.HINFO
	 *
	 * @param int   $client_id
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 *
	 * @return int|array dns_rr.id or error
	 */
	public function hinfo_add($client_id, $dns_rr)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'dns_rr'    => $dns_rr,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/hinfo_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
				'zone'      => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
				'name'      => isset($dns_rr['name']) ? $dns_rr['name'] : '',
				'data'      => isset($dns_rr['data']) ? $dns_rr['data'] : '',
				'ttl'       => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
				'active'    => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
				'type'      => 'HINFO',
				'stamp'     => date('Y-m-d H:i:s'),
				'serial'    => date('Ymd') . '01',
			);

			return $this->SoapClient->dns_hinfo_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in DNS > Zones > DNS Zone > Records.HINFO
	 *
	 * @param int   $client_id
	 * @param int   $id
	 * @param array $params server_id, zone, name, data, ttl, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function hinfo_update($client_id, $id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'id'        => $id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/hinfo_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_hinfo_update($this->ID, $client_id, $id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in DNS > Zones > Records.HINFO
	 *
	 * @param int $id
	 *
	 * @return bool|array TRUE or error
	 */
	public function hinfo_delete($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/hinfo_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->dns_hinfo_delete($this->ID, $id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from DNS > Zones > Records.MX
	 *
	 * @param int $id
	 *
	 * @return array dns_rr.* or error
	 */
	public function mx_get($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/mx_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_mx_get($this->ID, $id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on DNS > Zones > DNS Zone > Records.MX
	 *
	 * @param int   $client_id
	 * @param array $dns_rr server_id, zone, name, data, ttl, active, aux
	 *
	 * @return int|array dns_rr.id or error
	 */
	public function mx_add($client_id, $dns_rr)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'dns_rr'    => $dns_rr,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/mx_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
				'zone'      => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
				'name'      => isset($dns_rr['name']) ? $dns_rr['name'] : '',
				'data'      => isset($dns_rr['data']) ? $dns_rr['data'] : '',
				'ttl'       => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
				'active'    => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
				'aux'       => isset($dns_rr['aux']) ? $dns_rr['aux'] : 10,
				'type'      => 'MX',
				'stamp'     => date('Y-m-d H:i:s'),
				'serial'    => date('Ymd') . '01',
			);

			return $this->SoapClient->dns_mx_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in DNS > Zones > DNS Zone > Records.MX
	 *
	 * @param int   $client_id
	 * @param int   $id
	 * @param array $params server_id, zone, name, data, ttl, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function mx_update($client_id, $id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'id'        => $id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/mx_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_mx_update($this->ID, $client_id, $id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in DNS > Zones > Records.MX
	 *
	 * @param int $id
	 *
	 * @return bool|array TRUE or error
	 */
	public function mx_delete($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/mx_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->dns_mx_delete($this->ID, $id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from DNS > Zones > Records.NS
	 *
	 * @param int $id
	 *
	 * @return array dns_rr.* or error
	 */
	public function ns_get($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/ns_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_ns_get($this->ID, $id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on DNS > Zones > DNS Zone > Records.NS
	 *
	 * @param int   $client_id
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 *
	 * @return int|array dns_rr.id or error
	 */
	public function ns_add($client_id, $dns_rr)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'dns_rr'    => $dns_rr,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/ns_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
				'zone'      => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
				'name'      => isset($dns_rr['name']) ? $dns_rr['name'] : '',
				'data'      => isset($dns_rr['data']) ? $dns_rr['data'] : '',
				'ttl'       => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
				'active'    => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
				'type'      => 'NS',
				'stamp'     => date('Y-m-d H:i:s'),
				'serial'    => date('Ymd') . '01',
			);

			return $this->SoapClient->dns_ns_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in DNS > Zones > DNS Zone > Records.NS
	 *
	 * @param int   $client_id
	 * @param int   $id
	 * @param array $params server_id, zone, name, data, ttl, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function ns_update($client_id, $id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'id'        => $id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/ns_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_ns_update($this->ID, $client_id, $id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in DNS > Zones > Records.NS
	 *
	 * @param int $id
	 *
	 * @return bool|array TRUE or error
	 */
	public function ns_delete($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/ns_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->dns_ns_delete($this->ID, $id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from DNS > Zones > Records.PTR
	 *
	 * @param int $id
	 *
	 * @return array dns_rr.* or error
	 */
	public function ptr_get($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/ptr_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_ptr_get($this->ID, $id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on DNS > Zones > DNS Zone > Records.PTR
	 *
	 * @param int   $client_id
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 *
	 * @return int|array dns_rr.id or error
	 */
	public function ptr_add($client_id, $dns_rr)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'dns_rr'    => $dns_rr,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/ptr_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
				'zone'      => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
				'name'      => isset($dns_rr['name']) ? $dns_rr['name'] : '',
				'data'      => isset($dns_rr['data']) ? $dns_rr['data'] : '',
				'ttl'       => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
				'active'    => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
				'type'      => 'PTR',
				'stamp'     => date('Y-m-d H:i:s'),
				'serial'    => date('Ymd') . '01',
			);

			return $this->SoapClient->dns_ptr_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in DNS > Zones > DNS Zone > Records.PTR
	 *
	 * @param int   $client_id
	 * @param int   $id
	 * @param array $params server_id, zone, name, data, ttl, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function ptr_update($client_id, $id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'id'        => $id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/ptr_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_ptr_update($this->ID, $client_id, $id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in DNS > Zones > Records.PTR
	 *
	 * @param int $id
	 *
	 * @return bool|array TRUE or error
	 */
	public function ptr_delete($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/ptr_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->dns_ptr_delete($this->ID, $id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from DNS > Zones > Records.RP
	 *
	 * @param int $id
	 *
	 * @return array dns_rr.* or error
	 */
	public function rp_get($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/rp_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_rp_get($this->ID, $id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on DNS > Zones > DNS Zone > Records.RP
	 *
	 * @param int   $client_id
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 *
	 * @return int|array dns_rr.id or error
	 */
	public function rp_add($client_id, $dns_rr)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'dns_rr'    => $dns_rr,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/rp_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
				'zone'      => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
				'name'      => isset($dns_rr['name']) ? $dns_rr['name'] : '',
				'data'      => isset($dns_rr['data']) ? $dns_rr['data'] : '',
				'ttl'       => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
				'active'    => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
				'type'      => 'RP',
				'stamp'     => date('Y-m-d H:i:s'),
				'serial'    => date('Ymd') . '01',
			);

			return $this->SoapClient->dns_rp_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in DNS > Zones > DNS Zone > Records.RP
	 *
	 * @param int   $client_id
	 * @param int   $id
	 * @param array $params server_id, zone, name, data, ttl, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function rp_update($client_id, $id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'id'        => $id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/rp_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_rp_update($this->ID, $client_id, $id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in DNS > Zones > Records.RP
	 *
	 * @param int $id
	 *
	 * @return bool|array TRUE or error
	 */
	public function rp_delete($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/rp_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->dns_rp_delete($this->ID, $id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from DNS > Zones > Records.SRV
	 *
	 * @param int $id
	 *
	 * @return array dns_rr.* or error
	 */
	public function srv_get($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/srv_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_srv_get($this->ID, $id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on DNS > Zones > DNS Zone > Records.SRV
	 *
	 * @param int   $client_id
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 *
	 * @return int|array dns_rr.id or error
	 */
	public function srv_add($client_id, $dns_rr)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'dns_rr'    => $dns_rr,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/srv_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
				'zone'      => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
				'name'      => isset($dns_rr['name']) ? $dns_rr['name'] : '',
				'data'      => isset($dns_rr['data']) ? $dns_rr['data'] : '',
				'ttl'       => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
				'active'    => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
				'type'      => 'SRV',
				'stamp'     => date('Y-m-d H:i:s'),
				'serial'    => date('Ymd') . '01',
			);

			return $this->SoapClient->dns_srv_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in DNS > Zones > DNS Zone > Records.SRV
	 *
	 * @param int   $client_id
	 * @param int   $id
	 * @param array $params server_id, zone, name, data, ttl, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function srv_update($client_id, $id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'id'        => $id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/srv_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_srv_update($this->ID, $client_id, $id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in DNS > Zones > Records.SRV
	 *
	 * @param int $id
	 *
	 * @return bool|array TRUE or error
	 */
	public function srv_delete($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/srv_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->dns_srv_delete($this->ID, $id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from DNS > Zones > Records.TXT
	 *
	 * @param int $id
	 *
	 * @return array dns_rr.* or error
	 */
	public function txt_get($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/txt_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_txt_get($this->ID, $id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on DNS > Zones > DNS Zone > Records.TXT
	 *
	 * @param int   $client_id
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 *
	 * @return int|array dns_rr.id or error
	 */
	public function txt_add($client_id, $dns_rr)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'dns_rr'    => $dns_rr,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/txt_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
				'zone'      => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
				'name'      => isset($dns_rr['name']) ? $dns_rr['name'] : '',
				'data'      => isset($dns_rr['data']) ? $dns_rr['data'] : '',
				'ttl'       => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
				'active'    => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
				'type'      => 'TXT',
				'stamp'     => date('Y-m-d H:i:s'),
				'serial'    => date('Ymd') . '01',
			);

			return $this->SoapClient->dns_txt_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in DNS > Zones > DNS Zone > Records.TXT
	 *
	 * @param int   $client_id
	 * @param int   $id
	 * @param array $params server_id, zone, name, data, ttl, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function txt_update($client_id, $id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'id'        => $id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/txt_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_txt_update($this->ID, $client_id, $id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in DNS > Zones > Records.TXT
	 *
	 * @param int $id
	 *
	 * @return bool|array TRUE or error
	 */
	public function txt_delete($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/txt_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->dns_txt_delete($this->ID, $id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get all records from dns_rr by dns_soa.id
	 *
	 * @param int $zone
	 *
	 * @return array dns_soa.* or error
	 */
	public function rr_get_all_by_zone($zone)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['zone' => $zone]);
			if ( ! $this->CI->form_validation->run('ispconfig/dns/rr_get_all_by_zone'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->dns_rr_get_all_by_zone($this->ID, $zone);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

}
