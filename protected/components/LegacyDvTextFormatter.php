<?php

class LegacyDvTextFormatter extends CApplicationComponent
{
	// http://volax.gr/v4/index.php?r=images/show&src=uploads%2Fnikaliamoutos%2F2014%2FDimitrisL.jpg&width=240
	var $image_thumb_creator = 'index.php?p=images/show&src={file}&width={width}&height={height}';
	
	function format($text, $assets_folder = '')
	{
		$text = $this->dvt_replace_tags_in_text($text, $assets_folder);
		$text = $this->dvt_format_blocks($text);
		
		return $text;
	}




	/*
	echo '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-7" />';
	echo '<style>body { font-family: Verdana,Helvetica,sans-serif; font-size: 8.5pt; }</style>';

	$plaintext = @$_REQUEST['plaintext'];
	if ($plaintext != '')
	{
		echo '<h2>Results</h2>';
		echo '<hr>';
		echo dvt_before_page_display($plaintext);
	}

	echo '<hr>';
	echo '<h2>PlainText</h2>';
	echo '<form method="post">';
	echo 'Κείμενο<br />';
	echo '<textarea cols="80" rows="20" name="plaintext">' . htmlspecialchars($plaintext) . '</textarea><br/>';
	echo '<input type="submit" value="Process" />';
	echo '</form>';
	*/

	function dvt_strip_tags($text)
	{
		$text = strip_tags($text, '<b><i><strong><em>');
		
		return $text;
	}

	function dvt_replace_tags_in_text($text, $assets_folder)
	{
		$text_pos = 0;
		$text_len = strlen($text);
		$result = '';
		
		$start_delimiter = '[[';
		$end_delimiter = ']]';
		$delimiters_len = 2;
		
		while ($text_pos < $text_len)
		{
			// find start and end of tag
			$tag_start = strpos($text, $start_delimiter, $text_pos);
			if ($tag_start === false)
			{
				$result .= substr($text, $text_pos);
				break;
			}
			
			$tag_end = strpos($text, $end_delimiter, $tag_start);
			if ($tag_end === false)
			{
				$result .= substr($text, $text_pos);
				break;
			}
			
			
			// copy up to the start, and extract tag.
			$result .= substr($text, $text_pos, $tag_start - $text_pos);
			$tag = substr($text, $tag_start + $delimiters_len, $tag_end - $tag_start - $delimiters_len);
			
			
			if (substr($tag, 0, 6) == 'image:')
			{
				$tag = substr($tag, 6);
				$result .= $this->dvt_handle_image_tag($tag, $assets_folder);
			}
			else if (substr($tag, 0, 8) == 'gallery:')
			{
				$tag = substr($tag, 8);
				$result .= $this->dvt_handle_gallery_tag($tag, $assets_folder);
			}
			else if (substr($tag, 0, 6) == 'audio:')
			{
				$tag = substr($tag, 6);
				$result .= $this->dvt_handle_audio_tag($tag, $assets_folder);
			}
			else if (substr($tag, 0, 9) == 'download:')
			{
				$tag = substr($tag, 9);
				$result .= $this->dvt_handle_download_tag($tag, $assets_folder);
			}
			else if (substr($tag, 0, 8) == 'youtube:')
			{
				$tag = substr($tag, 8);
				$result .= $this->dvt_handle_youtube_tag($tag, $assets_folder);
			}
			else
			{
				$result .= $this->dvt_handle_link_tag($tag);
			}
			
			
			// move on.
			$text_pos = $tag_end + $delimiters_len;
		}
		
		return $result;
	}


