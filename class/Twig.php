<?php

namespace Resume;

class Twig {
  private $twig;

  public function __construct() {
    $loader = new \Twig_Loader_Filesystem([
      BASEDIR . "/twig",
      BASEDIR . "/page"
    ]);
    $this->twig = new \Twig_Environment($loader, array("debug" => true));
  }

  public function renderTemplate($template, $data) {
    $template = $this->twig->load($template);
    
    return $template->render(["data" => $data]);
  }
}
