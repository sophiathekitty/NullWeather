<?php
require_once("../../../../includes/main.php");
$data = [];
$data['pollution'] = Pollution::LoadCurrentPollution();
OutputJson($data);
?>