<?php
/**
 * codeigniter-ispconfig-new
 *
 * @package  codeigniter-ispconfig-new
 * @author   Natan Felles <natanfelles@gmail.com>
 */
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class ispconfig_mail
 *
 * @package ispconfig
 */
class Ispconfig_mail extends Ispconfig {


	/**
	 * ispconfig_mail constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Get one record from Mail > Domain
	 *
	 * @param int $domain_id
	 *
	 * @return array mail_domain.* or error
	 */
	public function domain_get($domain_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['domain_id' => $domain_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/domain_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_domain_get($this->ID, $domain_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Domain
	 *
	 * @param int   $client_id
	 * @param array $mail_domain server_id,  domain,  active
	 *
	 * @return int|array mail_domain.domain_id or error
	 */
	public function domain_add($client_id, $mail_domain)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'   => $client_id,
				'mail_domain' => $mail_domain,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/domain_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($mail_domain['server_id']) ? $mail_domain['server_id'] : 0,
				'domain'    => isset($mail_domain['domain']) ? $mail_domain['domain'] : '',
				'active'    => isset($mail_domain['active']) ? $mail_domain['active'] : 'n',
			);

			return $this->SoapClient->mail_domain_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/***
	 * Update one record in Mail > Domain
	 *
	 * @param int   $client_id
	 * @param int   $domain_id
	 * @param array $params server_id,  domain,  active
	 *
	 * @return bool|array TRUE or error
	 */
	public function domain_update($client_id, $domain_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'domain_id' => $domain_id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/domain_update'))
			{
				//return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_domain_update($this->ID, $client_id, $domain_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Domain
	 *
	 * @param int $domain_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function domain_delete($domain_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['domain_id' => $domain_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/domain_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->mail_domain_delete($this->ID, $domain_id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Domain Alias
	 *
	 * @param int $forwarding_id
	 *
	 * @return array mail_forwarding.* or error
	 */
	public function aliasdomain_get($forwarding_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['forwarding_id' => $forwarding_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/aliasdomain_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_aliasdomain_get($this->ID, $forwarding_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Domain Alias
	 *
	 * @param int   $client_id
	 * @param array $mail_forwarding server_id, source, destination, active
	 *
	 * @return int|array mail_forwarding.forwarding_id or error
	 */
	public function aliasdomain_add($client_id, $mail_forwarding)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'       => $client_id,
				'mail_forwarding' => $mail_forwarding,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/aliasdomain_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id'   => isset($mail_forwarding['server_id']) ? $mail_forwarding['server_id'] : 0,
				'source'      => isset($mail_forwarding['source']) ? $mail_forwarding['source'] : '',
				'destination' => isset($mail_forwarding['destination']) ? $mail_forwarding['destination'] : '',
				'active'      => isset($mail_forwarding['active']) ? $mail_forwarding['active'] : 'n',
				'type'        => 'aliasdomain',
			);

			return $this->SoapClient->mail_aliasdomain_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in Mail > Domain Alias
	 *
	 * @param int   $client_id
	 * @param int   $forwarding_id
	 * @param array $params server_id, source, destination, active
	 *
	 * @return bool|array TRUE or error or error
	 */
	public function aliasdomain_update($client_id, $forwarding_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'     => $client_id,
				'forwarding_id' => $forwarding_id,
				'params'        => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/aliasdomain_update'))
			{
				//return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_aliasdomain_update($this->ID, $client_id, $forwarding_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Domain Alias
	 *
	 * @param int $forwarding_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function aliasdomain_delete($forwarding_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['forwarding_id' => $forwarding_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/aliasdomain_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_aliasdomain_delete($this->ID, $forwarding_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Mailing List
	 *
	 * @param int $mailinglist_id
	 *
	 * @return array mail_mailinglist.* or error
	 */
	public function mailinglist_get($mailinglist_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['mailinglist_id' => $mailinglist_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/mailinglist_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_mailinglist_get($this->ID, $mailinglist_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Mailing List
	 *
	 * @param int   $client_id
	 * @param array $mail_mailinglist server_id, domain, listname, email, password
	 *
	 * @return int|array mail_mailinglist.mailinglist_id or error
	 */
	public function mailinglist_add($client_id, $mail_mailinglist)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'        => $client_id,
				'mail_mailinglist' => $mail_mailinglist,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/mailinglist_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($mail_mailinglist['server_id']) ? $mail_mailinglist['server_id'] : 0,
				'domain'    => isset($mail_mailinglist['domain']) ? $mail_mailinglist['domain'] : '',
				'listname'  => isset($mail_mailinglist['listname']) ? $mail_mailinglist['listname'] : '',
				'email'     => isset($mail_mailinglist['email']) ? $mail_mailinglist['email'] : '',
				'password'  => isset($mail_mailinglist['password']) ? $mail_mailinglist['password'] : '',
			);

			return $this->SoapClient->mail_mailinglist_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in Mail > Mailing List
	 *
	 * @param int   $client_id
	 * @param int   $mailinglist_id
	 * @param array $params server_id, domain, listname, email, password
	 *
	 * @return bool|array TRUE or error or error
	 */
	public function mailinglist_update($client_id, $mailinglist_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'      => $client_id,
				'mailinglist_id' => $mailinglist_id,
				'params'         => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/mailinglist_update'))
			{
				//return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_mailinglist_update($this->ID, $client_id, $mailinglist_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Mailing List
	 *
	 * @param int $mailinglist_id
	 *
	 * @return bool|array TRUE or error or error
	 */
	public function mailinglist_delete($mailinglist_id = 0)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['mailinglist_id' => $mailinglist_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/mailinglist_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->mail_mailinglist_delete($this->ID, $mailinglist_id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Email Mailbox
	 *
	 * @param int $mailuser_id
	 *
	 * @return array mail_user.* or error
	 */
	public function user_get($mailuser_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['mailuser_id' => $mailuser_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/user_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_user_get($this->ID, $mailuser_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Email Mailbox
	 *
	 * @param int   $client_id
	 * @param array $mail_user server_id,  email,  login,  password,  name,  uid,  gid,  maildir,
	 *                         quota,  cc, homedir,  autoresponder, autoresponder_start_date,
	 *                         autoresponder_end_date, autoresponder_subject,  autoresponder_text,
	 *                         move_junk, custom_mailfilter, postfix,  access,  disableimap,
	 *                         disablepop3,  disabledeliver,  disablesmtp, disablesieve,
	 *                         disablesieve-filter,  disablelda,  disablelmtp,  disabledoveadm
	 *
	 * @return int|array mail_user.mailuser_id or error
	 */
	public function user_add($client_id, $mail_user)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'mail_user' => $mail_user,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/user_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$maildir = explode('@', $mail_user['email']);
			$params = array(
				'server_id'                => isset($mail_user['server_id']) ? $mail_user['server_id'] : 0,
				'email'                    => isset($mail_user['email']) ? $mail_user['email'] : '',
				'login'                    => isset($mail_user['login']) ? $mail_user['login'] : $mail_user['email'],
				'password'                 => isset($mail_user['password']) ? $mail_user['password'] : '',
				'name'                     => isset($mail_user['name']) ? $mail_user['name'] : '',
				'uid'                      => isset($mail_user['uid']) ? $mail_user['uid'] : 5000,
				'gid'                      => isset($mail_user['gid']) ? $mail_user['gid'] : 5000,
				'maildir'                  => "/var/vmail/{$maildir[1]}/{$maildir[0]}",
				'quota'                    => isset($mail_user['quota']) ? $mail_user['quota'] : 0,
				'cc'                       => isset($mail_user['cc']) ? $mail_user['cc'] : '',
				'homedir'                  => isset($mail_user['homedir']) ? $mail_user['homedir'] : '/var/vmail',
				'autoresponder'            => isset($mail_user['autoresponder']) ? $mail_user['autoresponder'] : 'n',
				'autoresponder_start_date' => isset($mail_user['autoresponder_start_date']) ? $mail_user['autoresponder_start_date'] : array(
					'day'    => date('d'),
					'month'  => date('m'),
					'year'   => date('Y'),
					'hour'   => date('H'),
					'minute' => date('i'),
				),
				'autoresponder_end_date'   => isset($mail_user['autoresponder_end_date']) ? $mail_user['autoresponder_end_date'] : array(
					'day'    => date('d'),
					'month'  => date('m') + 1,
					'year'   => date('Y'),
					'hour'   => date('H'),
					'minute' => date('i'),
				),
				'autoresponder_subject'    => isset($mail_user['autoresponder_subject']) ? $mail_user['autoresponder_subject'] : '',
				'autoresponder_text'       => isset($mail_user['autoresponder_text']) ? $mail_user['autoresponder_text'] : '',
				'move_junk'                => isset($mail_user['move_junk']) ? $mail_user['move_junk'] : 'n',
				'custom_mailfilter'        => isset($mail_user['custom_mailfilter']) ? $mail_user['custom_mailfilter'] : '',
				'postfix'                  => isset($mail_user['postfix']) ? $mail_user['postfix'] : 'y',
				'access'                   => isset($mail_user['access']) ? $mail_user['access'] : 'y',
				'disableimap'              => isset($mail_user['disableimap']) ? $mail_user['disableimap'] : 'n',
				'disablepop3'              => isset($mail_user['disablepop3']) ? $mail_user['disablepop3'] : 'n',
				'disabledeliver'           => isset($mail_user['disabledeliver']) ? $mail_user['disabledeliver'] : 'n',
				'disablesmtp'              => isset($mail_user['disablesmtp']) ? $mail_user['disablesmtp'] : 'n',
				'disablesieve'             => isset($mail_user['disablesieve']) ? $mail_user['disablesieve'] : 'n',
				'disablesieve-filter'      => isset($mail_user['disablesieve-filter']) ? $mail_user['disablesieve-filter'] : 'n',
				'disablelda'               => isset($mail_user['disablelda']) ? $mail_user['disablelda'] : 'n',
				'disablelmtp'              => isset($mail_user['disablelmtp']) ? $mail_user['disablelmtp'] : 'n',
				'disabledoveadm'           => isset($mail_user['disabledoveadm']) ? $mail_user['disabledoveadm'] : 'n',
			);

			return $this->SoapClient->mail_user_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in Mail > Email Mailbox
	 *
	 * @param int   $client_id
	 * @param int   $mailuser_id
	 * @param array $params    server_id,  email,  login,  password,  name,  uid,  gid,  maildir,
	 *                         quota,  cc, homedir,  autoresponder, autoresponder_start_date,
	 *                         autoresponder_end_date, autoresponder_subject,  autoresponder_text,
	 *                         move_junk, custom_mailfilter, postfix,  access,  disableimap,
	 *                         disablepop3,  disabledeliver,  disablesmtp, disablesieve,
	 *                         disablesieve-filter,  disablelda,  disablelmtp,  disabledoveadm
	 *
	 * @return bool|array TRUE or error
	 */
	public function user_update($client_id, $mailuser_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'   => $client_id,
				'mailuser_id' => $mailuser_id,
				'params'      => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/user_update'))
			{
				//return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_user_update($this->ID, $client_id, $mailuser_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Email Mailbox
	 *
	 * @param int $mailuser_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function user_delete($mailuser_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['mailuser_id' => $mailuser_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/user_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->mail_user_delete($this->ID, $mailuser_id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Email Mailbox > Mail Filter
	 *
	 * @param int $filter_id
	 *
	 * @return array mail_user_filter.* or error
	 */
	public function user_filter_get($filter_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['filter_id' => $filter_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/user_filter_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_user_filter_get($this->ID, $filter_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Email Mailbox > Mail Filter
	 *
	 * @param int   $client_id
	 * @param array $mail_user_filter mailuser_id, rulename, source, searchterm, op, action, target,
	 *                                active
	 *
	 * @return array mail_user_filter.filter_id or error
	 */
	public function user_filter_add($client_id, $mail_user_filter)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'        => $client_id,
				'mail_user_filter' => $mail_user_filter,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/user_filter_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'mailuser_id' => isset($mail_user_filter['mailuser_id']) ? $mail_user_filter['mailuser_id'] : 0,
				'rulename'    => isset($mail_user_filter['rulename']) ? $mail_user_filter['rulename'] : '',
				'source'      => isset($mail_user_filter['source']) ? $mail_user_filter['source'] : '',
				'searchterm'  => isset($mail_user_filter['searchterm']) ? $mail_user_filter['searchterm'] : '',
				'op'          => isset($mail_user_filter['op']) ? $mail_user_filter['op'] : '',
				'action'      => isset($mail_user_filter['action']) ? $mail_user_filter['action'] : '',
				'target'      => isset($mail_user_filter['target']) ? $mail_user_filter['target'] : '',
				'active'      => isset($mail_user_filter['active']) ? $mail_user_filter['active'] : 'n',
			);

			return $this->SoapClient->mail_user_filter_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	public function user_filter_update($client_id, $filter_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'filter_id' => $filter_id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/user_filter_update'))
			{
				//return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_user_filter_update($this->ID, $client_id, $filter_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Email Mailbox > Mail Filter
	 *
	 * @param int $filter_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function user_filter_delete($filter_id = 0)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['filter_id' => $filter_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/user_filter_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->mail_user_filter_delete($this->ID, $filter_id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Domain Alias
	 *
	 * @param int $forwarding_id
	 *
	 * @return array mail_forwarding.* or error
	 */
	public function alias_get($forwarding_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['forwarding_id' => $forwarding_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/alias_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_alias_get($this->ID, $forwarding_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Email Alias
	 *
	 * @param int   $client_id
	 * @param array $mail_forwarding server_id, source, destination, active
	 *
	 * @return int|array mail_forwarding.forwarding_id or error
	 */
	public function alias_add($client_id, $mail_forwarding)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'       => $client_id,
				'mail_forwarding' => $mail_forwarding,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/alias_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id'   => isset($mail_forwarding['server_id']) ? $mail_forwarding['server_id'] : 0,
				'source'      => isset($mail_forwarding['source']) ? $mail_forwarding['source'] : '',
				'destination' => isset($mail_forwarding['destination']) ? $mail_forwarding['destination'] : '',
				'active'      => isset($mail_forwarding['active']) ? $mail_forwarding['active'] : 'n',
				'type'        => 'alias',
			);

			return $this->SoapClient->mail_alias_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in Mail > Email Alias
	 *
	 * @param int   $client_id
	 * @param int   $forwarding_id
	 * @param array $params server_id, source, destination, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function alias_update($client_id, $forwarding_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'     => $client_id,
				'forwarding_id' => $forwarding_id,
				'params'        => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/alias_update'))
			{
				//return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_alias_update($this->ID, $client_id, $forwarding_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Email Alias
	 *
	 * @param int $forwarding_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function alias_delete($forwarding_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['forwarding_id' => $forwarding_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/alias_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->mail_alias_delete($this->ID, $forwarding_id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Email Forward
	 *
	 * @param int $forwarding_id
	 *
	 * @return array mail_forwarding.* or error
	 */
	public function forward_get($forwarding_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['forwarding_id' => $forwarding_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/forward_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_forward_get($this->ID, $forwarding_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Email Forward
	 *
	 * @param int   $client_id
	 * @param array $mail_forwarding server_id, source, destination, active
	 *
	 * @return int|array mail_forwarding.forwarding_id or error
	 */
	public function forward_add($client_id, $mail_forwarding)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'       => $client_id,
				'mail_forwarding' => $mail_forwarding,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/forward_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id'   => isset($mail_forwarding['server_id']) ? $mail_forwarding['server_id'] : 0,
				'source'      => isset($mail_forwarding['source']) ? $mail_forwarding['source'] : '',
				'destination' => isset($mail_forwarding['destination']) ? $mail_forwarding['destination'] : '',
				'active'      => isset($mail_forwarding['active']) ? $mail_forwarding['active'] : 'n',
				'type'        => 'forward',
			);

			return $this->SoapClient->mail_forward_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in Mail > Email Forward
	 *
	 * @param int   $client_id
	 * @param int   $forwarding_id
	 * @param array $params server_id, source, destination, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function forward_update($client_id, $forwarding_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'     => $client_id,
				'forwarding_id' => $forwarding_id,
				'params'        => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/forward_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_forward_update($this->ID, $client_id, $forwarding_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Email Forward
	 *
	 * @param int $forwarding_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function forward_delete($forwarding_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['forwarding_id' => $forwarding_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/forward_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->mail_forward_delete($this->ID, $forwarding_id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Email Catchall
	 *
	 * @param int $forwarding_id
	 *
	 * @return array mail_forwarding.* or error
	 */
	public function catchall_get($forwarding_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['forwarding_id' => $forwarding_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/catchall_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_catchall_get($this->ID, $forwarding_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Email Catchall
	 *
	 * @param int   $client_id
	 * @param array $mail_forwarding server_id, source, destination, active
	 *
	 * @return int|array mail_forwarding.forwarding_id or error
	 */
	public function catchall_add($client_id, $mail_forwarding)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'       => $client_id,
				'mail_forwarding' => $mail_forwarding,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/catchall_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id'   => isset($mail_forwarding['server_id']) ? $mail_forwarding['server_id'] : 0,
				'source'      => isset($mail_forwarding['source']) ? $mail_forwarding['source'] : '',
				'destination' => isset($mail_forwarding['destination']) ? $mail_forwarding['destination'] : '',
				'active'      => isset($mail_forwarding['active']) ? $mail_forwarding['active'] : 'n',
				'type'        => 'catchall',
			);

			return $this->SoapClient->mail_catchall_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in Mail > Email Catchall
	 *
	 * @param int   $client_id
	 * @param int   $forwarding_id
	 * @param array $params server_id, source, destination, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function catchall_update($client_id, $forwarding_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'     => $client_id,
				'forwarding_id' => $forwarding_id,
				'params'        => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/catchall_update'))
			{
				//return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_catchall_update($this->ID, $client_id, $forwarding_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Email Catchall
	 *
	 * @param int $forwarding_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function catchall_delete($forwarding_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['forwarding_id' => $forwarding_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/catchall_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->mail_catchall_delete($this->ID, $forwarding_id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Email Routing
	 *
	 * @param int $transport_id
	 *
	 * @return array mail_transport.* or error
	 */
	public function transport_get($transport_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['transport_id' => $transport_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/transport_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_transport_get($this->ID, $transport_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Email Routing
	 *
	 * @param int   $client_id
	 * @param array $mail_transport server_id, domain, transport, sort_order, active
	 *
	 * @return int|array mail_transport.transport_id or error
	 */
	public function transport_add($client_id, $mail_transport)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'      => $client_id,
				'mail_transport' => $mail_transport,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/transport_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id'  => isset($mail_transport['server_id']) ? $mail_transport['server_id'] : 0,
				'domain'     => isset($mail_transport['domain']) ? $mail_transport['domain'] : '',
				'transport'  => isset($mail_transport['transport']) ? $mail_transport['transport'] : '',
				'sort_order' => isset($mail_transport['sort_order']) ? $mail_transport['sort_order'] : 5,
				'active'     => isset($mail_transport['active']) ? $mail_transport['active'] : 'n',
			);

			return $this->SoapClient->mail_transport_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in Mail > Email Routing
	 *
	 * @param int   $client_id
	 * @param int   $transport_id
	 * @param array $params server_id, domain, transport, sort_order, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function transport_update($client_id, $transport_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'    => $client_id,
				'transport_id' => $transport_id,
				'params'       => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/transport_update'))
			{
				//return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_transport_update($this->ID, $client_id, $transport_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Email Routing
	 *
	 * @param int $transport_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function transport_delete($transport_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['transport_id' => $transport_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/transport_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->mail_transport_delete($this->ID, $transport_id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Relay Recipients
	 *
	 * @param int $relay_recipient_id
	 *
	 * @return array mail_relay_recipient.* or error
	 */
	public function relay_recipient_get($relay_recipient_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['relay_recipient_id' => $relay_recipient_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/relay_recipient_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_relay_recipient_get($this->ID, $relay_recipient_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Relay Recipients
	 *
	 * @param int   $client_id
	 * @param array $mail_relay_recipient server_id,  source,  active
	 *
	 * @return int|array mail_relay_recipient.relay_recipient_id or error
	 */
	public function relay_recipient_add($client_id, $mail_relay_recipient)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'            => $client_id,
				'mail_relay_recipient' => $mail_relay_recipient,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/relay_recipient_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($mail_relay_recipient['server_id']) ? $mail_relay_recipient['server_id'] : 0,
				'source'    => isset($mail_relay_recipient['source']) ? $mail_relay_recipient['source'] : 0,
				'access'    => 'OK',
				'active'    => isset($mail_relay_recipient['active']) ? $mail_relay_recipient['active'] : 'y',
			);

			return $this->SoapClient->mail_relay_recipient_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in Mail > Relay Recipients
	 *
	 * @param int   $client_id
	 * @param int   $relay_recipient_id
	 * @param array $params server_id,  source,  active
	 *
	 * @return int|array mail_relay_recipient.relay_recipient_id or error
	 */
	public function relay_recipient_update($client_id, $relay_recipient_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'          => $client_id,
				'relay_recipient_id' => $relay_recipient_id,
				'params'             => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/relay_recipient_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_relay_recipient_update($this->ID, $client_id, $relay_recipient_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Relay Recipients
	 *
	 * @param int $relay_recipient_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function relay_recipient_delete($relay_recipient_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['relay_recipient_id' => $relay_recipient_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/relay_recipient_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->mail_relay_recipient_delete($this->ID, $relay_recipient_id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Spamfilter Whitelist
	 *
	 * @param int $wblist_id
	 *
	 * @return array spamfilter_wblist.* or error
	 */
	public function spamfilter_whitelist_get($wblist_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['wblist_id' => $wblist_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/spamfilter_whitelist_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_spamfilter_whitelist_get($this->ID, $wblist_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Spamfilter Whitelist
	 *
	 * @param int   $client_id
	 * @param array $spamfilter_wblist server_id,  rid, email,  priority,  active
	 *
	 * @return int|array spamfilter_wblist.wblist_id or error
	 */
	public function spamfilter_whitelist_add($client_id, $spamfilter_wblist)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'         => $client_id,
				'spamfilter_wblist' => $spamfilter_wblist,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/spamfilter_whitelist_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($spamfilter_wblist['server_id']) ? $spamfilter_wblist['server_id'] : 0,
				'wb'        => 'W',
				'rid'       => isset($spamfilter_wblist['rid']) ? $spamfilter_wblist['rid'] : 0,
				'email'     => isset($spamfilter_wblist['email']) ? $spamfilter_wblist['email'] : '',
				'priority'  => isset($spamfilter_wblist['priority']) ? $spamfilter_wblist['priority'] : 5,
				'active'    => isset($spamfilter_wblist['active']) ? $spamfilter_wblist['active'] : 'n',
			);

			return $this->SoapClient->mail_spamfilter_whitelist_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in Mail > Spamfilter Whitelist
	 *
	 * @param int   $client_id
	 * @param int   $wblist_id
	 * @param array $params server_id,  rid, email,  priority,  active
	 *
	 * @return int|array spamfilter_wblist.wblist_id or error
	 */
	public function spamfilter_whitelist_update($client_id, $wblist_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'wblist_id' => $wblist_id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/spamfilter_whitelist_update'))
			{
				//return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_spamfilter_whitelist_update($this->ID, $client_id, $wblist_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Spamfilter Whitelist
	 *
	 * @param int $wblist_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function spamfilter_whitelist_delete($wblist_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['wblist_id' => $wblist_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/spamfilter_whitelist_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->mail_spamfilter_whitelist_delete($this->ID, $wblist_id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Spamfilter Blacklist
	 *
	 * @param int $wblist_id
	 *
	 * @return array spamfilter_wblist.* or error
	 */
	public function spamfilter_blacklist_get($wblist_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['wblist_id' => $wblist_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/spamfilter_blacklist_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_spamfilter_blacklist_get($this->ID, $wblist_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Spamfilter Blacklist
	 *
	 * @param int   $client_id
	 * @param array $params server_id,  rid, email,  priority,  active
	 *
	 * @return int|array spamfilter_wblist.wblist_id or error
	 */
	public function spamfilter_blacklist_add($client_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/spamfilter_blacklist_add'))
			{
				//return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_spamfilter_blacklist_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in Mail > Spamfilter Blacklist
	 *
	 * @param int   $client_id
	 * @param int   $wblist_id
	 * @param array $params server_id,  rid, email,  priority,  active
	 *
	 * @return int|array spamfilter_wblist.wblist_id or error
	 */
	public function spamfilter_blacklist_update($client_id, $wblist_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'wblist_id' => $wblist_id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/spamfilter_blacklist_update'))
			{
				//return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_spamfilter_blacklist_update($this->ID, $client_id, $wblist_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Spamfilter Blacklist
	 *
	 * @param int $wblist_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function spamfilter_blacklist_delete($wblist_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['wblist_id' => $wblist_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/spamfilter_blacklist_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->mail_spamfilter_blacklist_delete($this->ID, $wblist_id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Spamfilter User / Domain
	 *
	 * @param int $id
	 *
	 * @return array spamfilter_users.* or error
	 */
	public function spamfilter_user_get($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/spamfilter_user_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_spamfilter_user_get($this->ID, $id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Spamfilter User / Domain
	 *
	 * @param int   $client_id
	 * @param array $spamfilter_users server_id,  priority,  policy_id,  email,  fullname,  local
	 *
	 * @return int|array spamfilter_users.id or error
	 */
	public function spamfilter_user_add($client_id, $spamfilter_users)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'        => $client_id,
				'spamfilter_users' => $spamfilter_users,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/spamfilter_user_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($spamfilter_users['server_id']) ? $spamfilter_users['server_id'] : 0,
				'priority'  => isset($spamfilter_users['priority']) ? $spamfilter_users['priority'] : 7,
				'policy_id' => isset($spamfilter_users['policy_id']) ? $spamfilter_users['policy_id'] : 1,
				'email'     => isset($spamfilter_users['email']) ? $spamfilter_users['email'] : '',
				'fullname'  => isset($spamfilter_users['fullname']) ? $spamfilter_users['fullname'] : '',
				'local'     => isset($spamfilter_users['local']) ? $spamfilter_users['local'] : 'Y',
			);

			return $this->SoapClient->mail_spamfilter_user_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in Mail > Spamfilter User / Domain
	 *
	 * @param int   $client_id
	 * @param int   $id
	 * @param array $params server_id,  priority,  policy_id,  email,  fullname,  local
	 *
	 * @return bool|array TRUE or error
	 */
	public function spamfilter_user_update($client_id, $id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'id'        => $id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/spamfilter_user_update'))
			{
				//return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_spamfilter_user_update($this->ID, $client_id, $id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Spamfilter User / Domain
	 *
	 * @param int $id
	 *
	 * @return bool|array TRUE or error
	 */
	public function spamfilter_user_delete($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/spamfilter_user_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->mail_spamfilter_user_delete($this->ID, $id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Spamfilter Policy
	 *
	 * @param int $id
	 *
	 * @return array spamfilter_policy.* or error
	 */
	public function policy_get($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/policy_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_policy_get($this->ID, $id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Spamfilter Policy
	 *
	 * @param int   $client_id
	 * @param array $spamfilter_policy policy_name,  virus_lover,  spam_lover,  banned_files_lover,
	 *                                 bad_header_lover,  bypass_virus_checks, bypass_spam_checks,
	 *                                 bypass_banned_checks,  bypass_header_checks,
	 *                                 spam_modifies_subj, virus_quarantine_to, spam_quarantine_to,
	 *                                 banned_quarantine_to, bad_header_quarantine_to,
	 *                                 clean_quarantine_to, other_quarantine_to, spam_tag_level,
	 *                                 spam_tag2_level,  spam_kill_level, spam_dsn_cutoff_level,
	 *                                 spam_quarantine_cutoff_level, addr_extension_virus,
	 *                                 addr_extension_spam,  addr_extension_banned,
	 *                                 addr_extension_bad_header,  warnvirusrecip,  warnbannedrecip,
	 *                                 warnbadhrecip,  newvirus_admin,  virus_admin, banned_admin,
	 *                                 bad_header_admin,  spam_admin,  spam_subject_tag,
	 *                                 spam_subject_tag2, message_size_limit, banned_rulenames,
	 *                                 policyd_quota_in, policyd_quota_in_period,
	 *                                 policyd_quota_out,  policyd_quota_out_period,
	 *                                 policyd_greylist
	 *
	 * @return int|array spamfilter_policy.id or error
	 */
	public function policy_add($client_id, $spamfilter_policy)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'         => $client_id,
				'spamfilter_policy' => $spamfilter_policy,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/policy_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'policy_name'                  => isset($spamfilter_policy['policy_name']) ? $spamfilter_policy['policy_name'] : '',
				'virus_lover'                  => isset($spamfilter_policy['virus_lover']) ? $spamfilter_policy['virus_lover'] : 'N',
				'spam_lover'                   => isset($spamfilter_policy['spam_lover']) ? $spamfilter_policy['spam_lover'] : 'N',
				'banned_files_lover'           => isset($spamfilter_policy['banned_files_lover']) ? $spamfilter_policy['banned_files_lover'] : 'N',
				'bad_header_lover'             => isset($spamfilter_policy['bad_header_lover']) ? $spamfilter_policy['bad_header_lover'] : 'N',
				'bypass_virus_checks'          => isset($spamfilter_policy['bypass_virus_checks']) ? $spamfilter_policy['bypass_virus_checks'] : 'N',
				'bypass_spam_checks'           => isset($spamfilter_policy['bypass_spam_checks']) ? $spamfilter_policy['bypass_spam_checks'] : 'N',
				'bypass_banned_checks'         => isset($spamfilter_policy['bypass_banned_checks']) ? $spamfilter_policy['bypass_banned_checks'] : 'N',
				'bypass_header_checks'         => isset($spamfilter_policy['bypass_header_checks']) ? $spamfilter_policy['bypass_header_checks'] : 'N',
				'spam_modifies_subj'           => isset($spamfilter_policy['spam_modifies_subj']) ? $spamfilter_policy['spam_modifies_subj'] : 'N',
				'virus_quarantine_to'          => isset($spamfilter_policy['virus_quarantine_to']) ? $spamfilter_policy['virus_quarantine_to'] : '',
				'spam_quarantine_to'           => isset($spamfilter_policy['spam_quarantine_to']) ? $spamfilter_policy['spam_quarantine_to'] : '',
				'banned_quarantine_to'         => isset($spamfilter_policy['banned_quarantine_to']) ? $spamfilter_policy['banned_quarantine_to'] : '',
				'bad_header_quarantine_to'     => isset($spamfilter_policy['bad_header_quarantine_to']) ? $spamfilter_policy['bad_header_quarantine_to'] : '',
				'clean_quarantine_to'          => isset($spamfilter_policy['clean_quarantine_to']) ? $spamfilter_policy['clean_quarantine_to'] : '',
				'other_quarantine_to'          => isset($spamfilter_policy['other_quarantine_to']) ? $spamfilter_policy['other_quarantine_to'] : '',
				'spam_tag_level'               => isset($spamfilter_policy['spam_tag_level']) ? $spamfilter_policy['spam_tag_level'] : 0,
				'spam_tag2_level'              => isset($spamfilter_policy['spam_tag2_level']) ? $spamfilter_policy['spam_tag2_level'] : 0,
				'spam_kill_level'              => isset($spamfilter_policy['spam_kill_level']) ? $spamfilter_policy['spam_kill_level'] : 0,
				'spam_dsn_cutoff_level'        => isset($spamfilter_policy['spam_dsn_cutoff_level']) ? $spamfilter_policy['spam_dsn_cutoff_level'] : 0,
				'spam_quarantine_cutoff_level' => isset($spamfilter_policy['spam_quarantine_cutoff_level']) ? $spamfilter_policy['spam_quarantine_cutoff_level'] : 0,
				'addr_extension_virus'         => isset($spamfilter_policy['addr_extension_virus']) ? $spamfilter_policy['addr_extension_virus'] : '',
				'addr_extension_spam'          => isset($spamfilter_policy['addr_extension_spam']) ? $spamfilter_policy['addr_extension_spam'] : '',
				'addr_extension_banned'        => isset($spamfilter_policy['addr_extension_banned']) ? $spamfilter_policy['addr_extension_banned'] : '',
				'addr_extension_bad_header'    => isset($spamfilter_policy['addr_extension_bad_header']) ? $spamfilter_policy['addr_extension_bad_header'] : '',
				'warnvirusrecip'               => isset($spamfilter_policy['warnvirusrecip']) ? $spamfilter_policy['warnvirusrecip'] : 'N',
				'warnbannedrecip'              => isset($spamfilter_policy['warnbannedrecip']) ? $spamfilter_policy['warnbannedrecip'] : 'N',
				'warnbadhrecip'                => isset($spamfilter_policy['warnbadhrecip']) ? $spamfilter_policy['warnbadhrecip'] : 'N',
				'newvirus_admin'               => isset($spamfilter_policy['newvirus_admin']) ? $spamfilter_policy['newvirus_admin'] : '',
				'virus_admin'                  => isset($spamfilter_policy['virus_admin']) ? $spamfilter_policy['virus_admin'] : '',
				'banned_admin'                 => isset($spamfilter_policy['banned_admin']) ? $spamfilter_policy['banned_admin'] : '',
				'bad_header_admin'             => isset($spamfilter_policy['bad_header_admin']) ? $spamfilter_policy['bad_header_admin'] : '',
				'spam_admin'                   => isset($spamfilter_policy['spam_admin']) ? $spamfilter_policy['spam_admin'] : '',
				'spam_subject_tag'             => isset($spamfilter_policy['spam_subject_tag']) ? $spamfilter_policy['spam_subject_tag'] : '',
				'spam_subject_tag2'            => isset($spamfilter_policy['spam_subject_tag2']) ? $spamfilter_policy['spam_subject_tag2'] : '',
				'message_size_limit'           => isset($spamfilter_policy['message_size_limit']) ? $spamfilter_policy['message_size_limit'] : 0,
				'banned_rulenames'             => isset($spamfilter_policy['banned_rulenames']) ? $spamfilter_policy['banned_rulenames'] : '',
				'policyd_quota_in	'          => isset($spamfilter_policy['policyd_quota_in	']) ? $spamfilter_policy['policyd_quota_in	'] : -1,
				'policyd_quota_in_period'      => isset($spamfilter_policy['policyd_quota_in_period']) ? $spamfilter_policy['policyd_quota_in_period'] : 24,
				'policyd_quota_out'            => isset($spamfilter_policy['policyd_quota_out']) ? $spamfilter_policy['policyd_quota_out'] : -1,
				'policyd_quota_out_period'     => isset($spamfilter_policy['policyd_quota_out_period']) ? $spamfilter_policy['policyd_quota_out_period'] : 24,
				'policyd_greylist'             => isset($spamfilter_policy['policyd_greylist']) ? $spamfilter_policy['policyd_greylist'] : 'N',
			);

			return $this->SoapClient->mail_policy_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in Mail > Spamfilter Policy
	 *
	 * @param int   $client_id
	 * @param int   $id
	 * @param array $params            policy_name,  virus_lover,  spam_lover,  banned_files_lover,
	 *                                 bad_header_lover,  bypass_virus_checks, bypass_spam_checks,
	 *                                 bypass_banned_checks,  bypass_header_checks,
	 *                                 spam_modifies_subj, virus_quarantine_to, spam_quarantine_to,
	 *                                 banned_quarantine_to, bad_header_quarantine_to,
	 *                                 clean_quarantine_to, other_quarantine_to, spam_tag_level,
	 *                                 spam_tag2_level,  spam_kill_level, spam_dsn_cutoff_level,
	 *                                 spam_quarantine_cutoff_level, addr_extension_virus,
	 *                                 addr_extension_spam,  addr_extension_banned,
	 *                                 addr_extension_bad_header,  warnvirusrecip,  warnbannedrecip,
	 *                                 warnbadhrecip,  newvirus_admin,  virus_admin, banned_admin,
	 *                                 bad_header_admin,  spam_admin,  spam_subject_tag,
	 *                                 spam_subject_tag2, message_size_limit, banned_rulenames,
	 *                                 policyd_quota_in, policyd_quota_in_period,
	 *                                 policyd_quota_out,  policyd_quota_out_period,
	 *                                 policyd_greylist
	 *
	 * @return bool|array TRUE or error
	 */
	public function policy_update($client_id, $id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'id'        => $id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/policy_update'))
			{
				//return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_policy_update($this->ID, $client_id, $id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Spamfilter Policy
	 *
	 * @param int $id
	 *
	 * @return bool|array TRUE or error
	 */
	public function policy_delete($id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['id' => $id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/policy_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->mail_policy_delete($this->ID, $id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Fetchemail
	 *
	 * @param int $mailget_id
	 *
	 * @return array mail_get.* or error
	 */
	public function fetchmail_get($mailget_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['mailget_id' => $mailget_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/fetchmail_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_fetchmail_get($this->ID, $mailget_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Fetchemail
	 *
	 * @param int   $client_id
	 * @param array $mail_get server_id, type, source_server, source_username, source_password,
	 *                        source_delete, source_read_all, destination, active
	 *
	 * @return int|array mail_get.mailget_id or error
	 */
	public function fetchmail_add($client_id = 0, $mail_get = array())
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'mail_get'  => $mail_get,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/fetchmail_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id'       => isset($mail_get['server_id']) ? $mail_get['server_id'] : 0,
				'type'            => isset($mail_get['type']) ? $mail_get['type'] : '',
				'source_server'   => isset($mail_get['source_server']) ? $mail_get['source_server'] : '',
				'source_username' => isset($mail_get['source_username']) ? $mail_get['source_username'] : '',
				'source_password' => isset($mail_get['source_password']) ? $mail_get['source_password'] : '',
				'source_delete'   => isset($mail_get['source_delete']) ? $mail_get['source_delete'] : 'y',
				'source_read_all' => isset($mail_get['source_read_all']) ? $mail_get['source_read_all'] : 'y',
				'destination'     => isset($mail_get['destination']) ? $mail_get['destination'] : '',
				'active'          => isset($mail_get['active']) ? $mail_get['active'] : 'y',
			);

			return $this->SoapClient->mail_fetchmail_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record in Mail > Fetchemail
	 *
	 * @param int   $client_id
	 * @param int   $mailget_id
	 * @param array $params   server_id, type, source_server, source_username, source_password,
	 *                        source_delete, source_read_all, destination, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function fetchmail_update($client_id, $mailget_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'  => $client_id,
				'mailget_id' => $mailget_id,
				'params'     => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/fetchmail_update'))
			{
				//return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_fetchmail_update($this->ID, $client_id, $mailget_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Fetchemail
	 *
	 * @param int $mailget_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function fetchmail_delete($mailget_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['mailget_id' => $mailget_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/fetchmail_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->mail_fetchmail_delete($this->ID, $mailget_id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Postfix Whitelist
	 *
	 * @param int $access_id
	 *
	 * @return array mail_access.* or error
	 */
	public function whitelist_get($access_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['access_id' => $access_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/whitelist_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_whitelist_get($this->ID, $access_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Postfix Whitelist
	 *
	 * @param int   $client_id
	 * @param array $mail_access server_id,  source,  type,  active
	 *
	 * @return int|array mail_access.access_id or error
	 */
	public function whitelist_add($client_id, $mail_access)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'   => $client_id,
				'mail_access' => $mail_access,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/whitelist_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($mail_access['server_id']) ? $mail_access['server_id'] : 0,
				'source'    => isset($mail_access['source']) ? $mail_access['source'] : '',
				'access'    => 'OK',
				'type'      => isset($mail_access['type']) ? $mail_access['type'] : 'recipient',
				'active'    => isset($mail_access['active']) ? $mail_access['active'] : 'y',
			);

			return $this->SoapClient->mail_whitelist_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Postfix Whitelist
	 *
	 * @param int   $client_id
	 * @param int   $access_id
	 * @param array $params server_id,  source,  type,  active
	 *
	 * @return bool|array TRUE or error
	 */
	public function whitelist_update($client_id, $access_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'access_id' => $access_id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/whitelist_update'))
			{
				//return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_whitelist_update($this->ID, $client_id, $access_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Postfix Whitelist
	 *
	 * @param int $access_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function whitelist_delete($access_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['access_id' => $access_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/whitelist_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->mail_whitelist_delete($this->ID, $access_id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Postfix Blacklist
	 *
	 * @param int $access_id
	 *
	 * @return array mail_access.* or error
	 */
	public function blacklist_get($access_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['access_id' => $access_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/blacklist_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_blacklist_get($this->ID, $access_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Postfix Blacklist
	 *
	 * @param int   $client_id
	 * @param array $mail_access server_id,  source,  type,  active
	 *
	 * @return int|array mail_access.access_id or error
	 */
	public function blacklist_add($client_id, $mail_access)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'   => $client_id,
				'mail_access' => $mail_access,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/blacklist_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($mail_access['server_id']) ? $mail_access['server_id'] : 0,
				'source'    => isset($mail_access['source']) ? $mail_access['source'] : '',
				'access'    => 'REJECT',
				'type'      => isset($mail_access['type']) ? $mail_access['type'] : 'recipient',
				'active'    => isset($mail_access['active']) ? $mail_access['active'] : 'y',
			);

			return $this->SoapClient->mail_blacklist_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Postfix Blacklist
	 *
	 * @param int   $client_id
	 * @param int   $access_id
	 * @param array $params server_id,  source,  type,  active
	 *
	 * @return bool|array TRUE or error
	 */
	public function blacklist_update($client_id, $access_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'access_id' => $access_id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/blacklist_update'))
			{
				//return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_blacklist_update($this->ID, $client_id, $access_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Postfix Blacklist
	 *
	 * @param int $access_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function blacklist_delete($access_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['access_id' => $access_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/blacklist_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->mail_blacklist_delete($this->ID, $access_id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Content Filter
	 *
	 * @param int $content_filter_id
	 *
	 * @return array mail_content_filter.* or error
	 */
	public function filter_get($content_filter_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['content_filter_id' => $content_filter_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/filter_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_filter_get($this->ID, $content_filter_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Mail > Content Filter
	 *
	 * @param int   $client_id
	 * @param array $mail_content_filter server_id,  type,  pattern,  data,  action,  active
	 *
	 * @return int|array mail_content_filter.content_filter_id or error
	 */
	public function filter_add($client_id, $mail_content_filter)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'           => $client_id,
				'mail_content_filter' => $mail_content_filter,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/filter_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'server_id' => isset($mail_content_filter['server_id']) ? $mail_content_filter['server_id'] : 0,
				'type'      => isset($mail_content_filter['type']) ? $mail_content_filter['type'] : '',
				'pattern'   => isset($mail_content_filter['pattern']) ? $mail_content_filter['pattern'] : '',
				'data'      => isset($mail_content_filter['data']) ? $mail_content_filter['data'] : '',
				'action'    => isset($mail_content_filter['action']) ? $mail_content_filter['action'] : '',
				'active'    => isset($mail_content_filter['active']) ? $mail_content_filter['active'] : 'n',
			);

			return $this->SoapClient->mail_filter_add($this->ID, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Update one record on Mail > Content Filter
	 *
	 * @param int   $client_id
	 * @param int   $content_filter_id
	 * @param array $params server_id,  type,  pattern,  data,  action,  active
	 *
	 * @return bool|array TRUE or error
	 */
	public function filter_update($client_id, $content_filter_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'         => $client_id,
				'content_filter_id' => $content_filter_id,
				'params'            => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/filter_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_filter_update($this->ID, $client_id, $content_filter_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Mail > Content Filter
	 *
	 * @param int $content_filter_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function filter_delete($content_filter_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['content_filter_id' => $content_filter_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/filter_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->mail_filter_delete($this->ID, $content_filter_id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Mail > Domain
	 *
	 * @param string $domain
	 *
	 * @return array mail_domain.* or error
	 */
	public function domain_get_by_domain($domain)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['domain' => $domain]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/domain_get_by_domain'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_domain_get_by_domain($this->ID, $domain));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Set status in Mail > Domain.Active
	 *
	 * @param int    $domain_id
	 * @param string $status active or inactive
	 *
	 * @return bool|mixed TRUE or error
	 */
	public function domain_set_status($domain_id, $status)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['domain_id' => $domain_id, 'status' => $status]);
			if ( ! $this->CI->form_validation->run('ispconfig/mail/domain_set_status'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->mail_domain_set_status($this->ID, $domain_id, $status);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Email > Mailbox > Backup
	 *
	 * Get existing backup info about mailbox
	 *
	 * @param int $mailuser_id
	 *
	 * @return array
	 */
	public function user_backup_list($mailuser_id)
	{
		if (is_array($validation = $this->validate_primary_key('domain_id', $mailuser_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_user_backup_list($this->ID, $mailuser_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	public function user_backup($mailuser_id)
	{
		if (is_array($validation = $this->validate_primary_key('domain_id', $mailuser_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mail_user_backup($this->ID, $mailuser_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get Mail Traffic Quota usage
	 *
	 * @param int $client_id
	 *
	 * @return array
	 */
	public function mailquota_get_by_user($client_id)
	{
		if (is_array($validation = $this->validate_primary_key('client_id', $client_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->mailquota_get_by_user($this->ID, $client_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

}
