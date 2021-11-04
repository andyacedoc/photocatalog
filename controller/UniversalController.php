<?php
namespace Controller;
class UniversalController
{
	private $page;
    private $action;
    private $parameters;
	private $model;
    private $view;
	
    public function __construct($page, $action, $parameters)
    {
        $this->page = $page;
        $this->action = $action;
		$this->parameters = $parameters;
		$this->checkAction(); //проверка запрошенного метода
    }

	private function checkAction()
	{
		if (preg_match('/^(read)$/', $this->action)
			|| preg_match('/^(in|out|reg|read|update|delete)$/', $this->action)
			|| preg_match('/^(photoread|photoupdate|photocreate)$/', $this->action)) {
		} else {
			$error = 'Запрошенное действие не определено.';
			include 'view/error.php';
			exit;
		}
	}

    public function run()
    {
		$modelName = 'Model\\' . $this->page;
		$this->model = new $modelName($this->parameters);
		\core\db::init(DSN, LOGIN, PASSWORD);
		$this->model->{$this->action}();
		$viewName = 'View\\' . $this->page;
		$this->view = new $viewName($this->model);
		$this->view->{$this->action}();
	}
}
