<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

if ($this->StartResultCache())
{
	if (!CModule::IncludeModule("iblock"))
		return;

	$cb_el = new CIBlockElement;

	//отправка формы
	if ($_POST["new_comment"] == "Y"){
		$section_id = false;

		//валидация полей
		$validate = true;
		if (strlen($_POST["name"]) < 3){
			$arResult["ERRORS"][] = "Имя должно содержать минимум 3 символа!";
			$validate = false;
		}
		if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
			$arResult["ERRORS"][] = "Неверный email";
			$validate = false;
		}
		if (strlen($_POST["message"]) < 3){
			$arResult["ERRORS"][] = "Текст сообщения должен содержать минимум 3 символа!";
			$validate = false;
		}

		if ($validate){
			//поиск и создание раздела, отвечающего за привязку по ID
			$db_list = CIBlockSection::GetList([],["CODE" => "el_".$_POST["element_id"]],false,["*"]);
			$ar_section = $db_list->GetNext();
			if (!is_array($ar_section)){
				$bs = new CIBlockSection;
				$arFields = Array(
					"ACTIVE" => "Y",
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"NAME" => "el_".$arParams["ELEMENT_ID"],
					"CODE" => "el_".$arParams["ELEMENT_ID"]
				);
				$section_id = $bs->Add($arFields);
			}else{
				$section_id = $ar_section["ID"];
			}

			//добавление нового комментария
			if ($section_id !== false){
				$elem = $cb_el->Add([
					"NAME" => time(),
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"PREVIEW_TEXT_TYPE" => "text",
					"PREVIEW_TEXT" => $_POST["name"]."---".$_POST["email"],
					"DETAIL_TEXT_TYPE" => "text",
					"DETAIL_TEXT" => $_POST["message"],
					"IBLOCK_SECTION_ID" => $section_id
				]);

				$_POST["name"] = "";
				$_POST["email"] = "";
				$_POST["message"] = "";
			}
		}
	}

	//получение комментариев
	$arSelect = ["*"];
	$arFilter = [
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SECTION_CODE" => "el_".$arParams["ELEMENT_ID"],
		"ACTIVE" => "Y"
	];
	$res = $cb_el->getList(
		array("created" => "ASC"),
		$arFilter,
		false,
		[],
		$arSelect
	);

	while($ob = $res->GetNextElement()){
		$arFields = $ob->GetFields();
		//распарсим NAME и EMAIL
		$arFields["PREVIEW_TEXT"] = strip_tags($arFields["PREVIEW_TEXT"]);
		$arFields["NAME"] = substr($arFields["PREVIEW_TEXT"],0,strpos($arFields["PREVIEW_TEXT"], "---"));
		$arFields["EMAIL"] = substr($arFields["PREVIEW_TEXT"],strpos($arFields["PREVIEW_TEXT"], "---") + 3);

		$arResult["ELEMENTS"][] = $arFields;
	}
}

//подключаем шаблон
if ($componentTemplate == ".default"){ $componentTemplate = ""; }
$this->includeComponentTemplate($componentTemplate);

?>
