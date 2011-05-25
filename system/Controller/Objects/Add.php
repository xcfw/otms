<?php
class Controller_Objects_Add extends Controller_Objects {
    
    public function __construct($registry) {
		parent::__construct($registry);
        
        $this->begin("objects", "add");
	}
	
	public function index($args) {
        if (!$this->registry["ui"]["readonly"]) {
        
            $this->view->setTitle("Добавить объект");

            $object = new Model_Object($this->registry);
            
            if (isset($_POST["submit"])) {
                
                $object->addObject($_POST);
                
                $this->view->refresh(array("timer" => "1", "url" => "objects/list/"));
                
            } else {
                if (isset($_GET["p"])) {
                    $this->view->objects_add(array("pname" => $_GET["p"]));
                }
            }
        }
        
        $this->view->showPage();
	}
}
?>