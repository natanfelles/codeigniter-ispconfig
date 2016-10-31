<?php
/**
 * codeigniter-ispconfig-new
 *
 * @package  codeigniter-ispconfig-new
 * @author   Natan Felles <natanfelles@gmail.com>
 */
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class Ispconfig_client
 */
class Ispconfig_client extends Ispconfig {


	/**
	 * Ispconfig_client constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Get one record from Client
	 *
	 * @param int $client_id
	 *
	 * @return array client.* or error
	 */
	public function get($client_id)
	{
		if (is_array($validation = $this->validate_primary_key('client_id', $client_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->client_get($this->ID, $client_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get the client_id of one sys_user
	 *
	 * @param int $userid
	 *
	 * @return int|array sys_user.client_id or error
	 */
	public function get_id($userid)
	{
		if (is_array($validation = $this->validate_primary_key('userid', $userid)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->client_get_id($this->ID, $userid);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * @param int $client_id
	 *
	 * @return array contact info or error
	 */
	public function get_emailcontact($client_id)
	{
		if (is_array($validation = $this->validate_primary_key('client_id', $client_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->client_get_emailcontact($this->ID, $client_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get the groupid of one sys_group
	 *
	 * @param int $client_id
	 *
	 * @return int|array sys_group.groupid or error
	 */
	public function get_groupid($client_id)
	{
		if (is_array($validation = $this->validate_primary_key('client_id', $client_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->client_get_groupid($this->ID, $client_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Client
	 *
	 * @param array $params reseller_id, company_name, company_id, gender, contact_firstname,
	 *                      contact_name, customer_no, vat_id, street, zip, city, state, country,
	 *                      telephone, mobile, fax, email, internet, icq, notes, bank_account_owner,
	 *                      bank_account_number, bank_code, bank_name, bank_account_iban,
	 *                      bank_account_swift, paypal_email, default_mailserver, mail_servers,
	 *                      limit_maildomain, limit_mailbox, limit_mailalias, limit_mailaliasdomain,
	 *                      limit_mailforward, limit_mailcatchall, limit_mailrouting,
	 *                      limit_mailfilter, limit_fetchmail, limit_mailquota,
	 *                      limit_spamfilter_wblist, limit_spamfilter_user, limit_spamfilter_policy,
	 *                      default_webserver, web_servers, limit_web_ip, limit_web_domain,
	 *                      limit_web_quota, web_php_options, limit_cgi, limit_ssi, limit_perl,
	 *                      limit_ruby, limit_python, force_suexec, limit_hterror, limit_wildcard,
	 *                      limit_ssl, limit_ssl_letsencrypt, limit_web_subdomain,
	 *                      limit_web_aliasdomain, limit_ftp_user, limit_shell_user, ssh_chroot,
	 *                      limit_webdav_user, limit_backup, limit_directive_snippet, limit_aps,
	 *                      default_dnsserver, dns_servers, limit_dns_zone, default_slave_dnsserver,
	 *                      limit_dns_slave_zone, limit_dns_record, default_dbserver, db_servers,
	 *                      limit_database, limit_database_user, limit_database_quota, limit_cron,
	 *                      limit_cron_type, limit_cron_frequency, limit_traffic_quota,
	 *                      limit_client, limit_domainmodule, limit_mailmailinglist,
	 *                      limit_openvz_vm, limit_openvz_vm_template_id, parent_client_id,
	 *                      username, password, language, usertheme, template_master,
	 *                      template_additional, created_at, locked, canceled, can_use_api,
	 *                      tmp_data, id_rsa, ssh_rsa, customer_no_template, customer_no_start,
	 *                      customer_no_counter, added_date, added_by, default_xmppserver,
	 *                      xmpp_servers, limit_xmpp_domain, limit_xmpp_user, limit_xmpp_muc,
	 *                      limit_xmpp_anon, limit_xmpp_auth_options, limit_xmpp_vjud,
	 *                      limit_xmpp_proxy, limit_xmpp_status, limit_xmpp_pastebin,
	 *                      limit_xmpp_httparchive
	 *
	 * @return int|array client.client_id or error
	 */
	public function add($params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['params' => $params,]);
			$rules = array(
				[
					'field' => 'params[reseller_id]',
					'label' => 'params[reseller_id]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[company_name]',
					'label' => 'params[company_name]',
					'rules' => 'trim|max_length[64]',
				],
				[
					'field' => 'params[company_id]',
					'label' => 'params[company_id]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[gender]',
					'label' => 'params[gender]',
					'rules' => 'trim|in_list[m,f]',
				],
				[
					'field' => 'params[contact_firstname]',
					'label' => 'params[contact_firstname]',
					'rules' => 'trim|max_length[64]',
				],
				[
					'field' => 'params[contact_name]',
					'label' => 'params[contact_name]',
					'rules' => 'trim|required|max_length[64]',
				],
				[
					'field' => 'params[customer_no]',
					'label' => 'params[customer_no]',
					'rules' => 'trim|max_length[64]',
				],
				[
					'field' => 'params[vat_id]',
					'label' => 'params[vat_id]',
					'rules' => 'trim|max_length[64]',
				],
				[
					'field' => 'params[street]',
					'label' => 'params[street]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[zip]',
					'label' => 'params[zip]',
					'rules' => 'trim|max_length[32]',
				],
				[
					'field' => 'params[city]',
					'label' => 'params[city]',
					'rules' => 'trim|max_length[64]',
				],
				[
					'field' => 'params[state]',
					'label' => 'params[state]',
					'rules' => 'trim|max_length[32]',
				],
				[
					'field' => 'params[country]',
					'label' => 'params[country]',
					'rules' => 'trim|alpha|exact_length[2]',
				],
				[
					'field' => 'params[telephone]',
					'label' => 'params[telephone]',
					'rules' => 'trim|max_length[32]',
				],
				[
					'field' => 'params[mobile]',
					'label' => 'params[mobile]',
					'rules' => 'trim|max_length[32]',
				],
				[
					'field' => 'params[fax]',
					'label' => 'params[fax]',
					'rules' => 'trim|max_length[32]',
				],
				[
					'field' => 'params[email]',
					'label' => 'params[email]',
					'rules' => 'trim|required|valid_email',
				],
				[
					'field' => 'params[internet]',
					'label' => 'params[internet]',
					'rules' => 'trim|valid_url',
				],
				[
					'field' => 'params[icq]',
					'label' => 'params[icq]',
					'rules' => 'trim|max_length[16]',
				],
				[
					'field' => 'params[notes]',
					'label' => 'params[notes]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[bank_account_owner]',
					'label' => 'params[bank_account_owner]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[bank_account_number]',
					'label' => 'params[bank_account_number]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[bank_code]',
					'label' => 'params[bank_code]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[bank_name]',
					'label' => 'params[bank_name]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[bank_account_iban]',
					'label' => 'params[bank_account_iban]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[bank_account_swift]',
					'label' => 'params[bank_account_swift]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[paypal_email]',
					'label' => 'params[paypal_email]',
					'rules' => 'trim|valid_email',
				],
				[
					'field' => 'params[default_mailserver]',
					'label' => 'params[default_mailserver]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[mail_servers]',
					'label' => 'params[mail_servers]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[limit_maildomain]',
					'label' => 'params[limit_maildomain]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailbox]',
					'label' => 'params[limit_mailbox]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailalias]',
					'label' => 'params[limit_mailalias]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailaliasdomain]',
					'label' => 'params[limit_mailaliasdomain]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailforward]',
					'label' => 'params[limit_mailforward]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailcatchall]',
					'label' => 'params[limit_mailcatchall]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailrouting]',
					'label' => 'params[limit_mailrouting]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailfilter]',
					'label' => 'params[limit_mailfilter]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_fetchmail]',
					'label' => 'params[limit_fetchmail]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailquota]',
					'label' => 'params[limit_mailquota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_spamfilter_wblist]',
					'label' => 'params[limit_spamfilter_wblist]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_spamfilter_user]',
					'label' => 'params[limit_spamfilter_user]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_spamfilter_policy]',
					'label' => 'params[limit_spamfilter_policy]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[default_webserver]',
					'label' => 'params[default_webserver]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[web_servers]',
					'label' => 'params[web_servers]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[limit_web_ip]',
					'label' => 'params[limit_web_ip]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[limit_web_domain]',
					'label' => 'params[limit_web_domain]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_web_quota]',
					'label' => 'params[limit_web_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[web_php_options]',
					'label' => 'params[web_php_options]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[limit_cgi]',
					'label' => 'params[limit_cgi]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_ssi]',
					'label' => 'params[limit_ssi]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_perl]',
					'label' => 'params[limit_perl]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_ruby]',
					'label' => 'params[limit_ruby]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_python]',
					'label' => 'params[limit_python]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[force_suexec]',
					'label' => 'params[force_suexec]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_hterror]',
					'label' => 'params[limit_hterror]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_wildcard]',
					'label' => 'params[limit_wildcard]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_ssl]',
					'label' => 'params[limit_ssl]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_ssl_letsencrypt]',
					'label' => 'params[limit_ssl_letsencrypt]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_web_subdomain]',
					'label' => 'params[limit_web_subdomain]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_web_aliasdomain]',
					'label' => 'params[limit_web_aliasdomain]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_ftp_user]',
					'label' => 'params[limit_ftp_user]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_shell_user]',
					'label' => 'params[limit_shell_user]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[ssh_chroot]',
					'label' => 'params[ssh_chroot]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[limit_webdav_user]',
					'label' => 'params[limit_webdav_user]',
					'rules' => 'trim|max_length[11]',
				],
				[
					'field' => 'params[limit_backup]',
					'label' => 'params[limit_backup]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_directive_snippet]',
					'label' => 'params[limit_directive_snippet]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_aps]',
					'label' => 'params[limit_aps]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[default_dnsserver]',
					'label' => 'params[default_dnsserver]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[dns_servers]',
					'label' => 'params[dns_servers]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[limit_dns_zone]',
					'label' => 'params[limit_dns_zone]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[default_slave_dnsserver]',
					'label' => 'params[default_slave_dnsserver]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[limit_dns_slave_zone]',
					'label' => 'params[limit_dns_slave_zone]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_dns_record]',
					'label' => 'params[limit_dns_record]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[default_dbserver]',
					'label' => 'params[default_dbserver]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[db_servers]',
					'label' => 'params[db_servers]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[limit_database]',
					'label' => 'params[limit_database]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_database_user]',
					'label' => 'params[limit_database_user]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_database_quota]',
					'label' => 'params[limit_database_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_cron]',
					'label' => 'params[limit_cron]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_cron_type]',
					'label' => 'params[limit_cron_type]',
					'rules' => 'trim|in_list[url,chrooted,full]',
				],
				[
					'field' => 'params[limit_cron_frequency]',
					'label' => 'params[limit_cron_frequency]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[limit_traffic_quota]',
					'label' => 'params[limit_traffic_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_client]',
					'label' => 'params[limit_client]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_domainmodule]',
					'label' => 'params[limit_domainmodule]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailmailinglist]',
					'label' => 'params[limit_mailmailinglist]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_openvz_vm]',
					'label' => 'params[limit_openvz_vm]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_openvz_vm_template_id]',
					'label' => 'params[limit_openvz_vm_template_id]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[parent_client_id]',
					'label' => 'params[parent_client_id]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[username]',
					'label' => 'params[username]',
					'rules' => 'trim|required|max_length[64]',
				],
				[
					'field' => 'params[password]',
					'label' => 'params[password]',
					'rules' => 'trim|required|max_length[64]',
				],
				[
					'field' => 'params[language]',
					'label' => 'params[language]',
					'rules' => 'trim|alpha|exact_length[2]',
				],
				[
					'field' => 'params[usertheme]',
					'label' => 'params[usertheme]',
					'rules' => 'trim|max_length[32]',
				],
				[
					'field' => 'params[template_master]',
					'label' => 'params[template_master]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[template_additional]',
					'label' => 'params[template_additional]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[created_at]',
					'label' => 'params[created_at]',
					'rules' => 'trim|max_length[10]',
				],
				[
					'field' => 'params[locked]',
					'label' => 'params[locked]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[canceled]',
					'label' => 'params[canceled]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[can_use_api]',
					'label' => 'params[can_use_api]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[tmp_data]',
					'label' => 'params[tmp_data]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[id_rsa]',
					'label' => 'params[id_rsa]',
					'rules' => 'trim|max_length[2000]',
				],
				[
					'field' => 'params[ssh_rsa]',
					'label' => 'params[ssh_rsa]',
					'rules' => 'trim|max_length[600]',
				],
				[
					'field' => 'params[customer_no_template]',
					'label' => 'params[customer_no_template]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[customer_no_start]',
					'label' => 'params[customer_no_start]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[customer_no_counter]',
					'label' => 'params[customer_no_counter]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[added_date]',
					'label' => 'params[added_date]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[added_by]',
					'label' => 'params[added_by]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[default_xmppserver]',
					'label' => 'params[default_xmppserver]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[xmpp_servers]',
					'label' => 'params[xmpp_servers]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[limit_xmpp_domain]',
					'label' => 'params[limit_xmpp_domain]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_xmpp_user]',
					'label' => 'params[limit_xmpp_user]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_xmpp_muc]',
					'label' => 'params[limit_xmpp_muc]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_xmpp_anon]',
					'label' => 'params[limit_xmpp_anon]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_xmpp_auth_options]',
					'label' => 'params[limit_xmpp_auth_options]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[limit_xmpp_vjud]',
					'label' => 'params[limit_xmpp_vjud]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_xmpp_proxy]',
					'label' => 'params[limit_xmpp_proxy]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_xmpp_status]',
					'label' => 'params[limit_xmpp_status]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_xmpp_pastebin]',
					'label' => 'params[limit_xmpp_pastebin]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_xmpp_httparchive]',
					'label' => 'params[limit_xmpp_httparchive]',
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
			$reseller_id = isset($params['reseller_id']) ? $params['reseller_id'] : 0;
			$params = array(
				'company_name'                => isset($params['company_name']) ? $params['company_name'] : NULL,
				'company_id'                  => isset($params['company_id']) ? $params['company_id'] : NULL,
				'gender'                      => isset($params['gender']) ? $params['gender'] : '',
				'contact_firstname'           => isset($params['contact_firstname']) ? $params['contact_firstname'] : '',
				'contact_name'                => isset($params['contact_name']) ? $params['contact_name'] : NULL,
				'customer_no'                 => isset($params['customer_no']) ? $params['customer_no'] : NULL,
				'vat_id'                      => isset($params['vat_id']) ? $params['vat_id'] : NULL,
				'street'                      => isset($params['street']) ? $params['street'] : NULL,
				'zip'                         => isset($params['zip']) ? $params['zip'] : NULL,
				'city'                        => isset($params['city']) ? $params['city'] : NULL,
				'state'                       => isset($params['state']) ? $params['state'] : NULL,
				'country'                     => isset($params['country']) ? $params['country'] : NULL,
				'telephone'                   => isset($params['telephone']) ? $params['telephone'] : NULL,
				'mobile'                      => isset($params['mobile']) ? $params['mobile'] : NULL,
				'fax'                         => isset($params['fax']) ? $params['fax'] : NULL,
				'email'                       => isset($params['email']) ? $params['email'] : NULL,
				'internet'                    => isset($params['internet']) ? $params['internet'] : '',
				'icq'                         => isset($params['icq']) ? $params['icq'] : NULL,
				'notes'                       => isset($params['notes']) ? $params['notes'] : NULL,
				'bank_account_owner'          => isset($params['bank_account_owner']) ? $params['bank_account_owner'] : NULL,
				'bank_account_number'         => isset($params['bank_account_number']) ? $params['bank_account_number'] : NULL,
				'bank_code'                   => isset($params['bank_code']) ? $params['bank_code'] : NULL,
				'bank_name'                   => isset($params['bank_name']) ? $params['bank_name'] : NULL,
				'bank_account_iban'           => isset($params['bank_account_iban']) ? $params['bank_account_iban'] : NULL,
				'bank_account_swift'          => isset($params['bank_account_swift']) ? $params['bank_account_swift'] : NULL,
				'paypal_email'                => isset($params['paypal_email']) ? $params['paypal_email'] : NULL,
				'default_mailserver'          => isset($params['default_mailserver']) ? $params['default_mailserver'] : 1,
				'mail_servers'                => isset($params['mail_servers']) ? $params['mail_servers'] : NULL,
				'limit_maildomain'            => isset($params['limit_maildomain']) ? $params['limit_maildomain'] : -1,
				'limit_mailbox'               => isset($params['limit_mailbox']) ? $params['limit_mailbox'] : -1,
				'limit_mailalias'             => isset($params['limit_mailalias']) ? $params['limit_mailalias'] : -1,
				'limit_mailaliasdomain'       => isset($params['limit_mailaliasdomain']) ? $params['limit_mailaliasdomain'] : -1,
				'limit_mailforward'           => isset($params['limit_mailforward']) ? $params['limit_mailforward'] : -1,
				'limit_mailcatchall'          => isset($params['limit_mailcatchall']) ? $params['limit_mailcatchall'] : -1,
				'limit_mailrouting'           => isset($params['limit_mailrouting']) ? $params['limit_mailrouting'] : 0,
				'limit_mailfilter'            => isset($params['limit_mailfilter']) ? $params['limit_mailfilter'] : -1,
				'limit_fetchmail'             => isset($params['limit_fetchmail']) ? $params['limit_fetchmail'] : -1,
				'limit_mailquota'             => isset($params['limit_mailquota']) ? $params['limit_mailquota'] : -1,
				'limit_spamfilter_wblist'     => isset($params['limit_spamfilter_wblist']) ? $params['limit_spamfilter_wblist'] : 0,
				'limit_spamfilter_user'       => isset($params['limit_spamfilter_user']) ? $params['limit_spamfilter_user'] : 0,
				'limit_spamfilter_policy'     => isset($params['limit_spamfilter_policy']) ? $params['limit_spamfilter_policy'] : 0,
				'default_webserver'           => isset($params['default_webserver']) ? $params['default_webserver'] : 1,
				'web_servers'                 => isset($params['web_servers']) ? $params['web_servers'] : NULL,
				'limit_web_ip'                => isset($params['limit_web_ip']) ? $params['limit_web_ip'] : NULL,
				'limit_web_domain'            => isset($params['limit_web_domain']) ? $params['limit_web_domain'] : -1,
				'limit_web_quota'             => isset($params['limit_web_quota']) ? $params['limit_web_quota'] : -1,
				'web_php_options'             => isset($params['web_php_options']) ? $params['web_php_options'] : 'no,fast-cgi,cgi,mod,suphp,php-fpm,hhvm',
				'limit_cgi'                   => isset($params['limit_cgi']) ? $params['limit_cgi'] : 'n',
				'limit_ssi'                   => isset($params['limit_ssi']) ? $params['limit_ssi'] : 'n',
				'limit_perl'                  => isset($params['limit_perl']) ? $params['limit_perl'] : 'n',
				'limit_ruby'                  => isset($params['limit_ruby']) ? $params['limit_ruby'] : 'n',
				'limit_python'                => isset($params['limit_python']) ? $params['limit_python'] : 'n',
				'force_suexec'                => isset($params['force_suexec']) ? $params['force_suexec'] : 'y',
				'limit_hterror'               => isset($params['limit_hterror']) ? $params['limit_hterror'] : 'n',
				'limit_wildcard'              => isset($params['limit_wildcard']) ? $params['limit_wildcard'] : 'n',
				'limit_ssl'                   => isset($params['limit_ssl']) ? $params['limit_ssl'] : 'n',
				'limit_ssl_letsencrypt'       => isset($params['limit_ssl_letsencrypt']) ? $params['limit_ssl_letsencrypt'] : 'n',
				'limit_web_subdomain'         => isset($params['limit_web_subdomain']) ? $params['limit_web_subdomain'] : -1,
				'limit_web_aliasdomain'       => isset($params['limit_web_aliasdomain']) ? $params['limit_web_aliasdomain'] : -1,
				'limit_ftp_user'              => isset($params['limit_ftp_user']) ? $params['limit_ftp_user'] : -1,
				'limit_shell_user'            => isset($params['limit_shell_user']) ? $params['limit_shell_user'] : 0,
				'ssh_chroot'                  => isset($params['ssh_chroot']) ? $params['ssh_chroot'] : 'no,jailkit,ssh-chroot',
				'limit_webdav_user'           => isset($params['limit_webdav_user']) ? $params['limit_webdav_user'] : 0,
				'limit_backup'                => isset($params['limit_backup']) ? $params['limit_backup'] : 'y',
				'limit_directive_snippet'     => isset($params['limit_directive_snippet']) ? $params['limit_directive_snippet'] : 'n',
				'limit_aps'                   => isset($params['limit_aps']) ? $params['limit_aps'] : -1,
				'default_dnsserver'           => isset($params['default_dnsserver']) ? $params['default_dnsserver'] : 1,
				'dns_servers'                 => isset($params['db_servers']) ? $params['db_servers'] : NULL,
				'limit_dns_zone'              => isset($params['limit_dns_zone']) ? $params['limit_dns_zone'] : -1,
				'default_slave_dnsserver'     => isset($params['default_slave_dnsserver']) ? $params['default_slave_dnsserver'] : 1,
				'limit_dns_slave_zone'        => isset($params['limit_dns_slave_zone']) ? $params['limit_dns_slave_zone'] : -1,
				'limit_dns_record'            => isset($params['limit_dns_record']) ? $params['limit_dns_record'] : -1,
				'default_dbserver'            => isset($params['default_dbserver']) ? $params['default_dbserver'] : 1,
				'db_servers'                  => isset($params['db_servers']) ? $params['db_servers'] : NULL,
				'limit_database'              => isset($params['limit_database']) ? $params['limit_database'] : -1,
				'limit_database_user'         => isset($params['limit_database_user']) ? $params['limit_database_user'] : -1,
				'limit_database_quota'        => isset($params['limit_database_quota']) ? $params['limit_database_quota'] : -1,
				'limit_cron'                  => isset($params['limit_cron']) ? $params['limit_cron'] : 0,
				'limit_cron_type'             => isset($params['limit_cron_type']) ? $params['limit_cron_type'] : 'url',
				'limit_cron_frequency'        => isset($params['limit_cron_frequency']) ? $params['limit_cron_frequency'] : 5,
				'limit_traffic_quota'         => isset($params['limit_traffic_quota']) ? $params['limit_traffic_quota'] : -1,
				'limit_client'                => isset($params['limit_client']) ? $params['limit_client'] : 0,
				'limit_domainmodule'          => isset($params['limit_domainmodule']) ? $params['limit_domainmodule'] : 0,
				'limit_mailmailinglist'       => isset($params['limit_mailmailinglist']) ? $params['limit_mailmailinglist'] : -1,
				'limit_openvz_vm'             => isset($params['limit_openvz_vm']) ? $params['limit_openvz_vm'] : 0,
				'limit_openvz_vm_template_id' => isset($params['limit_openvz_vm_template_id']) ? $params['limit_openvz_vm_template_id'] : 0,
				'parent_client_id'            => isset($params['parent_client_id']) ? $params['parent_client_id'] : 0,
				'username'                    => isset($params['username']) ? $params['username'] : NULL,
				'password'                    => isset($params['password']) ? $params['password'] : NULL,
				'language'                    => isset($params['language']) ? $params['language'] : 'en',
				'usertheme'                   => isset($params['usertheme']) ? $params['usertheme'] : 'default',
				'template_master'             => isset($params['template_master']) ? $params['template_master'] : 0,
				'template_additional'         => isset($params['template_additional']) ? $params['template_additional'] : NULL,
				'created_at'                  => isset($params['created_at']) ? $params['created_at'] : NULL,
				'locked'                      => isset($params['locked']) ? $params['locked'] : 'n',
				'canceled'                    => isset($params['canceled']) ? $params['canceled'] : 'n',
				'can_use_api'                 => isset($params['can_use_api']) ? $params['can_use_api'] : 'n',
				'tmp_data'                    => isset($params['tmp_data']) ? $params['tmp_data'] : NULL,
				'id_rsa'                      => isset($params['id_rsa']) ? $params['id_rsa'] : '',
				'ssh_rsa'                     => isset($params['ssh_rsa']) ? $params['ssh_rsa'] : '',
				'customer_no_template'        => isset($params['customer_no_template']) ? $params['customer_no_template'] : 'R[CLIENTID]C[CUSTOMER_NO]',
				'customer_no_start'           => isset($params['customer_no_start']) ? $params['customer_no_start'] : 1,
				'customer_no_counter'         => isset($params['customer_no_counter']) ? $params['customer_no_counter'] : 0,
				'added_date'                  => isset($params['added_date']) ? $params['added_date'] : date('Y-m-d'),
				'added_by'                    => isset($params['added_by']) ? $params['added_by'] : $this->CI->config->item('ispconfig_username'),
				'default_xmppserver'          => isset($params['default_xmppserver']) ? $params['default_xmppserver'] : 1,
				'xmpp_servers'                => isset($params['xmpp_servers']) ? $params['xmpp_servers'] : NULL,
				'limit_xmpp_domain'           => isset($params['limit_xmpp_domain']) ? $params['limit_xmpp_domain'] : -1,
				'limit_xmpp_user'             => isset($params['limit_xmpp_user']) ? $params['limit_xmpp_user'] : -1,
				'limit_xmpp_muc'              => isset($params['limit_xmpp_muc']) ? $params['limit_xmpp_muc'] : 'n',
				'limit_xmpp_anon'             => isset($params['limit_xmpp_anon']) ? $params['limit_xmpp_anon'] : 'n',
				'limit_xmpp_auth_options'     => isset($params['limit_xmpp_auth_options']) ? $params['limit_xmpp_auth_options'] : 'plain,hashed,isp',
				'limit_xmpp_vjud'             => isset($params['limit_xmpp_vjud']) ? $params['limit_xmpp_vjud'] : 'n',
				'limit_xmpp_proxy'            => isset($params['limit_xmpp_proxy']) ? $params['limit_xmpp_proxy'] : 'n',
				'limit_xmpp_status'           => isset($params['limit_xmpp_status']) ? $params['limit_xmpp_status'] : 'n',
				'limit_xmpp_pastebin'         => isset($params['limit_xmpp_pastebin']) ? $params['limit_xmpp_pastebin'] : 'n',
				'limit_xmpp_httparchive'      => isset($params['limit_xmpp_httparchive']) ? $params['limit_xmpp_httparchive'] : 'n',
			);

			return $this->SoapClient->client_add($this->ID, $reseller_id, $params);
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
	 * Update one record in Client
	 *
	 * @param int   $client_id
	 * @param array $params reseller_id, company_name, company_id, gender, contact_firstname,
	 *                      contact_name, customer_no, vat_id, street, zip, city, state, country,
	 *                      telephone, mobile, fax, email, internet, icq, notes, bank_account_owner,
	 *                      bank_account_number, bank_code, bank_name, bank_account_iban,
	 *                      bank_account_swift, paypal_email, default_mailserver, mail_servers,
	 *                      limit_maildomain, limit_mailbox, limit_mailalias, limit_mailaliasdomain,
	 *                      limit_mailforward, limit_mailcatchall, limit_mailrouting,
	 *                      limit_mailfilter, limit_fetchmail, limit_mailquota,
	 *                      limit_spamfilter_wblist, limit_spamfilter_user, limit_spamfilter_policy,
	 *                      default_webserver, web_servers, limit_web_ip, limit_web_domain,
	 *                      limit_web_quota, web_php_options, limit_cgi, limit_ssi, limit_perl,
	 *                      limit_ruby, limit_python, force_suexec, limit_hterror, limit_wildcard,
	 *                      limit_ssl, limit_ssl_letsencrypt, limit_web_subdomain,
	 *                      limit_web_aliasdomain, limit_ftp_user, limit_shell_user, ssh_chroot,
	 *                      limit_webdav_user, limit_backup, limit_directive_snippet, limit_aps,
	 *                      default_dnsserver, dns_servers, limit_dns_zone, default_slave_dnsserver,
	 *                      limit_dns_slave_zone, limit_dns_record, default_dbserver, db_servers,
	 *                      limit_database, limit_database_user, limit_database_quota, limit_cron,
	 *                      limit_cron_type, limit_cron_frequency, limit_traffic_quota,
	 *                      limit_client, limit_domainmodule, limit_mailmailinglist,
	 *                      limit_openvz_vm, limit_openvz_vm_template_id, parent_client_id,
	 *                      username, password, language, usertheme, template_master,
	 *                      template_additional, created_at, locked, canceled, can_use_api,
	 *                      tmp_data, id_rsa, ssh_rsa, customer_no_template, customer_no_start,
	 *                      customer_no_counter, added_date, added_by, default_xmppserver,
	 *                      xmpp_servers, limit_xmpp_domain, limit_xmpp_user, limit_xmpp_muc,
	 *                      limit_xmpp_anon, limit_xmpp_auth_options, limit_xmpp_vjud,
	 *                      limit_xmpp_proxy, limit_xmpp_status, limit_xmpp_pastebin,
	 *                      limit_xmpp_httparchive
	 *
	 * @return int|array Number of affected rows or error
	 */
	public function update($client_id, $params)
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
					'field' => 'params[reseller_id]',
					'label' => 'params[reseller_id]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[company_name]',
					'label' => 'params[company_name]',
					'rules' => 'trim|max_length[64]',
				],
				[
					'field' => 'params[company_id]',
					'label' => 'params[company_id]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[gender]',
					'label' => 'params[gender]',
					'rules' => 'trim|in_list[m,f]',
				],
				[
					'field' => 'params[contact_firstname]',
					'label' => 'params[contact_firstname]',
					'rules' => 'trim|max_length[64]',
				],
				[
					'field' => 'params[contact_name]',
					'label' => 'params[contact_name]',
					'rules' => 'trim|max_length[64]',
				],
				[
					'field' => 'params[customer_no]',
					'label' => 'params[customer_no]',
					'rules' => 'trim|max_length[64]',
				],
				[
					'field' => 'params[vat_id]',
					'label' => 'params[vat_id]',
					'rules' => 'trim|max_length[64]',
				],
				[
					'field' => 'params[street]',
					'label' => 'params[street]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[zip]',
					'label' => 'params[zip]',
					'rules' => 'trim|max_length[32]',
				],
				[
					'field' => 'params[city]',
					'label' => 'params[city]',
					'rules' => 'trim|max_length[64]',
				],
				[
					'field' => 'params[state]',
					'label' => 'params[state]',
					'rules' => 'trim|max_length[32]',
				],
				[
					'field' => 'params[country]',
					'label' => 'params[country]',
					'rules' => 'trim|alpha|exact_length[2]',
				],
				[
					'field' => 'params[telephone]',
					'label' => 'params[telephone]',
					'rules' => 'trim|max_length[32]',
				],
				[
					'field' => 'params[mobile]',
					'label' => 'params[mobile]',
					'rules' => 'trim|max_length[32]',
				],
				[
					'field' => 'params[fax]',
					'label' => 'params[fax]',
					'rules' => 'trim|max_length[32]',
				],
				[
					'field' => 'params[email]',
					'label' => 'params[email]',
					'rules' => 'trim|valid_email',
				],
				[
					'field' => 'params[internet]',
					'label' => 'params[internet]',
					'rules' => 'trim|valid_url',
				],
				[
					'field' => 'params[icq]',
					'label' => 'params[icq]',
					'rules' => 'trim|max_length[16]',
				],
				[
					'field' => 'params[notes]',
					'label' => 'params[notes]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[bank_account_owner]',
					'label' => 'params[bank_account_owner]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[bank_account_number]',
					'label' => 'params[bank_account_number]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[bank_code]',
					'label' => 'params[bank_code]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[bank_name]',
					'label' => 'params[bank_name]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[bank_account_iban]',
					'label' => 'params[bank_account_iban]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[bank_account_swift]',
					'label' => 'params[bank_account_swift]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[paypal_email]',
					'label' => 'params[paypal_email]',
					'rules' => 'trim|valid_email',
				],
				[
					'field' => 'params[default_mailserver]',
					'label' => 'params[default_mailserver]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[mail_servers]',
					'label' => 'params[mail_servers]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[limit_maildomain]',
					'label' => 'params[limit_maildomain]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailbox]',
					'label' => 'params[limit_mailbox]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailalias]',
					'label' => 'params[limit_mailalias]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailaliasdomain]',
					'label' => 'params[limit_mailaliasdomain]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailforward]',
					'label' => 'params[limit_mailforward]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailcatchall]',
					'label' => 'params[limit_mailcatchall]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailrouting]',
					'label' => 'params[limit_mailrouting]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailfilter]',
					'label' => 'params[limit_mailfilter]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_fetchmail]',
					'label' => 'params[limit_fetchmail]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailquota]',
					'label' => 'params[limit_mailquota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_spamfilter_wblist]',
					'label' => 'params[limit_spamfilter_wblist]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_spamfilter_user]',
					'label' => 'params[limit_spamfilter_user]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_spamfilter_policy]',
					'label' => 'params[limit_spamfilter_policy]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[default_webserver]',
					'label' => 'params[default_webserver]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[web_servers]',
					'label' => 'params[web_servers]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[limit_web_ip]',
					'label' => 'params[limit_web_ip]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[limit_web_domain]',
					'label' => 'params[limit_web_domain]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_web_quota]',
					'label' => 'params[limit_web_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[web_php_options]',
					'label' => 'params[web_php_options]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[limit_cgi]',
					'label' => 'params[limit_cgi]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_ssi]',
					'label' => 'params[limit_ssi]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_perl]',
					'label' => 'params[limit_perl]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_ruby]',
					'label' => 'params[limit_ruby]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_python]',
					'label' => 'params[limit_python]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[force_suexec]',
					'label' => 'params[force_suexec]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_hterror]',
					'label' => 'params[limit_hterror]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_wildcard]',
					'label' => 'params[limit_wildcard]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_ssl]',
					'label' => 'params[limit_ssl]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_ssl_letsencrypt]',
					'label' => 'params[limit_ssl_letsencrypt]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_web_subdomain]',
					'label' => 'params[limit_web_subdomain]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_web_aliasdomain]',
					'label' => 'params[limit_web_aliasdomain]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_ftp_user]',
					'label' => 'params[limit_ftp_user]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_shell_user]',
					'label' => 'params[limit_shell_user]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[ssh_chroot]',
					'label' => 'params[ssh_chroot]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[limit_webdav_user]',
					'label' => 'params[limit_webdav_user]',
					'rules' => 'trim|max_length[11]',
				],
				[
					'field' => 'params[limit_backup]',
					'label' => 'params[limit_backup]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_directive_snippet]',
					'label' => 'params[limit_directive_snippet]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_aps]',
					'label' => 'params[limit_aps]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[default_dnsserver]',
					'label' => 'params[default_dnsserver]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[dns_servers]',
					'label' => 'params[dns_servers]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[limit_dns_zone]',
					'label' => 'params[limit_dns_zone]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[default_slave_dnsserver]',
					'label' => 'params[default_slave_dnsserver]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[limit_dns_slave_zone]',
					'label' => 'params[limit_dns_slave_zone]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_dns_record]',
					'label' => 'params[limit_dns_record]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[default_dbserver]',
					'label' => 'params[default_dbserver]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[db_servers]',
					'label' => 'params[db_servers]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[limit_database]',
					'label' => 'params[limit_database]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_database_user]',
					'label' => 'params[limit_database_user]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_database_quota]',
					'label' => 'params[limit_database_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_cron]',
					'label' => 'params[limit_cron]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_cron_type]',
					'label' => 'params[limit_cron_type]',
					'rules' => 'trim|in_list[url,chrooted,full]',
				],
				[
					'field' => 'params[limit_cron_frequency]',
					'label' => 'params[limit_cron_frequency]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[limit_traffic_quota]',
					'label' => 'params[limit_traffic_quota]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_client]',
					'label' => 'params[limit_client]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_domainmodule]',
					'label' => 'params[limit_domainmodule]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_mailmailinglist]',
					'label' => 'params[limit_mailmailinglist]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_openvz_vm]',
					'label' => 'params[limit_openvz_vm]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_openvz_vm_template_id]',
					'label' => 'params[limit_openvz_vm_template_id]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[parent_client_id]',
					'label' => 'params[parent_client_id]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[username]',
					'label' => 'params[username]',
					'rules' => 'trim|max_length[64]',
				],
				[
					'field' => 'params[password]',
					'label' => 'params[password]',
					'rules' => 'trim|max_length[64]',
				],
				[
					'field' => 'params[language]',
					'label' => 'params[language]',
					'rules' => 'trim|alpha|exact_length[2]',
				],
				[
					'field' => 'params[usertheme]',
					'label' => 'params[usertheme]',
					'rules' => 'trim|max_length[32]',
				],
				[
					'field' => 'params[template_master]',
					'label' => 'params[template_master]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[template_additional]',
					'label' => 'params[template_additional]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[created_at]',
					'label' => 'params[created_at]',
					'rules' => 'trim|max_length[10]',
				],
				[
					'field' => 'params[locked]',
					'label' => 'params[locked]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[canceled]',
					'label' => 'params[canceled]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[can_use_api]',
					'label' => 'params[can_use_api]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[tmp_data]',
					'label' => 'params[tmp_data]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[id_rsa]',
					'label' => 'params[id_rsa]',
					'rules' => 'trim|max_length[2000]',
				],
				[
					'field' => 'params[ssh_rsa]',
					'label' => 'params[ssh_rsa]',
					'rules' => 'trim|max_length[600]',
				],
				[
					'field' => 'params[customer_no_template]',
					'label' => 'params[customer_no_template]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[customer_no_start]',
					'label' => 'params[customer_no_start]',
					'rules' => 'trim|greater_than_equal_to[0]|max_length[11]',
				],
				[
					'field' => 'params[customer_no_counter]',
					'label' => 'params[customer_no_counter]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[added_date]',
					'label' => 'params[added_date]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[added_by]',
					'label' => 'params[added_by]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[default_xmppserver]',
					'label' => 'params[default_xmppserver]',
					'rules' => 'trim|greater_than[0]|max_length[11]',
				],
				[
					'field' => 'params[xmpp_servers]',
					'label' => 'params[xmpp_servers]',
					'rules' => 'trim',
				],
				[
					'field' => 'params[limit_xmpp_domain]',
					'label' => 'params[limit_xmpp_domain]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_xmpp_user]',
					'label' => 'params[limit_xmpp_user]',
					'rules' => 'trim|greater_than_equal_to[-1]|max_length[11]',
				],
				[
					'field' => 'params[limit_xmpp_muc]',
					'label' => 'params[limit_xmpp_muc]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_xmpp_anon]',
					'label' => 'params[limit_xmpp_anon]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_xmpp_auth_options]',
					'label' => 'params[limit_xmpp_auth_options]',
					'rules' => 'trim|max_length[255]',
				],
				[
					'field' => 'params[limit_xmpp_vjud]',
					'label' => 'params[limit_xmpp_vjud]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_xmpp_proxy]',
					'label' => 'params[limit_xmpp_proxy]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_xmpp_status]',
					'label' => 'params[limit_xmpp_status]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_xmpp_pastebin]',
					'label' => 'params[limit_xmpp_pastebin]',
					'rules' => 'trim|in_list[n,y]',
				],
				[
					'field' => 'params[limit_xmpp_httparchive]',
					'label' => 'params[limit_xmpp_httparchive]',
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
			$reseller_id = isset($client['reseller_id']) ? $client['reseller_id'] : NULL;

			return $this->SoapClient->client_update($this->ID, $client_id, $reseller_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from Clients > Limits.Active Addons
	 *
	 * @param int $client_id
	 *
	 * @return array client_template_assigned.* or error
	 */
	public function template_additional_get($client_id)
	{
		if (is_array($validation = $this->validate_primary_key('client_id', $client_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->client_template_additional_get($this->ID, $client_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Add one record on Clients > Limits.Active Addons
	 *
	 * @param int $client_id
	 * @param int $template_id
	 *
	 * @return int|array client_template_assigned.assigned_template_id or error
	 */
	public function template_additional_add($client_id, $template_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'   => $client_id,
				'template_id' => $template_id,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('template_id'),
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

			return $this->get_empty($this->SoapClient->client_template_additional_add($this->ID, $client_id, $template_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * @param int $client_id
	 * @param int $template_id
	 *
	 * @return mixed
	 */
	public function template_additional_delete($client_id, $template_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'   => $client_id,
				'template_id' => $template_id,
			]);
			$rules = array(
				$this->prepare_validate_pk('client_id'),
				$this->prepare_validate_pk('template_id'),
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

			// Todo: Debug: Is it ok?
			return $this->SoapClient->client_template_additional_delete($this->ID, $client_id, $template_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get all records from Clients > Limit-Templates
	 *
	 * @return array client_template.* or error
	 */
	public function templates_get_all()
	{
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->client_templates_get_all($this->ID));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete one record in Client
	 *
	 * @param int $client_id
	 *
	 * @return int|array affected rows or error
	 */
	public function delete($client_id)
	{
		if (is_array($validation = $this->validate_primary_key('client_id', $client_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->client_delete($this->ID, $client_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Delete everything linked to one Client
	 *
	 * @param int $client_id
	 *
	 * @return int|array affected rows or or error
	 */
	public function delete_everything($client_id)
	{
		if (is_array($validation = $this->validate_primary_key('client_id', $client_id)))
		{
			return $validation['error'];
		}
		try
		{
			$this->login();

			return $this->SoapClient->client_delete_everything($this->ID, $client_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get all records from Sites > Websites by sys_user
	 *
	 * @param int $sys_userid
	 * @param int $sys_groupid
	 *
	 * @return array web_domain.(domain, domain_id, document_root, active) or error
	 */
	public function get_sites_by_user($sys_userid, $sys_groupid)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'sys_userid'  => $sys_userid,
				'sys_groupid' => $sys_groupid,
			]);
			$rules = array(
				$this->prepare_validate_pk('sys_userid'),
				$this->prepare_validate_pk('sys_groupid'),
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

			return $this->get_empty($this->SoapClient->client_get_sites_by_user($this->ID, $sys_userid, $sys_groupid));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from System > CP Users
	 *
	 * @param string $username
	 *
	 * @return array sys_user.* or error
	 */
	public function get_by_username($username)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['username' => $username]);
			$rules = array(
				[
					'field' => 'username',
					'label' => 'username',
					'rules' => 'trim|required|max_length[64]',
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

			return $this->get_empty($this->SoapClient->client_get_by_username($this->ID, $username));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get one record from client
	 *
	 * @param string $customer_no
	 *
	 * @return array client.* or error
	 */
	public function get_by_customer_no($customer_no)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['customer_no' => $customer_no]);
			$rules = array(
				[
					'field' => 'customer_no',
					'label' => 'customer_no',
					'rules' => 'trim|required|max_length[64]',
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

			return $this->get_empty($this->SoapClient->client_get_by_customer_no($this->ID, $customer_no));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get all records from client_ids
	 *
	 * @return array client.client_id or error
	 */
	public function get_all()
	{
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->client_get_all($this->ID));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Change one Client password
	 *
	 * @param int    $client_id
	 * @param string $new_password
	 *
	 * @return bool|array affected rows or error
	 */
	public function change_password($client_id, $new_password)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['client_id' => $client_id]);
			$rules = array($this->prepare_validate_pk('client_id'));
			$this->CI->form_validation->set_rules($rules);
			if ( ! $this->CI->form_validation->run())
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->client_change_password($this->ID, $client_id, $new_password);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Test login credentials
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return array error or [username, type, client_id, language, country]
	 */
	public function login_get($username, $password)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'username' => $username,
				'password' => $password,
			]);
			$rules = array(
				[
					'field' => 'username',
					'label' => 'username',
					'rules' => 'trim|required|max_length[64]',
				],
				[
					'field' => 'password',
					'label' => 'password',
					'rules' => 'trim|required|max_length[64]',
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

			// Todo: Debug: Always returning country as "de"
			return $this->SoapClient->client_login_get($this->ID, $username, $password);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

}
