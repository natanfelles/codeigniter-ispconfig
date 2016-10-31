<?php
/**
 * codeigniter-ispconfig-new
 *
 * @package  codeigniter-ispconfig-new
 * @author   Natan Felles <natanfelles@gmail.com>
 */
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class Ispconfig_sites
 */
class Ispconfig_sites extends Ispconfig {


	/**
	 * Ispconfig_sites constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Get one record from Sites > Cron Jobs
	 *
	 * @param int $cron_id
	 *
	 * @return array cron.* or error
	 */
	public function cron_get($cron_id)
	{
		if (is_array($validation = $this->validate_primary_key('cron_id', $cron_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->sites_cron_get($this->ID, $cron_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Sites > Cron Jobs
	 *
	 * @param int   $client_id
	 * @param array $params server_id, parent_domain_id, type, command, run_min, run_hour, run_mday,
	 *                      run_month, run_wday, log, active
	 *
	 * @return int|array cron.id or error
	 */
	public function cron_add($client_id, $params)
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
				$this->prepare_validate_pk('params[parent_domain_id]'),
				[
					'field' => 'params[type]',
					'label' => 'params[type]',
					'rules' => 'trim|in_list[url,chrooted,full]',
				],
				[
					'field' => 'params[command]',
					'label' => 'params[command]',
					'rules' => 'trim|required',
				],
				[
					'field' => 'params[run_min]',
					'label' => 'params[run_min]',
					'rules' => 'trim|required|max_length[5]',
				],
				[
					'field' => 'params[run_hour]',
					'label' => 'params[run_hour]',
					'rules' => 'trim|required|max_length[5]',
				],
				[
					'field' => 'params[run_mday]',
					'label' => 'params[run_mday]',
					'rules' => 'trim|required|max_length[5]',
				],
				[
					'field' => 'params[run_month]',
					'label' => 'params[run_month]',
					'rules' => 'trim|required|max_length[5]',
				],
				[
					'field' => 'params[run_wday]',
					'label' => 'params[run_wday]',
					'rules' => 'trim|required|max_length[5]',
				],
				[
					'field' => 'params[log]',
					'label' => 'params[log]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
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
				'parent_domain_id' => isset($params['parent_domain_id']) ? $params['parent_domain_id'] : 0,
				'type'             => isset($params['type']) ? $params['type'] : 'url',
				'command'          => isset($params['command']) ? $params['command'] : NULL,
				'run_min'          => isset($params['run_min']) ? $params['run_min'] : NULL,
				'run_hour'         => isset($params['run_hour']) ? $params['run_hour'] : NULL,
				'run_mday'         => isset($params['run_mday']) ? $params['run_mday'] : NULL,
				'run_month'        => isset($params['run_month']) ? $params['run_month'] : NULL,
				'run_wday'         => isset($params['run_wday']) ? $params['run_wday'] : NULL,
				'log'              => isset($params['log']) ? $params['log'] : 'n',
				'active'           => isset($params['active']) ? $params['active'] : 'y',
			);

			return $this->SoapClient->sites_cron_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in Sites > Cron Jobs
	 *
	 * @param int   $client_id
	 * @param int   $cron_id
	 * @param array $params server_id, parent_domain_id, type, command, run_min, run_hour, run_mday,
	 *                      run_month, run_wday, log, active
	 *
	 * @return int|array Number of affected rows or error
	 */
	public function cron_update($client_id, $cron_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'cron_id'   => $cron_id,
				'params'    => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('cron_id'),
				[
					'field' => 'params[server_id]',
					'label' => 'params[server_id]',
					'rules' => 'trim|greater_than[0]',
				],
				[
					'field' => 'params[parent_domain_id]',
					'label' => 'params[parent_domain_id]',
					'rules' => 'trim|greater_than[0]',
				],
				[
					'field' => 'params[type]',
					'label' => 'params[type]',
					'rules' => 'trim|in_list[url,chrooted,full]',
				],
				[
					'field' => 'params[command]',
					'label' => 'params[command]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[run_min]',
					'label' => 'params[run_min]',
					'rules' => 'trim|max_length[5]',
				],
				[
					'field' => 'params[run_hour]',
					'label' => 'params[run_hour]',
					'rules' => 'trim|max_length[5]',
				],
				[
					'field' => 'params[run_mday]',
					'label' => 'params[run_mday]',
					'rules' => 'trim|max_length[5]',
				],
				[
					'field' => 'params[run_month]',
					'label' => 'params[run_month]',
					'rules' => 'trim|max_length[5]',
				],
				[
					'field' => 'params[run_wday]',
					'label' => 'params[run_wday]',
					'rules' => 'trim|max_length[5]',
				],
				[
					'field' => 'params[log]',
					'label' => 'params[log]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
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

			return $this->SoapClient->sites_cron_update($this->ID, $client_id, $cron_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * @param int $cron_id
	 *
	 * @return int|array affected rows or error
	 */
	public function cron_delete($cron_id)
	{
		if (is_array($validation = $this->validate_primary_key('cron_id', $cron_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->sites_cron_delete($this->ID, $cron_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Sites > Databases
	 *
	 * @param int $database_id
	 *
	 * @return array web_database.* or error
	 */
	public function database_get($database_id)
	{
		if (is_array($validation = $this->validate_primary_key('database_id', $database_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->sites_database_get($this->ID, $database_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get all records from Sites > Databases by Client
	 *
	 * @param int $client_id
	 *
	 * @return array web_database.* or error
	 */
	public function database_get_all_by_user($client_id)
	{
		if (is_array($validation = $this->validate_primary_key('client_id', $client_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->sites_database_get_all_by_user($this->ID, $client_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Sites > Databases
	 *
	 * @param int   $client_id
	 * @param array $params       server_id, type, parent_domain_id, database_name, database_user_id,
	 *                            database_ro_user_id, database_charset, remote_access, remote_ips,
	 *                            backup_interval, backup_copies, active
	 *
	 * @return int|array web_database.database_id or error
	 */
	public function database_add($client_id, $params)
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
				$this->prepare_validate_pk('params[parent_domain_id]'),
				$this->prepare_validate_pk('params[database_user_id]'),
				[
					'field' => 'params[type]',
					'label' => 'params[type]',
					'rules' => 'trim|in_list[mysql]',
				],
				[
					'field' => 'params[database_name_prefix]',
					'label' => 'params[database_name_prefix]',
					'rules' => 'trim|required|alpha_dash|max_length[50]',
				],
				[
					'field' => 'params[database_name]',
					'label' => 'params[database_name]',
					'rules' => 'trim|required|alpha_dash|max_length[64]',
				],
				[
					'field' => 'params[database_quota]',
					'label' => 'params[database_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[database_ro_user_id]',
					'label' => 'params[database_ro_user_id]',
					'rules' => 'trim|greater_than[0]',
				],
				[
					'field' => 'params[database_charset]',
					'label' => 'params[database_charset]',
					'rules' => 'trim|in_list[utf8,latin1]',
				],
				[
					'field' => 'params[remote_access]',
					'label' => 'params[remote_access]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[backup_interval]',
					'label' => 'params[backup_interval]',
					'rules' => 'trim|in_list[none,daily,weekly,monthly]',
				],
				[
					'field' => 'params[backup_copies]',
					'label' => 'params[backup_copies]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[10]',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
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
				'server_id'            => isset($params['server_id']) ? $params['server_id'] : 0,
				'parent_domain_id'     => isset($params['parent_domain_id']) ? $params['parent_domain_id'] : 0,
				'type'                 => isset($params['type']) ? $params['type'] : 'mysql',
				'database_name_prefix' => isset($params['database_name_prefix']) ? $params['database_name_prefix'] : NULL,
				'database_name'        => isset($params['database_name']) ? $params['database_name_prefix'] . $params['database_name'] : NULL,
				'database_quota'       => isset($params['database_quota']) ? $params['database_quota'] : NULL,
				'database_user_id'     => isset($params['database_user_id']) ? $params['database_user_id'] : NULL,
				'database_ro_user_id'  => isset($params['database_ro_user_id']) ? $params['database_ro_user_id'] : NULL,
				'database_charset'     => isset($params['database_charset']) ? $params['database_charset'] : 'utf8',
				'remote_access'        => isset($params['remote_access']) ? $params['remote_access'] : 'n',
				'remote_ips'           => isset($params['remote_ips']) ? $params['remote_ips'] : NULL,
				'backup_interval'      => isset($params['backup_interval']) ? $params['backup_interval'] : 'none',
				'backup_copies'        => isset($params['backup_copies']) ? $params['backup_copies'] : 1,
				'active'               => isset($params['active']) ? $params['active'] : 'y',
			);

			return $this->SoapClient->sites_database_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in Sites > Databases
	 *
	 * @param int   $client_id
	 * @param int   $database_id
	 * @param array $params       server_id, type, parent_domain_id, database_name, database_user_id,
	 *                            database_ro_user_id, database_charset, remote_access, remote_ips,
	 *                            backup_interval, backup_copies, active
	 *
	 * @return int|array affected rows or error
	 */
	public function database_update($client_id, $database_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'   => $client_id,
				'database_id' => $database_id,
				'params'      => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('database_id'),
				[
					'field' => 'params[server_id]',
					'label' => 'params[server_id]',
					'rules' => 'trim|greater_than[0]',
				],
				[
					'field' => 'params[parent_domain_id]',
					'label' => 'params[parent_domain_id]',
					'rules' => 'trim|greater_than[0]',
				],
				[
					'field' => 'params[type]',
					'label' => 'params[type]',
					'rules' => 'trim|in_list[mysql]',
				],
				[
					'field' => 'params[database_name_prefix]',
					'label' => 'params[database_name_prefix]',
					'rules' => 'trim|alpha_dash|max_length[50]',
				],
				[
					'field' => 'params[database_name]',
					'label' => 'params[database_name]',
					'rules' => 'trim|alpha_dash|max_length[64]',
				],
				[
					'field' => 'params[database_quota]',
					'label' => 'params[database_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]',
				],
				[
					'field' => 'params[database_user_id]',
					'label' => 'params[database_user_id]',
					'rules' => 'trim|greater_than[0]',
				],
				[
					'field' => 'params[database_ro_user_id]',
					'label' => 'params[database_ro_user_id]',
					'rules' => 'trim|greater_than[0]',
				],
				[
					'field' => 'params[database_charset]',
					'label' => 'params[database_charset]',
					'rules' => 'trim|in_list[utf8,latin1]',
				],
				[
					'field' => 'params[remote_access]',
					'label' => 'params[remote_access]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[backup_interval]',
					'label' => 'params[backup_interval]',
					'rules' => 'trim|in_list[none,daily,weekly,monthly]',
				],
				[
					'field' => 'params[backup_copies]',
					'label' => 'params[backup_copies]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[10]',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
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

			return $this->SoapClient->sites_database_update($this->ID, $client_id, $database_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Sites > Databases
	 *
	 * @param int $database_id
	 *
	 * @return int|array affected rows or error
	 */
	public function database_delete($database_id)
	{
		if (is_array($validation = $this->validate_primary_key('database_id', $database_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->sites_database_delete($this->ID, $database_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Sites > Database Users
	 *
	 * @param int $database_user_id
	 *
	 * @return array web_database_user.* or error
	 */
	public function database_user_get($database_user_id)
	{
		if (is_array($validation = $this->validate_primary_key('database_user_id', $database_user_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->sites_database_user_get($this->ID, $database_user_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Sites > Database Users
	 *
	 * @param int   $client_id
	 * @param array $params server_id, database_user_prefix, database_user, database_password
	 *
	 * @return int|array web_database_user.database_user_id or error
	 */
	public function database_user_add($client_id, $params)
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
					'field' => 'params[database_user_prefix]',
					'label' => 'params[database_user_prefix]',
					'rules' => 'trim|required|alpha_dash|max_length[50]',
				],
				[
					'field' => 'params[database_user]',
					'label' => 'params[database_user]',
					'rules' => 'trim|required|alpha_dash|min_length[2]|max_length[64]',
				],
				[
					'field' => 'params[database_password]',
					'label' => 'params[database_password]',
					'rules' => 'trim|required|min_length[5]|max_length[64]',
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
				'server_id'            => isset($params['server_id']) ? $params['server_id'] : 0,
				'database_user_prefix' => isset($params['database_user_prefix']) ? $params['database_user_prefix'] : NULL,
				'database_user'        => isset($params['database_user']) ? $params['database_user_prefix'] . $params['database_user'] : NULL,
				'database_password'    => isset($params['database_password']) ? $params['database_password'] : NULL,
			);

			return $this->SoapClient->sites_database_user_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			$errors = $this->get_error($e->getMessage());
			if (trim($errors['error'][0]) == 'database_user_error_unique')
			{
				return array(
					'error' => ['params[database_user]' => 'The params[database_user] field must contain a unique value.'],
				);
			}

			return $errors;
		}
	}


	/**
	 * Update one record in Sites > Database Users
	 *
	 * @param int   $client_id
	 * @param int   $database_user_id
	 * @param array $params server_id, database_user, database_password
	 *
	 * @return int|array affected rows or error
	 */
	public function database_user_update($client_id, $database_user_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'        => $client_id,
				'database_user_id' => $database_user_id,
				'params'           => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('database_user_id'),
				[
					'field' => 'params[database_user_prefix]',
					'label' => 'params[database_user_prefix]',
					'rules' => 'trim|required|alpha_dash|max_length[50]',
				],
				[
					'field' => 'params[database_user]',
					'label' => 'params[database_user]',
					'rules' => 'trim|required|alpha_dash|min_length[2]|max_length[64]',
				],
				[
					'field' => 'params[database_password]',
					'label' => 'params[database_password]',
					'rules' => 'trim|min_length[5]|max_length[64]',
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

			return $this->SoapClient->sites_database_user_update($this->ID, $client_id, $database_user_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Sites > Database Users
	 *
	 * @param int $database_user_id
	 *
	 * @return int|array affected rows or error
	 */
	public function database_user_delete($database_user_id)
	{
		if (is_array($validation = $this->validate_primary_key('database_user_id', $database_user_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->sites_database_user_delete($this->ID, $database_user_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Sites > FTP-Accounts > FTP-User
	 *
	 * @param int $ftp_user_id
	 *
	 * @return array ftp_user.* or error
	 */
	public function ftp_user_get($ftp_user_id)
	{
		if (is_array($validation = $this->validate_primary_key('ftp_user_id', $ftp_user_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->sites_ftp_user_get($this->ID, $ftp_user_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Sites > FTP-Accounts > FTP-User
	 *
	 * @param int   $client_id
	 * @param array $params   server_id, username, password, quota_size, active, uid, gid, dir,
	 *                        quota_files, ul_ratio, dl_ratio, ul_bandwidth, dl_bandwidth, expires
	 *
	 * @return array ftp_user.ftp_user_id or error
	 */
	public function ftp_user_add($client_id, $params)
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
				$this->prepare_validate_pk('params[parent_domain_id]'),
				[
					'field' => 'params[username_prefix]',
					'label' => 'params[username_prefix]',
					'rules' => 'trim|required|alpha_dash|max_length[50]',
				],
				[
					'field' => 'params[username]',
					'label' => 'params[username]',
					'rules' => 'trim|required|alpha_dash|max_length[64]',
				],
				[
					'field' => 'params[password]',
					'label' => 'params[password]',
					'rules' => 'trim|required|max_length[50]',
				],
				[
					'field' => 'params[quota_size]',
					'label' => 'params[quota_size]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[uid]',
					'label' => 'params[uid]',
					'rules' => 'trim|required|alpha_dash|max_length[64]',
				],
				[
					'field' => 'params[gid]',
					'label' => 'params[gid]',
					'rules' => 'trim|required|alpha_dash|max_length[64]',
				],
				[
					'field' => 'params[dir]',
					'label' => 'params[dir]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[quota_files]',
					'label' => 'params[quota_files]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[ul_ratio]',
					'label' => 'params[ul_ratio]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[dl_ratio]',
					'label' => 'params[dl_ratio]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[ul_bandwidth]',
					'label' => 'params[ul_bandwidth]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[dl_bandwidth]',
					'label' => 'params[dl_bandwidth]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[expires]',
					'label' => 'params[expires]',
					'rules' => 'trim|max_length[19]',
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
				'parent_domain_id' => isset($params['parent_domain_id']) ? $params['parent_domain_id'] : 0,
				'username_prefix'  => isset($params['username_prefix']) ? $params['username_prefix'] : '',
				'username'         => isset($params['username']) ? $params['username_prefix'] . $params['username'] : NULL,
				'password'         => isset($params['password']) ? $params['password'] : NULL,
				'quota_size'       => isset($params['quota_size']) ? $params['quota_size'] : -1,
				'active'           => isset($params['active']) ? $params['active'] : 'y',
				'uid'              => isset($params['uid']) ? $params['uid'] : NULL,
				'gid'              => isset($params['gid']) ? $params['gid'] : NULL,
				'dir'              => isset($params['dir']) ? $params['dir'] : NULL,
				'quota_files'      => isset($params['quota_files']) ? $params['quota_files'] : -1,
				'ul_ratio'         => isset($params['ul_ratio']) ? $params['ul_ratio'] : -1,
				'dl_ratio'         => isset($params['dl_ratio']) ? $params['dl_ratio'] : -1,
				'ul_bandwidth'     => isset($params['ul_bandwidth']) ? $params['ul_bandwidth'] : -1,
				'dl_bandwidth'     => isset($params['dl_bandwidth']) ? $params['dl_bandwidth'] : -1,
				'expires'          => isset($params['expires']) ? $params['expires'] : NULL,
			);

			return $this->SoapClient->sites_ftp_user_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			$errors = $this->get_error($e->getMessage());
			if (trim($errors['error'][0]) == 'username_error_unique')
			{
				return array(
					'error' => ['params[username]' => 'The params[username] field must contain a unique value.'],
				);
			}

			return $errors;
		}
	}


	/**
	 * Update one record in Sites > FTP-Accounts > FTP-User
	 *
	 * @param int   $client_id
	 * @param int   $ftp_user_id
	 * @param array $params   server_id, username, password, quota_size, active, uid, gid, dir,
	 *                        quota_files, ul_ratio, dl_ratio, ul_bandwidth, dl_bandwidth, expires
	 *
	 * @return int|array affected rows or error
	 */
	public function ftp_user_update($client_id, $ftp_user_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'   => $client_id,
				'ftp_user_id' => $ftp_user_id,
				'params'      => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('ftp_user_id'),
				[
					'field' => 'params[server_id]',
					'label' => 'params[server_id]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[parent_domain_id]',
					'label' => 'params[parent_domain_id]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[username_prefix]',
					'label' => 'params[username_prefix]',
					'rules' => 'trim|alpha_dash|max_length[50]',
				],
				[
					'field' => 'params[username]',
					'label' => 'params[username]',
					'rules' => 'trim|alpha_dash|max_length[64]',
				],
				[
					'field' => 'params[password]',
					'label' => 'params[password]',
					'rules' => 'trim|max_length[50]',
				],
				[
					'field' => 'params[quota_size]',
					'label' => 'params[quota_size]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[uid]',
					'label' => 'params[uid]',
					'rules' => 'trim|alpha_dash|max_length[64]',
				],
				[
					'field' => 'params[gid]',
					'label' => 'params[gid]',
					'rules' => 'trim|alpha_dash|max_length[64]',
				],
				[
					'field' => 'params[dir]',
					'label' => 'params[dir]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[quota_files]',
					'label' => 'params[quota_files]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[ul_ratio]',
					'label' => 'params[ul_ratio]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[dl_ratio]',
					'label' => 'params[dl_ratio]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[ul_bandwidth]',
					'label' => 'params[ul_bandwidth]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[dl_bandwidth]',
					'label' => 'params[dl_bandwidth]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[expires]',
					'label' => 'params[expires]',
					'rules' => 'trim|max_length[19]',
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

			return $this->SoapClient->sites_ftp_user_update($this->ID, $client_id, $ftp_user_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Sites > FTP-Accounts > FTP-User
	 *
	 * @param int $ftp_user_id
	 *
	 * @return int|array affected rows or error
	 */
	public function ftp_user_delete($ftp_user_id)
	{
		if (is_array($validation = $this->validate_primary_key('ftp_user_id', $ftp_user_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->sites_ftp_user_delete($this->ID, $ftp_user_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get server.config[server] by ftp_user
	 *
	 * @param string $ftp_user
	 *
	 * @return array server.config[server] or error
	 */
	public function ftp_user_server_get($ftp_user)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['ftp_user' => $ftp_user]);
			$rules = array(
				[
					'field' => 'ftp_user',
					'label' => 'ftp_user',
					'rules' => 'trim|required|alpha_dash|max_length[64]',
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

			return $this->get_empty($this->SoapClient->sites_ftp_user_server_get($this->ID, $ftp_user));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Sites > Shell User
	 *
	 * @param int $shell_user_id
	 *
	 * @return array shell_user.* or error
	 */
	public function shell_user_get($shell_user_id)
	{
		if (is_array($validation = $this->validate_primary_key('shell_user_id', $shell_user_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->sites_shell_user_get($this->ID, $shell_user_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Sites > Shell User
	 *
	 * @param int   $client_id
	 * @param array $params server_id, parent_domain_id, username_prefix, username, password,
	 *                      quota_size, active, puser, pgroup, shell, dir, chroot, ssh_rsa
	 *
	 * @return int|array shell_user.shell_user_id or error
	 */
	public function shell_user_add($client_id, $params)
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
				$this->prepare_validate_pk('params[parent_domain_id]'),
				[
					'field' => 'params[username_prefix]',
					'label' => 'params[username_prefix]',
					'rules' => 'trim|required|alpha_dash|max_length[50]',
				],
				[
					'field' => 'params[username]',
					'label' => 'params[username]',
					'rules' => 'trim|required|alpha_dash|max_length[64]',
				],
				[
					'field' => 'params[password]',
					'label' => 'params[password]',
					'rules' => 'trim|required|max_length[64]',
				],
				[
					'field' => 'params[quota_size]',
					'label' => 'params[quota_size]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[puser]',
					'label' => 'params[puser]',
					'rules' => 'trim|required|alpha_dash|max_length[255]',
				],
				[
					'field' => 'params[pgroup]',
					'label' => 'params[pgroup]',
					'rules' => 'trim|required|alpha_dash|max_length[255]',
				],
				[
					'field' => 'params[shell]',
					'label' => 'params[shell]',
					'rules' => 'trim|in_list[/bin/bash,/bin/dash]',
				],
				[
					'field' => 'params[dir]',
					'label' => 'params[dir]',
					'rules' => 'trim|required|max_length[255]',
				],
				[
					'field' => 'params[chroot]',
					'label' => 'params[chroot]',
					'rules' => 'trim|in_list[no,jailkit]',
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
				'parent_domain_id' => isset($params['parent_domain_id']) ? $params['parent_domain_id'] : 0,
				'username_prefix'  => isset($params['username_prefix']) ? $params['username_prefix'] : '',
				'username'         => isset($params['username']) ? $params['username_prefix'] . $params['username'] : NULL,
				'password'         => isset($params['password']) ? $params['password'] : NULL,
				'quota_size'       => isset($params['quota_size']) ? $params['quota_size'] : -1,
				'active'           => isset($params['active']) ? $params['active'] : 'y',
				'puser'            => isset($params['puser']) ? $params['puser'] : NULL,
				'pgroup'           => isset($params['pgroup']) ? $params['pgroup'] : NULL,
				'shell'            => isset($params['shell']) ? $params['shell'] : '/bin/bash',
				'dir'              => isset($params['dir']) ? $params['dir'] : NULL,
				'chroot'           => isset($params['chroot']) ? $params['chroot'] : '',
				'ssh_rsa'          => isset($params['ssh_rsa']) ? $params['ssh_rsa'] : NULL,
			);

			return $this->SoapClient->sites_shell_user_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in Sites > Shell User
	 *
	 * @param int   $client_id
	 * @param int   $shell_user_id
	 * @param array $params server_id, parent_domain_id, username_prefix, username, password,
	 *                      quota_size, active, puser, pgroup, shell, dir, chroot, ssh_rsa
	 *
	 * @return int|array affected rows or error
	 */
	public function shell_user_update($client_id, $shell_user_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'     => $client_id,
				'shell_user_id' => $shell_user_id,
				'params'        => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('shell_user_id'),
				[
					'field' => 'params[server_id]',
					'label' => 'params[server_id]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[parent_domain_id]',
					'label' => 'params[parent_domain_id]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[username_prefix]',
					'label' => 'params[username_prefix]',
					'rules' => 'trim|alpha_dash|max_length[50]',
				],
				[
					'field' => 'params[username]',
					'label' => 'params[username]',
					'rules' => 'trim|alpha_dash|max_length[64]',
				],
				[
					'field' => 'params[password]',
					'label' => 'params[password]',
					'rules' => 'trim|max_length[64]',
				],
				[
					'field' => 'params[quota_size]',
					'label' => 'params[quota_size]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[puser]',
					'label' => 'params[puser]',
					'rules' => 'trim|alpha_dash|max_length[255]',
				],
				[
					'field' => 'params[pgroup]',
					'label' => 'params[pgroup]',
					'rules' => 'trim|alpha_dash|max_length[255]',
				],
				[
					'field' => 'params[shell]',
					'label' => 'params[shell]',
					'rules' => 'trim|in_list[/bin/bash,/bin/dash]',
				],
				[
					'field' => 'params[dir]',
					'label' => 'params[dir]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[chroot]',
					'label' => 'params[chroot]',
					'rules' => 'trim|in_list[no,jailkit]',
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

			return $this->SoapClient->sites_shell_user_update($this->ID, $client_id, $shell_user_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Sites > Shell User
	 *
	 * @param int $shell_user_id
	 *
	 * @return int|array affected rows or error
	 */
	public function shell_user_delete($shell_user_id)
	{
		if (is_array($validation = $this->validate_primary_key('shell_user_id', $shell_user_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->sites_shell_user_delete($this->ID, $shell_user_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Sites > Websites
	 *
	 * @param int $domain_id
	 *
	 * @return array web_domain.* or error
	 */
	public function web_domain_get($domain_id)
	{
		if (is_array($validation = $this->validate_primary_key('domain_id', $domain_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->sites_web_domain_get($this->ID, $domain_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Sites > Websites
	 *
	 * @param int   $client_id
	 * @param array $params server_id, ip_address, ipv6_address, domain, hd_quota, traffic_quota, cgi, ssi, suexec,
	 *                      errordocs, is_subdomainwww, subdomain, php, ruby, python, perl, redirect_type,
	 *                      redirect_path, seo_redirect, rewrite_to_https, ssl, ssl_letsencrypt, ssl_state,
	 *                      ssl_locality, ssl_organisation, ssl_organisation_unit, ssl_country, ssl_domain, ssl_request,
	 *                      ssl_cert, ssl_bundle, ssl_key, ssl_action, stats_password, stats_type, allow_override,
	 *                      apache_directives, nginx_directives, php_fpm_use_socket, pm, pm_max_children,
	 *                      pm_start_servers, pm_min_spare_servers, pm_max_spare_servers, pm_process_idle_timeout,
	 *                      pm_max_requests, php_open_basedir, custom_php_ini, backup_interval, backup_copies,
	 *                      backup_excludes, active, traffic_quota_lock, fastcgi_php_version, proxy_directives,
	 *                      enable_spdy, rewrite_rules, added_date, added_by, directive_snippets_id, enable_pagespeed,
	 *                      http_port, https_port
	 *
	 * @param bool  $readonly
	 *
	 * @return int|array web_domain.domain_id or error
	 */
	public function web_domain_add($client_id, $params, $readonly = FALSE)
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
					'field' => 'params[ipv6_address]',
					'label' => 'params[ipv6_address]',
					'rules' => 'trim|valid_ip[ipv6]',
				],
				[
					'field' => 'params[domain]',
					'label' => 'params[domain]',
					'rules' => 'trim|required|valid_url',
				],
				[
					'field' => 'params[hd_quota]',
					'label' => 'params[hd_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[traffic_quota]',
					'label' => 'params[traffic_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[cgi]',
					'label' => 'params[cgi]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssi]',
					'label' => 'params[ssi]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[suexec]',
					'label' => 'params[suexec]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[errordocs]',
					'label' => 'params[errordocs]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[1]',
				],
				[
					'field' => 'params[is_subdomainwww]',
					'label' => 'params[is_subdomainwww]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[1]',
				],
				[
					'field' => 'params[subdomain]',
					'label' => 'params[subdomain]',
					'rules' => 'trim|in_list[none,www,*]',
				],
				[
					'field' => 'params[php]',
					'label' => 'params[php]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[ruby]',
					'label' => 'params[ruby]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[python]',
					'label' => 'params[python]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[perl]',
					'label' => 'params[perl]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[redirect_type]',
					'label' => 'params[redirect_type]',
					'rules' => 'trim|in_list[no,last,break,redirect,permanent,proxy]',
				],
				[
					'field' => 'params[redirect_path]',
					'label' => 'params[redirect_path]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[seo_redirect]',
					'label' => 'params[seo_redirect]',
					'rules' => 'trim|in_list[non_www_to_www,www_to_non_www,*_domain_tld_to_domain_tld,*_domain_tld_to_www_domain_tld,*_to_domain_tld,*_to_www_domain_tld]',
				],
				[
					'field' => 'params[rewrite_to_https]',
					'label' => 'params[rewrite_to_https]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl]',
					'label' => 'params[ssl]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl_letsencrypt]',
					'label' => 'params[ssl_letsencrypt]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl_state]',
					'label' => 'params[ssl_state]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_locality]',
					'label' => 'params[ssl_locality]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_organisation]',
					'label' => 'params[ssl_organisation]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_organisation_unit]',
					'label' => 'params[ssl_organisation_unit]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_country]',
					'label' => 'params[ssl_country]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_domain]',
					'label' => 'params[ssl_domain]',
					'rules' => 'trim|valid_url',
				],
				[
					'field' => 'params[ssl_action]',
					'label' => 'params[ssl_action]',
					'rules' => 'trim|in_list[create,save,del]',
				],
				[
					'field' => 'params[stats_password]',
					'label' => 'params[stats_password]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[stats_type]',
					'label' => 'params[stats_type]',
					'rules' => 'trim|in_list[awstats,webalizer]',
				],
				[
					'field' => 'params[allow_override]',
					'label' => 'params[allow_override]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[apache_directives]',
					'label' => 'params[apache_directives]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[nginx_directives]',
					'label' => 'params[nginx_directives]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[php_fpm_use_socket]',
					'label' => 'params[php_fpm_use_socket]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[pm]',
					'label' => 'params[pm]',
					'rules' => 'trim|in_list[static,dynamic,ondemand]',
				],
				[
					'field' => 'params[pm_max_children]',
					'label' => 'params[pm_max_children]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_start_servers]',
					'label' => 'params[pm_start_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_min_spare_servers]',
					'label' => 'params[pm_min_spare_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_max_spare_servers]',
					'label' => 'params[pm_max_spare_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_process_idle_timeout]',
					'label' => 'params[pm_process_idle_timeout]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_max_requests]',
					'label' => 'params[pm_max_requests]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[php_open_basedir]',
					'label' => 'params[php_open_basedir]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[backup_interval]',
					'label' => 'params[backup_interval]',
					'rules' => 'trim|in_list[daily,weekly,monthly]',
				],
				[
					'field' => 'params[backup_copies]',
					'label' => 'params[backup_copies]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[10]',
				],
				[
					'field' => 'params[backup_excludes]',
					'label' => 'params[backup_excludes]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[traffic_quota_lock]',
					'label' => 'params[traffic_quota_lock]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[fastcgi_php_version]',
					'label' => 'params[fastcgi_php_version]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[enable_spdy]',
					'label' => 'params[enable_spdy]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[added_date]',
					'label' => 'params[added_date]',
					'rules' => 'trim|max_length[10]',
				],
				[
					'field' => 'params[added_by]',
					'label' => 'params[added_by]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[directive_snippets_id]',
					'label' => 'params[directive_snippets_id]',
					'rules' => 'trim|max_length[11]',
				],
				[
					'field' => 'params[enable_pagespeed]',
					'label' => 'params[enable_pagespeed]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[http_port]',
					'label' => 'params[http_port]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[65535]',
				],
				[
					'field' => 'params[https_port]',
					'label' => 'params[https_port]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[65535]',
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
				'server_id'               => isset($params['server_id']) ? $params['server_id'] : 0,
				'ip_address'              => isset($params['ip_address']) ? $params['ip_address'] : '*',
				'ipv6_address'            => isset($params['ipv6_address']) ? $params['ipv6_address'] : NULL,
				'domain'                  => isset($params['domain']) ? $params['domain'] : NULL,
				'type'                    => 'vhost',
				'parent_domain_id'        => 0,
				'vhost_type'              => 'name',
				'hd_quota'                => isset($params['hd_quota']) ? $params['hd_quota'] : 0,
				'traffic_quota'           => isset($params['traffic_quota']) ? $params['traffic_quota'] : -1,
				'cgi'                     => isset($params['cgi']) ? $params['cgi'] : 'y',
				'ssi'                     => isset($params['ssi']) ? $params['ssi'] : 'y',
				'suexec'                  => isset($params['suexec']) ? $params['suexec'] : 'y',
				'errordocs'               => isset($params['errordocs']) ? $params['errordocs'] : 1,
				'is_subdomainwww'         => isset($params['is_subdomainwww']) ? $params['is_subdomainwww'] : 1,
				'subdomain'               => isset($params['subdomain']) ? $params['subdomain'] : 'none',
				'php'                     => isset($params['php']) ? $params['php'] : 'y',
				'ruby'                    => isset($params['ruby']) ? $params['ruby'] : 'n',
				'python'                  => isset($params['python']) ? $params['python'] : 'n',
				'perl'                    => isset($params['perl']) ? $params['perl'] : 'n',
				'redirect_type'           => isset($params['redirect_type']) ? $params['redirect_type'] : NULL,
				'redirect_path'           => isset($params['redirect_path']) ? $params['redirect_path'] : NULL,
				'seo_redirect'            => isset($params['seo_redirect']) ? $params['seo_redirect'] : NULL,
				'rewrite_to_https'        => isset($params['rewrite_to_https']) ? $params['rewrite_to_https'] : 'n',
				'ssl'                     => isset($params['ssl']) ? $params['ssl'] : 'n',
				'ssl_letsencrypt'         => isset($params['ssl_letsencrypt']) ? $params['ssl_letsencrypt'] : 'n',
				'ssl_state'               => isset($params['ssl_state']) ? $params['ssl_state'] : NULL,
				'ssl_locality'            => isset($params['ssl_locality']) ? $params['ssl_locality'] : NULL,
				'ssl_organisation'        => isset($params['ssl_organisation']) ? $params['ssl_organisation'] : NULL,
				'ssl_organisation_unit'   => isset($params['ssl_organisation_unit']) ? $params['ssl_organisation_unit'] : NULL,
				'ssl_country'             => isset($params['ssl_country']) ? $params['ssl_country'] : NULL,
				'ssl_domain'              => isset($params['ssl_domain']) ? $params['ssl_domain'] : NULL,
				'ssl_request'             => isset($params['ssl_request']) ? $params['ssl_request'] : NULL,
				'ssl_cert'                => isset($params['ssl_cert']) ? $params['ssl_cert'] : NULL,
				'ssl_bundle'              => isset($params['ssl_bundle']) ? $params['ssl_bundle'] : NULL,
				'ssl_key'                 => isset($params['ssl_key']) ? $params['ssl_key'] : NULL,
				'ssl_action'              => isset($params['ssl_action']) ? $params['ssl_action'] : NULL,
				'stats_password'          => isset($params['stats_password']) ? $params['stats_password'] : NULL,
				'stats_type'              => isset($params['stats_type']) ? $params['stats_type'] : 'webalizer',
				'allow_override'          => isset($params['allow_override']) ? $params['allow_override'] : 'All',
				'apache_directives'       => isset($params['apache_directives']) ? $params['apache_directives'] : NULL,
				'nginx_directives'        => isset($params['nginx_directives']) ? $params['nginx_directives'] : NULL,
				'php_fpm_use_socket'      => isset($params['php_fpm_use_socket']) ? $params['php_fpm_use_socket'] : 'y',
				'pm'                      => isset($params['pm']) ? $params['pm'] : 'dynamic',
				'pm_max_children'         => isset($params['pm_max_children']) ? $params['pm_max_children'] : 10,
				'pm_start_servers'        => isset($params['pm_start_servers']) ? $params['pm_start_servers'] : 2,
				'pm_min_spare_servers'    => isset($params['pm_min_spare_servers']) ? $params['pm_min_spare_servers'] : 1,
				'pm_max_spare_servers'    => isset($params['pm_max_spare_servers']) ? $params['pm_max_spare_servers'] : 5,
				'pm_process_idle_timeout' => isset($params['pm_process_idle_timeout']) ? $params['pm_process_idle_timeout'] : 10,
				'pm_max_requests'         => isset($params['pm_max_requests']) ? $params['pm_max_requests'] : 0,
				'php_open_basedir'        => isset($params['php_open_basedir']) ? $params['php_open_basedir'] : NULL,
				'custom_php_ini'          => isset($params['custom_php_ini']) ? $params['custom_php_ini'] : NULL,
				'backup_interval'         => isset($params['backup_interval']) ? $params['backup_interval'] : 'none',
				'backup_copies'           => isset($params['backup_copies']) ? $params['backup_copies'] : 1,
				'backup_excludes'         => isset($params['backup_excludes']) ? $params['backup_excludes'] : NULL,
				'active'                  => isset($params['active']) ? $params['active'] : 'y',
				'traffic_quota_lock'      => isset($params['traffic_quota_lock']) ? $params['traffic_quota_lock'] : 'n',
				'fastcgi_php_version'     => isset($params['fastcgi_php_version']) ? $params['fastcgi_php_version'] : NULL,
				'proxy_directives'        => isset($params['proxy_directives']) ? $params['proxy_directives'] : NULL,
				'enable_spdy'             => isset($params['enable_spdy']) ? $params['enable_spdy'] : 'n',
				'rewrite_rules'           => isset($params['rewrite_rules']) ? $params['rewrite_rules'] : NULL,
				'added_date'              => isset($params['added_date']) ? $params['added_date'] : date('Y-m-d'),
				'added_by'                => isset($params['added_by']) ? $params['added_by'] : $this->CI->config->item('ispconfig_username'),
				'directive_snippets_id'   => isset($params['directive_snippets_id']) ? $params['directive_snippets_id'] : 0,
				'enable_pagespeed'        => isset($params['enable_pagespeed']) ? $params['enable_pagespeed'] : 'n',
				'http_port'               => isset($params['http_port']) ? $params['http_port'] : 80,
				'https_port'              => isset($params['https_port']) ? $params['https_port'] : 443,
			);

			return $this->SoapClient->sites_web_domain_add($this->ID, $client_id, $params, $readonly);
		}
		catch (SoapFault $e)
		{
			$errors = $this->get_error($e->getMessage());
			if (trim($errors['error'][0]) == 'domain_error_unique')
			{
				return array(
					'error' => ['params[domain]' => 'The params[domain] field must contain a unique value.'],
				);
			}

			return $errors;
		}
	}


	/**
	 * Update one record on Sites > Websites
	 *
	 * @param int   $client_id
	 * @param int   $domain_id
	 * @param array $params server_id, ip_address, ipv6_address, domain, hd_quota, traffic_quota, cgi, ssi, suexec,
	 *                      errordocs, is_subdomainwww, subdomain, php, ruby, python, perl, redirect_type,
	 *                      redirect_path, seo_redirect, rewrite_to_https, ssl, ssl_letsencrypt, ssl_state,
	 *                      ssl_locality, ssl_organisation, ssl_organisation_unit, ssl_country, ssl_domain, ssl_request,
	 *                      ssl_cert, ssl_bundle, ssl_key, ssl_action, stats_password, stats_type, allow_override,
	 *                      apache_directives, nginx_directives, php_fpm_use_socket, pm, pm_max_children,
	 *                      pm_start_servers, pm_min_spare_servers, pm_max_spare_servers, pm_process_idle_timeout,
	 *                      pm_max_requests, php_open_basedir, custom_php_ini, backup_interval, backup_copies,
	 *                      backup_excludes, active, traffic_quota_lock, fastcgi_php_version, proxy_directives,
	 *                      enable_spdy, rewrite_rules, added_date, added_by, directive_snippets_id, enable_pagespeed,
	 *                      http_port, https_port
	 *
	 *
	 * @return int|array affected rows or error
	 */
	public function web_domain_update($client_id, $domain_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'domain_id' => $domain_id,
				'params'    => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('domain_id'),
				[
					'field' => 'params[server_id]',
					'label' => 'params[server_id]',
					'rules' => 'trim|greater_than[0]',
				],
				[
					'field' => 'params[ip_address]',
					'label' => 'params[ip_address]',
					'rules' => 'trim|valid_ip',
				],
				[
					'field' => 'params[ipv6_address]',
					'label' => 'params[ipv6_address]',
					'rules' => 'trim|valid_ip[ipv6]',
				],
				[
					'field' => 'params[domain]',
					'label' => 'params[domain]',
					'rules' => 'trim|valid_url',
				],
				[
					'field' => 'params[hd_quota]',
					'label' => 'params[hd_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[traffic_quota]',
					'label' => 'params[traffic_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[cgi]',
					'label' => 'params[cgi]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssi]',
					'label' => 'params[ssi]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[suexec]',
					'label' => 'params[suexec]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[errordocs]',
					'label' => 'params[errordocs]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[1]',
				],
				[
					'field' => 'params[is_subdomainwww]',
					'label' => 'params[is_subdomainwww]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[1]',
				],
				[
					'field' => 'params[subdomain]',
					'label' => 'params[subdomain]',
					'rules' => 'trim|in_list[none,www,*]',
				],
				[
					'field' => 'params[php]',
					'label' => 'params[php]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[ruby]',
					'label' => 'params[ruby]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[python]',
					'label' => 'params[python]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[perl]',
					'label' => 'params[perl]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[redirect_type]',
					'label' => 'params[redirect_type]',
					'rules' => 'trim|in_list[no,last,break,redirect,permanent,proxy]',
				],
				[
					'field' => 'params[redirect_path]',
					'label' => 'params[redirect_path]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[seo_redirect]',
					'label' => 'params[seo_redirect]',
					'rules' => 'trim|in_list[non_www_to_www,www_to_non_www,*_domain_tld_to_domain_tld,*_domain_tld_to_www_domain_tld,*_to_domain_tld,*_to_www_domain_tld]',
				],
				[
					'field' => 'params[rewrite_to_https]',
					'label' => 'params[rewrite_to_https]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl]',
					'label' => 'params[ssl]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl_letsencrypt]',
					'label' => 'params[ssl_letsencrypt]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl_state]',
					'label' => 'params[ssl_state]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_locality]',
					'label' => 'params[ssl_locality]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_organisation]',
					'label' => 'params[ssl_organisation]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_organisation_unit]',
					'label' => 'params[ssl_organisation_unit]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_country]',
					'label' => 'params[ssl_country]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_domain]',
					'label' => 'params[ssl_domain]',
					'rules' => 'trim|valid_url',
				],
				[
					'field' => 'params[ssl_action]',
					'label' => 'params[ssl_action]',
					'rules' => 'trim|in_list[create,save,del]',
				],
				[
					'field' => 'params[stats_password]',
					'label' => 'params[stats_password]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[stats_type]',
					'label' => 'params[stats_type]',
					'rules' => 'trim|in_list[awstats,webalizer]',
				],
				[
					'field' => 'params[allow_override]',
					'label' => 'params[allow_override]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[apache_directives]',
					'label' => 'params[apache_directives]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[nginx_directives]',
					'label' => 'params[nginx_directives]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[php_fpm_use_socket]',
					'label' => 'params[php_fpm_use_socket]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[pm]',
					'label' => 'params[pm]',
					'rules' => 'trim|in_list[static,dynamic,ondemand]',
				],
				[
					'field' => 'params[pm_max_children]',
					'label' => 'params[pm_max_children]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_start_servers]',
					'label' => 'params[pm_start_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_min_spare_servers]',
					'label' => 'params[pm_min_spare_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_max_spare_servers]',
					'label' => 'params[pm_max_spare_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_process_idle_timeout]',
					'label' => 'params[pm_process_idle_timeout]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_max_requests]',
					'label' => 'params[pm_max_requests]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[php_open_basedir]',
					'label' => 'params[php_open_basedir]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[backup_interval]',
					'label' => 'params[backup_interval]',
					'rules' => 'trim|in_list[daily,weekly,monthly]',
				],
				[
					'field' => 'params[backup_copies]',
					'label' => 'params[backup_copies]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[10]',
				],
				[
					'field' => 'params[backup_excludes]',
					'label' => 'params[backup_excludes]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[traffic_quota_lock]',
					'label' => 'params[traffic_quota_lock]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[fastcgi_php_version]',
					'label' => 'params[fastcgi_php_version]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[enable_spdy]',
					'label' => 'params[enable_spdy]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[added_date]',
					'label' => 'params[added_date]',
					'rules' => 'trim|max_length[10]',
				],
				[
					'field' => 'params[added_by]',
					'label' => 'params[added_by]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[directive_snippets_id]',
					'label' => 'params[directive_snippets_id]',
					'rules' => 'trim|max_length[11]',
				],
				[
					'field' => 'params[enable_pagespeed]',
					'label' => 'params[enable_pagespeed]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[http_port]',
					'label' => 'params[http_port]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[65535]',
				],
				[
					'field' => 'params[https_port]',
					'label' => 'params[https_port]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[65535]',
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

			return $this->SoapClient->sites_web_domain_update($this->ID, $client_id, $domain_id, $params);
		}
		catch (SoapFault $e)
		{
			$errors = $this->get_error($e->getMessage());
			if (trim($errors['error'][0]) == 'domain_error_unique')
			{
				return array(
					'error' => ['params[domain]' => 'The params[domain] field must contain a unique value.'],
				);
			}

			return $errors;
		}
	}


	/**
	 * Delete one record in Sites > Websites
	 *
	 * @param int $domain_id
	 *
	 * @return int|array affected rows or error
	 */
	public function web_domain_delete($domain_id)
	{
		if (is_array($validation = $this->validate_primary_key('domain_id', $domain_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->sites_web_domain_delete($this->ID, $domain_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Set status in Sites > Websites > Web Domain.Active
	 *
	 * @param int    $domain_id
	 * @param string $status active or inactive
	 *
	 * @return int|array affected rows or error
	 */
	public function web_domain_set_status($domain_id, $status)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'domain_id' => $domain_id,
				'status'    => $status,
			]);
			$rules = array(
				$this->prepare_validate_pk('domain_id'),
				[
					'field' => 'status',
					'label' => 'status',
					'rules' => 'trim|required|in_list[active,inactive]',
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

			return $this->SoapClient->sites_web_domain_set_status($this->ID, $domain_id, $status);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	public function web_vhost_aliasdomain_get()
	{
		// Todo
	}


	public function web_vhost_aliasdomain_add()
	{
		// Todo
	}


	public function web_vhost_aliasdomain_update()
	{
		// Todo
	}


	public function web_vhost_aliasdomain_delete()
	{
		// Todo
	}


	/**
	 * Get one record from Sites > Subdomain for website
	 *
	 * @param int $domain_id
	 *
	 * @return array webdomain.* or error
	 */
	public function web_vhost_subdomain_get($domain_id)
	{
		if (is_array($validation = $this->validate_primary_key('domain_id', $domain_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->sites_web_vhost_subdomain_get($this->ID, $domain_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Sites > Subdomain for website
	 *
	 * @param int   $client_id
	 * @param array $params server_id, ip_address, ipv6_address, domain, hd_quota, traffic_quota, cgi, ssi, suexec,
	 *                      errordocs, is_subdomainwww, subdomain, php, ruby, python, perl, redirect_type,
	 *                      redirect_path, seo_redirect, rewrite_to_https, ssl, ssl_letsencrypt, ssl_state,
	 *                      ssl_locality, ssl_organisation, ssl_organisation_unit, ssl_country, ssl_domain, ssl_request,
	 *                      ssl_cert, ssl_bundle, ssl_key, ssl_action, stats_password, stats_type, allow_override,
	 *                      apache_directives, nginx_directives, php_fpm_use_socket, pm, pm_max_children,
	 *                      pm_start_servers, pm_min_spare_servers, pm_max_spare_servers, pm_process_idle_timeout,
	 *                      pm_max_requests, php_open_basedir, custom_php_ini, backup_interval, backup_copies,
	 *                      backup_excludes, active, traffic_quota_lock, fastcgi_php_version, proxy_directives,
	 *                      enable_spdy, rewrite_rules, added_date, added_by, directive_snippets_id, enable_pagespeed,
	 *                      http_port, https_port
	 *
	 * @return int|array web_domain.domain_id or error
	 */
	public function web_vhost_subdomain_add($client_id, $params)
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
				$this->prepare_validate_pk('params[parent_domain_id]'),
				[
					'field' => 'params[ip_address]',
					'label' => 'params[ip_address]',
					'rules' => 'trim|valid_ip',
				],
				[
					'field' => 'params[ipv6_address]',
					'label' => 'params[ipv6_address]',
					'rules' => 'trim|valid_ip[ipv6]',
				],
				[
					'field' => 'params[domain]',
					'label' => 'params[domain]',
					'rules' => 'trim|required|valid_url',
				],
				[
					'field' => 'params[web_folder]',
					'label' => 'params[web_folder]',
					'rules' => 'trim|required|max_length[100]',
				],
				[
					'field' => 'params[hd_quota]',
					'label' => 'params[hd_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[traffic_quota]',
					'label' => 'params[traffic_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[cgi]',
					'label' => 'params[cgi]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssi]',
					'label' => 'params[ssi]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[suexec]',
					'label' => 'params[suexec]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[errordocs]',
					'label' => 'params[errordocs]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[1]',
				],
				[
					'field' => 'params[is_subdomainwww]',
					'label' => 'params[is_subdomainwww]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[1]',
				],
				[
					'field' => 'params[subdomain]',
					'label' => 'params[subdomain]',
					'rules' => 'trim|in_list[none,www,*]',
				],
				[
					'field' => 'params[php]',
					'label' => 'params[php]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[ruby]',
					'label' => 'params[ruby]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[python]',
					'label' => 'params[python]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[perl]',
					'label' => 'params[perl]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[redirect_type]',
					'label' => 'params[redirect_type]',
					'rules' => 'trim|in_list[no,last,break,redirect,permanent,proxy]',
				],
				[
					'field' => 'params[redirect_path]',
					'label' => 'params[redirect_path]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[seo_redirect]',
					'label' => 'params[seo_redirect]',
					'rules' => 'trim|in_list[non_www_to_www,www_to_non_www,*_domain_tld_to_domain_tld,*_domain_tld_to_www_domain_tld,*_to_domain_tld,*_to_www_domain_tld]',
				],
				[
					'field' => 'params[rewrite_to_https]',
					'label' => 'params[rewrite_to_https]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl]',
					'label' => 'params[ssl]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl_letsencrypt]',
					'label' => 'params[ssl_letsencrypt]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl_state]',
					'label' => 'params[ssl_state]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_locality]',
					'label' => 'params[ssl_locality]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_organisation]',
					'label' => 'params[ssl_organisation]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_organisation_unit]',
					'label' => 'params[ssl_organisation_unit]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_country]',
					'label' => 'params[ssl_country]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_domain]',
					'label' => 'params[ssl_domain]',
					'rules' => 'trim|valid_url',
				],
				[
					'field' => 'params[ssl_action]',
					'label' => 'params[ssl_action]',
					'rules' => 'trim|in_list[create,save,del]',
				],
				[
					'field' => 'params[stats_password]',
					'label' => 'params[stats_password]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[stats_type]',
					'label' => 'params[stats_type]',
					'rules' => 'trim|in_list[awstats,webalizer]',
				],
				[
					'field' => 'params[allow_override]',
					'label' => 'params[allow_override]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[apache_directives]',
					'label' => 'params[apache_directives]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[nginx_directives]',
					'label' => 'params[nginx_directives]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[php_fpm_use_socket]',
					'label' => 'params[php_fpm_use_socket]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[pm]',
					'label' => 'params[pm]',
					'rules' => 'trim|in_list[static,dynamic,ondemand]',
				],
				[
					'field' => 'params[pm_max_children]',
					'label' => 'params[pm_max_children]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_start_servers]',
					'label' => 'params[pm_start_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_min_spare_servers]',
					'label' => 'params[pm_min_spare_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_max_spare_servers]',
					'label' => 'params[pm_max_spare_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_process_idle_timeout]',
					'label' => 'params[pm_process_idle_timeout]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_max_requests]',
					'label' => 'params[pm_max_requests]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[php_open_basedir]',
					'label' => 'params[php_open_basedir]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[backup_interval]',
					'label' => 'params[backup_interval]',
					'rules' => 'trim|in_list[daily,weekly,monthly]',
				],
				[
					'field' => 'params[backup_copies]',
					'label' => 'params[backup_copies]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[10]',
				],
				[
					'field' => 'params[backup_excludes]',
					'label' => 'params[backup_excludes]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[traffic_quota_lock]',
					'label' => 'params[traffic_quota_lock]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[fastcgi_php_version]',
					'label' => 'params[fastcgi_php_version]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[enable_spdy]',
					'label' => 'params[enable_spdy]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[added_date]',
					'label' => 'params[added_date]',
					'rules' => 'trim|max_length[10]',
				],
				[
					'field' => 'params[added_by]',
					'label' => 'params[added_by]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[directive_snippets_id]',
					'label' => 'params[directive_snippets_id]',
					'rules' => 'trim|max_length[11]',
				],
				[
					'field' => 'params[enable_pagespeed]',
					'label' => 'params[enable_pagespeed]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[http_port]',
					'label' => 'params[http_port]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[65535]',
				],
				[
					'field' => 'params[https_port]',
					'label' => 'params[https_port]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[65535]',
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
				'server_id'               => isset($params['server_id']) ? $params['server_id'] : 0,
				'ip_address'              => isset($params['ip_address']) ? $params['ip_address'] : '*',
				'ipv6_address'            => isset($params['ipv6_address']) ? $params['ipv6_address'] : NULL,
				'domain'                  => isset($params['domain']) ? $params['domain'] : NULL,
				'web_folder'              => isset($params['web_folder']) ? $params['web_folder'] : NULL,
				'type'                    => 'subdomain',
				'parent_domain_id'        => isset($params['parent_domain_id']) ? $params['parent_domain_id'] : 0,
				'vhost_type'              => NULL,
				'hd_quota'                => isset($params['hd_quota']) ? $params['hd_quota'] : 0,
				'traffic_quota'           => isset($params['traffic_quota']) ? $params['traffic_quota'] : -1,
				'cgi'                     => isset($params['cgi']) ? $params['cgi'] : 'y',
				'ssi'                     => isset($params['ssi']) ? $params['ssi'] : 'y',
				'suexec'                  => isset($params['suexec']) ? $params['suexec'] : 'y',
				'errordocs'               => isset($params['errordocs']) ? $params['errordocs'] : 1,
				'is_subdomainwww'         => isset($params['is_subdomainwww']) ? $params['is_subdomainwww'] : 1,
				'subdomain'               => isset($params['subdomain']) ? $params['subdomain'] : 'none',
				'php'                     => isset($params['php']) ? $params['php'] : 'y',
				'ruby'                    => isset($params['ruby']) ? $params['ruby'] : 'n',
				'python'                  => isset($params['python']) ? $params['python'] : 'n',
				'perl'                    => isset($params['perl']) ? $params['perl'] : 'n',
				'redirect_type'           => isset($params['redirect_type']) ? $params['redirect_type'] : NULL,
				'redirect_path'           => isset($params['redirect_path']) ? $params['redirect_path'] : NULL,
				'seo_redirect'            => isset($params['seo_redirect']) ? $params['seo_redirect'] : NULL,
				'rewrite_to_https'        => isset($params['rewrite_to_https']) ? $params['rewrite_to_https'] : 'n',
				'ssl'                     => isset($params['ssl']) ? $params['ssl'] : 'n',
				'ssl_letsencrypt'         => isset($params['ssl_letsencrypt']) ? $params['ssl_letsencrypt'] : 'n',
				'ssl_state'               => isset($params['ssl_state']) ? $params['ssl_state'] : NULL,
				'ssl_locality'            => isset($params['ssl_locality']) ? $params['ssl_locality'] : NULL,
				'ssl_organisation'        => isset($params['ssl_organisation']) ? $params['ssl_organisation'] : NULL,
				'ssl_organisation_unit'   => isset($params['ssl_organisation_unit']) ? $params['ssl_organisation_unit'] : NULL,
				'ssl_country'             => isset($params['ssl_country']) ? $params['ssl_country'] : NULL,
				'ssl_domain'              => isset($params['ssl_domain']) ? $params['ssl_domain'] : NULL,
				'ssl_request'             => isset($params['ssl_request']) ? $params['ssl_request'] : NULL,
				'ssl_cert'                => isset($params['ssl_cert']) ? $params['ssl_cert'] : NULL,
				'ssl_bundle'              => isset($params['ssl_bundle']) ? $params['ssl_bundle'] : NULL,
				'ssl_key'                 => isset($params['ssl_key']) ? $params['ssl_key'] : NULL,
				'ssl_action'              => isset($params['ssl_action']) ? $params['ssl_action'] : NULL,
				'stats_password'          => isset($params['stats_password']) ? $params['stats_password'] : NULL,
				'stats_type'              => isset($params['stats_type']) ? $params['stats_type'] : 'webalizer',
				'allow_override'          => isset($params['allow_override']) ? $params['allow_override'] : 'All',
				'apache_directives'       => isset($params['apache_directives']) ? $params['apache_directives'] : NULL,
				'nginx_directives'        => isset($params['nginx_directives']) ? $params['nginx_directives'] : NULL,
				'php_fpm_use_socket'      => isset($params['php_fpm_use_socket']) ? $params['php_fpm_use_socket'] : 'y',
				'pm'                      => isset($params['pm']) ? $params['pm'] : 'dynamic',
				'pm_max_children'         => isset($params['pm_max_children']) ? $params['pm_max_children'] : 10,
				'pm_start_servers'        => isset($params['pm_start_servers']) ? $params['pm_start_servers'] : 2,
				'pm_min_spare_servers'    => isset($params['pm_min_spare_servers']) ? $params['pm_min_spare_servers'] : 1,
				'pm_max_spare_servers'    => isset($params['pm_max_spare_servers']) ? $params['pm_max_spare_servers'] : 5,
				'pm_process_idle_timeout' => isset($params['pm_process_idle_timeout']) ? $params['pm_process_idle_timeout'] : 10,
				'pm_max_requests'         => isset($params['pm_max_requests']) ? $params['pm_max_requests'] : 0,
				'php_open_basedir'        => isset($params['php_open_basedir']) ? $params['php_open_basedir'] : NULL,
				'custom_php_ini'          => isset($params['custom_php_ini']) ? $params['custom_php_ini'] : NULL,
				'backup_interval'         => isset($params['backup_interval']) ? $params['backup_interval'] : 'none',
				'backup_copies'           => isset($params['backup_copies']) ? $params['backup_copies'] : 1,
				'backup_excludes'         => isset($params['backup_excludes']) ? $params['backup_excludes'] : NULL,
				'active'                  => isset($params['active']) ? $params['active'] : 'y',
				'traffic_quota_lock'      => isset($params['traffic_quota_lock']) ? $params['traffic_quota_lock'] : 'n',
				'fastcgi_php_version'     => isset($params['fastcgi_php_version']) ? $params['fastcgi_php_version'] : NULL,
				'proxy_directives'        => isset($params['proxy_directives']) ? $params['proxy_directives'] : NULL,
				'enable_spdy'             => isset($params['enable_spdy']) ? $params['enable_spdy'] : 'n',
				'rewrite_rules'           => isset($params['rewrite_rules']) ? $params['rewrite_rules'] : NULL,
				'added_date'              => isset($params['added_date']) ? $params['added_date'] : date('Y-m-d'),
				'added_by'                => isset($params['added_by']) ? $params['added_by'] : $this->CI->config->item('ispconfig_username'),
				'directive_snippets_id'   => isset($params['directive_snippets_id']) ? $params['directive_snippets_id'] : 0,
				'enable_pagespeed'        => isset($params['enable_pagespeed']) ? $params['enable_pagespeed'] : 'n',
				'http_port'               => isset($params['http_port']) ? $params['http_port'] : 80,
				'https_port'              => isset($params['https_port']) ? $params['https_port'] : 443,
			);

			return $this->SoapClient->sites_web_vhost_subdomain_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			$errors = $this->get_error($e->getMessage());
			if (trim($errors['error'][0]) == 'domain_error_unique')
			{
				return array(
					'error' => ['params[domain]' => 'The params[domain] field must contain a unique value.'],
				);
			}

			return $errors;
		}
	}


	/**
	 * Update one record in Sites > Subdomain for website
	 *
	 * @param int   $client_id
	 * @param int   $domain_id
	 * @param array $params server_id, ip_address, ipv6_address, domain, hd_quota, traffic_quota, cgi, ssi, suexec,
	 *                      errordocs, is_subdomainwww, subdomain, php, ruby, python, perl, redirect_type,
	 *                      redirect_path, seo_redirect, rewrite_to_https, ssl, ssl_letsencrypt, ssl_state,
	 *                      ssl_locality, ssl_organisation, ssl_organisation_unit, ssl_country, ssl_domain, ssl_request,
	 *                      ssl_cert, ssl_bundle, ssl_key, ssl_action, stats_password, stats_type, allow_override,
	 *                      apache_directives, nginx_directives, php_fpm_use_socket, pm, pm_max_children,
	 *                      pm_start_servers, pm_min_spare_servers, pm_max_spare_servers, pm_process_idle_timeout,
	 *                      pm_max_requests, php_open_basedir, custom_php_ini, backup_interval, backup_copies,
	 *                      backup_excludes, active, traffic_quota_lock, fastcgi_php_version, proxy_directives,
	 *                      enable_spdy, rewrite_rules, added_date, added_by, directive_snippets_id, enable_pagespeed,
	 *                      http_port, https_port
	 *
	 * @return bool|array TRUE or error
	 */
	public function web_vhost_subdomain_update($client_id, $domain_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'domain_id' => $domain_id,
				'params'    => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('domain_id'),
				[
					'field' => 'params[server_id]',
					'label' => 'params[server_id]',
					'rules' => 'trim|greater_than[0]',
				],
				[
					'field' => 'params[parent_domain_id]',
					'label' => 'params[parent_domain_id]',
					'rules' => 'trim|greater_than[0]',
				],
				[
					'field' => 'params[ip_address]',
					'label' => 'params[ip_address]',
					'rules' => 'trim|valid_ip',
				],
				[
					'field' => 'params[ipv6_address]',
					'label' => 'params[ipv6_address]',
					'rules' => 'trim|valid_ip[ipv6]',
				],
				[
					'field' => 'params[domain]',
					'label' => 'params[domain]',
					'rules' => 'trim|valid_url',
				],
				[
					'field' => 'params[web_folder]',
					'label' => 'params[web_folder]',
					'rules' => 'trim|max_length[100]',
				],
				[
					'field' => 'params[hd_quota]',
					'label' => 'params[hd_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[traffic_quota]',
					'label' => 'params[traffic_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[cgi]',
					'label' => 'params[cgi]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssi]',
					'label' => 'params[ssi]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[suexec]',
					'label' => 'params[suexec]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[errordocs]',
					'label' => 'params[errordocs]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[1]',
				],
				[
					'field' => 'params[is_subdomainwww]',
					'label' => 'params[is_subdomainwww]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[1]',
				],
				[
					'field' => 'params[subdomain]',
					'label' => 'params[subdomain]',
					'rules' => 'trim|in_list[none,www,*]',
				],
				[
					'field' => 'params[php]',
					'label' => 'params[php]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[ruby]',
					'label' => 'params[ruby]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[python]',
					'label' => 'params[python]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[perl]',
					'label' => 'params[perl]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[redirect_type]',
					'label' => 'params[redirect_type]',
					'rules' => 'trim|in_list[no,last,break,redirect,permanent,proxy]',
				],
				[
					'field' => 'params[redirect_path]',
					'label' => 'params[redirect_path]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[seo_redirect]',
					'label' => 'params[seo_redirect]',
					'rules' => 'trim|in_list[non_www_to_www,www_to_non_www,*_domain_tld_to_domain_tld,*_domain_tld_to_www_domain_tld,*_to_domain_tld,*_to_www_domain_tld]',
				],
				[
					'field' => 'params[rewrite_to_https]',
					'label' => 'params[rewrite_to_https]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl]',
					'label' => 'params[ssl]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl_letsencrypt]',
					'label' => 'params[ssl_letsencrypt]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl_state]',
					'label' => 'params[ssl_state]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_locality]',
					'label' => 'params[ssl_locality]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_organisation]',
					'label' => 'params[ssl_organisation]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_organisation_unit]',
					'label' => 'params[ssl_organisation_unit]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_country]',
					'label' => 'params[ssl_country]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_domain]',
					'label' => 'params[ssl_domain]',
					'rules' => 'trim|valid_url',
				],
				[
					'field' => 'params[ssl_action]',
					'label' => 'params[ssl_action]',
					'rules' => 'trim|in_list[create,save,del]',
				],
				[
					'field' => 'params[stats_password]',
					'label' => 'params[stats_password]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[stats_type]',
					'label' => 'params[stats_type]',
					'rules' => 'trim|in_list[awstats,webalizer]',
				],
				[
					'field' => 'params[allow_override]',
					'label' => 'params[allow_override]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[apache_directives]',
					'label' => 'params[apache_directives]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[nginx_directives]',
					'label' => 'params[nginx_directives]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[php_fpm_use_socket]',
					'label' => 'params[php_fpm_use_socket]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[pm]',
					'label' => 'params[pm]',
					'rules' => 'trim|in_list[static,dynamic,ondemand]',
				],
				[
					'field' => 'params[pm_max_children]',
					'label' => 'params[pm_max_children]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_start_servers]',
					'label' => 'params[pm_start_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_min_spare_servers]',
					'label' => 'params[pm_min_spare_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_max_spare_servers]',
					'label' => 'params[pm_max_spare_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_process_idle_timeout]',
					'label' => 'params[pm_process_idle_timeout]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_max_requests]',
					'label' => 'params[pm_max_requests]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[php_open_basedir]',
					'label' => 'params[php_open_basedir]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[backup_interval]',
					'label' => 'params[backup_interval]',
					'rules' => 'trim|in_list[daily,weekly,monthly]',
				],
				[
					'field' => 'params[backup_copies]',
					'label' => 'params[backup_copies]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[10]',
				],
				[
					'field' => 'params[backup_excludes]',
					'label' => 'params[backup_excludes]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[traffic_quota_lock]',
					'label' => 'params[traffic_quota_lock]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[fastcgi_php_version]',
					'label' => 'params[fastcgi_php_version]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[enable_spdy]',
					'label' => 'params[enable_spdy]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[added_date]',
					'label' => 'params[added_date]',
					'rules' => 'trim|max_length[10]',
				],
				[
					'field' => 'params[added_by]',
					'label' => 'params[added_by]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[directive_snippets_id]',
					'label' => 'params[directive_snippets_id]',
					'rules' => 'trim|max_length[11]',
				],
				[
					'field' => 'params[enable_pagespeed]',
					'label' => 'params[enable_pagespeed]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[http_port]',
					'label' => 'params[http_port]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[65535]',
				],
				[
					'field' => 'params[https_port]',
					'label' => 'params[https_port]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[65535]',
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

			return $this->SoapClient->sites_web_vhost_subdomain_update($this->ID, $client_id, $domain_id, $params);
		}
		catch (SoapFault $e)
		{
			$errors = $this->get_error($e->getMessage());
			if (trim($errors['error'][0]) == 'domain_error_unique')
			{
				return array(
					'error' => ['params[domain]' => 'The params[domain] field must contain a unique value.'],
				);
			}

			return $errors;
		}
	}


	/**
	 * Delete one record in Sites > Subdomain for website
	 *
	 * @param int $domain_id
	 *
	 * @return int|array affected rows or error
	 */
	public function web_vhost_subdomain_delete($domain_id)
	{
		if (is_array($validation = $this->validate_primary_key('domain_id', $domain_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->sites_web_vhost_subdomain_delete($this->ID, $domain_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Sites > Aliasdomain for website
	 *
	 * @param int $domain_id
	 *
	 * @return array webdomain.* or error
	 */
	public function web_aliasdomain_get($domain_id)
	{
		if (is_array($validation = $this->validate_primary_key('domain_id', $domain_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->sites_web_aliasdomain_get($this->ID, $domain_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Sites > Aliasdomain for website
	 *
	 * @param int   $client_id
	 * @param array $params server_id, ip_address, ipv6_address, domain, hd_quota, traffic_quota, cgi, ssi, suexec,
	 *                      errordocs, is_subdomainwww, subdomain, php, ruby, python, perl, redirect_type,
	 *                      redirect_path, seo_redirect, rewrite_to_https, ssl, ssl_letsencrypt, ssl_state,
	 *                      ssl_locality, ssl_organisation, ssl_organisation_unit, ssl_country, ssl_domain, ssl_request,
	 *                      ssl_cert, ssl_bundle, ssl_key, ssl_action, stats_password, stats_type, allow_override,
	 *                      apache_directives, nginx_directives, php_fpm_use_socket, pm, pm_max_children,
	 *                      pm_start_servers, pm_min_spare_servers, pm_max_spare_servers, pm_process_idle_timeout,
	 *                      pm_max_requests, php_open_basedir, custom_php_ini, backup_interval, backup_copies,
	 *                      backup_excludes, active, traffic_quota_lock, fastcgi_php_version, proxy_directives,
	 *                      enable_spdy, rewrite_rules, added_date, added_by, directive_snippets_id, enable_pagespeed,
	 *                      http_port, https_port
	 *
	 * @return int|array web_domain.domain_id or error
	 */
	public function web_aliasdomain_add($client_id, $params)
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
					'field' => 'params[ipv6_address]',
					'label' => 'params[ipv6_address]',
					'rules' => 'trim|valid_ip[ipv6]',
				],
				[
					'field' => 'params[domain]',
					'label' => 'params[domain]',
					'rules' => 'trim|required|valid_url',
				],
				[
					'field' => 'params[hd_quota]',
					'label' => 'params[hd_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[traffic_quota]',
					'label' => 'params[traffic_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[cgi]',
					'label' => 'params[cgi]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssi]',
					'label' => 'params[ssi]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[suexec]',
					'label' => 'params[suexec]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[errordocs]',
					'label' => 'params[errordocs]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[1]',
				],
				[
					'field' => 'params[is_subdomainwww]',
					'label' => 'params[is_subdomainwww]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[1]',
				],
				[
					'field' => 'params[subdomain]',
					'label' => 'params[subdomain]',
					'rules' => 'trim|in_list[none,www,*]',
				],
				[
					'field' => 'params[php]',
					'label' => 'params[php]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[ruby]',
					'label' => 'params[ruby]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[python]',
					'label' => 'params[python]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[perl]',
					'label' => 'params[perl]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[redirect_type]',
					'label' => 'params[redirect_type]',
					'rules' => 'trim|in_list[no,last,break,redirect,permanent,proxy]',
				],
				[
					'field' => 'params[redirect_path]',
					'label' => 'params[redirect_path]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[seo_redirect]',
					'label' => 'params[seo_redirect]',
					'rules' => 'trim|in_list[non_www_to_www,www_to_non_www,*_domain_tld_to_domain_tld,*_domain_tld_to_www_domain_tld,*_to_domain_tld,*_to_www_domain_tld]',
				],
				[
					'field' => 'params[rewrite_to_https]',
					'label' => 'params[rewrite_to_https]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl]',
					'label' => 'params[ssl]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl_letsencrypt]',
					'label' => 'params[ssl_letsencrypt]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl_state]',
					'label' => 'params[ssl_state]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_locality]',
					'label' => 'params[ssl_locality]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_organisation]',
					'label' => 'params[ssl_organisation]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_organisation_unit]',
					'label' => 'params[ssl_organisation_unit]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_country]',
					'label' => 'params[ssl_country]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_domain]',
					'label' => 'params[ssl_domain]',
					'rules' => 'trim|valid_url',
				],
				[
					'field' => 'params[ssl_action]',
					'label' => 'params[ssl_action]',
					'rules' => 'trim|in_list[create,save,del]',
				],
				[
					'field' => 'params[stats_password]',
					'label' => 'params[stats_password]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[stats_type]',
					'label' => 'params[stats_type]',
					'rules' => 'trim|in_list[awstats,webalizer]',
				],
				[
					'field' => 'params[allow_override]',
					'label' => 'params[allow_override]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[apache_directives]',
					'label' => 'params[apache_directives]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[nginx_directives]',
					'label' => 'params[nginx_directives]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[php_fpm_use_socket]',
					'label' => 'params[php_fpm_use_socket]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[pm]',
					'label' => 'params[pm]',
					'rules' => 'trim|in_list[static,dynamic,ondemand]',
				],
				[
					'field' => 'params[pm_max_children]',
					'label' => 'params[pm_max_children]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_start_servers]',
					'label' => 'params[pm_start_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_min_spare_servers]',
					'label' => 'params[pm_min_spare_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_max_spare_servers]',
					'label' => 'params[pm_max_spare_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_process_idle_timeout]',
					'label' => 'params[pm_process_idle_timeout]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_max_requests]',
					'label' => 'params[pm_max_requests]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[php_open_basedir]',
					'label' => 'params[php_open_basedir]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[backup_interval]',
					'label' => 'params[backup_interval]',
					'rules' => 'trim|in_list[daily,weekly,monthly]',
				],
				[
					'field' => 'params[backup_copies]',
					'label' => 'params[backup_copies]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[10]',
				],
				[
					'field' => 'params[backup_excludes]',
					'label' => 'params[backup_excludes]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[traffic_quota_lock]',
					'label' => 'params[traffic_quota_lock]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[fastcgi_php_version]',
					'label' => 'params[fastcgi_php_version]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[enable_spdy]',
					'label' => 'params[enable_spdy]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[added_date]',
					'label' => 'params[added_date]',
					'rules' => 'trim|max_length[10]',
				],
				[
					'field' => 'params[added_by]',
					'label' => 'params[added_by]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[directive_snippets_id]',
					'label' => 'params[directive_snippets_id]',
					'rules' => 'trim|max_length[11]',
				],
				[
					'field' => 'params[enable_pagespeed]',
					'label' => 'params[enable_pagespeed]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[http_port]',
					'label' => 'params[http_port]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[65535]',
				],
				[
					'field' => 'params[https_port]',
					'label' => 'params[https_port]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[65535]',
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
				'server_id'               => isset($params['server_id']) ? $params['server_id'] : 0,
				'ip_address'              => isset($params['ip_address']) ? $params['ip_address'] : '*',
				'ipv6_address'            => isset($params['ipv6_address']) ? $params['ipv6_address'] : NULL,
				'domain'                  => isset($params['domain']) ? $params['domain'] : NULL,
				'type'                    => 'alias',
				'parent_domain_id'        => isset($params['parent_domain_id']) ? $params['parent_domain_id'] : 0,
				'vhost_type'              => NULL,
				'hd_quota'                => isset($params['hd_quota']) ? $params['hd_quota'] : 0,
				'traffic_quota'           => isset($params['traffic_quota']) ? $params['traffic_quota'] : -1,
				'cgi'                     => isset($params['cgi']) ? $params['cgi'] : 'y',
				'ssi'                     => isset($params['ssi']) ? $params['ssi'] : 'y',
				'suexec'                  => isset($params['suexec']) ? $params['suexec'] : 'y',
				'errordocs'               => isset($params['errordocs']) ? $params['errordocs'] : 1,
				'is_subdomainwww'         => isset($params['is_subdomainwww']) ? $params['is_subdomainwww'] : 1,
				'subdomain'               => isset($params['subdomain']) ? $params['subdomain'] : 'none',
				'php'                     => isset($params['php']) ? $params['php'] : 'y',
				'ruby'                    => isset($params['ruby']) ? $params['ruby'] : 'n',
				'python'                  => isset($params['python']) ? $params['python'] : 'n',
				'perl'                    => isset($params['perl']) ? $params['perl'] : 'n',
				'redirect_type'           => isset($params['redirect_type']) ? $params['redirect_type'] : NULL,
				'redirect_path'           => isset($params['redirect_path']) ? $params['redirect_path'] : NULL,
				'seo_redirect'            => isset($params['seo_redirect']) ? $params['seo_redirect'] : NULL,
				'rewrite_to_https'        => isset($params['rewrite_to_https']) ? $params['rewrite_to_https'] : 'n',
				'ssl'                     => isset($params['ssl']) ? $params['ssl'] : 'n',
				'ssl_letsencrypt'         => isset($params['ssl_letsencrypt']) ? $params['ssl_letsencrypt'] : 'n',
				'ssl_state'               => isset($params['ssl_state']) ? $params['ssl_state'] : NULL,
				'ssl_locality'            => isset($params['ssl_locality']) ? $params['ssl_locality'] : NULL,
				'ssl_organisation'        => isset($params['ssl_organisation']) ? $params['ssl_organisation'] : NULL,
				'ssl_organisation_unit'   => isset($params['ssl_organisation_unit']) ? $params['ssl_organisation_unit'] : NULL,
				'ssl_country'             => isset($params['ssl_country']) ? $params['ssl_country'] : NULL,
				'ssl_domain'              => isset($params['ssl_domain']) ? $params['ssl_domain'] : NULL,
				'ssl_request'             => isset($params['ssl_request']) ? $params['ssl_request'] : NULL,
				'ssl_cert'                => isset($params['ssl_cert']) ? $params['ssl_cert'] : NULL,
				'ssl_bundle'              => isset($params['ssl_bundle']) ? $params['ssl_bundle'] : NULL,
				'ssl_key'                 => isset($params['ssl_key']) ? $params['ssl_key'] : NULL,
				'ssl_action'              => isset($params['ssl_action']) ? $params['ssl_action'] : NULL,
				'stats_password'          => isset($params['stats_password']) ? $params['stats_password'] : NULL,
				'stats_type'              => isset($params['stats_type']) ? $params['stats_type'] : 'webalizer',
				'allow_override'          => isset($params['allow_override']) ? $params['allow_override'] : 'All',
				'apache_directives'       => isset($params['apache_directives']) ? $params['apache_directives'] : NULL,
				'nginx_directives'        => isset($params['nginx_directives']) ? $params['nginx_directives'] : NULL,
				'php_fpm_use_socket'      => isset($params['php_fpm_use_socket']) ? $params['php_fpm_use_socket'] : 'y',
				'pm'                      => isset($params['pm']) ? $params['pm'] : 'dynamic',
				'pm_max_children'         => isset($params['pm_max_children']) ? $params['pm_max_children'] : 10,
				'pm_start_servers'        => isset($params['pm_start_servers']) ? $params['pm_start_servers'] : 2,
				'pm_min_spare_servers'    => isset($params['pm_min_spare_servers']) ? $params['pm_min_spare_servers'] : 1,
				'pm_max_spare_servers'    => isset($params['pm_max_spare_servers']) ? $params['pm_max_spare_servers'] : 5,
				'pm_process_idle_timeout' => isset($params['pm_process_idle_timeout']) ? $params['pm_process_idle_timeout'] : 10,
				'pm_max_requests'         => isset($params['pm_max_requests']) ? $params['pm_max_requests'] : 0,
				'php_open_basedir'        => isset($params['php_open_basedir']) ? $params['php_open_basedir'] : NULL,
				'custom_php_ini'          => isset($params['custom_php_ini']) ? $params['custom_php_ini'] : NULL,
				'backup_interval'         => isset($params['backup_interval']) ? $params['backup_interval'] : 'none',
				'backup_copies'           => isset($params['backup_copies']) ? $params['backup_copies'] : 1,
				'backup_excludes'         => isset($params['backup_excludes']) ? $params['backup_excludes'] : NULL,
				'active'                  => isset($params['active']) ? $params['active'] : 'y',
				'traffic_quota_lock'      => isset($params['traffic_quota_lock']) ? $params['traffic_quota_lock'] : 'n',
				'fastcgi_php_version'     => isset($params['fastcgi_php_version']) ? $params['fastcgi_php_version'] : NULL,
				'proxy_directives'        => isset($params['proxy_directives']) ? $params['proxy_directives'] : NULL,
				'enable_spdy'             => isset($params['enable_spdy']) ? $params['enable_spdy'] : 'n',
				'rewrite_rules'           => isset($params['rewrite_rules']) ? $params['rewrite_rules'] : NULL,
				'added_date'              => isset($params['added_date']) ? $params['added_date'] : date('Y-m-d'),
				'added_by'                => isset($params['added_by']) ? $params['added_by'] : $this->CI->config->item('ispconfig_username'),
				'directive_snippets_id'   => isset($params['directive_snippets_id']) ? $params['directive_snippets_id'] : 0,
				'enable_pagespeed'        => isset($params['enable_pagespeed']) ? $params['enable_pagespeed'] : 'n',
				'http_port'               => isset($params['http_port']) ? $params['http_port'] : 80,
				'https_port'              => isset($params['https_port']) ? $params['https_port'] : 443,
			);

			return $this->SoapClient->sites_web_aliasdomain_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			$errors = $this->get_error($e->getMessage());
			if (trim($errors['error'][0]) == 'domain_error_unique')
			{
				return array(
					'error' => ['params[domain]' => 'The params[domain] field must contain a unique value.'],
				);
			}

			return $errors;
		}
	}


	/**
	 * Update one record in Sites > Aliasdomain for website
	 *
	 * @param int   $client_id
	 * @param int   $domain_id
	 * @param array $params server_id, ip_address, ipv6_address, domain, hd_quota, traffic_quota, cgi, ssi, suexec,
	 *                      errordocs, is_subdomainwww, subdomain, php, ruby, python, perl, redirect_type,
	 *                      redirect_path, seo_redirect, rewrite_to_https, ssl, ssl_letsencrypt, ssl_state,
	 *                      ssl_locality, ssl_organisation, ssl_organisation_unit, ssl_country, ssl_domain, ssl_request,
	 *                      ssl_cert, ssl_bundle, ssl_key, ssl_action, stats_password, stats_type, allow_override,
	 *                      apache_directives, nginx_directives, php_fpm_use_socket, pm, pm_max_children,
	 *                      pm_start_servers, pm_min_spare_servers, pm_max_spare_servers, pm_process_idle_timeout,
	 *                      pm_max_requests, php_open_basedir, custom_php_ini, backup_interval, backup_copies,
	 *                      backup_excludes, active, traffic_quota_lock, fastcgi_php_version, proxy_directives,
	 *                      enable_spdy, rewrite_rules, added_date, added_by, directive_snippets_id, enable_pagespeed,
	 *                      http_port, https_port
	 *
	 * @return bool|array TRUE or error
	 */
	public function web_aliasdomain_update($client_id, $domain_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'domain_id' => $domain_id,
				'params'    => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('domain_id'),
				[
					'field' => 'params[server_id]',
					'label' => 'params[server_id]',
					'rules' => 'trim|greater_than[0]',
				],
				[
					'field' => 'params[ip_address]',
					'label' => 'params[ip_address]',
					'rules' => 'trim|valid_ip',
				],
				[
					'field' => 'params[ipv6_address]',
					'label' => 'params[ipv6_address]',
					'rules' => 'trim|valid_ip[ipv6]',
				],
				[
					'field' => 'params[domain]',
					'label' => 'params[domain]',
					'rules' => 'trim|valid_url',
				],
				[
					'field' => 'params[hd_quota]',
					'label' => 'params[hd_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[traffic_quota]',
					'label' => 'params[traffic_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[cgi]',
					'label' => 'params[cgi]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssi]',
					'label' => 'params[ssi]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[suexec]',
					'label' => 'params[suexec]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[errordocs]',
					'label' => 'params[errordocs]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[1]',
				],
				[
					'field' => 'params[is_subdomainwww]',
					'label' => 'params[is_subdomainwww]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[1]',
				],
				[
					'field' => 'params[subdomain]',
					'label' => 'params[subdomain]',
					'rules' => 'trim|in_list[none,www,*]',
				],
				[
					'field' => 'params[php]',
					'label' => 'params[php]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[ruby]',
					'label' => 'params[ruby]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[python]',
					'label' => 'params[python]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[perl]',
					'label' => 'params[perl]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[redirect_type]',
					'label' => 'params[redirect_type]',
					'rules' => 'trim|in_list[no,last,break,redirect,permanent,proxy]',
				],
				[
					'field' => 'params[redirect_path]',
					'label' => 'params[redirect_path]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[seo_redirect]',
					'label' => 'params[seo_redirect]',
					'rules' => 'trim|in_list[non_www_to_www,www_to_non_www,*_domain_tld_to_domain_tld,*_domain_tld_to_www_domain_tld,*_to_domain_tld,*_to_www_domain_tld]',
				],
				[
					'field' => 'params[rewrite_to_https]',
					'label' => 'params[rewrite_to_https]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl]',
					'label' => 'params[ssl]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl_letsencrypt]',
					'label' => 'params[ssl_letsencrypt]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl_state]',
					'label' => 'params[ssl_state]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_locality]',
					'label' => 'params[ssl_locality]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_organisation]',
					'label' => 'params[ssl_organisation]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_organisation_unit]',
					'label' => 'params[ssl_organisation_unit]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_country]',
					'label' => 'params[ssl_country]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_domain]',
					'label' => 'params[ssl_domain]',
					'rules' => 'trim|valid_url',
				],
				[
					'field' => 'params[ssl_action]',
					'label' => 'params[ssl_action]',
					'rules' => 'trim|in_list[create,save,del]',
				],
				[
					'field' => 'params[stats_password]',
					'label' => 'params[stats_password]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[stats_type]',
					'label' => 'params[stats_type]',
					'rules' => 'trim|in_list[awstats,webalizer]',
				],
				[
					'field' => 'params[allow_override]',
					'label' => 'params[allow_override]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[apache_directives]',
					'label' => 'params[apache_directives]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[nginx_directives]',
					'label' => 'params[nginx_directives]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[php_fpm_use_socket]',
					'label' => 'params[php_fpm_use_socket]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[pm]',
					'label' => 'params[pm]',
					'rules' => 'trim|in_list[static,dynamic,ondemand]',
				],
				[
					'field' => 'params[pm_max_children]',
					'label' => 'params[pm_max_children]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_start_servers]',
					'label' => 'params[pm_start_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_min_spare_servers]',
					'label' => 'params[pm_min_spare_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_max_spare_servers]',
					'label' => 'params[pm_max_spare_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_process_idle_timeout]',
					'label' => 'params[pm_process_idle_timeout]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_max_requests]',
					'label' => 'params[pm_max_requests]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[php_open_basedir]',
					'label' => 'params[php_open_basedir]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[backup_interval]',
					'label' => 'params[backup_interval]',
					'rules' => 'trim|in_list[daily,weekly,monthly]',
				],
				[
					'field' => 'params[backup_copies]',
					'label' => 'params[backup_copies]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[10]',
				],
				[
					'field' => 'params[backup_excludes]',
					'label' => 'params[backup_excludes]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[traffic_quota_lock]',
					'label' => 'params[traffic_quota_lock]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[fastcgi_php_version]',
					'label' => 'params[fastcgi_php_version]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[enable_spdy]',
					'label' => 'params[enable_spdy]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[added_date]',
					'label' => 'params[added_date]',
					'rules' => 'trim|max_length[10]',
				],
				[
					'field' => 'params[added_by]',
					'label' => 'params[added_by]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[directive_snippets_id]',
					'label' => 'params[directive_snippets_id]',
					'rules' => 'trim|max_length[11]',
				],
				[
					'field' => 'params[enable_pagespeed]',
					'label' => 'params[enable_pagespeed]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[http_port]',
					'label' => 'params[http_port]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[65535]',
				],
				[
					'field' => 'params[https_port]',
					'label' => 'params[https_port]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[65535]',
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

			return $this->SoapClient->sites_web_aliasdomain_update($this->ID, $client_id, $domain_id, $params);
		}
		catch (SoapFault $e)
		{
			$errors = $this->get_error($e->getMessage());
			if (trim($errors['error'][0]) == 'domain_error_unique')
			{
				return array(
					'error' => ['params[domain]' => 'The params[domain] field must contain a unique value.'],
				);
			}

			return $errors;
		}
	}


	/**
	 * Delete one record in Sites > Aliasdomain for website
	 *
	 * @param int $domain_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function web_aliasdomain_delete($domain_id)
	{
		if (is_array($validation = $this->validate_primary_key('domain_id', $domain_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->sites_web_aliasdomain_delete($this->ID, $domain_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Sites > Subdomain for website
	 *
	 * @param int $domain_id
	 *
	 * @return array webdomain.* or error
	 */
	public function web_subdomain_get($domain_id)
	{
		if (is_array($validation = $this->validate_primary_key('domain_id', $domain_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->sites_web_subdomain_get($this->ID, $domain_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Sites > Subdomain for website
	 *
	 * @param int   $client_id
	 * @param array $params server_id, ip_address, ipv6_address, domain, hd_quota, traffic_quota, cgi, ssi, suexec,
	 *                      errordocs, is_subdomainwww, subdomain, php, ruby, python, perl, redirect_type,
	 *                      redirect_path, seo_redirect, rewrite_to_https, ssl, ssl_letsencrypt, ssl_state,
	 *                      ssl_locality, ssl_organisation, ssl_organisation_unit, ssl_country, ssl_domain, ssl_request,
	 *                      ssl_cert, ssl_bundle, ssl_key, ssl_action, stats_password, stats_type, allow_override,
	 *                      apache_directives, nginx_directives, php_fpm_use_socket, pm, pm_max_children,
	 *                      pm_start_servers, pm_min_spare_servers, pm_max_spare_servers, pm_process_idle_timeout,
	 *                      pm_max_requests, php_open_basedir, custom_php_ini, backup_interval, backup_copies,
	 *                      backup_excludes, active, traffic_quota_lock, fastcgi_php_version, proxy_directives,
	 *                      enable_spdy, rewrite_rules, added_date, added_by, directive_snippets_id, enable_pagespeed,
	 *                      http_port, https_port
	 * @param bool  $readonly
	 *
	 * @return int|array web_domain.domain_id or error
	 */
	public function web_subdomain_add($client_id, $params, $readonly = FALSE)
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
					'field' => 'params[ipv6_address]',
					'label' => 'params[ipv6_address]',
					'rules' => 'trim|valid_ip[ipv6]',
				],
				[
					'field' => 'params[domain]',
					'label' => 'params[domain]',
					'rules' => 'trim|required|valid_url',
				],
				[
					'field' => 'params[hd_quota]',
					'label' => 'params[hd_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[traffic_quota]',
					'label' => 'params[traffic_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[cgi]',
					'label' => 'params[cgi]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssi]',
					'label' => 'params[ssi]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[suexec]',
					'label' => 'params[suexec]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[errordocs]',
					'label' => 'params[errordocs]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[1]',
				],
				[
					'field' => 'params[is_subdomainwww]',
					'label' => 'params[is_subdomainwww]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[1]',
				],
				[
					'field' => 'params[subdomain]',
					'label' => 'params[subdomain]',
					'rules' => 'trim|in_list[none,www,*]',
				],
				[
					'field' => 'params[php]',
					'label' => 'params[php]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[ruby]',
					'label' => 'params[ruby]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[python]',
					'label' => 'params[python]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[perl]',
					'label' => 'params[perl]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[redirect_type]',
					'label' => 'params[redirect_type]',
					'rules' => 'trim|in_list[no,last,break,redirect,permanent,proxy]',
				],
				[
					'field' => 'params[redirect_path]',
					'label' => 'params[redirect_path]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[seo_redirect]',
					'label' => 'params[seo_redirect]',
					'rules' => 'trim|in_list[non_www_to_www,www_to_non_www,*_domain_tld_to_domain_tld,*_domain_tld_to_www_domain_tld,*_to_domain_tld,*_to_www_domain_tld]',
				],
				[
					'field' => 'params[rewrite_to_https]',
					'label' => 'params[rewrite_to_https]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl]',
					'label' => 'params[ssl]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl_letsencrypt]',
					'label' => 'params[ssl_letsencrypt]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl_state]',
					'label' => 'params[ssl_state]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_locality]',
					'label' => 'params[ssl_locality]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_organisation]',
					'label' => 'params[ssl_organisation]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_organisation_unit]',
					'label' => 'params[ssl_organisation_unit]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_country]',
					'label' => 'params[ssl_country]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_domain]',
					'label' => 'params[ssl_domain]',
					'rules' => 'trim|valid_url',
				],
				[
					'field' => 'params[ssl_action]',
					'label' => 'params[ssl_action]',
					'rules' => 'trim|in_list[create,save,del]',
				],
				[
					'field' => 'params[stats_password]',
					'label' => 'params[stats_password]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[stats_type]',
					'label' => 'params[stats_type]',
					'rules' => 'trim|in_list[awstats,webalizer]',
				],
				[
					'field' => 'params[allow_override]',
					'label' => 'params[allow_override]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[apache_directives]',
					'label' => 'params[apache_directives]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[nginx_directives]',
					'label' => 'params[nginx_directives]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[php_fpm_use_socket]',
					'label' => 'params[php_fpm_use_socket]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[pm]',
					'label' => 'params[pm]',
					'rules' => 'trim|in_list[static,dynamic,ondemand]',
				],
				[
					'field' => 'params[pm_max_children]',
					'label' => 'params[pm_max_children]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_start_servers]',
					'label' => 'params[pm_start_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_min_spare_servers]',
					'label' => 'params[pm_min_spare_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_max_spare_servers]',
					'label' => 'params[pm_max_spare_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_process_idle_timeout]',
					'label' => 'params[pm_process_idle_timeout]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_max_requests]',
					'label' => 'params[pm_max_requests]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[php_open_basedir]',
					'label' => 'params[php_open_basedir]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[backup_interval]',
					'label' => 'params[backup_interval]',
					'rules' => 'trim|in_list[daily,weekly,monthly]',
				],
				[
					'field' => 'params[backup_copies]',
					'label' => 'params[backup_copies]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[10]',
				],
				[
					'field' => 'params[backup_excludes]',
					'label' => 'params[backup_excludes]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[traffic_quota_lock]',
					'label' => 'params[traffic_quota_lock]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[fastcgi_php_version]',
					'label' => 'params[fastcgi_php_version]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[enable_spdy]',
					'label' => 'params[enable_spdy]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[added_date]',
					'label' => 'params[added_date]',
					'rules' => 'trim|max_length[10]',
				],
				[
					'field' => 'params[added_by]',
					'label' => 'params[added_by]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[directive_snippets_id]',
					'label' => 'params[directive_snippets_id]',
					'rules' => 'trim|max_length[11]',
				],
				[
					'field' => 'params[enable_pagespeed]',
					'label' => 'params[enable_pagespeed]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[http_port]',
					'label' => 'params[http_port]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[65535]',
				],
				[
					'field' => 'params[https_port]',
					'label' => 'params[https_port]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[65535]',
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
				'server_id'               => isset($params['server_id']) ? $params['server_id'] : 0,
				'ip_address'              => isset($params['ip_address']) ? $params['ip_address'] : '*',
				'ipv6_address'            => isset($params['ipv6_address']) ? $params['ipv6_address'] : NULL,
				'domain'                  => isset($params['domain']) ? $params['domain'] : NULL,
				'type'                    => 'subdomain',
				'parent_domain_id'        => isset($params['parent_domain_id']) ? $params['parent_domain_id'] : 0,
				'vhost_type'              => 'name',
				'hd_quota'                => isset($params['hd_quota']) ? $params['hd_quota'] : 0,
				'traffic_quota'           => isset($params['traffic_quota']) ? $params['traffic_quota'] : -1,
				'cgi'                     => isset($params['cgi']) ? $params['cgi'] : 'y',
				'ssi'                     => isset($params['ssi']) ? $params['ssi'] : 'y',
				'suexec'                  => isset($params['suexec']) ? $params['suexec'] : 'y',
				'errordocs'               => isset($params['errordocs']) ? $params['errordocs'] : 1,
				'is_subdomainwww'         => isset($params['is_subdomainwww']) ? $params['is_subdomainwww'] : 1,
				'subdomain'               => isset($params['subdomain']) ? $params['subdomain'] : 'none',
				'php'                     => isset($params['php']) ? $params['php'] : 'y',
				'ruby'                    => isset($params['ruby']) ? $params['ruby'] : 'n',
				'python'                  => isset($params['python']) ? $params['python'] : 'n',
				'perl'                    => isset($params['perl']) ? $params['perl'] : 'n',
				'redirect_type'           => isset($params['redirect_type']) ? $params['redirect_type'] : NULL,
				'redirect_path'           => isset($params['redirect_path']) ? $params['redirect_path'] : NULL,
				'seo_redirect'            => isset($params['seo_redirect']) ? $params['seo_redirect'] : NULL,
				'rewrite_to_https'        => isset($params['rewrite_to_https']) ? $params['rewrite_to_https'] : 'n',
				'ssl'                     => isset($params['ssl']) ? $params['ssl'] : 'n',
				'ssl_letsencrypt'         => isset($params['ssl_letsencrypt']) ? $params['ssl_letsencrypt'] : 'n',
				'ssl_state'               => isset($params['ssl_state']) ? $params['ssl_state'] : NULL,
				'ssl_locality'            => isset($params['ssl_locality']) ? $params['ssl_locality'] : NULL,
				'ssl_organisation'        => isset($params['ssl_organisation']) ? $params['ssl_organisation'] : NULL,
				'ssl_organisation_unit'   => isset($params['ssl_organisation_unit']) ? $params['ssl_organisation_unit'] : NULL,
				'ssl_country'             => isset($params['ssl_country']) ? $params['ssl_country'] : NULL,
				'ssl_domain'              => isset($params['ssl_domain']) ? $params['ssl_domain'] : NULL,
				'ssl_request'             => isset($params['ssl_request']) ? $params['ssl_request'] : NULL,
				'ssl_cert'                => isset($params['ssl_cert']) ? $params['ssl_cert'] : NULL,
				'ssl_bundle'              => isset($params['ssl_bundle']) ? $params['ssl_bundle'] : NULL,
				'ssl_key'                 => isset($params['ssl_key']) ? $params['ssl_key'] : NULL,
				'ssl_action'              => isset($params['ssl_action']) ? $params['ssl_action'] : NULL,
				'stats_password'          => isset($params['stats_password']) ? $params['stats_password'] : NULL,
				'stats_type'              => isset($params['stats_type']) ? $params['stats_type'] : 'webalizer',
				'allow_override'          => isset($params['allow_override']) ? $params['allow_override'] : 'All',
				'apache_directives'       => isset($params['apache_directives']) ? $params['apache_directives'] : NULL,
				'nginx_directives'        => isset($params['nginx_directives']) ? $params['nginx_directives'] : NULL,
				'php_fpm_use_socket'      => isset($params['php_fpm_use_socket']) ? $params['php_fpm_use_socket'] : 'y',
				'pm'                      => isset($params['pm']) ? $params['pm'] : 'dynamic',
				'pm_max_children'         => isset($params['pm_max_children']) ? $params['pm_max_children'] : 10,
				'pm_start_servers'        => isset($params['pm_start_servers']) ? $params['pm_start_servers'] : 2,
				'pm_min_spare_servers'    => isset($params['pm_min_spare_servers']) ? $params['pm_min_spare_servers'] : 1,
				'pm_max_spare_servers'    => isset($params['pm_max_spare_servers']) ? $params['pm_max_spare_servers'] : 5,
				'pm_process_idle_timeout' => isset($params['pm_process_idle_timeout']) ? $params['pm_process_idle_timeout'] : 10,
				'pm_max_requests'         => isset($params['pm_max_requests']) ? $params['pm_max_requests'] : 0,
				'php_open_basedir'        => isset($params['php_open_basedir']) ? $params['php_open_basedir'] : NULL,
				'custom_php_ini'          => isset($params['custom_php_ini']) ? $params['custom_php_ini'] : NULL,
				'backup_interval'         => isset($params['backup_interval']) ? $params['backup_interval'] : 'none',
				'backup_copies'           => isset($params['backup_copies']) ? $params['backup_copies'] : 1,
				'backup_excludes'         => isset($params['backup_excludes']) ? $params['backup_excludes'] : NULL,
				'active'                  => isset($params['active']) ? $params['active'] : 'y',
				'traffic_quota_lock'      => isset($params['traffic_quota_lock']) ? $params['traffic_quota_lock'] : 'n',
				'fastcgi_php_version'     => isset($params['fastcgi_php_version']) ? $params['fastcgi_php_version'] : NULL,
				'proxy_directives'        => isset($params['proxy_directives']) ? $params['proxy_directives'] : NULL,
				'enable_spdy'             => isset($params['enable_spdy']) ? $params['enable_spdy'] : 'n',
				'rewrite_rules'           => isset($params['rewrite_rules']) ? $params['rewrite_rules'] : NULL,
				'added_date'              => isset($params['added_date']) ? $params['added_date'] : date('Y-m-d'),
				'added_by'                => isset($params['added_by']) ? $params['added_by'] : $this->CI->config->item('ispconfig_username'),
				'directive_snippets_id'   => isset($params['directive_snippets_id']) ? $params['directive_snippets_id'] : 0,
				'enable_pagespeed'        => isset($params['enable_pagespeed']) ? $params['enable_pagespeed'] : 'n',
				'http_port'               => isset($params['http_port']) ? $params['http_port'] : 80,
				'https_port'              => isset($params['https_port']) ? $params['https_port'] : 443,
			);

			return $this->SoapClient->sites_web_subdomain_add($this->ID, $client_id, $params, $readonly);
		}
		catch (SoapFault $e)
		{
			$errors = $this->get_error($e->getMessage());
			if (trim($errors['error'][0]) == 'domain_error_unique')
			{
				return array(
					'error' => ['params[domain]' => 'The params[domain] field must contain a unique value.'],
				);
			}

			return $errors;
		}
	}


	/**
	 * Update one record in Sites > Subdomain for website
	 *
	 * @param int   $client_id
	 * @param int   $domain_id
	 * @param array $params server_id, ip_address, ipv6_address, domain, hd_quota, traffic_quota, cgi, ssi, suexec,
	 *                      errordocs, is_subdomainwww, subdomain, php, ruby, python, perl, redirect_type,
	 *                      redirect_path, seo_redirect, rewrite_to_https, ssl, ssl_letsencrypt, ssl_state,
	 *                      ssl_locality, ssl_organisation, ssl_organisation_unit, ssl_country, ssl_domain, ssl_request,
	 *                      ssl_cert, ssl_bundle, ssl_key, ssl_action, stats_password, stats_type, allow_override,
	 *                      apache_directives, nginx_directives, php_fpm_use_socket, pm, pm_max_children,
	 *                      pm_start_servers, pm_min_spare_servers, pm_max_spare_servers, pm_process_idle_timeout,
	 *                      pm_max_requests, php_open_basedir, custom_php_ini, backup_interval, backup_copies,
	 *                      backup_excludes, active, traffic_quota_lock, fastcgi_php_version, proxy_directives,
	 *                      enable_spdy, rewrite_rules, added_date, added_by, directive_snippets_id, enable_pagespeed,
	 *                      http_port, https_port
	 *
	 * @return bool|array TRUE or error
	 */
	public function web_subdomain_update($client_id, $domain_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'domain_id' => $domain_id,
				'params'    => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('domain_id'),
				[
					'field' => 'params[server_id]',
					'label' => 'params[server_id]',
					'rules' => 'trim|greater_than[0]',
				],
				[
					'field' => 'params[ip_address]',
					'label' => 'params[ip_address]',
					'rules' => 'trim|valid_ip',
				],
				[
					'field' => 'params[ipv6_address]',
					'label' => 'params[ipv6_address]',
					'rules' => 'trim|valid_ip[ipv6]',
				],
				[
					'field' => 'params[domain]',
					'label' => 'params[domain]',
					'rules' => 'trim|valid_url',
				],
				[
					'field' => 'params[hd_quota]',
					'label' => 'params[hd_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[traffic_quota]',
					'label' => 'params[traffic_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[20]',
				],
				[
					'field' => 'params[cgi]',
					'label' => 'params[cgi]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssi]',
					'label' => 'params[ssi]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[suexec]',
					'label' => 'params[suexec]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[errordocs]',
					'label' => 'params[errordocs]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[1]',
				],
				[
					'field' => 'params[is_subdomainwww]',
					'label' => 'params[is_subdomainwww]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[1]',
				],
				[
					'field' => 'params[subdomain]',
					'label' => 'params[subdomain]',
					'rules' => 'trim|in_list[none,www,*]',
				],
				[
					'field' => 'params[php]',
					'label' => 'params[php]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[ruby]',
					'label' => 'params[ruby]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[python]',
					'label' => 'params[python]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[perl]',
					'label' => 'params[perl]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[redirect_type]',
					'label' => 'params[redirect_type]',
					'rules' => 'trim|in_list[no,last,break,redirect,permanent,proxy]',
				],
				[
					'field' => 'params[redirect_path]',
					'label' => 'params[redirect_path]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[seo_redirect]',
					'label' => 'params[seo_redirect]',
					'rules' => 'trim|in_list[non_www_to_www,www_to_non_www,*_domain_tld_to_domain_tld,*_domain_tld_to_www_domain_tld,*_to_domain_tld,*_to_www_domain_tld]',
				],
				[
					'field' => 'params[rewrite_to_https]',
					'label' => 'params[rewrite_to_https]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl]',
					'label' => 'params[ssl]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl_letsencrypt]',
					'label' => 'params[ssl_letsencrypt]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[ssl_state]',
					'label' => 'params[ssl_state]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_locality]',
					'label' => 'params[ssl_locality]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_organisation]',
					'label' => 'params[ssl_organisation]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_organisation_unit]',
					'label' => 'params[ssl_organisation_unit]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_country]',
					'label' => 'params[ssl_country]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[ssl_domain]',
					'label' => 'params[ssl_domain]',
					'rules' => 'trim|valid_url',
				],
				[
					'field' => 'params[ssl_action]',
					'label' => 'params[ssl_action]',
					'rules' => 'trim|in_list[create,save,del]',
				],
				[
					'field' => 'params[stats_password]',
					'label' => 'params[stats_password]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[stats_type]',
					'label' => 'params[stats_type]',
					'rules' => 'trim|in_list[awstats,webalizer]',
				],
				[
					'field' => 'params[allow_override]',
					'label' => 'params[allow_override]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[apache_directives]',
					'label' => 'params[apache_directives]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[nginx_directives]',
					'label' => 'params[nginx_directives]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[php_fpm_use_socket]',
					'label' => 'params[php_fpm_use_socket]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[pm]',
					'label' => 'params[pm]',
					'rules' => 'trim|in_list[static,dynamic,ondemand]',
				],
				[
					'field' => 'params[pm_max_children]',
					'label' => 'params[pm_max_children]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_start_servers]',
					'label' => 'params[pm_start_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_min_spare_servers]',
					'label' => 'params[pm_min_spare_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_max_spare_servers]',
					'label' => 'params[pm_max_spare_servers]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_process_idle_timeout]',
					'label' => 'params[pm_process_idle_timeout]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[pm_max_requests]',
					'label' => 'params[pm_max_requests]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[php_open_basedir]',
					'label' => 'params[php_open_basedir]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[backup_interval]',
					'label' => 'params[backup_interval]',
					'rules' => 'trim|in_list[daily,weekly,monthly]',
				],
				[
					'field' => 'params[backup_copies]',
					'label' => 'params[backup_copies]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[10]',
				],
				[
					'field' => 'params[backup_excludes]',
					'label' => 'params[backup_excludes]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[active]',
					'label' => 'params[active]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[traffic_quota_lock]',
					'label' => 'params[traffic_quota_lock]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[fastcgi_php_version]',
					'label' => 'params[fastcgi_php_version]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[enable_spdy]',
					'label' => 'params[enable_spdy]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[added_date]',
					'label' => 'params[added_date]',
					'rules' => 'trim|max_length[10]',
				],
				[
					'field' => 'params[added_by]',
					'label' => 'params[added_by]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[directive_snippets_id]',
					'label' => 'params[directive_snippets_id]',
					'rules' => 'trim|max_length[11]',
				],
				[
					'field' => 'params[enable_pagespeed]',
					'label' => 'params[enable_pagespeed]',
					'rules' => 'trim|in_lis[n,y]',
				],
				[
					'field' => 'params[http_port]',
					'label' => 'params[http_port]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[65535]',
				],
				[
					'field' => 'params[https_port]',
					'label' => 'params[https_port]',
					'rules' => 'trim|greater_than[0]|less_than_equal_to[65535]',
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

			return $this->SoapClient->sites_web_subdomain_update($this->ID, $client_id, $domain_id, $params);
		}
		catch (SoapFault $e)
		{
			$errors = $this->get_error($e->getMessage());
			if (trim($errors['error'][0]) == 'domain_error_unique')
			{
				return array(
					'error' => ['params[domain]' => 'The params[domain] field must contain a unique value.'],
				);
			}

			return $errors;
		}
	}


	/**
	 * Delete one record in Sites > Subdomain for website
	 *
	 * @param int $domain_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function web_subdomain_delete($domain_id)
	{
		if (is_array($validation = $this->validate_primary_key('domain_id', $domain_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->sites_web_subdomain_delete($this->ID, $domain_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Sites > Protected Folders > Web Folder
	 *
	 * @param int $web_folder_id
	 *
	 * @return array web_folder.* or error
	 */
	public function web_folder_get($web_folder_id)
	{
		if (is_array($validation = $this->validate_primary_key('web_folder_id', $web_folder_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->sites_web_folder_get($this->ID, $web_folder_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Sites > Protected Folders > Web Folder
	 *
	 * @param int   $client_id
	 * @param array $params server_id, parent_domain_id, path, active
	 *
	 * @return int|array web_folder.web_folder_id or error
	 */
	public function web_folder_add($client_id, $params)
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
					'field' => 'params[index]',
					'label' => 'params[index]',
					// Todo
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
				'parent_domain_id' => isset($params['parent_domain_id']) ? $params['parent_domain_id'] : 0,
				'path'             => isset($params['path']) ? $params['path'] : '',
				'active'           => isset($params['active']) ? $params['active'] : 'y',
			);

			return $this->SoapClient->sites_web_folder_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in Sites > Protected Folders > Web Folder
	 *
	 * @param int   $client_id
	 * @param int   $web_folder_id
	 * @param array $params server_id, parent_domain_id, path, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function web_folder_update($client_id, $web_folder_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'     => $client_id,
				'web_folder_id' => $web_folder_id,
				'params'        => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('web_folder_id'),
				[
					'field' => 'params[index]',
					'label' => 'params[index]',
					// Todo
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

			return $this->SoapClient->sites_web_folder_update($this->ID, $client_id, $web_folder_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Sites > Protected Folders > Web Folder
	 *
	 * @param int $web_folder_id
	 *
	 * @return int|array affected rows or error
	 */
	public function web_folder_delete($web_folder_id)
	{
		if (is_array($validation = $this->validate_primary_key('web_folder_id', $web_folder_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->sites_web_folder_delete($this->ID, $web_folder_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Sites > Protected Folders
	 *
	 * @param int $web_folder_user_id
	 *
	 * @return array web_folder_user.* or error
	 */
	public function web_folder_user_get($web_folder_user_id)
	{
		if (is_array($validation = $this->validate_primary_key('web_folder_user_id', $web_folder_user_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->sites_web_folder_user_get($this->ID, $web_folder_user_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Sites > Protected Folders > Web Folder
	 *
	 * @param int   $client_id
	 * @param int   $web_folder_id
	 * @param array $params server_id, web_folder_id, username, password, active
	 *
	 * @return int|array web_folder_user.web_folder_user_id or error
	 */
	public function web_folder_user_add($client_id, $web_folder_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'     => $client_id,
				'web_folder_id' => $web_folder_id,
				'params'        => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('web_folder_id'),
				[
					'field' => 'params[index]',
					'label' => 'params[index]',
					// Todo
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
				'web_folder_id' => $web_folder_id,
				'username'      => isset($params['username']) ? $params['username'] : '',
				'password'      => isset($params['password']) ? $params['password'] : '',
				'active'        => isset($params['active']) ? $params['active'] : 'y',
			);

			return $this->SoapClient->sites_web_folder_user_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update Sites > Protected Folders > Web Folder
	 *
	 * @param int   $client_id
	 * @param int   $web_folder_user_id
	 * @param array $params server_id, web_folder_id, username, password, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function web_folder_user_update($client_id, $web_folder_user_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'          => $client_id,
				'web_folder_user_id' => $web_folder_user_id,
				'params'             => $params,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('web_folder_user_id'),
				[
					'field' => 'params[index]',
					'label' => 'params[index]',
					// Todo
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

			return $this->SoapClient->sites_web_folder_user_update($this->ID, $client_id, $web_folder_user_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Sites > Protected Folders
	 *
	 * @param int $web_folder_user_id
	 *
	 * @return int|array affected rows or error
	 */
	public function web_folder_user_delete($web_folder_user_id)
	{
		if (is_array($validation = $this->validate_primary_key('web_folder_user_id', $web_folder_user_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->sites_web_folder_user_delete($this->ID, $web_folder_user_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Websites > Web Domain > Backup
	 *
	 * Get existing backup info about web files and database
	 *
	 * @param int $domain_id
	 *
	 * @return array
	 */
	public function web_domain_backup_list($domain_id)
	{
		if (is_array($validation = $this->validate_primary_key('domain_id', $domain_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->sites_web_domain_backup_list($this->ID, $domain_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	public function web_domain_backup($domain_id)
	{
		if (is_array($validation = $this->validate_primary_key('domain_id', $domain_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->sites_web_domain_backup($this->ID, $domain_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	public function aps_update_package_list()
	{
		// Todo
	}


	public function aps_available_packages_list()
	{
		// Todo
	}


	public function aps_get_package_details()
	{
		// Todo
	}


	public function aps_get_package_file()
	{
		// Todo
	}


	public function aps_get_package_settings()
	{
		// Todo
	}


	public function aps_change_package_status()
	{
		// Todo
	}


	public function aps_install_package()
	{
		// Todo
	}


	public function aps_instance_get()
	{
		// Todo
	}


	public function aps_instance_settings_get()
	{
		// Todo
	}


	public function aps_instance_delete()
	{
		// Todo
	}

	/**
	 * Get Disc Quota usage
	 *
	 * @param int $client_id
	 *
	 * @return array
	 */
	public function quota_get_by_user($client_id)
	{
		if (is_array($validation = $this->validate_primary_key('client_id', $client_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->quota_get_by_user($this->ID, $client_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get Traffic Quota usage
	 *
	 * @param int $client_id
	 *
	 * @return array
	 */
	public function trafficquota_get_by_user($client_id)
	{
		if (is_array($validation = $this->validate_primary_key('client_id', $client_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->trafficquota_get_by_user($this->ID, $client_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get FTP Traffic Quota usage
	 *
	 * @param int $client_id
	 *
	 * @return array
	 */
	public function ftptrafficquota_data($client_id)
	{
		if (is_array($validation = $this->validate_primary_key('client_id', $client_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->ftptrafficquota_data($this->ID, $client_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get Database Traffic Quota usage
	 *
	 * @param int $client_id
	 *
	 * @return array
	 */
	public function databasequota_get_by_user($client_id)
	{
		if (is_array($validation = $this->validate_primary_key('client_id', $client_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->databasequota_get_by_user($this->ID, $client_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


}
