<?php

class ToolsController extends Controller
{
	function actionIndex()
	{
		$this->render('index', array(
			'xml_sitemap_filename' => $this->getSitemapFilename(),
			'xml_sitemap_url' => $this->getSitemapUrl(),
		));
	}
	
	function actionXmlSitemap()
	{
		$posts = Post::model()->findAll(array(
			'condition'=>'status = '.Post::STATUS_PUBLISHED,
			'order'=>'create_time DESC',
		));
		
		$categories = Category::model()->findAll(array(
			'condition'=>'status = '.Category::STATUS_PUBLISHED,
			'order'=>'parent_id, view_order',
		));
		
		$xml = $this->renderPartial('xmlSitemap', array(
			'posts'=>$posts,
			'categories'=>$categories,
		), true);
		
		$filename = $this->getSitemapFilename();
		$result = file_put_contents($filename, $xml);
		Yii::app()->user->setFlash('xmlSitemap','Δημιουργία αρχείου ' . $filename . ': ' . ($result === false ? 'Σφάλμα!' : 'Επιτυχής!'));
		$this->redirect(array('/admin/tools'));
	}
	
	/**
	 * filename to save the xml sitemap for google and other search engines.
	 */
	function getSitemapFilename() { return dirname(Yii::app()->basePath) . '/sitemap.xml'; }
	function getSitemapUrl() { return Yii::app()->baseUrl . '/sitemap.xml'; }
	
	
	
	
	function actionDiskUsage()
	{
		$baseHref = dirname(Yii::app()->basePath);
		$baseDirs = array(
			 // $baseHref . '/assets',
			 // $baseHref . '/dv',
			 // $baseHref . '/old-volax-tinos-gr',
			 // $baseHref . '/phpmyadmin',
			 // $baseHref . '/protected',
			 // $baseHref . '/themes',
			 $baseHref . '/uploads',
			 // $baseHref . '/yii',
		);
		
		// scan tree recursively with subfolders
		$tree = array();
		foreach ($baseDirs as $baseDir)
			$tree[] = $this->diskUsageOfDirectory($baseDir, strlen($baseHref));
		
		// produce a flat array of files and directories
		$dirs = arraY();
		$files = array();
		foreach ($tree as $treeBranch)
			$this->flattenTreeStructure($treeBranch, $dirs, $files);
		
		$this->render('diskUsage', array(
			'baseHref'=>$baseHref,
			'dirs'=>$dirs,
			'files'=>$files,
		));
	}
	
	function diskUsageOfDirectory($dirPath, $chopLen)
	{
		$result = array(
			'href'=>substr($dirPath, $chopLen),
			'size'=>0,
			'dirs'=>array(),
			'files'=>array(),
			'tree_size'=>0,
			'tree_files'=>0,
		);
		
		if (!is_dir($dirPath))
			return $result;
		
		$d = opendir($dirPath);
		while (($entry = readdir($d)) !== false)
		{
			$entryPath = $dirPath . '/' . $entry;
			$entryHref = substr($entryPath, $chopLen);
			
			if ($entry == '.' || $entry == '..' || substr($entry, 0, 1) == '.')
			{
				continue;
			}
			else if (is_dir($entryPath))
			{
				$subdir = $this->diskUsageOfDirectory($entryPath, $chopLen);
				$result['dirs'][] = $subdir;
				$result['tree_size'] += $subdir['tree_size'];
				$result['tree_files'] += $subdir['tree_files'];
			}
			else if (is_file($entryPath))
			{
				$size = filesize($entryPath);
				$ext = strtolower(substr($entryPath, -3));
				$fileInfo = array(
					'href'=>$entryHref,
					'size'=>$size,
					'ext'=>$ext,
					//'width'=>'',
					//'height'=>'',
					//'dimensions'=>'',
					//'bytesPerPixel'=>0,
				);
				
				// see if we can get extra info for images, but only for big images (> 20K), to speed up processing.
				if ($size > 30000 && ($ext == 'png' || $ext == 'jpg' || $ext == 'gif'))
				{
					if (($imgSize = getimagesize($entryPath)) !== false)
					{
						$fileInfo['width'] = $imgSize[0];
						$fileInfo['height'] = $imgSize[1];
						$fileInfo['dimensions'] = $imgSize[0] . '&nbsp;x&nbsp;' . $imgSize[1];
						$fileInfo['bytesPerPixel'] = ($imgSize[0] * $imgSize[1]) == 0 ? 0 : ($size / ($imgSize[0] * $imgSize[1]));
					}
				}
				
				$result['files'][] = $fileInfo;
				$result['size'] += $size;
				$result['tree_size'] += $size;
				$result['tree_files'] += 1;
			}
		}
		
		closedir($d);
		return $result;
	}
	
