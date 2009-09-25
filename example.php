<?php

require_once('UStream.php');

$ustream = new UStream('6FD7A12971BFFDD81A5869FDE60756DC');

$ustream->setRequestMode('channel');
//$result = $ustream->getInfo('aleac');

print "<pre>";
$ustream->getCustomEmbedTag('aleac',600,300,true,false);
print_r($ustream);
	//print_r($ustream->cacheResult);
print "</pre>";

print "<hr>";
print memory_get_peak_usage(true)/1024 . ' kb';

?>