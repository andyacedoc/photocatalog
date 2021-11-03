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
