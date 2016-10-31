<?php
/**
 * codeigniter-ispconfig-new
 *
 * @package  codeigniter-ispconfig-new
 * @author   Natan Felles <natanfelles@gmail.com>
 */
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class Ispconfig_billing
 */
class Ispconfig_billing extends Ispconfig {


	/**
	 * Ispconfig_billing constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Get one record from Billing > Invoices > Invoice
	 *
	 * @param int $invoice_id
	 *
	 * @return array invoice.* or error
	 */
	public function invoice_get($invoice_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_id' => $invoice_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->billing_invoice_get($this->ID, $invoice_id));
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}


	/**
	 * Get Billing > Invoices by Client
	 *
	 * @param int $client_id
	 * @param int $quantity
	 *
	 * @return array invoice.* or error
	 */
	public function invoice_get_by_client($client_id, $quantity)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'quantity'  => $quantity,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->billing_invoice_get_by_client($this->ID, $client_id, $quantity));
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
	 * @param array $invoice invoice_company_id, company_name, contact_name, street, zip, city,
	 *                       state, country, email, vat_id, payment_gateway, notes
	 *
	 * @return int|array invoice.invoice_id or error
	 */
	public function invoice_add($client_id, $invoice)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'invoice'   => $invoice,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$client = $this->SoapClient->client_get($this->ID, $client_id);
			$client_settings = $this->SoapClient->billing_client_settings_get($this->ID, $client_id);
			$params = array(
				'invoice_company_id' => isset($invoice['invoice_company_id']) ? $invoice['invoice_company_id'] : $client_settings['invoice_company_id'],
				'client_id'          => $client_id,
				'company_name'       => isset($invoice['company_name']) ? $invoice['company_name'] : $client['company_name'],
				'contact_name'       => isset($invoice['contact_name']) ? $invoice['contact_name'] : $client['contact_name'],
				'street'             => isset($invoice['street']) ? $invoice['street'] : $client['street'],
				'zip'                => isset($invoice['zip']) ? $invoice['zip'] : $client['zip'],
				'city'               => isset($invoice['city']) ? $invoice['city'] : $client['city'],
				'state'              => isset($invoice['state']) ? $invoice['state'] : $client['state'],
				'country'            => isset($invoice['country']) ? $invoice['country'] : $client['country'],
				'email'              => isset($invoice['email']) ? $invoice['email'] : $client['email'],
				'vat_id'             => isset($invoice['vat_id']) ? $invoice['vat_id'] : $client['vat_id'],
				'payment_gateway'    => isset($invoice['payment_gateway']) ? $invoice['payment_gateway'] : $client_settings['payment_gateway'],
				'notes'              => isset($invoice['notes']) ? $invoice['notes'] : '',
			);

			return $this->SoapClient->billing_invoice_add($this->ID, $client_id, $params);
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
	 * @param array $invoice invoice_company_id, company_name, contact_name, street, zip, city,
	 *                       state, country, email, vat_id, payment_gateway, notes, invoice_type
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_update($client_id, $invoice_id, $invoice)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'  => $client_id,
				'invoice_id' => $invoice_id,
				'invoice'    => $invoice,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$client = $this->SoapClient->client_get($this->ID, $client_id);
			// Todo: Need to get it?
			$client_settings = $this->SoapClient->billing_client_settings_get($this->ID, $client_id);
			$params = array(
				'invoice_company_id' => isset($invoice['invoice_company_id']) ? $invoice['invoice_company_id'] : $client_settings['invoice_company_id'],
				'client_id'          => $client_id,
				'company_name'       => isset($invoice['company_name']) ? $invoice['company_name'] : $client['company_name'],
				'contact_name'       => isset($invoice['contact_name']) ? $invoice['contact_name'] : $client['contact_name'],
				'street'             => isset($invoice['street']) ? $invoice['street'] : $client['street'],
				'zip'                => isset($invoice['zip']) ? $invoice['zip'] : $client['zip'],
				'city'               => isset($invoice['city']) ? $invoice['city'] : $client['city'],
				'state'              => isset($invoice['state']) ? $invoice['state'] : $client['state'],
				'country'            => isset($invoice['country']) ? $invoice['country'] : $client['country'],
				'email'              => isset($invoice['email']) ? $invoice['email'] : $client['email'],
				'vat_id'             => isset($invoice['vat_id']) ? $invoice['vat_id'] : $client['vat_id'],
				'payment_gateway'    => isset($invoice['payment_gateway']) ? $invoice['payment_gateway'] : $client_settings['payment_gateway'],
				'notes'              => isset($invoice['notes']) ? $invoice['notes'] : '',
				'invoice_type'       => isset($invoice['invoice_type']) ? $invoice['invoice_type'] : 'invoice',
			);

			return $this->SoapClient->billing_invoice_update($this->ID, $client_id, $invoice_id, $params);
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
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_delete($invoice_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_id' => $invoice_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->billing_invoice_delete($this->ID, $invoice_id);

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
	 *
	 * @return array invoice_item.* or error
	 */
	public function invoice_item_get_by_invoice($invoice_id, $quantity)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'invoice_id' => $invoice_id,
				'quantity'   => $quantity,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_item_get_by_invoice'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->billing_invoice_item_get_by_invoice($this->ID, $invoice_id, $quantity));
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
	 *
	 * @return array invoice_item.* or error
	 */
	public function invoice_item_get($invoice_item_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_item_id' => $invoice_item_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_item_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->billing_invoice_item_get($this->ID, $invoice_item_id));
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
	 *
	 * @return int|array invoice_item.invoice_item_id or error
	 */
	public function invoice_item_add($invoice_id, $invoice_item)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'invoice_id'   => $invoice_id,
				'invoice_item' => $invoice_item,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_item_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'quantity'    => isset($invoice_item['quantity']) ? $invoice_item['quantity'] : 1,
				'price'       => isset($invoice_item['price']) ? $invoice_item['price'] : 0,
				'vat'         => isset($invoice_item['vat']) ? $invoice_item['vat'] : 0,
				'description' => isset($invoice_item['description']) ? $invoice_item['description'] : '',
			);

			return $this->SoapClient->billing_invoice_item_add($this->ID, $invoice_id, $params);
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
	 * @param array $params quantity, price, vat, description
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_item_update($invoice_item_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'invoice_item_id' => $invoice_item_id,
				'params'          => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_item_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->billing_invoice_item_update($this->ID, $invoice_item_id, $params);
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
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_item_delete($invoice_item_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_item_id' => $invoice_item_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_item_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->billing_invoice_item_delete($this->ID, $invoice_item_id);

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
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_finalize($invoice_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_id' => $invoice_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_finalize'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->billing_invoice_finalize($this->ID, $invoice_id);
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
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_send($invoice_id, $email_template_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'invoice_id'        => $invoice_id,
				'email_template_id' => $email_template_id,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_send'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->billing_invoice_send($this->ID, $invoice_id, $email_template_id);
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
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_get_pdf($invoice_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_id' => $invoice_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_get_pdf'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$base64 = $this->SoapClient->billing_invoice_get_pdf($this->ID, $invoice_id);
			$invoices_dir = $this->CI->config->item('ispconfig_invoices_dir');
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
	 *
	 * @return string|array invoice.status_sent or error
	 */
	public function invoice_set_status_sent($invoice_id, $status)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_id' => $invoice_id, 'status' => $status]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_set_status_sent'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->billing_invoice_set_status_sent($this->ID, $invoice_id, $status);
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
	 *
	 * @return string|array invoice.status_paid or error
	 */
	public function invoice_set_status_paid($invoice_id, $status)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_id' => $invoice_id, 'status' => $status]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_set_status_paid'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->billing_invoice_set_status_paid($this->ID, $invoice_id, $status);
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
	 *
	 * @return array invoice_client_settings.* or error
	 */
	public function client_settings_get($client_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['client_id' => $client_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/client_settings_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->billing_client_settings_get($this->ID, $client_id));
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
	 * @param array $invoice_client_settings invoice_company_id, payment_email, payment_terms,
	 *                                       payment_gateway, no_invoice_sending,
	 *                                       last_invoice_number, last_refund_number,
	 *                                       last_proforma_number, invoice_sepa_mandate_id
	 *
	 * @return int|array invoice_client_settings.client_id or error
	 */
	public function client_settings_add($client_id, $invoice_client_settings)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'               => $client_id,
				'invoice_client_settings' => $invoice_client_settings,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/client_settings_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'invoice_company_id'      => isset($invoice_client_settings['invoice_company_id']) ? $invoice_client_settings['invoice_company_id'] : 0,
				'payment_email'           => isset($invoice_client_settings['payment_email']) ? $invoice_client_settings['payment_email'] : '',
				'payment_terms'           => isset($invoice_client_settings['payment_terms']) ? $invoice_client_settings['payment_terms'] : 1,
				'payment_gateway'         => isset($invoice_client_settings['payment_gateway']) ? $invoice_client_settings['payment_gateway'] : 'auto',
				'no_invoice_sending'      => isset($invoice_client_settings['no_invoice_sending']) ? $invoice_client_settings['no_invoice_sending'] : 'n',
				'last_invoice_number'     => isset($invoice_client_settings['last_invoice_number']) ? $invoice_client_settings['last_invoice_number'] : 0,
				'last_refund_number'      => isset($invoice_client_settings['last_refund_number']) ? $invoice_client_settings['last_refund_number'] : 0,
				'last_proforma_number'    => isset($invoice_client_settings['last_proforma_number']) ? $invoice_client_settings['last_proforma_number'] : 0,
				'invoice_sepa_mandate_id' => isset($invoice_client_settings['invoice_sepa_mandate_id']) ? $invoice_client_settings['invoice_sepa_mandate_id'] : 0,
			);

			return $this->SoapClient->billing_client_settings_add($this->ID, $client_id, $params);
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
	 * @param array $params                  invoice_company_id, payment_email, payment_terms,
	 *                                       payment_gateway, no_invoice_sending,
	 *                                       last_invoice_number, last_refund_number,
	 *                                       last_proforma_number, invoice_sepa_mandate_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function client_settings_update($client_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id' => $client_id,
				'params'    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/client_settings_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->billing_client_settings_update($this->ID, $client_id, $params);
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
	 *
	 * @return bool|array TRUE or error
	 */
	public function client_settings_delete($client_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['client_id' => $client_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/client_settings_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->billing_client_settings_delete($this->ID, $client_id);
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
	 *
	 * @return array invoice_company.* or error
	 */
	public function invoice_company_get($invoice_company_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_company_id' => $invoice_company_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_company_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->billing_invoice_company_get($this->ID, $invoice_company_id));
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
	 * @param array $invoice_company company_name, company_name_short, contact_name, street, zip,
	 *                               city, state, country, email, internet, telephone, fax,
	 *                               company_logo, ceo_name, vat_id, tax_id, company_register,
	 *                               bank_account_owner, bank_account_number, bank_code, bank_name,
	 *                               bank_account_iban, bank_account_swift, creditor_id,
	 *                               last_invoice_number, invoice_number_prefix, last_refund_number,
	 *                               refund_number_prefix last_proforma_number,
	 *                               proforma_number_prefix, invoice_pdf_template, reminder_pdf,
	 *                               reminder_fee, reminder_fee_step, reminder_steps,
	 *                               chargeback_fee, reminder_payment_terms,
	 *                               reminder_last_payment_terms, chargeback_payment_terms,
	 *                               sender_name, sender_email, bcc_email,
	 *
	 * @return int|array invoice_company.invoice_company_id or error
	 */
	public function invoice_company_add($client_id, $invoice_company)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'       => $client_id,
				'invoice_company' => $invoice_company,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_company_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'company_name'                => isset($invoice_company['company_name']) ? $invoice_company['company_name'] : '',
				'company_name_short'          => isset($invoice_company['company_name_short']) ? $invoice_company['company_name_short'] : '',
				'contact_name'                => isset($invoice_company['contact_name']) ? $invoice_company['contact_name'] : '',
				'street'                      => isset($invoice_company['street']) ? $invoice_company['street'] : '',
				'zip'                         => isset($invoice_company['zip']) ? $invoice_company['zip'] : '',
				'city'                        => isset($invoice_company['city']) ? $invoice_company['city'] : '',
				'state'                       => isset($invoice_company['state']) ? $invoice_company['state'] : '',
				'country'                     => isset($invoice_company['country']) ? $invoice_company['country'] : '',
				'email'                       => isset($invoice_company['email']) ? $invoice_company['email'] : '',
				'internet'                    => isset($invoice_company['internet']) ? $invoice_company['internet'] : '',
				'telephone'                   => isset($invoice_company['telephone']) ? $invoice_company['telephone'] : '',
				'fax'                         => isset($invoice_company['fax']) ? $invoice_company['fax'] : '',
				'company_logo'                => isset($invoice_company['company_logo']) ? $invoice_company['company_logo'] : '',
				'ceo_name'                    => isset($invoice_company['ceo_name']) ? $invoice_company['ceo_name'] : '',
				'vat_id'                      => isset($invoice_company['vat_id']) ? $invoice_company['vat_id'] : '',
				'tax_id'                      => isset($invoice_company['tax_id']) ? $invoice_company['tax_id'] : '',
				'company_register'            => isset($invoice_company['company_register']) ? $invoice_company['company_register'] : '',
				'bank_account_owner'          => isset($invoice_company['bank_account_owner']) ? $invoice_company['bank_account_owner'] : '',
				'bank_account_number'         => isset($invoice_company['bank_account_number']) ? $invoice_company['bank_account_number'] : '',
				'bank_code'                   => isset($invoice_company['bank_code']) ? $invoice_company['bank_code'] : '',
				'bank_name'                   => isset($invoice_company['bank_name']) ? $invoice_company['bank_name'] : '',
				'bank_account_iban'           => isset($invoice_company['bank_account_iban']) ? $invoice_company['bank_account_iban'] : '',
				'bank_account_swift'          => isset($invoice_company['bank_account_swift']) ? $invoice_company['bank_account_swift'] : '',
				'creditor_id'                 => isset($invoice_company['creditor_id']) ? $invoice_company['creditor_id'] : '',
				'last_invoice_number'         => isset($invoice_company['last_invoice_number']) ? $invoice_company['last_invoice_number'] : 0,
				'invoice_number_prefix'       => isset($invoice_company['invoice_number_prefix']) ? $invoice_company['invoice_number_prefix'] : '',
				'last_refund_number'          => isset($invoice_company['last_refund_number']) ? $invoice_company['last_refund_number'] : 0,
				'refund_number_prefix'        => isset($invoice_company['refund_number_prefix']) ? $invoice_company['refund_number_prefix'] : '',
				'last_proforma_number'        => isset($invoice_company['last_proforma_number']) ? $invoice_company['last_proforma_number'] : 0,
				'proforma_number_prefix'      => isset($invoice_company['proforma_number_prefix']) ? $invoice_company['proforma_number_prefix'] : '',
				'invoice_pdf_template'        => isset($invoice_company['invoice_pdf_template']) ? $invoice_company['invoice_pdf_template'] : 'default',
				'reminder_pdf'                => isset($invoice_company['reminder_pdf']) ? $invoice_company['reminder_pdf'] : 'n',
				'reminder_fee'                => isset($invoice_company['reminder_fee']) ? $invoice_company['reminder_fee'] : 0,
				'reminder_fee_step'           => isset($invoice_company['reminder_fee_step']) ? $invoice_company['reminder_fee_step'] : 1,
				'reminder_steps'              => isset($invoice_company['reminder_steps']) ? $invoice_company['reminder_steps'] : 3,
				'chargeback_fee'              => isset($invoice_company['chargeback_fee']) ? $invoice_company['chargeback_fee'] : 0,
				'reminder_payment_terms'      => isset($invoice_company['reminder_payment_terms']) ? $invoice_company['reminder_payment_terms'] : 0,
				'reminder_last_payment_terms' => isset($invoice_company['reminder_last_payment_terms']) ? $invoice_company['reminder_last_payment_terms'] : 0,
				'chargeback_payment_terms'    => isset($invoice_company['chargeback_payment_terms']) ? $invoice_company['chargeback_payment_terms'] : 0,
				'sender_name'                 => isset($invoice_company['sender_name']) ? $invoice_company['sender_name'] : '',
				'sender_email'                => isset($invoice_company['sender_email']) ? $invoice_company['sender_email'] : '',
				'bcc_email'                   => isset($invoice_company['bcc_email']) ? $invoice_company['bcc_email'] : '',
			);

			return $this->SoapClient->billing_invoice_company_add($this->ID, $client_id, $params);
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
	 * @param array $params          company_name, company_name_short, contact_name, street, zip,
	 *                               city, state, country, email, internet, telephone, fax,
	 *                               company_logo, ceo_name, vat_id, tax_id, company_register,
	 *                               bank_account_owner, bank_account_number, bank_code, bank_name,
	 *                               bank_account_iban, bank_account_swift, creditor_id,
	 *                               last_invoice_number, invoice_number_prefix, last_refund_number,
	 *                               refund_number_prefix, last_proforma_number,
	 *                               proforma_number_prefix, invoice_pdf_template, reminder_pdf,
	 *                               reminder_fee, reminder_fee_step, reminder_steps,
	 *                               chargeback_fee, reminder_payment_terms,
	 *                               reminder_last_payment_terms, chargeback_payment_terms,
	 *                               sender_name, sender_email, bcc_email
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_company_update($client_id, $invoice_company_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'          => $client_id,
				'invoice_company_id' => $invoice_company_id,
				'params'             => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_company_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->billing_invoice_company_update($this->ID, $client_id, $invoice_company_id, $params);
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
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_company_delete($invoice_company_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_company_id' => $invoice_company_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_company_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->billing_invoice_company_delete($this->ID, $invoice_company_id);

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
	 *
	 * @return array invoice_item_template.* or error
	 */
	public function invoice_item_template_get($invoice_item_template_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_item_template_id' => $invoice_item_template_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_item_template_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->billing_invoice_item_template_get($this->ID, $invoice_item_template_id));
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
	 * @param array $invoice_item_template type, name, description, price, setup_fee, vat, unit,
	 *                                     recur_months, cancellation_period, client_template_id,
	 *                                     offer_in_shop is_standalone, is_addon, is_updowngradable,
	 *                                     addon_of, updowngradable_to
	 *
	 * @return int|array invoice_item_template.invoice_item_template_id or error
	 */
	public function invoice_item_template_add($client_id, $invoice_item_template)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'             => $client_id,
				'invoice_item_template' => $invoice_item_template,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_item_template_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'type'                => isset($invoice_item_template['type']) ? $invoice_item_template['type'] : 'Item',
				'name'                => isset($invoice_item_template['name']) ? $invoice_item_template['name'] : '',
				'description'         => isset($invoice_item_template['description']) ? $invoice_item_template['description'] : '',
				'price'               => isset($invoice_item_template['price']) ? $invoice_item_template['price'] : 0,
				'setup_fee'           => isset($invoice_item_template['setup_fee']) ? $invoice_item_template['setup_fee'] : 0,
				'vat'                 => isset($invoice_item_template['vat']) ? $invoice_item_template['vat'] : 0,
				'unit'                => isset($invoice_item_template['unit']) ? $invoice_item_template['unit'] : 'default',
				'recur_months'        => isset($invoice_item_template['recur_months']) ? $invoice_item_template['recur_months'] : 0,
				'cancellation_period' => isset($invoice_item_template['cancellation_period']) ? $invoice_item_template['cancellation_period'] : 30,
				'client_template_id'  => isset($invoice_item_template['client_template_id']) ? $invoice_item_template['client_template_id'] : 0,
				'offer_in_shop'       => isset($invoice_item_template['offer_in_shop']) ? $invoice_item_template['offer_in_shop'] : 'n',
				'is_standalone'       => isset($invoice_item_template['is_standalone']) ? $invoice_item_template['is_standalone'] : 'y',
				'is_addon'            => isset($invoice_item_template['is_addon']) ? $invoice_item_template['is_addon'] : 'n',
				'is_updowngradable'   => isset($invoice_item_template['is_updowngradable']) ? $invoice_item_template['is_updowngradable'] : 'n',
				'addon_of'            => isset($invoice_item_template['addon_of']) ? $invoice_item_template['addon_of'] : '',
				'updowngradable_to'   => isset($invoice_item_template['updowngradable_to']) ? $invoice_item_template['updowngradable_to'] : '',
			);

			return $this->SoapClient->billing_invoice_item_template_add($this->ID, $client_id, $params);
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
	 * @param array $params                type, name, description, price, setup_fee, vat, unit,
	 *                                     recur_months, cancellation_period, client_template_id,
	 *                                     offer_in_shop is_standalone, is_addon, is_updowngradable,
	 *                                     addon_of, updowngradable_to
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_item_template_update($client_id, $invoice_item_template_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'                => $client_id,
				'invoice_item_template_id' => $invoice_item_template_id,
				'params'                   => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_item_template_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->billing_invoice_item_template_update($this->ID, $client_id, $invoice_item_template_id, $params);
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
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_item_template_delete($invoice_item_template_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_item_template_id' => $invoice_item_template_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_item_template_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->billing_invoice_item_template_delete($this->ID, $invoice_item_template_id);

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
	 *
	 * @return array invoice_message_template.* or error
	 */
	public function invoice_message_template_get($invoice_message_template_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_message_template_id' => $invoice_message_template_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_message_template_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->billing_invoice_message_template_get($this->ID, $invoice_message_template_id));
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
	 *
	 * @return int|array invoice_message_template.invoice_message_template_id or error
	 */
	public function invoice_message_template_add($client_id, $invoice_message_template)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'                => $client_id,
				'invoice_message_template' => $invoice_message_template,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_message_template_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'template_type' => isset($invoice_message_template['template_type']) ? $invoice_message_template['template_type'] : 'other',
				'template_name' => isset($invoice_message_template['template_name']) ? $invoice_message_template['template_name'] : '',
				'subject'       => isset($invoice_message_template['subject']) ? $invoice_message_template['subject'] : '',
				'message'       => isset($invoice_message_template['message']) ? $invoice_message_template['message'] : '',
			);

			return $this->SoapClient->billing_invoice_message_template_add($this->ID, $client_id, $params);
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
	 * @param array $params template_type, template_name, subject, message
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_message_template_update($client_id, $invoice_message_template_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'                   => $client_id,
				'invoice_message_template_id' => $invoice_message_template_id,
				'params'                      => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_message_template_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->billing_invoice_message_template_update($this->ID, $client_id, $invoice_message_template_id, $params);
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
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_message_template_delete($invoice_message_template_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_message_template_id' => $invoice_message_template_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_message_template_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->billing_invoice_message_template_delete($this->ID, $invoice_message_template_id);

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
	 *
	 * @return array invoice_payment_term.* or error
	 */
	public function invoice_payment_term_get($invoice_payment_term_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_payment_term_id' => $invoice_payment_term_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_payment_term_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->billing_invoice_payment_term_get($this->ID, $invoice_payment_term_id));
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
	 *
	 * @return int|array invoice_payment_term.invoice_payment_term_id or error
	 */
	public function invoice_payment_term_add($client_id, $invoice_payment_term)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'            => $client_id,
				'invoice_payment_term' => $invoice_payment_term,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_payment_term_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'name'                        => isset($invoice_payment_term['name']) ? $invoice_payment_term['name'] : '',
				'description'                 => isset($invoice_payment_term['description']) ? $invoice_payment_term['description'] : '',
				'due_days'                    => isset($invoice_payment_term['due_days']) ? $invoice_payment_term['due_days'] : 0,
				'invoice_explanation'         => isset($invoice_payment_term['invoice_explanation']) ? $invoice_payment_term['invoice_explanation'] : '',
				'invoice_message_template_id' => isset($invoice_payment_term['invoice_message_template_id']) ? $invoice_payment_term['invoice_message_template_id'] : 0,
			);

			return $this->SoapClient->billing_invoice_payment_term_add($this->ID, $client_id, $params);
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
	 * @param array $params               name, description, due_days, invoice_explanation,
	 *                                    invoice_message_template_id
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_payment_term_update($client_id, $invoice_payment_term_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'               => $client_id,
				'invoice_payment_term_id' => $invoice_payment_term_id,
				'params'                  => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_payment_term_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->billing_invoice_payment_term_update($this->ID, $client_id, $invoice_payment_term_id, $params);
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
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_payment_term_delete($invoice_payment_term_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_payment_term_id' => $invoice_payment_term_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_payment_term_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->billing_invoice_payment_term_delete($this->ID, $invoice_payment_term_id);

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
	 *
	 * @return array invoice_recurring_item.* or error
	 */
	public function invoice_recurring_item_get($invoice_recurring_item_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_recurring_item_id' => $invoice_recurring_item_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_recurring_item_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->billing_invoice_recurring_item_get($this->ID, $invoice_recurring_item_id));
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
	 *                                      parent_recurring_item_id, assigned_template_id, name,
	 *                                      description, quantity, price, setup_fee, vat,
	 *                                      recur_months, next_payment_date, start_date, end_date,
	 *                                      type, advance_payment, cancellation_period,
	 *                                      send_reminder, active
	 *
	 * @return int|array invoice_recurring_item.invoice_recurring_item_id or error
	 */
	public function invoice_recurring_item_add($client_id, $invoice_recurring_item)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'              => $client_id,
				'invoice_recurring_item' => $invoice_recurring_item,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_recurring_item_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'invoice_item_template_id' => isset($invoice_recurring_item['invoice_item_template_id']) ? $invoice_recurring_item['invoice_item_template_id'] : 0,
				'invoice_company_id'       => isset($invoice_recurring_item['invoice_company_id']) ? $invoice_recurring_item['invoice_company_id'] : 0,
				'parent_recurring_item_id' => isset($invoice_recurring_item['parent_recurring_item_id']) ? $invoice_recurring_item['parent_recurring_item_id'] : 0,
				'assigned_template_id'     => isset($invoice_recurring_item['assigned_template_id']) ? $invoice_recurring_item['assigned_template_id'] : 0,
				'name'                     => isset($invoice_recurring_item['name']) ? $invoice_recurring_item['name'] : '',
				'description'              => isset($invoice_recurring_item['description']) ? $invoice_recurring_item['description'] : '',
				'quantity'                 => isset($invoice_recurring_item['quantity']) ? $invoice_recurring_item['quantity'] : 1,
				'price'                    => isset($invoice_recurring_item['price']) ? $invoice_recurring_item['price'] : 0,
				'setup_fee'                => isset($invoice_recurring_item['setup_fee']) ? $invoice_recurring_item['setup_fee'] : 0,
				'vat'                      => isset($invoice_recurring_item['vat']) ? $invoice_recurring_item['vat'] : 0,
				'recur_months'             => isset($invoice_recurring_item['recur_months']) ? $invoice_recurring_item['recur_months'] : 0,
				'next_payment_date'        => isset($invoice_recurring_item['next_payment_date']) ? $invoice_recurring_item['next_payment_date'] : date('Y-m-d'),
				'start_date'               => isset($invoice_recurring_item['start_date']) ? $invoice_recurring_item['start_date'] : '',
				'end_date'                 => isset($invoice_recurring_item['end_date']) ? $invoice_recurring_item['end_date'] : '',
				'type'                     => isset($invoice_recurring_item['type']) ? $invoice_recurring_item['type'] : 'other',
				'advance_payment'          => isset($invoice_recurring_item['advance_payment']) ? $invoice_recurring_item['advance_payment'] : 'y',
				'cancellation_period'      => isset($invoice_recurring_item['cancellation_period']) ? $invoice_recurring_item['cancellation_period'] : 30,
				'send_reminder'            => isset($invoice_recurring_item['send_reminder']) ? $invoice_recurring_item['send_reminder'] : '',
				'active'                   => isset($invoice_recurring_item['active']) ? $invoice_recurring_item['active'] : 'y',
			);

			return $this->SoapClient->billing_invoice_recurring_item_add($this->ID, $client_id, $params);
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
	 * @param array $params                 invoice_item_template_id, invoice_company_id,
	 *                                      parent_recurring_item_id, assigned_template_id, name,
	 *                                      description, quantity, price, setup_fee, vat,
	 *                                      recur_months, next_payment_date, start_date, end_date,
	 *                                      type, advance_payment, cancellation_period,
	 *                                      send_reminder, active
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_recurring_item_update($client_id, $invoice_recurring_item_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'                 => $client_id,
				'invoice_recurring_item_id' => $invoice_recurring_item_id,
				'params'                    => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_recurring_item_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->billing_invoice_recurring_item_update($this->ID, $client_id, $invoice_recurring_item_id, $params);
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
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_recurring_item_delete($invoice_recurring_item_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_recurring_item_id' => $invoice_recurring_item_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_recurring_item_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->billing_invoice_recurring_item_delete($this->ID, $invoice_recurring_item_id);

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
	 *
	 * @return array invoice_settings.* or error
	 */
	public function invoice_settings_get($invoice_settings_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_settings_id' => $invoice_settings_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_settings_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->billing_invoice_settings_get($this->ID, $invoice_settings_id));
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
	 * @param array $invoice_settings date_format, invoice_dir, invoice_pay_link, currency,
	 *                                paypal_url, paypal_ipn_url, paypal_active,
	 *                                invoice_item_list_with_vat, revnum,
	 *                                recurring_invoices_cron_active, recurring_invoices_cron_test,
	 *                                recurring_invoices_cron_finalize_invoices,
	 *                                recurring_invoices_cron_send_invoices,
	 *                                recurring_invoices_cron_email_template_id,
	 *                                recurring_invoices_cron_proforma_invoice,
	 *                                sepa_core_collectiondate_ooff, sepa_core_collectiondate_frst,
	 *                                sepa_core_collectiondate_rcur, sepa_cor1_collectiondate_ooff,
	 *                                sepa_cor1_collectiondate_frst, sepa_cor1_collectiondate_rcur,
	 *                                sepa_b2b_collectiondate_ooff, sepa_b2b_collectiondate_frst,
	 *                                sepa_b2b_collectiondate_rcur, sepa_mandate_reminders,
	 *                                sepa_mandate_reminders_frequency,
	 *                                sepa_mandate_reminders_subject, sepa_mandate_reminders_msg
	 *
	 * @return int|array invoice_settings.invoice_settings_id or error
	 */
	public function invoice_settings_add($client_id = 0, $invoice_settings = array())
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'        => $client_id,
				'invoice_settings' => $invoice_settings,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_settings_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'date_format'                               => isset($invoice_settings['date_format']) ? $invoice_settings['date_format'] : 'd.m.Y',
				'invoice_dir'                               => isset($invoice_settings['invoice_dir']) ? $invoice_settings['invoice_dir'] : '',
				'invoice_pay_link'                          => isset($invoice_settings['invoice_pay_link']) ? $invoice_settings['invoice_pay_link'] : '',
				'currency'                                  => isset($invoice_settings['currency']) ? $invoice_settings['currency'] : 'USD',
				'paypal_url'                                => isset($invoice_settings['paypal_url']) ? $invoice_settings['paypal_url'] : 'https://www.paypal.com/cgi-bin/webscr',
				'paypal_ipn_url'                            => isset($invoice_settings['paypal_ipn_url']) ? $invoice_settings['paypal_ipn_url'] : '',
				'paypal_active'                             => isset($invoice_settings['paypal_active']) ? $invoice_settings['paypal_active'] : 'y',
				'invoice_item_list_with_vat'                => isset($invoice_settings['invoice_item_list_with_vat']) ? $invoice_settings['invoice_item_list_with_vat'] : 'n',
				'revnum'                                    => isset($invoice_settings['revnum']) ? $invoice_settings['revnum'] : 16,
				'recurring_invoices_cron_active'            => isset($invoice_settings['recurring_invoices_cron_active']) ? $invoice_settings['recurring_invoices_cron_active'] : 'y',
				'recurring_invoices_cron_test'              => isset($invoice_settings['recurring_invoices_cron_test']) ? $invoice_settings['recurring_invoices_cron_test'] : 'n',
				'recurring_invoices_cron_finalize_invoices' => isset($invoice_settings['recurring_invoices_cron_finalize_invoices']) ? $invoice_settings['recurring_invoices_cron_finalize_invoices'] : 'y',
				'recurring_invoices_cron_send_invoices'     => isset($invoice_settings['recurring_invoices_cron_send_invoices']) ? $invoice_settings['recurring_invoices_cron_send_invoices'] : 'y',
				'recurring_invoices_cron_email_template_id' => isset($invoice_settings['recurring_invoices_cron_email_template_id']) ? $invoice_settings['recurring_invoices_cron_email_template_id'] : 1,
				'recurring_invoices_cron_proforma_invoice'  => isset($invoice_settings['recurring_invoices_cron_proforma_invoice']) ? $invoice_settings['recurring_invoices_cron_proforma_invoice'] : 'y',
				'sepa_core_collectiondate_ooff'             => isset($invoice_settings['sepa_core_collectiondate_ooff']) ? $invoice_settings['sepa_core_collectiondate_ooff'] : 6,
				'sepa_core_collectiondate_frst'             => isset($invoice_settings['sepa_core_collectiondate_frst']) ? $invoice_settings['sepa_core_collectiondate_frst'] : 6,
				'sepa_core_collectiondate_rcur'             => isset($invoice_settings['sepa_core_collectiondate_rcur']) ? $invoice_settings['sepa_core_collectiondate_rcur'] : 3,
				'sepa_cor1_collectiondate_ooff'             => isset($invoice_settings['sepa_cor1_collectiondate_ooff']) ? $invoice_settings['sepa_cor1_collectiondate_ooff'] : 2,
				'sepa_cor1_collectiondate_frst'             => isset($invoice_settings['sepa_cor1_collectiondate_frst']) ? $invoice_settings['sepa_cor1_collectiondate_frst'] : 2,
				'sepa_cor1_collectiondate_rcur'             => isset($invoice_settings['sepa_cor1_collectiondate_rcur']) ? $invoice_settings['sepa_cor1_collectiondate_rcur'] : 2,
				'sepa_b2b_collectiondate_ooff'              => isset($invoice_settings['sepa_b2b_collectiondate_ooff']) ? $invoice_settings['sepa_b2b_collectiondate_ooff'] : 6,
				'sepa_b2b_collectiondate_frst'              => isset($invoice_settings['sepa_b2b_collectiondate_frst']) ? $invoice_settings['sepa_b2b_collectiondate_frst'] : 6,
				'sepa_b2b_collectiondate_rcur'              => isset($invoice_settings['sepa_b2b_collectiondate_rcur']) ? $invoice_settings['sepa_b2b_collectiondate_rcur'] : 3,
				'sepa_mandate_reminders'                    => isset($invoice_settings['sepa_mandate_reminders']) ? $invoice_settings['sepa_mandate_reminders'] : 'n',
				'sepa_mandate_reminders_frequency'          => isset($invoice_settings['sepa_mandate_reminders_frequency']) ? $invoice_settings['sepa_mandate_reminders_frequency'] : 7,
				'sepa_mandate_reminders_subject'            => isset($invoice_settings['sepa_mandate_reminders_subject']) ? $invoice_settings['sepa_mandate_reminders_subject'] : '',
				'sepa_mandate_reminders_msg'                => isset($invoice_settings['sepa_mandate_reminders_msg']) ? $invoice_settings['sepa_mandate_reminders_msg'] : '',
			);

			return $this->SoapClient->billing_invoice_settings_add($this->ID, $client_id, $params);
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
	 * @param array $params           date_format, invoice_dir, invoice_pay_link, currency,
	 *                                paypal_url, paypal_ipn_url, paypal_active,
	 *                                invoice_item_list_with_vat, revnum,
	 *                                recurring_invoices_cron_active, recurring_invoices_cron_test,
	 *                                recurring_invoices_cron_finalize_invoices,
	 *                                recurring_invoices_cron_send_invoices,
	 *                                recurring_invoices_cron_email_template_id,
	 *                                recurring_invoices_cron_proforma_invoice,
	 *                                sepa_core_collectiondate_ooff, sepa_core_collectiondate_frst,
	 *                                sepa_core_collectiondate_rcur, sepa_cor1_collectiondate_ooff,
	 *                                sepa_cor1_collectiondate_frst, sepa_cor1_collectiondate_rcur,
	 *                                sepa_b2b_collectiondate_ooff, sepa_b2b_collectiondate_frst,
	 *                                sepa_b2b_collectiondate_rcur, sepa_mandate_reminders,
	 *                                sepa_mandate_reminders_frequency,
	 *                                sepa_mandate_reminders_subject, sepa_mandate_reminders_msg
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_settings_update($client_id, $invoice_settings_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'           => $client_id,
				'invoice_settings_id' => $invoice_settings_id,
				'params'              => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_settings_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->billing_invoice_settings_update($this->ID, $client_id, $invoice_settings_id, $params);
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
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_settings_delete($invoice_settings_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_settings_id' => $invoice_settings_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_settings_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->billing_invoice_settings_delete($this->ID, $invoice_settings_id);

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
	 *
	 * @return array invoice_sepa_mandate.* or error
	 */
	public function invoice_sepa_mandate_get($invoice_sepa_mandate_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_sepa_mandate_id' => $invoice_sepa_mandate_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_sepa_mandate_get'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->get_empty($this->SoapClient->billing_invoice_sepa_mandate_get($this->ID, $invoice_sepa_mandate_id));
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
	 * @param array $invoice_sepa_mandate invoice_company_id, mandate_reference, signature_date,
	 *                                    type, signed, active, sequence_type
	 *
	 * @return int|array invoice_settings.invoice_settings_id or error
	 */
	public function invoice_sepa_mandate_add($client_id, $invoice_sepa_mandate)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'            => $client_id,
				'invoice_sepa_mandate' => $invoice_sepa_mandate,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_sepa_mandate_add'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$params = array(
				'invoice_company_id' => isset($invoice_sepa_mandate['invoice_company_id']) ? $invoice_sepa_mandate['invoice_company_id'] : 0,
				'mandate_reference'  => isset($invoice_sepa_mandate['mandate_reference']) ? $invoice_sepa_mandate['mandate_reference'] : '',
				'signature_date'     => isset($invoice_sepa_mandate['signature_date']) ? $invoice_sepa_mandate['signature_date'] : date('Y-m-d'),
				'type'               => isset($invoice_sepa_mandate['type']) ? $invoice_sepa_mandate['type'] : 'sepa_core',
				'signed'             => isset($invoice_sepa_mandate['signed']) ? $invoice_sepa_mandate['signed'] : 'n',
				'active'             => isset($invoice_sepa_mandate['active']) ? $invoice_sepa_mandate['active'] : 'y',
				'sequence_type'      => isset($invoice_sepa_mandate['sequence_type']) ? $invoice_sepa_mandate['sequence_type'] : 'RCUR',
			);

			return $this->SoapClient->billing_invoice_sepa_mandate_add($this->ID, $client_id, $params);
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
	 * @param array $params               invoice_company_id, mandate_reference, signature_date,
	 *                                    type, signed, active, sequence_type
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_sepa_mandate_update($client_id, $invoice_sepa_mandate_id, $params)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data([
				'client_id'               => $client_id,
				'invoice_sepa_mandate_id' => $invoice_sepa_mandate_id,
				'params'                  => $params,
			]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_sepa_mandate_update'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();

			return $this->SoapClient->billing_invoice_sepa_mandate_update($this->ID, $client_id, $invoice_sepa_mandate_id, $params);
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
	 *
	 * @return bool|array TRUE or error
	 */
	public function invoice_sepa_mandate_delete($invoice_sepa_mandate_id)
	{
		if ($this->use_form_validation == TRUE)
		{
			$this->CI->form_validation->set_data(['invoice_sepa_mandate_id' => $invoice_sepa_mandate_id]);
			if ( ! $this->CI->form_validation->run('ispconfig/billing/invoice_sepa_mandate_delete'))
			{
				return ['error' => $this->CI->form_validation->error_array()];
			}
		}
		try
		{
			$this->login();
			$this->SoapClient->billing_invoice_sepa_mandate_delete($this->ID, $invoice_sepa_mandate_id);

			return TRUE;
		}
		catch (SoapFault $e)
		{
			return $this->get_error($e->getMessage());
		}
	}

}
