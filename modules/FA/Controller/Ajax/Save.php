<?php
class Controller_Ajax_Save extends Modules_Ajax {
    private $file;

    private $abspDir = null;
    private $abs_thumbDir = null;
	
    function __construct($config) {
        parent::__construct($config);     

        if (isset($_GET['qqfile'])) {
            $this->file = new Model_Save();
        } else {
            $this->file = false; 
        }
        
        $this->abspDir = $this->registry['path']['root'] . "/" . $this->registry['path']['upload'];
        $this->abs_thumbDir = $this->registry['path']['root'] . "/" . $this->registry['path']['upload'] . "_thumb/";
    }
    
    function handleUpload($uploadDirectory, $_thumbPath, $replaceOldFile = FALSE) {		 
        if (!is_writable($uploadDirectory)){
            return array('error' => "Ошибка сервера. Запись в директорию невозможен! " . $this->abspDir);
        }
        
        if (!$this->file){
            return array('error' => 'Нет файлов для загрузки');
        }
        
        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => 'Пустой файл или директория');
        }
        
        if ($size > $this->config["sizeLimit"]) {
			if (($this->config["sizeLimit"] / 1024) > 1) {
				$tsize = round($this->config["sizeLimit"] / 1024, 2) . " Кб";
			} else {
				$tsize = round($this->config["sizeLimit"], 2) . " Б";
			};
			
			if (($tsize / 1024) > 1) {
				$tsize = round($tsize / 1024, 2) . " Мб";
			};
			
            return array('error' => 'Файл слишком большой! Установлен лимит на максимальный размер загружаемого файла: ' . $tsize);
        }
        
        $this->file->getName();

        if ($this->file->save()) {
            return array('success'=>true);
        } else {
            return array('error'=> 'Не получается сохранить файл.' .
                'Загрузка отменена, ошибка сервера');
        }
        
    }

	function index() {
		$sPath = $this->abspDir;
        $_thumbPath = $this->abs_thumbDir;
		
		$result = $this->handleUpload($sPath, $_thumbPath);

		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	}
}
?>