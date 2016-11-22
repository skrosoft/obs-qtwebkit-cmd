<?php

namespace Onaxis\Bundle\ObsQtwebkitCmdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ObsQtwebkitCmdBundle:Default:index.html.twig');
    }
}
