<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
  die();
$arComponentDescription = array(
  "NAME" => GetMessage("DMITRIY_CURRENT_DESCRIPTION_NAME"),
  "DESCRIPTION" => GetMessage("DMITRIY_CURRENT_DESCRIPTION_TEXT"),
  "PATH" => array(
    "ID" => "nikolaevevge_components",
    "NAME" => GetMessage("DMITRIY_CURRENT_DESCRIPTION_GROUP_NAME"),
    "CHILD" => array(
      "ID" => "curdate",
      "NAME" => GetMessage("DMITRIY_CURRENT_DESCRIPTION_CHILD_NAME")
    )
  ),
  //  "ICON" => "/images/icon.gif",
);