	function dvt_handle_image_tag($tag, $assets_folder)
	{
		// format: [[image:{name}|{type}|{location}|{size}|{upright}|{link}|{text}]]
		// see http://en.wikipedia.org/wiki/Wikipedia:Extended_image_syntax
			
		$parts = explode('|', $tag);
		
		if (count($parts) == 0)
			return '';
		
		// the first is always the image
		$file = $assets_folder . array_shift($parts);
		
		if (!file_exists($file))
			return '(image file "'.$file.'" not found)';
		
		
		// default values
		$type = '';
		$location = 'right';
		$size = '';
		$width = 0; 
		$height = 0;
		$link = '';       // default will be the original image, if a different size is displayed.
		$text = '';
		
		
		for ($i = 0; $i < count($parts); $i++)
		{
			$part = $parts[$i];
			$recognized = true;
			
			if (in_array($part, array('thumb', 'thumbnail', 'frame', 'border')))
			{
				$type = $part;
			}
			else if (in_array($part, array('left', 'right', 'center', 'none')))
			{
				$location = $part;
			}
			else if (substr($part, -2) == 'px')
			{
				$part = substr($part, 0, -2);
				if (($pos = strpos($part, 'x')) === false)
				{
					$width = $part;
					$height = 0;
				}
				else
				{
					$width = substr($part, 0, $pos);
					$height = substr($part, $pos + 1);
				}
			}
			else if (substr($part, 0, 5) == 'link=')
			{
				$link = substr($part, 5);
			}
			else
			{
				// we could not recognize the option.
				$recognized = false;
			}
			
			if ($recognized)
			{
				array_splice($parts, $i, 1);
				$i--;
			}
		}
		
		// the last non-recognized element is the text.
		if (count($parts) > 0)
			$text = array_pop($parts);
		
		
		// find dimensions
		if (!file_exists($file))
			return '(image not found)';
		
		$img_size = getimagesize($file);
		if (!is_array($img_size))
			return '(image not supported)';
		
		$original_width = $img_size[0];
		$original_height = $img_size[1];
		
		if ($width == 0)
		{
			$width = ($type == 'thumb' || $type == 'thumbnail') ? 180 : $original_width;
		}
		
		if ($height == 0)
		{
			if ($width == $original_width)
				$height = $original_height;
			else
				$height = round($width * $original_height / $original_width);
		}
		
		
		
		// format container and alignment
		$prefix = '';
		$suffix = '';
		
		if ($location == 'center')
		{
			$style = 'text-align: center; margin: 1em 0;';
			
			$prefix = '<div class="image" style="text-align: center; margin: 1em 0;">';
			$suffix = '</div>';
		}
		else if ($location == 'left' || $location == 'right')
		{
			$margin = ($location == 'left') ? '0 2.5em 1em 0' : '0 0 1em 1em';
			$style = 'width: '.$width.'px; float: '.$location.'; text-align: center; margin: '.$margin . ';';
			
			$prefix = '<div class="image" style="'.$style.'">';
			$suffix = '</div>';
		}
		else
		{
			// nothing. display inline
		}
		
		
		// prepare link
		$link_prefix = '';
		$link_suffix = '';
		
		if ($link == '')
		{
			if ($width != $original_width)
			{
				$link_prefix = '<a href="' . $file . '">';
				$link_suffix = '</a>';
			}
		}
		else
		{
			$link_prefix = '<a href="' . $link . '">';
			$link_suffix = '</a>';
		}
		
		
		// prepare options
		$display_caption = $location != 'none' && $text != '';
		$display_shadow = $location != 'none';
		
		
		// prepare image source
		if ($width == $original_width && $height == $original_height)
		{
			$image_src = $file;
		}
		else
		{
			global $image_thumb_creator;
			
			$image_src = $image_thumb_creator;
			$image_src = str_replace('{file}', $file, $image_src);
			$image_src = str_replace('{width}', $width, $image_src);
			$image_src = str_replace('{height}', $height, $image_src);
		}
		
		// prepare the image tag
		$image_tag = '';
		$image_tag .= ($display_shadow) ? '<span class="image">' : '';
		$image_tag .= "<img src=\"$image_src\" width=\"$width\" height=\"$height\" alt=\"$text\" border=\"0\"";
		$image_tag .= ($display_caption) ? '' : " title=\"$text\"";
		$image_tag .= ' />';
		$image_tag .= ($display_shadow) ? '</span>' : '';
		
		
		
		// generate html
		$result = '';
		$result .= $prefix;
		$result .= $link_prefix;
		$result .= $image_tag;
		$result .= $link_suffix;
		$result .= ($display_caption) ? ('<br /><span class="image-caption">' . $text . '</span>') : '';
		$result .= $suffix;
		
		return $result;
	}


	function dvt_handle_audio_tag($tag, $assets_folder)
	{
		// format: [[audio: path-or-url-to-mp3-file ]]
		// audio player by 1pixelout: http://www.1pixelout.net/code/audio-player-wordpress-plugin/
		
		static $player_counter = 0;
		
		$file = $assets_folder . $tag;
		$player_js  = 'cms/audio/audio-player.js';
		$player_swf = 'cms/audio/player.swf';
		
		$result = '<script language="JavaScript" src="'.$player_js.'"></script>'.
			'<object type="application/x-shockwave-flash" data="'.$player_swf.'" id="audioplayer'.$player_counter.'" height="24" width="290">'.
			'<param name="movie" value="'.$player_swf.'">'.
			'<param name="FlashVars" value="playerID='.$player_counter.'&amp;soundFile='.$file.'">'.
			'<param name="quality" value="high">'.
			'<param name="menu" value="false">'.
			'<param name="wmode" value="transparent">'.
		'</object>';

		$result .= '<br /><a href="'.$file.'" />'.$tag.'</a>, '.filesize($file) . ' bytes';
		
		$player_counter++;
		
		return $result;
	}

