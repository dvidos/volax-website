<h1>Μέγεθος αρχείων</h1>
<style>
	tr.low { color: #ccc; }
</style>
<?php 
	function abbr($size) { return ($size < 1024) ? $size : (($size < 1048576) ? ((int)(round($size / 1024)) . ' KB') : ((int)(round($size / 1048576)) . ' MB')); }

	$totals = array(
		'files'=>array('caption'=>'Αρχεία', 'count'=>0, 'size'=>0),
		'images'=>array('caption'=>'Εικόνες', 'count'=>0, 'size'=>0),
		'wide_images'=>array('caption'=>'Φαρδιές εικόνες', 'count'=>0, 'size'=>0),
		'heavy_images'=>array('caption'=>'Βαριές εικόνες', 'count'=>0, 'size'=>0),
		'mp3s'=>array('caption'=>'mp3', 'count'=>0, 'size'=>0),
		'pdfs'=>array('caption'=>'pdf', 'count'=>0, 'size'=>0),
		'others'=>array('caption'=>'Λοιπά αρχεία', 'count'=>0, 'size'=>0),
	);
	foreach ($files as $file)
	{
		$s = $file['size'];
		$e = $file['ext'];
		
		$totals['files']['count'] += 1;
		$totals['files']['size'] += $s;
		
		if ($e == 'jpg' || $e == 'png' || $e == 'gif')
		{
			$totals['images']['count'] += 1;
			$totals['images']['size'] += $s;
			
			if (@$file['width'] > 1024)
			{
				$totals['wide_images']['count'] += 1;
				$totals['wide_images']['size'] += $s;
			}
			else if (@$file['bytesPerPixel'] > 1)
			{
				$totals['heavy_images']['count'] += 1;
				$totals['heavy_images']['size'] += $s;
			}
		}
		else if ($e == 'pdf')
		{
			$totals['pdfs']['count'] += 1;
			$totals['pdfs']['size'] += $s;
		}
		elseif ($e == 'mp3')
		{
			$totals['mp3s']['count'] += 1;
			$totals['mp3s']['size'] += $s;
		}
		else
		{
			$totals['others']['count'] += 1;
			$totals['others']['size'] += $s;
		}
	}
?>
<h2>Σύνοψη</h2>
<table class="bordered">
<thead><tr><td>Είδος</td><td>Πλήθος</td><td>Μέγεθος</td><td>Μ.Ο. μεγέθους/αρχείο</td></tr></thead>
<?php
	foreach ($totals as $key => $total)
	{
		$mean = $total['count'] == 0 ? '' : abbr($total['size'] / $total['count']);
		echo '<tr><td>'.$total['caption'].'</td><td>'.$total['count'].'</td><td>'.abbr($total['size']).'</td><td>'.$mean.'</td></tr>';
	}
?>
</table>



<h2><a onClick="$('#fld-t').slideToggle('slow');">Φάκελοι</a></h2>
<div id="fld-t" style="display:none;">
	<table class="bordered">
	<thead><tr><td>dir</td><td>files</td><td>size</td><td>total files</td><td>total size</td></tr></thead>
	<?php
		foreach ($dirs as $dir)
		{
			$className = ($dir['tree_size'] == 0) ? 'low' : '';
			echo '<tr class="'.$className.'">';
			echo '<td>' . $dir['href'] . '</td><td>' . $dir['files'] . '</td><td>' . abbr($dir['size']) . '</td>';
			echo '<td>' . $dir['tree_files'] . '</td><td>' . abbr($dir['tree_size']) . '</td>';
			echo '</tr>';
		}
	?>
	</table>
	<p>&nbsp;</p>
</div>


<?php
	function presentFilesHeader($id, $title)
	{
		echo CHtml::tag('h2', array(), CHtml::link($title, '#', array('onClick'=>'$("#'.$id.'").slideToggle(); return false;')));
		echo '<div id="'.$id.'" style="display:none;">';
		echo '<table class="bordered">';
		echo '<thead><tr><td>#</td><td>file</td><td>size</td><td>dims</td><td>bytes per px</td></tr></thead>';
	}
	function presentFile($file, $i)
	{
		echo '<tr>';
		echo '<td>' . ($i++) . '</td>';
		echo '<td>' . CHtml::link($file['href'], Yii::app()->baseUrl . $file['href'], array('target'=>'_blank')) . '</td>';
		echo '<td>' . abbr($file['size']) . '</td>';
		
		$w = @$file['width'];
		$style = ($w > 1024) ? 'color:red;' : '';
		echo '<td style="'.$style.'">' . @$file['dimensions'] . '</td>';
		
		$bpp = @$file['bytesPerPixel'];
		$style = ($bpp > 1) ? 'color:red;' : '';
		echo '<td style="'.$style.'">' . (empty($bpp) ? '' : round($bpp, 3)) . '</td>';
		echo '</tr>';
	}
	function presentFilesFooter()
	{
		echo '</table>';
		echo '<p>&nbsp;</p>';
		echo '</div>';
	}
	
	function compare_href($a, $b) { return strcmp($a['href'], $b['href']); }
	function compare_size($a, $b) { return $b['size'] - $a['size']; }
	function compare_width($a, $b) { return @$b['width'] - @$a['width']; }
	function compare_bytesPerPixel($a, $b) { return @$b['bytesPerPixel'] - @$a['bytesPerPixel']; }
	//usort($flat, 'compare_size');
	
	function pass_width($a) { return (@$a['width'] > 1024); }
	function pass_bytesPerPixel($a) { return (@$a['bytesPerPixel'] > 1); }
	function pass_mp3($a) { return ($a['ext'] == 'mp3'); }
	function pass_pdf($a) { return ($a['ext'] == 'pdf'); }
	function pass_other($a) { return ($a['ext'] != 'pdf' && $a['ext'] != 'mp3' && $a['ext'] != 'png' && $a['ext'] != 'jpg' && $a['ext'] != 'gif'); }
	
	function presentFiles($files, $id, $title, $sort_func = null, $pass_func = null)
	{
		if (!empty($sort_func))
			usort($files, $sort_func);
		
		presentFilesHeader($id, $title);
		$i = 1;
		foreach ($files as $file)
		{
			if (!empty($pass_func))
			{
				if (!call_user_func($pass_func, $file))
					continue;
					//break;
			}
			
			presentFile($file, $i++);
		}
		presentFilesFooter();
	}
	
	
	presentFiles($files, 'wim-t', 'Φαρδιές εικόνες (μεγαλύτερες από 1024px)', 'compare_width', 'pass_width');
	presentFiles($files, 'him-t', 'Βαριές εικόνες (περισσότερο από 1 byte ανά pixel)', 'compare_bytesPerPixel', 'pass_bytesPerPixel');
	presentFiles($files, 'mp3-t', 'mp3', 'compare_size', 'pass_mp3');
	presentFiles($files, 'pdf-t', 'pdf', 'compare_size', 'pass_pdf');
	presentFiles($files, 'oth-t', 'Λοιπά', 'compare_size', 'pass_other');
	presentFiles($files, 'all-t', 'Ολα τα αρχεία, ανά μέγεθος', 'compare_size');
	//presentFiles($files, 'Ολα τα αρχεία, ανά όνομα', 'compare_href');
	
	
?>
<h2>Αρχεία για γρήγορη επεξεργασία (copy/paste)</h2>
<textarea cols="70" rows="6" scroll="both"><?php foreach ($files as $file) echo $file['href'] . "\r\n"; ?></textarea>