	function flattenTreeStructure($dir, &$flatDirs, &$flatFiles)
	{
		$flatDirs[] = array(
			'href'=>$dir['href'],
			'size'=>$dir['size'],
			'dirs'=>count($dir['dirs']),
			'files'=>count($dir['files']),
			'tree_size'=>$dir['tree_size'],
			'tree_files'=>$dir['tree_files'],
		);
		
		// add this dir's files
		foreach ($dir['files'] as $file)
			$flatFiles[] = $file;
			
		// recurse into subdirectories
		foreach ($dir['dirs'] as $subdir)
			$this->flattenTreeStructure($subdir, $flatDirs, $flatFiles);
	}
	
	function actionPhpInfo()
	{
		$this->render('phpinfo');
	}
	
	

	public function actionPostsLinks()
	{
		$ids = Post::model()->findAll(array(
			'select'=>'id',
			'order'=>'update_time DESC',
		));
		
		$data = array();
		foreach ($ids as $id)
		{
			$post = Post::model()->findByPk($id['id']);
			$links = $post->getContentLinks();
			if (empty($links))
				continue;
			$data[] = array(
				'id'=>$post->id,
				'title'=>$post->title,
				'links'=>$links,
			);
		}
		
		$this->render('postsLinks', array(
			'data'=>$data,
		));
	}

	public function actionPostsImages()
	{
		$ids = Post::model()->findAll(array(
			'select'=>'id',
			'order'=>'create_time DESC',
		));
		
		$data = array();
		foreach ($ids as $id)
		{
			$post = Post::model()->findByPk($id['id']);
			$images = $post->getContentImages();
			if (empty($images))
				continue;
			$data[] = array(
				'id'=>$post->id,
				'title'=>$post->title,
				'images'=>$images,
			);
		}
		
		$this->render('postsImages', array(
			'data'=>$data,
		));
	}
	
	
	public function actionPostsIntegrity()
	{
		$categories = Category::model()->findAll(array(
			'select'=>'id, parent_id, status, title',
		));
		
		$posts = Post::model()->findAll(array(
			'select'=>'id, category_id, author_id, status, title',
		));
		
		$authors = User::model()->findAll(array(
			'select'=>'id, username',
		));
		
		
		$categories_ids = array();
		$parent_categories_ids = array();
		foreach ($categories as $category)
		{
			$categories_ids[] = $category->id;
			$parent_categories_ids[$category->id] = $category->parent_id;
		}
		$leaves_categories_ids = array();
		$branches_categories_ids = array();
		foreach ($categories as $category)
		{
			// see if there is a child pointing to this category.
			$has_children = in_array($category->id, $parent_categories_ids);
			if ($has_children)
				$branches_categories_ids[] = $category->id;
			else
				$leaves_categories_ids[] = $category->id;
		}
		
		$author_ids = array();
		foreach ($authors as $author)
			$author_ids[] = $author->id;
			

		$errors = array();
		
		foreach ($posts as $post)
		{
			$messages = array();
			
			if ($post->category_id == 0)
				$messages[] = 'Δεν δηλώθηκε κατηγορία';
			
			else if (!in_array($post->category_id, $categories_ids))
				$messages[] = 'Η κατηγορία δεν βρέθηκε';
			
			else if (in_array($post->category_id, $branches_categories_ids))
				$messages[] = 'Η κατηγορία που ανήκει η ανάρτηση έχει υποκατηγορίες';;
			
			
			
			if ($post->author_id == 0)
				$messages[] = 'Δεν δηλώθηκε συγγραφέας';
			
			else if (!in_array($post->author_id, $author_ids))
				$messages[] = 'Ο συγγραφέας δεν βρέθηκε';
			
			
			if (count($messages) > 0)
			{
				$errors[] = array(
					'type'=>'Ανάρτηση',
					'status'=> ($post->status == 2 ? 'Published' : $post->status == 1 ? 'Draft' : 'Archived'),
					'title'=>$post->title,
					'url'=>array('/admin/posts/update', 'id'=>$post->id),
					'error'=>implode('<br>', $messages),
				);
			}
		}
		
		foreach ($categories as $category)
		{
			$messages = array();
			
			if ($category->parent_id == 0)
			{
				// nothing! allowed!
			}
			else if (!in_array($category->parent_id, $categories_ids))
				$messages[] = 'Η πατρική κατηγορία δεν βρέθηκε';
			
			
			if (count($messages) > 0)
			{
				$errors[] = array(
					'type'=>'Κατηγορία',
					'status'=> ($category->status == 2 ? 'Published' : 'Draft'),
					'title'=>$category->title,
					'url'=>array('/admin/categories/update', 'id'=>$category->id),
					'error'=>implode('<br>', $messages),
				);
			}
		}
		
		$this->render('postsIntegrity', array(
			'errors'=>$errors,
		));
	}
	
