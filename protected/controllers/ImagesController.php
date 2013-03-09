<?php

class ImagesController extends Controller
{
	/** 
	 * Shows an image, possibly resized in the desired... size!
	 *
	 * Call it like: localhost/index.php?r=images/show&src=images/boats/3/boat.jpg&width=100
	 * If only one dimension is given, aspect ratio is maintained.
	 * If both dimensions are given, and are far from aspect ratio, 
	 * the action tries to return a meaningful portion of the image.
	 */
	public function actionShow($src = '', $width = '', $height = '')
	{
		// sometime in the future, we should cache the result of the resize to make it fast.
		// we do not check for "is_file", because sometimes we are asked to resize remote images.
		//if (!is_file($src))
		//	throw new CHttpException(404);
		
		
		// if nothing given, simply redirect to the file.
		if (($width == '' && $height == '') || ($width == 0 && $height == 0))
		{
			$this->_send_headers($src);
			header('Location: ' . $src);
			Yii::app()->end();
		}

		// we need to resize..
		if (!($source_image = $this->_img_load($src)))
			throw new CHttpException(500);

		$original_width = imagesx($source_image);
		$original_height = imagesy($source_image);
		$requested_width = $width;
		$requested_height = $height;
		
		$src_x = 0;
		$src_y = 0;
		$dst_x = 0;
		$dst_y = 0;
		
		$src_w = 0;
		$src_h = 0;
		$dst_w = 0;
		$dst_h = 0;
		
		$this->_calculate_dimensions(
			$original_width, $original_height, 
			$requested_width, $requested_height, 
			$src_x, $src_y, $src_w, $src_h, $dst_w, $dst_h);
		
		//die('initial image width is ' . $original_width . ', requested width is ' . $width . ', new image width is ' . $new_img_width . ', src_x is ' . $src_x);
		
		$target_image = imageCreateTrueColor($dst_w, $dst_h);
		imageCopyResampled($target_image, $source_image, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
		
		
		// send it.
		$this->_send_headers($src);
		$this->_img_send($target_image, $src);
	}

	protected function _calculate_dimensions(
		$original_width, $original_height, 
		$requested_width, $requested_height, 
		&$src_x, &$src_y, &$src_w, &$src_h, &$dst_w, &$dst_h)
	{
		if ($requested_width == 0 && $requested_height == 0)
		{
			// no dimensions, no resizing
			$src_x = 0;
			$src_y = 0;
			$src_w = $original_width;
			$src_h = $original_height;
			$dst_w = $original_width;
			$dst_h = $original_height;
		}
		else if ($requested_height != 0 && $requested_width == 0)
		{
			// can be any width. resize based on height.
			$scale_factor = $requested_height / $original_height;
			
			$src_x = 0;
			$src_y = 0;
			$src_w = $original_width;
			$src_h = $original_height;
			$dst_w = $original_width * $scale_factor;
			$dst_h = $requested_height;
		}
		else if ($requested_width != 0 && $requested_height == 0)
		{
			// can be any height. resize based on width.
			$scale_factor = $requested_width / $original_width;
			
			$src_x = 0;
			$src_y = 0;
			$src_w = $original_width;
			$src_h = $original_height;
			$dst_w = $requested_width;
			$dst_h = $original_height * $scale_factor;
		}
		else if ($requested_width != 0 && $requested_height != 0)
		{
			// both dimensions given, scale, honouring aspect ratio. caller wants to fill said space.
			$dst_w = $requested_width;
			$dst_h = $requested_height;
			
			// first, try landscape image, as is usual, scale to width, check height to crop.
			$scale_factor = $requested_width / $original_width;
			$resulting_width = ceil($original_width * $scale_factor);
			$resulting_height = ceil($original_height * $scale_factor);
			
			if ($resulting_height > $requested_height)
			{
				// good, image is taller than requested. crop some at the top and bottom of it.
				$src_w = $original_width;
				$src_h = ceil($requested_height / $scale_factor);
				$src_x = 0;
				$src_y = ($original_height - $src_h) / 2;
			}
			else if ($resulting_height == $requested_height)
			{
				// good, aspect ratio is maintained!
				$src_w = $original_width;
				$src_h = $original_height;
				$src_x = 0;
				$src_y = 0;
			}
			else if ($resulting_height < $requested_height)
			{
				// image would not be tall enough, if we resized based on width. 
				// resize the height, check the width.
				$scale_factor = $requested_height / $original_height;
				$resulting_width = ceil($original_width * $scale_factor);
				$resulting_height = ceil($original_height * $scale_factor);
				
				// there is only one case: the image is wider than needed.
				// it cannot be the same, for if aspect ratio is maintened, we would be in the "elseif" above,
				// it cannot be narrower, for we would be in the first "if"
				$src_w = ceil($requested_width / $scale_factor);
				$src_h = $original_height;
				$src_x = ($original_width - $src_w) / 2;
				$src_y = 0;
			}
		}
	}
	
	protected function _send_headers($filename)
	{
		$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		if ($extension == "jpg" || $extension == "jpeg")
		{
			header("Content-type: image/jpeg");
		}
		else if($extension == "png")
		{
			header("Content-type: image/png");
		}
		elseif($extension == "gif")
		{
			header("Content-type: image/gif");
		}
	}
	
	protected function _img_load($source_file)
	{
		$image = false;
		$extension = strtolower(pathinfo($source_file, PATHINFO_EXTENSION));
		
		if ($extension == "jpg" || $extension == "jpeg")
		{
			$image = imageCreateFromJPEG($source_file);
		}	
		elseif ($extension == "png")
		{
			$image = imageCreateFromPNG($source_file);
		}	
		elseif ($extension == "gif")
		{
			$image = imageCreateFromGIF($source_file);
		}
		
		if (!$image)
		{
			// log_warning('_img_load(): could not load file "' . $source_file . '"');
			return false;
		}
		
		return $image;
	}

	protected function _img_send($image, $source_file)
	{
		$saved = false;
		$extension = strtolower(pathinfo($source_file, PATHINFO_EXTENSION));
		
		if ($extension == "jpg" || $extension == "jpeg")
		{
			$jpeg_quality = 90;
			$saved = imageJPEG($image);
		}
		else if($extension == "png")
		{
			$saved = imagePNG($image);
		}
		// Save GIF
		elseif($extension == "gif")
		{
			$saved = imageGIF($image);
		}
		
		return true;
	}
	
}