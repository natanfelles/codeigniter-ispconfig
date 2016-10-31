<?php
/**
 * codeigniter-ispconfig-new
 *
 * @package  codeigniter-ispconfig-new
 * @author   Natan Felles <natanfelles@gmail.com>
 */
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class Ispconfig_openvz
 */
class Ispconfig_openvz extends Ispconfig {


	/**
	 * Ispconfig_openvz constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Get one record from VServer > OpenVZ OS Template
	 *
	 * @param int $ostemplate_id
	 *
	 * @return array openvz_ostemplate.* or error
	 */
	public function ostemplate_get($ostemplate_id)
	{
		if (is_array($validation = $this->validate_primary_key('ostemplate_id', $ostemplate_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->openvz_ostemplate_get($this->ID, $ostemplate_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on VServer > OpenVZ OS Template
	 *
	 * @param int   $client_id
	 * @param array $params            server_id,  template_name,  template_file,  allservers,
	 *                                 active, description
	 *
	 * @return int|array openvz_ostemplate.ostemplate_id or error
	 */
	public function ostemplate_add($client_id, $params)
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
					'field' => 'params[template_name]',
					'label' => 'params[template_name]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[template_file]',
					'label' => 'params[template_file]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[allservers]',
					'label' => 'params[allservers]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[description]',
					'label' => 'params[description]',
					'rules' => 'trim',
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
				'server_id'     => isset($params['server_id']) ? $params['server_id'] : 0,
				'template_name' => isset($params['template_name']) ? $params['template_name'] : NULL,
				'template_file' => isset($params['template_file']) ? $params['template_file'] : '',
				'allservers'    => isset($params['allservers']) ? $params['allservers'] : 'y',
				'active'        => isset($params['active']) ? $params['active'] : 'y',
				'description'   => isset($params['description']) ? $params['description'] : NULL,
			);

			return $this->SoapClient->openvz_ostemplate_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in VServer > OpenVZ OS Template
	 *
	 * @param int   $client_id
	 * @param int   $ostemplate_id
	 * @param array $params            server_id,  template_name,  template_file,  allservers,
	 *                                 active, description
	 *
	 * @return bool|array TRUE or error
	 */
	public function ostemplate_update($client_id, $ostemplate_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'     => $client_id,
				'ostemplate_id' => $ostemplate_id,
				'params'        => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('ostemplate_id'),
				$this->prepare_validate_pk('params[server_id]'),
				[
					'field' => 'params[template_name]',
					'label' => 'params[template_name]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[template_file]',
					'label' => 'params[template_file]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[allservers]',
					'label' => 'params[allservers]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[description]',
					'label' => 'params[description]',
					'rules' => 'trim',
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

			return $this->SoapClient->openvz_ostemplate_update($this->ID, $client_id, $ostemplate_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in VServer > OpenVZ OS Template
	 *
	 * @param int $ostemplate_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function ostemplate_delete($ostemplate_id)
	{
		if (is_array($validation = $this->validate_primary_key('ostemplate_id', $ostemplate_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->openvz_ostemplate_delete($this->ID, $ostemplate_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from VServer > OpenVZ Template
	 *
	 * @param int $template_id
	 *
	 * @return array openvz_template.* or error
	 */
	public function template_get($template_id)
	{
		if (is_array($validation = $this->validate_primary_key('template_id', $template_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->openvz_template_get($this->ID, $template_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on VServer > OpenVZ Template
	 *
	 * @param int   $client_id
	 * @param array $params template_name, diskspace, traffic, bandwidth, ram, ram_burst, cpu_units,
	 *                      cpu_num, cpu_limit, io_priority, active, description, numproc,
	 *                      numtcpsock, numothersock, vmguarpages, kmemsize, tcpsndbuf, tcprcvbuf,
	 *                      othersockbuf, dgramrcvbuf, oomguarpages, privvmpages, lockedpages,
	 *                      shmpages, physpages, numfile, avnumproc, numflock, numpty, numsiginfo,
	 *                      dcachesize, numiptent, swappages, hostname, nameserver, create_dns,
	 *                      capability, features, iptables, custom
	 *
	 * @return int|array openvz_template.template_id or error
	 */
	public function template_add($client_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'params'    => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				[
					'field' => 'params[template_name]',
					'label' => 'params[template_name]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[diskspace]',
					'label' => 'params[diskspace]',
					'rules' => 'trim|required|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[traffic]',
					'label' => 'params[traffic]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[bandwidth]',
					'label' => 'params[bandwidth]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[ram]',
					'label' => 'params[ram]',
					'rules' => 'trim|required|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[ram_burst]',
					'label' => 'params[ram_burst]',
					'rules' => 'trim|required|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[cpu_units]',
					'label' => 'params[cpu_units]',
					'rules' => 'trim|required|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[cpu_num]',
					'label' => 'params[cpu_num]',
					'rules' => 'trim|required|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[cpu_limit]',
					'label' => 'params[cpu_limit]',
					'rules' => 'trim|required|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[io_priority]',
					'label' => 'params[io_priority]',
					'rules' => 'trim|required|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[description]',
					'label' => 'params[description]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[numproc]',
					'label' => 'params[numproc]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[numtcpsock]',
					'label' => 'params[numtcpsock]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[numothersock]',
					'label' => 'params[numothersock]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[vmguarpages]',
					'label' => 'params[vmguarpages]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[kmemsize]',
					'label' => 'params[kmemsize]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[tcpsndbuf]',
					'label' => 'params[tcpsndbuf]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[tcprcvbuf]',
					'label' => 'params[tcprcvbuf]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[othersockbuf]',
					'label' => 'params[othersockbuf]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[dgramrcvbuf]',
					'label' => 'params[dgramrcvbuf]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[oomguarpages]',
					'label' => 'params[oomguarpages]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[privvmpages]',
					'label' => 'params[privvmpages]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[lockedpages]',
					'label' => 'params[lockedpages]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[shmpages]',
					'label' => 'params[shmpages]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[physpages]',
					'label' => 'params[physpages]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[numfile]',
					'label' => 'params[numfile]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[avnumproc]',
					'label' => 'params[avnumproc]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[numflock]',
					'label' => 'params[numflock]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[numpty]',
					'label' => 'params[numpty]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[numsiginfo]',
					'label' => 'params[numsiginfo]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[dcachesize]',
					'label' => 'params[dcachesize]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[numiptent]',
					'label' => 'params[numiptent]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[swappages]',
					'label' => 'params[swappages]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[hostname]',
					'label' => 'params[hostname]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[nameserver]',
					'label' => 'params[nameserver]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[create_dns]',
					'label' => 'params[create_dns]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[capability]',
					'label' => 'params[capability]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[features]',
					'label' => 'params[features]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[iptables]',
					'label' => 'params[iptables]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[custom]',
					'label' => 'params[custom]',
					'rules' => 'trim',
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
				'template_name' => isset($params['template_name']) ? $params['template_name'] : NULL,
				'diskspace'     => isset($params['diskspace']) ? $params['diskspace'] : 0,
				'traffic'       => isset($params['traffic']) ? $params['traffic'] : -1,
				'bandwidth'     => isset($params['bandwidth']) ? $params['bandwidth'] : -1,
				'ram'           => isset($params['ram']) ? $params['ram'] : 0,
				'ram_burst'     => isset($params['ram_burst']) ? $params['ram_burst'] : 0,
				'cpu_units'     => isset($params['cpu_units']) ? $params['cpu_units'] : 1000,
				'cpu_num'       => isset($params['cpu_num']) ? $params['cpu_num'] : 4,
				'cpu_limit'     => isset($params['cpu_limit']) ? $params['cpu_limit'] : 400,
				'io_priority'   => isset($params['io_priority']) ? $params['io_priority'] : 4,
				'active'        => isset($params['active']) ? $params['active'] : 'y',
				'description'   => isset($params['description']) ? $params['description'] : NULL,
				'numproc'       => isset($params['numproc']) ? $params['numproc'] : NULL,
				'numtcpsock'    => isset($params['numtcpsock']) ? $params['numtcpsock'] : NULL,
				'numothersock'  => isset($params['numothersock']) ? $params['numothersock'] : NULL,
				'vmguarpages'   => isset($params['vmguarpages']) ? $params['vmguarpages'] : '65536:unlimited',
				'kmemsize'      => isset($params['kmemsize']) ? $params['kmemsize'] : NULL,
				'tcpsndbuf'     => isset($params['tcpsndbuf']) ? $params['tcpsndbuf'] : NULL,
				'tcprcvbuf'     => isset($params['tcprcvbuf']) ? $params['tcprcvbuf'] : NULL,
				'othersockbuf'  => isset($params['othersockbuf']) ? $params['othersockbuf'] : NULL,
				'dgramrcvbuf'   => isset($params['dgramrcvbuf']) ? $params['dgramrcvbuf'] : NULL,
				'oomguarpages'  => isset($params['oomguarpages']) ? $params['oomguarpages'] : NULL,
				'privvmpages'   => isset($params['privvmpages']) ? $params['privvmpages'] : '131072:139264',
				'lockedpages'   => isset($params['lockedpages']) ? $params['lockedpages'] : NULL,
				'shmpages'      => isset($params['shmpages']) ? $params['shmpages'] : NULL,
				'physpages'     => isset($params['physpages']) ? $params['physpages'] : NULL,
				'numfile'       => isset($params['numfile']) ? $params['numfile'] : NULL,
				'avnumproc'     => isset($params['avnumproc']) ? $params['avnumproc'] : NULL,
				'numflock'      => isset($params['numflock']) ? $params['numflock'] : NULL,
				'numpty'        => isset($params['numpty']) ? $params['numpty'] : NULL,
				'numsiginfo'    => isset($params['numsiginfo']) ? $params['numsiginfo'] : NULL,
				'dcachesize'    => isset($params['dcachesize']) ? $params['dcachesize'] : NULL,
				'numiptent'     => isset($params['numiptent']) ? $params['numiptent'] : NULL,
				'swappages'     => isset($params['swappages']) ? $params['swappages'] : NULL,
				'hostname'      => isset($params['hostname']) ? $params['hostname'] : NULL,
				'nameserver'    => isset($params['nameserver']) ? $params['nameserver'] : NULL,
				'create_dns'    => isset($params['create_dns']) ? $params['create_dns'] : 'n',
				'capability'    => isset($params['capability']) ? $params['capability'] : NULL,
				'features'      => isset($params['features']) ? $params['features'] : NULL,
				'iptables'      => isset($params['iptables']) ? $params['iptables'] : NULL,
				'custom'        => isset($params['custom']) ? $params['custom'] : NULL,
			);
			$this->SoapClient->openvz_template_add($this->ID, $client_id, $params);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in VServer > OpenVZ Template
	 *
	 * @param int   $client_id
	 * @param int   $template_id
	 * @param array $params template_name, diskspace, traffic, bandwidth, ram, ram_burst, cpu_units,
	 *                      cpu_num, cpu_limit, io_priority, active, description, numproc,
	 *                      numtcpsock, numothersock, vmguarpages, kmemsize, tcpsndbuf, tcprcvbuf,
	 *                      othersockbuf, dgramrcvbuf, oomguarpages, privvmpages, lockedpages,
	 *                      shmpages, physpages, numfile, avnumproc, numflock, numpty, numsiginfo,
	 *                      dcachesize, numiptent, swappages, hostname, nameserver, create_dns,
	 *                      capability, features, iptables, custom
	 *
	 * @return int|array openvz_template.template_id or error
	 */
	public function template_update($client_id, $template_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'   => $client_id,
				'template_id' => $template_id,
				'params'      => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				[
					'field' => 'params[template_name]',
					'label' => 'params[template_name]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[diskspace]',
					'label' => 'params[diskspace]',
					'rules' => 'trim|required|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[traffic]',
					'label' => 'params[traffic]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[bandwidth]',
					'label' => 'params[bandwidth]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[ram]',
					'label' => 'params[ram]',
					'rules' => 'trim|required|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[ram_burst]',
					'label' => 'params[ram_burst]',
					'rules' => 'trim|required|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[cpu_units]',
					'label' => 'params[cpu_units]',
					'rules' => 'trim|required|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[cpu_num]',
					'label' => 'params[cpu_num]',
					'rules' => 'trim|required|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[cpu_limit]',
					'label' => 'params[cpu_limit]',
					'rules' => 'trim|required|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[io_priority]',
					'label' => 'params[io_priority]',
					'rules' => 'trim|required|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[description]',
					'label' => 'params[description]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[numproc]',
					'label' => 'params[numproc]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[numtcpsock]',
					'label' => 'params[numtcpsock]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[numothersock]',
					'label' => 'params[numothersock]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[vmguarpages]',
					'label' => 'params[vmguarpages]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[kmemsize]',
					'label' => 'params[kmemsize]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[tcpsndbuf]',
					'label' => 'params[tcpsndbuf]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[tcprcvbuf]',
					'label' => 'params[tcprcvbuf]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[othersockbuf]',
					'label' => 'params[othersockbuf]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[dgramrcvbuf]',
					'label' => 'params[dgramrcvbuf]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[oomguarpages]',
					'label' => 'params[oomguarpages]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[privvmpages]',
					'label' => 'params[privvmpages]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[lockedpages]',
					'label' => 'params[lockedpages]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[shmpages]',
					'label' => 'params[shmpages]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[physpages]',
					'label' => 'params[physpages]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[numfile]',
					'label' => 'params[numfile]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[avnumproc]',
					'label' => 'params[avnumproc]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[numflock]',
					'label' => 'params[numflock]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[numpty]',
					'label' => 'params[numpty]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[numsiginfo]',
					'label' => 'params[numsiginfo]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[dcachesize]',
					'label' => 'params[dcachesize]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[numiptent]',
					'label' => 'params[numiptent]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[swappages]',
					'label' => 'params[swappages]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[hostname]',
					'label' => 'params[hostname]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[nameserver]',
					'label' => 'params[nameserver]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[create_dns]',
					'label' => 'params[create_dns]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[capability]',
					'label' => 'params[capability]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[features]',
					'label' => 'params[features]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[iptables]',
					'label' => 'params[iptables]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[custom]',
					'label' => 'params[custom]',
					'rules' => 'trim',
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

			return $this->SoapClient->openvz_template_update($this->ID, $client_id, $template_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in VServer > OpenVZ Template
	 *
	 * @param int $template_id
	 *
	 * @return int|array affected rows or error
	 */
	public function template_delete($template_id)
	{
		if (is_array($validation = $this->validate_primary_key('template_id', $template_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->openvz_template_delete($this->ID, $template_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from VServer > OpenVZ IP addresses
	 *
	 * @param int $ip_address_id
	 *
	 * @return array openvz_ip.* or error
	 */
	public function ip_get($ip_address_id)
	{
		if (is_array($validation = $this->validate_primary_key('ip_address_id', $ip_address_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->openvz_ip_get($this->ID, $ip_address_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from free IP from VServer > OpenVZ IP addresses
	 *
	 * @param int $server_id
	 *
	 * @return array openvz_ip.* or error
	 */
	public function get_free_ip($server_id)
	{
		if (is_array($validation = $this->validate_primary_key('server_id', $server_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->openvz_get_free_ip($this->ID, $server_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on VServer > OpenVZ IP addresses
	 *
	 * @param int   $client_id
	 * @param array $params server_id,  ip_address,  vm_id,  reserved, additional
	 *
	 * @return int|array openvz_ip.ip_address_id or error
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
					'field' => 'params[ip_address]',
					'label' => 'params[ip_address]',
					'rules' => 'trim|valid_ip',
				],
				[
					'field' => 'params[vm_id]',
					'label' => 'params[vm_id]',
					'rules' => 'trim|integer|max_length[11]|greater_than_equal_to[0]',
				],
				[
					'field' => 'params[reserved]',
					'label' => 'params[reserved]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[additional]',
					'label' => 'params[additional]',
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
				'server_id'  => isset($params['server_id']) ? $params['server_id'] : 0,
				'ip_address' => isset($params['ip_address']) ? $params['ip_address'] : NULL,
				'vm_id'      => isset($params['vm_id']) ? $params['vm_id'] : 0,
				'reserved'   => isset($params['reserved']) ? $params['reserved'] : 'n',
				'additional' => isset($params['additional']) ? $params['additional'] : 'n',
			);

			return $this->SoapClient->openvz_ip_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			$errors = $this->get_error($e->getMessage());
			if (trim($errors['error'][0]) == 'ip_error_unique')
			{
				return array(
					'error' => ['params[ip_address]' => 'The params[ip_address] field must contain a unique value.'],
				);
			}

			return $errors;
		}
	}


	/**
	 * Update one record in VServer > OpenVZ IP addresses
	 *
	 * @param int   $client_id
	 * @param int   $ip_address_id
	 * @param array $params server_id,  ip_address,  vm_id,  reserved
	 *
	 * @return bool|array TRUE or error
	 */
	public function ip_update($client_id, $ip_address_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'     => $client_id,
				'ip_address_id' => $ip_address_id,
				'params'        => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('ip_address_id'),
				$this->prepare_validate_pk('params[server_id]'),
				[
					'field' => 'params[ip_address]',
					'label' => 'params[ip_address]',
					'rules' => 'trim|valid_ip',
				],
				[
					'field' => 'params[vm_id]',
					'label' => 'params[vm_id]',
					'rules' => 'trim|integer|max_length[11]|greater_than_equal_to[0]',
				],
				[
					'field' => 'params[reserved]',
					'label' => 'params[reserved]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[additional]',
					'label' => 'params[additional]',
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

			return $this->SoapClient->openvz_ip_update($this->ID, $client_id, $ip_address_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in VServer > OpenVZ IP addresses
	 *
	 * @param int $ip_address_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function ip_delete($ip_address_id)
	{
		if (is_array($validation = $this->validate_primary_key('ip_address_id', $ip_address_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->openvz_ip_delete($this->ID, $ip_address_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from VServer > OpenVZ Virtual Servers
	 *
	 * @param int $vm_id
	 *
	 * @return array openvz_vm.* or error
	 */
	public function vm_get($vm_id)
	{
		if (is_array($validation = $this->validate_primary_key('vm_id', $vm_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->openvz_vm_get($this->ID, $vm_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get records from VServer > OpenVZ Virtual Servers by Client
	 *
	 * @param int $client_id
	 *
	 * @return array openvz_vm.* or error
	 */
	public function vm_get_by_client($client_id)
	{
		if (is_array($validation = $this->validate_primary_key('client_id', $client_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->openvz_vm_get_by_client($this->ID, $client_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on VServer > OpenVZ Virtual Servers
	 *
	 * @param int   $client_id
	 * @param array $params    server_id,  veid,  ostemplate_id,  template_id,  ip_address,
	 *                         hostname, vm_password,  start_boot,  active, bootorder,
	 *                         active_until_date, description,  diskspace, traffic,  bandwidth,
	 *                         ram,  ram_burst, cpu_units,  cpu_num,  cpu_limit, io_priority,
	 *                         nameserver, create_dns,  capability,  config
	 *
	 * @return int|array openvz_vm.vm_id or error
	 */
	public function vm_add($client_id, $params)
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
				$this->prepare_validate_pk('params[veid]'),
				$this->prepare_validate_pk('params[ostemplate_id]'),
				$this->prepare_validate_pk('params[template_id]'),
				[
					'field' => 'params[ip_address]',
					'label' => 'params[ip_address]',
					'rules' => 'trim|required|valid_ip',
				],
				[
					'field' => 'params[hostname]',
					'label' => 'params[hostname]',
					'rules' => 'trim|required|valid_url',
				],
				[
					'field' => 'params[vm_password]',
					'label' => 'params[vm_password]',
					'rules' => 'trim|required',
				],
				[
					'field' => 'params[start_boot]',
					'label' => 'params[start_boot]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[bootorder]',
					'label' => 'params[bootorder]',
					'rules' => 'trim|greater_than[0]',
				],
				[
					'field' => 'params[active_until_date]',
					'label' => 'params[active_until_date]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[description]',
					'label' => 'params[description]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[diskspace]',
					'label' => 'params[diskspace]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[traffic]',
					'label' => 'params[traffic]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[bandwidth]',
					'label' => 'params[bandwidth]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[ram]',
					'label' => 'params[ram]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[ram_burst]',
					'label' => 'params[ram_burst]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[cpu_units]',
					'label' => 'params[cpu_units]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[cpu_num]',
					'label' => 'params[cpu_num]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[cpu_limit]',
					'label' => 'params[cpu_limit]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[io_priority]',
					'label' => 'params[io_priority]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[nameserver]',
					'label' => 'params[nameserver]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[create_dns]',
					'label' => 'params[create_dns]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[capability]',
					'label' => 'params[capability]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[features]',
					'label' => 'params[features]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[iptables]',
					'label' => 'params[iptables]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[config]',
					'label' => 'params[config]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[custom]',
					'label' => 'params[custom]',
					'rules' => 'trim',
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
				'server_id'         => isset($params['server_id']) ? $params['server_id'] : 0,
				'veid'              => isset($params['veid']) ? $params['veid'] : 0,
				'ostemplate_id'     => isset($params['ostemplate_id']) ? $params['ostemplate_id'] : 0,
				'template_id'       => isset($params['template_id']) ? $params['template_id'] : 0,
				'ip_address'        => isset($params['ip_address']) ? $params['ip_address'] : NULL,
				'hostname'          => isset($params['hostname']) ? $params['hostname'] : NULL,
				'vm_password'       => isset($params['vm_password']) ? $params['vm_password'] : NULL,
				'start_boot'        => isset($params['start_boot']) ? $params['start_boot'] : 'y',
				'active'            => isset($params['active']) ? $params['active'] : 'y',
				'bootorder'         => isset($params['bootorder']) ? $params['bootorder'] : 1,
				'active_until_date' => isset($params['active_until_date']) ? $params['active_until_date'] : NULL,
				'description'       => isset($params['description']) ? $params['description'] : NULL,
				'diskspace'         => isset($params['diskspace']) ? $params['diskspace'] : 0,
				'traffic'           => isset($params['traffic']) ? $params['traffic'] : -1,
				'bandwidth'         => isset($params['bandwidth']) ? $params['bandwidth'] : -1,
				'ram'               => isset($params['ram']) ? $params['ram'] : 0,
				'ram_burst'         => isset($params['ram_burst']) ? $params['ram_burst'] : 0,
				'cpu_units'         => isset($params['cpu_units']) ? $params['cpu_units'] : 1000,
				'cpu_num'           => isset($params['cpu_num']) ? $params['cpu_num'] : 4,
				'cpu_limit'         => isset($params['cpu_limit']) ? $params['cpu_limit'] : 400,
				'io_priority'       => isset($params['io_priority']) ? $params['io_priority'] : 4,
				'nameserver'        => isset($params['nameserver']) ? $params['nameserver'] : '8.8.8.8 8.8.4.4',
				'create_dns'        => isset($params['create_dns']) ? $params['create_dns'] : 'n',
				'capability'        => isset($params['capability']) ? $params['capability'] : NULL,
				'features'          => isset($params['features']) ? $params['features'] : NULL,
				'iptables'          => isset($params['iptables']) ? $params['iptables'] : NULL,
				'config'            => isset($params['config']) ? $params['config'] : NULL,
				'custom'            => isset($params['custom']) ? $params['custom'] : NULL,
			);

			return $this->SoapClient->openvz_vm_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			$errors = $this->get_error($e->getMessage());
			if (trim($errors['error'][0]) == 'veid_error_unique')
			{
				return array(
					'error' => ['params[veid]' => 'The params[veid] field must contain a unique value.'],
				);
			}

			return $errors;
		}
	}


	public function vm_add_from_template($client_id, $ostemplate_id, $template_id, $override_params = [])
	{
		try
		{
			$this->login();

			// Todo: Not working. Why?
			return $this->SoapClient->openvz_vm_add_from_template($this->ID, $client_id, $ostemplate_id, $template_id, $override_params);
		}
		catch (SoapFault $e)
		{
			$errors = $this->get_error($e->getMessage());
			if (trim($errors['error'][0]) == 'veid_error_unique')
			{
				return array(
					'error' => ['params[veid]' => 'The params[veid] field must contain a unique value.'],
				);
			}

			return $errors;
		}
	}


	/**
	 * Update one record in VServer > OpenVZ Virtual Servers
	 *
	 * @param int   $client_id
	 * @param int   $vm_id
	 * @param array $params    server_id,  veid,  ostemplate_id,  template_id,  ip_address,
	 *                         hostname, vm_password,  start_boot,  active, active_until_date,
	 *                         description,  diskspace, traffic,  bandwidth,  ram,  ram_burst,
	 *                         cpu_units,  cpu_num,  cpu_limit, io_priority,  nameserver,
	 *                         create_dns,  capability,  config
	 *
	 * @return int|array openvz_vm.vm_id or error
	 */
	public function vm_update($client_id, $vm_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'vm_id'     => $vm_id,
				'params'    => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('vm_id'),
				[
					'field' => 'params[server_id]',
					'label' => 'params[server_id]',
					'rules' => 'trim|integer|max_length[11]|greater_than[0]',
				],
				[
					'field' => 'params[veid]',
					'label' => 'params[veid]',
					'rules' => 'trim|integer|max_length[11]|greater_than[0]',
				],
				[
					'field' => 'params[ostemplate_id]',
					'label' => 'params[ostemplate_id]',
					'rules' => 'trim|integer|max_length[11]|greater_than[0]',
				],
				[
					'field' => 'params[template_id]',
					'label' => 'params[template_id]',
					'rules' => 'trim|integer|max_length[11]|greater_than[0]',
				],
				[
					'field' => 'params[ip_address]',
					'label' => 'params[ip_address]',
					'rules' => 'trim|valid_ip',
				],
				[
					'field' => 'params[hostname]',
					'label' => 'params[hostname]',
					'rules' => 'trim|valid_url',
				],
				[
					'field' => 'params[vm_password]',
					'label' => 'params[vm_password]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[start_boot]',
					'label' => 'params[start_boot]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[bootorder]',
					'label' => 'params[bootorder]',
					'rules' => 'trim|greater_than[0]',
				],
				[
					'field' => 'params[active_until_date]',
					'label' => 'params[active_until_date]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[description]',
					'label' => 'params[description]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[diskspace]',
					'label' => 'params[diskspace]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[traffic]',
					'label' => 'params[traffic]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[bandwidth]',
					'label' => 'params[bandwidth]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[ram]',
					'label' => 'params[ram]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[ram_burst]',
					'label' => 'params[ram_burst]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[cpu_units]',
					'label' => 'params[cpu_units]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[cpu_num]',
					'label' => 'params[cpu_num]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[cpu_limit]',
					'label' => 'params[cpu_limit]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[io_priority]',
					'label' => 'params[io_priority]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[nameserver]',
					'label' => 'params[nameserver]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[create_dns]',
					'label' => 'params[create_dns]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[capability]',
					'label' => 'params[capability]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[features]',
					'label' => 'params[features]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[iptables]',
					'label' => 'params[iptables]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[config]',
					'label' => 'params[config]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[custom]',
					'label' => 'params[custom]',
					'rules' => 'trim',
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

			return $this->SoapClient->openvz_vm_update($this->ID, $client_id, $vm_id, $params);
		}
		catch (SoapFault $e)
		{
			$errors = $this->get_error($e->getMessage());
			if (trim($errors['error'][0]) == 'veid_error_unique')
			{
				return array(
					'error' => ['params[veid]' => 'The params[veid] field must contain a unique value.'],
				);
			}

			return $errors;
		}
	}


	/**
	 * Delete one record in VServer > OpenVZ Virtual Servers
	 *
	 * @param int $vm_id
	 *
	 * @return int|array affected rows or error
	 */
	public function vm_delete($vm_id)
	{
		if (is_array($validation = $this->validate_primary_key('vm_id', $vm_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->openvz_vm_delete($this->ID, $vm_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Execute action in VServer > OpenVZ Virtual Servers > Actions.Start virtual server
	 *
	 * @param int $vm_id
	 *
	 * @return int|array affected rows or error
	 */
	public function vm_start($vm_id)
	{
		if (is_array($validation = $this->validate_primary_key('vm_id', $vm_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->openvz_vm_start($this->ID, $vm_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Execute action in VServer > OpenVZ Virtual Servers > Actions.Stop virtual server
	 *
	 * @param int $vm_id
	 *
	 * @return int|array affected rows or error
	 */
	public function vm_stop($vm_id)
	{
		if (is_array($validation = $this->validate_primary_key('vm_id', $vm_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->openvz_vm_stop($this->ID, $vm_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Execute action in VServer > OpenVZ Virtual Servers > Actions.Restart virtual server
	 *
	 * @param int $vm_id
	 *
	 * @return int|array affect rows or error
	 */
	public function vm_restart($vm_id)
	{
		if (is_array($validation = $this->validate_primary_key('vm_id', $vm_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->openvz_vm_restart($this->ID, $vm_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

}
