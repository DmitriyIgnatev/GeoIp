<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule("highloadblock");
?>

<?php
// ----- ФУНКЦИЯ ДЛЯ ПАРСИНГА JSON ФАЙЛА -----
function CBR_XML_Daily_Ru($key, $ip)
{
    static $rates;

    if ($rates === null) {
        $rates = json_decode(file_get_contents("http://api.ipstack.com/$ip?access_key=" . $key));
    }

    return $rates;
}

// ----- ФУНКЦИЯ ОБЪЕКТ -> АССОЦИАТИВНЫЙ МАССИВ -----
function objectToArray($d)
{
    if (is_object($d)) {
        $d = get_object_vars($d);
    }
    if (is_array($d)) {
        return array_map(__FUNCTION__, $d); // recursive
    } else {
        return $d;
    }
}

// ----- ОСНОВЫНЕ ДЕЙСТВИЯ -----
if (empty($_POST['ip'])) {
    // ----- ВАЛИДАЦИЯ ПУСТОГО ЗНАЧЕНИЯ -----
    echo 'Укажите ip';
} else {
    // ----- ПРИ УСПЕШНОМ AJAX ЗАПРОСЕ ВЫПОЛНЯЕТСЯ АЛГОРИТМ -----

    $ip = $_POST["ip"]; // -- IP ВВЕДЕНЫЙ ПОЛЬЗОВАТЕЛЕМ
    $flag = false; // -- ПЕРЕМЕННАЯ ПЕРЕКЛЮЧАТЕЛЬ ЕСЛИ ЕСТЬ ЗНАЧЕНИЕ В ДБ -> TRUE ИНАЧЕ -> FALSE


    // ----- АЛГОРИТМ ПОЛУЧЕНИЯ ВСЕХ ЗНАЧЕНИЙ HL БЛОКОВ -----

    // делаем выборку хайлоуд блока
    $arHLBlock = Bitrix\Highloadblock\HighloadBlockTable::getById($_POST['id'])->fetch();
    // инициализируем класс сущности хайлоуд блока
    $obEntity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHLBlock);
    // обращаемся к DataManager
    $strEntityDataClass = $obEntity->getDataClass();
    $rsData = $strEntityDataClass::getList(array(
        // необходимые для выборки поля
        'select' => array('ID', 'UF_NAME', 'UF_DESCRIPTION'),
    ));

    // ------
    // В ЦИКЛЕ ПРОВЕРЯЕМ, СУЩ. ЛИ IP ПОЛЬЗОВАТЕЛЯ В БД, ЕСЛИ НЕТ flag = true
    while ($arItem = $rsData->Fetch()) {
        if ($_POST["ip"] == $arItem['UF_NAME']) {
            $flag = true;
            break;
        }
    }

    // УСЛОВИЕ ЕСЛИ ЗНАЧЕНИЕ НЕ СУЩ. ТО ДОБАВЛЯЕМ В БД
    if ($flag == false && $_POST["ip"] != null) {

        // Если нет ip в HL, то делаем запрос
        $data = objectToArray(CBR_XML_Daily_Ru($_POST['key'], $_POST['ip']));

        // ОБРАБОТКА ИСКЛЮЧЕНИЙ
        try {
            if ($data['error']['type'] == 'invalid_ip_address') {
                echo "Неверный ip адрес";
                exit;
            }

        } catch (Exception $e) {
            // ЗАГЛУШКА
            true;
        }

        // ГЕНЕРАЦИЯ ОПИСАНИЯ ПО IP
        $description = $data['continent_name'] . ' | ' . $data['region_name'] . ' | ' . $data['city'];

        // СОСТАВЛЕНИЕ МАССИВА ДЛЯ ДОБАВЛЕНИЯ В БД
        $arElementFields = array(
            'UF_NAME' => $_POST["ip"],
            'UF_FILE' => "",
            'UF_LINK' => "",
            'UF_SORT' => 100,
            'UF_DEF' => "",
            'UF_XML_ID' => rand(),
            'UF_DESCRIPTION' => $description,
        );

        // ДОБАВЛЕНИЕ
        $obResult = $strEntityDataClass::add($arElementFields);

        // можем сразу полочить информацию о добавленом поле
        $ID = $obResult->getID();
        $bSuccess = $obResult->isSuccess();
        print ("Добавлена запись в HL" . $description . "<br>");
        print ("Айпи: " . $_POST["ip"]);

    } else if ($flag == true) { // ЕСЛИ АЙПИ ЕСТЬ В БД ТО ПРОСТО ВЫДАЕМ РЕЗУЛЬТАТ
        $description = $arItem['UF_DESCRIPTION'];
        print ('Запись найдена <br>');
        print ("Айпи: " . $arItem['UF_NAME'] . "<br>");
        print ("Описание: " . $description . "<br>");
    }
}
?>
