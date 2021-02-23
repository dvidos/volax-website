<?php

/* 
	"a" (action) can be
	--------------------------------------
	- panel		returns the admin panel
	- edit			returns a form for editing the specific (or new) file
	- save			saves the contents of the edit form
	- fileman		returns a list of files in a given directory for delete and upload
	- upload		uploads a file in a directory
	- delete		deletes a file in a directory
*/
error_reporting(E_ALL);
ini_set('display_errors', '1');
try
{

	$a = get('a');
	admin_action($a);
}
catch (Exception $e)
{
	echo $e->getMessage();
}


function get($name, $defaultValue = '')
{
	if (!array_key_exists($name, $_REQUEST))
		return $defaultValue;
	$value = @$_REQUEST[$name]; 
	if (get_magic_quotes_gpc())
		$value = stripcslashes($value);
	return $value;
}

function tag($tag, $htmlOptions, $content = false, $closeTag = true)
{
	$html = '<' . $tag;
	foreach ($htmlOptions as $k=>$v)
		$html .= ' ' . $k . '="' . htmlspecialchars($v) . '"';
	if ($content === false)
	{
		$html .= $closeTag ? ' />' : '>';
	}
	else
	{
		$html .= '>' . $content;
		if ($closeTag)
			$html .= '</' . $tag . '>';
	}
	return $html;
}

function button($caption, $onClick)
{
	$htmlOptions = array(
		'type'=>'button',
		'value'=>$caption,
		'onClick'=>$onClick . 'return false;',
	);
	
	return tag('input', $htmlOptions);
}
function hidden($name, $value)
{
	$htmlOptions = array(
		'type'=>'hidden',
		'name'=>$name,
		'value'=>$value,
	);
	
	return tag('input', $htmlOptions);
}

function admin_action($a)
{
	if ($a == 'panel')
	{
		$sender = get('sender');
		
		// should send the panel
		admin_panel($sender);
	}
	else if ($a == 'edit')
	{
		$file = get('file');
		$html = get('html');
		$sender = get('sender');
	
		admin_edit_file($file, $html, $sender);
	}
	else if ($a == 'save')
	{
		$file = get('file');
		$html = get('html');
		$title = get('title');
		$content = get('content');
		$sender = get('sender');
		
		admin_save_file($file, $html, $title, $content, $sender);
	}
	else if ($a == 'fileman')
	{
		$dir = get('dir');
		$sender = get('sender');
		// should return a list of files, with links to download or delete (after confirmation) them 
		// and a form to upload a new file.
		
		admin_file_manager($dir, $sender);
	}
	else if ($a == 'upload')
	{
		$dir = get('dir');
		$sender = get('sender');
		// should upload a new file.
	
		admin_upload_file($dir, $sender);
	}
	else if ($a == 'delete')
	{
		$dir = get('dir');
		$file = get('file');
		$sender = get('sender');
		// should delete a file
	
		admin_delete_file($dir, $file, $sender);
	}
	else
	{
		die('Bad arguments! See source code');
	}
}

function admin_panel($sender)
{
	// find the file name of the sender.	
	// sender was "http://localhost/dvidos/volax_dv/theatre.php" or for index, "http://localhost/dvidos/volax_dv/".
	$slash = strrpos($sender, '/');
	$php = strpos($sender, '.php');
	if ($slash !== false && $php !== false)
		$me = substr($sender, $slash + 1, $php - $slash + 3);
	else
		$me = 'index.php';
	
	
	echo '<p>';
	echo '<b>Admin</b> ';
	echo button('edit', 'callAdminHandler({a:"edit",file:"'.$me.'",html:1}, function(){editor();});');
	echo button('new', 'callAdminHandler({a:"edit",file:"",html:1}, function(){editor();});');
	echo button('pages', 'callAdminHandler({a:"fileman",dir:"."});');
	echo button('images', 'callAdminHandler({a:"fileman",dir:"images"});');
	echo button('files', 'callAdminHandler({a:"fileman",dir:"files"});');
	echo button('style', 'callAdminHandler({a:"edit",file:"files/style.css"});');
	echo button('menu', 'callAdminHandler({a:"edit",file:"inc/menu.php"});');
	echo button('header', 'callAdminHandler({a:"edit",file:"inc/header.php"});');
	echo button('footer', 'callAdminHandler({a:"edit",file:"inc/footer.php"});');
	echo button('close', 'hide("admin-panel");show("admin-button");');
	echo '</p>';

	echo '<div id="admin-work-area" style="display:none;"></div>';
}

function admin_edit_file($file, $html, $sender)
{
	$title = '';
	$lines = file('../' . $file);
	for ($i = 0; $i < count($lines); $i++)
		$lines[$i] = rtrim($lines[$i]);
	
	if ($html)
	{
		while (count($lines) > 0 && $lines[0] == '')
			array_shift($lines);
		
		$title = array_shift($lines);
		if (substr($title, 0, 16) == '<?php $title = \'' && substr($title, -5) == '\'; ?>')
			$title = substr($title, 16, -5);
		
		while (count($lines) > 0 && $lines[0] == '')
			array_shift($lines);
		array_shift($lines); // include header
		
		
		while (count($lines) > 0 && $lines[count($lines) - 1] == '')
			array_pop($lines);
		array_pop($lines); // include footer
	}
	
	$content = implode("\r\n", $lines);
	
	// send the edit form with action 'save'
	echo tag('form', array('method'=>'POST', 'action'=>'inc/admin_handler.php'), false, false);
	echo hidden('a', 'save');
	
	if ($file)
	{
		echo hidden('file', $file);
	}
	else
	{
		echo 'Filename (xx.php)<br />';
		echo tag('input', array('name'=>'file', 'size'=>50, 'value'=>$file));
		echo '<br />';
	}
	
	echo hidden('html', $html);
	echo hidden('sender', $sender);
	if ($html)
	{
		echo 'Title<br />';
		echo tag('input', array('name'=>'title', 'size'=>50, 'value'=>$title));
		echo '<br />';
	}
	echo 'Content<br />';
	echo tag('textarea', array('name'=>'content', 'style'=>'width:100%;height:300px;', false, false));
	echo htmlspecialchars($content);
	echo '</textarea>';
	echo '&nbsp;<br />';
	echo tag('input', array('type'=>'submit', 'value'=>'Save'));
	echo '</form>';
}

