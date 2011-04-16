<?php
class View_Index {

	protected $registry;

	private $title = null;
	private $description = array();
	private $keywords = array();
    private $leftBlock = null;
	private $mainContent = null;

	private $menu = null;

	private $main;
	private $twig;


	function __construct($registry) {
		$this->registry = $registry;

        $this->main = $this->registry['layouts'];
        $this->twig = $this->registry['templates'];
	}

	function getTemplate($template) {
		$dirClass = explode("_", $template);
	
		if (sizeof($dirClass) > 1) {
			$template = implode(DIRECTORY_SEPARATOR, $dirClass) . '.tpl';
		} else
		{
			$template = $template . '.tpl';
		};
	
		return $template;
	}

	public function __call($name, $params) {
		$template = $this->twig->loadTemplate($this->getTemplate($name));

		if (isset($params[0])) {
			$content = $template->render($params[0]);
		} else {
			$content = $template->render(array());
		};

		$this->setMainContent($content);
	}
	
	public function render($name, $params) {
		$template = $this->twig->loadTemplate($this->getTemplate($name));

		if (isset($params)) {
			$content = $template->render($params);
		} else {
			$content = $template->render(array());
		};

		return $content;
	}
    
	public function setTitle($text) {
		$this->title .= $text;
	}

	public function setDescription($text) {
		$this->description[] = str_replace('"',"",$text);
	}

	public function setKeywords($text) {
		$this->keywords[] = str_replace('"',"",$text);
	}

	public function setMainContent($text) {
		$this->mainContent .= $text;
	}

	public function setLeftContent($text) {
		$this->leftBlock .= $text;
	}

	// Главная страница-шаблон
	public function showPage() {

		$template = $this->main->loadTemplate("head.tpl");
		$template->display(array("description" => implode(",", $this->description),
								"keywords" => implode(",", $this->keywords),
								"title" => $this->title));

		
		
        
        $categories = array();

		if ($this->registry["auth"]) {
			$categories[1]["name"] = "Задачи";
			$categories[1]["link"] = "/tt/";
            
			//$categories[2]["name"] = "База";
			//$categories[2]["link"] = "/kb/";
            
			$categories[2]["name"] = "Объекты";
			$categories[2]["link"] = "/objects/";
            
            if ($this->registry["ui"]["admin"]) {
    			$categories[3]["name"] = "Настройки";
    			$categories[3]["link"] = "/settings/";
            }
            
			//$categories[]["name"] = "Статистика";
			//$categories[]["link"] = "/stat/";
            
			//$categories[]["name"] = "Помощь";
			//$categories[]["link"] = "/help/";
		} else {
			$categories[1]["name"] = "Главная";
			$categories[1]["link"] = "/";
          
			$categories[2]["name"] = "Вход";
			$categories[2]["link"] = "/login/";
		};

		for ($i=1; $i<=count($categories); $i++) {
            $action = "/" . $this->registry["action"] . "/";
            if ($this->registry["action"] == "index") { $action = "/"; }
            
			if ($action == $categories[$i]["link"]) {
				$categories[$i]["selected"] = TRUE;
			} else {
				$categories[$i]["selected"] = FALSE;
			}
		}

		$template = $this->main->loadTemplate("menu.tpl");

		$template->display(array("categories" => $categories));
       
        
              
		$template = $this->main->loadTemplate("content.tpl");
		$template->display(array("leftBlock" => $this->leftBlock,
                                "main_content" => $this->mainContent,
                                "ttgroups" => $this->registry["ttgroups"]));

		$template = $this->main->loadTemplate("footer.tpl");
		$template->display(array("sitename" => $this->registry["siteName"],
                                "ui" => $this->registry["ui"]));
	}
}
?>