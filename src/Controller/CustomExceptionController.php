<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 10/03/2018
 * Time: 20:06
 */

namespace App\Controller;


use Symfony\Bundle\TwigBundle\Controller\ExceptionController;
use Symfony\Component\HttpFoundation\Request;

class CustomExceptionController extends ExceptionController
{

    public function findTemplate(Request $request, $format, $code, $showException){

        //-----------------------------------------------
        // If it's a backend route, show different error

        $isBackend = false;
        if(preg_match("/^\/admin/", $request->getRequestUri())){
            $isBackend = true;
        }

        //-----------------------------------------------


        $name = $showException ? 'exception' : 'error';
        if ($showException && 'html' == $format) {
            $name = 'exception_full';
        }
        // For error pages, try to find a template for the specific HTTP status code and format
        if (!$showException) {
            $template = sprintf('@Twig/Exception/%s%s%s.%s.twig', ($isBackend)?'admin_':'' ,$name, $code, $format);
            if ($this->templateExists($template)) {
                return $template;
            }
        }
        // try to find a template for the given format
        $template = sprintf('@Twig/Exception/%s%s.%s.twig', ($isBackend)?'admin_':'', $name, $format);
        if ($this->templateExists($template)) {
            return $template;
        }
        // default to a generic HTML exception
        $request->setRequestFormat('html');
        return sprintf('@Twig/Exception/%s.html.twig', $showException ? 'exception_full' : $name);
    }
}