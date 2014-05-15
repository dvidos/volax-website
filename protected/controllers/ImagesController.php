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
		if ($src == '')
			throw new CHttpException(400, 'No image SRC given');

		// if nothing given, simply redirect to the file.
		if (($width == '' && $height == '') || ($width == 0 && $height == 0))
		{
			Yii::log('No dimensions given, redirecting to original source: "' . $src . '"', 'info', 'ImagesController');
			header('Location: ' . $src);
			return;
		}
		
		// usually, spaces will be rendered as %20. try to decode here...
		$originalFilename = urldecode($src);
		if ($originalFilename != $src)
			Yii::log('Original filename for url "' . $src . '" is "' . $originalFilename . '"', 'info', 'ImagesController');
			
		
		if (!file_exists($originalFilename))
			throw new CHttpException('File "' . $originalFilename . '" not found');
		
		
		// see if we already have this file at stock.
		$resizedFilename = $this->getResizedImageFileName($originalFilename, $width, $height);
		if ($resizedFilename === false)
		{
			Yii::log('Failed getting new image filename for "' . $originalFilename . '"', 'error', 'ImagesController');
			header('Location: ' . $src);
			return;
		}
		
		$originalTimestamp = $this->getFileTimestamp($originalFilename);
		if ($originalTimestamp === false)
		{
			Yii::log('Failed getting file timestamp for "' . $originalFilename . '"', 'error', 'ImagesController');
			header('Location: ' . $src);
			return;
		}
		
		// see if timestamps match, so we send the resized one.
		if (file_exists($resizedFilename))
		{
			$resizedTimestamp = $this->getFileTimestamp($resizedFilename);
			if ($resizedTimestamp === false)
			{
				Yii::log('Failed getting file timestamp for "' . $resizedFilename . '"', 'error', 'ImagesController');
				header('Location: ' . $src);
				return;
			}
			
			if ($originalTimestamp == $resizedTimestamp)
			{
				// encode the slashes as well ???
				$url = $this->filenameToUrl($resizedFilename);
				Yii::log('Resized file "' . $resizedFilename . '" has same timestamp as original. Redirecting to url "' . $url . '"', 'info', 'ImagesController');
				header('Location: ' . $url);
				return;
			}
		}
		
		
		// so, we want a specific size, there is no resized file, 
		// or it has different timestamp, hence we need to create it.
		$image = $this->loadImage($originalFilename);
		if ($image === false)
		{
			Yii::log('Failed to load image "' . $originalFilename . '"', 'error', 'ImagesController');
			header('Location: ' . $src);
			return;
		}
		
		$resizedImage = $this->resizeImage($image, $width, $height);
		if ($resizedImage === false)
		{
			Yii::log('Failed to resize image', 'error', 'ImagesController');
			header('Location: ' . $src);
			return;
		}
		Yii::log('Image resized to ' . $width . ' x ' . $height, 'info', 'ImagesController');

			
		if (!$this->saveImage($resizedImage, $resizedFilename))
		{
			Yii::log('Failed to save resized image: "' . $resizedFilename . '"', 'error', 'ImagesController');
			header('Location: ' . $src);
			return;
		}
		
		if (!$this->setFileTimestamp($resizedFilename, $originalTimestamp))
		{
			Yii::log('Failed to set resized image file timestamp: "' . $resizedFilename . '"', 'error', 'ImagesController');
			header('Location: ' . $src);
			return;
		}
		
		
		// finally...
		$url = $this->filenameToUrl($resizedFilename);
		Yii::log('Redirecting to newly created resized image: "' . $url . '"', 'info', 'ImagesController');
		header('Location: ' . $url);
	}
	
	
	
	protected function getResizedImageFileName($filename, $width = '', $height = '')
	{
		// if no dimensions given, return original filename
		if ($width == '' && $height == '')
			return $filename;
		
		// derive one dimension if one is not given
		$size = getimagesize($filename);
		if ($size === false)
			return false;
		
		$original_width = $size[0];
		$original_height = $size[1];
		
		if ($width == '')
			$width = ceil($original_width * ($height / $original_height));

		if ($height == '')
			$height = ceil($original_height * ($width / $original_width));

		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		$before_extension = substr($filename, 0, strlen($filename) - strlen($extension) - 1);
		return $before_extension . '.' . $width . 'x' . $height . '.' . $extension;
	}
	
	protected function resizeImage($originalImage, &$width, &$height)
	{
		$original_width = imagesx($originalImage);
		$original_height = imagesy($originalImage);
		$requested_width = $width;
		$requested_height = $height;
		
		$src_x = 0;
		$src_y = 0;
		$src_w = 0;
		$src_h = 0;
		$dst_w = 0;
		$dst_h = 0;
		
		$this->calculateDimensions(
			$original_width, $original_height, 
			$requested_width, $requested_height, 
			$src_x, $src_y, $src_w, $src_h, $dst_w, $dst_h);
		
		$resizedImage = imageCreateTrueColor($dst_w, $dst_h);
		imageCopyResampled($resizedImage, $originalImage, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
		
		$width = $dst_w;
		$height = $dst_h;
		
		return $resizedImage;
	}
	
	protected function calculateDimensions(
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
			$dst_w = ceil($original_width * $scale_factor);
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
			$dst_h = ceil($original_height * $scale_factor);
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
	
	protected function loadImage($filename)
	{
		Yii::log('Loading image "' . $filename . '"', 'info');
		$image = false;
		$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		
		if ($extension == "jpg" || $extension == "jpeg")
			$image = imageCreateFromJPEG($filename);
		elseif ($extension == "png")
			$image = imageCreateFromPNG($filename);
		elseif ($extension == "gif")
			$image = imageCreateFromGIF($filename);
		
		if (!$image)
			Yii::log('Failed loading image "' . $filename . '"', 'error');
		
		return $image;
	}

	protected function saveImage($img, $filename)
	{
		Yii::log('Saving image to "' . $filename . '"', 'info');
		$saved = false;
		$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		
		if ($extension == "jpg" || $extension == "jpeg")
			$saved = imagejpeg($img, $filename);
		elseif ($extension == "png")
			$saved = imagepng($img, $filename);
		elseif ($extension == "gif")
			$saved = imagegif($img, $filename);
		
		if (!$saved)
			Yii::log('Failed saving image "' . $filename . '"', 'error');
		
		return $saved;
	}
	
	protected function getFileTimestamp($filename)
	{
		return filemtime($filename);
	}
	
	protected function setFileTimestamp($filename, $timestamp)
	{
		if (!file_exists($filename))
			return false;
		
		return touch($filename, $timestamp);
	}
	
	protected function filenameToUrl($filename)
	{
		// urlencode() will not work. "uploads/tinos.jpg" becomes "uploads%2F/tinos.jpg"
		$parts = explode('/', $filename);
		for ($i = 0; $i < count($parts); $i++)
			$parts[$i] = rawurlencode($parts[$i]);
			
		$url = implode('/', $parts);
		return $url;
	}
}