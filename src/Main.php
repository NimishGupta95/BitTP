<?php
/*
	This file is part of BitTP.

	BitTP is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	BitTP is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with BitTP.  If not, see <http://www.gnu.org/licenses/>.
*/

echo "Initializing...\n";
//`killall php-cgi`;
//`php-cgi -b localhost:8888 &`;
require('FCGIClient.php');

set_time_limit(0);

$socketPort = 8080;
$socketHost = "0.0.0.0";

$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("ERROR: Could not create socket\n");

if(isset($argv[1])) {
	echo 'Using port '.$argv[1]."\n";
	$socketPort = intval($argv[1]);
}

else
	echo "No port specified... using 8080\n";

$result = socket_bind($socket, $socketHost, $socketPort) or die("ERROR: Could not bind to port\n");

for(;;) {
	
	
	
	$result = socket_listen($socket, 3) or die("ERROR: Could not set up socket listener\n");
	$spawn = socket_accept($socket) or die("ERROR: Could not accept incoming connection\n");
	$input = socket_read($spawn, 1024) or die("ERROR: Could not read input\n");
	$input = trim($input);

	/*

	//#### THIS CODE WILL BE USED ONLY FOR NON-HTML FILES. PHP-CGI CAN HANDLE NORMAL HTML FILES ALSO ####//
	$filename = "/www/index.html";
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);

	$output = $contents;
	
	*/
	
	$client = new \framework\FCGIClient('localhost', '8888');

	$client->request(
		array(
			'GATEWAY_INTERFACE'	=> 'FastCGI/1.0',
			'REQUEST_METHOD'	=> 'GET',
			'SCRIPT_FILENAME'	=> '/www/index.php?=PHPE9568F34-D428-11d2-A769-00AA001ACF42',
			'SERVER_SOFTWARE'	=> 'php/fcgiclient',
			'REMOTE_ADDR'		=> '127.0.0.1',
			'REMOTE_PORT'		=> '8888',
			'SERVER_ADDR'		=> '127.0.0.1',
			'SERVER_PORT'		=> '8888',
			'SERVER_NAME'		=> php_uname('n'),
			'SERVER_PROTOCOL'	=> 'HTTP/1.1'
		),
		''
	);
	usleep(200000);
	
	$response = $client->response();
	$output = print_r($response["body"],true);
	
	// GZIP Compression TODO
	//ob_start("ob_gzhandler");
	//echo $output;
	//$output = ob_get_flush();
	
	socket_write($spawn, $output, strlen ($output)) or die("Could not write output\n");

	socket_close($spawn);
}

socket_close($socket);
?>
