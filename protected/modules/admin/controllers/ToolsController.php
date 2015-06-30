<?phpclass ToolsController extends Controller{	function actionIndex()	{		$this->render('index', array(			'xml_sitemap_filename' => $this->getSitemapFilename(),			'xml_sitemap_url' => $this->getSitemapUrl(),		));	}		function actionXmlSitemap()	{		$posts = Post::model()->findAll(array(			'condition'=>'status = '.Post::STATUS_PUBLISHED,			'order'=>'create_time DESC',		));				$categories = Category::model()->findAll(array(			'condition'=>'status = '.Category::STATUS_PUBLISHED,			'order'=>'parent_id, view_order',		));				$xml = $this->renderPartial('xmlSitemap', array(			'posts'=>$posts,			'categories'=>$categories,		), true);				$filename = $this->getSitemapFilename();		$result = file_put_contents($filename, $xml);		Yii::app()->user->setFlash('xmlSitemap','Δημιουργία αρχείου ' . $filename . ': ' . ($result === false ? 'Σφάλμα!' : 'Επιτυχής!'));		$this->redirect(array('/admin/tools'));	}		/**	 * filename to save the xml sitemap for google and other search engines.	 */	function getSitemapFilename() { return dirname(Yii::app()->basePath) . '/sitemap.xml'; }	function getSitemapUrl() { return Yii::app()->baseUrl . '/sitemap.xml'; }					function actionDiskUsage()	{		$baseHref = dirname(Yii::app()->basePath);		$baseDirs = array(			 // $baseHref . '/assets',			 // $baseHref . '/dv',			 // $baseHref . '/old-volax-tinos-gr',			 // $baseHref . '/phpmyadmin',			 // $baseHref . '/protected',			 // $baseHref . '/themes',			 $baseHref . '/uploads',			 // $baseHref . '/yii',		);				// scan tree recursively with subfolders		$tree = array();		foreach ($baseDirs as $baseDir)			$tree[] = $this->diskUsageOfDirectory($baseDir, strlen($baseHref));				// produce a flat array of files and directories		$dirs = arraY();		$files = array();		foreach ($tree as $treeBranch)			$this->flattenTreeStructure($treeBranch, $dirs, $files);				$this->render('diskUsage', array(			'baseHref'=>$baseHref,			'dirs'=>$dirs,			'files'=>$files,		));	}		function diskUsageOfDirectory($dirPath, $chopLen)	{		$result = array(			'href'=>substr($dirPath, $chopLen),			'size'=>0,			'dirs'=>array(),			'files'=>array(),			'tree_size'=>0,			'tree_files'=>0,		);				if (!is_dir($dirPath))			return $result;				$d = opendir($dirPath);		while (($entry = readdir($d)) !== false)		{			$entryPath = $dirPath . '/' . $entry;			$entryHref = substr($entryPath, $chopLen);						if ($entry == '.' || $entry == '..')			{				continue;			}			else if (is_dir($entryPath))			{				$subdir = $this->diskUsageOfDirectory($entryPath, $chopLen);				$result['dirs'][] = $subdir;				$result['tree_size'] += $subdir['tree_size'];				$result['tree_files'] += $subdir['tree_files'];			}			else if (is_file($entryPath))			{				$size = filesize($entryPath);				$ext = strtolower(substr($entryPath, -3));				$fileInfo = array(					'href'=>$entryHref,					'size'=>$size,					'ext'=>$ext,					//'width'=>'',					//'height'=>'',					//'dimensions'=>'',					//'bytesPerPixel'=>0,				);								// see if we can get extra info for images, but only for big images (> 20K), to speed up processing.				if ($size > 30000 && ($ext == 'png' || $ext == 'jpg' || $ext == 'gif'))				{					if (($imgSize = getimagesize($entryPath)) !== false)					{						$fileInfo['width'] = $imgSize[0];						$fileInfo['height'] = $imgSize[1];						$fileInfo['dimensions'] = $imgSize[0] . '&nbsp;x&nbsp;' . $imgSize[1];						$fileInfo['bytesPerPixel'] = ($imgSize[0] * $imgSize[1]) == 0 ? 0 : ($size / ($imgSize[0] * $imgSize[1]));					}				}								$result['files'][] = $fileInfo;				$result['size'] += $size;				$result['tree_size'] += $size;				$result['tree_files'] += 1;			}		}				closedir($d);		return $result;	}		function flattenTreeStructure($dir, &$flatDirs, &$flatFiles)	{		$flatDirs[] = array(			'href'=>$dir['href'],			'size'=>$dir['size'],			'dirs'=>count($dir['dirs']),			'files'=>count($dir['files']),			'tree_size'=>$dir['tree_size'],			'tree_files'=>$dir['tree_files'],		);				// add this dir's files		foreach ($dir['files'] as $file)			$flatFiles[] = $file;					// recurse into subdirectories		foreach ($dir['dirs'] as $subdir)			$this->flattenTreeStructure($subdir, $flatDirs, $flatFiles);	}}