<?php

class aEmbedFormService
{
  /**
   * If the value is recognizable as a google docs form embed code, return a
   * canonicalized embed code for that form. If it is not recognizable as such,
   * return null. A link to the form won't do because we need to know the height.
   * The returned embed code has a _WIDTH_ placeholder which you must replace
   * with the desired width (note that forms will not work well in tiny columns). 
   * The height is as provided by google docs
   */
  public function canonicalize($value)
  {
    if (preg_match('|docs.google.com(/\w+/[\w\.]+?)/[/\w]+?\?formkey=(\w+)|', $value, $matches))
    {
      list($dummy, $org, $key) = $matches;
      if (preg_match('/height="(\d+)"/', $value, $matches))
      {
        $height = $matches[1];
        return <<<EOM
<iframe src="https://docs.google.com$org/spreadsheet/embeddedform?formkey=$key" width="_WIDTH_" height="$height" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>
EOM
        ;
      }
    }
    return null;
  }
}
