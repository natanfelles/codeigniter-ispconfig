<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ispconfig Class
 * This class enables you to use the ISPConfig 3 Remote API
 *
 * @package       CodeIgniter
 * @subpackage    ISPConfig
 * @category      Remote API
 * @version       1.0.0
 * @author        Natan Felles
 * @link          http://github.com/natanfelles/codeigniter/ispconfig
 */
class Ispconfig {

	/**
	 * Reference to CodeIgniter instance
	 *
	 * @var object
	 */
	protected $CI;

	/**
	 * SoapClient Object
	 *
	 * @var object
	 */
	protected $client;

	/**
	 * Session ID from SoapClient Login
	 *
	 * @var string
	 */
	protected $session_id = '';

	/**
	 * Constructor - Set the Super Object CI and load the Config File
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->config->load('ispconfig');
	}

	/**
	 * Get one record from Billing > Invoices > Invoice
	 *
	 * @param int $invoice_id
	 * @return array invoice.* or error
	 */
	public function billing_invoice_get($invoice_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->billing_invoice_get($this->session_id, $invoice_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Start one Remote Session
	 */
	protected function login()
	{
		$this->client = new SoapClient(NULL, array(
				'location' => $this->CI->config->item('soap_location'),
				'uri' => $this->CI->config->item('soap_uri'),
				'trace' => 1,
				'exceptions' => 1
		));
		if ($this->session_id = $this->client->login($this->CI->config->item('username'), $this->CI->config->item('password')))
		{
			log_message('info', 'ISPConfig: Logged successfull. Session ID: ' . $this->session_id);
		}
	}

	/**
	 * Get no results response
	 *
	 * @param null $response The Client Object Function
	 * @return mixed $response
	 */
	protected function get_empty($response = NULL)
	{
		if ($response == '' or $response == array())
		{
			$response = 'no_results';
		}
		return $response;
	}

	/**
	 * Get errors
	 *
	 * @param null $error The SOAP Message
	 * @return mixed $reponse
	 */
	protected function get_error($error = NULL)
	{
		log_message('error', 'ISPConfig: ' . $error);
		$response['error'] = $error;
		return $response;
	}

	/**
	 * Get Billing > Invoices by Client
	 *
	 * @param int $client_id
	 * @param int $quantity
	 * @return array invoice.* or error
	 */
	public function billing_invoice_get_by_client($client_id = 0, $quantity = 1)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->billing_invoice_get_by_client($this->session_id, $client_id, $quantity));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Add one record on Billing > Invoices > Invoice
	 *
	 * @param int   $client_id
	 * @param array $invoice invoice_company_id, company_name, contact_name, street, zip, city, state, country,
	 *                       email, vat_id, payment_gateway, notes
	 * @return int|array invoice.invoice_id or error
	 */
	public function billing_invoice_add($client_id = 0, $invoice = array())
	{
		try
		{
			$this->login();
			$client = $this->client->client_get($this->session_id, $client_id);
			$client_settings = $this->client->billing_client_settings_get($this->session_id, $client_id);
			$params = array(
					'invoice_company_id' => isset($invoice['invoice_company_id']) ? $invoice['invoice_company_id'] : $client_settings['invoice_company_id'],
					'client_id' => $client_id,
					'company_name' => isset($invoice['company_name']) ? $invoice['company_name'] : $client['company_name'],
					'contact_name' => isset($invoice['contact_name']) ? $invoice['contact_name'] : $client['contact_name'],
					'street' => isset($invoice['street']) ? $invoice['street'] : $client['street'],
					'zip' => isset($invoice['zip']) ? $invoice['zip'] : $client['zip'],
					'city' => isset($invoice['city']) ? $invoice['city'] : $client['city'],
					'state' => isset($invoice['state']) ? $invoice['state'] : $client['state'],
					'country' => isset($invoice['country']) ? $invoice['country'] : $client['country'],
					'email' => isset($invoice['email']) ? $invoice['email'] : $client['email'],
					'vat_id' => isset($invoice['vat_id']) ? $invoice['vat_id'] : $client['vat_id'],
					'payment_gateway' => isset($invoice['payment_gateway']) ? $invoice['payment_gateway'] : $client_settings['payment_gateway'],
					'notes' => isset($invoice['notes']) ? $invoice['notes'] : ''
			);
			return $this->client->billing_invoice_add($this->session_id, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update one record in Billing > Invoices > Invoice
	 *
	 * @param int   $client_id
	 * @param int   $invoice_id
	 * @param array $invoice invoice_company_id, company_name, contact_name, street, zip, city, state, country,
	 *                       email, vat_id, payment_gateway, notes, invoice_type
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_update($client_id = 0, $invoice_id = 0, $invoice = array())
	{
		try
		{
			$this->login();
			$client = $this->client->client_get($this->session_id, $client_id);
			$client_settings = $this->client->billing_client_settings_get($this->session_id, $client_id);
			$params = array(
					'invoice_company_id' => isset($invoice['invoice_company_id']) ? $invoice['invoice_company_id'] : $client_settings['invoice_company_id'],
					'client_id' => $client_id,
					'company_name' => isset($invoice['company_name']) ? $invoice['company_name'] : $client['company_name'],
					'contact_name' => isset($invoice['contact_name']) ? $invoice['contact_name'] : $client['contact_name'],
					'street' => isset($invoice['street']) ? $invoice['street'] : $client['street'],
					'zip' => isset($invoice['zip']) ? $invoice['zip'] : $client['zip'],
					'city' => isset($invoice['city']) ? $invoice['city'] : $client['city'],
					'state' => isset($invoice['state']) ? $invoice['state'] : $client['state'],
					'country' => isset($invoice['country']) ? $invoice['country'] : $client['country'],
					'email' => isset($invoice['email']) ? $invoice['email'] : $client['email'],
					'vat_id' => isset($invoice['vat_id']) ? $invoice['vat_id'] : $client['vat_id'],
					'payment_gateway' => isset($invoice['payment_gateway']) ? $invoice['payment_gateway'] : $client_settings['payment_gateway'],
					'notes' => isset($invoice['notes']) ? $invoice['notes'] : '',
					'invoice_type' => isset($invoice['invoice_type']) ? $invoice['invoice_type'] : 'invoice',
			);
			$this->client->billing_invoice_update($this->session_id, $client_id, $invoice_id, $params);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Delete one record in Billing > Invoices > Invoice
	 *
	 * @param int $invoice_id
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_delete($invoice_id = 0)
	{
		try
		{
			$this->login();
			$this->client->billing_invoice_delete($this->session_id, $invoice_id);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get Billing > Invoices > Invoice > Items by Invoice
	 *
	 * @param int $invoice_id
	 * @param int $quantity
	 * @return array invoice_item.* or error
	 */
	public function billing_invoice_item_get_by_invoice($invoice_id = 0, $quantity = 1)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->billing_invoice_item_get_by_invoice($this->session_id, $invoice_id, $quantity));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get all records from Billing > Invoices > Invoice > Items
	 *
	 * @param int $invoice_item_id
	 * @return array invoice_item.* or error
	 */
	public function billing_invoice_item_get($invoice_item_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->billing_invoice_item_get($this->session_id, $invoice_item_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Add one record on Billing > Invoices > Invoice > Items
	 *
	 * @param int   $invoice_id
	 * @param array $invoice_item quantity, price, vat, description
	 * @return int|array invoice_item.invoice_item_id or error
	 */
	public function billing_invoice_item_add($invoice_id = 0, $invoice_item = array())
	{
		try
		{
			$this->login();
			$params = array(
					'quantity' => isset($invoice_item['quantity']) ? $invoice_item['quantity'] : 1,
					'price' => isset($invoice_item['price']) ? $invoice_item['price'] : 0,
					'vat' => isset($invoice_item['vat']) ? $invoice_item['vat'] : 0,
					'description' => isset($invoice_item['description']) ? $invoice_item['description'] : '',
			);
			return $this->client->billing_invoice_item_add($this->session_id, $invoice_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update one record in Billing > Invoices > Invoice > Items
	 * Todo: Debug. Not working!
	 *
	 * @param int   $invoice_item_id
	 * @param array $invoice_item quantity, price, vat, description
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_item_update($invoice_item_id = 0, $invoice_item = array())
	{
		try
		{
			$this->login();
			$record = $this->client->billing_invoice_item_get($this->session_id, $invoice_item_id);
			$params = array(
					'quantity' => isset($invoice_item['quantity']) ? $invoice_item['quantity'] : $record['quantity'],
					'price' => isset($invoice_item['price']) ? $invoice_item['price'] : $record['price'],
					'vat' => isset($invoice_item['vat']) ? $invoice_item['vat'] : $record['vat'],
					'description' => isset($invoice_item['description']) ? $invoice_item['description'] : $record['description'],
			);
			$this->client->billing_invoice_item_update($this->session_id, $invoice_item_id, $params);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Delete one record in Billing > Invoices > Invoice > Items
	 *
	 * @param int $invoice_item_id
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_item_delete($invoice_item_id = 0)
	{
		try
		{
			$this->login();
			$this->client->billing_invoice_item_delete($this->session_id, $invoice_item_id);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Finalize one Billing > Invoices > Invoice
	 *
	 * @param int $invoice_id
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_finalize($invoice_id = 0)
	{
		try
		{
			$this->login();
			return $this->client->billing_invoice_finalize($this->session_id, $invoice_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Send one Billing > Invoices > Invoice
	 *
	 * @param int $invoice_id
	 * @param int $email_template_id
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_send($invoice_id = 0, $email_template_id = 1)
	{
		try
		{
			$this->login();
			return $this->client->billing_invoice_send($this->session_id, $invoice_id, $email_template_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Create one Invoice PDF into the configured invoices_dir
	 *
	 * @param int $invoice_id
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_get_pdf($invoice_id = 0)
	{
		try
		{
			$this->login();
			$base64 = $this->client->billing_invoice_get_pdf($this->session_id, $invoice_id);
			$invoices_dir = $this->CI->config->item('invoices_dir');
			file_put_contents("{$invoices_dir}/invoice_{$invoice_id}.pdf", base64_decode($base64));
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Set status in Billing > Invoices > Invoice.Sent
	 *
	 * @param int    $invoice_id
	 * @param string $status y or n
	 * @return string|array invoice.status_sent or error
	 */
	public function billing_invoice_set_status_sent($invoice_id = 0, $status = 'n')
	{
		try
		{
			$this->login();
			$this->client->billing_invoice_set_status_sent($this->session_id, $invoice_id, $status);
			return $status;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Set status in Billing > Invoices > Invoice.Paid
	 *
	 * @param int    $invoice_id
	 * @param string $status y or n
	 * @return string|array invoice.status_paid or error
	 */
	public function billing_invoice_set_status_paid($invoice_id = 0, $status = 'n')
	{
		try
		{
			$this->login();
			$this->client->billing_invoice_set_status_paid($this->session_id, $invoice_id, $status);
			return $status;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get one record from Billing > Client List > Client > Settings
	 *
	 * @param int $client_id
	 * @return array invoice_client_settings.* or error
	 */
	public function billing_client_settings_get($client_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->billing_client_settings_get($this->session_id, $client_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Add one record on Billing > Client List > Client > Settings
	 *
	 * @param int   $client_id
	 * @param array $invoice_client_settings invoice_company_id, payment_email, payment_terms, payment_gateway,
	 *                                       no_invoice_sending, last_invoice_number, last_refund_number,
	 *                                       last_proforma_number, invoice_sepa_mandate_id
	 * @return int|array invoice_client_settings.client_id or error
	 */
	public function billing_client_settings_add($client_id = 0, $invoice_client_settings = array())
	{
		try
		{
			$this->login();
			$params = array(
					'invoice_company_id' => isset($invoice_client_settings['invoice_company_id']) ? $invoice_client_settings['invoice_company_id'] : 0,
					'payment_email' => isset($invoice_client_settings['payment_email']) ? $invoice_client_settings['payment_email'] : '',
					'payment_terms' => isset($invoice_client_settings['payment_terms']) ? $invoice_client_settings['payment_terms'] : 1,
					'payment_gateway' => isset($invoice_client_settings['payment_gateway']) ? $invoice_client_settings['payment_gateway'] : 'auto',
					'no_invoice_sending' => isset($invoice_client_settings['no_invoice_sending']) ? $invoice_client_settings['no_invoice_sending'] : 'n',
					'last_invoice_number' => isset($invoice_client_settings['last_invoice_number']) ? $invoice_client_settings['last_invoice_number'] : 0,
					'last_refund_number' => isset($invoice_client_settings['last_refund_number']) ? $invoice_client_settings['last_refund_number'] : 0,
					'last_proforma_number' => isset($invoice_client_settings['last_proforma_number']) ? $invoice_client_settings['last_proforma_number'] : 0,
					'invoice_sepa_mandate_id' => isset($invoice_client_settings['invoice_sepa_mandate_id']) ? $invoice_client_settings['invoice_sepa_mandate_id'] : 0,
			);
			return $this->client->billing_client_settings_add($this->session_id, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update one record in Billing > Client List > Client > Settings
	 *
	 * @param int   $client_id
	 * @param array $invoice_client_settings invoice_company_id, payment_email, payment_terms, payment_gateway,
	 *                                       no_invoice_sending, last_invoice_number, last_refund_number,
	 *                                       last_proforma_number, invoice_sepa_mandate_id
	 * @return bool|array TRUE or error
	 */
	public function billing_client_settings_update($client_id = 0, $invoice_client_settings = array())
	{
		try
		{
			$this->login();
			$record = $this->client->billing_client_settings_get($this->session_id, $client_id);
			$params = array(
					'invoice_company_id' => isset($invoice_client_settings['invoice_company_id']) ? $invoice_client_settings['invoice_company_id'] : $record['invoice_company_id'],
					'payment_email' => isset($invoice_client_settings['payment_email']) ? $invoice_client_settings['payment_email'] : $record['payment_email'],
					'payment_terms' => isset($invoice_client_settings['payment_terms']) ? $invoice_client_settings['payment_terms'] : $record['payment_terms'],
					'payment_gateway' => isset($invoice_client_settings['payment_gateway']) ? $invoice_client_settings['payment_gateway'] : $record['payment_gateway'],
					'no_invoice_sending' => isset($invoice_client_settings['no_invoice_sending']) ? $invoice_client_settings['no_invoice_sending'] : $record['no_invoice_sending'],
					'last_invoice_number' => isset($invoice_client_settings['last_invoice_number']) ? $invoice_client_settings['last_invoice_number'] : $record['last_invoice_number'],
					'last_refund_number' => isset($invoice_client_settings['last_refund_number']) ? $invoice_client_settings['last_refund_number'] : $record['last_refund_number'],
					'last_proforma_number' => isset($invoice_client_settings['last_proforma_number']) ? $invoice_client_settings['last_proforma_number'] : $record['last_proforma_number'],
					'invoice_sepa_mandate_id' => isset($invoice_client_settings['invoice_sepa_mandate_id']) ? $invoice_client_settings['invoice_sepa_mandate_id'] : $record['invoice_sepa_mandate_id'],
			);
			$this->client->billing_client_settings_update($this->session_id, $client_id, $params);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Delete one record in Billing > Client List > Client > Settings
	 *
	 * @param int $client_id
	 * @return bool|array TRUE or error
	 */
	public function billing_client_settings_delete($client_id = 0)
	{
		try
		{
			$this->login();
			$this->client->billing_client_settings_delete($this->session_id, $client_id);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get one record from Billing > Company
	 *
	 * @param int $invoice_company_id
	 * @return array invoice_company.* or error
	 */
	public function billing_invoice_company_get($invoice_company_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->billing_invoice_company_get($this->session_id, $invoice_company_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Add one record on Billing > Company
	 *
	 * @param int   $client_id
	 * @param array $invoice_company company_name, company_name_short, contact_name, street, zip, city, state,
	 *                               country, email, internet, telephone, fax, company_logo, ceo_name, vat_id,
	 *                               tax_id, company_register, bank_account_owner, bank_account_number,
	 *                               bank_code, bank_name, bank_account_iban, bank_account_swift, creditor_id,
	 *                               last_invoice_number, invoice_number_prefix, last_refund_number,
	 *                               refund_number_prefix last_proforma_number, proforma_number_prefix,
	 *                               invoice_pdf_template, reminder_pdf, reminder_fee, reminder_fee_step,
	 *                               reminder_steps, chargeback_fee, reminder_payment_terms,
	 *                               reminder_last_payment_terms, chargeback_payment_terms, sender_name,
	 *                               sender_email, bcc_email,
	 * @return int|array invoice_company.invoice_company_id or error
	 */
	public function billing_invoice_company_add($client_id = 0, $invoice_company = array())
	{
		try
		{
			$this->login();
			$params = array(
					'company_name' => isset($invoice_company['company_name']) ? $invoice_company['company_name'] : '',
					'company_name_short' => isset($invoice_company['company_name_short']) ? $invoice_company['company_name_short'] : '',
					'contact_name' => isset($invoice_company['contact_name']) ? $invoice_company['contact_name'] : '',
					'street' => isset($invoice_company['street']) ? $invoice_company['street'] : '',
					'zip' => isset($invoice_company['zip']) ? $invoice_company['zip'] : '',
					'city' => isset($invoice_company['city']) ? $invoice_company['city'] : '',
					'state' => isset($invoice_company['state']) ? $invoice_company['state'] : '',
					'country' => isset($invoice_company['country']) ? $invoice_company['country'] : '',
					'email' => isset($invoice_company['email']) ? $invoice_company['email'] : '',
					'internet' => isset($invoice_company['internet']) ? $invoice_company['internet'] : '',
					'telephone' => isset($invoice_company['telephone']) ? $invoice_company['telephone'] : '',
					'fax' => isset($invoice_company['fax']) ? $invoice_company['fax'] : '',
					'company_logo' => isset($invoice_company['company_logo']) ? $invoice_company['company_logo'] : '',
					'ceo_name' => isset($invoice_company['ceo_name']) ? $invoice_company['ceo_name'] : '',
					'vat_id' => isset($invoice_company['vat_id']) ? $invoice_company['vat_id'] : '',
					'tax_id' => isset($invoice_company['tax_id']) ? $invoice_company['tax_id'] : '',
					'company_register' => isset($invoice_company['company_register']) ? $invoice_company['company_register'] : '',
					'bank_account_owner' => isset($invoice_company['bank_account_owner']) ? $invoice_company['bank_account_owner'] : '',
					'bank_account_number' => isset($invoice_company['bank_account_number']) ? $invoice_company['bank_account_number'] : '',
					'bank_code' => isset($invoice_company['bank_code']) ? $invoice_company['bank_code'] : '',
					'bank_name' => isset($invoice_company['bank_name']) ? $invoice_company['bank_name'] : '',
					'bank_account_iban' => isset($invoice_company['bank_account_iban']) ? $invoice_company['bank_account_iban'] : '',
					'bank_account_swift' => isset($invoice_company['bank_account_swift']) ? $invoice_company['bank_account_swift'] : '',
					'creditor_id' => isset($invoice_company['creditor_id']) ? $invoice_company['creditor_id'] : '',
					'last_invoice_number' => isset($invoice_company['last_invoice_number']) ? $invoice_company['last_invoice_number'] : 0,
					'invoice_number_prefix' => isset($invoice_company['invoice_number_prefix']) ? $invoice_company['invoice_number_prefix'] : '',
					'last_refund_number' => isset($invoice_company['last_refund_number']) ? $invoice_company['last_refund_number'] : 0,
					'refund_number_prefix' => isset($invoice_company['refund_number_prefix']) ? $invoice_company['refund_number_prefix'] : '',
					'last_proforma_number' => isset($invoice_company['last_proforma_number']) ? $invoice_company['last_proforma_number'] : 0,
					'proforma_number_prefix' => isset($invoice_company['proforma_number_prefix']) ? $invoice_company['proforma_number_prefix'] : '',
					'invoice_pdf_template' => isset($invoice_company['invoice_pdf_template']) ? $invoice_company['invoice_pdf_template'] : 'default',
					'reminder_pdf' => isset($invoice_company['reminder_pdf']) ? $invoice_company['reminder_pdf'] : 'n',
					'reminder_fee' => isset($invoice_company['reminder_fee']) ? $invoice_company['reminder_fee'] : 0,
					'reminder_fee_step' => isset($invoice_company['reminder_fee_step']) ? $invoice_company['reminder_fee_step'] : 1,
					'reminder_steps' => isset($invoice_company['reminder_steps']) ? $invoice_company['reminder_steps'] : 3,
					'chargeback_fee' => isset($invoice_company['chargeback_fee']) ? $invoice_company['chargeback_fee'] : 0,
					'reminder_payment_terms' => isset($invoice_company['reminder_payment_terms']) ? $invoice_company['reminder_payment_terms'] : 0,
					'reminder_last_payment_terms' => isset($invoice_company['reminder_last_payment_terms']) ? $invoice_company['reminder_last_payment_terms'] : 0,
					'chargeback_payment_terms' => isset($invoice_company['chargeback_payment_terms']) ? $invoice_company['chargeback_payment_terms'] : 0,
					'sender_name' => isset($invoice_company['sender_name']) ? $invoice_company['sender_name'] : '',
					'sender_email' => isset($invoice_company['sender_email']) ? $invoice_company['sender_email'] : '',
					'bcc_email' => isset($invoice_company['bcc_email']) ? $invoice_company['bcc_email'] : '',
			);
			return $this->client->billing_invoice_company_add($this->session_id, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update one record in Billing > Company
	 *
	 * @param int   $client_id
	 * @param int   $invoice_company_id
	 * @param array $invoice_company company_name, company_name_short, contact_name, street, zip, city, state,
	 *                               country, email, internet, telephone, fax, company_logo, ceo_name, vat_id,
	 *                               tax_id, company_register, bank_account_owner, bank_account_number,
	 *                               bank_code, bank_name, bank_account_iban, bank_account_swift, creditor_id,
	 *                               last_invoice_number, invoice_number_prefix, last_refund_number,
	 *                               refund_number_prefix, last_proforma_number, proforma_number_prefix,
	 *                               invoice_pdf_template, reminder_pdf, reminder_fee, reminder_fee_step,
	 *                               reminder_steps,  chargeback_fee, reminder_payment_terms,
	 *                               reminder_last_payment_terms, chargeback_payment_terms, sender_name,
	 *                               sender_email, bcc_email
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_company_update($client_id = 0, $invoice_company_id = 0, $invoice_company = array())
	{
		try
		{
			$this->login();
			$record = $this->client->billing_invoice_company_get($this->session_id, $invoice_company_id);
			$params = array(
					'company_name' => isset($invoice_company['company_name']) ? $invoice_company['company_name'] : $record['company_name'],
					'company_name_short' => isset($invoice_company['company_name_short']) ? $invoice_company['company_name_short'] : $record['company_name_short'],
					'contact_name' => isset($invoice_company['contact_name']) ? $invoice_company['contact_name'] : $record['contact_name'],
					'street' => isset($invoice_company['street']) ? $invoice_company['street'] : $record['street'],
					'zip' => isset($invoice_company['zip']) ? $invoice_company['zip'] : $record['zip'],
					'city' => isset($invoice_company['city']) ? $invoice_company['city'] : $record['city'],
					'state' => isset($invoice_company['state']) ? $invoice_company['state'] : $record['state'],
					'country' => isset($invoice_company['country']) ? $invoice_company['country'] : $record['country'],
					'email' => isset($invoice_company['email']) ? $invoice_company['email'] : $record['email'],
					'internet' => isset($invoice_company['internet']) ? $invoice_company['internet'] : $record['internet'],
					'telephone' => isset($invoice_company['telephone']) ? $invoice_company['telephone'] : $record['telephone'],
					'fax' => isset($invoice_company['fax']) ? $invoice_company['fax'] : $record['fax'],
					'company_logo' => isset($invoice_company['company_logo']) ? $invoice_company['company_logo'] : $record['company_logo'],
					'ceo_name' => isset($invoice_company['ceo_name']) ? $invoice_company['ceo_name'] : $record['ceo_name'],
					'vat_id' => isset($invoice_company['vat_id']) ? $invoice_company['vat_id'] : $record['vat_id'],
					'tax_id' => isset($invoice_company['tax_id']) ? $invoice_company['tax_id'] : $record['tax_id'],
					'company_register' => isset($invoice_company['company_register']) ? $invoice_company['company_register'] : $record['company_register'],
					'bank_account_owner' => isset($invoice_company['bank_account_owner']) ? $invoice_company['bank_account_owner'] : $record['bank_account_owner'],
					'bank_account_number' => isset($invoice_company['bank_account_number']) ? $invoice_company['bank_account_number'] : $record['bank_account_number'],
					'bank_code' => isset($invoice_company['bank_code']) ? $invoice_company['bank_code'] : $record['bank_code'],
					'bank_name' => isset($invoice_company['bank_name']) ? $invoice_company['bank_name'] : $record['bank_name'],
					'bank_account_iban' => isset($invoice_company['bank_account_iban']) ? $invoice_company['bank_account_iban'] : $record['bank_account_iban'],
					'bank_account_swift' => isset($invoice_company['bank_account_swift']) ? $invoice_company['bank_account_swift'] : $record['bank_account_swift'],
					'creditor_id' => isset($invoice_company['creditor_id']) ? $invoice_company['creditor_id'] : $record['creditor_id'],
					'last_invoice_number' => isset($invoice_company['last_invoice_number']) ? $invoice_company['last_invoice_number'] : $record['last_invoice_number'],
					'invoice_number_prefix' => isset($invoice_company['invoice_number_prefix']) ? $invoice_company['invoice_number_prefix'] : $record['invoice_number_prefix'],
					'last_refund_number' => isset($invoice_company['last_refund_number']) ? $invoice_company['last_refund_number'] : $record['last_refund_number'],
					'refund_number_prefix' => isset($invoice_company['refund_number_prefix']) ? $invoice_company['refund_number_prefix'] : $record['refund_number_prefix'],
					'last_proforma_number' => isset($invoice_company['last_proforma_number']) ? $invoice_company['last_proforma_number'] : $record['last_proforma_number'],
					'proforma_number_prefix' => isset($invoice_company['proforma_number_prefix']) ? $invoice_company['proforma_number_prefix'] : $record['proforma_number_prefix'],
					'invoice_pdf_template' => isset($invoice_company['invoice_pdf_template']) ? $invoice_company['invoice_pdf_template'] : $record['invoice_pdf_template'],
					'reminder_pdf' => isset($invoice_company['reminder_pdf']) ? $invoice_company['reminder_pdf'] : $record['reminder_pdf'],
					'reminder_fee' => isset($invoice_company['reminder_fee']) ? $invoice_company['reminder_fee'] : $record['reminder_fee'],
					'reminder_fee_step' => isset($invoice_company['reminder_fee_step']) ? $invoice_company['reminder_fee_step'] : $record['reminder_fee_step'],
					'reminder_steps' => isset($invoice_company['reminder_steps']) ? $invoice_company['reminder_steps'] : $record['reminder_steps'],
					'chargeback_fee' => isset($invoice_company['chargeback_fee']) ? $invoice_company['chargeback_fee'] : $record['chargeback_fee'],
					'reminder_payment_terms' => isset($invoice_company['reminder_payment_terms']) ? $invoice_company['reminder_payment_terms'] : $record['reminder_payment_terms'],
					'reminder_last_payment_terms' => isset($invoice_company['reminder_last_payment_terms']) ? $invoice_company['reminder_last_payment_terms'] : $record['reminder_last_payment_terms'],
					'chargeback_payment_terms' => isset($invoice_company['chargeback_payment_terms']) ? $invoice_company['chargeback_payment_terms'] : $record['chargeback_payment_terms'],
					'sender_name' => isset($invoice_company['sender_name']) ? $invoice_company['sender_name'] : $record['sender_name'],
					'sender_email' => isset($invoice_company['sender_email']) ? $invoice_company['sender_email'] : $record['sender_email'],
					'bcc_email' => isset($invoice_company['bcc_email']) ? $invoice_company['bcc_email'] : $record['bcc_email'],
			);
			$this->client->billing_invoice_company_update($this->session_id, $client_id, $invoice_company_id, $params);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Delete one record in Billing > Company
	 *
	 * @param int $invoice_company_id
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_company_delete($invoice_company_id = 0)
	{
		try
		{
			$this->login();
			$this->client->billing_invoice_company_delete($this->session_id, $invoice_company_id);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get one record from Billing > Item Template
	 *
	 * @param int $invoice_item_template_id
	 * @return array invoice_item_template.* or error
	 */
	public function billing_invoice_item_template_get($invoice_item_template_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->billing_invoice_item_template_get($this->session_id, $invoice_item_template_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Add one record on Billing > Item Template
	 *
	 * @param int   $client_id
	 * @param array $invoice_item_template type, name, description, price, setup_fee, vat, unit, recur_months,
	 *                                     cancellation_period, client_template_id, offer_in_shop
	 *                                     is_standalone, is_addon, is_updowngradable, addon_of,
	 *                                     updowngradable_to
	 * @return int|array invoice_item_template.invoice_item_template_id or error
	 */
	public function billing_invoice_item_template_add($client_id = 0, $invoice_item_template = array())
	{
		try
		{
			$this->login();
			$params = array(
					'type' => isset($invoice_item_template['type']) ? $invoice_item_template['type'] : 'Item',
					'name' => isset($invoice_item_template['name']) ? $invoice_item_template['name'] : '',
					'description' => isset($invoice_item_template['description']) ? $invoice_item_template['description'] : '',
					'price' => isset($invoice_item_template['price']) ? $invoice_item_template['price'] : 0,
					'setup_fee' => isset($invoice_item_template['setup_fee']) ? $invoice_item_template['setup_fee'] : 0,
					'vat' => isset($invoice_item_template['vat']) ? $invoice_item_template['vat'] : 0,
					'unit' => isset($invoice_item_template['unit']) ? $invoice_item_template['unit'] : 'default',
					'recur_months' => isset($invoice_item_template['recur_months']) ? $invoice_item_template['recur_months'] : 0,
					'cancellation_period' => isset($invoice_item_template['cancellation_period']) ? $invoice_item_template['cancellation_period'] : 30,
					'client_template_id' => isset($invoice_item_template['client_template_id']) ? $invoice_item_template['client_template_id'] : 0,
					'offer_in_shop' => isset($invoice_item_template['offer_in_shop']) ? $invoice_item_template['offer_in_shop'] : 'n',
					'is_standalone' => isset($invoice_item_template['is_standalone']) ? $invoice_item_template['is_standalone'] : 'y',
					'is_addon' => isset($invoice_item_template['is_addon']) ? $invoice_item_template['is_addon'] : 'n',
					'is_updowngradable' => isset($invoice_item_template['is_updowngradable']) ? $invoice_item_template['is_updowngradable'] : 'n',
					'addon_of' => isset($invoice_item_template['addon_of']) ? $invoice_item_template['addon_of'] : '',
					'updowngradable_to' => isset($invoice_item_template['updowngradable_to']) ? $invoice_item_template['updowngradable_to'] : '',
			);
			return $this->client->billing_invoice_item_template_add($this->session_id, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update one record in Billing > Item Template
	 *
	 * @param int   $client_id
	 * @param int   $invoice_item_template_id
	 * @param array $invoice_item_template type, name, description, price, setup_fee, vat, unit, recur_months,
	 *                                     cancellation_period, client_template_id, offer_in_shop
	 *                                     is_standalone, is_addon, is_updowngradable, addon_of,
	 *                                     updowngradable_to
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_item_template_update($client_id = 0, $invoice_item_template_id = 0, $invoice_item_template = array())
	{
		try
		{
			$this->login();
			$record = $this->client->billing_invoice_item_template_get($this->session_id, $invoice_item_template_id);
			$params = array(
					'type' => isset($invoice_item_template['type']) ? $invoice_item_template['type'] : $record['type'],
					'name' => isset($invoice_item_template['name']) ? $invoice_item_template['name'] : $record['name'],
					'description' => isset($invoice_item_template['description']) ? $invoice_item_template['description'] : $record['description'],
					'price' => isset($invoice_item_template['price']) ? $invoice_item_template['price'] : $record['price'],
					'setup_fee' => isset($invoice_item_template['setup_fee']) ? $invoice_item_template['setup_fee'] : $record['setup_fee'],
					'vat' => isset($invoice_item_template['vat']) ? $invoice_item_template['vat'] : $record['vat'],
					'unit' => isset($invoice_item_template['unit']) ? $invoice_item_template['unit'] : $record['unit'],
					'recur_months' => isset($invoice_item_template['recur_months']) ? $invoice_item_template['recur_months'] : $record['recur_months'],
					'cancellation_period' => isset($invoice_item_template['cancellation_period']) ? $invoice_item_template['cancellation_period'] : $record['cancellation_period'],
					'client_template_id' => isset($invoice_item_template['client_template_id']) ? $invoice_item_template['client_template_id'] : $record['client_template_id'],
					'offer_in_shop' => isset($invoice_item_template['offer_in_shop']) ? $invoice_item_template['offer_in_shop'] : $record['offer_in_shop'],
					'is_standalone' => isset($invoice_item_template['is_standalone']) ? $invoice_item_template['is_standalone'] : $record['is_standalone'],
					'is_addon' => isset($invoice_item_template['is_addon']) ? $invoice_item_template['is_addon'] : $record['is_addon'],
					'is_updowngradable' => isset($invoice_item_template['is_updowngradable']) ? $invoice_item_template['is_updowngradable'] : $record['is_updowngradable'],
					'addon_of' => isset($invoice_item_template['addon_of']) ? $invoice_item_template['addon_of'] : $record['addon_of'],
					'updowngradable_to' => isset($invoice_item_template['updowngradable_to']) ? $invoice_item_template['updowngradable_to'] : $record['updowngradable_to'],
			);
			return $this->client->billing_invoice_item_template_update($this->session_id, $client_id, $invoice_item_template_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Delete one record in Billing > Item Template
	 *
	 * @param int $invoice_item_template_id
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_item_template_delete($invoice_item_template_id = 0)
	{
		try
		{
			$this->login();
			$this->client->billing_invoice_item_template_delete($this->session_id, $invoice_item_template_id);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get one record from Billing > Email Templates
	 *
	 * @param int $invoice_message_template_id
	 * @return array invoice_message_template.* or error
	 */
	public function billing_invoice_message_template_get($invoice_message_template_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->billing_invoice_message_template_get($this->session_id, $invoice_message_template_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Add one record on Billing > Email Templates
	 *
	 * @param int   $client_id
	 * @param array $invoice_message_template template_type, template_name, subject, message
	 * @return int|array invoice_message_template.invoice_message_template_id or error
	 */
	public function billing_invoice_message_template_add($client_id = 0, $invoice_message_template = array())
	{
		try
		{
			$this->login();
			$params = array(
					'template_type' => isset($invoice_message_template['template_type']) ? $invoice_message_template['template_type'] : 'other',
					'template_name' => isset($invoice_message_template['template_name']) ? $invoice_message_template['template_name'] : '',
					'subject' => isset($invoice_message_template['subject']) ? $invoice_message_template['subject'] : '',
					'message' => isset($invoice_message_template['message']) ? $invoice_message_template['message'] : '',
			);
			return $this->client->billing_invoice_message_template_add($this->session_id, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update one record in Billing > Email Templates
	 *
	 * @param int   $client_id
	 * @param int   $invoice_message_template_id
	 * @param array $invoice_message_template template_type, template_name, subject, message
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_message_template_update($client_id = 0, $invoice_message_template_id = 0, $invoice_message_template = array())
	{
		try
		{
			$this->login();
			$record = $this->client->billing_invoice_message_template_get($this->session_id, $invoice_message_template_id);
			$params = array(
					'template_type' => isset($invoice_message_template['template_type']) ? $invoice_message_template['template_type'] : $record['template_type'],
					'template_name' => isset($invoice_message_template['template_name']) ? $invoice_message_template['template_name'] : $record['template_name'],
					'subject' => isset($invoice_message_template['subject']) ? $invoice_message_template['subject'] : $record['subject'],
					'message' => isset($invoice_message_template['message']) ? $invoice_message_template['message'] : $record['message']
			);
			$this->client->billing_invoice_message_template_update($this->session_id, $client_id, $invoice_message_template_id, $params);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Delete one record in Billing > Email Templates
	 *
	 * @param int $invoice_message_template_id
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_message_template_delete($invoice_message_template_id = 0)
	{
		try
		{
			$this->login();
			$this->client->billing_invoice_message_template_delete($this->session_id, $invoice_message_template_id);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get one record from Billing > Payment Terms
	 *
	 * @param int $invoice_payment_term_id
	 * @return array invoice_payment_term.* or error
	 */
	public function billing_invoice_payment_term_get($invoice_payment_term_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->billing_invoice_payment_term_get($this->session_id, $invoice_payment_term_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Add one record on Billing > Payment Terms
	 *
	 * @param int   $client_id
	 * @param array $invoice_payment_term name, description, due_days, invoice_explanation,
	 *                                    invoice_message_template_id
	 * @return int|array invoice_payment_term.invoice_payment_term_id or error
	 */
	public function billing_invoice_payment_term_add($client_id = 0, $invoice_payment_term = array())
	{
		try
		{
			$this->login();
			$params = array(
					'name' => isset($invoice_payment_term['name']) ? $invoice_payment_term['name'] : '',
					'description' => isset($invoice_payment_term['description']) ? $invoice_payment_term['description'] : '',
					'due_days' => isset($invoice_payment_term['due_days']) ? $invoice_payment_term['due_days'] : 0,
					'invoice_explanation' => isset($invoice_payment_term['invoice_explanation']) ? $invoice_payment_term['invoice_explanation'] : '',
					'invoice_message_template_id' => isset($invoice_payment_term['invoice_message_template_id']) ? $invoice_payment_term['invoice_message_template_id'] : 0,
			);
			return $this->client->billing_invoice_payment_term_add($this->session_id, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update one record in Billing > Payment Terms
	 *
	 * @param int   $client_id
	 * @param int   $invoice_payment_term_id
	 * @param array $invoice_payment_term name, description, due_days, invoice_explanation,
	 *                                    invoice_message_template_id
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_payment_term_update($client_id = 0, $invoice_payment_term_id = 0, $invoice_payment_term = array())
	{
		try
		{
			$this->login();
			$record = $this->client->billing_invoice_payment_term_get($this->session_id, $invoice_payment_term_id);
			$params = array(
					'name' => isset($invoice_payment_term['name']) ? $invoice_payment_term['name'] : $record['name'],
					'description' => isset($invoice_payment_term['description']) ? $invoice_payment_term['description'] : $record['description'],
					'due_days' => isset($invoice_payment_term['due_days']) ? $invoice_payment_term['due_days'] : $record['due_days'],
					'invoice_explanation' => isset($invoice_payment_term['invoice_explanation']) ? $invoice_payment_term['invoice_explanation'] : $record['invoice_explanation'],
					'invoice_message_template_id' => isset($invoice_payment_term['invoice_message_template_id']) ? $invoice_payment_term['invoice_message_template_id'] : $record['invoice_message_template_id'],
			);
			$this->client->billing_invoice_payment_term_update($this->session_id, $client_id, $invoice_payment_term_id, $params);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Delete one record in Billing > Payment Terms
	 *
	 * @param int $invoice_payment_term_id
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_payment_term_delete($invoice_payment_term_id = 0)
	{
		try
		{
			$this->login();
			$this->client->billing_invoice_payment_term_delete($this->session_id, $invoice_payment_term_id);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get one record from Billing > Recurring Item
	 *
	 * @param int $invoice_recurring_item_id
	 * @return array invoice_recurring_item.* or error
	 */
	public function billing_invoice_recurring_item_get($invoice_recurring_item_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->billing_invoice_recurring_item_get($this->session_id, $invoice_recurring_item_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Add one record on Billing > Recurring Item
	 *
	 * @param int   $client_id
	 * @param array $invoice_recurring_item invoice_item_template_id, invoice_company_id,
	 *                                      parent_recurring_item_id, assigned_template_id, name, description,
	 *                                      quantity, price, setup_fee, vat, recur_months, next_payment_date,
	 *                                      start_date, end_date, type, advance_payment, cancellation_period,
	 *                                      send_reminder, active
	 * @return int|array invoice_recurring_item.invoice_recurring_item_id or error
	 */
	public function billing_invoice_recurring_item_add($client_id = 0, $invoice_recurring_item = array())
	{
		try
		{
			$this->login();
			$params = array(
					'invoice_item_template_id' => isset($invoice_recurring_item['invoice_item_template_id']) ? $invoice_recurring_item['invoice_item_template_id'] : 0,
					'invoice_company_id' => isset($invoice_recurring_item['invoice_company_id']) ? $invoice_recurring_item['invoice_company_id'] : 0,
					'parent_recurring_item_id' => isset($invoice_recurring_item['parent_recurring_item_id']) ? $invoice_recurring_item['parent_recurring_item_id'] : 0,
					'assigned_template_id' => isset($invoice_recurring_item['assigned_template_id']) ? $invoice_recurring_item['assigned_template_id'] : 0,
					'name' => isset($invoice_recurring_item['name']) ? $invoice_recurring_item['name'] : '',
					'description' => isset($invoice_recurring_item['description']) ? $invoice_recurring_item['description'] : '',
					'quantity' => isset($invoice_recurring_item['quantity']) ? $invoice_recurring_item['quantity'] : 1,
					'price' => isset($invoice_recurring_item['price']) ? $invoice_recurring_item['price'] : 0,
					'setup_fee' => isset($invoice_recurring_item['setup_fee']) ? $invoice_recurring_item['setup_fee'] : 0,
					'vat' => isset($invoice_recurring_item['vat']) ? $invoice_recurring_item['vat'] : 0,
					'recur_months' => isset($invoice_recurring_item['recur_months']) ? $invoice_recurring_item['recur_months'] : 0,
					'next_payment_date' => isset($invoice_recurring_item['next_payment_date']) ? $invoice_recurring_item['next_payment_date'] : date('Y-m-d'),
					'start_date' => isset($invoice_recurring_item['start_date']) ? $invoice_recurring_item['start_date'] : '',
					'end_date' => isset($invoice_recurring_item['end_date']) ? $invoice_recurring_item['end_date'] : '',
					'type' => isset($invoice_recurring_item['type']) ? $invoice_recurring_item['type'] : 'other',
					'advance_payment' => isset($invoice_recurring_item['advance_payment']) ? $invoice_recurring_item['advance_payment'] : 'y',
					'cancellation_period' => isset($invoice_recurring_item['cancellation_period']) ? $invoice_recurring_item['cancellation_period'] : 30,
					'send_reminder' => isset($invoice_recurring_item['send_reminder']) ? $invoice_recurring_item['send_reminder'] : '',
					'active' => isset($invoice_recurring_item['active']) ? $invoice_recurring_item['active'] : 'y',
			);
			return $this->client->billing_invoice_recurring_item_add($this->session_id, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update one record in Billing > Recurring Item
	 *
	 * @param int   $client_id
	 * @param int   $invoice_recurring_item_id
	 * @param array $invoice_recurring_item invoice_item_template_id, invoice_company_id,
	 *                                      parent_recurring_item_id, assigned_template_id, name, description,
	 *                                      quantity, price, setup_fee, vat, recur_months, next_payment_date,
	 *                                      start_date, end_date, type, advance_payment, cancellation_period,
	 *                                      send_reminder, active
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_recurring_item_update($client_id = 0, $invoice_recurring_item_id = 0, $invoice_recurring_item = array())
	{
		try
		{
			$this->login();
			$record = $this->client->billing_invoice_recurring_item_get($this->session_id, $invoice_recurring_item_id);
			$params = array(
					'invoice_item_template_id' => isset($invoice_recurring_item['invoice_item_template_id']) ? $invoice_recurring_item['invoice_item_template_id'] : $record['invoice_item_template_id'],
					'invoice_company_id' => isset($invoice_recurring_item['invoice_company_id']) ? $invoice_recurring_item['invoice_company_id'] : $record['invoice_company_id'],
					'parent_recurring_item_id' => isset($invoice_recurring_item['parent_recurring_item_id']) ? $invoice_recurring_item['parent_recurring_item_id'] : $record['parent_recurring_item_id'],
					'assigned_template_id' => isset($invoice_recurring_item['assigned_template_id']) ? $invoice_recurring_item['assigned_template_id'] : $record['assigned_template_id'],
					'name' => isset($invoice_recurring_item['name']) ? $invoice_recurring_item['name'] : $record['name'],
					'description' => isset($invoice_recurring_item['description']) ? $invoice_recurring_item['description'] : $record['description'],
					'quantity' => isset($invoice_recurring_item['quantity']) ? $invoice_recurring_item['quantity'] : $record['quantity'],
					'price' => isset($invoice_recurring_item['price']) ? $invoice_recurring_item['price'] : $record['price'],
					'setup_fee' => isset($invoice_recurring_item['setup_fee']) ? $invoice_recurring_item['setup_fee'] : $record['setup_fee'],
					'vat' => isset($invoice_recurring_item['vat']) ? $invoice_recurring_item['vat'] : $record['vat'],
					'recur_months' => isset($invoice_recurring_item['recur_months']) ? $invoice_recurring_item['recur_months'] : $record['recur_months'],
					'next_payment_date' => isset($invoice_recurring_item['next_payment_date']) ? $invoice_recurring_item['next_payment_date'] : $record['next_payment_date'],
					'start_date' => isset($invoice_recurring_item['start_date']) ? $invoice_recurring_item['start_date'] : $record['start_date'],
					'end_date' => isset($invoice_recurring_item['end_date']) ? $invoice_recurring_item['end_date'] : $record['end_date'],
					'type' => isset($invoice_recurring_item['type']) ? $invoice_recurring_item['type'] : $record['type'],
					'advance_payment' => isset($invoice_recurring_item['advance_payment']) ? $invoice_recurring_item['advance_payment'] : $record['advance_payment'],
					'cancellation_period' => isset($invoice_recurring_item['cancellation_period']) ? $invoice_recurring_item['cancellation_period'] : $record['cancellation_period'],
					'send_reminder' => isset($invoice_recurring_item['send_reminder']) ? $invoice_recurring_item['send_reminder'] : $record['send_reminder'],
					'active' => isset($invoice_recurring_item['active']) ? $invoice_recurring_item['active'] : $record['active'],
			);
			return $this->client->billing_invoice_recurring_item_update($this->session_id, $client_id, $invoice_recurring_item_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Delete one record in Billing > Recurring Item
	 *
	 * @param int $invoice_recurring_item_id
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_recurring_item_delete($invoice_recurring_item_id = 0)
	{
		try
		{
			$this->login();
			$this->client->billing_invoice_recurring_item_delete($this->session_id, $invoice_recurring_item_id);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get one record from Billing > Global Settings
	 *
	 * @param int $invoice_settings_id
	 * @return array invoice_settings.* or error
	 */
	public function billing_invoice_settings_get($invoice_settings_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->billing_invoice_settings_get($this->session_id, $invoice_settings_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Add one record on Billing > Global Settings
	 *
	 * @param int   $client_id
	 * @param array $invoice_settings date_format, invoice_dir, invoice_pay_link, currency, paypal_url,
	 *                                paypal_ipn_url, paypal_active, invoice_item_list_with_vat, revnum,
	 *                                recurring_invoices_cron_active, recurring_invoices_cron_test,
	 *                                recurring_invoices_cron_finalize_invoices,
	 *                                recurring_invoices_cron_send_invoices,
	 *                                recurring_invoices_cron_email_template_id,
	 *                                recurring_invoices_cron_proforma_invoice, sepa_core_collectiondate_ooff,
	 *                                sepa_core_collectiondate_frst, sepa_core_collectiondate_rcur,
	 *                                sepa_cor1_collectiondate_ooff, sepa_cor1_collectiondate_frst,
	 *                                sepa_cor1_collectiondate_rcur, sepa_b2b_collectiondate_ooff,
	 *                                sepa_b2b_collectiondate_frst, sepa_b2b_collectiondate_rcur,
	 *                                sepa_mandate_reminders, sepa_mandate_reminders_frequency,
	 *                                sepa_mandate_reminders_subject, sepa_mandate_reminders_msg
	 * @return int|array invoice_settings.invoice_settings_id or error
	 */
	public function billing_invoice_settings_add($client_id = 0, $invoice_settings = array())
	{
		try
		{
			$this->login();
			$params = array(
					'date_format' => isset($invoice_settings['date_format']) ? $invoice_settings['date_format'] : 'd.m.Y',
					'invoice_dir' => isset($invoice_settings['invoice_dir']) ? $invoice_settings['invoice_dir'] : '',
					'invoice_pay_link' => isset($invoice_settings['invoice_pay_link']) ? $invoice_settings['invoice_pay_link'] : '',
					'currency' => isset($invoice_settings['currency']) ? $invoice_settings['currency'] : 'USD',
					'paypal_url' => isset($invoice_settings['paypal_url']) ? $invoice_settings['paypal_url'] : 'https://www.paypal.com/cgi-bin/webscr',
					'paypal_ipn_url' => isset($invoice_settings['paypal_ipn_url']) ? $invoice_settings['paypal_ipn_url'] : '',
					'paypal_active' => isset($invoice_settings['paypal_active']) ? $invoice_settings['paypal_active'] : 'y',
					'invoice_item_list_with_vat' => isset($invoice_settings['invoice_item_list_with_vat']) ? $invoice_settings['invoice_item_list_with_vat'] : 'n',
					'revnum' => isset($invoice_settings['revnum']) ? $invoice_settings['revnum'] : 16,
					'recurring_invoices_cron_active' => isset($invoice_settings['recurring_invoices_cron_active']) ? $invoice_settings['recurring_invoices_cron_active'] : 'y',
					'recurring_invoices_cron_test' => isset($invoice_settings['recurring_invoices_cron_test']) ? $invoice_settings['recurring_invoices_cron_test'] : 'n',
					'recurring_invoices_cron_finalize_invoices' => isset($invoice_settings['recurring_invoices_cron_finalize_invoices']) ? $invoice_settings['recurring_invoices_cron_finalize_invoices'] : 'y',
					'recurring_invoices_cron_send_invoices' => isset($invoice_settings['recurring_invoices_cron_send_invoices']) ? $invoice_settings['recurring_invoices_cron_send_invoices'] : 'y',
					'recurring_invoices_cron_email_template_id' => isset($invoice_settings['recurring_invoices_cron_email_template_id']) ? $invoice_settings['recurring_invoices_cron_email_template_id'] : 1,
					'recurring_invoices_cron_proforma_invoice' => isset($invoice_settings['recurring_invoices_cron_proforma_invoice']) ? $invoice_settings['recurring_invoices_cron_proforma_invoice'] : 'y',
					'sepa_core_collectiondate_ooff' => isset($invoice_settings['sepa_core_collectiondate_ooff']) ? $invoice_settings['sepa_core_collectiondate_ooff'] : 6,
					'sepa_core_collectiondate_frst' => isset($invoice_settings['sepa_core_collectiondate_frst']) ? $invoice_settings['sepa_core_collectiondate_frst'] : 6,
					'sepa_core_collectiondate_rcur' => isset($invoice_settings['sepa_core_collectiondate_rcur']) ? $invoice_settings['sepa_core_collectiondate_rcur'] : 3,
					'sepa_cor1_collectiondate_ooff' => isset($invoice_settings['sepa_cor1_collectiondate_ooff']) ? $invoice_settings['sepa_cor1_collectiondate_ooff'] : 2,
					'sepa_cor1_collectiondate_frst' => isset($invoice_settings['sepa_cor1_collectiondate_frst']) ? $invoice_settings['sepa_cor1_collectiondate_frst'] : 2,
					'sepa_cor1_collectiondate_rcur' => isset($invoice_settings['sepa_cor1_collectiondate_rcur']) ? $invoice_settings['sepa_cor1_collectiondate_rcur'] : 2,
					'sepa_b2b_collectiondate_ooff' => isset($invoice_settings['sepa_b2b_collectiondate_ooff']) ? $invoice_settings['sepa_b2b_collectiondate_ooff'] : 6,
					'sepa_b2b_collectiondate_frst' => isset($invoice_settings['sepa_b2b_collectiondate_frst']) ? $invoice_settings['sepa_b2b_collectiondate_frst'] : 6,
					'sepa_b2b_collectiondate_rcur' => isset($invoice_settings['sepa_b2b_collectiondate_rcur']) ? $invoice_settings['sepa_b2b_collectiondate_rcur'] : 3,
					'sepa_mandate_reminders' => isset($invoice_settings['sepa_mandate_reminders']) ? $invoice_settings['sepa_mandate_reminders'] : 'n',
					'sepa_mandate_reminders_frequency' => isset($invoice_settings['sepa_mandate_reminders_frequency']) ? $invoice_settings['sepa_mandate_reminders_frequency'] : 7,
					'sepa_mandate_reminders_subject' => isset($invoice_settings['sepa_mandate_reminders_subject']) ? $invoice_settings['sepa_mandate_reminders_subject'] : '',
					'sepa_mandate_reminders_msg' => isset($invoice_settings['sepa_mandate_reminders_msg']) ? $invoice_settings['sepa_mandate_reminders_msg'] : '',
			);
			return $this->client->billing_invoice_settings_add($this->session_id, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update one record in Billing > Global Settings
	 *
	 * @param int   $client_id
	 * @param int   $invoice_settings_id
	 * @param array $invoice_settings date_format, invoice_dir, invoice_pay_link, currency, paypal_url,
	 *                                paypal_ipn_url, paypal_active, invoice_item_list_with_vat, revnum,
	 *                                recurring_invoices_cron_active, recurring_invoices_cron_test,
	 *                                recurring_invoices_cron_finalize_invoices,
	 *                                recurring_invoices_cron_send_invoices,
	 *                                recurring_invoices_cron_email_template_id,
	 *                                recurring_invoices_cron_proforma_invoice, sepa_core_collectiondate_ooff,
	 *                                sepa_core_collectiondate_frst, sepa_core_collectiondate_rcur,
	 *                                sepa_cor1_collectiondate_ooff, sepa_cor1_collectiondate_frst,
	 *                                sepa_cor1_collectiondate_rcur, sepa_b2b_collectiondate_ooff,
	 *                                sepa_b2b_collectiondate_frst, sepa_b2b_collectiondate_rcur,
	 *                                sepa_mandate_reminders, sepa_mandate_reminders_frequency,
	 *                                sepa_mandate_reminders_subject, sepa_mandate_reminders_msg
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_settings_update($client_id = 0, $invoice_settings_id = 0, $invoice_settings = array())
	{
		try
		{
			$this->login();
			$record = $this->client->billing_invoice_settings_get($this->session_id, $invoice_settings_id);
			$params = array(
					'date_format' => isset($invoice_settings['date_format']) ? $invoice_settings['date_format'] : $record['date_format'],
					'invoice_dir' => isset($invoice_settings['invoice_dir']) ? $invoice_settings['invoice_dir'] : $record['invoice_dir'],
					'invoice_pay_link' => isset($invoice_settings['invoice_pay_link']) ? $invoice_settings['invoice_pay_link'] : $record['invoice_pay_link'],
					'currency' => isset($invoice_settings['currency']) ? $invoice_settings['currency'] : $record['currency'],
					'paypal_url' => isset($invoice_settings['paypal_url']) ? $invoice_settings['paypal_url'] : $record['paypal_url'],
					'paypal_ipn_url' => isset($invoice_settings['paypal_ipn_url']) ? $invoice_settings['paypal_ipn_url'] : $record['paypal_ipn_url'],
					'paypal_active' => isset($invoice_settings['paypal_active']) ? $invoice_settings['paypal_active'] : $record['paypal_active'],
					'invoice_item_list_with_vat' => isset($invoice_settings['invoice_item_list_with_vat']) ? $invoice_settings['invoice_item_list_with_vat'] : $record['invoice_item_list_with_vat'],
					'revnum' => isset($invoice_settings['revnum']) ? $invoice_settings['revnum'] : $record['revnum'],
					'recurring_invoices_cron_active' => isset($invoice_settings['recurring_invoices_cron_active']) ? $invoice_settings['recurring_invoices_cron_active'] : $record['recurring_invoices_cron_active'],
					'recurring_invoices_cron_test' => isset($invoice_settings['recurring_invoices_cron_test']) ? $invoice_settings['recurring_invoices_cron_test'] : $record['recurring_invoices_cron_test'],
					'recurring_invoices_cron_finalize_invoices' => isset($invoice_settings['recurring_invoices_cron_finalize_invoices']) ? $invoice_settings['recurring_invoices_cron_finalize_invoices'] : $record['recurring_invoices_cron_finalize_invoices'],
					'recurring_invoices_cron_send_invoices' => isset($invoice_settings['recurring_invoices_cron_send_invoices']) ? $invoice_settings['recurring_invoices_cron_send_invoices'] : $record['recurring_invoices_cron_send_invoices'],
					'recurring_invoices_cron_email_template_id' => isset($invoice_settings['recurring_invoices_cron_email_template_id']) ? $invoice_settings['recurring_invoices_cron_email_template_id'] : $record['recurring_invoices_cron_email_template_id'],
					'recurring_invoices_cron_proforma_invoice' => isset($invoice_settings['recurring_invoices_cron_proforma_invoice']) ? $invoice_settings['recurring_invoices_cron_proforma_invoice'] : $record['recurring_invoices_cron_proforma_invoice'],
					'sepa_core_collectiondate_ooff' => isset($invoice_settings['sepa_core_collectiondate_ooff']) ? $invoice_settings['sepa_core_collectiondate_ooff'] : $record['sepa_core_collectiondate_ooff'],
					'sepa_core_collectiondate_frst' => isset($invoice_settings['sepa_core_collectiondate_frst']) ? $invoice_settings['sepa_core_collectiondate_frst'] : $record['sepa_core_collectiondate_frst'],
					'sepa_core_collectiondate_rcur' => isset($invoice_settings['sepa_core_collectiondate_rcur']) ? $invoice_settings['sepa_core_collectiondate_rcur'] : $record['sepa_core_collectiondate_rcur'],
					'sepa_cor1_collectiondate_ooff' => isset($invoice_settings['sepa_cor1_collectiondate_ooff']) ? $invoice_settings['sepa_cor1_collectiondate_ooff'] : $record['sepa_cor1_collectiondate_ooff'],
					'sepa_cor1_collectiondate_frst' => isset($invoice_settings['sepa_cor1_collectiondate_frst']) ? $invoice_settings['sepa_cor1_collectiondate_frst'] : $record['sepa_cor1_collectiondate_frst'],
					'sepa_cor1_collectiondate_rcur' => isset($invoice_settings['sepa_cor1_collectiondate_rcur']) ? $invoice_settings['sepa_cor1_collectiondate_rcur'] : $record['sepa_cor1_collectiondate_rcur'],
					'sepa_b2b_collectiondate_ooff' => isset($invoice_settings['sepa_b2b_collectiondate_ooff']) ? $invoice_settings['sepa_b2b_collectiondate_ooff'] : $record['sepa_b2b_collectiondate_ooff'],
					'sepa_b2b_collectiondate_frst' => isset($invoice_settings['sepa_b2b_collectiondate_frst']) ? $invoice_settings['sepa_b2b_collectiondate_frst'] : $record['sepa_b2b_collectiondate_frst'],
					'sepa_b2b_collectiondate_rcur' => isset($invoice_settings['sepa_b2b_collectiondate_rcur']) ? $invoice_settings['sepa_b2b_collectiondate_rcur'] : $record['sepa_b2b_collectiondate_rcur'],
					'sepa_mandate_reminders' => isset($invoice_settings['sepa_mandate_reminders']) ? $invoice_settings['sepa_mandate_reminders'] : $record['sepa_mandate_reminders'],
					'sepa_mandate_reminders_frequency' => isset($invoice_settings['sepa_mandate_reminders_frequency']) ? $invoice_settings['sepa_mandate_reminders_frequency'] : $record['sepa_mandate_reminders_frequency'],
					'sepa_mandate_reminders_subject' => isset($invoice_settings['sepa_mandate_reminders_subject']) ? $invoice_settings['sepa_mandate_reminders_subject'] : $record['sepa_mandate_reminders_subject'],
					'sepa_mandate_reminders_msg' => isset($invoice_settings['sepa_mandate_reminders_msg']) ? $invoice_settings['sepa_mandate_reminders_msg'] : $record['sepa_mandate_reminders_msg'],
			);
			$this->client->billing_invoice_settings_update($this->session_id, $client_id, $invoice_settings_id, $params);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Delete one record in Billing > Global Settings
	 *
	 * @param int $invoice_settings_id
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_settings_delete($invoice_settings_id = 0)
	{
		try
		{
			$this->login();
			$this->client->billing_invoice_settings_delete($this->session_id, $invoice_settings_id);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get one record from Billing > SEPA Mandates
	 *
	 * @param int $invoice_sepa_mandate_id
	 * @return array invoice_sepa_mandate.* or error
	 */
	public function billing_invoice_sepa_mandate_get($invoice_sepa_mandate_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->billing_invoice_sepa_mandate_get($this->session_id, $invoice_sepa_mandate_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Add one record on Billing > SEPA Mandates
	 *
	 * @param int   $client_id
	 * @param array $invoice_sepa_mandate invoice_company_id, mandate_reference, signature_date, type, signed,
	 *                                    active, sequence_type
	 * @return int|array invoice_settings.invoice_settings_id or error
	 */
	public function billing_invoice_sepa_mandate_add($client_id = 0, $invoice_sepa_mandate = array())
	{
		try
		{
			$this->login();
			$params = array(
					'invoice_company_id' => isset($invoice_sepa_mandate['invoice_company_id']) ? $invoice_sepa_mandate['invoice_company_id'] : 0,
					'mandate_reference' => isset($invoice_sepa_mandate['mandate_reference']) ? $invoice_sepa_mandate['mandate_reference'] : '',
					'signature_date' => isset($invoice_sepa_mandate['signature_date']) ? $invoice_sepa_mandate['signature_date'] : date('Y-m-d'),
					'type' => isset($invoice_sepa_mandate['type']) ? $invoice_sepa_mandate['type'] : 'sepa_core',
					'signed' => isset($invoice_sepa_mandate['signed']) ? $invoice_sepa_mandate['signed'] : 'n',
					'active' => isset($invoice_sepa_mandate['active']) ? $invoice_sepa_mandate['active'] : 'y',
					'sequence_type' => isset($invoice_sepa_mandate['sequence_type']) ? $invoice_sepa_mandate['sequence_type'] : 'RCUR',
			);
			return $this->client->billing_invoice_sepa_mandate_add($this->session_id, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update one record in Billing > SEPA Mandates
	 *
	 * @param int   $client_id
	 * @param int   $invoice_sepa_mandate_id
	 * @param array $invoice_sepa_mandate invoice_company_id, mandate_reference, signature_date, type, signed,
	 *                                    active, sequence_type
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_sepa_mandate_update($client_id = 0, $invoice_sepa_mandate_id = 0, $invoice_sepa_mandate = array())
	{
		try
		{
			$this->login();
			$record = $this->client->billing_invoice_sepa_mandate_get($this->session_id, $invoice_sepa_mandate_id);
			$params = array(
					'invoice_company_id' => isset($invoice_sepa_mandate['invoice_company_id']) ? $invoice_sepa_mandate['invoice_company_id'] : $record['invoice_company_id'],
					'mandate_reference' => isset($invoice_sepa_mandate['mandate_reference']) ? $invoice_sepa_mandate['mandate_reference'] : $record['mandate_reference'],
					'signature_date' => isset($invoice_sepa_mandate['signature_date']) ? $invoice_sepa_mandate['signature_date'] : $record['signature_date'],
					'type' => isset($invoice_sepa_mandate['type']) ? $invoice_sepa_mandate['type'] : $record['type'],
					'signed' => isset($invoice_sepa_mandate['signed']) ? $invoice_sepa_mandate['signed'] : $record['signed'],
					'active' => isset($invoice_sepa_mandate['active']) ? $invoice_sepa_mandate['active'] : $record['active'],
					'sequence_type' => isset($invoice_sepa_mandate['sequence_type']) ? $invoice_sepa_mandate['sequence_type'] : $record['sequence_type'],
			);
			$this->client->billing_invoice_sepa_mandate_update($this->session_id, $client_id, $invoice_sepa_mandate_id, $params);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Delete one record in Billing > SEPA Mandates
	 *
	 * @param int $invoice_sepa_mandate_id
	 * @return bool|array TRUE or error
	 */
	public function billing_invoice_sepa_mandate_delete($invoice_sepa_mandate_id = 0)
	{
		try
		{
			$this->login();
			$this->client->billing_invoice_sepa_mandate_delete($this->session_id, $invoice_sepa_mandate_id);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get one record from Client
	 *
	 * @param int $client_id
	 * @return array client.* or error
	 */
	public function client_get($client_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->client_get($this->session_id, $client_id));
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
	 * @return int|array sys_user.client_id or error
	 */
	public function client_get_id($userid = 0)
	{
		try
		{
			$this->login();
			return $this->client->client_get_id($this->session_id, $userid);
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
	 * @return int|array sys_group.groupid or error
	 */
	public function client_get_groupid($client_id = 0)
	{
		try
		{
			$this->login();
			return $this->client->client_get_groupid($this->session_id, $client_id);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Add one record on Client
	 *
	 * @param array $client company_name, company_id, gender, contact_name, customer_no, vat_id, street, zip,
	 *                      city, state, country, telephone, mobile, fax, email, internet, icq, notes,
	 *                      bank_account_owner, bank_account_number, bank_code, bank_name, bank_account_iban,
	 *                      bank_account_swift, paypal_email, default_mailserver, limit_maildomain,
	 *                      limit_mailbox, limit_mailalias, limit_mailaliasdomain, limit_mailforward,
	 *                      limit_mailcatchall, limit_mailrouting, limit_mailfilter, limit_fetchmail,
	 *                      limit_mailquota, limit_spamfilter_wblist, limit_spamfilter_user,
	 *                      limit_spamfilter_policy, default_webserver, limit_web_ip, limit_web_domain,
	 *                      limit_web_quota, web_php_options, limit_cgi, limit_ssi, limit_perl, limit_ruby,
	 *                      limit_python, force_suexec, limit_hterror, limit_wildcard, limit_ssl,
	 *                      limit_web_subdomain, limit_web_aliasdomain, limit_ftp_user, limit_shell_user,
	 *                      ssh_chroot, limit_webdav_user, limit_backup, limit_aps, default_dnsserver,
	 *                      limit_dns_zone, default_slave_dnsserver, limit_dns_slave_zone, limit_dns_record,
	 *                      default_dbserver, limit_database, limit_database_quota, limit_cron,
	 *                      limit_cron_type, limit_cron_frequency, limit_traffic_quota, limit_client,
	 *                      limit_domainmodule, limit_mailmailinglist, limit_openvz_vm,
	 *                      limit_openvz_vm_template_id, parent_client_id, username, password, language,
	 *                      usertheme, template_master, template_additional, locked, canceled, added_date,
	 *                      added_by
	 * @return int|array client.client_id or error
	 *  */
	public function client_add($client = array())
	{
		try
		{
			$this->login();
			$reseller_id = isset($client['reseller_id']) ? $client['reseller_id'] : 0;
			$params = array(
					'company_name' => isset($client['company_name']) ? $client['company_name'] : '',
					'company_id' => isset($client['company_id']) ? $client['company_id'] : '',
					'gender' => isset($client['gender']) ? $client['gender'] : '',
					'contact_name' => isset($client['contact_name']) ? $client['contact_name'] : '',
					'customer_no' => isset($client['customer_no']) ? $client['customer_no'] : '',
					'vat_id' => isset($client['vat_id']) ? $client['vat_id'] : '',
					'street' => isset($client['street']) ? $client['street'] : '',
					'zip' => isset($client['zip']) ? $client['zip'] : '',
					'city' => isset($client['city']) ? $client['city'] : '',
					'state' => isset($client['state']) ? $client['state'] : '',
					'country' => isset($client['country']) ? $client['country'] : 'US',
					'telephone' => isset($client['telephone']) ? $client['telephone'] : '',
					'mobile' => isset($client['mobile']) ? $client['mobile'] : '',
					'fax' => isset($client['fax']) ? $client['fax'] : '',
					'email' => isset($client['email']) ? $client['email'] : '',
					'internet' => isset($client['internet']) ? $client['internet'] : '',
					'icq' => isset($client['icq']) ? $client['icq'] : '',
					'notes' => isset($client['notes']) ? $client['notes'] : '',
					'bank_account_owner' => isset($client['bank_account_owner']) ? $client['bank_account_owner'] : '',
					'bank_account_number' => isset($client['bank_account_number']) ? $client['bank_account_number'] : '',
					'bank_code' => isset($client['bank_code']) ? $client['bank_code'] : '',
					'bank_name' => isset($client['bank_name']) ? $client['bank_name'] : '',
					'bank_account_iban' => isset($client['bank_account_iban']) ? $client['bank_account_iban'] : '',
					'bank_account_swift' => isset($client['bank_account_swift']) ? $client['bank_account_swift'] : '',
					'paypal_email' => isset($client['paypal_email']) ? $client['paypal_email'] : '',
					'default_mailserver' => isset($client['default_mailserver']) ? $client['default_mailserver'] : 1,
					'limit_maildomain' => isset($client['limit_maildomain']) ? $client['limit_maildomain'] : -1,
					'limit_mailbox' => isset($client['limit_mailbox']) ? $client['limit_mailbox'] : -1,
					'limit_mailalias' => isset($client['limit_mailalias']) ? $client['limit_mailalias'] : -1,
					'limit_mailaliasdomain' => isset($client['limit_mailaliasdomain']) ? $client['limit_mailaliasdomain'] : -1,
					'limit_mailforward' => isset($client['limit_mailforward']) ? $client['limit_mailforward'] : -1,
					'limit_mailcatchall' => isset($client['limit_mailcatchall']) ? $client['limit_mailcatchall'] : -1,
					'limit_mailrouting' => isset($client['limit_mailrouting']) ? $client['limit_mailrouting'] : 0,
					'limit_mailfilter' => isset($client['limit_mailfilter']) ? $client['limit_mailfilter'] : -1,
					'limit_fetchmail' => isset($client['limit_fetchmail']) ? $client['limit_fetchmail'] : -1,
					'limit_mailquota' => isset($client['limit_mailquota']) ? $client['limit_mailquota'] : -1,
					'limit_spamfilter_wblist' => isset($client['limit_spamfilter_wblist']) ? $client['limit_spamfilter_wblist'] : 0,
					'limit_spamfilter_user' => isset($client['limit_spamfilter_user']) ? $client['limit_spamfilter_user'] : 0,
					'limit_spamfilter_policy' => isset($client['limit_spamfilter_policy']) ? $client['limit_spamfilter_policy'] : 0,
					'default_webserver' => isset($client['default_webserver']) ? $client['default_webserver'] : 1,
					'limit_web_ip' => isset($client['limit_web_ip']) ? $client['limit_web_ip'] : '',
					'limit_web_domain' => isset($client['limit_web_domain']) ? $client['limit_web_domain'] : -1,
					'limit_web_quota' => isset($client['limit_web_quota']) ? $client['limit_web_quota'] : -1,
					'web_php_options' => isset($client['web_php_options']) ? $client['web_php_options'] : 'no,fast-cgi,cgi,mod,suphp,php-fpm',
					'limit_cgi' => isset($client['limit_cgi']) ? $client['limit_cgi'] : 'n',
					'limit_ssi' => isset($client['limit_ssi']) ? $client['limit_ssi'] : 'n',
					'limit_perl' => isset($client['limit_perl']) ? $client['limit_perl'] : 'n',
					'limit_ruby' => isset($client['limit_ruby']) ? $client['limit_ruby'] : 'n',
					'limit_python' => isset($client['limit_python']) ? $client['limit_python'] : 'n',
					'force_suexec' => isset($client['force_suexec']) ? $client['force_suexec'] : 'y',
					'limit_hterror' => isset($client['limit_hterror']) ? $client['limit_hterror'] : 'n',
					'limit_wildcard' => isset($client['limit_wildcard']) ? $client['limit_wildcard'] : 'n',
					'limit_ssl' => isset($client['limit_ssl']) ? $client['limit_ssl'] : 'n',
					'limit_web_subdomain' => isset($client['limit_web_subdomain']) ? $client['limit_web_subdomain'] : -1,
					'limit_web_aliasdomain' => isset($client['limit_web_aliasdomain']) ? $client['limit_web_aliasdomain'] : -1,
					'limit_ftp_user' => isset($client['limit_ftp_user']) ? $client['limit_ftp_user'] : -1,
					'limit_shell_user' => isset($client['limit_shell_user']) ? $client['limit_shell_user'] : 0,
					'ssh_chroot' => isset($client['ssh_chroot']) ? $client['ssh_chroot'] : 'no,jailkit,ssh-chroot',
					'limit_webdav_user' => isset($client['limit_webdav_user']) ? $client['limit_webdav_user'] : 0,
					'limit_backup' => isset($client['limit_backup']) ? $client['limit_backup'] : 'y',
					'limit_aps' => isset($client['limit_aps']) ? $client['limit_aps'] : 0,
					'default_dnsserver' => isset($client['default_dnsserver']) ? $client['default_dnsserver'] : 1,
					'limit_dns_zone' => isset($client['limit_dns_zone']) ? $client['limit_dns_zone'] : -1,
					'default_slave_dnsserver' => isset($client['default_slave_dnsserver']) ? $client['default_slave_dnsserver'] : 1,
					'limit_dns_slave_zone' => isset($client['limit_dns_slave_zone']) ? $client['limit_dns_slave_zone'] : -1,
					'limit_dns_record' => isset($client['limit_dns_record']) ? $client['limit_dns_record'] : -1,
					'default_dbserver' => isset($client['default_dbserver']) ? $client['default_dbserver'] : 1,
					'limit_database' => isset($client['limit_database']) ? $client['limit_database'] : -1,
					'limit_database_quota' => isset($client['limit_database_quota']) ? $client['limit_database_quota'] : -1,
					'limit_cron' => isset($client['limit_cron']) ? $client['limit_cron'] : 0,
					'limit_cron_type' => isset($client['limit_cron_type']) ? $client['limit_cron_type'] : 'url',
					'limit_cron_frequency' => isset($client['limit_cron_frequency']) ? $client['limit_cron_frequency'] : 5,
					'limit_traffic_quota' => isset($client['limit_traffic_quota']) ? $client['limit_traffic_quota'] : -1,
					'limit_client' => isset($client['limit_client']) ? $client['limit_client'] : 0,
					'limit_domainmodule' => isset($client['limit_domainmodule']) ? $client['limit_domainmodule'] : 0,
					'limit_mailmailinglist' => isset($client['limit_mailmailinglist']) ? $client['limit_mailmailinglist'] : -1,
					'limit_openvz_vm' => isset($client['limit_openvz_vm']) ? $client['limit_openvz_vm'] : 0,
					'limit_openvz_vm_template_id' => isset($client['limit_openvz_vm_template_id']) ? $client['limit_openvz_vm_template_id'] : 0,
					'parent_client_id' => isset($client['parent_client_id']) ? $client['parent_client_id'] : 0,
					'username' => isset($client['username']) ? $client['username'] : '',
					'password' => isset($client['password']) ? $client['password'] : '',
					'language' => isset($client['language']) ? $client['language'] : 'en',
					'usertheme' => isset($client['usertheme']) ? $client['usertheme'] : 'default',
					'template_master' => isset($client['template_master']) ? $client['template_master'] : 0,
					'template_additional' => isset($client['template_additional']) ? $client['template_additional'] : '',
					'locked' => isset($client['locked']) ? $client['locked'] : 'n',
					'canceled' => isset($client['canceled']) ? $client['canceled'] : 'n',
					'added_date' => isset($client['added_date']) ? $client['added_date'] : date('Y-m-d'),
					'added_by' => isset($client['added_by']) ? $client['added_by'] : ''
			);
			return $this->client->client_add($this->session_id, $reseller_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update one record in Client
	 *
	 * @param int   $client_id
	 * @param array $client company_name, company_id, gender, contact_name, customer_no, vat_id, street, zip,
	 *                      city, state, country, telephone, mobile, fax, email, internet, icq, notes,
	 *                      bank_account_owner, bank_account_number, bank_code, bank_name, bank_account_iban,
	 *                      bank_account_swift, paypal_email, default_mailserver, limit_maildomain,
	 *                      limit_mailbox, limit_mailalias, limit_mailaliasdomain, limit_mailforward,
	 *                      limit_mailcatchall, limit_mailrouting, limit_mailfilter, limit_fetchmail,
	 *                      limit_mailquota, limit_spamfilter_wblist, limit_spamfilter_user,
	 *                      limit_spamfilter_policy, default_webserver, limit_web_ip, limit_web_domain,
	 *                      limit_web_quota, web_php_options, limit_cgi, limit_ssi, limit_perl, limit_ruby,
	 *                      limit_python, force_suexec, limit_hterror, limit_wildcard, limit_ssl,
	 *                      limit_web_subdomain, limit_web_aliasdomain, limit_ftp_user, limit_shell_user,
	 *                      ssh_chroot, limit_webdav_user, limit_backup, limit_aps, default_dnsserver,
	 *                      limit_dns_zone, default_slave_dnsserver, limit_dns_slave_zone, limit_dns_record,
	 *                      default_dbserver, limit_database, limit_database_quota, limit_cron,
	 *                      limit_cron_type, limit_cron_frequency, limit_traffic_quota, limit_client,
	 *                      limit_domainmodule, limit_mailmailinglist, limit_openvz_vm,
	 *                      limit_openvz_vm_template_id, parent_client_id, username, password, language,
	 *                      usertheme, template_master, template_additional, locked, canceled, added_date,
	 *                      added_by
	 * @return bool|array TRUE or error
	 */
	public function client_update($client_id = 0, $client = array())
	{
		try
		{
			$this->login();
			$record = $this->client->client_get($this->session_id, $client_id);
			$reseller_id = isset($client['reseller_id']) ? $client['reseller_id'] : $record['parent_client_id'];
			$params = array(
					'company_name' => isset($client['company_name']) ? $client['company_name'] : $record['company_name'],
					'company_id' => isset($client['company_id']) ? $client['company_id'] : $record['company_id'],
					'gender' => isset($client['gender']) ? $client['gender'] : $record['gender'],
					'contact_name' => isset($client['contact_name']) ? $client['contact_name'] : $record['contact_name'],
					'customer_no' => isset($client['customer_no']) ? $client['customer_no'] : $record['customer_no'],
					'vat_id' => isset($client['vat_id']) ? $client['vat_id'] : $record['vat_id'],
					'street' => isset($client['street']) ? $client['street'] : $record['street'],
					'zip' => isset($client['zip']) ? $client['zip'] : $record['zip'],
					'city' => isset($client['city']) ? $client['city'] : $record['city'],
					'state' => isset($client['state']) ? $client['state'] : $record['state'],
					'country' => isset($client['country']) ? $client['country'] : $record['country'],
					'telephone' => isset($client['telephone']) ? $client['telephone'] : $record['telephone'],
					'mobile' => isset($client['mobile']) ? $client['mobile'] : $record['mobile'],
					'fax' => isset($client['fax']) ? $client['fax'] : $record['fax'],
					'email' => isset($client['email']) ? $client['email'] : $record['email'],
					'internet' => isset($client['internet']) ? $client['internet'] : $record['internet'],
					'icq' => isset($client['icq']) ? $client['icq'] : $record['icq'],
					'notes' => isset($client['notes']) ? $client['notes'] : $record['notes'],
					'bank_account_owner' => isset($client['bank_account_owner']) ? $client['bank_account_owner'] : $record['bank_account_owner'],
					'bank_account_number' => isset($client['bank_account_number']) ? $client['bank_account_number'] : $record['bank_account_number'],
					'bank_code' => isset($client['bank_code']) ? $client['bank_code'] : $record['bank_code'],
					'bank_name' => isset($client['bank_name']) ? $client['bank_name'] : $record['bank_name'],
					'bank_account_iban' => isset($client['bank_account_iban']) ? $client['bank_account_iban'] : $record['bank_account_iban'],
					'bank_account_swift' => isset($client['bank_account_swift']) ? $client['bank_account_swift'] : $record['bank_account_swift'],
					'paypal_email' => isset($client['paypal_email']) ? $client['paypal_email'] : $record['paypal_email'],
					'default_mailserver' => isset($client['default_mailserver']) ? $client['default_mailserver'] : $record['default_mailserver'],
					'limit_maildomain' => isset($client['limit_maildomain']) ? $client['limit_maildomain'] : $record['limit_maildomain'],
					'limit_mailbox' => isset($client['limit_mailbox']) ? $client['limit_mailbox'] : $record['limit_mailbox'],
					'limit_mailalias' => isset($client['limit_mailalias']) ? $client['limit_mailalias'] : $record['limit_mailalias'],
					'limit_mailaliasdomain' => isset($client['limit_mailaliasdomain']) ? $client['limit_mailaliasdomain'] : $record['limit_mailaliasdomain'],
					'limit_mailforward' => isset($client['limit_mailforward']) ? $client['limit_mailforward'] : $record['limit_mailforward'],
					'limit_mailcatchall' => isset($client['limit_mailcatchall']) ? $client['limit_mailcatchall'] : $record['limit_mailcatchall'],
					'limit_mailrouting' => isset($client['limit_mailrouting']) ? $client['limit_mailrouting'] : $record['limit_mailrouting'],
					'limit_mailfilter' => isset($client['limit_mailfilter']) ? $client['limit_mailfilter'] : $record['limit_mailfilter'],
					'limit_fetchmail' => isset($client['limit_fetchmail']) ? $client['limit_fetchmail'] : $record['limit_fetchmail'],
					'limit_mailquota' => isset($client['limit_mailquota']) ? $client['limit_mailquota'] : $record['limit_mailquota'],
					'limit_spamfilter_wblist' => isset($client['limit_spamfilter_wblist']) ? $client['limit_spamfilter_wblist'] : $record['limit_spamfilter_wblist'],
					'limit_spamfilter_user' => isset($client['limit_spamfilter_user']) ? $client['limit_spamfilter_user'] : $record['limit_spamfilter_user'],
					'limit_spamfilter_policy' => isset($client['limit_spamfilter_policy']) ? $client['limit_spamfilter_policy'] : $record['limit_spamfilter_policy'],
					'default_webserver' => isset($client['default_webserver']) ? $client['default_webserver'] : $record['default_webserver'],
					'limit_web_ip' => isset($client['limit_web_ip']) ? $client['limit_web_ip'] : $record['limit_web_ip'],
					'limit_web_domain' => isset($client['limit_web_domain']) ? $client['limit_web_domain'] : $record['limit_web_domain'],
					'limit_web_quota' => isset($client['limit_web_quota']) ? $client['limit_web_quota'] : $record['limit_web_quota'],
					'web_php_options' => isset($client['web_php_options']) ? $client['web_php_options'] : $record['web_php_options'],
					'limit_cgi' => isset($client['limit_cgi']) ? $client['limit_cgi'] : $record['limit_cgi'],
					'limit_ssi' => isset($client['limit_ssi']) ? $client['limit_ssi'] : $record['limit_ssi'],
					'limit_perl' => isset($client['limit_perl']) ? $client['limit_perl'] : $record['limit_perl'],
					'limit_ruby' => isset($client['limit_ruby']) ? $client['limit_ruby'] : $record['limit_ruby'],
					'limit_python' => isset($client['limit_python']) ? $client['limit_python'] : $record['limit_python'],
					'force_suexec' => isset($client['force_suexec']) ? $client['force_suexec'] : $record['force_suexec'],
					'limit_hterror' => isset($client['limit_hterror']) ? $client['limit_hterror'] : $record['limit_hterror'],
					'limit_wildcard' => isset($client['limit_wildcard']) ? $client['limit_wildcard'] : $record['limit_wildcard'],
					'limit_ssl' => isset($client['limit_ssl']) ? $client['limit_ssl'] : $record['limit_ssl'],
					'limit_web_subdomain' => isset($client['limit_web_subdomain']) ? $client['limit_web_subdomain'] : $record['limit_web_subdomain'],
					'limit_web_aliasdomain' => isset($client['limit_web_aliasdomain']) ? $client['limit_web_aliasdomain'] : $record['limit_web_aliasdomain'],
					'limit_ftp_user' => isset($client['limit_ftp_user']) ? $client['limit_ftp_user'] : $record['limit_ftp_user'],
					'limit_shell_user' => isset($client['limit_shell_user']) ? $client['limit_shell_user'] : $record['limit_shell_user'],
					'ssh_chroot' => isset($client['ssh_chroot']) ? $client['ssh_chroot'] : $record['ssh_chroot'],
					'limit_webdav_user' => isset($client['limit_webdav_user']) ? $client['limit_webdav_user'] : $record['limit_webdav_user'],
					'limit_backup' => isset($client['limit_backup']) ? $client['limit_backup'] : $record['limit_backup'],
					'limit_aps' => isset($client['limit_aps']) ? $client['limit_aps'] : $record['limit_aps'],
					'default_dnsserver' => isset($client['default_dnsserver']) ? $client['default_dnsserver'] : $record['default_dnsserver'],
					'limit_dns_zone' => isset($client['limit_dns_zone']) ? $client['limit_dns_zone'] : $record['limit_dns_zone'],
					'default_slave_dnsserver' => isset($client['default_slave_dnsserver']) ? $client['default_slave_dnsserver'] : $record['default_slave_dnsserver'],
					'limit_dns_slave_zone' => isset($client['limit_dns_slave_zone']) ? $client['limit_dns_slave_zone'] : $record['limit_dns_slave_zone'],
					'limit_dns_record' => isset($client['limit_dns_record']) ? $client['limit_dns_record'] : $record['limit_dns_record'],
					'default_dbserver' => isset($client['default_dbserver']) ? $client['default_dbserver'] : $record['default_dbserver'],
					'limit_database' => isset($client['limit_database']) ? $client['limit_database'] : $record['limit_database'],
					'limit_database_quota' => isset($client['limit_database_quota']) ? $client['limit_database_quota'] : $record['limit_database_quota'],
					'limit_cron' => isset($client['limit_cron']) ? $client['limit_cron'] : $record['limit_cron'],
					'limit_cron_type' => isset($client['limit_cron_type']) ? $client['limit_cron_type'] : $record['limit_cron_type'],
					'limit_cron_frequency' => isset($client['limit_cron_frequency']) ? $client['limit_cron_frequency'] : $record['limit_cron_frequency'],
					'limit_traffic_quota' => isset($client['limit_traffic_quota']) ? $client['limit_traffic_quota'] : $record['limit_traffic_quota'],
					'limit_client' => isset($client['limit_client']) ? $client['limit_client'] : $record['limit_client'],
					'limit_domainmodule' => isset($client['limit_domainmodule']) ? $client['limit_domainmodule'] : $record['limit_domainmodule'],
					'limit_mailmailinglist' => isset($client['limit_mailmailinglist']) ? $client['limit_mailmailinglist'] : $record['limit_mailmailinglist'],
					'limit_openvz_vm' => isset($client['limit_openvz_vm']) ? $client['limit_openvz_vm'] : $record['limit_openvz_vm'],
					'limit_openvz_vm_template_id' => isset($client['limit_openvz_vm_template_id']) ? $client['limit_openvz_vm_template_id'] : $record['limit_openvz_vm_template_id'],
					'parent_client_id' => isset($client['parent_client_id']) ? $client['parent_client_id'] : $record['parent_client_id'],
					'username' => isset($client['username']) ? $client['username'] : $record['username'],
					'password' => isset($client['password']) ? $client['password'] : $record['password'],
					'language' => isset($client['language']) ? $client['language'] : $record['language'],
					'usertheme' => isset($client['usertheme']) ? $client['usertheme'] : $record['usertheme'],
					'template_master' => isset($client['template_master']) ? $client['template_master'] : $record['template_master'],
					'template_additional' => isset($client['template_additional']) ? $client['template_additional'] : $record['template_additional'],
					'locked' => isset($client['locked']) ? $client['locked'] : $record['locked'],
					'canceled' => isset($client['canceled']) ? $client['canceled'] : $record['canceled'],
					'added_date' => isset($client['added_date']) ? $client['added_date'] : $record['added_date'],
					'added_by' => isset($client['added_by']) ? $client['added_by'] : $record['added_by']
			);
			$this->client->client_update($this->session_id, $client_id, $reseller_id, $params);
			return TRUE;
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
	 * @return array client_template_assigned.* or error
	 */
	public function client_template_additional_get($client_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->client_template_additional_get($this->session_id, $client_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Internal significant use only
	 *
	 * @internal
	 * @param int $client_id
	 * @return bool|array TRUE or error
	 */
	public function _set_client_formdata($client_id = 0)
	{
		try
		{
			$this->login();
			$this->client->_set_client_formdata($this->session_id, $client_id);
			return TRUE;
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
	 * @return int|array client_template_assigned.assigned_template_id or error
	 */
	public function client_template_additional_add($client_id = 0, $template_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->client_template_additional_add($this->session_id, $client_id, $template_id));
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
	public function client_templates_get_all()
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->client_templates_get_all($this->session_id));
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
	 * @return bool|array TRUE or error
	 */
	public function client_delete($client_id = 0)
	{
		try
		{
			$this->login();

			$this->client->client_delete($this->session_id, $client_id);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function client_delete_everything($client_id = 0)
	{
		try
		{
			$this->login();
			$this->client->client_delete_everything($this->session_id, $client_id);
			return TRUE;
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
	 * @return array web_domain.(domain, domain_id, document_root, active) or error
	 */
	public function client_get_sites_by_user($sys_userid = 0, $sys_groupid = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->client_get_sites_by_user($this->session_id, $sys_userid, $sys_groupid));
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
	 * @return array sys_user.* or error
	 */
	public function client_get_by_username($username = '')
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->client_get_by_username($this->session_id, $username));
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
	public function client_get_all()
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->client_get_all($this->session_id));
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
	 * @return bool|array TRUE or error or error
	 */
	public function client_change_password($client_id = 0, $new_password = '')
	{
		try
		{
			$this->login();
			return $this->client->client_change_password($this->session_id, $client_id, $new_password);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
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
	 * @return bool|array TRUE or error
	 */
	public function dns_templatezone_add($client_id = 0, $template_id = 0, $domain = '', $ip = '', $ns1 = '', $ns2 = '', $email = '')
	{
		try
		{
			$this->login();
			$this->client->dns_templatezone_add($this->session_id, $client_id, $template_id, $domain, $ip, $ns1, $ns2, $email);
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
	 * @return array dns_soa.* or error
	 */
	public function dns_zone_get($id = 0)
	{
		try
		{
			$this->login();
			return $this->client->dns_zone_get($this->session_id, $id);
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
	 * @return int|array dns_soa.id or error
	 */
	public function dns_zone_get_id($origin = '')
	{
		try
		{
			$this->login();
			return $this->client->dns_zone_get_id($this->session_id, $origin);
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
	 * @param array $dns_soa server_id, origin, ns, mbox, serial, refresh, retry, expire, minimum, ttl, active,
	 *                       xfer, also_notify, update_acl
	 * @return int|array invoice_settings.invoice_settings_id or error
	 */
	public function dns_zone_add($client_id = 0, $dns_soa = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($dns_soa['server_id']) ? $dns_soa['server_id'] : 0,
					'origin' => isset($dns_soa['origin']) ? $dns_soa['origin'] : '',
					'ns' => isset($dns_soa['ns']) ? $dns_soa['ns'] : '',
					'mbox' => isset($dns_soa['mbox']) ? $dns_soa['mbox'] : '',
					'serial' => isset($dns_soa['serial']) ? $dns_soa['serial'] : 1,
					'refresh' => isset($dns_soa['refresh']) ? $dns_soa['refresh'] : 28800,
					'retry' => isset($dns_soa['retry']) ? $dns_soa['retry'] : 7200,
					'expire' => isset($dns_soa['expire']) ? $dns_soa['expire'] : 604800,
					'minimum' => isset($dns_soa['minimum']) ? $dns_soa['minimum'] : 86400,
					'ttl' => isset($dns_soa['ttl']) ? $dns_soa['ttl'] : 86400,
					'active' => isset($dns_soa['active']) ? $dns_soa['active'] : 'N',
					'xfer' => isset($dns_soa['xfer']) ? $dns_soa['xfer'] : '',
					'also_notify' => isset($dns_soa['also_notify']) ? $dns_soa['also_notify'] : '',
					'update_acl' => isset($dns_soa['update_acl']) ? $dns_soa['update_acl'] : '',
			);
			return $this->client->dns_zone_add($this->session_id, $client_id, $params);
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
	 * @param array $dns_soa server_id, origin, ns, mbox, serial, refresh, retry, expire, minimum, ttl, active,
	 *                       xfer, also_notify, update_acl
	 * @return int|array invoice_settings.invoice_settings_id or error
	 */
	public function dns_zone_update($client_id = 0, $id = 0, $dns_soa = array())
	{
		try
		{
			$this->login();
			$record = $this->client->dns_zone_get($this->session_id, $id);
			$params = array(
					'server_id' => isset($dns_soa['server_id']) ? $dns_soa['server_id'] : $record['server_id'],
					'origin' => isset($dns_soa['origin']) ? $dns_soa['origin'] : $record['origin'],
					'ns' => isset($dns_soa['ns']) ? $dns_soa['ns'] : $record['ns'],
					'mbox' => isset($dns_soa['mbox']) ? $dns_soa['mbox'] : $record['mbox'],
					'serial' => isset($dns_soa['serial']) ? $dns_soa['serial'] : $record['serial'],
					'refresh' => isset($dns_soa['refresh']) ? $dns_soa['refresh'] : $record['refresh'],
					'retry' => isset($dns_soa['retry']) ? $dns_soa['retry'] : $record['retry'],
					'expire' => isset($dns_soa['expire']) ? $dns_soa['expire'] : $record['expire'],
					'minimum' => isset($dns_soa['minimum']) ? $dns_soa['minimum'] : $record['minimum'],
					'ttl' => isset($dns_soa['ttl']) ? $dns_soa['ttl'] : $record['ttl'],
					'active' => isset($dns_soa['active']) ? $dns_soa['active'] : $record['active'],
					'xfer' => isset($dns_soa['xfer']) ? $dns_soa['xfer'] : $record['xfer'],
					'also_notify' => isset($dns_soa['also_notify']) ? $dns_soa['also_notify'] : $record['also_notify'],
					'update_acl' => isset($dns_soa['update_acl']) ? $dns_soa['update_acl'] : $record['update_acl'],
			);
			return $this->client->dns_zone_update($this->session_id, $client_id, $id, $params);
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
	 * @return bool|array TRUE or error
	 */
	public function dns_zone_delete($id = 0)
	{
		try
		{
			$this->login();
			$this->client->dns_zone_delete($this->session_id, $id);
			return TRUE;
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
	 * @return array dns_rr.* or error
	 */
	public function dns_aaaa_get($id = 0)
	{
		try
		{
			$this->login();
			return $this->client->dns_aaaa_get($this->session_id, $id);
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
	 * @return int|array dns_rr.id or error
	 */
	public function dns_aaaa_add($client_id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : '',
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : '',
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
					'type' => 'AAAA',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => date('Ymd') . '01'
			);
			return $this->client->dns_aaaa_add($this->session_id, $client_id, $params);
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
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 * @return bool|array TRUE or error
	 */
	public function dns_aaaa_update($client_id = 0, $id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$record = $this->client->dns_aaaa_get($this->session_id, $id);
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : $record['server_id'],
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : $record['zone'],
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : $record['name'],
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : $record['data'],
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : $record['ttl'],
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : $record['active'],
					'type' => 'AAAA',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => $record['serial'] + 1
			);
			$this->client->dns_aaaa_update($this->session_id, $client_id, $id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function dns_aaaa_delete($id = 0)
	{
		try
		{
			$this->login();
			$this->client->dns_aaaa_delete($this->session_id, $id);
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
	 * @return array dns_rr.* or error
	 */
	public function dns_a_get($id = 0)
	{
		try
		{
			$this->login();
			return $this->client->dns_a_get($this->session_id, $id);
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
	 * @return int|array dns_rr.id or error
	 */
	public function dns_a_add($client_id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : '',
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : '',
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
					'type' => 'A',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => date('Ymd') . '01'
			);
			return $this->client->dns_a_add($this->session_id, $client_id, $params);
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
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 * @return bool|array TRUE or error
	 */
	public function dns_a_update($client_id = 0, $id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$record = $this->client->dns_a_get($this->session_id, $id);
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : $record['server_id'],
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : $record['zone'],
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : $record['name'],
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : $record['data'],
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : $record['ttl'],
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : $record['active'],
					'type' => 'A',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => $record['serial'] + 1
			);
			$this->client->dns_a_update($this->session_id, $client_id, $id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function dns_a_delete($id = 0)
	{
		try
		{
			$this->login();
			$this->client->dns_a_delete($this->session_id, $id);
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
	 * @return array dns_rr.* or error
	 */
	public function dns_alias_get($id = 0)
	{
		try
		{
			$this->login();
			return $this->client->dns_alias_get($this->session_id, $id);
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
	 * @return int|array dns_rr.id or error
	 */
	public function dns_alias_add($client_id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : '',
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : '',
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
					'type' => 'ALIAS',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => date('Ymd') . '01'
			);
			return $this->client->dns_alias_add($this->session_id, $client_id, $params);
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
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 * @return bool|array TRUE or error
	 */
	public function dns_alias_update($client_id = 0, $id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$record = $this->client->dns_alias_get($this->session_id, $id);
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : $record['server_id'],
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : $record['zone'],
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : $record['name'],
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : $record['data'],
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : $record['ttl'],
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : $record['active'],
					'type' => 'ALIAS',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => $record['serial'] + 1
			);
			$this->client->dns_alias_update($this->session_id, $client_id, $id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function dns_alias_delete($id = 0)
	{
		try
		{
			$this->login();
			$this->client->dns_alias_delete($this->session_id, $id);
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
	 * @return array dns_rr.* or error
	 */
	public function dns_cname_get($id = 0)
	{
		try
		{
			$this->login();
			return $this->client->dns_cname_get($this->session_id, $id);
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
	 * @return int|array dns_rr.id or error
	 */
	public function dns_cname_add($client_id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : '',
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : '',
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
					'type' => 'CNAME',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => date('Ymd') . '01'
			);
			return $this->client->dns_cname_add($this->session_id, $client_id, $params);
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
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 * @return bool|array TRUE or error
	 */
	public function dns_cname_update($client_id = 0, $id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$record = $this->client->dns_cname_get($this->session_id, $id);
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : $record['server_id'],
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : $record['zone'],
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : $record['name'],
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : $record['data'],
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : $record['ttl'],
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : $record['active'],
					'type' => 'CNAME',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => $record['serial'] + 1
			);
			$this->client->dns_cname_update($this->session_id, $client_id, $id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function dns_cname_delete($id = 0)
	{
		try
		{
			$this->login();
			$this->client->dns_cname_delete($this->session_id, $id);
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
	 * @return array dns_rr.* or error
	 */
	public function dns_hinfo_get($id = 0)
	{
		try
		{
			$this->login();
			return $this->client->dns_hinfo_get($this->session_id, $id);
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
	 * @return int|array dns_rr.id or error
	 */
	public function dns_hinfo_add($client_id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : '',
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : '',
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
					'type' => 'HINFO',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => date('Ymd') . '01'
			);
			return $this->client->dns_hinfo_add($this->session_id, $client_id, $params);
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
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 * @return bool|array TRUE or error
	 */
	public function dns_hinfo_update($client_id = 0, $id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$record = $this->client->dns_hinfo_get($this->session_id, $id);
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : $record['server_id'],
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : $record['zone'],
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : $record['name'],
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : $record['data'],
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : $record['ttl'],
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : $record['active'],
					'type' => 'HINFO',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => $record['serial'] + 1
			);
			$this->client->dns_hinfo_update($this->session_id, $client_id, $id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function dns_hinfo_delete($id = 0)
	{
		try
		{
			$this->login();
			$this->client->dns_hinfo_delete($this->session_id, $id);
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
	 * @return array dns_rr.* or error
	 */
	public function dns_mx_get($id = 0)
	{
		try
		{
			$this->login();
			return $this->client->dns_mx_get($this->session_id, $id);
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
	 * @return int|array dns_rr.id or error
	 */
	public function dns_mx_add($client_id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : '',
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : '',
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
					'aux' => isset($dns_rr['aux']) ? $dns_rr['aux'] : 10,
					'type' => 'MX',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => date('Ymd') . '01'
			);
			return $this->client->dns_mx_add($this->session_id, $client_id, $params);
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
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 * @return bool|array TRUE or error
	 */
	public function dns_mx_update($client_id = 0, $id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$record = $this->client->dns_mx_get($this->session_id, $id);
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : $record['server_id'],
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : $record['zone'],
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : $record['name'],
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : $record['data'],
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : $record['ttl'],
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : $record['active'],
					'aux' => isset($dns_rr['aux']) ? $dns_rr['aux'] : $record['aux'],
					'type' => 'MX',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => $record['serial'] + 1
			);
			$this->client->dns_mx_update($this->session_id, $client_id, $id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function dns_mx_delete($id = 0)
	{
		try
		{
			$this->login();
			$this->client->dns_mx_delete($this->session_id, $id);
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
	 * @return array dns_rr.* or error
	 */
	public function dns_ns_get($id = 0)
	{
		try
		{
			$this->login();
			return $this->client->dns_ns_get($this->session_id, $id);
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
	 * @return int|array dns_rr.id or error
	 */
	public function dns_ns_add($client_id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : '',
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : '',
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
					'type' => 'NS',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => date('Ymd') . '01'
			);
			return $this->client->dns_ns_add($this->session_id, $client_id, $params);
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
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 * @return bool|array TRUE or error
	 */
	public function dns_ns_update($client_id = 0, $id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$record = $this->client->dns_ns_get($this->session_id, $id);
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : $record['server_id'],
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : $record['zone'],
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : $record['name'],
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : $record['data'],
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : $record['ttl'],
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : $record['active'],
					'type' => 'NS',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => $record['serial'] + 1
			);
			$this->client->dns_ns_update($this->session_id, $client_id, $id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function dns_ns_delete($id = 0)
	{
		try
		{
			$this->login();
			$this->client->dns_ns_delete($this->session_id, $id);
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
	 * @return array dns_rr.* or error
	 */
	public function dns_ptr_get($id = 0)
	{
		try
		{
			$this->login();
			return $this->client->dns_ptr_get($this->session_id, $id);
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
	 * @return int|array dns_rr.id or error
	 */
	public function dns_ptr_add($client_id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : '',
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : '',
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
					'type' => 'PTR',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => date('Ymd') . '01'
			);
			return $this->client->dns_ptr_add($this->session_id, $client_id, $params);
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
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 * @return bool|array TRUE or error
	 */
	public function dns_ptr_update($client_id = 0, $id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$record = $this->client->dns_ptr_get($this->session_id, $id);
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : $record['server_id'],
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : $record['zone'],
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : $record['name'],
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : $record['data'],
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : $record['ttl'],
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : $record['active'],
					'type' => 'PTR',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => $record['serial'] + 1
			);
			$this->client->dns_ptr_update($this->session_id, $client_id, $id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function dns_ptr_delete($id = 0)
	{
		try
		{
			$this->login();
			$this->client->dns_ptr_delete($this->session_id, $id);
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
	 * @return array dns_rr.* or error
	 */
	public function dns_rp_get($id = 0)
	{
		try
		{
			$this->login();
			return $this->client->dns_rp_get($this->session_id, $id);
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
	 * @return int|array dns_rr.id or error
	 */
	public function dns_rp_add($client_id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : '',
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : '',
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
					'type' => 'RP',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => date('Ymd') . '01'
			);
			return $this->client->dns_rp_add($this->session_id, $client_id, $params);
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
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 * @return bool|array TRUE or error
	 */
	public function dns_rp_update($client_id = 0, $id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$record = $this->client->dns_rp_get($this->session_id, $id);
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : $record['server_id'],
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : $record['zone'],
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : $record['name'],
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : $record['data'],
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : $record['ttl'],
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : $record['active'],
					'type' => 'RP',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => $record['serial'] + 1
			);
			$this->client->dns_rp_update($this->session_id, $client_id, $id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function dns_rp_delete($id = 0)
	{
		try
		{
			$this->login();
			$this->client->dns_rp_delete($this->session_id, $id);
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
	 * @return array dns_rr.* or error
	 */
	public function dns_srv_get($id = 0)
	{
		try
		{
			$this->login();
			return $this->client->dns_srv_get($this->session_id, $id);
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
	 * @return int|array dns_rr.id or error
	 */
	public function dns_srv_add($client_id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : '',
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : '',
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
					'type' => 'SRV',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => date('Ymd') . '01'
			);
			return $this->client->dns_srv_add($this->session_id, $client_id, $params);
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
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 * @return bool|array TRUE or error
	 */
	public function dns_srv_update($client_id = 0, $id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$record = $this->client->dns_srv_get($this->session_id, $id);
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : $record['server_id'],
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : $record['zone'],
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : $record['name'],
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : $record['data'],
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : $record['ttl'],
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : $record['active'],
					'type' => 'SRV',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => $record['serial'] + 1
			);
			$this->client->dns_srv_update($this->session_id, $client_id, $id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function dns_srv_delete($id = 0)
	{
		try
		{
			$this->login();
			$this->client->dns_srv_delete($this->session_id, $id);
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
	 * @return array dns_rr.* or error
	 */
	public function dns_txt_get($id = 0)
	{
		try
		{
			$this->login();
			return $this->client->dns_txt_get($this->session_id, $id);
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
	 * @return int|array dns_rr.id or error
	 */
	public function dns_txt_add($client_id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : 0,
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : 0,
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : '',
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : '',
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : 86400,
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : 'Y',
					'type' => 'TXT',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => date('Ymd') . '01'
			);
			return $this->client->dns_txt_add($this->session_id, $client_id, $params);
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
	 * @param array $dns_rr server_id, zone, name, data, ttl, active
	 * @return bool|array TRUE or error
	 */
	public function dns_txt_update($client_id = 0, $id = 0, $dns_rr = array())
	{
		try
		{
			$this->login();
			$record = $this->client->dns_txt_get($this->session_id, $id);
			$params = array(
					'server_id' => isset($dns_rr['server_id']) ? $dns_rr['server_id'] : $record['server_id'],
					'zone' => isset($dns_rr['zone']) ? $dns_rr['zone'] : $record['zone'],
					'name' => isset($dns_rr['name']) ? $dns_rr['name'] : $record['name'],
					'data' => isset($dns_rr['data']) ? $dns_rr['data'] : $record['data'],
					'ttl' => isset($dns_rr['ttl']) ? $dns_rr['ttl'] : $record['ttl'],
					'active' => isset($dns_rr['active']) ? $dns_rr['active'] : $record['active'],
					'type' => 'TXT',
					'stamp' => date('Y-m-d H:i:s'),
					'serial' => $record['serial'] + 1
			);
			$this->client->dns_txt_update($this->session_id, $client_id, $id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function dns_txt_delete($id = 0)
	{
		try
		{
			$this->login();
			$this->client->dns_txt_delete($this->session_id, $id);
			return TRUE;
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
	 * @return array dns_soa.* or error
	 */
	public function dns_zone_get_by_user($client_id = 0, $server_id = 0)
	{
		try
		{
			$this->login();
			return $this->client->dns_zone_get_by_user($this->session_id, $client_id, $server_id);
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
	 * @return array dns_soa.* or error
	 */
	public function dns_rr_get_all_by_zone($zone = 0)
	{
		try
		{
			$this->login();
			return $this->client->dns_rr_get_all_by_zone($this->session_id, $zone);
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
	 * @return string dns_soa.active or error
	 */
	public function dns_zone_set_status($id = 0, $status = 'inactive')
	{
		try
		{
			$this->login();
			$this->client->dns_zone_set_status($this->session_id, $id, $status);
			return $status;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get one record from Client > Domains
	 *
	 * @param int $domain_id
	 * @return array domain.* or error
	 */
	public function domains_domain_get($domain_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->domains_domain_get($this->session_id, $domain_id));
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
	 * @return int|array domain.domain_id or error
	 */
	public function domains_domain_add($client_id = 0, $domain = '')
	{
		try
		{
			$this->login();
			$params = array('domain' => $domain);
			return $this->client->domains_domain_add($this->session_id, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Delete one record in Client > Domains > Domain
	 *
	 * @param int $domain_id
	 * @return bool|array TRUE or error
	 */
	public function domains_domain_delete($domain_id = 0)
	{
		try
		{
			$this->login();
			$this->client->domains_domain_delete($this->session_id, $domain_id);
			return TRUE;
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
	 * @return array domain.(domain_id, domain) or error
	 */
	public function domains_domain_get_all_by_user($group_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->domains_get_all_by_user($this->session_id, $group_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get one record from Mail > Domain
	 *
	 * @param int $domain_id
	 * @return array mail_domain.* or error
	 */
	public function mail_domain_get($domain_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_domain_get($this->session_id, $domain_id));
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
	 * @return int|array mail_domain.domain_id or error
	 */
	public function mail_domain_add($client_id = 0, $mail_domain = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($mail_domain['server_id']) ? $mail_domain['server_id'] : 0,
					'domain' => isset($mail_domain['domain']) ? $mail_domain['domain'] : '',
					'active' => isset($mail_domain['active']) ? $mail_domain['active'] : 'n'
			);
			return $this->client->mail_domain_add($this->session_id, $client_id, $params);
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
	 * @param array $mail_domain server_id,  domain,  active
	 * @return bool|array TRUE or error
	 */
	public function mail_domain_update($client_id = 0, $domain_id = 0, $mail_domain = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_domain_get($this->session_id, $domain_id);
			$params = array(
					'server_id' => isset($mail_domain['server_id']) ? $mail_domain['server_id'] : $record['server_id'],
					'domain' => isset($mail_domain['domain']) ? $mail_domain['domain'] : $record['domain'],
					'active' => isset($mail_domain['active']) ? $mail_domain['active'] : $record['active']
			);
			$this->client->mail_domain_update($this->session_id, $client_id, $domain_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function mail_domain_delete($domain_id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_domain_delete($this->session_id, $domain_id);
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
	 * @return array mail_forwarding.* or error
	 */
	public function mail_aliasdomain_get($forwarding_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_aliasdomain_get($this->session_id, $forwarding_id));
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
	 * @return int|array mail_forwarding.forwarding_id or error
	 */
	public function mail_aliasdomain_add($client_id = 0, $mail_forwarding = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($mail_forwarding['server_id']) ? $mail_forwarding['server_id'] : 0,
					'source' => isset($mail_forwarding['source']) ? $mail_forwarding['source'] : '',
					'destination' => isset($mail_forwarding['destination']) ? $mail_forwarding['destination'] : '',
					'active' => isset($mail_forwarding['active']) ? $mail_forwarding['active'] : 'n',
					'type' => 'aliasdomain'
			);
			return $this->client->mail_aliasdomain_add($this->session_id, $client_id, $params);
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
	 * @param array $mail_forwarding server_id, source, destination, active
	 * @return bool|array TRUE or error or error
	 */
	public function mail_aliasdomain_update($client_id = 0, $forwarding_id = 0, $mail_forwarding = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_aliasdomain_get($this->session_id, $forwarding_id);
			$params = array(
					'server_id' => isset($mail_forwarding['server_id']) ? $mail_forwarding['server_id'] : $record['server_id'],
					'source' => isset($mail_forwarding['source']) ? $mail_forwarding['source'] : $record['source'],
					'destination' => isset($mail_forwarding['destination']) ? $mail_forwarding['destination'] : $record['destination'],
					'active' => isset($mail_forwarding['active']) ? $mail_forwarding['active'] : $record['active'],
					'type' => 'aliasdomain'
			);
			$this->client->mail_aliasdomain_update($this->session_id, $client_id, $forwarding_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function mail_aliasdomain_delete($forwarding_id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_aliasdomain_delete($this->session_id, $forwarding_id);
			return TRUE;
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
	 * @return array mail_mailinglist.* or error
	 */
	public function mail_mailinglist_get($mailinglist_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_mailinglist_get($this->session_id, $mailinglist_id));
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
	 * @return int|array mail_mailinglist.mailinglist_id or error
	 */
	public function mail_mailinglist_add($client_id = 0, $mail_mailinglist = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($mail_mailinglist['server_id']) ? $mail_mailinglist['server_id'] : 0,
					'domain' => isset($mail_mailinglist['domain']) ? $mail_mailinglist['domain'] : '',
					'listname' => isset($mail_mailinglist['listname']) ? $mail_mailinglist['listname'] : '',
					'email' => isset($mail_mailinglist['email']) ? $mail_mailinglist['email'] : '',
					'password' => isset($mail_mailinglist['password']) ? $mail_mailinglist['password'] : '',
			);
			return $this->client->mail_mailinglist_add($this->session_id, $client_id, $params);
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
	 * @param array $mail_mailinglist server_id, domain, listname, email, password
	 * @return bool|array TRUE or error or error
	 */
	public function mail_mailinglist_update($client_id = 0, $mailinglist_id = 0, $mail_mailinglist = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_mailinglist_get($this->session_id, $mailinglist_id);
			$params = array(
					'server_id' => isset($mail_mailinglist['server_id']) ? $mail_mailinglist['server_id'] : $record['server_id'],
					'domain' => isset($mail_mailinglist['domain']) ? $mail_mailinglist['domain'] : $record['domain'],
					'listname' => isset($mail_mailinglist['listname']) ? $mail_mailinglist['listname'] : $record['listname'],
					'email' => isset($mail_mailinglist['email']) ? $mail_mailinglist['email'] : $record['email'],
					'password' => isset($mail_mailinglist['password']) ? $mail_mailinglist['password'] : $record['password']
			);
			$this->client->mail_mailinglist_update($this->session_id, $client_id, $mailinglist_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error or error
	 */
	public function mail_mailinglist_delete($mailinglist_id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_mailinglist_delete($this->session_id, $mailinglist_id);
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
	 * @return array mail_user.* or error
	 */
	public function mail_user_get($mailuser_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_user_get($this->session_id, $mailuser_id));
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
	 * @param array $mail_user server_id,  email,  login,  password,  name,  uid,  gid,  maildir,  quota,  cc,
	 *                         homedir,  autoresponder, autoresponder_start_date,  autoresponder_end_date,
	 *                         autoresponder_subject,  autoresponder_text,  move_junk, custom_mailfilter,
	 *                         postfix,  access,  disableimap,  disablepop3,  disabledeliver,  disablesmtp,
	 *                         disablesieve, disablesieve-filter,  disablelda,  disablelmtp,  disabledoveadm
	 * @return int|array mail_user.mailuser_id or error
	 */
	public function mail_user_add($client_id = 0, $mail_user = array())
	{
		try
		{
			$this->login();
			$maildir = explode('@', $mail_user['email']);
			$params = array(
					'server_id' => isset($mail_user['server_id']) ? $mail_user['server_id'] : 0,
					'email' => isset($mail_user['email']) ? $mail_user['email'] : '',
					'login' => isset($mail_user['login']) ? $mail_user['login'] : $mail_user['email'],
					'password' => isset($mail_user['password']) ? $mail_user['password'] : '',
					'name' => isset($mail_user['name']) ? $mail_user['name'] : '',
					'uid' => isset($mail_user['uid']) ? $mail_user['uid'] : 5000,
					'gid' => isset($mail_user['gid']) ? $mail_user['gid'] : 5000,
					'maildir' => "/var/vmail/{$maildir[1]}/{$maildir[0]}",
					'quota' => isset($mail_user['quota']) ? $mail_user['quota'] : 0,
					'cc' => isset($mail_user['cc']) ? $mail_user['cc'] : '',
					'homedir' => isset($mail_user['homedir']) ? $mail_user['homedir'] : '/var/vmail',
					'autoresponder' => isset($mail_user['autoresponder']) ? $mail_user['autoresponder'] : 'n',
					'autoresponder_start_date' => isset($mail_user['autoresponder_start_date']) ? $mail_user['autoresponder_start_date'] : array(
							'day' => date('d'),
							'month' => date('m'),
							'year' => date('Y'),
							'hour' => date('H'),
							'minute' => date('i')
					),
					'autoresponder_end_date' => isset($mail_user['autoresponder_end_date']) ? $mail_user['autoresponder_end_date'] : array(
							'day' => date('d'),
							'month' => date('m') + 1,
							'year' => date('Y'),
							'hour' => date('H'),
							'minute' => date('i')
					),
					'autoresponder_subject' => isset($mail_user['autoresponder_subject']) ? $mail_user['autoresponder_subject'] : '',
					'autoresponder_text' => isset($mail_user['autoresponder_text']) ? $mail_user['autoresponder_text'] : '',
					'move_junk' => isset($mail_user['move_junk']) ? $mail_user['move_junk'] : 'n',
					'custom_mailfilter' => isset($mail_user['custom_mailfilter']) ? $mail_user['custom_mailfilter'] : '',
					'postfix' => isset($mail_user['postfix']) ? $mail_user['postfix'] : 'y',
					'access' => isset($mail_user['access']) ? $mail_user['access'] : 'y',
					'disableimap' => isset($mail_user['disableimap']) ? $mail_user['disableimap'] : 'n',
					'disablepop3' => isset($mail_user['disablepop3']) ? $mail_user['disablepop3'] : 'n',
					'disabledeliver' => isset($mail_user['disabledeliver']) ? $mail_user['disabledeliver'] : 'n',
					'disablesmtp' => isset($mail_user['disablesmtp']) ? $mail_user['disablesmtp'] : 'n',
					'disablesieve' => isset($mail_user['disablesieve']) ? $mail_user['disablesieve'] : 'n',
					'disablesieve-filter' => isset($mail_user['disablesieve-filter']) ? $mail_user['disablesieve-filter'] : 'n',
					'disablelda' => isset($mail_user['disablelda']) ? $mail_user['disablelda'] : 'n',
					'disablelmtp' => isset($mail_user['disablelmtp']) ? $mail_user['disablelmtp'] : 'n',
					'disabledoveadm' => isset($mail_user['disabledoveadm']) ? $mail_user['disabledoveadm'] : 'n',
			);
			return $this->client->mail_user_add($this->session_id, $client_id, $params);
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
	 * @param array $mail_user server_id,  email,  login,  password,  name,  uid,  gid,  maildir,  quota,  cc,
	 *                         homedir,  autoresponder, autoresponder_start_date,  autoresponder_end_date,
	 *                         autoresponder_subject,  autoresponder_text,  move_junk, custom_mailfilter,
	 *                         postfix,  access,  disableimap,  disablepop3,  disabledeliver,  disablesmtp,
	 *                         disablesieve, disablesieve-filter,  disablelda,  disablelmtp,  disabledoveadm
	 * @return bool|array TRUE or error
	 */
	public function mail_user_update($client_id = 0, $mailuser_id = 0, $mail_user = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_user_get($this->session_id, $mailuser_id);
			$maildir = explode('@', $mail_user['email']);
			$params = array(
					'server_id' => isset($mail_user['server_id']) ? $mail_user['server_id'] : $record['server_id'],
					'email' => isset($mail_user['email']) ? $mail_user['email'] : $record['email'],
					'login' => isset($mail_user['login']) ? $mail_user['login'] : $mail_user['email'],
					'password' => isset($mail_user['password']) ? $mail_user['password'] : $record['password'],
					'name' => isset($mail_user['name']) ? $mail_user['name'] : $record['name'],
					'uid' => isset($mail_user['uid']) ? $mail_user['uid'] : $record['uid'],
					'gid' => isset($mail_user['gid']) ? $mail_user['gid'] : $record['gid'],
					'maildir' => isset($mail_user['email']) ? "/var/vmail/{$maildir[1]}/{$maildir[0]}" : $record['maildir'],
					'quota' => isset($mail_user['quota']) ? $mail_user['quota'] : $record['quota'],
					'cc' => isset($mail_user['cc']) ? $mail_user['cc'] : $record['cc'],
					'homedir' => isset($mail_user['homedir']) ? $mail_user['homedir'] : $record['homedir'],
					'autoresponder' => isset($mail_user['autoresponder']) ? $mail_user['autoresponder'] : $record['autoresponder'],
					'autoresponder_start_date' => isset($mail_user['autoresponder_start_date']) ? $mail_user['autoresponder_start_date'] : $record['autoresponder_start_date'],
					'autoresponder_end_date' => isset($mail_user['autoresponder_end_date']) ? $mail_user['autoresponder_end_date'] : $record['autoresponder_end_date'],
					'autoresponder_subject' => isset($mail_user['autoresponder_subject']) ? $mail_user['autoresponder_subject'] : $record['autoresponder_subject'],
					'autoresponder_text' => isset($mail_user['autoresponder_text']) ? $mail_user['autoresponder_text'] : $record['autoresponder_text'],
					'move_junk' => isset($mail_user['move_junk']) ? $mail_user['move_junk'] : $record['move_junk'],
					'custom_mailfilter' => isset($mail_user['custom_mailfilter']) ? $mail_user['custom_mailfilter'] : $record['custom_mailfilter'],
					'postfix' => isset($mail_user['postfix']) ? $mail_user['postfix'] : $record['postfix'],
					'access' => isset($mail_user['access']) ? $mail_user['access'] : $record['access'],
					'disableimap' => isset($mail_user['disableimap']) ? $mail_user['disableimap'] : $record['disableimap'],
					'disablepop3' => isset($mail_user['disablepop3']) ? $mail_user['disablepop3'] : $record['disablepop3'],
					'disabledeliver' => isset($mail_user['disabledeliver']) ? $mail_user['disabledeliver'] : $record['disabledeliver'],
					'disablesmtp' => isset($mail_user['disablesmtp']) ? $mail_user['disablesmtp'] : $record['disablesmtp'],
					'disablesieve' => isset($mail_user['disablesieve']) ? $mail_user['disablesieve'] : $record['disablesieve'],
					'disablesieve-filter' => isset($mail_user['disablesieve-filter']) ? $mail_user['disablesieve-filter'] : $record['disablesieve-filter'],
					'disablelda' => isset($mail_user['disablelda']) ? $mail_user['disablelda'] : $record['disablelda'],
					'disablelmtp' => isset($mail_user['disablelmtp']) ? $mail_user['disablelmtp'] : $record['disablelmtp'],
					'disabledoveadm' => isset($mail_user['disabledoveadm']) ? $mail_user['disabledoveadm'] : $record['disabledoveadm'],
			);
			$this->client->mail_user_update($this->session_id, $client_id, $mailuser_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function mail_user_delete($mailuser_id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_user_delete($this->session_id, $mailuser_id);
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
	 * @return array mail_user_filter.* or error
	 */
	public function mail_user_filter_get($filter_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_user_filter_get($this->session_id, $filter_id));
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
	 * @param array $mail_user_filter mailuser_id, rulename, source, searchterm, op, action, target, active
	 * @return array mail_user_filter.filter_id or error
	 */
	public function mail_user_filter_add($client_id = 0, $mail_user_filter = array())
	{
		try
		{
			$this->login();
			$params = array(
					'mailuser_id' => isset($mail_user_filter['mailuser_id']) ? $mail_user_filter['mailuser_id'] : 0,
					'rulename' => isset($mail_user_filter['rulename']) ? $mail_user_filter['rulename'] : '',
					'source' => isset($mail_user_filter['source']) ? $mail_user_filter['source'] : '',
					'searchterm' => isset($mail_user_filter['searchterm']) ? $mail_user_filter['searchterm'] : '',
					'op' => isset($mail_user_filter['op']) ? $mail_user_filter['op'] : '',
					'action' => isset($mail_user_filter['action']) ? $mail_user_filter['action'] : '',
					'target' => isset($mail_user_filter['target']) ? $mail_user_filter['target'] : '',
					'active' => isset($mail_user_filter['active']) ? $mail_user_filter['active'] : 'n',
			);
			return $this->client->mail_user_filter_add($this->session_id, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	public function mail_user_filter_update($client_id = 0, $filter_id = 0, $mail_user_filter = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_user_filter_get($this->session_id, $filter_id);
			$params = array(
					'mailuser_id' => isset($mail_user_filter['mailuser_id']) ? $mail_user_filter['mailuser_id'] : $record['mailuser_id'],
					'rulename' => isset($mail_user_filter['rulename']) ? $mail_user_filter['rulename'] : $record['rulename'],
					'source' => isset($mail_user_filter['source']) ? $mail_user_filter['source'] : $record['source'],
					'searchterm' => isset($mail_user_filter['searchterm']) ? $mail_user_filter['searchterm'] : $record['searchterm'],
					'op' => isset($mail_user_filter['op']) ? $mail_user_filter['op'] : $record['op'],
					'action' => isset($mail_user_filter['action']) ? $mail_user_filter['action'] : $record['action'],
					'target' => isset($mail_user_filter['target']) ? $mail_user_filter['target'] : $record['target'],
					'active' => isset($mail_user_filter['active']) ? $mail_user_filter['active'] : $record['active']
			);
			$this->client->mail_user_filter_update($this->session_id, $client_id, $filter_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function mail_user_filter_delete($filter_id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_user_filter_delete($this->session_id, $filter_id);
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
	 * @return array mail_forwarding.* or error
	 */
	public function mail_alias_get($forwarding_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_alias_get($this->session_id, $forwarding_id));
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
	 * @return int|array mail_forwarding.forwarding_id or error
	 */
	public function mail_alias_add($client_id = 0, $mail_forwarding = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($mail_forwarding['server_id']) ? $mail_forwarding['server_id'] : 0,
					'source' => isset($mail_forwarding['source']) ? $mail_forwarding['source'] : '',
					'destination' => isset($mail_forwarding['destination']) ? $mail_forwarding['destination'] : '',
					'active' => isset($mail_forwarding['active']) ? $mail_forwarding['active'] : 'n',
					'type' => 'alias'
			);
			return $this->client->mail_alias_add($this->session_id, $client_id, $params);
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
	 * @param array $mail_forwarding server_id, source, destination, active
	 * @return bool|array TRUE or error
	 */
	public function mail_alias_update($client_id = 0, $forwarding_id = 0, $mail_forwarding = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_alias_get($this->session_id, $forwarding_id);
			$params = array(
					'server_id' => isset($mail_forwarding['server_id']) ? $mail_forwarding['server_id'] : $record['server_id'],
					'source' => isset($mail_forwarding['source']) ? $mail_forwarding['source'] : $record['source'],
					'destination' => isset($mail_forwarding['destination']) ? $mail_forwarding['destination'] : $record['destination'],
					'active' => isset($mail_forwarding['active']) ? $mail_forwarding['active'] : $record['active'],
					'type' => 'alias'
			);
			$this->client->mail_alias_update($this->session_id, $client_id, $forwarding_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function mail_alias_delete($forwarding_id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_alias_delete($this->session_id, $forwarding_id);
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
	 * @return array mail_forwarding.* or error
	 */
	public function mail_forward_get($forwarding_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_forward_get($this->session_id, $forwarding_id));
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
	 * @return int|array mail_forwarding.forwarding_id or error
	 */
	public function mail_forward_add($client_id = 0, $mail_forwarding = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($mail_forwarding['server_id']) ? $mail_forwarding['server_id'] : 0,
					'source' => isset($mail_forwarding['source']) ? $mail_forwarding['source'] : '',
					'destination' => isset($mail_forwarding['destination']) ? $mail_forwarding['destination'] : '',
					'active' => isset($mail_forwarding['active']) ? $mail_forwarding['active'] : 'n',
					'type' => 'forward'
			);
			return $this->client->mail_forward_add($this->session_id, $client_id, $params);
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
	 * @param array $mail_forwarding server_id, source, destination, active
	 * @return bool|array TRUE or error
	 */
	public function mail_forward_update($client_id = 0, $forwarding_id = 0, $mail_forwarding = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_forward_get($this->session_id, $forwarding_id);
			$params = array(
					'server_id' => isset($mail_forwarding['server_id']) ? $mail_forwarding['server_id'] : $record['server_id'],
					'source' => isset($mail_forwarding['source']) ? $mail_forwarding['source'] : $record['source'],
					'destination' => isset($mail_forwarding['destination']) ? $mail_forwarding['destination'] : $record['destination'],
					'active' => isset($mail_forwarding['active']) ? $mail_forwarding['active'] : $record['active'],
					'type' => 'forward'
			);
			$this->client->mail_forward_update($this->session_id, $client_id, $forwarding_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function mail_forward_delete($forwarding_id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_forward_delete($this->session_id, $forwarding_id);
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
	 * @return array mail_forwarding.* or error
	 */
	public function mail_catchall_get($forwarding_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_catchall_get($this->session_id, $forwarding_id));
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
	 * @return int|array mail_forwarding.forwarding_id or error
	 */
	public function mail_catchall_add($client_id = 0, $mail_forwarding = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($mail_forwarding['server_id']) ? $mail_forwarding['server_id'] : 0,
					'source' => isset($mail_forwarding['source']) ? $mail_forwarding['source'] : '',
					'destination' => isset($mail_forwarding['destination']) ? $mail_forwarding['destination'] : '',
					'active' => isset($mail_forwarding['active']) ? $mail_forwarding['active'] : 'n',
					'type' => 'catchall'
			);
			return $this->client->mail_catchall_add($this->session_id, $client_id, $params);
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
	 * @param array $mail_forwarding server_id, source, destination, active
	 * @return bool|array TRUE or error
	 */
	public function mail_catchall_update($client_id = 0, $forwarding_id = 0, $mail_forwarding = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_catchall_get($this->session_id, $forwarding_id);
			$params = array(
					'server_id' => isset($mail_forwarding['server_id']) ? $mail_forwarding['server_id'] : $record['server_id'],
					'source' => isset($mail_forwarding['source']) ? $mail_forwarding['source'] : $record['source'],
					'destination' => isset($mail_forwarding['destination']) ? $mail_forwarding['destination'] : $record['destination'],
					'active' => isset($mail_forwarding['active']) ? $mail_forwarding['active'] : $record['active'],
					'type' => 'catchall'
			);
			$this->client->mail_catchall_update($this->session_id, $client_id, $forwarding_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function mail_catchall_delete($forwarding_id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_catchall_delete($this->session_id, $forwarding_id);
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
	 * @return array mail_transport.* or error
	 */
	public function mail_transport_get($transport_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_transport_get($this->session_id, $transport_id));
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
	 * @return int|array mail_transport.transport_id or error
	 */
	public function mail_transport_add($client_id = 0, $mail_transport = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($mail_transport['server_id']) ? $mail_transport['server_id'] : 0,
					'domain' => isset($mail_transport['domain']) ? $mail_transport['domain'] : '',
					'transport' => isset($mail_transport['transport']) ? $mail_transport['transport'] : '',
					'sort_order' => isset($mail_transport['sort_order']) ? $mail_transport['sort_order'] : 5,
					'active' => isset($mail_transport['active']) ? $mail_transport['active'] : 'n'
			);
			return $this->client->mail_transport_add($this->session_id, $client_id, $params);
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
	 * @param array $mail_transport server_id, domain, transport, sort_order, active
	 * @return bool|array TRUE or error
	 */
	public function mail_transport_update($client_id = 0, $transport_id = 0, $mail_transport = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_transport_get($this->session_id, $transport_id);
			$params = array(
					'server_id' => isset($mail_transport['server_id']) ? $mail_transport['server_id'] : $record['server_id'],
					'domain' => isset($mail_transport['domain']) ? $mail_transport['domain'] : $record['domain'],
					'transport' => isset($mail_transport['transport']) ? $mail_transport['transport'] : $record['transport'],
					'sort_order' => isset($mail_transport['sort_order']) ? $mail_transport['sort_order'] : $record['sort_order'],
					'active' => isset($mail_transport['active']) ? $mail_transport['active'] : $record['active'],
			);
			$this->client->mail_transport_update($this->session_id, $client_id, $transport_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function mail_transport_delete($transport_id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_transport_delete($this->session_id, $transport_id);
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
	 * @return array mail_relay_recipient.* or error
	 */
	public function mail_relay_recipient_get($relay_recipient_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_relay_recipient_get($this->session_id, $relay_recipient_id));
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
	 * @return int|array mail_relay_recipient.relay_recipient_id or error
	 */
	public function mail_relay_recipient_add($client_id = 0, $mail_relay_recipient = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($mail_relay_recipient['server_id']) ? $mail_relay_recipient['server_id'] : 0,
					'source' => isset($mail_relay_recipient['source']) ? $mail_relay_recipient['source'] : 0,
					'access' => 'OK',
					'active' => isset($mail_relay_recipient['active']) ? $mail_relay_recipient['active'] : 'y',
			);
			return $this->client->mail_relay_recipient_add($this->session_id, $client_id, $params);
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
	 * @param array $mail_relay_recipient server_id,  source,  active
	 * @return int|array mail_relay_recipient.relay_recipient_id or error
	 */
	public function mail_relay_recipient_update($client_id = 0, $relay_recipient_id = 0, $mail_relay_recipient = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_relay_recipient_get($this->session_id, $relay_recipient_id);
			$params = array(
					'server_id' => isset($mail_relay_recipient['server_id']) ? $mail_relay_recipient['server_id'] : $record['server_id'],
					'source' => isset($mail_relay_recipient['source']) ? $mail_relay_recipient['source'] : $record['source'],
					'access' => 'OK',
					'active' => isset($mail_relay_recipient['active']) ? $mail_relay_recipient['active'] : $record['active']
			);
			$this->client->mail_relay_recipient_update($this->session_id, $client_id, $relay_recipient_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function mail_relay_recipient_delete($relay_recipient_id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_relay_recipient_delete($this->session_id, $relay_recipient_id);
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
	 * @return array spamfilter_wblist.* or error
	 */
	public function mail_spamfilter_whitelist_get($wblist_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_spamfilter_whitelist_get($this->session_id, $wblist_id));
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
	 * @return int|array spamfilter_wblist.wblist_id or error
	 */
	public function mail_spamfilter_whitelist_add($client_id = 0, $spamfilter_wblist = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($spamfilter_wblist['server_id']) ? $spamfilter_wblist['server_id'] : 0,
					'wb' => 'W',
					'rid' => isset($spamfilter_wblist['rid']) ? $spamfilter_wblist['rid'] : 0,
					'email' => isset($spamfilter_wblist['email']) ? $spamfilter_wblist['email'] : '',
					'priority' => isset($spamfilter_wblist['priority']) ? $spamfilter_wblist['priority'] : 5,
					'active' => isset($spamfilter_wblist['active']) ? $spamfilter_wblist['active'] : 'n'
			);
			return $this->client->mail_spamfilter_whitelist_add($this->session_id, $client_id, $params);
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
	 * @param array $spamfilter_wblist server_id,  rid, email,  priority,  active
	 * @return int|array spamfilter_wblist.wblist_id or error
	 */
	public function mail_spamfilter_whitelist_update($client_id = 0, $wblist_id = 0, $spamfilter_wblist = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_spamfilter_whitelist_get($this->session_id, $wblist_id);
			$params = array(
					'server_id' => isset($spamfilter_wblist['server_id']) ? $spamfilter_wblist['server_id'] : $record['server_id'],
					'wb' => 'W',
					'rid' => isset($spamfilter_wblist['rid']) ? $spamfilter_wblist['rid'] : $record['rid'],
					'email' => isset($spamfilter_wblist['email']) ? $spamfilter_wblist['email'] : $record['email'],
					'priority' => isset($spamfilter_wblist['priority']) ? $spamfilter_wblist['priority'] : $record['priority'],
					'active' => isset($spamfilter_wblist['active']) ? $spamfilter_wblist['active'] : $record['active']
			);
			$this->client->mail_spamfilter_whitelist_update($this->session_id, $client_id, $wblist_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function mail_spamfilter_whitelist_delete($wblist_id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_spamfilter_whitelist_delete($this->session_id, $wblist_id);
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
	 * @return array spamfilter_wblist.* or error
	 */
	public function mail_spamfilter_blacklist_get($wblist_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_spamfilter_blacklist_get($this->session_id, $wblist_id));
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
	 * @param array $spamfilter_wblist server_id,  rid, email,  priority,  active
	 * @return int|array spamfilter_wblist.wblist_id or error
	 */
	public function mail_spamfilter_blacklist_add($client_id = 0, $spamfilter_wblist = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($spamfilter_wblist['server_id']) ? $spamfilter_wblist['server_id'] : 0,
					'wb' => 'B',
					'rid' => isset($spamfilter_wblist['rid']) ? $spamfilter_wblist['rid'] : 0,
					'email' => isset($spamfilter_wblist['email']) ? $spamfilter_wblist['email'] : '',
					'priority' => isset($spamfilter_wblist['priority']) ? $spamfilter_wblist['priority'] : 5,
					'active' => isset($spamfilter_wblist['active']) ? $spamfilter_wblist['active'] : 'n'
			);
			return $this->client->mail_spamfilter_blacklist_add($this->session_id, $client_id, $params);
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
	 * @param array $spamfilter_wblist server_id,  rid, email,  priority,  active
	 * @return int|array spamfilter_wblist.wblist_id or error
	 */
	public function mail_spamfilter_blacklist_update($client_id = 0, $wblist_id = 0, $spamfilter_wblist = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_spamfilter_whitelist_get($this->session_id, $wblist_id);
			$params = array(
					'server_id' => isset($spamfilter_wblist['server_id']) ? $spamfilter_wblist['server_id'] : $record['server_id'],
					'wb' => 'B',
					'rid' => isset($spamfilter_wblist['rid']) ? $spamfilter_wblist['rid'] : $record['rid'],
					'email' => isset($spamfilter_wblist['email']) ? $spamfilter_wblist['email'] : $record['email'],
					'priority' => isset($spamfilter_wblist['priority']) ? $spamfilter_wblist['priority'] : $record['priority'],
					'active' => isset($spamfilter_wblist['active']) ? $spamfilter_wblist['active'] : $record['active']
			);
			$this->client->mail_spamfilter_blacklist_update($this->session_id, $client_id, $wblist_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function mail_spamfilter_blacklist_delete($wblist_id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_spamfilter_blacklist_delete($this->session_id, $wblist_id);
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
	 * @return array spamfilter_users.* or error
	 */
	public function mail_spamfilter_user_get($id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_spamfilter_user_get($this->session_id, $id));
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
	 * @return int|array spamfilter_users.id or error
	 */
	public function mail_spamfilter_user_add($client_id = 0, $spamfilter_users = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($spamfilter_users['server_id']) ? $spamfilter_users['server_id'] : 0,
					'priority' => isset($spamfilter_users['priority']) ? $spamfilter_users['priority'] : 7,
					'policy_id' => isset($spamfilter_users['policy_id']) ? $spamfilter_users['policy_id'] : 1,
					'email' => isset($spamfilter_users['email']) ? $spamfilter_users['email'] : '',
					'fullname' => isset($spamfilter_users['fullname']) ? $spamfilter_users['fullname'] : '',
					'local' => isset($spamfilter_users['local']) ? $spamfilter_users['local'] : 'Y',
			);
			return $this->client->mail_spamfilter_user_add($this->session_id, $client_id, $params);
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
	 * @param array $spamfilter_users server_id,  priority,  policy_id,  email,  fullname,  local
	 * @return bool|array TRUE or error
	 */
	public function mail_spamfilter_user_update($client_id = 0, $id = 0, $spamfilter_users = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_spamfilter_user_get($this->session_id, $id);
			$params = array(
					'server_id' => isset($spamfilter_users['server_id']) ? $spamfilter_users['server_id'] : $record['server_id'],
					'priority' => isset($spamfilter_users['priority']) ? $spamfilter_users['priority'] : $record['priority'],
					'policy_id' => isset($spamfilter_users['policy_id']) ? $spamfilter_users['policy_id'] : $record['policy_id'],
					'email' => isset($spamfilter_users['email']) ? $spamfilter_users['email'] : $record['email'],
					'fullname' => isset($spamfilter_users['fullname']) ? $spamfilter_users['fullname'] : $record['fullname'],
					'local' => isset($spamfilter_users['local']) ? $spamfilter_users['local'] : $record['local'],
			);
			$this->client->mail_spamfilter_user_update($this->session_id, $client_id, $id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function mail_spamfilter_user_delete($id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_spamfilter_user_delete($this->session_id, $id);
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
	 * @return array spamfilter_policy.* or error
	 */
	public function mail_policy_get($id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_policy_get($this->session_id, $id));
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
	 *                                 bypass_banned_checks,  bypass_header_checks,  spam_modifies_subj,
	 *                                 virus_quarantine_to, spam_quarantine_to,  banned_quarantine_to,
	 *                                 bad_header_quarantine_to,  clean_quarantine_to, other_quarantine_to,
	 *                                 spam_tag_level,  spam_tag2_level,  spam_kill_level,
	 *                                 spam_dsn_cutoff_level, spam_quarantine_cutoff_level,
	 *                                 addr_extension_virus,  addr_extension_spam,  addr_extension_banned,
	 *                                 addr_extension_bad_header,  warnvirusrecip,  warnbannedrecip,
	 *                                 warnbadhrecip,  newvirus_admin,  virus_admin, banned_admin,
	 *                                 bad_header_admin,  spam_admin,  spam_subject_tag,  spam_subject_tag2,
	 *                                 message_size_limit, banned_rulenames,  policyd_quota_in,
	 *                                 policyd_quota_in_period,  policyd_quota_out,  policyd_quota_out_period,
	 *                                 policyd_greylist
	 * @return int|array spamfilter_policy.id or error
	 */
	public function mail_policy_add($client_id = 0, $spamfilter_policy = array())
	{
		try
		{
			$this->login();
			$params = array(
					'policy_name' => isset($spamfilter_policy['policy_name']) ? $spamfilter_policy['policy_name'] : '',
					'virus_lover' => isset($spamfilter_policy['virus_lover']) ? $spamfilter_policy['virus_lover'] : 'N',
					'spam_lover' => isset($spamfilter_policy['spam_lover']) ? $spamfilter_policy['spam_lover'] : 'N',
					'banned_files_lover' => isset($spamfilter_policy['banned_files_lover']) ? $spamfilter_policy['banned_files_lover'] : 'N',
					'bad_header_lover' => isset($spamfilter_policy['bad_header_lover']) ? $spamfilter_policy['bad_header_lover'] : 'N',
					'bypass_virus_checks' => isset($spamfilter_policy['bypass_virus_checks']) ? $spamfilter_policy['bypass_virus_checks'] : 'N',
					'bypass_spam_checks' => isset($spamfilter_policy['bypass_spam_checks']) ? $spamfilter_policy['bypass_spam_checks'] : 'N',
					'bypass_banned_checks' => isset($spamfilter_policy['bypass_banned_checks']) ? $spamfilter_policy['bypass_banned_checks'] : 'N',
					'bypass_header_checks' => isset($spamfilter_policy['bypass_header_checks']) ? $spamfilter_policy['bypass_header_checks'] : 'N',
					'spam_modifies_subj' => isset($spamfilter_policy['spam_modifies_subj']) ? $spamfilter_policy['spam_modifies_subj'] : 'N',
					'virus_quarantine_to' => isset($spamfilter_policy['virus_quarantine_to']) ? $spamfilter_policy['virus_quarantine_to'] : '',
					'spam_quarantine_to' => isset($spamfilter_policy['spam_quarantine_to']) ? $spamfilter_policy['spam_quarantine_to'] : '',
					'banned_quarantine_to' => isset($spamfilter_policy['banned_quarantine_to']) ? $spamfilter_policy['banned_quarantine_to'] : '',
					'bad_header_quarantine_to' => isset($spamfilter_policy['bad_header_quarantine_to']) ? $spamfilter_policy['bad_header_quarantine_to'] : '',
					'clean_quarantine_to' => isset($spamfilter_policy['clean_quarantine_to']) ? $spamfilter_policy['clean_quarantine_to'] : '',
					'other_quarantine_to' => isset($spamfilter_policy['other_quarantine_to']) ? $spamfilter_policy['other_quarantine_to'] : '',
					'spam_tag_level' => isset($spamfilter_policy['spam_tag_level']) ? $spamfilter_policy['spam_tag_level'] : 0,
					'spam_tag2_level' => isset($spamfilter_policy['spam_tag2_level']) ? $spamfilter_policy['spam_tag2_level'] : 0,
					'spam_kill_level' => isset($spamfilter_policy['spam_kill_level']) ? $spamfilter_policy['spam_kill_level'] : 0,
					'spam_dsn_cutoff_level' => isset($spamfilter_policy['spam_dsn_cutoff_level']) ? $spamfilter_policy['spam_dsn_cutoff_level'] : 0,
					'spam_quarantine_cutoff_level' => isset($spamfilter_policy['spam_quarantine_cutoff_level']) ? $spamfilter_policy['spam_quarantine_cutoff_level'] : 0,
					'addr_extension_virus' => isset($spamfilter_policy['addr_extension_virus']) ? $spamfilter_policy['addr_extension_virus'] : '',
					'addr_extension_spam' => isset($spamfilter_policy['addr_extension_spam']) ? $spamfilter_policy['addr_extension_spam'] : '',
					'addr_extension_banned' => isset($spamfilter_policy['addr_extension_banned']) ? $spamfilter_policy['addr_extension_banned'] : '',
					'addr_extension_bad_header' => isset($spamfilter_policy['addr_extension_bad_header']) ? $spamfilter_policy['addr_extension_bad_header'] : '',
					'warnvirusrecip' => isset($spamfilter_policy['warnvirusrecip']) ? $spamfilter_policy['warnvirusrecip'] : 'N',
					'warnbannedrecip' => isset($spamfilter_policy['warnbannedrecip']) ? $spamfilter_policy['warnbannedrecip'] : 'N',
					'warnbadhrecip' => isset($spamfilter_policy['warnbadhrecip']) ? $spamfilter_policy['warnbadhrecip'] : 'N',
					'newvirus_admin' => isset($spamfilter_policy['newvirus_admin']) ? $spamfilter_policy['newvirus_admin'] : '',
					'virus_admin' => isset($spamfilter_policy['virus_admin']) ? $spamfilter_policy['virus_admin'] : '',
					'banned_admin' => isset($spamfilter_policy['banned_admin']) ? $spamfilter_policy['banned_admin'] : '',
					'bad_header_admin' => isset($spamfilter_policy['bad_header_admin']) ? $spamfilter_policy['bad_header_admin'] : '',
					'spam_admin' => isset($spamfilter_policy['spam_admin']) ? $spamfilter_policy['spam_admin'] : '',
					'spam_subject_tag' => isset($spamfilter_policy['spam_subject_tag']) ? $spamfilter_policy['spam_subject_tag'] : '',
					'spam_subject_tag2' => isset($spamfilter_policy['spam_subject_tag2']) ? $spamfilter_policy['spam_subject_tag2'] : '',
					'message_size_limit' => isset($spamfilter_policy['message_size_limit']) ? $spamfilter_policy['message_size_limit'] : 0,
					'banned_rulenames' => isset($spamfilter_policy['banned_rulenames']) ? $spamfilter_policy['banned_rulenames'] : '',
					'policyd_quota_in	' => isset($spamfilter_policy['policyd_quota_in	']) ? $spamfilter_policy['policyd_quota_in	'] : -1,
					'policyd_quota_in_period' => isset($spamfilter_policy['policyd_quota_in_period']) ? $spamfilter_policy['policyd_quota_in_period'] : 24,
					'policyd_quota_out' => isset($spamfilter_policy['policyd_quota_out']) ? $spamfilter_policy['policyd_quota_out'] : -1,
					'policyd_quota_out_period' => isset($spamfilter_policy['policyd_quota_out_period']) ? $spamfilter_policy['policyd_quota_out_period'] : 24,
					'policyd_greylist' => isset($spamfilter_policy['policyd_greylist']) ? $spamfilter_policy['policyd_greylist'] : 'N',
			);
			return $this->client->mail_policy_add($this->session_id, $client_id, $params);
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
	 * @param array $spamfilter_policy policy_name,  virus_lover,  spam_lover,  banned_files_lover,
	 *                                 bad_header_lover,  bypass_virus_checks, bypass_spam_checks,
	 *                                 bypass_banned_checks,  bypass_header_checks,  spam_modifies_subj,
	 *                                 virus_quarantine_to, spam_quarantine_to,  banned_quarantine_to,
	 *                                 bad_header_quarantine_to,  clean_quarantine_to, other_quarantine_to,
	 *                                 spam_tag_level,  spam_tag2_level,  spam_kill_level,
	 *                                 spam_dsn_cutoff_level, spam_quarantine_cutoff_level,
	 *                                 addr_extension_virus,  addr_extension_spam,  addr_extension_banned,
	 *                                 addr_extension_bad_header,  warnvirusrecip,  warnbannedrecip,
	 *                                 warnbadhrecip,  newvirus_admin,  virus_admin, banned_admin,
	 *                                 bad_header_admin,  spam_admin,  spam_subject_tag,  spam_subject_tag2,
	 *                                 message_size_limit, banned_rulenames,  policyd_quota_in,
	 *                                 policyd_quota_in_period,  policyd_quota_out,  policyd_quota_out_period,
	 *                                 policyd_greylist
	 * @return bool|array TRUE or error
	 */
	public function mail_policy_update($client_id = 0, $id = 0, $spamfilter_policy = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_policy_get($this->session_id, $id);
			$params = array(
					'policy_name' => isset($spamfilter_policy['policy_name']) ? $spamfilter_policy['policy_name'] : $record['policy_name'],
					'virus_lover' => isset($spamfilter_policy['virus_lover']) ? $spamfilter_policy['virus_lover'] : $record['virus_lover'],
					'spam_lover' => isset($spamfilter_policy['spam_lover']) ? $spamfilter_policy['spam_lover'] : $record['spam_lover'],
					'banned_files_lover' => isset($spamfilter_policy['banned_files_lover']) ? $spamfilter_policy['banned_files_lover'] : $record['banned_files_lover'],
					'bad_header_lover' => isset($spamfilter_policy['bad_header_lover']) ? $spamfilter_policy['bad_header_lover'] : $record['bad_header_lover'],
					'bypass_virus_checks' => isset($spamfilter_policy['bypass_virus_checks']) ? $spamfilter_policy['bypass_virus_checks'] : $record['bypass_virus_checks'],
					'bypass_spam_checks' => isset($spamfilter_policy['bypass_spam_checks']) ? $spamfilter_policy['bypass_spam_checks'] : $record['bypass_spam_checks'],
					'bypass_banned_checks' => isset($spamfilter_policy['bypass_banned_checks']) ? $spamfilter_policy['bypass_banned_checks'] : $record['bypass_banned_checks'],
					'bypass_header_checks' => isset($spamfilter_policy['bypass_header_checks']) ? $spamfilter_policy['bypass_header_checks'] : $record['bypass_header_checks'],
					'spam_modifies_subj' => isset($spamfilter_policy['spam_modifies_subj']) ? $spamfilter_policy['spam_modifies_subj'] : $record['spam_modifies_subj'],
					'virus_quarantine_to' => isset($spamfilter_policy['virus_quarantine_to']) ? $spamfilter_policy['virus_quarantine_to'] : $record['virus_quarantine_to'],
					'spam_quarantine_to' => isset($spamfilter_policy['spam_quarantine_to']) ? $spamfilter_policy['spam_quarantine_to'] : $record['spam_quarantine_to'],
					'banned_quarantine_to' => isset($spamfilter_policy['banned_quarantine_to']) ? $spamfilter_policy['banned_quarantine_to'] : $record['banned_quarantine_to'],
					'bad_header_quarantine_to' => isset($spamfilter_policy['bad_header_quarantine_to']) ? $spamfilter_policy['bad_header_quarantine_to'] : $record['bad_header_quarantine_to'],
					'clean_quarantine_to' => isset($spamfilter_policy['clean_quarantine_to']) ? $spamfilter_policy['clean_quarantine_to'] : $record['clean_quarantine_to'],
					'other_quarantine_to' => isset($spamfilter_policy['other_quarantine_to']) ? $spamfilter_policy['other_quarantine_to'] : $record['other_quarantine_to'],
					'spam_tag_level' => isset($spamfilter_policy['spam_tag_level']) ? $spamfilter_policy['spam_tag_level'] : $record['spam_tag_level'],
					'spam_tag2_level' => isset($spamfilter_policy['spam_tag2_level']) ? $spamfilter_policy['spam_tag2_level'] : $record['spam_tag2_level'],
					'spam_kill_level' => isset($spamfilter_policy['spam_kill_level']) ? $spamfilter_policy['spam_kill_level'] : $record['spam_kill_level'],
					'spam_dsn_cutoff_level' => isset($spamfilter_policy['spam_dsn_cutoff_level']) ? $spamfilter_policy['spam_dsn_cutoff_level'] : $record['spam_dsn_cutoff_level'],
					'spam_quarantine_cutoff_level' => isset($spamfilter_policy['spam_quarantine_cutoff_level']) ? $spamfilter_policy['spam_quarantine_cutoff_level'] : $record['spam_quarantine_cutoff_level'],
					'addr_extension_virus' => isset($spamfilter_policy['addr_extension_virus']) ? $spamfilter_policy['addr_extension_virus'] : $record['addr_extension_virus'],
					'addr_extension_spam' => isset($spamfilter_policy['addr_extension_spam']) ? $spamfilter_policy['addr_extension_spam'] : $record['addr_extension_spam'],
					'addr_extension_banned' => isset($spamfilter_policy['addr_extension_banned']) ? $spamfilter_policy['addr_extension_banned'] : $record['addr_extension_banned'],
					'addr_extension_bad_header' => isset($spamfilter_policy['addr_extension_bad_header']) ? $spamfilter_policy['addr_extension_bad_header'] : $record['addr_extension_bad_header'],
					'warnvirusrecip' => isset($spamfilter_policy['warnvirusrecip']) ? $spamfilter_policy['warnvirusrecip'] : $record['warnvirusrecip'],
					'warnbannedrecip' => isset($spamfilter_policy['warnbannedrecip']) ? $spamfilter_policy['warnbannedrecip'] : $record['warnbannedrecip'],
					'warnbadhrecip' => isset($spamfilter_policy['warnbadhrecip']) ? $spamfilter_policy['warnbadhrecip'] : $record['warnbadhrecip'],
					'newvirus_admin' => isset($spamfilter_policy['newvirus_admin']) ? $spamfilter_policy['newvirus_admin'] : $record['newvirus_admin'],
					'virus_admin' => isset($spamfilter_policy['virus_admin']) ? $spamfilter_policy['virus_admin'] : $record['virus_admin'],
					'banned_admin' => isset($spamfilter_policy['banned_admin']) ? $spamfilter_policy['banned_admin'] : $record['banned_admin'],
					'bad_header_admin' => isset($spamfilter_policy['bad_header_admin']) ? $spamfilter_policy['bad_header_admin'] : $record['bad_header_admin'],
					'spam_admin' => isset($spamfilter_policy['spam_admin']) ? $spamfilter_policy['spam_admin'] : $record['spam_admin'],
					'spam_subject_tag' => isset($spamfilter_policy['spam_subject_tag']) ? $spamfilter_policy['spam_subject_tag'] : $record['spam_subject_tag'],
					'spam_subject_tag2' => isset($spamfilter_policy['spam_subject_tag2']) ? $spamfilter_policy['spam_subject_tag2'] : $record['spam_subject_tag2'],
					'message_size_limit' => isset($spamfilter_policy['message_size_limit']) ? $spamfilter_policy['message_size_limit'] : $record['message_size_limit'],
					'banned_rulenames' => isset($spamfilter_policy['banned_rulenames']) ? $spamfilter_policy['banned_rulenames'] : $record['banned_rulenames'],
					'policyd_quota_in	' => isset($spamfilter_policy['policyd_quota_in	']) ? $spamfilter_policy['policyd_quota_in	'] : $record['policyd_quota_in'],
					'policyd_quota_in_period' => isset($spamfilter_policy['policyd_quota_in_period']) ? $spamfilter_policy['policyd_quota_in_period'] : $record['policyd_quota_in_period'],
					'policyd_quota_out' => isset($spamfilter_policy['policyd_quota_out']) ? $spamfilter_policy['policyd_quota_out'] : $record['policyd_quota_out'],
					'policyd_quota_out_period' => isset($spamfilter_policy['policyd_quota_out_period']) ? $spamfilter_policy['policyd_quota_out_period'] : $record['policyd_quota_out_period'],
					'policyd_greylist' => isset($spamfilter_policy['policyd_greylist']) ? $spamfilter_policy['policyd_greylist'] : $record['policyd_greylist']
			);
			$this->client->mail_policy_update($this->session_id, $client_id, $id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function mail_policy_delete($id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_policy_delete($this->session_id, $id);
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
	 * @return array mail_get.* or error
	 */
	public function mail_fetchmail_get($mailget_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_fetchmail_get($this->session_id, $mailget_id));
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
	 * @param array $mail_get server_id, type, source_server, source_username, source_password, source_delete,
	 *                        source_read_all, destination, active
	 * @return int|array mail_get.mailget_id or error
	 */
	public function mail_fetchmail_add($client_id = 0, $mail_get = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($mail_get['server_id']) ? $mail_get['server_id'] : 0,
					'type' => isset($mail_get['type']) ? $mail_get['type'] : '',
					'source_server' => isset($mail_get['source_server']) ? $mail_get['source_server'] : '',
					'source_username' => isset($mail_get['source_username']) ? $mail_get['source_username'] : '',
					'source_password' => isset($mail_get['source_password']) ? $mail_get['source_password'] : '',
					'source_delete' => isset($mail_get['source_delete']) ? $mail_get['source_delete'] : 'y',
					'source_read_all' => isset($mail_get['source_read_all']) ? $mail_get['source_read_all'] : 'y',
					'destination' => isset($mail_get['destination']) ? $mail_get['destination'] : '',
					'active' => isset($mail_get['active']) ? $mail_get['active'] : 'y',
			);
			return $this->client->mail_fetchmail_add($this->session_id, $client_id, $params);
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
	 * @param array $mail_get server_id, type, source_server, source_username, source_password, source_delete,
	 *                        source_read_all, destination, active
	 * @return bool|array TRUE or error
	 */
	public function mail_fetchmail_update($client_id = 0, $mailget_id = 0, $mail_get = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_fetchmail_get($this->session_id, $mailget_id);
			$params = array(
					'server_id' => isset($mail_get['server_id']) ? $mail_get['server_id'] : $record['server_id'],
					'type' => isset($mail_get['type']) ? $mail_get['type'] : $record['type'],
					'source_server' => isset($mail_get['source_server']) ? $mail_get['source_server'] : $record['source_server'],
					'source_username' => isset($mail_get['source_username']) ? $mail_get['source_username'] : $record['source_username'],
					'source_password' => isset($mail_get['source_password']) ? $mail_get['source_password'] : $record['source_password'],
					'source_delete' => isset($mail_get['source_delete']) ? $mail_get['source_delete'] : $record['source_delete'],
					'source_read_all' => isset($mail_get['source_read_all']) ? $mail_get['source_read_all'] : $record['source_read_all'],
					'destination' => isset($mail_get['destination']) ? $mail_get['destination'] : $record['destination'],
					'active' => isset($mail_get['active']) ? $mail_get['active'] : $record['active'],
			);
			$this->client->mail_fetchmail_update($this->session_id, $client_id, $mailget_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function mail_fetchmail_delete($mailget_id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_fetchmail_delete($this->session_id, $mailget_id);
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
	 * @return array mail_access.* or error
	 */
	public function mail_whitelist_get($access_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_whitelist_get($this->session_id, $access_id));
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
	 * @return int|array mail_access.access_id or error
	 */
	public function mail_whitelist_add($client_id = 0, $mail_access = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($mail_access['server_id']) ? $mail_access['server_id'] : 0,
					'source' => isset($mail_access['source']) ? $mail_access['source'] : '',
					'access' => 'OK',
					'type' => isset($mail_access['type']) ? $mail_access['type'] : 'recipient',
					'active' => isset($mail_access['active']) ? $mail_access['active'] : 'y',
			);
			return $this->client->mail_whitelist_add($this->session_id, $client_id, $params);
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
	 * @param array $mail_access server_id,  source,  type,  active
	 * @return bool|array TRUE or error
	 */
	public function mail_whitelist_update($client_id = 0, $access_id = 0, $mail_access = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_whitelist_get($this->session_id, $access_id);
			$params = array(
					'server_id' => isset($mail_access['server_id']) ? $mail_access['server_id'] : $record['server_id'],
					'source' => isset($mail_access['source']) ? $mail_access['source'] : $record['source'],
					'access' => 'OK',
					'type' => isset($mail_access['type']) ? $mail_access['type'] : $record['type'],
					'active' => isset($mail_access['active']) ? $mail_access['active'] : $record['active'],
			);
			$this->client->mail_whitelist_update($this->session_id, $client_id, $access_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function mail_whitelist_delete($access_id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_whitelist_delete($this->session_id, $access_id);
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
	 * @return array mail_access.* or error
	 */
	public function mail_blacklist_get($access_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_blacklist_get($this->session_id, $access_id));
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
	 * @return int|array mail_access.access_id or error
	 */
	public function mail_blacklist_add($client_id = 0, $mail_access = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($mail_access['server_id']) ? $mail_access['server_id'] : 0,
					'source' => isset($mail_access['source']) ? $mail_access['source'] : '',
					'access' => 'REJECT',
					'type' => isset($mail_access['type']) ? $mail_access['type'] : 'recipient',
					'active' => isset($mail_access['active']) ? $mail_access['active'] : 'y',
			);
			return $this->client->mail_blacklist_add($this->session_id, $client_id, $params);
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
	 * @param array $mail_access server_id,  source,  type,  active
	 * @return bool|array TRUE or error
	 */
	public function mail_blacklist_update($client_id = 0, $access_id = 0, $mail_access = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_blacklist_get($this->session_id, $access_id);
			$params = array(
					'server_id' => isset($mail_access['server_id']) ? $mail_access['server_id'] : $record['server_id'],
					'source' => isset($mail_access['source']) ? $mail_access['source'] : $record['source'],
					'access' => 'REJECT',
					'type' => isset($mail_access['type']) ? $mail_access['type'] : $record['type'],
					'active' => isset($mail_access['active']) ? $mail_access['active'] : $record['active'],
			);
			$this->client->mail_blacklist_update($this->session_id, $client_id, $access_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function mail_blacklist_delete($access_id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_blacklist_delete($this->session_id, $access_id);
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
	 * @return array mail_content_filter.* or error
	 */
	public function mail_filter_get($content_filter_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_filter_get($this->session_id, $content_filter_id));
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
	 * @return int|array mail_content_filter.content_filter_id or error
	 */
	public function mail_filter_add($client_id = 0, $mail_content_filter = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($mail_content_filter['server_id']) ? $mail_content_filter['server_id'] : 0,
					'type' => isset($mail_content_filter['type']) ? $mail_content_filter['type'] : '',
					'pattern' => isset($mail_content_filter['pattern']) ? $mail_content_filter['pattern'] : '',
					'data' => isset($mail_content_filter['data']) ? $mail_content_filter['data'] : '',
					'action' => isset($mail_content_filter['action']) ? $mail_content_filter['action'] : '',
					'active' => isset($mail_content_filter['active']) ? $mail_content_filter['active'] : 'n',
			);
			return $this->client->mail_filter_add($this->session_id, $client_id, $params);
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
	 * @param array $mail_content_filter server_id,  type,  pattern,  data,  action,  active
	 * @return bool|array TRUE or error
	 */
	public function mail_filter_update($client_id = 0, $content_filter_id = 0, $mail_content_filter = array())
	{
		try
		{
			$this->login();
			$record = $this->client->mail_filter_get($this->session_id, $content_filter_id);
			$params = array(
					'server_id' => isset($mail_content_filter['server_id']) ? $mail_content_filter['server_id'] : $record['server_id'],
					'type' => isset($mail_content_filter['type']) ? $mail_content_filter['type'] : $record['type'],
					'pattern' => isset($mail_content_filter['pattern']) ? $mail_content_filter['pattern'] : $record['pattern'],
					'data' => isset($mail_content_filter['data']) ? $mail_content_filter['data'] : $record['data'],
					'action' => isset($mail_content_filter['action']) ? $mail_content_filter['action'] : $record['action'],
					'active' => isset($mail_content_filter['active']) ? $mail_content_filter['active'] : $record['active'],
			);
			$this->client->mail_filter_update($this->session_id, $client_id, $content_filter_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function mail_filter_delete($content_filter_id = 0)
	{
		try
		{
			$this->login();
			$this->client->mail_filter_delete($this->session_id, $content_filter_id);
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
	 * @return array mail_domain.* or error
	 */
	public function mail_domain_get_by_domain($domain = '')
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->mail_domain_get_by_domain($this->session_id, $domain));
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
	 * @return bool|mixed TRUE or error
	 */
	public function mail_domain_set_status($domain_id = 0, $status = 'inactive')
	{
		try
		{
			$this->login();
			$this->client->mail_domain_set_status($this->session_id, $domain_id, $status);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get one record from VServer > OpenVZ OS Template
	 *
	 * @param int $ostemplate_id
	 * @return array openvz_ostemplate.* or error
	 */
	public function openvz_ostemplate_get($ostemplate_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->openvz_ostemplate_get($this->session_id, $ostemplate_id));
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
	 * @param array $openvz_ostemplate server_id,  template_name,  template_file,  allservers,  active,
	 *                                 description
	 * @return int|array openvz_ostemplate.ostemplate_id or error
	 */
	public function openvz_ostemplate_add($client_id = 0, $openvz_ostemplate = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($openvz_ostemplate['server_id']) ? $openvz_ostemplate['server_id'] : 0,
					'template_name' => isset($openvz_ostemplate['template_name']) ? $openvz_ostemplate['template_name'] : '',
					'template_file' => isset($openvz_ostemplate['template_file']) ? $openvz_ostemplate['template_file'] : '',
					'allservers' => isset($openvz_ostemplate['allservers']) ? $openvz_ostemplate['allservers'] : 'n',
					'active' => isset($openvz_ostemplate['active']) ? $openvz_ostemplate['active'] : 'n',
					'description' => isset($openvz_ostemplate['description']) ? $openvz_ostemplate['description'] : ''
			);
			return $this->client->openvz_ostemplate_add($this->session_id, $client_id, $params);
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
	 * @param array $openvz_ostemplate server_id,  template_name,  template_file,  allservers,  active,
	 *                                 description
	 * @return bool|array TRUE or error
	 */
	public function openvz_ostemplate_update($client_id = 0, $ostemplate_id = 0, $openvz_ostemplate = array())
	{
		try
		{
			$this->login();
			$record = $this->client->openvz_ostemplate_get($this->session_id, $ostemplate_id);
			$params = array(
					'server_id' => isset($openvz_ostemplate['server_id']) ? $openvz_ostemplate['server_id'] : $record['server_id'],
					'template_name' => isset($openvz_ostemplate['template_name']) ? $openvz_ostemplate['template_name'] : $record['template_name'],
					'template_file' => isset($openvz_ostemplate['template_file']) ? $openvz_ostemplate['template_file'] : $record['template_file'],
					'allservers' => isset($openvz_ostemplate['allservers']) ? $openvz_ostemplate['allservers'] : $record['allservers'],
					'active' => isset($openvz_ostemplate['active']) ? $openvz_ostemplate['active'] : $record['active'],
					'description' => isset($openvz_ostemplate['description']) ? $openvz_ostemplate['description'] : $record['description']
			);
			$this->client->openvz_ostemplate_update($this->session_id, $client_id, $ostemplate_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function openvz_ostemplate_delete($ostemplate_id = 0)
	{
		try
		{
			$this->login();
			$this->client->openvz_ostemplate_delete($this->session_id, $ostemplate_id);
			return TRUE;
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
	 * @return array openvz_template.* or error
	 */
	public function openvz_template_get($template_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->openvz_template_get($this->session_id, $template_id));
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
	 * @param array $openvz_template   template_name,  diskspace,  traffic,  bandwidth,  ram,  ram_burst,
	 *                                 cpu_units,  cpu_num,  cpu_limit,  io_priority, active,  description,
	 *                                 numproc,  numtcpsock,  numothersock,  vmguarpages,  kmemsize,  tcpsndbuf,
	 *                                 tcprcvbuf, othersockbuf,  dgramrcvbuf,  oomguarpages,  privvmpages,
	 *                                 lockedpages,  shmpages,  physpages,  numfile, avnumproc,  numflock,
	 *                                 numpty,  numsiginfo,  dcachesize,  numiptent,  swappages,  hostname,
	 *                                 nameserver, create_dns,  capability
	 * @return int|array openvz_template.template_id or error
	 */
	public function openvz_template_add($client_id = 0, $openvz_template = array())
	{
		try
		{
			$this->login();
			$params = array(
					'template_name' => isset($openvz_template['template_name']) ? $openvz_template['template_name'] : '',
					'diskspace' => isset($openvz_template['diskspace']) ? $openvz_template['diskspace'] : 0,
					'traffic' => isset($openvz_template['traffic']) ? $openvz_template['traffic'] : -1,
					'bandwidth' => isset($openvz_template['bandwidth']) ? $openvz_template['bandwidth'] : -1,
					'ram' => isset($openvz_template['ram']) ? $openvz_template['ram'] : 0,
					'ram_burst' => isset($openvz_template['ram_burst']) ? $openvz_template['ram_burst'] : 0,
					'cpu_units' => isset($openvz_template['cpu_units']) ? $openvz_template['cpu_units'] : 1000,
					'cpu_num' => isset($openvz_template['cpu_num']) ? $openvz_template['cpu_num'] : 4,
					'cpu_limit' => isset($openvz_template['cpu_limit']) ? $openvz_template['cpu_limit'] : 400,
					'io_priority' => isset($openvz_template['io_priority']) ? $openvz_template['io_priority'] : 4,
					'active' => isset($openvz_template['active']) ? $openvz_template['active'] : 'y',
					'description' => isset($openvz_template['description']) ? $openvz_template['description'] : '',
					'numproc' => isset($openvz_template['numproc']) ? $openvz_template['numproc'] : '',
					'numtcpsock' => isset($openvz_template['numtcpsock']) ? $openvz_template['numtcpsock'] : '',
					'numothersock' => isset($openvz_template['numothersock']) ? $openvz_template['numothersock'] : '',
					'vmguarpages' => isset($openvz_template['vmguarpages']) ? $openvz_template['vmguarpages'] : '',
					'kmemsize' => isset($openvz_template['kmemsize']) ? $openvz_template['kmemsize'] : '',
					'tcpsndbuf' => isset($openvz_template['tcpsndbuf']) ? $openvz_template['tcpsndbuf'] : '',
					'tcprcvbuf' => isset($openvz_template['tcprcvbuf']) ? $openvz_template['tcprcvbuf'] : '',
					'othersockbuf' => isset($openvz_template['othersockbuf']) ? $openvz_template['othersockbuf'] : '',
					'dgramrcvbuf' => isset($openvz_template['dgramrcvbuf']) ? $openvz_template['dgramrcvbuf'] : '',
					'oomguarpages' => isset($openvz_template['oomguarpages']) ? $openvz_template['oomguarpages'] : '',
					'privvmpages' => isset($openvz_template['privvmpages']) ? $openvz_template['privvmpages'] : '',
					'lockedpages' => isset($openvz_template['lockedpages']) ? $openvz_template['lockedpages'] : '',
					'shmpages' => isset($openvz_template['shmpages']) ? $openvz_template['shmpages'] : '',
					'physpages' => isset($openvz_template['physpages']) ? $openvz_template['physpages'] : '',
					'numfile' => isset($openvz_template['numfile']) ? $openvz_template['numfile'] : '',
					'avnumproc' => isset($openvz_template['avnumproc']) ? $openvz_template['avnumproc'] : '',
					'numflock' => isset($openvz_template['numflock']) ? $openvz_template['numflock'] : '',
					'numpty' => isset($openvz_template['numpty']) ? $openvz_template['numpty'] : '',
					'numsiginfo' => isset($openvz_template['numsiginfo']) ? $openvz_template['numsiginfo'] : '',
					'dcachesize' => isset($openvz_template['dcachesize']) ? $openvz_template['dcachesize'] : '',
					'numiptent' => isset($openvz_template['numiptent']) ? $openvz_template['numiptent'] : '',
					'swappages' => isset($openvz_template['swappages']) ? $openvz_template['swappages'] : '',
					'hostname' => isset($openvz_template['hostname']) ? $openvz_template['hostname'] : '',
					'nameserver' => isset($openvz_template['nameserver']) ? $openvz_template['nameserver'] : '',
					'create_dns' => isset($openvz_template['create_dns']) ? $openvz_template['create_dns'] : 'n',
					'capability' => isset($openvz_template['capability']) ? $openvz_template['capability'] : '',
			);
			$this->client->openvz_template_add($this->session_id, $client_id, $params);
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
	 * @param array $openvz_template   template_name,  diskspace,  traffic,  bandwidth,  ram,  ram_burst,
	 *                                 cpu_units,  cpu_num,  cpu_limit,  io_priority, active,  description,
	 *                                 numproc,  numtcpsock,  numothersock,  vmguarpages,  kmemsize,  tcpsndbuf,
	 *                                 tcprcvbuf, othersockbuf,  dgramrcvbuf,  oomguarpages,  privvmpages,
	 *                                 lockedpages,  shmpages,  physpages,  numfile, avnumproc,  numflock,
	 *                                 numpty,  numsiginfo,  dcachesize,  numiptent,  swappages,  hostname,
	 *                                 nameserver, create_dns,  capability
	 * @return int|array openvz_template.template_id or error
	 */
	public function openvz_template_update($client_id = 0, $template_id = 0, $openvz_template = array())
	{
		try
		{
			$this->login();
			$record = $this->client->openvz_template_get($this->session_id, $template_id);
			$params = array(
					'template_name' => isset($openvz_template['template_name']) ? $openvz_template['template_name'] : $record['template_name'],
					'diskspace' => isset($openvz_template['diskspace']) ? $openvz_template['diskspace'] : $record['diskspace'],
					'traffic' => isset($openvz_template['traffic']) ? $openvz_template['traffic'] : $record['traffic'],
					'bandwidth' => isset($openvz_template['bandwidth']) ? $openvz_template['bandwidth'] : $record['bandwidth'],
					'ram' => isset($openvz_template['ram']) ? $openvz_template['ram'] : $record['ram'],
					'ram_burst' => isset($openvz_template['ram_burst']) ? $openvz_template['ram_burst'] : $record['ram_burst'],
					'cpu_units' => isset($openvz_template['cpu_units']) ? $openvz_template['cpu_units'] : $record['cpu_units'],
					'cpu_num' => isset($openvz_template['cpu_num']) ? $openvz_template['cpu_num'] : $record['cpu_num'],
					'cpu_limit' => isset($openvz_template['cpu_limit']) ? $openvz_template['cpu_limit'] : $record['cpu_limit'],
					'io_priority' => isset($openvz_template['io_priority']) ? $openvz_template['io_priority'] : $record['io_priority'],
					'active' => isset($openvz_template['active']) ? $openvz_template['active'] : $record['active'],
					'description' => isset($openvz_template['description']) ? $openvz_template['description'] : $record['description'],
					'numproc' => isset($openvz_template['numproc']) ? $openvz_template['numproc'] : $record['numproc'],
					'numtcpsock' => isset($openvz_template['numtcpsock']) ? $openvz_template['numtcpsock'] : $record['numtcpsock'],
					'numothersock' => isset($openvz_template['numothersock']) ? $openvz_template['numothersock'] : $record['numothersock'],
					'vmguarpages' => isset($openvz_template['vmguarpages']) ? $openvz_template['vmguarpages'] : $record['vmguarpages'],
					'kmemsize' => isset($openvz_template['kmemsize']) ? $openvz_template['kmemsize'] : $record['kmemsize'],
					'tcpsndbuf' => isset($openvz_template['tcpsndbuf']) ? $openvz_template['tcpsndbuf'] : $record['tcpsndbuf'],
					'tcprcvbuf' => isset($openvz_template['tcprcvbuf']) ? $openvz_template['tcprcvbuf'] : $record['tcprcvbuf'],
					'othersockbuf' => isset($openvz_template['othersockbuf']) ? $openvz_template['othersockbuf'] : $record['othersockbuf'],
					'dgramrcvbuf' => isset($openvz_template['dgramrcvbuf']) ? $openvz_template['dgramrcvbuf'] : $record['dgramrcvbuf'],
					'oomguarpages' => isset($openvz_template['oomguarpages']) ? $openvz_template['oomguarpages'] : $record['oomguarpages'],
					'privvmpages' => isset($openvz_template['privvmpages']) ? $openvz_template['privvmpages'] : $record['privvmpages'],
					'lockedpages' => isset($openvz_template['lockedpages']) ? $openvz_template['lockedpages'] : $record['lockedpages'],
					'shmpages' => isset($openvz_template['shmpages']) ? $openvz_template['shmpages'] : $record['shmpages'],
					'physpages' => isset($openvz_template['physpages']) ? $openvz_template['physpages'] : $record['physpages'],
					'numfile' => isset($openvz_template['numfile']) ? $openvz_template['numfile'] : $record['numfile'],
					'avnumproc' => isset($openvz_template['avnumproc']) ? $openvz_template['avnumproc'] : $record['avnumproc'],
					'numflock' => isset($openvz_template['numflock']) ? $openvz_template['numflock'] : $record['numflock'],
					'numpty' => isset($openvz_template['numpty']) ? $openvz_template['numpty'] : $record['numpty'],
					'numsiginfo' => isset($openvz_template['numsiginfo']) ? $openvz_template['numsiginfo'] : $record['numsiginfo'],
					'dcachesize' => isset($openvz_template['dcachesize']) ? $openvz_template['dcachesize'] : $record['dcachesize'],
					'numiptent' => isset($openvz_template['numiptent']) ? $openvz_template['numiptent'] : $record['numiptent'],
					'swappages' => isset($openvz_template['swappages']) ? $openvz_template['swappages'] : $record['swappages'],
					'hostname' => isset($openvz_template['hostname']) ? $openvz_template['hostname'] : $record['hostname'],
					'nameserver' => isset($openvz_template['nameserver']) ? $openvz_template['nameserver'] : $record['nameserver'],
					'create_dns' => isset($openvz_template['create_dns']) ? $openvz_template['create_dns'] : $record['create_dns'],
					'capability' => isset($openvz_template['capability']) ? $openvz_template['capability'] : $record['capability'],
			);
			$this->client->openvz_template_update($this->session_id, $client_id, $template_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function openvz_template_delete($template_id = 0)
	{
		try
		{
			$this->login();
			$this->client->openvz_template_delete($this->session_id, $template_id);
			return TRUE;
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
	 * @return array openvz_ip.* or error
	 */
	public function openvz_ip_get($ip_address_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->openvz_ip_get($this->session_id, $ip_address_id));
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
	 * @return array openvz_ip.* or error
	 */
	public function openvz_get_free_ip($server_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->openvz_get_free_ip($this->session_id, $server_id));
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
	 * @param array $openvz_ip server_id,  ip_address,  vm_id,  reserved
	 * @return int|array openvz_ip.ip_address_id or error
	 */
	public function openvz_ip_add($client_id = 0, $openvz_ip = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($openvz_ip['server_id']) ? $openvz_ip['server_id'] : 0,
					'ip_address' => isset($openvz_ip['ip_address']) ? $openvz_ip['ip_address'] : '',
					'vm_id' => isset($openvz_ip['vm_id']) ? $openvz_ip['vm_id'] : 0,
					'reserved' => isset($openvz_ip['reserved']) ? $openvz_ip['reserved'] : 'n',
			);
			return $this->client->openvz_ip_add($this->session_id, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update one record in VServer > OpenVZ IP addresses
	 *
	 * @param int   $client_id
	 * @param int   $ip_address_id
	 * @param array $openvz_ip server_id,  ip_address,  vm_id,  reserved
	 * @return bool|array TRUE or error
	 */
	public function openvz_ip_update($client_id = 0, $ip_address_id = 0, $openvz_ip = array())
	{
		try
		{
			$this->login();
			$record = $this->client->openvz_ip_get($this->session_id, $ip_address_id);
			$params = array(
					'server_id' => isset($openvz_ip['server_id']) ? $openvz_ip['server_id'] : $record['server_id'],
					'ip_address' => isset($openvz_ip['ip_address']) ? $openvz_ip['ip_address'] : $record['ip_address'],
					'vm_id' => isset($openvz_ip['vm_id']) ? $openvz_ip['vm_id'] : $record['vm_id'],
					'reserved' => isset($openvz_ip['reserved']) ? $openvz_ip['reserved'] : $record['reserved']
			);
			$this->client->openvz_ip_update($this->session_id, $client_id, $ip_address_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function openvz_ip_delete($ip_address_id = 0)
	{
		try
		{
			$this->login();
			$this->client->openvz_ip_delete($this->session_id, $ip_address_id);
			return TRUE;
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
	 * @return array openvz_vm.* or error
	 */
	public function openvz_vm_get($vm_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->openvz_vm_get($this->session_id, $vm_id));
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
	 * @return array openvz_vm.* or error
	 */
	public function openvz_vm_get_by_client($client_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->openvz_vm_get_by_client($this->session_id, $client_id));
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
	 * @param array $openvz_vm server_id,  veid,  ostemplate_id,  template_id,  ip_address,  hostname,
	 *                         vm_password,  start_boot,  active, active_until_date,  description,  diskspace,
	 *                         traffic,  bandwidth,  ram,  ram_burst,  cpu_units,  cpu_num,  cpu_limit,
	 *                         io_priority,  nameserver,  create_dns,  capability,  config
	 * @return int|array openvz_vm.vm_id or error
	 */
	public function openvz_vm_add($client_id = 0, $openvz_vm = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($openvz_vm['server_id']) ? $openvz_vm['server_id'] : 0,
					'veid' => isset($openvz_vm['veid']) ? $openvz_vm['veid'] : 0,
					'ostemplate_id' => isset($openvz_vm['ostemplate_id']) ? $openvz_vm['ostemplate_id'] : 0,
					'template_id' => isset($openvz_vm['template_id']) ? $openvz_vm['template_id'] : 0,
					'ip_address' => isset($openvz_vm['ip_address']) ? $openvz_vm['ip_address'] : '',
					'hostname' => isset($openvz_vm['hostname']) ? $openvz_vm['hostname'] : '',
					'vm_password' => isset($openvz_vm['vm_password']) ? $openvz_vm['vm_password'] : '',
					'start_boot' => isset($openvz_vm['start_boot']) ? $openvz_vm['start_boot'] : 'y',
					'active' => isset($openvz_vm['active']) ? $openvz_vm['active'] : 'y',
					'active_until_date' => isset($openvz_vm['active_until_date']) ? $openvz_vm['active_until_date'] : '0000-00-00',
					'description' => isset($openvz_vm['description']) ? $openvz_vm['description'] : '',
					'diskspace' => isset($openvz_vm['diskspace']) ? $openvz_vm['diskspace'] : 0,
					'traffic' => isset($openvz_vm['traffic']) ? $openvz_vm['traffic'] : -1,
					'bandwidth' => isset($openvz_vm['bandwidth']) ? $openvz_vm['bandwidth'] : -1,
					'ram' => isset($openvz_vm['ram']) ? $openvz_vm['ram'] : 0,
					'ram_burst' => isset($openvz_vm['ram_burst']) ? $openvz_vm['ram_burst'] : 0,
					'cpu_units' => isset($openvz_vm['cpu_units']) ? $openvz_vm['cpu_units'] : 1000,
					'cpu_num' => isset($openvz_vm['cpu_num']) ? $openvz_vm['cpu_num'] : 4,
					'cpu_limit' => isset($openvz_vm['cpu_limit']) ? $openvz_vm['cpu_limit'] : 400,
					'io_priority' => isset($openvz_vm['io_priority']) ? $openvz_vm['io_priority'] : 4,
					'nameserver' => isset($openvz_vm['nameserver']) ? $openvz_vm['nameserver'] : '8.8.8.8 8.8.4.4',
					'create_dns' => isset($openvz_vm['create_dns']) ? $openvz_vm['create_dns'] : 'n',
					'capability' => isset($openvz_vm['capability']) ? $openvz_vm['capability'] : '',
					'config' => isset($openvz_vm['config']) ? $openvz_vm['config'] : '',
			);
			return $this->client->openvz_vm_add($this->session_id, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	public function openvz_vm_add_from_template()
	{
	}

	/**
	 * Update one record in VServer > OpenVZ Virtual Servers
	 *
	 * @param int   $client_id
	 * @param int   $vm_id
	 * @param array $openvz_vm server_id,  veid,  ostemplate_id,  template_id,  ip_address,  hostname,
	 *                         vm_password,  start_boot,  active, active_until_date,  description,  diskspace,
	 *                         traffic,  bandwidth,  ram,  ram_burst,  cpu_units,  cpu_num,  cpu_limit,
	 *                         io_priority,  nameserver,  create_dns,  capability,  config
	 * @return int|array openvz_vm.vm_id or error
	 */
	public function openvz_vm_update($client_id = 0, $vm_id = 0, $openvz_vm = array())
	{
		try
		{
			$this->login();
			$record = $this->client->openvz_vm_get($this->session_id, $vm_id);
			$params = array(
					'server_id' => isset($openvz_vm['server_id']) ? $openvz_vm['server_id'] : $record['server_id'],
					'veid' => isset($openvz_vm['veid']) ? $openvz_vm['veid'] : $record['veid'],
					'ostemplate_id' => isset($openvz_vm['ostemplate_id']) ? $openvz_vm['ostemplate_id'] : $record['ostemplate_id'],
					'template_id' => isset($openvz_vm['template_id']) ? $openvz_vm['template_id'] : $record['template_id'],
					'ip_address' => isset($openvz_vm['ip_address']) ? $openvz_vm['ip_address'] : $record['ip_address'],
					'hostname' => isset($openvz_vm['hostname']) ? $openvz_vm['hostname'] : $record['hostname'],
					'vm_password' => isset($openvz_vm['vm_password']) ? $openvz_vm['vm_password'] : $record['vm_password'],
					'start_boot' => isset($openvz_vm['start_boot']) ? $openvz_vm['start_boot'] : $record['start_boot'],
					'active' => isset($openvz_vm['active']) ? $openvz_vm['active'] : $record['active'],
					'active_until_date' => isset($openvz_vm['active_until_date']) ? $openvz_vm['active_until_date'] : $record['active_until_date'],
					'description' => isset($openvz_vm['description']) ? $openvz_vm['description'] : $record['description'],
					'diskspace' => isset($openvz_vm['diskspace']) ? $openvz_vm['diskspace'] : $record['diskspace'],
					'traffic' => isset($openvz_vm['traffic']) ? $openvz_vm['traffic'] : $record['traffic'],
					'bandwidth' => isset($openvz_vm['bandwidth']) ? $openvz_vm['bandwidth'] : $record['bandwidth'],
					'ram' => isset($openvz_vm['ram']) ? $openvz_vm['ram'] : $record['ram'],
					'ram_burst' => isset($openvz_vm['ram_burst']) ? $openvz_vm['ram_burst'] : $record['ram_burst'],
					'cpu_units' => isset($openvz_vm['cpu_units']) ? $openvz_vm['cpu_units'] : $record['cpu_units'],
					'cpu_num' => isset($openvz_vm['cpu_num']) ? $openvz_vm['cpu_num'] : $record['cpu_num'],
					'cpu_limit' => isset($openvz_vm['cpu_limit']) ? $openvz_vm['cpu_limit'] : $record['cpu_limit'],
					'io_priority' => isset($openvz_vm['io_priority']) ? $openvz_vm['io_priority'] : $record['io_priority'],
					'nameserver' => isset($openvz_vm['nameserver']) ? $openvz_vm['nameserver'] : $record['nameserver'],
					'create_dns' => isset($openvz_vm['create_dns']) ? $openvz_vm['create_dns'] : $record['create_dns'],
					'capability' => isset($openvz_vm['capability']) ? $openvz_vm['capability'] : $record['capability'],
					'config' => isset($openvz_vm['config']) ? $openvz_vm['config'] : $record['config'],
			);
			$this->client->openvz_vm_update($this->session_id, $client_id, $vm_id, $params);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Delete one record in VServer > OpenVZ Virtual Servers
	 *
	 * @param int $vm_id
	 * @return bool|array TRUE or error
	 */
	public function openvz_vm_delete($vm_id = 0)
	{
		try
		{
			$this->login();
			$this->client->openvz_vm_delete($this->session_id, $vm_id);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function openvz_vm_start($vm_id = 0)
	{
		try
		{
			$this->login();
			$this->client->openvz_vm_start($this->session_id, $vm_id);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function openvz_vm_stop($vm_id = 0)
	{
		try
		{
			$this->login();
			$this->client->openvz_vm_stop($this->session_id, $vm_id);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function openvz_vm_restart($vm_id = 0)
	{
		try
		{
			$this->login();
			$this->client->openvz_vm_restart($this->session_id, $vm_id);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get one record from System > Server Config
	 *
	 * @param int $server_id
	 * @return array server.* or error
	 */
	public function server_get($server_id = 0)
	{
		try
		{
			$this->login();
			return $this->client->server_get($this->session_id, $server_id, $section = '');
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
	public function server_get_all()
	{
		try
		{
			$this->login();
			return $this->client->server_get_all($this->session_id);
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
	 * @return array server.server_id or error
	 */
	public function server_get_serverid_by_name($server_name = '')
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->server_get_serverid_by_name($this->session_id, $server_name));
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
	 * @return array server.(mail_server, web_server, dns_server, file_server, db_server, vserver_server,
	 *               proxy_server, firewall_server) or error
	 */
	public function server_get_functions($server_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->server_get_functions($this->session_id, $server_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update sys_perm_user in any table
	 *
	 * @internal
	 * @param string $tablename
	 * @param string $index_field
	 * @param string $index_value
	 * @param string $permissions
	 * @return bool|array TRUE or error
	 */
	public function update_record_permissions($tablename = '', $index_field = '', $index_value = '', $permissions = 'riud')
	{
		try
		{
			$this->login();
			$this->client->update_record_permissions($this->session_id, $tablename, $index_field, $index_value, $permissions);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get the ISPConfig Version
	 *
	 * @return array ispc_app_version or error
	 */
	public function server_get_app_version()
	{
		try
		{
			$this->login();
			return $this->client->server_get_app_version($this->session_id);
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
	 * @return array server_ip.server_id or error
	 */
	public function server_get_serverid_by_ip($ipaddress = '')
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->server_get_serverid_by_ip($this->session_id, $ipaddress));
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
	 * @return array server_ip.* or error
	 */
	public function server_ip_get($server_ip_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->server_ip_get($this->session_id, $server_ip_id));
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
	 * @param array $server_ip server_id, client_id, ip_type, ip_address, virtualhost, virtualhost_port
	 * @return int|array server_ip.server_ip_id or error
	 */
	public function server_ip_add($client_id = 0, $server_ip = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($server_ip['server_id']) ? $server_ip['server_id'] : 0,
					'client_id' => $client_id,
					'ip_type' => isset($server_ip['ip_type']) ? $server_ip['ip_type'] : 'IPv4',
					'ip_address' => isset($server_ip['ip_address']) ? $server_ip['ip_address'] : '',
					'virtualhost' => isset($server_ip['virtualhost']) ? $server_ip['virtualhost'] : 'y',
					'virtualhost_port' => isset($server_ip['virtualhost_port']) ? $server_ip['virtualhost_port'] : '80,443'
			);
			return $this->client->server_ip_add($this->session_id, $client_id, $params);
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
	 * @param array $server_ip server_id, client_id, ip_type, ip_address, virtualhost, virtualhost_port
	 * @return bool|array TRUE or error
	 */
	public function server_ip_update($client_id = 0, $server_ip_id = 0, $server_ip = array())
	{
		try
		{
			$this->login();
			$record = $this->client->server_ip_get($this->session_id, $server_ip_id);
			$params = array(
					'server_id' => isset($server_ip['server_id']) ? $server_ip['server_id'] : $record['server_id'],
					'client_id' => $client_id,
					'ip_type' => isset($server_ip['ip_type']) ? $server_ip['ip_type'] : $record['ip_type'],
					'ip_address' => isset($server_ip['ip_address']) ? $server_ip['ip_address'] : $record['ip_address'],
					'virtualhost' => isset($server_ip['virtualhost']) ? $server_ip['virtualhost'] : $record['virtualhost'],
					'virtualhost_port' => isset($server_ip['virtualhost_port']) ? $server_ip['virtualhost_port'] : $record['virtualhost_port']
			);
			$this->client->server_ip_update($this->session_id, $client_id, $server_ip_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function server_ip_delete($server_ip_id = 0)
	{
		try
		{
			$this->login();
			$this->client->server_ip_delete($this->session_id, $server_ip_id);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Get one record from Sites > Cron Jobs
	 *
	 * @param int $id
	 * @return array cron.* or error
	 */
	public function sites_cron_get($id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->sites_cron_get($this->session_id, $id));
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
	 * @param array $cron server_id, parent_domain_id, type, command, run_min, run_hour, run_mday, run_month,
	 *                    run_wday, log, active
	 * @return int|array cron.id or error
	 */
	public function sites_cron_add($client_id = 0, $cron = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($cron['server_id']) ? $cron['server_id'] : 0,
					'parent_domain_id' => isset($cron['parent_domain_id']) ? $cron['parent_domain_id'] : 0,
					'type' => isset($cron['type']) ? $cron['type'] : 'url',
					'command' => isset($cron['command']) ? $cron['command'] : '',
					'run_min' => isset($cron['run_min']) ? $cron['run_min'] : '',
					'run_hour' => isset($cron['run_hour']) ? $cron['run_hour'] : '',
					'run_mday' => isset($cron['run_mday']) ? $cron['run_mday'] : '',
					'run_month' => isset($cron['run_month']) ? $cron['run_month'] : '',
					'run_wday' => isset($cron['run_wday']) ? $cron['run_wday'] : '',
					'log' => isset($cron['log']) ? $cron['log'] : 'n',
					'active' => isset($cron['active']) ? $cron['active'] : 'y'
			);
			return $this->client->sites_cron_add($this->session_id, $client_id, $params);
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
	 * @param int   $id
	 * @param array $cron server_id, parent_domain_id, type, command, run_min, run_hour, run_mday, run_month,
	 *                    run_wday, log, active
	 * @return bool|array TRUE or error
	 */
	public function sites_cron_update($client_id = 0, $id = 0, $cron = array())
	{
		try
		{
			$this->login();
			$record = $this->client->sites_cron_get($this->session_id, $id);
			$params = array(
					'server_id' => isset($cron['server_id']) ? $cron['server_id'] : $record['server_id'],
					'parent_domain_id' => isset($cron['parent_domain_id']) ? $cron['parent_domain_id'] : $record['parent_domain_id'],
					'type' => isset($cron['type']) ? $cron['type'] : $record['type'],
					'command' => isset($cron['command']) ? $cron['command'] : $record['command'],
					'run_min' => isset($cron['run_min']) ? $cron['run_min'] : $record['run_min'],
					'run_hour' => isset($cron['run_hour']) ? $cron['run_hour'] : $record['run_hour'],
					'run_mday' => isset($cron['run_mday']) ? $cron['run_mday'] : $record['run_mday'],
					'run_month' => isset($cron['run_month']) ? $cron['run_month'] : $record['run_month'],
					'run_wday' => isset($cron['run_wday']) ? $cron['run_wday'] : $record['run_wday'],
					'log' => isset($cron['log']) ? $cron['log'] : $record['log'],
					'active' => isset($cron['active']) ? $cron['active'] : $record['active'],
			);
			$this->client->sites_cron_update($this->session_id, $client_id, $id, $params);
			return TRUE;
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
	 * @return array web_database.* or error
	 */
	public function sites_database_get($database_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->sites_database_get($this->session_id, $database_id));
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
	 * @param array $web_database server_id, type, parent_domain_id, database_name, database_user_id,
	 *                            database_ro_user_id, database_charset, remote_access, remote_ips,
	 *                            backup_interval, backup_copies, active,
	 * @return int|array web_database.database_id or error
	 */
	public function sites_database_add($client_id = 0, $web_database = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($web_database['server_id']) ? $web_database['server_id'] : 0,
					'type' => isset($web_database['type']) ? $web_database['type'] : 'mysql',
					'parent_domain_id' => isset($web_database['parent_domain_id']) ? $web_database['parent_domain_id'] : 0,
					'database_name' => isset($web_database['database_name']) ? $web_database['database_name'] : '',
					'database_user_id' => isset($web_database['database_user_id']) ? $web_database['database_user_id'] : '',
					'database_ro_user_id' => isset($web_database['database_ro_user_id']) ? $web_database['database_ro_user_id'] : '',
					'database_charset' => isset($web_database['database_charset']) ? $web_database['database_charset'] : 'utf8',
					'remote_access' => isset($web_database['remote_access']) ? $web_database['remote_access'] : 'y',
					'remote_ips' => isset($web_database['remote_ips']) ? $web_database['remote_ips'] : '',
					'backup_interval' => isset($web_database['backup_interval']) ? $web_database['backup_interval'] : 'none',
					'backup_copies' => isset($web_database['backup_copies']) ? $web_database['backup_copies'] : 1,
					'active' => isset($web_database['active']) ? $web_database['active'] : 'y'
			);
			return $this->client->sites_database_add($this->session_id, $client_id, $params);
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
	 * @param array $web_database server_id, type, parent_domain_id, database_name, database_user_id,
	 *                            database_ro_user_id, database_charset, remote_access, remote_ips,
	 *                            backup_interval, backup_copies, active,
	 * @return bool|array TRUE or error
	 */
	public function sites_database_update($client_id = 0, $database_id = 0, $web_database = array())
	{
		try
		{
			$this->login();
			$record = $this->client->sites_database_get($this->session_id, $database_id);
			$params = array(
					'server_id' => isset($web_database['server_id']) ? $web_database['server_id'] : $record['server_id'],
					'type' => isset($web_database['type']) ? $web_database['type'] : $record['type'],
					'parent_domain_id' => isset($web_database['parent_domain_id']) ? $web_database['parent_domain_id'] : $record['parent_domain_id'],
					'database_name' => isset($web_database['database_name']) ? $web_database['database_name'] : $record['database_name'],
					'database_user_id' => isset($web_database['database_user_id']) ? $web_database['database_user_id'] : $record['database_user_id'],
					'database_ro_user_id' => isset($web_database['database_ro_user_id']) ? $web_database['database_ro_user_id'] : $record['database_ro_user_id'],
					'database_charset' => isset($web_database['database_charset']) ? $web_database['database_charset'] : $record['database_charset'],
					'remote_access' => isset($web_database['remote_access']) ? $web_database['remote_access'] : $record['remote_access'],
					'remote_ips' => isset($web_database['remote_ips']) ? $web_database['remote_ips'] : $record['remote_ips'],
					'backup_interval' => isset($web_database['backup_interval']) ? $web_database['backup_interval'] : $record['backup_interval'],
					'backup_copies' => isset($web_database['backup_copies']) ? $web_database['backup_copies'] : $record['backup_copies'],
					'active' => isset($web_database['active']) ? $web_database['active'] : $record['active']
			);
			$this->client->sites_database_update($this->session_id, $client_id, $database_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function sites_database_delete($database_id = 0)
	{
		try
		{
			$this->login();
			$this->client->sites_database_delete($this->session_id, $database_id);
			return TRUE;
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
	 * @return array web_database_user.* or error
	 */
	public function sites_database_user_get($database_user_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->sites_database_user_get($this->session_id, $database_user_id));
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
	 * @param array $web_database_user server_id, database_user, database_password
	 * @return int|array web_database_user.database_user_id or error
	 */
	public function sites_database_user_add($client_id = 0, $web_database_user = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($web_database_user['server_id']) ? $web_database_user['server_id'] : 0,
					'database_user' => isset($web_database_user['database_user']) ? $web_database_user['database_user'] : '',
					'database_password' => isset($web_database_user['database_password']) ? $web_database_user['database_password'] : ''
			);
			return $this->client->sites_database_user_add($this->session_id, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update one record in Sites > Database Users
	 *
	 * @param int   $client_id
	 * @param int   $database_user_id
	 * @param array $web_database_user server_id, database_user, database_password
	 * @return bool|array TRUE or error
	 */
	public function sites_database_user_update($client_id = 0, $database_user_id = 0, $web_database_user = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($web_database_user['server_id']) ? $web_database_user['server_id'] : 0,
					'database_user' => isset($web_database_user['database_user']) ? $web_database_user['database_user'] : '',
					'database_password' => isset($web_database_user['database_password']) ? $web_database_user['database_password'] : ''
			);
			$this->client->sites_database_user_update($this->session_id, $client_id, $database_user_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function sites_database_user_delete($database_user_id = 0)
	{
		try
		{
			$this->login();
			$this->client->sites_database_user_delete($this->session_id, $database_user_id);
			return TRUE;
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
	 * @return array ftp_user.* or error
	 */
	public function sites_ftp_user_get($ftp_user_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->sites_ftp_user_get($this->session_id, $ftp_user_id));
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
	 * @param int   $domain_id
	 * @param array $ftp_user server_id, parent_domain_id, username, password, quota_size, active, uid, gid,
	 *                        dir, quota_files, ul_ratio, dl_ratio, ul_bandwidth, dl_bandwidth
	 * @return array ftp_user.ftp_user_id or error
	 */
	public function sites_ftp_user_add($client_id = 0, $domain_id = 0, $ftp_user = array())
	{
		try
		{
			$this->login();
			$web_domain = $this->client->sites_web_domain_get($this->session_id, $domain_id);
			$params = array(
					'server_id' => isset($ftp_user['server_id']) ? $ftp_user['server_id'] : 1,
					'parent_domain_id' => isset($ftp_user['parent_domain_id']) ? $ftp_user['parent_domain_id'] : $domain_id,
					'username' => isset($ftp_user['username']) ? $ftp_user['username'] : '',
					'password' => isset($ftp_user['password']) ? $ftp_user['password'] : '',
					'quota_size' => isset($ftp_user['quota_size']) ? $ftp_user['quota_size'] : -1,
					'active' => isset($ftp_user['active']) ? $ftp_user['active'] : 'y',
					'uid' => isset($ftp_user['uid']) ? $ftp_user['uid'] : $web_domain['system_user'],
					'gid' => isset($ftp_user['gid']) ? $ftp_user['gid'] : $web_domain['system_group'],
					'dir' => isset($ftp_user['dir']) ? $ftp_user['dir'] : $web_domain['document_root'],
					'quota_files' => isset($ftp_user['quota_files']) ? $ftp_user['quota_files'] : -1,
					'ul_ratio' => isset($ftp_user['ul_ratio']) ? $ftp_user['ul_ratio'] : -1,
					'dl_ratio' => isset($ftp_user['dl_ratio']) ? $ftp_user['dl_ratio'] : -1,
					'ul_bandwidth' => isset($ftp_user['ul_bandwidth']) ? $ftp_user['ul_bandwidth'] : -1,
					'dl_bandwidth' => isset($ftp_user['dl_bandwidth']) ? $ftp_user['dl_bandwidth'] : -1
			);
			return $this->client->sites_ftp_user_add($this->session_id, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update one record in Sites > FTP-Accounts > FTP-User
	 *
	 * @param int   $client_id
	 * @param int   $ftp_user_id
	 * @param array $ftp_user server_id, parent_domain_id, username, password, quota_size, active, uid, gid,
	 *                        dir, quota_files, ul_ratio, dl_ratio, ul_bandwidth, dl_bandwidth
	 * @return bool|array TRUE or error
	 */
	public function sites_ftp_user_update($client_id = 0, $ftp_user_id = 0, $ftp_user = array())
	{
		try
		{
			$this->login();
			$record = $this->client->sites_ftp_user_get($this->session_id, $ftp_user_id);
			$params = array(
					'server_id' => isset($ftp_user['server_id']) ? $ftp_user['server_id'] : $record['server_id'],
					'parent_domain_id' => isset($ftp_user['parent_domain_id']) ? $ftp_user['parent_domain_id'] : $record['parent_domain_id'],
					'username' => isset($ftp_user['username']) ? $ftp_user['username'] : $record['username'],
					'password' => isset($ftp_user['password']) ? $ftp_user['password'] : $record['password'],
					'quota_size' => isset($ftp_user['quota_size']) ? $ftp_user['quota_size'] : $record['quota_size'],
					'active' => isset($ftp_user['active']) ? $ftp_user['active'] : $record['active'],
					'uid' => isset($ftp_user['uid']) ? $ftp_user['uid'] : $record['uid'],
					'gid' => isset($ftp_user['gid']) ? $ftp_user['gid'] : $record['gid'],
					'dir' => isset($ftp_user['dir']) ? $ftp_user['dir'] : $record['dir'],
					'quota_files' => isset($ftp_user['quota_files']) ? $ftp_user['quota_files'] : $record['quota_files'],
					'ul_ratio' => isset($ftp_user['ul_ratio']) ? $ftp_user['ul_ratio'] : $record['ul_ratio'],
					'dl_ratio' => isset($ftp_user['dl_ratio']) ? $ftp_user['dl_ratio'] : $record['dl_ratio'],
					'ul_bandwidth' => isset($ftp_user['ul_bandwidth']) ? $ftp_user['ul_bandwidth'] : $record['ul_bandwidth'],
					'dl_bandwidth' => isset($ftp_user['dl_bandwidth']) ? $ftp_user['dl_bandwidth'] : $record['dl_bandwidth']
			);
			$this->client->sites_ftp_user_update($this->session_id, $client_id, $ftp_user_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function sites_ftp_user_delete($ftp_user_id = 0)
	{
		try
		{
			$this->login();
			$this->client->sites_ftp_user_delete($this->session_id, $ftp_user_id);
			return TRUE;
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
	 * @return array server.config[server] or error
	 */
	public function sites_ftp_user_server_get($ftp_user = '')
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->sites_ftp_user_server_get($this->session_id, $ftp_user));
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
	 * @return array shell_user.* or error
	 */
	public function sites_shell_user_get($shell_user_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->sites_shell_user_get($this->session_id, $shell_user_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Add one record on Sites > Shell User
	 * Todo: Debug. Retrieving error "directory_error_notinweb" but dir is ok!
	 *
	 * @param int   $client_id
	 * @param int   $domain_id
	 * @param array $shell_user server_id, parent_domain_id, username, password, quota_size, active, puser,
	 *                          pgroup, shell, dir, chroot
	 * @return int|array shell_user.shell_user_id or error
	 */
	public function sites_shell_user_add($client_id = 0, $domain_id = 0, $shell_user = array())
	{
		try
		{
			$this->login();
			$web_domain = $this->client->sites_web_domain_get($this->session_id, $domain_id);
			$params = array(
					'server_id' => isset($shell_user['server_id']) ? $shell_user['server_id'] : $web_domain['server_id'],
					'parent_domain_id' => isset($shell_user['parent_domain_id']) ? $shell_user['parent_domain_id'] : $web_domain['parent_domain_id'],
					'username' => isset($shell_user['username']) ? $shell_user['username'] : '',
					'password' => isset($shell_user['password']) ? $shell_user['password'] : '',
					'quota_size' => isset($shell_user['quota_size']) ? $shell_user['quota_size'] : $web_domain['hd_quota'],
					'active' => isset($shell_user['active']) ? $shell_user['active'] : 'y',
					'puser' => isset($shell_user['puser']) ? $shell_user['puser'] : $web_domain['system_user'],
					'pgroup' => isset($shell_user['pgroup']) ? $shell_user['pgroup'] : $web_domain['system_group'],
					'shell' => isset($shell_user['shell']) ? $shell_user['shell'] : '/bin/bash',
					'dir' => isset($shell_user['dir']) ? $shell_user['dir'] : $web_domain['document_root'],
					'chroot' => isset($shell_user['chroot']) ? $shell_user['chroot'] : 'no'
			);
			return $this->client->sites_shell_user_add($this->session_id, $client_id, $params);
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
	 * @param array $shell_user server_id, parent_domain_id, username, password, quota_size, active, puser,
	 *                          pgroup, shell, dir, chroot
	 * @return bool|array TRUE or error
	 */
	public function sites_shell_user_update($client_id = 0, $shell_user_id = 0, $shell_user = array())
	{
		try
		{
			$this->login();
			$record = $this->client->sites_shell_user_get($this->session_id, $shell_user_id);
			$params = array(
					'server_id' => isset($shell_user['server_id']) ? $shell_user['server_id'] : $record['server_id'],
					'parent_domain_id' => isset($shell_user['parent_domain_id']) ? $shell_user['parent_domain_id'] : $record['parent_domain_id'],
					'username' => isset($shell_user['username']) ? $shell_user['username'] : $record['username'],
					'password' => isset($shell_user['password']) ? $shell_user['password'] : $record['password'],
					'quota_size' => isset($shell_user['quota_size']) ? $shell_user['quota_size'] : $record['quota_size'],
					'active' => isset($shell_user['active']) ? $shell_user['active'] : $record['active'],
					'puser' => isset($shell_user['puser']) ? $shell_user['puser'] : $record['puser'],
					'pgroup' => isset($shell_user['pgroup']) ? $shell_user['pgroup'] : $record['pgroup'],
					'shell' => isset($shell_user['shell']) ? $shell_user['shell'] : $record['shell'],
					'dir' => isset($shell_user['dir']) ? $shell_user['dir'] : $record['dir'],
					'chroot' => isset($shell_user['chroot']) ? $shell_user['chroot'] : $record['chroot']
			);
			$this->client->sites_shell_user_update($this->session_id, $client_id, $shell_user_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function sites_shell_user_delete($shell_user_id = 0)
	{
		try
		{
			$this->login();
			$this->client->sites_shell_user_delete($this->session_id, $shell_user_id);
			return TRUE;
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
	 * @return array web_domain.* or error
	 */
	public function sites_web_domain_get($domain_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->sites_web_domain_get($this->session_id, $domain_id));
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
	 * @param array $web_domain server_id, ip_address, ipv6_address, domain, hd_quota, traffic_quota, cgi, ssi,
	 *                          suexec, errordocs, is_subdomainwww, subdomain, php, ruby, python, perl,
	 *                          redirect_type, redirect_path, seo_redirect, ssl, ssl_state, ssl_locality,
	 *                          ssl_organisation, ssl_organisation_unit, ssl_country, ssl_domain, ssl_request,
	 *                          ssl_key, ssl_cert, ssl_bundle, ssl_action, stats_password, stats_type,
	 *                          allow_override, apache_directives, nginx_directives, php_fpm_use_socket, pm,
	 *                          pm_max_children, pm_start_servers, pm_min_spare_servers pm_max_spare_servers,
	 *                          pm_process_idle_timeout, pm_max_requests, custom_php_ini, backup_interval,
	 *                          backup_copies, active, traffic_quota_lock, fastcgi_php_version,
	 *                          proxy_directives, rewrite_rules, added_date, added_by
	 * @return int|array web_domain.domain_id or error
	 */
	public function sites_web_domain_add($client_id = 0, $web_domain = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($web_domain['server_id']) ? $web_domain['server_id'] : 0,
					'ip_address' => isset($web_domain['ip_address']) ? $web_domain['ip_address'] : '*',
					'ipv6_address' => isset($web_domain['ipv6_address']) ? $web_domain['ipv6_address'] : '192.168.1.55',
					'domain' => isset($web_domain['domain']) ? $web_domain['domain'] : '',
					'type' => 'vhost',
					'parent_domain_id' => 0,
					'vhost_type' => 'name',
					'hd_quota' => isset($web_domain['hd_quota']) ? $web_domain['hd_quota'] : -1,
					'traffic_quota' => isset($web_domain['traffic_quota']) ? $web_domain['traffic_quota'] : -1,
					'cgi' => isset($web_domain['cgi']) ? $web_domain['cgi'] : 'n',
					'ssi' => isset($web_domain['ssi']) ? $web_domain['ssi'] : 'n',
					'suexec' => isset($web_domain['suexec']) ? $web_domain['suexec'] : 'y',
					'errordocs' => isset($web_domain['errordocs']) ? $web_domain['errordocs'] : 1,
					'is_subdomainwww' => isset($web_domain['is_subdomainwww']) ? $web_domain['is_subdomainwww'] : 1,
					'subdomain' => isset($web_domain['subdomain']) ? $web_domain['subdomain'] : 'none',
					'php' => isset($web_domain['php']) ? $web_domain['php'] : 'y',
					'ruby' => isset($web_domain['ruby']) ? $web_domain['ruby'] : 'n',
					'python' => isset($web_domain['python']) ? $web_domain['python'] : 'n',
					'perl' => isset($web_domain['perl']) ? $web_domain['perl'] : 'n',
					'seo_redirect' => isset($web_domain['seo_redirect']) ? $web_domain['seo_redirect'] : '',
					'redirect_path' => isset($web_domain['redirect_path']) ? $web_domain['redirect_path'] : '',
					'redirect_type' => isset($web_domain['redirect_type']) ? $web_domain['redirect_type'] : '',
					'ssl' => isset($web_domain['ssl']) ? $web_domain['ssl'] : 'n',
					'ssl_state' => isset($web_domain['ssl_state']) ? $web_domain['ssl_state'] : '',
					'ssl_locality' => isset($web_domain['ssl_locality']) ? $web_domain['ssl_locality'] : '',
					'ssl_organisation' => isset($web_domain['ssl_organisation']) ? $web_domain['ssl_organisation'] : '',
					'ssl_organisation_unit' => isset($web_domain['ssl_organisation_unit']) ? $web_domain['ssl_organisation_unit'] : '',
					'ssl_country' => isset($web_domain['ssl_country']) ? $web_domain['ssl_country'] : '',
					'ssl_domain' => isset($web_domain['ssl_domain']) ? $web_domain['ssl_domain'] : '',
					'ssl_request' => isset($web_domain['ssl_request']) ? $web_domain['ssl_request'] : '',
					'ssl_key' => isset($web_domain['ssl_key']) ? $web_domain['ssl_key'] : '',
					'ssl_cert' => isset($web_domain['ssl_cert']) ? $web_domain['ssl_cert'] : '',
					'ssl_bundle' => isset($web_domain['ssl_bundle']) ? $web_domain['ssl_bundle'] : '',
					'ssl_action' => isset($web_domain['ssl_action']) ? $web_domain['ssl_action'] : '',
					'stats_password' => isset($web_domain['stats_password']) ? $web_domain['stats_password'] : '',
					'stats_type' => isset($web_domain['stats_type']) ? $web_domain['stats_type'] : 'webalizer',
					'allow_override' => isset($web_domain['allow_override']) ? $web_domain['allow_override'] : 'All',
					'apache_directives' => isset($web_domain['apache_directives']) ? $web_domain['apache_directives'] : '',
					'nginx_directives' => isset($web_domain['nginx_directives']) ? $web_domain['nginx_directives'] : '',
					'php_fpm_use_socket' => isset($web_domain['php_fpm_use_socket']) ? $web_domain['php_fpm_use_socket'] : 'y',
					'pm' => isset($web_domain['pm']) ? $web_domain['pm'] : 'dynamic',
					'pm_max_children' => isset($web_domain['pm_max_children']) ? $web_domain['pm_max_children'] : 10,
					'pm_start_servers' => isset($web_domain['pm_start_servers']) ? $web_domain['pm_start_servers'] : 2,
					'pm_min_spare_servers' => isset($web_domain['pm_min_spare_servers']) ? $web_domain['pm_min_spare_servers'] : 1,
					'pm_max_spare_servers' => isset($web_domain['pm_max_spare_servers']) ? $web_domain['pm_max_spare_servers'] : 5,
					'pm_process_idle_timeout' => isset($web_domain['pm_process_idle_timeout']) ? $web_domain['pm_process_idle_timeout'] : 10,
					'pm_max_requests' => isset($web_domain['pm_max_requests']) ? $web_domain['pm_max_requests'] : 0,
					'custom_php_ini' => isset($web_domain['custom_php_ini']) ? $web_domain['custom_php_ini'] : '',
					'backup_interval' => isset($web_domain['backup_interval']) ? $web_domain['backup_interval'] : 'none',
					'backup_copies' => isset($web_domain['backup_copies']) ? $web_domain['backup_copies'] : 1,
					'backup_excludes' => isset($web_domain['backup_excludes']) ? $web_domain['backup_excludes'] : '',
					'active' => isset($web_domain['active']) ? $web_domain['active'] : 'y',
					'traffic_quota_lock' => isset($web_domain['traffic_quota_lock']) ? $web_domain['traffic_quota_lock'] : 'n',
					'fastcgi_php_version' => isset($web_domain['fastcgi_php_version']) ? $web_domain['fastcgi_php_version'] : '',
					'proxy_directives' => isset($web_domain['proxy_directives']) ? $web_domain['proxy_directives'] : '',
					'rewrite_rules' => isset($web_domain['rewrite_rules']) ? $web_domain['rewrite_rules'] : '',
					'added_date' => isset($web_domain['added_date']) ? $web_domain['added_date'] : date('Y-m-d'),
					'added_by' => isset($web_domain['added_by']) ? $web_domain['added_by'] : $this->CI->config->item('username'),
			);
			return $this->client->sites_web_domain_add($this->session_id, $client_id, $params, $readonly = FALSE);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Delete one record in Sites > Websites
	 *
	 * @param int $domain_id
	 * @return bool|array TRUE or error
	 */
	public function sites_web_domain_delete($domain_id = 0)
	{
		try
		{
			$this->login();
			$this->client->sites_web_domain_delete($this->session_id, $domain_id);
			return TRUE;
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
	 * @return array webdomain.* or error
	 */
	public function sites_web_vhost_subdomain_get($domain_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->sites_web_vhost_subdomain_get($this->session_id, $domain_id));
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
	 * @param array $web_domain server_id, ip_address, ipv6_address, domain, hd_quota, traffic_quota, cgi, ssi,
	 *                          suexec, errordocs, is_subdomainwww, subdomain, php, ruby, python, perl,
	 *                          redirect_type, redirect_path, seo_redirect, ssl, ssl_state, ssl_locality,
	 *                          ssl_organisation, ssl_organisation_unit, ssl_country, ssl_domain, ssl_request,
	 *                          ssl_key, ssl_cert, ssl_bundle, ssl_action, stats_password, stats_type,
	 *                          allow_override, apache_directives, nginx_directives, php_fpm_use_socket, pm,
	 *                          pm_max_children, pm_start_servers, pm_min_spare_servers pm_max_spare_servers,
	 *                          pm_process_idle_timeout, pm_max_requests, custom_php_ini, backup_interval,
	 *                          backup_copies, active, traffic_quota_lock, fastcgi_php_version,
	 *                          proxy_directives, rewrite_rules, added_date, added_by
	 * @return int|array web_domain.domain_id or error
	 */
	public function sites_web_vhost_subdomain_add($client_id = 0, $web_domain = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($web_domain['server_id']) ? $web_domain['server_id'] : 0,
					'ip_address' => isset($web_domain['ip_address']) ? $web_domain['ip_address'] : '*',
					'ipv6_address' => isset($web_domain['ipv6_address']) ? $web_domain['ipv6_address'] : '192.168.1.55',
					'domain' => isset($web_domain['domain']) ? $web_domain['domain'] : '',
					'web_folder' => isset($web_domain['web_folder']) ? $web_domain['web_folder'] : '',
					'type' => 'subdomain',
					'parent_domain_id' => isset($web_domain['parent_domain_id']) ? $web_domain['parent_domain_id'] : 0,
					'vhost_type' => '',
					'hd_quota' => isset($web_domain['hd_quota']) ? $web_domain['hd_quota'] : -1,
					'traffic_quota' => isset($web_domain['traffic_quota']) ? $web_domain['traffic_quota'] : -1,
					'cgi' => isset($web_domain['cgi']) ? $web_domain['cgi'] : 'n',
					'ssi' => isset($web_domain['ssi']) ? $web_domain['ssi'] : 'n',
					'suexec' => isset($web_domain['suexec']) ? $web_domain['suexec'] : 'y',
					'errordocs' => isset($web_domain['errordocs']) ? $web_domain['errordocs'] : 1,
					'is_subdomainwww' => isset($web_domain['is_subdomainwww']) ? $web_domain['is_subdomainwww'] : 1,
					'subdomain' => isset($web_domain['subdomain']) ? $web_domain['subdomain'] : 'none',
					'php' => isset($web_domain['php']) ? $web_domain['php'] : 'y',
					'ruby' => isset($web_domain['ruby']) ? $web_domain['ruby'] : 'n',
					'python' => isset($web_domain['python']) ? $web_domain['python'] : 'n',
					'perl' => isset($web_domain['perl']) ? $web_domain['perl'] : 'n',
					'seo_redirect' => isset($web_domain['seo_redirect']) ? $web_domain['seo_redirect'] : '',
					'redirect_path' => isset($web_domain['redirect_path']) ? $web_domain['redirect_path'] : '',
					'redirect_type' => isset($web_domain['redirect_type']) ? $web_domain['redirect_type'] : '',
					'ssl' => isset($web_domain['ssl']) ? $web_domain['ssl'] : 'n',
					'ssl_state' => isset($web_domain['ssl_state']) ? $web_domain['ssl_state'] : '',
					'ssl_locality' => isset($web_domain['ssl_locality']) ? $web_domain['ssl_locality'] : '',
					'ssl_organisation' => isset($web_domain['ssl_organisation']) ? $web_domain['ssl_organisation'] : '',
					'ssl_organisation_unit' => isset($web_domain['ssl_organisation_unit']) ? $web_domain['ssl_organisation_unit'] : '',
					'ssl_country' => isset($web_domain['ssl_country']) ? $web_domain['ssl_country'] : '',
					'ssl_domain' => isset($web_domain['ssl_domain']) ? $web_domain['ssl_domain'] : '',
					'ssl_request' => isset($web_domain['ssl_request']) ? $web_domain['ssl_request'] : '',
					'ssl_key' => isset($web_domain['ssl_key']) ? $web_domain['ssl_key'] : '',
					'ssl_cert' => isset($web_domain['ssl_cert']) ? $web_domain['ssl_cert'] : '',
					'ssl_bundle' => isset($web_domain['ssl_bundle']) ? $web_domain['ssl_bundle'] : '',
					'ssl_action' => isset($web_domain['ssl_action']) ? $web_domain['ssl_action'] : '',
					'stats_password' => isset($web_domain['stats_password']) ? $web_domain['stats_password'] : '',
					'stats_type' => isset($web_domain['stats_type']) ? $web_domain['stats_type'] : 'webalizer',
					'allow_override' => isset($web_domain['allow_override']) ? $web_domain['allow_override'] : 'All',
					'apache_directives' => isset($web_domain['apache_directives']) ? $web_domain['apache_directives'] : '',
					'nginx_directives' => isset($web_domain['nginx_directives']) ? $web_domain['nginx_directives'] : '',
					'php_fpm_use_socket' => isset($web_domain['php_fpm_use_socket']) ? $web_domain['php_fpm_use_socket'] : 'y',
					'pm' => isset($web_domain['pm']) ? $web_domain['pm'] : 'dynamic',
					'pm_max_children' => isset($web_domain['pm_max_children']) ? $web_domain['pm_max_children'] : 10,
					'pm_start_servers' => isset($web_domain['pm_start_servers']) ? $web_domain['pm_start_servers'] : 2,
					'pm_min_spare_servers' => isset($web_domain['pm_min_spare_servers']) ? $web_domain['pm_min_spare_servers'] : 1,
					'pm_max_spare_servers' => isset($web_domain['pm_max_spare_servers']) ? $web_domain['pm_max_spare_servers'] : 5,
					'pm_process_idle_timeout' => isset($web_domain['pm_process_idle_timeout']) ? $web_domain['pm_process_idle_timeout'] : 10,
					'pm_max_requests' => isset($web_domain['pm_max_requests']) ? $web_domain['pm_max_requests'] : 0,
					'custom_php_ini' => isset($web_domain['custom_php_ini']) ? $web_domain['custom_php_ini'] : '',
					'backup_interval' => isset($web_domain['backup_interval']) ? $web_domain['backup_interval'] : 'none',
					'backup_copies' => isset($web_domain['backup_copies']) ? $web_domain['backup_copies'] : 1,
					'backup_excludes' => isset($web_domain['backup_excludes']) ? $web_domain['backup_excludes'] : '',
					'active' => isset($web_domain['active']) ? $web_domain['active'] : 'y',
					'traffic_quota_lock' => isset($web_domain['traffic_quota_lock']) ? $web_domain['traffic_quota_lock'] : 'n',
					'fastcgi_php_version' => isset($web_domain['fastcgi_php_version']) ? $web_domain['fastcgi_php_version'] : '',
					'proxy_directives' => isset($web_domain['proxy_directives']) ? $web_domain['proxy_directives'] : '',
					'rewrite_rules' => isset($web_domain['rewrite_rules']) ? $web_domain['rewrite_rules'] : '',
					'added_date' => isset($web_domain['added_date']) ? $web_domain['added_date'] : date('Y-m-d'),
					'added_by' => isset($web_domain['added_by']) ? $web_domain['added_by'] : $this->CI->config->item('username'),
			);
			return $this->client->sites_web_vhost_subdomain_add($this->session_id, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update one record in Sites > Subdomain for website
	 *
	 * @param int   $client_id
	 * @param int   $domain_id
	 * @param array $web_domain server_id, ip_address, ipv6_address, domain, hd_quota, traffic_quota, cgi, ssi,
	 *                          suexec, errordocs, is_subdomainwww, subdomain, php, ruby, python, perl,
	 *                          redirect_type, redirect_path, seo_redirect, ssl, ssl_state, ssl_locality,
	 *                          ssl_organisation, ssl_organisation_unit, ssl_country, ssl_domain, ssl_request,
	 *                          ssl_key, ssl_cert, ssl_bundle, ssl_action, stats_password, stats_type,
	 *                          allow_override, apache_directives, nginx_directives, php_fpm_use_socket, pm,
	 *                          pm_max_children, pm_start_servers, pm_min_spare_servers pm_max_spare_servers,
	 *                          pm_process_idle_timeout, pm_max_requests, custom_php_ini, backup_interval,
	 *                          backup_copies, active, traffic_quota_lock, fastcgi_php_version,
	 *                          proxy_directives, rewrite_rules, added_date, added_by
	 * @return bool|array TRUE or error
	 */
	public function sites_web_vhost_subdomain_update($client_id = 0, $domain_id = 0, $web_domain = array())
	{
		try
		{
			$this->login();
			$record = $this->client->sites_web_vhost_subdomain_get($this->session_id, $domain_id);
			$params = array(
					'server_id' => isset($web_domain['server_id']) ? $web_domain['server_id'] : $record['server_id'],
					'ip_address' => isset($web_domain['ip_address']) ? $web_domain['ip_address'] : $record['ip_address'],
					'ipv6_address' => isset($web_domain['ipv6_address']) ? $web_domain['ipv6_address'] : $record['ipv6_address'],
					'domain' => isset($web_domain['domain']) ? $web_domain['domain'] : $record['domain'],
					'web_folder' => isset($web_domain['web_folder']) ? $web_domain['web_folder'] : $record['web_folder'],
					'type' => 'subdomain',
					'parent_domain_id' => isset($web_domain['parent_domain_id']) ? $web_domain['parent_domain_id'] : $record['parent_domain_id'],
					'vhost_type' => isset($web_domain['vhost_type']) ? $web_domain['vhost_type'] : $record['vhost_type'],
					'hd_quota' => isset($web_domain['hd_quota']) ? $web_domain['hd_quota'] : $record['hd_quota'],
					'traffic_quota' => isset($web_domain['traffic_quota']) ? $web_domain['traffic_quota'] : $record['traffic_quota'],
					'cgi' => isset($web_domain['cgi']) ? $web_domain['cgi'] : $record['cgi'],
					'ssi' => isset($web_domain['ssi']) ? $web_domain['ssi'] : $record['ssi'],
					'suexec' => isset($web_domain['suexec']) ? $web_domain['suexec'] : $record['suexec'],
					'errordocs' => isset($web_domain['errordocs']) ? $web_domain['errordocs'] : $record['errordocs'],
					'is_subdomainwww' => isset($web_domain['is_subdomainwww']) ? $web_domain['is_subdomainwww'] : $record['is_subdomainwww'],
					'subdomain' => isset($web_domain['subdomain']) ? $web_domain['subdomain'] : $record['subdomain'],
					'php' => isset($web_domain['php']) ? $web_domain['php'] : $record['php'],
					'ruby' => isset($web_domain['ruby']) ? $web_domain['ruby'] : $record['ruby'],
					'python' => isset($web_domain['python']) ? $web_domain['python'] : $record['python'],
					'perl' => isset($web_domain['perl']) ? $web_domain['perl'] : $record['perl'],
					'seo_redirect' => isset($web_domain['seo_redirect']) ? $web_domain['seo_redirect'] : $record['seo_redirect'],
					'redirect_path' => isset($web_domain['redirect_path']) ? $web_domain['redirect_path'] : $record['redirect_path'],
					'redirect_type' => isset($web_domain['redirect_type']) ? $web_domain['redirect_type'] : $record['redirect_type'],
					'ssl' => isset($web_domain['ssl']) ? $web_domain['ssl'] : $record['ssl'],
					'ssl_state' => isset($web_domain['ssl_state']) ? $web_domain['ssl_state'] : $record['ssl_state'],
					'ssl_locality' => isset($web_domain['ssl_locality']) ? $web_domain['ssl_locality'] : $record['ssl_locality'],
					'ssl_organisation' => isset($web_domain['ssl_organisation']) ? $web_domain['ssl_organisation'] : $record['ssl_organisation'],
					'ssl_organisation_unit' => isset($web_domain['ssl_organisation_unit']) ? $web_domain['ssl_organisation_unit'] : $record['ssl_organisation_unit'],
					'ssl_country' => isset($web_domain['ssl_country']) ? $web_domain['ssl_country'] : $record['ssl_country'],
					'ssl_domain' => isset($web_domain['ssl_domain']) ? $web_domain['ssl_domain'] : $record['ssl_domain'],
					'ssl_request' => isset($web_domain['ssl_request']) ? $web_domain['ssl_request'] : $record['ssl_request'],
					'ssl_key' => isset($web_domain['ssl_key']) ? $web_domain['ssl_key'] : $record['ssl_key'],
					'ssl_cert' => isset($web_domain['ssl_cert']) ? $web_domain['ssl_cert'] : $record['ssl_cert'],
					'ssl_bundle' => isset($web_domain['ssl_bundle']) ? $web_domain['ssl_bundle'] : $record['ssl_bundle'],
					'ssl_action' => isset($web_domain['ssl_action']) ? $web_domain['ssl_action'] : $record['ssl_action'],
					'stats_password' => isset($web_domain['stats_password']) ? $web_domain['stats_password'] : $record['stats_password'],
					'stats_type' => isset($web_domain['stats_type']) ? $web_domain['stats_type'] : $record['stats_type'],
					'allow_override' => isset($web_domain['allow_override']) ? $web_domain['allow_override'] : $record['allow_override'],
					'apache_directives' => isset($web_domain['apache_directives']) ? $web_domain['apache_directives'] : $record['apache_directives'],
					'nginx_directives' => isset($web_domain['nginx_directives']) ? $web_domain['nginx_directives'] : $record['nginx_directives'],
					'php_fpm_use_socket' => isset($web_domain['php_fpm_use_socket']) ? $web_domain['php_fpm_use_socket'] : $record['php_fpm_use_socket'],
					'pm' => isset($web_domain['pm']) ? $web_domain['pm'] : $record['pm'],
					'pm_max_children' => isset($web_domain['pm_max_children']) ? $web_domain['pm_max_children'] : $record['pm_max_children'],
					'pm_start_servers' => isset($web_domain['pm_start_servers']) ? $web_domain['pm_start_servers'] : $record['pm_start_servers'],
					'pm_min_spare_servers' => isset($web_domain['pm_min_spare_servers']) ? $web_domain['pm_min_spare_servers'] : $record['pm_min_spare_servers'],
					'pm_max_spare_servers' => isset($web_domain['pm_max_spare_servers']) ? $web_domain['pm_max_spare_servers'] : $record['pm_max_spare_servers'],
					'pm_process_idle_timeout' => isset($web_domain['pm_process_idle_timeout']) ? $web_domain['pm_process_idle_timeout'] : $record['pm_process_idle_timeout'],
					'pm_max_requests' => isset($web_domain['pm_max_requests']) ? $web_domain['pm_max_requests'] : $record['pm_max_requests'],
					'custom_php_ini' => isset($web_domain['custom_php_ini']) ? $web_domain['custom_php_ini'] : $record['custom_php_ini'],
					'backup_interval' => isset($web_domain['backup_interval']) ? $web_domain['backup_interval'] : $record['backup_interval'],
					'backup_copies' => isset($web_domain['backup_copies']) ? $web_domain['backup_copies'] : $record['backup_copies'],
					'backup_excludes' => isset($web_domain['backup_excludes']) ? $web_domain['backup_excludes'] : $record['backup_excludes'],
					'active' => isset($web_domain['active']) ? $web_domain['active'] : $record['active'],
					'traffic_quota_lock' => isset($web_domain['traffic_quota_lock']) ? $web_domain['traffic_quota_lock'] : $record['traffic_quota_lock'],
					'fastcgi_php_version' => isset($web_domain['fastcgi_php_version']) ? $web_domain['fastcgi_php_version'] : $record['fastcgi_php_version'],
					'proxy_directives' => isset($web_domain['proxy_directives']) ? $web_domain['proxy_directives'] : $record['proxy_directives'],
					'rewrite_rules' => isset($web_domain['rewrite_rules']) ? $web_domain['rewrite_rules'] : $record['rewrite_rules'],
					'added_date' => isset($web_domain['added_date']) ? $web_domain['added_date'] : $record['added_date'],
					'added_by' => isset($web_domain['added_by']) ? $web_domain['added_by'] : $record['added_by']
			);
			$this->client->sites_web_vhost_subdomain_update($this->session_id, $client_id, $domain_id, $params);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Delete one record in Sites > Subdomain for website
	 *
	 * @param int $domain_id
	 * @return bool|array TRUE or error
	 */
	public function sites_web_vhost_subdomain_delete($domain_id = 0)
	{
		try
		{
			$this->login();
			$this->client->sites_web_vhost_subdomain_delete($this->session_id, $domain_id);
			return TRUE;
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
	 * @return array webdomain.* or error
	 */
	public function sites_web_aliasdomain_get($domain_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->sites_web_aliasdomain_get($this->session_id, $domain_id));
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
	 * @param array $web_domain server_id, ip_address, ipv6_address, domain, hd_quota, traffic_quota, cgi, ssi,
	 *                          suexec, errordocs, is_subdomainwww, subdomain, php, ruby, python, perl,
	 *                          redirect_type, redirect_path, seo_redirect, ssl, ssl_state, ssl_locality,
	 *                          ssl_organisation, ssl_organisation_unit, ssl_country, ssl_domain, ssl_request,
	 *                          ssl_key, ssl_cert, ssl_bundle, ssl_action, stats_password, stats_type,
	 *                          allow_override, apache_directives, nginx_directives, php_fpm_use_socket, pm,
	 *                          pm_max_children, pm_start_servers, pm_min_spare_servers pm_max_spare_servers,
	 *                          pm_process_idle_timeout, pm_max_requests, custom_php_ini, backup_interval,
	 *                          backup_copies, active, traffic_quota_lock, fastcgi_php_version,
	 *                          proxy_directives, rewrite_rules, added_date, added_by
	 * @return int|array web_domain.domain_id or error
	 */
	public function sites_web_aliasdomain_add($client_id = 0, $web_domain = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($web_domain['server_id']) ? $web_domain['server_id'] : 0,
					'ip_address' => isset($web_domain['ip_address']) ? $web_domain['ip_address'] : '*',
					'ipv6_address' => isset($web_domain['ipv6_address']) ? $web_domain['ipv6_address'] : '192.168.1.55',
					'domain' => isset($web_domain['domain']) ? $web_domain['domain'] : '',
					'web_folder' => isset($web_domain['web_folder']) ? $web_domain['web_folder'] : '',
					'type' => 'alias',
					'parent_domain_id' => isset($web_domain['parent_domain_id']) ? $web_domain['parent_domain_id'] : 0,
					'vhost_type' => '',
					'hd_quota' => isset($web_domain['hd_quota']) ? $web_domain['hd_quota'] : -1,
					'traffic_quota' => isset($web_domain['traffic_quota']) ? $web_domain['traffic_quota'] : -1,
					'cgi' => isset($web_domain['cgi']) ? $web_domain['cgi'] : 'n',
					'ssi' => isset($web_domain['ssi']) ? $web_domain['ssi'] : 'n',
					'suexec' => isset($web_domain['suexec']) ? $web_domain['suexec'] : 'y',
					'errordocs' => isset($web_domain['errordocs']) ? $web_domain['errordocs'] : 1,
					'is_subdomainwww' => isset($web_domain['is_subdomainwww']) ? $web_domain['is_subdomainwww'] : 1,
					'subdomain' => isset($web_domain['subdomain']) ? $web_domain['subdomain'] : 'none',
					'php' => isset($web_domain['php']) ? $web_domain['php'] : 'y',
					'ruby' => isset($web_domain['ruby']) ? $web_domain['ruby'] : 'n',
					'python' => isset($web_domain['python']) ? $web_domain['python'] : 'n',
					'perl' => isset($web_domain['perl']) ? $web_domain['perl'] : 'n',
					'seo_redirect' => isset($web_domain['seo_redirect']) ? $web_domain['seo_redirect'] : '',
					'redirect_path' => isset($web_domain['redirect_path']) ? $web_domain['redirect_path'] : '',
					'redirect_type' => isset($web_domain['redirect_type']) ? $web_domain['redirect_type'] : '',
					'ssl' => isset($web_domain['ssl']) ? $web_domain['ssl'] : 'n',
					'ssl_state' => isset($web_domain['ssl_state']) ? $web_domain['ssl_state'] : '',
					'ssl_locality' => isset($web_domain['ssl_locality']) ? $web_domain['ssl_locality'] : '',
					'ssl_organisation' => isset($web_domain['ssl_organisation']) ? $web_domain['ssl_organisation'] : '',
					'ssl_organisation_unit' => isset($web_domain['ssl_organisation_unit']) ? $web_domain['ssl_organisation_unit'] : '',
					'ssl_country' => isset($web_domain['ssl_country']) ? $web_domain['ssl_country'] : '',
					'ssl_domain' => isset($web_domain['ssl_domain']) ? $web_domain['ssl_domain'] : '',
					'ssl_request' => isset($web_domain['ssl_request']) ? $web_domain['ssl_request'] : '',
					'ssl_key' => isset($web_domain['ssl_key']) ? $web_domain['ssl_key'] : '',
					'ssl_cert' => isset($web_domain['ssl_cert']) ? $web_domain['ssl_cert'] : '',
					'ssl_bundle' => isset($web_domain['ssl_bundle']) ? $web_domain['ssl_bundle'] : '',
					'ssl_action' => isset($web_domain['ssl_action']) ? $web_domain['ssl_action'] : '',
					'stats_password' => isset($web_domain['stats_password']) ? $web_domain['stats_password'] : '',
					'stats_type' => isset($web_domain['stats_type']) ? $web_domain['stats_type'] : 'webalizer',
					'allow_override' => isset($web_domain['allow_override']) ? $web_domain['allow_override'] : 'All',
					'apache_directives' => isset($web_domain['apache_directives']) ? $web_domain['apache_directives'] : '',
					'nginx_directives' => isset($web_domain['nginx_directives']) ? $web_domain['nginx_directives'] : '',
					'php_fpm_use_socket' => isset($web_domain['php_fpm_use_socket']) ? $web_domain['php_fpm_use_socket'] : 'y',
					'pm' => isset($web_domain['pm']) ? $web_domain['pm'] : 'dynamic',
					'pm_max_children' => isset($web_domain['pm_max_children']) ? $web_domain['pm_max_children'] : 10,
					'pm_start_servers' => isset($web_domain['pm_start_servers']) ? $web_domain['pm_start_servers'] : 2,
					'pm_min_spare_servers' => isset($web_domain['pm_min_spare_servers']) ? $web_domain['pm_min_spare_servers'] : 1,
					'pm_max_spare_servers' => isset($web_domain['pm_max_spare_servers']) ? $web_domain['pm_max_spare_servers'] : 5,
					'pm_process_idle_timeout' => isset($web_domain['pm_process_idle_timeout']) ? $web_domain['pm_process_idle_timeout'] : 10,
					'pm_max_requests' => isset($web_domain['pm_max_requests']) ? $web_domain['pm_max_requests'] : 0,
					'custom_php_ini' => isset($web_domain['custom_php_ini']) ? $web_domain['custom_php_ini'] : '',
					'backup_interval' => isset($web_domain['backup_interval']) ? $web_domain['backup_interval'] : 'none',
					'backup_copies' => isset($web_domain['backup_copies']) ? $web_domain['backup_copies'] : 1,
					'backup_excludes' => isset($web_domain['backup_excludes']) ? $web_domain['backup_excludes'] : '',
					'active' => isset($web_domain['active']) ? $web_domain['active'] : 'y',
					'traffic_quota_lock' => isset($web_domain['traffic_quota_lock']) ? $web_domain['traffic_quota_lock'] : 'n',
					'fastcgi_php_version' => isset($web_domain['fastcgi_php_version']) ? $web_domain['fastcgi_php_version'] : '',
					'proxy_directives' => isset($web_domain['proxy_directives']) ? $web_domain['proxy_directives'] : '',
					'rewrite_rules' => isset($web_domain['rewrite_rules']) ? $web_domain['rewrite_rules'] : '',
					'added_date' => isset($web_domain['added_date']) ? $web_domain['added_date'] : date('Y-m-d'),
					'added_by' => isset($web_domain['added_by']) ? $web_domain['added_by'] : $this->CI->config->item('username'),
			);
			return $this->client->sites_web_aliasdomain_add($this->session_id, $client_id, $params);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update one record in Sites > Aliasdomain for website
	 *
	 * @param int   $client_id
	 * @param int   $domain_id
	 * @param array $web_domain server_id, ip_address, ipv6_address, domain, hd_quota, traffic_quota, cgi, ssi,
	 *                          suexec, errordocs, is_subdomainwww, subdomain, php, ruby, python, perl,
	 *                          redirect_type, redirect_path, seo_redirect, ssl, ssl_state, ssl_locality,
	 *                          ssl_organisation, ssl_organisation_unit, ssl_country, ssl_domain, ssl_request,
	 *                          ssl_key, ssl_cert, ssl_bundle, ssl_action, stats_password, stats_type,
	 *                          allow_override, apache_directives, nginx_directives, php_fpm_use_socket, pm,
	 *                          pm_max_children, pm_start_servers, pm_min_spare_servers pm_max_spare_servers,
	 *                          pm_process_idle_timeout, pm_max_requests, custom_php_ini, backup_interval,
	 *                          backup_copies, active, traffic_quota_lock, fastcgi_php_version,
	 *                          proxy_directives, rewrite_rules, added_date, added_by
	 * @return bool|array TRUE or error
	 */
	public function sites_web_aliasdomain_update($client_id = 0, $domain_id = 0, $web_domain = array())
	{
		try
		{
			$this->login();
			$record = $this->client->sites_web_aliasdomain_get($this->session_id, $domain_id);
			$params = array(
					'server_id' => isset($web_domain['server_id']) ? $web_domain['server_id'] : $record['server_id'],
					'ip_address' => isset($web_domain['ip_address']) ? $web_domain['ip_address'] : $record['ip_address'],
					'ipv6_address' => isset($web_domain['ipv6_address']) ? $web_domain['ipv6_address'] : $record['ipv6_address'],
					'domain' => isset($web_domain['domain']) ? $web_domain['domain'] : $record['domain'],
					'web_folder' => isset($web_domain['web_folder']) ? $web_domain['web_folder'] : $record['web_folder'],
					'type' => 'alias',
					'parent_domain_id' => isset($web_domain['parent_domain_id']) ? $web_domain['parent_domain_id'] : $record['parent_domain_id'],
					'vhost_type' => '',
					'hd_quota' => isset($web_domain['hd_quota']) ? $web_domain['hd_quota'] : $record['hd_quota'],
					'traffic_quota' => isset($web_domain['traffic_quota']) ? $web_domain['traffic_quota'] : $record['traffic_quota'],
					'cgi' => isset($web_domain['cgi']) ? $web_domain['cgi'] : $record['cgi'],
					'ssi' => isset($web_domain['ssi']) ? $web_domain['ssi'] : $record['ssi'],
					'suexec' => isset($web_domain['suexec']) ? $web_domain['suexec'] : $record['suexec'],
					'errordocs' => isset($web_domain['errordocs']) ? $web_domain['errordocs'] : $record['errordocs'],
					'is_subdomainwww' => isset($web_domain['is_subdomainwww']) ? $web_domain['is_subdomainwww'] : $record['is_subdomainwww'],
					'subdomain' => isset($web_domain['subdomain']) ? $web_domain['subdomain'] : $record['subdomain'],
					'php' => isset($web_domain['php']) ? $web_domain['php'] : $record['php'],
					'ruby' => isset($web_domain['ruby']) ? $web_domain['ruby'] : $record['ruby'],
					'python' => isset($web_domain['python']) ? $web_domain['python'] : $record['python'],
					'perl' => isset($web_domain['perl']) ? $web_domain['perl'] : $record['perl'],
					'seo_redirect' => isset($web_domain['seo_redirect']) ? $web_domain['seo_redirect'] : $record['seo_redirect'],
					'redirect_path' => isset($web_domain['redirect_path']) ? $web_domain['redirect_path'] : $record['redirect_path'],
					'redirect_type' => isset($web_domain['redirect_type']) ? $web_domain['redirect_type'] : $record['redirect_type'],
					'ssl' => isset($web_domain['ssl']) ? $web_domain['ssl'] : $record['ssl'],
					'ssl_state' => isset($web_domain['ssl_state']) ? $web_domain['ssl_state'] : $record['ssl_state'],
					'ssl_locality' => isset($web_domain['ssl_locality']) ? $web_domain['ssl_locality'] : $record['ssl_locality'],
					'ssl_organisation' => isset($web_domain['ssl_organisation']) ? $web_domain['ssl_organisation'] : $record['ssl_organisation'],
					'ssl_organisation_unit' => isset($web_domain['ssl_organisation_unit']) ? $web_domain['ssl_organisation_unit'] : $record['ssl_organisation_unit'],
					'ssl_country' => isset($web_domain['ssl_country']) ? $web_domain['ssl_country'] : $record['ssl_country'],
					'ssl_domain' => isset($web_domain['ssl_domain']) ? $web_domain['ssl_domain'] : $record['ssl_domain'],
					'ssl_request' => isset($web_domain['ssl_request']) ? $web_domain['ssl_request'] : $record['ssl_request'],
					'ssl_key' => isset($web_domain['ssl_key']) ? $web_domain['ssl_key'] : $record['ssl_key'],
					'ssl_cert' => isset($web_domain['ssl_cert']) ? $web_domain['ssl_cert'] : $record['ssl_cert'],
					'ssl_bundle' => isset($web_domain['ssl_bundle']) ? $web_domain['ssl_bundle'] : $record['ssl_bundle'],
					'ssl_action' => isset($web_domain['ssl_action']) ? $web_domain['ssl_action'] : $record['ssl_action'],
					'stats_password' => isset($web_domain['stats_password']) ? $web_domain['stats_password'] : $record['stats_password'],
					'stats_type' => isset($web_domain['stats_type']) ? $web_domain['stats_type'] : $record['stats_type'],
					'allow_override' => isset($web_domain['allow_override']) ? $web_domain['allow_override'] : $record['allow_override'],
					'apache_directives' => isset($web_domain['apache_directives']) ? $web_domain['apache_directives'] : $record['apache_directives'],
					'nginx_directives' => isset($web_domain['nginx_directives']) ? $web_domain['nginx_directives'] : $record['nginx_directives'],
					'php_fpm_use_socket' => isset($web_domain['php_fpm_use_socket']) ? $web_domain['php_fpm_use_socket'] : $record['php_fpm_use_socket'],
					'pm' => isset($web_domain['pm']) ? $web_domain['pm'] : $record['pm'],
					'pm_max_children' => isset($web_domain['pm_max_children']) ? $web_domain['pm_max_children'] : $record['pm_max_children'],
					'pm_start_servers' => isset($web_domain['pm_start_servers']) ? $web_domain['pm_start_servers'] : $record['pm_start_servers'],
					'pm_min_spare_servers' => isset($web_domain['pm_min_spare_servers']) ? $web_domain['pm_min_spare_servers'] : $record['pm_min_spare_servers'],
					'pm_max_spare_servers' => isset($web_domain['pm_max_spare_servers']) ? $web_domain['pm_max_spare_servers'] : $record['pm_max_spare_servers'],
					'pm_process_idle_timeout' => isset($web_domain['pm_process_idle_timeout']) ? $web_domain['pm_process_idle_timeout'] : $record['pm_process_idle_timeout'],
					'pm_max_requests' => isset($web_domain['pm_max_requests']) ? $web_domain['pm_max_requests'] : $record['pm_max_requests'],
					'custom_php_ini' => isset($web_domain['custom_php_ini']) ? $web_domain['custom_php_ini'] : $record['custom_php_ini'],
					'backup_interval' => isset($web_domain['backup_interval']) ? $web_domain['backup_interval'] : $record['backup_interval'],
					'backup_copies' => isset($web_domain['backup_copies']) ? $web_domain['backup_copies'] : $record['backup_copies'],
					'backup_excludes' => isset($web_domain['backup_excludes']) ? $web_domain['backup_excludes'] : $record['backup_excludes'],
					'active' => isset($web_domain['active']) ? $web_domain['active'] : $record['active'],
					'traffic_quota_lock' => isset($web_domain['traffic_quota_lock']) ? $web_domain['traffic_quota_lock'] : $record['traffic_quota_lock'],
					'fastcgi_php_version' => isset($web_domain['fastcgi_php_version']) ? $web_domain['fastcgi_php_version'] : $record['fastcgi_php_version'],
					'proxy_directives' => isset($web_domain['proxy_directives']) ? $web_domain['proxy_directives'] : $record['proxy_directives'],
					'rewrite_rules' => isset($web_domain['rewrite_rules']) ? $web_domain['rewrite_rules'] : $record['rewrite_rules'],
					'added_date' => isset($web_domain['added_date']) ? $web_domain['added_date'] : $record['added_date'],
					'added_by' => isset($web_domain['added_by']) ? $web_domain['added_by'] : $record['added_by']
			);
			$this->client->sites_web_aliasdomain_update($this->session_id, $client_id, $domain_id, $params);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Delete one record in Sites > Aliasdomain for website
	 *
	 * @param int $domain_id
	 * @return bool|array TRUE or error
	 */
	public function sites_web_aliasdomain_delete($domain_id = 0)
	{
		try
		{
			$this->login();
			$this->client->sites_web_aliasdomain_delete($this->session_id, $domain_id);
			return TRUE;
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
	 * @return array webdomain.* or error
	 */
	public function sites_web_subdomain_get($domain_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->sites_web_subdomain_get($this->session_id, $domain_id));
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
	 * @param array $web_domain server_id, ip_address, domain, type, parent_domain_id, vhost_type, hd_quota,
	 *                          traffic_quota, cgi, ssi, suexec, errordocs, is_subdomainwww, subdomain, php,
	 *                          ruby, python, perl, redirect_type, redirect_path, seo_redirect, ssl, ssl_state,
	 *                          ssl_locality, ssl_organisation, ssl_organisation_unit, ssl_country, ssl_domain,
	 *                          ssl_request, ssl_key, ssl_cert, ssl_bundle, ssl_action, stats_password,
	 *                          stats_type, allow_override, apache_directives, nginx_directives,
	 *                          php_fpm_use_socket, pm, pm_max_children, pm_start_servers, pm_min_spare_servers
	 *                          pm_max_spare_servers, pm_process_idle_timeout, pm_max_requests, custom_php_ini,
	 *                          backup_interval, backup_copies, active, traffic_quota_lock,
	 *                          fastcgi_php_version, proxy_directives, rewrite_rules, added_date, added_by
	 * @return int|array web_domain.domain_id or error
	 */
	public function sites_web_subdomain_add($client_id = 0, $web_domain = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($web_domain['server_id']) ? $web_domain['server_id'] : 0,
					'ip_address' => isset($web_domain['ip_address']) ? $web_domain['ip_address'] : '*',
					'domain' => isset($web_domain['domain']) ? $web_domain['domain'] : '',
					'type' => 'subdomain',
					'parent_domain_id' => isset($web_domain['parent_domain_id']) ? $web_domain['parent_domain_id'] : 0,
					'vhost_type' => isset($web_domain['vhost_type']) ? $web_domain['vhost_type'] : 'name',
					'hd_quota' => isset($web_domain['hd_quota']) ? $web_domain['hd_quota'] : -1,
					'traffic_quota' => isset($web_domain['traffic_quota']) ? $web_domain['traffic_quota'] : -1,
					'cgi' => isset($web_domain['cgi']) ? $web_domain['cgi'] : 'n',
					'ssi' => isset($web_domain['ssi']) ? $web_domain['ssi'] : 'n',
					'suexec' => isset($web_domain['suexec']) ? $web_domain['suexec'] : 'y',
					'errordocs' => isset($web_domain['errordocs']) ? $web_domain['errordocs'] : 1,
					'is_subdomainwww' => isset($web_domain['is_subdomainwww']) ? $web_domain['is_subdomainwww'] : 1,
					'subdomain' => isset($web_domain['subdomain']) ? $web_domain['subdomain'] : 'none',
					'php' => isset($web_domain['php']) ? $web_domain['php'] : 'y',
					'ruby' => isset($web_domain['ruby']) ? $web_domain['ruby'] : 'n',
					'python' => isset($web_domain['python']) ? $web_domain['python'] : 'n',
					'perl' => isset($web_domain['perl']) ? $web_domain['perl'] : 'n',
					'redirect_type' => isset($web_domain['redirect_type']) ? $web_domain['redirect_type'] : '',
					'redirect_path' => isset($web_domain['redirect_path']) ? $web_domain['redirect_path'] : '',
					'seo_redirect' => isset($web_domain['seo_redirect']) ? $web_domain['seo_redirect'] : '',
					'ssl' => isset($web_domain['ssl']) ? $web_domain['ssl'] : 'n',
					'ssl_state' => isset($web_domain['ssl_state']) ? $web_domain['ssl_state'] : '',
					'ssl_locality' => isset($web_domain['ssl_locality']) ? $web_domain['ssl_locality'] : '',
					'ssl_organisation' => isset($web_domain['ssl_organisation']) ? $web_domain['ssl_organisation'] : '',
					'ssl_organisation_unit' => isset($web_domain['ssl_organisation_unit']) ? $web_domain['ssl_organisation_unit'] : '',
					'ssl_country' => isset($web_domain['ssl_country']) ? $web_domain['ssl_country'] : '',
					'ssl_domain' => isset($web_domain['ssl_domain']) ? $web_domain['ssl_domain'] : '',
					'ssl_request' => isset($web_domain['ssl_request']) ? $web_domain['ssl_request'] : '',
					'ssl_key' => isset($web_domain['ssl_key']) ? $web_domain['ssl_key'] : '',
					'ssl_cert' => isset($web_domain['ssl_cert']) ? $web_domain['ssl_cert'] : '',
					'ssl_bundle' => isset($web_domain['ssl_bundle']) ? $web_domain['ssl_bundle'] : '',
					'ssl_action' => isset($web_domain['ssl_action']) ? $web_domain['ssl_action'] : '',
					'stats_password' => isset($web_domain['stats_password']) ? $web_domain['stats_password'] : '',
					'stats_type' => isset($web_domain['stats_type']) ? $web_domain['stats_type'] : 'webalizer',
					'allow_override' => isset($web_domain['allow_override']) ? $web_domain['allow_override'] : 'All',
					'apache_directives' => isset($web_domain['apache_directives']) ? $web_domain['apache_directives'] : '',
					'nginx_directives' => isset($web_domain['nginx_directives']) ? $web_domain['nginx_directives'] : '',
					'php_fpm_use_socket' => isset($web_domain['php_fpm_use_socket']) ? $web_domain['php_fpm_use_socket'] : 'y',
					'pm' => isset($web_domain['pm']) ? $web_domain['pm'] : 'dynamic',
					'pm_max_children' => isset($web_domain['pm_max_children']) ? $web_domain['pm_max_children'] : 10,
					'pm_start_servers' => isset($web_domain['pm_start_servers']) ? $web_domain['pm_start_servers'] : 2,
					'pm_min_spare_servers' => isset($web_domain['pm_min_spare_servers']) ? $web_domain['pm_min_spare_servers'] : 1,
					'pm_max_spare_servers' => isset($web_domain['pm_max_spare_servers']) ? $web_domain['pm_max_spare_servers'] : 5,
					'pm_process_idle_timeout' => isset($web_domain['pm_process_idle_timeout']) ? $web_domain['pm_process_idle_timeout'] : 10,
					'pm_max_requests' => isset($web_domain['pm_max_requests']) ? $web_domain['pm_max_requests'] : 0,
					'custom_php_ini' => isset($web_domain['custom_php_ini']) ? $web_domain['custom_php_ini'] : '',
					'backup_interval' => isset($web_domain['backup_interval']) ? $web_domain['backup_interval'] : 'none',
					'backup_copies' => isset($web_domain['backup_copies']) ? $web_domain['backup_copies'] : 1,
					'active' => isset($web_domain['active']) ? $web_domain['active'] : 'y',
					'traffic_quota_lock' => isset($web_domain['traffic_quota_lock']) ? $web_domain['traffic_quota_lock'] : 'n',
					'fastcgi_php_version' => isset($web_domain['fastcgi_php_version']) ? $web_domain['fastcgi_php_version'] : '',
					'proxy_directives' => isset($web_domain['proxy_directives']) ? $web_domain['proxy_directives'] : '',
					'rewrite_rules' => isset($web_domain['rewrite_rules']) ? $web_domain['rewrite_rules'] : '',
					'added_date' => isset($web_domain['added_date']) ? $web_domain['added_date'] : date('Y-m-d'),
					'added_by' => isset($web_domain['added_by']) ? $web_domain['added_by'] : '',
			);
			return $this->client->sites_web_subdomain_add($this->session_id, $client_id, $params, $readonly = FALSE);
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Update one record in Sites > Subdomain for website
	 *
	 * @param int   $client_id
	 * @param int   $domain_id
	 * @param array $web_domain server_id, ip_address, ipv6_address, domain, hd_quota, traffic_quota, cgi, ssi,
	 *                          suexec, errordocs, is_subdomainwww, subdomain, php, ruby, python, perl,
	 *                          redirect_type, redirect_path, seo_redirect, ssl, ssl_state, ssl_locality,
	 *                          ssl_organisation, ssl_organisation_unit, ssl_country, ssl_domain, ssl_request,
	 *                          ssl_key, ssl_cert, ssl_bundle, ssl_action, stats_password, stats_type,
	 *                          allow_override, apache_directives, nginx_directives, php_fpm_use_socket, pm,
	 *                          pm_max_children, pm_start_servers, pm_min_spare_servers pm_max_spare_servers,
	 *                          pm_process_idle_timeout, pm_max_requests, custom_php_ini, backup_interval,
	 *                          backup_copies, active, traffic_quota_lock, fastcgi_php_version,
	 *                          proxy_directives, rewrite_rules, added_date, added_by
	 * @return bool|array TRUE or error
	 */
	public function sites_web_subdomain_update($client_id = 0, $domain_id = 0, $web_domain = array())
	{
		try
		{
			$this->login();
			$record = $this->client->sites_web_subdomain_get($this->session_id, $domain_id);
			$params = array(
					'server_id' => isset($web_domain['server_id']) ? $web_domain['server_id'] : $record['server_id'],
					'ip_address' => isset($web_domain['ip_address']) ? $web_domain['ip_address'] : $record['ip_address'],
					'ipv6_address' => isset($web_domain['ipv6_address']) ? $web_domain['ipv6_address'] : $record['ipv6_address'],
					'domain' => isset($web_domain['domain']) ? $web_domain['domain'] : $record['domain'],
					'web_folder' => isset($web_domain['web_folder']) ? $web_domain['web_folder'] : $record['web_folder'],
					'type' => 'subdomain',
					'parent_domain_id' => isset($web_domain['parent_domain_id']) ? $web_domain['parent_domain_id'] : $record['parent_domain_id'],
					'vhost_type' => isset($web_domain['vhost_type']) ? $web_domain['vhost_type'] : $record['vhost_type'],
					'hd_quota' => isset($web_domain['hd_quota']) ? $web_domain['hd_quota'] : $record['hd_quota'],
					'traffic_quota' => isset($web_domain['traffic_quota']) ? $web_domain['traffic_quota'] : $record['traffic_quota'],
					'cgi' => isset($web_domain['cgi']) ? $web_domain['cgi'] : $record['cgi'],
					'ssi' => isset($web_domain['ssi']) ? $web_domain['ssi'] : $record['ssi'],
					'suexec' => isset($web_domain['suexec']) ? $web_domain['suexec'] : $record['suexec'],
					'errordocs' => isset($web_domain['errordocs']) ? $web_domain['errordocs'] : $record['errordocs'],
					'is_subdomainwww' => isset($web_domain['is_subdomainwww']) ? $web_domain['is_subdomainwww'] : $record['is_subdomainwww'],
					'subdomain' => isset($web_domain['subdomain']) ? $web_domain['subdomain'] : $record['subdomain'],
					'php' => isset($web_domain['php']) ? $web_domain['php'] : $record['php'],
					'ruby' => isset($web_domain['ruby']) ? $web_domain['ruby'] : $record['ruby'],
					'python' => isset($web_domain['python']) ? $web_domain['python'] : $record['python'],
					'perl' => isset($web_domain['perl']) ? $web_domain['perl'] : $record['perl'],
					'seo_redirect' => isset($web_domain['seo_redirect']) ? $web_domain['seo_redirect'] : $record['seo_redirect'],
					'redirect_path' => isset($web_domain['redirect_path']) ? $web_domain['redirect_path'] : $record['redirect_path'],
					'redirect_type' => isset($web_domain['redirect_type']) ? $web_domain['redirect_type'] : $record['redirect_type'],
					'ssl' => isset($web_domain['ssl']) ? $web_domain['ssl'] : $record['ssl'],
					'ssl_state' => isset($web_domain['ssl_state']) ? $web_domain['ssl_state'] : $record['ssl_state'],
					'ssl_locality' => isset($web_domain['ssl_locality']) ? $web_domain['ssl_locality'] : $record['ssl_locality'],
					'ssl_organisation' => isset($web_domain['ssl_organisation']) ? $web_domain['ssl_organisation'] : $record['ssl_organisation'],
					'ssl_organisation_unit' => isset($web_domain['ssl_organisation_unit']) ? $web_domain['ssl_organisation_unit'] : $record['ssl_organisation_unit'],
					'ssl_country' => isset($web_domain['ssl_country']) ? $web_domain['ssl_country'] : $record['ssl_country'],
					'ssl_domain' => isset($web_domain['ssl_domain']) ? $web_domain['ssl_domain'] : $record['ssl_domain'],
					'ssl_request' => isset($web_domain['ssl_request']) ? $web_domain['ssl_request'] : $record['ssl_request'],
					'ssl_key' => isset($web_domain['ssl_key']) ? $web_domain['ssl_key'] : $record['ssl_key'],
					'ssl_cert' => isset($web_domain['ssl_cert']) ? $web_domain['ssl_cert'] : $record['ssl_cert'],
					'ssl_bundle' => isset($web_domain['ssl_bundle']) ? $web_domain['ssl_bundle'] : $record['ssl_bundle'],
					'ssl_action' => isset($web_domain['ssl_action']) ? $web_domain['ssl_action'] : $record['ssl_action'],
					'stats_password' => isset($web_domain['stats_password']) ? $web_domain['stats_password'] : $record['stats_password'],
					'stats_type' => isset($web_domain['stats_type']) ? $web_domain['stats_type'] : $record['stats_type'],
					'allow_override' => isset($web_domain['allow_override']) ? $web_domain['allow_override'] : $record['allow_override'],
					'apache_directives' => isset($web_domain['apache_directives']) ? $web_domain['apache_directives'] : $record['apache_directives'],
					'nginx_directives' => isset($web_domain['nginx_directives']) ? $web_domain['nginx_directives'] : $record['nginx_directives'],
					'php_fpm_use_socket' => isset($web_domain['php_fpm_use_socket']) ? $web_domain['php_fpm_use_socket'] : $record['php_fpm_use_socket'],
					'pm' => isset($web_domain['pm']) ? $web_domain['pm'] : $record['pm'],
					'pm_max_children' => isset($web_domain['pm_max_children']) ? $web_domain['pm_max_children'] : $record['pm_max_children'],
					'pm_start_servers' => isset($web_domain['pm_start_servers']) ? $web_domain['pm_start_servers'] : $record['pm_start_servers'],
					'pm_min_spare_servers' => isset($web_domain['pm_min_spare_servers']) ? $web_domain['pm_min_spare_servers'] : $record['pm_min_spare_servers'],
					'pm_max_spare_servers' => isset($web_domain['pm_max_spare_servers']) ? $web_domain['pm_max_spare_servers'] : $record['pm_max_spare_servers'],
					'pm_process_idle_timeout' => isset($web_domain['pm_process_idle_timeout']) ? $web_domain['pm_process_idle_timeout'] : $record['pm_process_idle_timeout'],
					'pm_max_requests' => isset($web_domain['pm_max_requests']) ? $web_domain['pm_max_requests'] : $record['pm_max_requests'],
					'custom_php_ini' => isset($web_domain['custom_php_ini']) ? $web_domain['custom_php_ini'] : $record['custom_php_ini'],
					'backup_interval' => isset($web_domain['backup_interval']) ? $web_domain['backup_interval'] : $record['backup_interval'],
					'backup_copies' => isset($web_domain['backup_copies']) ? $web_domain['backup_copies'] : $record['backup_copies'],
					'backup_excludes' => isset($web_domain['backup_excludes']) ? $web_domain['backup_excludes'] : $record['backup_excludes'],
					'active' => isset($web_domain['active']) ? $web_domain['active'] : $record['active'],
					'traffic_quota_lock' => isset($web_domain['traffic_quota_lock']) ? $web_domain['traffic_quota_lock'] : $record['traffic_quota_lock'],
					'fastcgi_php_version' => isset($web_domain['fastcgi_php_version']) ? $web_domain['fastcgi_php_version'] : $record['fastcgi_php_version'],
					'proxy_directives' => isset($web_domain['proxy_directives']) ? $web_domain['proxy_directives'] : $record['proxy_directives'],
					'rewrite_rules' => isset($web_domain['rewrite_rules']) ? $web_domain['rewrite_rules'] : $record['rewrite_rules'],
					'added_date' => isset($web_domain['added_date']) ? $web_domain['added_date'] : $record['added_date'],
					'added_by' => isset($web_domain['added_by']) ? $web_domain['added_by'] : $record['added_by']
			);
			$this->client->sites_web_subdomain_update($this->session_id, $client_id, $domain_id, $params);
			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

	/**
	 * Delete one record in Sites > Subdomain for website
	 *
	 * @param int $domain_id
	 * @return bool|array TRUE or error
	 */
	public function sites_web_subdomain_delete($domain_id = 0)
	{
		try
		{
			$this->login();
			$this->client->sites_web_subdomain_delete($this->session_id, $domain_id);
			return TRUE;
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
	 * @return array web_folder.* or error
	 */
	public function sites_web_folder_get($web_folder_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->sites_web_folder_get($this->session_id, $web_folder_id));
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
	 * @param array $web_folder server_id, parent_domain_id, path, active
	 * @return int|array web_folder.web_folder_id or error
	 */
	public function sites_web_folder_add($client_id = 0, $web_folder = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($web_folder['server_id']) ? $web_folder['server_id'] : 0,
					'parent_domain_id' => isset($web_folder['parent_domain_id']) ? $web_folder['parent_domain_id'] : 0,
					'path' => isset($web_folder['path']) ? $web_folder['path'] : '',
					'active' => isset($web_folder['active']) ? $web_folder['active'] : 'y',
			);
			return $this->client->sites_web_folder_add($this->session_id, $client_id, $params);
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
	 * @param array $web_folder server_id, parent_domain_id, path, active
	 * @return bool|array TRUE or error
	 */
	public function sites_web_folder_update($client_id = 0, $web_folder_id = 0, $web_folder = array())
	{
		try
		{
			$this->login();
			$record = $this->client->sites_web_folder_get($this->session_id, $web_folder_id);
			$params = array(
					'server_id' => isset($web_folder['server_id']) ? $web_folder['server_id'] : $record['server_id'],
					'parent_domain_id' => isset($web_folder['parent_domain_id']) ? $web_folder['parent_domain_id'] : $record['parent_domain_id'],
					'path' => isset($web_folder['path']) ? $web_folder['path'] : $record['path'],
					'active' => isset($web_folder['active']) ? $web_folder['active'] : $record['active'],
			);
			$this->client->sites_web_folder_update($this->session_id, $client_id, $web_folder_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function sites_web_folder_delete($web_folder_id = 0)
	{
		try
		{
			$this->login();
			$this->client->sites_web_folder_delete($this->session_id, $web_folder_id);
			return TRUE;
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
	 * @return array web_folder_user.* or error
	 */
	public function sites_web_folder_user_get($web_folder_user_id = 0)
	{
		try
		{
			$this->login();
			return $this->get_empty($this->client->sites_web_folder_user_get($this->session_id, $web_folder_user_id));
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
	 * @param array $web_folder_user server_id, web_folder_id, username, password, active
	 * @return int|array web_folder_user.web_folder_user_id or error
	 */
	public function sites_web_folder_user_add($client_id = 0, $web_folder_id = 0, $web_folder_user = array())
	{
		try
		{
			$this->login();
			$params = array(
					'server_id' => isset($web_folder_user['server_id']) ? $web_folder_user['server_id'] : 0,
					'web_folder_id' => $web_folder_id,
					'username' => isset($web_folder_user['username']) ? $web_folder_user['username'] : '',
					'password' => isset($web_folder_user['password']) ? $web_folder_user['password'] : '',
					'active' => isset($web_folder_user['active']) ? $web_folder_user['active'] : 'y',
			);
			return $this->client->sites_web_folder_user_add($this->session_id, $client_id, $params);
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
	 * @param array $web_folder_user server_id, web_folder_id, username, password, active
	 * @return bool|array TRUE or error
	 */
	public function sites_web_folder_user_update($client_id = 0, $web_folder_user_id = 0, $web_folder_user = array())
	{
		try
		{
			$this->login();
			$record = $this->client->sites_web_folder_user_get($this->session_id, $web_folder_user_id);
			$params = array(
					'server_id' => isset($web_folder_user['server_id']) ? $web_folder_user['server_id'] : $record['server_id'],
					'web_folder_id' => $record['web_folder_id'],
					'username' => isset($web_folder_user['username']) ? $web_folder_user['username'] : $record['username'],
					'password' => isset($web_folder_user['password']) ? $web_folder_user['password'] : $record['password'],
					'active' => isset($web_folder_user['active']) ? $web_folder_user['active'] : $record['active'],
			);
			$this->client->sites_web_folder_user_update($this->session_id, $client_id, $web_folder_user_id, $params);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function sites_web_folder_user_delete($web_folder_user_id = 0)
	{
		try
		{
			$this->login();
			$this->client->sites_web_folder_user_delete($this->session_id, $web_folder_user_id);
			return TRUE;
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
	 * @return bool|array TRUE or error
	 */
	public function sites_web_domain_set_status($domain_id = 0, $status = 'inactive')
	{
		try
		{
			$this->login();
			$this->client->sites_web_domain_set_status($this->session_id, $domain_id, $status);
			return TRUE;
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
	 * @return array web_database.* or error
	 */
	public function sites_database_get_all_by_user($client_id = 0)
	{
		try
		{
			$this->login();
			return $this->client->sites_database_get_all_by_user($this->session_id, $client_id);
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
			return $this->client->get_function_list($this->session_id);
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
		$data['username'] = $this->CI->config->item('username');
		$data['password'] = $this->CI->config->item('password');
		$data['soap_uri'] = $this->CI->config->item('soap_uri');
		$data['soap_location'] = $this->CI->config->item('soap_location');
		$data['invoices_dir'] = $this->CI->config->item('invoices_dir');
		return $data;
	}

	/**
	 * Destructor - Cancels a Remote Session
	 */
	public function __destruct()
	{
		if ($this->session_id)
		{
			$this->client->logout($this->session_id);
			log_message('info', 'ISPConfig: Logged out.');
		}
	}

}
