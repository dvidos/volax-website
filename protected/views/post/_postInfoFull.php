	<div class="nav">		<?php			echo $post->getFriendlyCreateTime();			echo ', από τον χρήστη ' . CHtml::encode($post->author->username);						if ($post->category != null) {				echo '<br />';				echo 'Στήλη: <b>' . CHtml::link($post->category->title, $post->category->getUrl()) . '</b>';			}			if (count($post->tagLinks) > 0) {				echo '<br />';				echo 'Tags: <b>' . implode(', ', $post->tagLinks) . '</b>';			}							if ($post->commentCount > 0) {				$caption = $post->commentCount == 1 ? 'σχόλιο' : 'σχόλια';				echo ', ' . CHtml::link($post->commentCount . ' ' . $caption, $post->url.'#comments');			}		?>	</div>