	public function actionPostsLanguages()
	{
		$errors = array();
		$greek = 'ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩαβγδεζηθικλμνξοπρστυφχψωςάέύίόήώΆΈΎΊΌΉΏΐΰ';
		$regexp = '/([a-zA-Z]['.$greek.']|['.$greek.'][a-zA-Z])/uS';
		
		$posts = Post::model()->findAll(array(
			'select'=>'id, status, title',
		));
		foreach ($posts as $post)
		{
			if (preg_match($regexp, $post->title))
				$errors[] = array('type'=>'Post', 'title'=>$post->title, 'url'=>array('/admin/posts/update', 'id'=>$post->id));
		}
		$posts = null;
		
			
		$categories = Category::model()->findAll(array(
			'select'=>'id, status, title',
		));
		foreach ($categories as $category)
		{
			if (preg_match($regexp, $category->title))
				$errors[] = array('type'=>'Category', 'title'=>$category->title, 'url'=>array('/admin/categories/update', 'id'=>$category->id));
		}
		$categories = null;


		
		$tags = Tag::model()->findAll(array(
			'select'=>'id, name',
		));
		foreach ($tags as $tag)
		{
			if (preg_match($regexp, $tag->name))
				$errors[] = array('type'=>'Tag', 'title'=>$tag->name, 'url'=>array('/admin/posts', 'Post[tags]'=>$tag->name));
		}
		$tags = null;


		
		$this->render('postsLanguages', array(
			'errors'=>$errors,
		));
	}
	
	
	public function actionSearchPostsContent()
	{
		$key = array_key_exists('key', $_REQUEST) ? $_REQUEST['key'] : '';
		$regex = array_key_exists('regex', $_REQUEST) ? $_REQUEST['regex'] : '';
		
		$results = array();
		if (!empty($key))
			$results = Post::model()->searchPostsForContent($key, $regex);
			
		$this->render('searchPostsContent', array(
			'key'=>$key,
			'regex'=>$regex,
			'results'=>$results,
		));
	}
	
