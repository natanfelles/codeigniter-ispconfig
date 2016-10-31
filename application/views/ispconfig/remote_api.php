<?php
/**
 * Remote API View
 *
 * This view is an example using ISPConfig 3 Remote API
 *
 * @package       CodeIgniter
 * @subpackage    ISPConfig
 * @category      Remote API
 * @version       2.0.0
 * @author        Natan Felles
 * @link          http://github.com/natanfelles/codeigniter-ispconfig
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @see Remote_api
 * @var mixed $response
 */
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>ISPConfig 3 Remote API</title>
	<link rel="stylesheet" href="<?= base_url('assets/ispconfig.css'); ?>">
	<link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/ispconfig.ico'); ?>"/>
</head>
<body>
<div class="container">

	<div class="logo"></div>

	<div class="pages">
		<ul>
			<li<?php
			if (uri_string() == 'remote_api')
			{
				echo ' class="active"';
			}
			?>>
				<a href="<?= site_url('remote_api'); ?>">Test</a>
			</li>
			<li<?php
			if (uri_string() == 'remote_api/config')
			{
				echo ' class="active"';
			}
			?>>
				<a href="<?= site_url('remote_api/config'); ?>">Config</a>
			</li>
			<li<?php
			if (uri_string() == 'remote_api/function_list')
			{
				echo ' class="active"';
			}
			?>>
				<a href="<?= site_url('remote_api/function_list'); ?>">Function List</a>
			</li>
		</ul>
	</div>

	<div class="response">
		<h2>Response:</h2>
		<pre><?php print_r($response); ?></pre>
		<strong>Elapsed Time:</strong> {elapsed_time} - <strong>Memory Usage:</strong> {memory_usage}
	</div>

	<div class="bottom">
		<a href="http://ispconfig.org" target="_blank">ISPConfig</a>
		Remote API to
		<a href="http://codeigniter.com" target="_blank">CodeIgniter</a>
		by
		<a href="http://github.com/natanfelles" target="_blank">Natan Felles</a>
	</div>

</div>
</body>
</html>
