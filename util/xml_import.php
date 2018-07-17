<?php


//check:
//http://www.w3school.com.cn/php/php_xml_simplexml.asp

$xmlDoc = new DOMDocument();
$xmlDoc->load("note.xml");

print $xmlDoc->saveXML();
?> 