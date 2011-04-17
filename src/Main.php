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

	$filename = "/www/index.html";
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);

	$output = $contents;

	socket_write($spawn, $output, strlen ($output)) or die("Could not write output\n");

	socket_close($spawn);
	usleep(200000);
}

socket_close($socket);
?>
