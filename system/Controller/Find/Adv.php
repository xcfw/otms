<?php
class Controller_Find_Adv extends Controller_Find {
    
    public function __construct($registry) {
		parent::__construct($registry);
        
        $this->begin("find", "adv");
	}
	
	public function index($args) {
        $this->view->setTitle("Поиск");
       
        $find = new Model_Find($this->registry);
        $task = new Model_Task($this->registry);
        
        if (isset($this->findSess["string"])) {
            
            $this->view->setMainContent("<p style='font-weight: bold; margin-bottom: 20px'>Поиск: " . $this->findSess["string"] . "</p>");

            if (isset($args[1])) {
    			if ( ($args[1] == "page") and (isset($args[2])) ) {
    				if (!$find->setPage($args[2])) {
    					$this->__call("objects", "index");
    				}
    			}
    		}
            
            $text = substr($this->findSess["string"], 0, 64);
			$text = explode(" ", $text);

            $findArr = $find->findAdvs($text);
            
            if (!isset($args[1]) or ($args[1] == "page"))  {
                
                foreach($findArr as $part) {
                    
                    $numTroubles = $task->getNumTroubles($part["id"]);
                    $obj = $task->getShortObject($part["id"]);
                    $advInfo = $task->getAdvancedInfo($part["id"]);
                    $numAdvInfo = $task->getNumAdvancedInfo($part["id"]);
                    $this->view->objectMain(array("ui" => $this->registry["ui"], "obj" => $obj, "advInfo" => $advInfo, "numAdvInfo" => $numAdvInfo, "numTroubles" => $numTroubles));
                }
            
                //Отобразим пейджер
    			if (count($find->pager) != 0) {
    				$this->view->pager(array("pages" => $find->pager));
    			}
            }
        }

        $this->view->showPage();
    }
}
?>