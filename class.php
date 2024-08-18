<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use Bitrix\Main\Localization\Loc;//Для работы с языковыми переменными

class CMyComponentName extends CBitrixComponent
{
    public function onIncludeComponentLang()
    {
        Loc::loadMessages(dirname(__FILE__) . "/_class.php");
    }

    public function onPrepareComponentParams($arParams)
    {
        if ($arParams["APIKEY"] == "" || !isset($arParams["APIKEY"])) {
            $arParams["APIKEY"] == "e664fa9008eaec3711bbb7358a563e22";
        }

        return $arParams;
    }

    public function executeComponent()
    {
        $this->arResult["ID_HIGHLOAD"] = $this->arParams["ID_HIGHLOAD"];
        $this->arResult["APIKEY"] = $this->arParams["APIKEY"];
        $this->IncludeComponentTemplate();
    }
}
