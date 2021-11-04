<?php
namespace Controller;
class Router 
{
    private $baseURL;
    private $page = "catalog";
	private $action = "read";

    public function __construct($baseURL)
    {
        $this->baseURL = $baseURL;
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

		//проверка названий страниц и запуск необходимого контроллера
		if (preg_match('/^(catalog|logreg|edit)$/', $this->page)) {
			$controller = new UniversalController($this->page, $this->action, $parameters);
			$controller->run();
		} else {
			$error = 'Запрошенная страница не определена.';
			include 'view/error.php';
		}
	}
}