	function dvt_handle_gallery_tag($tag, $assets_folder)
	{
		// thumb size could end in "px" as in image, could be width only, could be both using "x"
		// default width would be 100 px, add little padding and fit in page as many in a row as they fit.
			
		return '';
	}


	function dvt_handle_download_tag($tag, $assets_folder)
	{
		// format: [[image:{name}|{type}|{location}|{size}|{upright}|{link}|{text}]]
		// see http://en.wikipedia.org/wiki/Wikipedia:Extended_image_syntax
		
		$parts = explode('|', $tag);
		
		if (count($parts) == 0)
			return '';
		
		// the first is always the image
		$filename = array_shift($parts);
		
		$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		$basename = substr($filename, 0, -1 * (strlen($extension) + 1));
		
		if (count($parts) > 0)
			$description = array_pop($parts) . '<br />';
		else
			$description = '';
		
		$filepath = $assets_folder . $filename;
		if (!file_exists($filepath))
			return '(file "' . $filename . '" not found)';
		
		$filesize = filesize($filepath) . ' bytes';
		$filedate = date('d-m-y', filemtime($filepath));
		
		if ($extension == 'pdf')
			$icon_name = 'ico_pdf.gif';
		else if ($extension == 'mp3')
			$icon_name = 'ico_audio.gif';
		else if ($extension == 'zip')
			$icon_name = 'ico_zip.gif';
		else
			$icon_name = 'ico_other.gif';
		
		
		$result = '<div class="download-box">'.
			'<div style="float:left; width: 50px;"><a href="' . $filepath . '"><img src="cms/icons/' . $icon_name . '" border="0" /></a></div>'.
			'<div style="float:left;">'.
				'<a href="' . $filepath . '">' . $basename . '</a><br />'.
				$description.
				$extension . ', ' . $filesize . ', ' . $filedate .
			'</div>'.
			'<div style="clear:both;"></div>'.
		'</div>';
			
		return $result;
	}

	function dvt_handle_youtube_tag($tag, $assets_folder)
	{
		// format: [[youtube: video-id ]]
		
		$video = $tag;
		
		$result = '<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/'.$video.'&hl=en&fs=1&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$video.'&hl=en&fs=1&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>';
		
		return $result;
	}



	function dvt_handle_link_tag($tag)
	{
		// format: [[ local-or-global-url | optional-text-for-link | optional-title-for-link ]]
		
		$parts = explode('|', $tag);
		if (count($parts) == 1)
		{
			$href = $parts[0];
			$text = $parts[0];
			$title = '';
		}
		else if (count($parts) == 2)
		{
			$href = $parts[0];
			$text = $parts[1];
			$title = '';
		}
		else if (count($parts) >= 3)
		{
			$href = $parts[0];
			$text = $parts[1];
			$title = $parts[2];
		}
		
		// what about our links? as in "posts/Istoria_tou_xwriou"???
		$result = '<a href="' . $href . '"';
		$result .= ($title == '') ? '' : (' title="' . htmlspecialchars($title) . '"');
		$result .= '>';
		$result .= htmlspecialchars($text);
		$result .= '</a>';
		
		return $result;
	}







