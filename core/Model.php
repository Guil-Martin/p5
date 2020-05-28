<?php
    class Model
    {

        protected function makeExcerpt($content)
        { // Generate an excerpt using the content of the news
            $str = strip_tags(trim($content));
            $startPos = 0;
            $maxLength = 150;
            if(strlen($str) > $maxLength) {
                $excerpt   = substr($str, $startPos, $maxLength-3);
                $lastSpace = strrpos($excerpt, ' ');
                $excerpt   = substr($excerpt, 0, $lastSpace);
                $excerpt  .= '...';
            } else {
                $excerpt = $str;
            }
            return $excerpt;
        }
        
    }