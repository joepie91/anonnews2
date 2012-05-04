<?php
if(!isset($_ANONNEWS)) { die(); } // Protect against direct access.
/* This include contains functions related to filters that are applied to page content. */

function strip_tags_attributes($string, $allowtags = NULL, $allowattributes = NULL)
{ 
	/* Thanks to nauthiz693@gmail.com (http://www.php.net/manual/en/function.strip-tags.php#91498) */
    $string = strip_tags($string,$allowtags); 
    if (!is_null($allowattributes)) 
    { 
        if(!is_array($allowattributes)) 
        {
            $allowattributes = explode(",",$allowattributes); 
		}
		
        if(is_array($allowattributes)) 
        {
            $allowattributes = implode(")(?<!",$allowattributes); 
		}
		
        if (strlen($allowattributes) > 0) 
        {
            $allowattributes = "(?<!".$allowattributes.")"; 
		}
		
        $string = preg_replace_callback("/<[^>]*>/i",create_function( 
            '$matches', 
            'return preg_replace("/ [^ =]*'.$allowattributes.'=(\"[^\"]*\"|\'[^\']*\')/i", "", $matches[0]);'    
        ),$string); 
    } 
    return $string; 
} 

function filter_extended($input)
{
	return strip_tags_attributes($input,
	"<a><b><i><u><span><div><p><img><br><hr><font><ul><li><ol><dt><dd><h1><h2><h3><h4><h5><h6><h7><del><map><area><strong><em><big><small><sub><sup><ins><pre><blockquote><cite><q><center><table><tr><td><th>",
	"href,src,alt,class,style,align,valign,color,face,size,width,height,shape,coords,target,border,cellpadding,cellspacing,colspan,rowspan");
}

function filter_basic($input)
{
	return strip_tags_attributes($input,
	"<b><i><u><span><p><font><ul><li><ol><dt><dd><del><strong><big><small><sub><sup><ins><pre><blockquote><cite><q><center><table><tr><td><th>",
	"href,src,alt,class,style,align,valign,color,face,size,width,height,border,cellpadding,cellspacing,colspan,rowspan");
}

/*function parse_youtube($input)
{
	return preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
	"<object width=\"425\" height=\"344\"><param name=\"movie\" value=\"http://www.youtube.com/v/$1&hl=en&fs=1\"></param><param name=\"allowFullScreen\" value=\"true\"></param>
	<embed src=\"http://www.youtube.com/v/$1&hl=en&fs=1\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" width=\"425\" height=\"344\"></embed></object>",$input);
}*/

function youtubify($input)
{
	$video_width = 900;
	$video_height = 506;
	
	$input = preg_replace("/<a[^>]+href=[\"']https?:\/\/([a-z\-0-9]+\.)youtube\.com\/watch\?[^'\" ]*v=([a-z0-9\-_]+)[^'\" ]*['\"][^>]*>[^<]*<\/a>/i",
	"<br><iframe width=\"{$video_width}\" height=\"{$video_height}\" src=\"http://www.youtube.com/embed/$2\" frameborder=\"0\" allowfullscreen></iframe><br>", $input);
	
	$input = preg_replace("/https?:\/\/([a-z\-0-9]+\.)youtube\.com\/watch\?[^'\" ]*v=([a-z0-9\-_]+)[^<)\]! ]*/i",
	"<br><iframe width=\"{$video_width}\" height=\"{$video_height}\" src=\"http://www.youtube.com/embed/$2\" frameborder=\"0\" allowfullscreen></iframe><br>", $input);
	
	return $input;
}
?>
