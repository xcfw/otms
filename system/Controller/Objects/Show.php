<?php
class Controller_Objects_Show extends Controller_Objects {
    
    public function __construct($registry) {
		parent::__construct($registry);
        
        $this->begin("objects", "show");
	}
	
	public function index($args) {            
        $this->view->setTitle("Объект");

        $task = new Model_Task($this->registry);
        
        if ($obj = $task->getShortObject($args["0"])) {
            
            $numTroubles = $task->getNumTroubles($args["0"]);
            $advInfo = $task->getAdvancedInfo($args["0"]);
            $numAdvInfo = $task->getNumAdvancedInfo($args["0"]);
            $this->view->objectMain(array("ui" => $this->registry["ui"], "obj" => $obj, "advInfo" => $advInfo, "numAdvInfo" => $numAdvInfo, "numTroubles" => $numTroubles));
        } else {
            $this->view->setMainContent("<p>Объект не найден</p>");
        }
        
        $this->view->showPage();
	}
}
?>