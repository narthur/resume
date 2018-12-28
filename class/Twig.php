<?

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

  public function renderTemplate($template, $data, $outPath) {
    $template = $this->twig->load($template);
    $html = $template->render(["data" => $data]);
    file_put_contents($outPath, $html);
  }
}
