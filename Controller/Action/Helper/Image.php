<?php
class Can_Controller_Action_Helper_Image extends Zend_Controller_Action_Helper_Abstract
	{
	
	public function direct($filename) {
		$this->load($filename);
		return $this;
	}
		
	public function load($filename) {
		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];
		if( $this->image_type == IMAGETYPE_JPEG ) {
			$this->image = imagecreatefromjpeg($filename);
		} elseif( $this->image_type == IMAGETYPE_GIF ) {
			$this->image = imagecreatefromgif($filename);
		} elseif( $this->image_type == IMAGETYPE_PNG ) {
			$this->image = imagecreatefrompng($filename);
		}
		return $this;
	}
	
	public function save($filename, $image_type=IMAGETYPE_PNG, $compression=75, $permissions=null) {
		if( $image_type == IMAGETYPE_JPEG ) {
			if(!imagejpeg($this->image,$filename,$compression))
				throw new Zend_Exception('Fuck off!');
			
		} elseif( $image_type == IMAGETYPE_GIF ) {
			imagegif($this->image,$filename);
		} elseif( $image_type == IMAGETYPE_PNG ) {
			imagepng($this->image,$filename);
		}
		if( $permissions != null) {
			chmod($filename,$permissions);
		}
		return $this;
	}
	
	public function output($image_type=IMAGETYPE_JPEG) {
		if( $image_type == IMAGETYPE_JPEG ) {
			imagejpeg($this->image);
		} elseif( $image_type == IMAGETYPE_GIF ) {
			imagegif($this->image);
		} elseif( $image_type == IMAGETYPE_PNG ) {
			imagepng($this->image);
		}
		return $this;
	}
	
	public function getWidth() {
		return imagesx($this->image);
	}
	
	public function getHeight() {
		return imagesy($this->image);
	}
	
	public function resizeToHeight($height) {
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->_resize($width,$height);
		return $this;
	}
	
	public function resizeToWidth($width) {
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		$this->_resize($width,$height);
		return $this;
	}
	
	public function scale($scale) {
		$width = $this->getWidth() * $scale/100;
		$height = $this->getheight() * $scale/100;
		$this->_resize($width,$height);
		return $this;
	}

	private function _resize($width,$height) {
//		print 'width: '. $width . "\n<br/>";
//		print 'height: '. $height . "\n<br/>";
		
		$original_width = $this->getWidth();
		$original_height = $this->getHeight();
//		print '$original_width: '. $original_width . "\n<br/>";
//		print '$original_height: '. $original_height . "\n<br/>";
		
		if($original_width > $width OR $original_height > $height){
			$w = $width;
			if($width==0)$w = $original_width;
			$h = $height;
			if($height==0)$h = $original_height;
		}else{
			$w = $original_width;
			$h = $original_height;
		}
//		print '$w: '. $w . "\n<br/>";
//		print '$h: '. $h . "\n<br/>";
			
		$oranw	= $original_width/$w;
		$oranh	= $original_height/$h;
		$oran	= ( $oranw > $oranh ? $oranh : $oranw );
		
//		print '$oran: '. $oran . "\n<br/>";
//		print '$h: '. $h . "\n<br/>";
		
		$yeniw	= $w*$oran;
		$yenih	= $h*$oran;
		
//		print '$yeniw: '. $yeniw . "\n<br/>";
//		print '$yenih: '. $yenih . "\n<br/>";
		
		$farkx	= $original_width - $yeniw;
		$farky	= $original_height - $yenih;
		
//		print '$farkx: '. $farkx . "\n<br/>";
//		print '$farky: '. $farky . "\n<br/>";
		
		$sx		= (int)($farkx/2);
		$sy		= (int)($farky/2);
		$bw		= (int)($original_width-$farkx);
		$bh		= (int)($original_height-$farky);
		
//		print '$sx: '. $sx . "\n<br/>";
//		print '$sy: '. $sy . "\n<br/>";
//		print '$bw: '. $bw . "\n<br/>";
//		print '$bh: '. $bh . "\n<br/>";

		$new_image = imagecreatetruecolor($w, $h);

		/* Check if this image is PNG or GIF, then set if Transparent*/
		if ( $this->image_type == IMAGETYPE_GIF || $this->image_type == IMAGETYPE_PNG ) {
			$trnprt_indx = imagecolortransparent($this->image);

			// If we have a specific transparent color
			if ($trnprt_indx >= 0) {

				// Get the original image's transparent color's RGB values
				$trnprt_color    = imagecolorsforindex($this->image, $trnprt_indx);

				// Allocate the same color in the new image resource
				$trnprt_indx    = imagecolorallocate($new_image, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);

				// Completely fill the background of the new image with allocated color.
				imagefill($new_image, 0, 0, $trnprt_indx);

				// Set the background color for new image to transparent
				imagecolortransparent($new_image, $trnprt_indx);
			}
			// Always make a transparent background color for PNGs that don't have one allocated already
			elseif ($this->image_type == IMAGETYPE_PNG) {

				// Turn off transparency blending (temporarily)
				imagealphablending($new_image, false);

				// Create a new transparent color for image
				$color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);

				// Completely fill the background of the new image with allocated color.
				imagefill($new_image, 0, 0, $color);

				// Restore transparency blending
				imagesavealpha($new_image, true);
			}
		}else{
			$white = imagecolorallocate($new_image, 255, 255, 255);
			imagefill($new_image, 0, 0, $white);
		}

		imagecopyresampled($new_image, $this->image, 0, 0, $sx, $sy, $w, $h, $bw, $bh);
		$this->image = $new_image;
	}

	public function resize($width = 0, $height = 0){
		if($width == 0 && $height > 0){
			$this->resizeToHeight($height);
		}else if($height == 0 && $width > 0){
			$this->resizeToWidth($width);
		}else if($height > 0 && $width > 0){
			$this->_resize($width, $height);
		}
		return $this;
	}
	
	private function _crop($width, $height, $left, $top, $target_width, $target_height) {
		
		$target_width = isset($target_width)?$target_width:$this->getWidth();
		$target_height = isset($target_height)?$target_height:$this->getHeight();
		
		$new_image = imagecreatetruecolor($target_width, $target_height);

		/* Check if this image is PNG or GIF, then set if Transparent*/
		if ( $this->image_type == IMAGETYPE_GIF || $this->image_type == IMAGETYPE_PNG ) {
			$trnprt_indx = imagecolortransparent($this->image);

			// If we have a specific transparent color
			if ($trnprt_indx >= 0) {

				// Get the original image's transparent color's RGB values
				$trnprt_color    = imagecolorsforindex($this->image, $trnprt_indx);

				// Allocate the same color in the new image resource
				$trnprt_indx    = imagecolorallocate($new_image, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);

				// Completely fill the background of the new image with allocated color.
				imagefill($new_image, 0, 0, $trnprt_indx);

				// Set the background color for new image to transparent
				imagecolortransparent($new_image, $trnprt_indx);
			}
			// Always make a transparent background color for PNGs that don't have one allocated already
			elseif ($this->image_type == IMAGETYPE_PNG) {

				// Turn off transparency blending (temporarily)
				imagealphablending($new_image, false);

				// Create a new transparent color for image
				$color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);

				// Completely fill the background of the new image with allocated color.
				imagefill($new_image, 0, 0, $color);

				// Restore transparency blending
				imagesavealpha($new_image, true);
			}
		}else{
			$white = imagecolorallocate($new_image, 255, 255, 255);
			imagefill($new_image, 0, 0, $white);
		}

		imagecopyresampled($new_image, $this->image, 0, 0, $left, $top, $target_width, $target_height, $width, $height);
		$this->image = $new_image;
	}
	
	public function crop($width, $height, $left=0, $top=0, $target_width, $target_height){
		$this->_crop($width, $height, $left, $top, $target_width, $target_height);
		return $this;
	}
}