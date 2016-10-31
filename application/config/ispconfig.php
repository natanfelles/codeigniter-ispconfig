<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| ISPCONFIG REMOTE API
| -------------------------------------------------------------------------
|
| This is the ISPConfig Remote API Library settings file
|
| The following parameters and description of each:
|
|
| $config['ispconfig_username'] = 'admin'
|
| Defines the remote username configured in ISPConfig.
|
|
| $config['ispconfig_password'] = 'admin'
|
| Defines the password of the remote user configured in ISPConfig.
|
|
| $config['ispconfig_soap_uri'] = 'https://server.domain.tld:8080/remote/'
|
| Defines the URI for SOAP connection with the ISPConfig.
|
|
| $config['ispconfig_soap_location'] = 'https://server.domain.tld:8080/remote/index.php'
|
| Defines the full URL for SOAP connection with the ISPConfig.
|
|
| $config['ispconfig_invoices_dir'] = 'invoices'
|
| Defines the local directory where the PDF invoices will be saved.
|
|
| $config['ispconfig_verify_ssl'] = TRUE
|
| Defines if the SOAP must check the authenticity of SSL certificate.
| Set FALSE if you use a self-signed certificate.
|
|
| $config['ispconfig_development_mode'] = FALSE
|
| Defines if the system is under development. If set to TRUE you can not
| use the get_config() method to display the settings in frontend.
|
 */
$config['ispconfig_username'] = 'admin';
$config['ispconfig_password'] = 'admin';
$config['ispconfig_soap_uri'] = 'https://server.domain.tld:8080/remote/';
$config['ispconfig_soap_location'] = 'https://server.domain.tld:8080/remote/index.php';
$config['ispconfig_invoices_dir'] = 'invoices';
$config['ispconfig_verify_ssl'] = FALSE;
$config['ispconfig_development_mode'] = TRUE;
$config['ispconfig_use_form_validation'] = TRUE;
