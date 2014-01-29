<?php
/**
 *
 * @author canerdogan
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * Content helper
 *
 * @uses viewHelper Can
 */
class Can_View_Helper_Content extends Zend_View_Helper_Abstract
{

	CONST TYPE_VIDEO = 'video';

	CONST TYPE_YOUTUBE = 'youtube';

	CONST TYPE_PHOTO = 'photo';

	CONST TYPE_AUDIO = 'audio';

	CONST TYPE_TEXT = 'text';

	CONST TYPE_DRAW = 'draw';

	private $_data;
	private $_type;

	public function content ($data, $type = 'photo')
	{
		$this->_data = json_decode($data);
		$this->_type = $type;
		return $this;
	}

	public function embed ($width = null, $height = null)
	{
		switch ($this->_type) {
			case self::TYPE_VIDEO:
				if($this->_data->type == self::TYPE_YOUTUBE)
					return '<iframe width="' . ($width?$width:'530') . '" height="' . ($height?$height:'370') . '" src="https://www.youtube.com/embed/' . $this->_data->content . '" frameborder="0" allowfullscreen></iframe>';
				else
					return $this->_data->content;
				break;
			case self::TYPE_AUDIO:
				return $this->_data->content;
				break;
			
			case self::TYPE_TEXT:
				return '<p>' . $this->_data->content . '</p>';
				break;
			
			default:
				return '<img src="' . $this->_data->content . '"' . ($width?' width="' . $width . '"':'') . ($height?' height="' . $height . '"':'') . '>';
				break;
		}
	}
}
