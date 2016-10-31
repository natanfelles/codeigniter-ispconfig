<?php

/**
 * @param $client_id
 * @param $params
 *
 * @return mixed
 */
function function_name($client_id, $params)
{
	try
	{
		$this->login();

		return $this->get_empty($this->SoapClient->function_name($this->ID, $client_id, $params));
	}
	catch (SoapFault $e)
	{
		return $this->get_error($e->getMessage());
	}
}