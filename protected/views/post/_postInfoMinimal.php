	<div class="nav">		<?php 			echo CHtml::link(CHtml::encode($post->author->fullname), array('/user/view', 'id'=>$post->author_id));			echo ', ';			echo $post->getFriendlyCreateTime(); 						if ($post->category != null) {				echo ', <b>' . CHtml::link($post->category->title, $post->category->getUrl()) . '</b>';			}			if (count($post->tagLinks) > 0) {				echo ', tags: <b>' . implode(', ', $post->tagLinks) . '</b>';			}						if ($post->commentCount > 0) {				$caption = $post->commentCount == 1 ? 'σχόλιο' : 'σχόλια';				echo ', ' . CHtml::link($post->commentCount . ' ' . $caption, $post->url.'#comments');			}		?>	</div>	