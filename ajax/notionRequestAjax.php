<?php
include_once ('../NotionRequest.php');

$request = new NotionRequest();
//$request->setNotionDatabaseId('268f41cbf2594d9bac220985898a3aec');
$request->setNotionDatabaseId('a7f14d61af20460b81ab35958cee4575');
$blockId = $request->getNotionPageId();
echo '<pre>' . __FILE__ . ':' . __LINE__ . ':<br>' . print_r($blockId, true) . '</pre>';
/*$result = $request->appendBlock($blockId, 'to_do', 'test');
echo '<pre>' . __FILE__ . ':' . __LINE__ . ':<br>' . print_r($result, true) . '</pre>';*/
