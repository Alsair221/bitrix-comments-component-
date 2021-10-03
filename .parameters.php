<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!\Bitrix\Main\Loader::includeModule('iblock'))
	return;

//получаем типы инфоблоков
$arIBlockType_res = \Bitrix\Iblock\TypeLanguageTable::getList([
	'select' => ['*']
]);
$arIBlockType = [];
while ($item = $arIBlockType_res->fetch()){
	if ($item["LANGUAGE_ID"] == LANGUAGE_ID)
		$arIBlockType[$item["IBLOCK_TYPE_ID"]] = $item["NAME"];
}

//получаем инфоблоки, в зависимости от выбранного типа
$arIBlock_res = \Bitrix\Iblock\IblockTable::getList([
	'filter' => ["=IBLOCK_TYPE_ID" => $arCurrentValues["IBLOCK_TYPE"]],
	'select' => ['*']
]);
$arIBlock = [];
while ($item = $arIBlock_res->fetch()){
	$arIBlock[$item["ID"]] = $item["NAME"];
}


//основной массив параметров
$arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS" => array(
		"IBLOCK_TYPE" => array(
			"NAME" => "Тип инфоблока комментариев",
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"NAME" => "Инфоблок комментариев",
			"TYPE" => "LIST",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
		),
		"ELEMENT_ID" => array(
			"NAME" => "ID элемента, к которому вывести комментарии",
			"TYPE" => "STRING",
			"DEFAULT" => "1",
		)
	),
);

?>
