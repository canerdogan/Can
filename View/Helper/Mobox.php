<?php
/**
 *
 * @author canerdogan
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * Mobox helper
 *
 * @uses viewHelper Can
 */
class Can_View_Helper_Mobox extends Zend_View_Helper_ServerUrl
{

	public function mobox ($apps, $banner)
	{
		$button = '';
		
		if ($apps['type'] == 'photo')
			$button = '<input id="photo-upload-input" type="file" class="hide" name="" /><a href="#" class="btn btn-yellow btn-block file-uploader" data-find-input="#photo-upload-input">FOTOĞRAF YÜKLE</a>';
		
		$temp = '<p>{{label}} <input type="{{type}}" name="{{name}}" id="{{name}}link" value="{{value}}" class="input-block-level" /></p>';
		$forms = Zend_Json::decode($apps['form']);
		
		$form = '';
		if ($apps['is_youtube_available'] == 'Y')
			$form .= Can_View_Helper_Template::template(Array(
					'label' => 'Youtube linkini gir',
					'type' => 'text',
					'name' => 'youtube',
					'value' => ''
			), $temp);
		
		foreach ($forms as $element) {
			$form .= Can_View_Helper_Template::template($element, $temp);
		}
		
		return <<<HTML
<div id="{$apps['slug']}-yukle" class="mobox close-top {$apps['type']}-mobox yukle">
	<div class="mobox-closer modal-close">
		<i class="icon-remove-sign"></i>
	</div>
	<div class="mobox-admin">
		<header>
			<img src="{$banner}" alt=""/>
		</header>
		<form id="{$apps['type']}-upload">
			<fieldset>
				<div class="preview">
					<div class="item {$apps['type']}">
						<div class="item-media"></div>
					</div>
					{$button}
				</div>
				<div class="app-form">
					{$form}
					<button type="submit" class="btn btn-purple btn-block">KAYDET</button>
				</div>
				<div class="app-form hide form-hidden-area">
					<h4 class="text-center">
						Tebrikler!<br>Yarışmaya başarıyla katıldın.
					</h4>
					<div class="social-share">
						<a href="#" class="btn-facebook">Facebookta Paylaş</a>
						<div class="twitter-share">
							<a href="https://twitter.com/share" class="twitter-share-button" data-via="yavuzselimy">Tweet</a>
						</div>
					</div>
					<a href="#" class="btn btn-purple btn-block">SAYFAYI GÖR</a>
				</div>
			</fieldset>
		</form>
	</div>
</div>
HTML;
	}
}