	function dvt_format_blocks($text)
	{
		if ($text == '')
			return $text;
		
		// echo '<hr>Initial text<br /><pre>' . htmlspecialchars($text) . '</pre>';
		
		
		// normalize paragraphs, generate blocks
		$text = trim($text);
		$text = str_replace(array("\r\n", "\r"), "\n", $text); // convert all to \n
		$text = preg_replace("/\n\s+\n/", "\n\n", $text);       // trim line endings
		$text = preg_replace("/\n\n+/", "\n\n", $text);         // max consecutive newlines is 2
		$blocks = explode("\n\n", $text);
		$text = '';
		
		
		foreach ($blocks as $block)
		{
			// h1. - h6. headings.
			// bq.       blockquote
			// p.        paragraph
			// <..>      not wrapped. example: divs.
			// ..        anything else is a paragraph.
			
			$skip_br = false;

			if (preg_match('/^h([1-6])\.\s+(.+)$/is', $block, $matches))
			{
				// heading 1 - 6
				$block = '<h' . $matches[1] . '>' . $matches[2] . '</h' . $matches[1] . '>';
			}
			else if (substr($block, 0, 4) == 'bq. ')
			{
				// blockquote
				$block = '<blockquote>' . substr($block, 4) . '</blockquote>';
			}
			else if (substr($block, 0, 5) == 'pre. ')
			{
				// preformatted
				$block = '<pre>' . substr($block, 5) . '</pre>';
				$skip_br = true;
			}
			else if (substr($block, 0, 3) == 'p. ')
			{
				// paragraph
				$block = '<p>' . substr($block, 3) . '</p>';
			}
			else if (substr($block, 0, 4) == 'p=. ')
			{
				// paragraph, align center
				$block = '<p style="text-align: center;">' . substr($block, 4) . '</p>';
			}
			else if (substr($block, 0, 4) == 'p>. ')
			{
				// paragraph, align center
				$block = '<p style="text-align: right;">' . substr($block, 4) . '</p>';
			}
			else if (!preg_match('/^\<(.+)\>$/is', $block))
			{
				// only if there are no tags in the start and end of the block.
				// no formatting. make this a paragraph.
				$block = '<p>' . $block . '</p>';
			}
			
			// insert line breaks
			if (!$skip_br)
				$block = str_replace("\n", "<br />\n", $block);
			
			// add it.
			$text .= $block . "\r\n\r\n";
		}

		
		//echo '<hr>Final text<br /><pre>' . htmlspecialchars($text) . '</pre><hr>';
		return $text;
	}



