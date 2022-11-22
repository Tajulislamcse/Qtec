<?php
$text = trim(fgets(STDIN));
$pattern = trim(fgets(STDIN));
echo countOccurrences($text,$pattern);
function countOccurrences($text,$pattern)
{
	 $text_length = strlen($text);
	 $pattern_length = strlen($pattern);
     $outerLooplength = $text_length - $pattern_length;
     $count = 0;
	for ($i=0; $i <= $outerLooplength; $i++)
	 { 
	 	$j = 0;
        while($text[$i+$j] == $pattern[$j] )
        {
          $j++;
          if($j == $pattern_length)
          {
          	$count++;
          	break;
          } 
        }
	 }
	 return $count;	
}

/*
   Documentation

   Time Complexity: O(text_length * pattern length)
   As we have to find a pattern in a text, so we can subtract the pattern length from the text length. For example, if text is "rakib"
   pattern is "kib". It is enough to searching upto the position of k. As first character index is 0.
   In the inner while loop, We have tried to match from the position of text to the position of pattern and checked if the length of pattern
   is equal to the length of matched pattern, then by increasing the value of count is finally break.
*/

