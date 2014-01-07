<?php

// from   http://www.phpmoot.com/php-class-that-compare-two-strings-and-return-the-changed-lines/
class Differer extends CApplicationComponent
{

	/**
	* Creates LCS matrix (naive non optimized version).
	*
	* @param mixed[] $left
	* @param mixed[] $right
	* @return mixed[] LCS matrix
	*/
	protected function makeLcs($left, $right)
	{
		$matrix = array(array());
		for($i = 0; $i <= count($left); $i++)
		{
			$matrix[$i][0] = 0;
		}

		for($i = 0; $i <= count($right); $i++)
		{
			$matrix[0][$i] = 0;
		}
		
		$leftCount = count($left);
		$rightCount = count($right);
		for($leftIndex = 1; $leftIndex < $leftCount; $leftIndex++) 
		{
			$leftValue = $left[$leftIndex];
			for($rightIndex = 1; $rightIndex < $rightCount; $rightIndex++)
			{
				$rightValue = $right[$rightIndex];
				if($leftValue == $rightValue)
				{
					$matrix[$leftIndex][$rightIndex] = $matrix[$leftIndex - 1][$rightIndex - 1] + 1;
				}
				else
				{
					$matrix[$leftIndex][$rightIndex] =
					(
						$matrix[$leftIndex][$rightIndex - 1] > $matrix[$leftIndex - 1][$rightIndex]
						?
						$matrix[$leftIndex][$rightIndex - 1]
						:
						$matrix[$leftIndex - 1][$rightIndex]
					);
				}
			}
		}

		return $matrix;
	}

	/**
	* Traverses through LCS matrix.
	*
	* @param mixed[] $matrix LCS matrix
	* @param mixed[] $left
	* @param mixed[] $right
	* @param int $i Current left coordinate.
	* @param int $j Current right coordinate.
	* @param int $start Starting index (not used in naive version of diff).
	* @param string $result
	*/
	protected function traverseLcs($matrix, $left, $right, $i, $j, $start = 0, & $result = '')
	{
		if($i > $start && $j > $start && $left[$i] === $right[$j])
		{
			$this->traverseLcs($matrix, $left, $right, $i - 1, $j - 1, $start, $result);
			$result .= " ".$left[$i]."\n";
		}
		else
		{
			if($j > $start && ($i == $start || $matrix[$i][$j - 1] >= $matrix[$i - 1][$j]))
			{
				$this->traverseLcs($matrix, $left, $right, $i, $j - 1, $start, $result);
				//$result .=  "+".$right[$j]."\n";
				$result .=  "<ins style=\"color: #55aa55; text-decoration: underline;\">".$right[$j]."</ins>\n";
			}
			else if($i > $start && ($j == $start || $matrix[$i][$j - 1] < $matrix[$i - 1][$j]))
			{
				$this->traverseLcs($matrix, $left, $right, $i - 1, $j, $start, $result);
				//$result .= "-".$left[$i]."\n";
				$result .= "<del style=\"color: #aa5555; text-decoration: line-through;\">".$left[$i]."</del>\n";
			}
			else
			{
			}
		}
	}

	/**
	* Creates diff for two strings.
	*
	* @param string $left String #1.
	* @param string $right String #2.
	* @return string
	*/
	public function compare($left, $right)
	{
		$left = explode("\n", $left);
		$right = explode("\n", $right);
		return $this->compareArray($left, $right);
	}

	/**
	* Creates diff for two custom arrays.
	*
	* @param mixed[] $left Array #1.
	* @param mixed[] $right Array #2.
	* @return string
	*/
	function compareArray($left, $right)
	{
		array_unshift($left, '');
		array_unshift($right, '');
		$matrix = $this->makeLcs($left, $right);
		$result = "";
		$this->traverseLcs($matrix, $left, $right, count($left) - 1, count($right) - 1, 0, $result);
		return $result;
	}
}


?>