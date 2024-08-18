<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
  "GROUPS" => array(),
  "PARAMETERS" => array(

    "ID_HIGHLOAD" => array(
      "PARENT"=>"BASE",
      "NAME"=>"ID Highload блока",
      "MULTIPLE" => "N",
      "TYPE" => "INTEGER",
    ),
    "APIKEY" => array(
      "PARENT"=>"BASE",
      "NAME"=>"ApiKey от ipstack.com",
      "MULTIPLE" => "N",
      "TYPE" => "STRING",
      "DEFAULT"=>"e664fa9008eaec3711bbb7358a563e22",
    )
  ),
);
