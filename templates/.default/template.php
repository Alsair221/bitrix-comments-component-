<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<div class="comments">
	<?
	if ($arResult["ERRORS"]){
		foreach($arResult["ERRORS"] as $error){
			echo "<p style='color: #f00'>".$error."</p>";
		}
	}

	foreach($arResult["ELEMENTS"] as $elem){
		?>
		<div class="comments_element">
			<p><?echo $elem["NAME"];?> - <a href="mailto:<?echo $elem["EMAIL"];?>"><?echo $elem["EMAIL"];?></a></p>
			<p>Сообщение: <?echo $elem["DETAIL_TEXT"];?></p>
		</div>
		<?
	}
	?>

	<form name="comments_form" method="post">
		<input type="hidden" name="new_comment" value="Y" />
		<input type="hidden" name="element_id" value="<?echo $arParams["ELEMENT_ID"];?>" />
		<input type="text" name="name" placeholder="Введите имя" value="<?echo $_POST["name"];?>" />
		<input type="text" name="email" placeholder="Введите email" value="<?echo $_POST["email"];?>" />
		<textarea name="message"><?echo $_POST["message"];?></textarea>
		<button>Отправить</button>
	</form>
</div>