function admin_save_file($file, $html, $title, $content, $sender)
{
	$text = $content;
	
	if ($html)
	{
		$text = 
			'<?php $title = \'' . $title . '\'; ?>' . "\r\n" .
			'<?php require(\'inc/header.php\'); ?>' . "\r\n" .
			"\r\n" . 
			$content . "\r\n" . 
			"\r\n" . 
			'<?php require(\'inc/footer.php\'); ?>' . "\r\n";
	}
	
	if (!file_put_contents('../' . $file, $text))
		die('Argh! Cannot save file "' . $file . '"');
	
	chmod('../' . $file, 0777);
	header('Location: ' . $sender);
}

function admin_file_manager($dir, $sender)
{
	$files = get_files('../' . $dir);
	$links = array();
	$names = array();
	$sizes = array();
	
	$total_count = 0;
	$total_size = 0;
	
	foreach ($files as $file)
	{
		$name = $file;
		if (substr($name, 0, 3) == '../')
			$name = substr($name, 3);
		if (substr($name, 0, 2) == './')
			$name = substr($name, 2);
		
		$links[] = $name;
		$name = basename($name);
		$names[] = $name;
		$size = filesize($file);
		$sizes[] = ceil($size / 1024);
		$total_size += $size;
	}

	echo '<p>Directory of ' . $dir . ', total ' . count($files) . ' files, ' . ceil($total_size / 1024) . ' kb' . '</p>';
	
	// three columns...
	$columns = 3;
	$width = round(100 / $columns);
	$each = ceil(count($files) / 3);
	echo '<table width="100%" border="0"><tr>';
	for ($col = 0; $col < $columns; $col++)
	{
		$colstart = $col * $each;
		echo '<td width="' . $width . '%" valign="top">';
		echo '<table width="100%" border="0">';
		for ($i = $colstart; $i < $colstart + $each; $i++)
		{
			if ($i >= count($files))
				break;
			
			$del_js = 'if (confirm("Delete ' . $names[$i] . ' ?")) { 
				callAdminHandler({a:"delete",dir:"' . $dir . '",file:"'.$names[$i].'",sender:"'.$sender.'"}); 
			} return false;';
			echo '<tr>';
			echo '<td><a href="'.htmlspecialchars($links[$i]).'">' . htmlspecialchars($names[$i]) . '</a></td>';
			echo '<td>' . $sizes[$i] . ' kb</td>';
			echo '<td><a href="#" onClick="'.htmlspecialchars($del_js).'">del</a></td>';
			echo '</tr>' . "\r\n";
		}
		echo '</table>';
		echo '</td>' . "\r\n";
	}
	echo '</tr></table>';

	echo '<p>';	
	echo tag('form', array(
		'method'=>'POST', 'action'=>'inc/admin_handler.php', 'enctype'=>'multipart/form-data'), 
		false, false);
	echo hidden('a', 'upload');
	echo hidden('dir', $dir);
	echo hidden('sender', $sender);
	
	echo 'Upload new file ';
	echo tag('input', array('type'=>'file', 'name'=>'file', 'size'=>40));
	echo tag('input', array('type'=>'submit', 'value'=>'Upload'));
	echo '</form>';
	echo '</p>';	
}

function get_files($dir)
{
	$files = array();
	$h = opendir($dir);
	if ($h)
	{
		while (($file = readdir($h)) != null)
		{
			if (is_dir($dir . '/' . $file))
				continue;
			$files[] = $dir . '/' . $file;
		}
		closedir($h);
	}
	sort($files);	
	return $files;
}
function admin_upload_file($dir, $sender)
{
	$file = @$_FILES['file'];
	if (!is_array($file))
	{
		header('Location: ' . $sender);
		return;
	}
	
	$realName = $file['name'];
	$tmpPath = $file['tmp_name'];
	$error = $file['error'];
	
	if (strlen($realName) > 0 && $error == 0)
	{
		$newPath = '../' . $dir . '/' . $realName;
		if (file_exists($newPath))
			die('File "'.$newPath.'" already exists');
		
		if (!move_uploaded_file($tmpPath, $newPath))
			die('Cannot move uploaded file "'.$tmpPath.'" to "'.$newPath.'"');
			
		chmod($newPath, 0777);
	}
	
	header('Location: ' . $sender);
}

function admin_delete_file($dir, $file, $sender)
{
	unlink('../'. $dir . '/' . $file);
	
	// we are called through ajax
	// act as a file manager, send back the new results
	// to show that the file was deleted.
	admin_file_manager($dir, $sender);
}

?>
