<?php

namespace AppBundle\TwigExt;

class BigChar extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
          new \Twig_SimpleFunction('big_char', function( $string = null ) {
              $firstChar = substr(ltrim($string), 0, 1);
              $text = substr(ltrim($string), 1);
              echo'<span style="color: #2da02d; font: italic bold 1.5em Georgia, serif; text-transform: capitalize">'.$firstChar.'</span>'.$text;
              })
        );
    }

    public function getName()
    {
        return 'big_char';
    }
}
