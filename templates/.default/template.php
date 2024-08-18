<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
use Bitrix\Highloadblock\HighloadBlockTable;
\Bitrix\Main\Loader::includeModule("highloadblock");
?>
<div class='tz'>

    <form id="form" method="POST">
        <input type="text" name="ip" placeholder="Введите ip" required>
        <button type="submit" id="form-submit">Отправить</button>
    </form>


    <div id="message">

    </div>
    <script>
        $("#form").on("submit", function () {
            var data = $(this).serializeArray();
            data.push({ name: "key", value: "<?= $arResult['APIKEY'] ?>" });
            data.push({ name: "id", value: "<?= $arResult['ID_HIGHLOAD'] ?>" });

            $.ajax({
                url: '/bitrix/components/bitrix/ip.search/ajax.php',
                method: 'post',
                data: $.param(data),
                success: function (data) {
                    $('#message').html(data);
                }
            });
            return false;
        });
    </script>
</div>

<script src="/assets/js/jquery-3.7.0.min.js"></script>
