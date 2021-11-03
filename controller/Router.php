<?php
namespace Controller;
class Router 
{
    private $baseURL;
    private $page;
    private $action = "read";
    private $idCatalog = 0;
	private $idPhoto = 0;
    private $pageNumber = 1;
    
    public function __construct($page, $baseURL)
    {
        $this->page = $page;
        $this->baseURL = $baseURL;
    }
    
	private function checkAdminRole()
    {
		if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin')) {
			return true;
		} else {
			return false;
		}
	}
	
	public function runRouter()
    {
        //разбор поступившего адреса
		$this->page = strtolower ($this->page);
		$this->baseURL = strtolower ($this->baseURL);
		$this->action = strtolower ($this->action);
		$url = strtolower ($_SERVER['REQUEST_URI']);
        if (strpos($url, $this->baseURL) === 0) {
            $url = substr($url, strlen($this->baseURL));
        }
        if (strpos($url, 'index.php') === 0) {
            $url = substr($url, strlen('index.php'));
        }
        if (($pos = strpos($url, '?')) !== false) {
            $url = substr($url, 0, $pos);
        }
		$parameters = explode('/', $url);
        if (!empty($parameters[0])) $this->page = strtolower ($parameters[0]);
        if (!empty($parameters[1])) $this->action = strtolower ($parameters[1]);
        if (!empty($parameters[2])) $this->idCatalog = intval($parameters[2]);
		if (!empty($parameters[3])) $this->idPhoto = intval($parameters[3]);
        if (!empty($parameters[4])) $this->pageNumber = intval($parameters[4]);
	
		//проверка названий страниц и методов
		$check = false;
		unset($parameters);
		if (($this->page === "catalog") 
			&& ($this->action === "read")) {
			$parameters = array($this->idCatalog, $this->idPhoto, $this->pageNumber);
			$check = true;
		}
		
		if (($this->page === "logreg") 
			&& preg_match('/^(in|out|reg|read|update|delete)$/', $this->action)) {
			$parameters = "";
			$check = true;				
			}

		if (($this->page === "edit") && $this->checkAdminRole() 
			&& preg_match('/^(photoread|photoupdate|photocreate)$/', $this->action)) {
			$parameters = "";
			$check = true;
		}
		
		//вызов контроллера
		if ($check) {	
			$controller = new UniversalController($this->page, $this->action, $parameters);
			$controller->run();
		} else {
			$error = 'Запрошенные страница или действие не определены.';
			include 'view/error.php';
		}
	}
}
