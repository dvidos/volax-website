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
	public function actionShow($src = '', $width = 0, $height = 0, $maxwidth = 0, $maxheight = 0, $allowgrow = 1)
	{
		// first see if a file is given
		if ($src == '')
			throw new CHttpException(400, 'No image SRC given');

		$originalFilename = urldecode($src);
		if ($originalFilename != $src)
			Yii::log('Original filename for url "' . $src . '" is "' . $originalFilename . '"', 'info', 'ImagesController');
		
		if (!file_exists($originalFilename))
			throw new CHttpException('File "' . $originalFilename . '" not found');
		
		// declare variables
		$originalSize = new ImageInfo();
		$originalSize->sizeFromFile($originalFilename);
		$requestedSize = new ImageInfo($width, $height);
		$maxSize = new ImageInfo($maxwidth, $maxheight);
		$finalSize = new ImageInfo();
		$imageCopySource = new ImageInfo();
		
		// calculate final dimensions
		$this->calculateSizes($originalSize, $requestedSize, $maxSize, $finalSize, $imageCopySource, ($allowgrow == 1));
		
		// if same dimensions, send the original image
		if ($finalSize->sameAs($originalSize))
		{
			Yii::log('Final dimensions same as original. Redirecting to original source: "' . $src . '"', 'info', 'ImagesController');
			header('Location: ' . $src);
			return;
		}
		
		// get original timestamp to compare and/or save
		$originalTimestamp = $this->getFileTimestamp($originalFilename);
		if ($originalTimestamp === false)
		{
			Yii::log('Failed getting file timestamp for "' . $originalFilename . '"', 'error', 'ImagesController');
			header('Location: ' . $src);
			return;
		}
		
		// see if we have an image and if we can use it.
		$resizedFilename = $this->getResizedImageFileName($originalFilename, $finalSize);
		$resizedUrl = $this->filenameToUrl($resizedFilename);
		if (file_exists($resizedFilename))
		{
			if ($this->getFileTimestamp($resizedFilename) == $originalTimestamp)
			{
				Yii::log('Found resized file with same timestamp. Redirecting to it: "' . $resizedUrl . '"', 'info', 'ImagesController');
				header('Location: ' . $resizedUrl);
				return;
			}
		}
		
		// so, we want a specific size, there is no resized file, 
		// or it has different timestamp, hence we need to create it.
		Yii::log('Will resize image to ' . $finalSize->width . ' x ' . $finalSize->height, 'info', 'ImagesController');
		$originalImage = $this->loadImage($originalFilename);
		if ($originalImage === false)
		{
			Yii::log('Failed to load image "' . $originalFilename . '"', 'error', 'ImagesController');
			header('Location: ' . $src);
			return;
		}
		
		$resizedImage = $this->resizeImage($originalImage, $finalSize, $imageCopySource);
		if ($resizedImage === false)
		{
			Yii::log('Failed to resize image', 'error', 'ImagesController');
			header('Location: ' . $src);
			return;
		}
		//Yii::log('Image resized to ' . $finalSize->width . ' x ' . $finalSize->height, 'info', 'ImagesController');

			
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
		Yii::log('Redirecting to newly created resized image: "' . $resizedUrl . '"', 'info', 'ImagesController');
		header('Location: ' . $resizedUrl);
	}
	
	protected function calculateSizes($originalSize, $requestedSize, $maxSize, $finalSize, $imageCopySource, $allowGrow)
	{
		// first, depending on requested size
		if ($requestedSize->width != 0 && $requestedSize->height != 0)
		{
			// both dimensions givend, try to meet them
			$finalSize->copyFrom($requestedSize);
		}
		else if ($requestedSize->width != 0)
		{
			// width given, derive height
			$finalSize->copyFrom($originalSize);
			$factor = $requestedSize->width / $originalSize->width;
			$finalSize->resize($factor);
		}
		else if ($requestedSize->height != 0)
		{
			// height given, derive width
			$finalSize->copyFrom($originalSize);
			$factor = $requestedSize->height / $originalSize->height;
			$finalSize->resize($factor);
		}
		else // no requested size
		{
			$finalSize->copyFrom($originalSize);
		}
		
		
		// if now allowed to grow, and we have grown, keep original size
		if ($allowGrow == false && $finalSize->sizeLargerThan($originalSize))
		{
			$finalSize->copyFrom($originalSize);
		}

			
		// now, make sure we do not exceed our limits
		if ($maxSize->width != 0 && $finalSize->width > $maxSize->width)
		{
			$factor = $maxSize->width / $finalSize->width;
			$finalSize->resize($factor);
		}
		if ($maxSize->height != 0 && $finalSize->height > $maxSize->height)
		{
			$factor = $maxSize->height / $finalSize->height;
			$finalSize->resize($factor);
		}
		
		
		// now, derive the source rectangle
		$originalRatio = $originalSize->getAspectRatio();
		$finalRatio = $finalSize->getAspectRatio();
		Yii::log('Original aspect ratio ' . $originalRatio . ', final aspect ratio ' . $finalRatio, 'debug', 'ImagesController');
		
		if ($finalRatio == $originalRatio)
		{
			// same aspect ratio, we take the whole picture
			$imageCopySource->copyFrom($originalSize);
			$imageCopySource->x = 0;
			$imageCopySource->y = 0;
		}
		else if ($finalRatio < $originalRatio)
		{
			// final image is narrower than original - we take the whole height
			
			$imageCopySource->y = 0;
			$imageCopySource->height = $originalSize->height;
			
			$imageCopySource->width = round($originalSize->width * ($finalRatio / $originalRatio));
			$imageCopySource->x = floor($originalSize->width - $imageCopySource->width) / 2;
		}
		else if ($finalRatio > $originalRatio)
		{
			// final image is wider than original - we take the whole width
			// final image is narrower than original - we take the whole height
			$imageCopySource->x = 0;
			$imageCopySource->width = $originalSize->width;
			
			$imageCopySource->height = round($originalSize->height * ($originalRatio / $finalRatio));
			$imageCopySource->y = floor($originalSize->height - $imageCopySource->height) / 2;
		}
		
		Yii::log('ImageCopySource: Width=' . $imageCopySource->width . ', Height=' . $imageCopySource->height . ', X=' . $imageCopySource->x . ', Y=' . $imageCopySource->y, 'debug', 'ImagesController');
	}
	
	
	
	
	
	protected function getResizedImageFileName($filename, $size)
	{
		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		$before_extension = substr($filename, 0, strlen($filename) - strlen($extension) - 1);
		return $before_extension . '.' . $size->width . 'x' . $size->height . '.' . $extension;
	}
	
	protected function resizeImage($originalImage, $finalSize, $imageCopySource)
	{
		$resizedImage = imageCreateTrueColor($finalSize->width, $finalSize->height);
		imageCopyResampled($resizedImage, $originalImage, 
			0, 0, 
			$imageCopySource->x, $imageCopySource->y, 
			$finalSize->width, $finalSize->height, 
			$imageCopySource->width, $imageCopySource->height);
		
		return $resizedImage;
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


class ImageInfo
{
	function __construct($width = 0, $height = 0, $x = 0, $y = 0)
	{
		$this->width = $width;
		$this->height = $height;
		$this->x = $x;
		$this->y = $y;
	}
	
	public $width;
	public $height;
	public $x;
	public $y;

	function copyFrom(ImageInfo $other)
	{
		$this->width = $other->width;
		$this->height = $other->height;
		$this->x = $other->x;
		$this->y = $other->y;
	}
	
	function sizeFromFile($filename)
	{
		$size = getimagesize($filename);
		if ($size === false)
			return false;
		
		$this->width = $size[0];
		$this->height = $size[1];
		
		return true;
	}
	
	function getAspectRatio()
	{
		return ($this->height == 0) ? 0 : ($this->width / $this->height);
	}
	
	function resize($factor)
	{
		$this->width = round($this->width * $factor);
		$this->height = round($this->height * $factor);
	}

	function sameAs(ImageInfo $other)
	{
		return ($this->width == $other->width && $this->height == $other->height && $this->x == $other->x && $this->y == $other->y);
	}
	
	function sizeLargerThan(ImageInfo $other)
	{
		return ($this->width > $other->width || $this->height > $other->height);
	}
}