	public function actionOrphanFiles()
	{
		$posts = Post::model()->findAll(array(
			'select'=>'id, title, content',
		));
		
		$files_used_by_posts = array();
		foreach ($posts as $post)
		{
			$urls = array_merge($post->getContentImages(), $post->getContentLinks());
			foreach ($urls as $url)
			{
				$key = Post::makeRelativeUrl($url);
				$key = urldecode($key);
				if (empty($key))
					continue;
				
				if (!array_key_exists($key, $files_used_by_posts))
					$files_used_by_posts[$key] = array();
				
				$files_used_by_posts[$key][] = $post->id;
			}
		}
		
		
		// find all files under /uploads
		$existing_files = array();
		$cutOff = strlen(dirname(Yii::app()->basePath));
		$this->orphanFiles_getFiles(dirname(Yii::app()->basePath) . '/uploads', $cutOff, $existing_files);
		
		
		// sort used files into three main groups
		$external_links = array();
		$used_files = array();
		$missing_files = array();
		foreach ($files_used_by_posts as $fn => $post_ids)
		{
			if (substr($fn, 0, 4) == 'http')
			{
				if (!isset($external_links[$fn]))
					$external_links[$fn] = array();
				$external_links[$fn] = array_merge($external_links[$fn], $post_ids);
			}
			else if (in_array($fn, $existing_files))
			{
				if (!isset($used_files[$fn]))
					$used_files[$fn] = array();
				$used_files[$fn] = array_merge($used_files[$fn], $post_ids);
			}
			else
			{
				if (!isset($missing_files[$fn]))
					$missing_files[$fn] = array();
				$missing_files[$fn] = array_merge($missing_files[$fn], $post_ids);
			}
		}
		
		// find files base path
		$bp = dirname(Yii::app()->basePath);
		
		// find used files, both from posts and old-volax-tinos-gr
		$non_orphan_files = array_keys($files_used_by_posts);
		$orphan_files = array();
		foreach ($existing_files as $ef)
		{
			if (!in_array($ef, $non_orphan_files))
				$orphan_files[] = $ef;
		}
		
		$existing_files_size = 0;
		$used_files_size = 0;
		$orphan_files_size = 0;
		foreach ($existing_files as $fn)
			$existing_files_size += filesize($bp . $fn);
		foreach ($used_files as $fn=>$pids)
			$used_files_size += filesize($bp . $fn);
		foreach ($orphan_files as $fn)
			$orphan_files_size += filesize($bp . $fn);
		
		sort($existing_files, SORT_STRING);
		ksort($external_links, SORT_STRING);
		ksort($used_files, SORT_STRING);
		ksort($missing_files, SORT_STRING);
		sort($orphan_files, SORT_STRING);
		
		$this->render('orphanFiles', array(
			'existing_files'=>$existing_files,
			'external_links'=>$external_links,
			'used_files'=>$used_files,
			'missing_files'=>$missing_files,
			'orphan_files'=>$orphan_files,
			
			'existing_files_size'=>$existing_files_size,
			'used_files_size'=>$used_files_size,
			'orphan_files_size'=>$orphan_files_size,
		));
	}
	
	function orphanFiles_getFiles($dir, $cutOff, &$files)
	{
		$d = opendir($dir);
		while (($f = readdir($d)) !== false)
		{
			if ($f == '.' || $f == '..' || $f == '.tmb')
				continue;
			
			$path = $dir . '/' . $f;
			if (is_file($path))
			{
				$files[] = substr($path, $cutOff);
			}
			else if (is_dir($path))
			{
				$this->orphanFiles_getFiles($path, $cutOff, $files);
			}
		}
		closedir($d);
	}
	
	function actionPopulateWordpress(
		$posts = '',
		$categories = '',
		$tags = '',
		$pages = '',
		$media = ''
	) {
		Yii::app()->wordpressPopulator->run(
			$posts,
			$categories,
			$tags,
			$pages,
			$media
		);
		$log = Yii::app()->wordpressPopulator->getLog();
		
		$this->render('populateWordpress', array(
			'desired_posts' => $posts,
			'desired_categories' => $categories,
			'desired_tags' => $tags,
			'desired_pages' => $pages,
			'desired_media' => $media,
			'log' => $log,
		));
	}
}
