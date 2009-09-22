<?php

require_once('UStream.php');

$ustream = new UStream('6FD7A12971BFFDD81A5869FDE60756DC');

$ustream->setRequestMode('user');
$result = $ustream->getInfo('klederson');

print "<pre>";
	print_r($ustream->cacheResult);
print "</pre>";

print "<hr>";
print memory_get_peak_usage(true)/1024 . ' kb';

?>