	function dvt_get_rules_html()
	{
		return '
	<h3>Formatting Rules</h3>
	<ol>

	<li><b>Blocks</b>
		<p>Κάθε κομμάτι που χωρίζεται με μία κενή γραμμή (δύο "Enter") θεωρείται block. Αυτό μπορεί να ξεκινάει με διάφορες μικρές εντολές και ένα απαραίτητο space οι οποίες είναι:</p>
		<ul>
			<li><b><tt>p. </tt></b>: Παράγραφος. πχ. "<tt>p. Μια ωραία μέρα πήγαινα...</tt>"</li>
			<li><b><tt>p=. </tt></b>: Παράγραφος, στοιχισμένη στο κέντρο. Προς αποφυγήν.</li>
			<li><b><tt>p&gt;. </tt></b>: Παράγραφος, στοιχισμένη δεξιά. Χρήσιμη για υπογραφές. πχ. "<tt>p>. Σας ασπάζομαι, Φαίδων</tt>"</li>
			<li><b><tt>hx. </tt></b>: Header. Το x μπορεί να είναι από 1 ως 6. Χρήσιμο για να χωρίζει την σελίδα σε ενότητες. πχ. "<tt>h3. Τα χρόνια του πολέμου</tt>"</li>
			<li><b><tt>bq. </tt></b>: BlockQuote. Οταν παραθέτουμε κείμενο τρίτων. πχ. "</tt>bq. Την ημέρα εκείνη μαζεύτηκαν οι μαθητές για μπύρες...</tt>"</li>
			<li><b><tt>pre. </tt></b>: Preformatted. Εμφανίζεται το κείμενο ως γραφομηχανή. Χρήσιμο για μικρούς πίνακες.</li>
		</ul>
		<p>Αν δεν δώσουμε κάποιο χαρακτηριστικό, τότε το block θεωρείται παράγραφος, εκτός και αν αρχίζει και τελειώνει με html tags.</p>
	</li>


	<li><b>Links</b>
	<p>
		Τα διάφορα Links ορίζονται με [[ και τελειώνουν με ]].<br />
		Η μορφή τους μπορεί να είναι: [[ link | text | title ]] (χωρίς τα spaces)<br />
	</p>
	<p>
		<b>link</b>: Είναι το URL που βάζουμε. Αν δεν ξεκινάει με http://, θεωρείται τοπικό αρχείο. Πρέπει να υπάρχει. 
	</p>
	<p>
		<b>text</b>: Προεραιτικό. Είναι το κείμενο που θα εμφανίζεται. 
		Αν δεν το ορίσουμε, εμφανίζεται το link.
	</p>
	<p>
		<b>title</b>: Προεραιτικότερο. Είναι ένας επιπλέον τίτλος που εμφανίζεται αν αφήσουμε το ποντίκι μας 
		πάνω από το link. Αν δεν δοθεί δεν εμφανίζεται καθόλου.
	</p>
	</li>

	<li><b>Εικόνες</b>
	<p>
		Οι εικόνες ορίζονται με [[image:  και τελειώνουν με ]].<br />
		Οι παράμετροι είναι παρόμοιοι με την wikipedia: [[image: file | type | location | size | link | text ]] (χωρίς τα spaces)<br />
	</p>
	<p>
		<b>file</b>: Είναι το όνομα του αρχείου, ενδεχωμένως με το όνομα του φακέλου. 
		Πχ. uploads/village.jpg. 
		Πρέπει να είναι πρώτο και πρέπει να υπάρχει.
	</p>
	<p>
		<b>type</b>: thumb,border,frame ή τίποτα. 
		Προς το παρόν μόνο το thumb υποστηρίζεται, όπου έχει default πλάτος 180 pixels.
	</p>
	<p>
		<b>location</b>: Μπορεί να είναι: left, center, right, none. 
		Τα <b>left</b> και <b>right</b> προκαλούν το κείμενο να αναδιπλώνεται δίπλα στην εικόνα. 
		Το <b>center</b> βάζει την εικόνα στο κέντρο χωρίς αναδίπλωση κειμένου 
		και το <b>none</b> βάζει την εικόνα να είναι μέρος του κειμένου. 
		To default είναι right.
	</p>
	<p>
		<b>size</b>: Αριθμός και το επίθεμα "<b>px</b>". Δηλώνει το πλάτος, όπου το ύψος βρίσκεται τηρώντας τις αναλογίες της εικόνας. 
		Μπορείτε να ορίσετε πλάτος και ύψος χωρίζοντάς τα με το αγγλικό "<b>x</b>", παράδειγμα: 100x200px.
		Αν δεν οριστεί παίρνει το πλήρες πλάτος της εικόνας, εκτός και αν το type είναι thumb, οπότε παίρνει το default 180.
	</p>
	<p>
		<b>link</b>: Θα πρέπει να ξεκινά με την λέξη "<b>link=</b>". Ορίζει το πού θα πάμε αν κάνουμε κλικ στην εικόνα.
		Αν δεν δίνεται, τότε αν κάνουμε κλικ θα οδηγούμαστε στην πλήρη εικόνα. 
		Εκτός όμως αν εμφανίζεται το πλήρες μέγεθος, οπότε δεν θα υποστηρίζεται κλικ.
	</p>
	<p>
		<b>text</b>: Η λεζάντα της εικόνας. Μπορεί να είναι οτιδήποτε. 
		Εμφανίζεται κάτω από την εικόνα, εκτός και αν το location είναι none, 
		οπότε εμφανίζεται όταν αφήνουμε το ποντίκι πάνω στην εικόνα.
	</p>
	</li>

	<li><b>Αρχεία ήχου</b>
	<p>
		Τα αρχεία ήχου ορίζονται με [[audio:  και τελειώνουν με ]].<br />
		Η μόνη παράμετρο είναι το όνομα του mp3 αρχείου.<br /> 
		Στην θέση της εντολής εμφανίζεται ένας web audio player. Τον πήραμε από <a href="http://www.1pixelout.net/code/audio-player-wordpress-plugin/">εδώ</a>.
	</p>
	</li>

	<li><b>YouTube</b>
	<p>
		Τα video του youtube γίνονται embed εύκολα. Στο URL της σελίδας θα βρείτε την μορφή: http://www.youtube.com/watch?v=xxxxxxxxxxx.
		Παίρνουμε αυτό το xxxxxx και δίνουμε [[ youtube: xxxxxxxxx ]]. Τέλος! Αυτό είναι. Δεν θέλει τίποτε άλλο!
	</p>

	<li><b>Αρχεία για download</b>
	<p>
		Η εντολή για την παράθεση αρχείου για download είναι [[download: όνομα αρχείου | Περιγραφή αρχείου ]].<br />
		Το εικονίδιο, το μέγεθος του αρχείου και η ημερομηνία του παρέχονται αυτόματα.
	</p>
	</li>

	<li><b>Image Gallery</b>
	<p>
		Δεν υποστηρίζεται ακόμα, αλλά βλέπουμε
	</p>
	</li>


	</ol>
	';

	}


}


