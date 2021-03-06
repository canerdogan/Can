<?php

class Can_Controller_Action_Helper_Watermark extends Zend_Controller_Action_Helper_Abstract
{

	protected $_waterMarkedImage;
	public $pluginLoader;

	public function __construct()
	{
		$this->pluginLoader = new Zend_Loader_PluginLoader();
	}

	function watermark($file, $waterMarkLogo, $destination = NULL)
	{
		$path = dirname($file);
		$fileName = basename($file);
		if (!is_dir($path)) {
			throw new Exception("Invalid directory path provided");
		}
		if (!file_exists($file)) {
			throw new Exception("File doesn't exist in the given directory");
		}
		if (is_null($destination)) {
			$destinationFile = $path . DIRECTORY_SEPARATOR . $fileName;
		} else {
			$destinationFile = $destination;
		}
		$stamp = $this->getImageStamp($waterMarkLogo);
		$im = $this->getImageStamp($file);
		$img = NULL;
		if ($im && $stamp) {
			$sx = imagesx($stamp);
			$sy = imagesy($stamp);
			$marge_right = 20;
			$marge_bottom = 20;
			imagecopy(
					$im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0,
					imagesx($stamp), imagesy($stamp)
			);
			//saves image in corresponding format
			$extension = $this->getExtention($file);
			switch ($extension) {
				case "png":
					$img = imagepng($im, $destinationFile);
					break;
				case "jpeg":
				case "jpg":
					$img = imagejpeg($im, $destinationFile, 100);
					break;
				case "gif":
					$img = imagegif($im, $destinationFile);
					break;
				default:
					$img = NULL;
			}
		}
		if ($img) {
			$img = $destinationFile;
		}

		return $img;
	}

	public function getImageStamp($image)
	{
		$extension = $this->getExtention($image);
		switch ($extension) {
			case "png":
				$img = imagecreatefrompng($image);
				break;
			case "jpeg":
			case "jpg":
				$img = imagecreatefromjpeg($image);
				break;
			case "gif":
				$img = imagecreatefromgif($image);
				break;
			default:
				$img = NULL;
		}

		return $img;
	}

	public function getExtention($image)
	{
		$fileInfo = explode("/", $image);
		$fileName = $fileInfo[sizeof($fileInfo) - 1];
		$pathInfo = pathinfo($fileName);

		return $pathInfo['extension'];
	}

	public function direct($file, $waterMarkLogo = NULL, $destination = NULL)
	{
		if (!$waterMarkLogo) {
			//default logo to watermark an image
			$waterMarkLogo = IMAGE_PATH . DIRECTORY_SEPARATOR . "themes" . DIRECTORY_SEPARATOR . "copyright-logo.png";
		}

		return $this->watermark($file, $waterMarkLogo, $destination);
	}

}