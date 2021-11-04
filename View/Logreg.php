<?php
namespace View;

class Logreg {
    private $model;
    
    public function __construct($model)
    {
        $this->model = $model;
    }

    private function reloadPage() //передаем 'reloadpage' в js для перезагрузки страницы и выходим из скрипта иначе просто вывод ошибок
	{
		if ($this->model->getError() == 'reloadpage') {
			echo $this->model->getError();
			exit;
		} else {
			echo '<b>' . $this->model->getError() . '</b>';
		}
	}

    private function formIn()
    {
		$this->reloadPage();
		$dataUser = $this->model->getData();
		$inputText =   [0 => ['name' => '', 'disabled' => '', 'type' => 'hidden'],
						1 => ['name' => 'Логин (e-mail):', 'disabled' => '', 'type' => 'text'],
						2 => ['name' => 'Пароль:', 'disabled' => '', 'type' => 'password']];
						
		$inputButton = [0 => ['disabled' => '', 'type' => 'button', 'value' => 'Войти', 'url' => BASEURL . 'logreg/in/', 'emptypost' => ''],
						1 => ['disabled' => '', 'type' => 'button', 'value' => 'Регистрация', 'url' => BASEURL . 'logreg/reg/', 'emptypost' => 'emptypost']];		

		echo '<h5>Вход</h5>';
		echo $this->formPattern($dataUser, $inputText, $inputButton);		
    }

    private function formReg()
    {
		$this->reloadPage();
		$dataUser = $this->model->getData();
		$inputText =   [0 => ['name' => 'Имя:', 'disabled' => '', 'type' => 'text'],
						1 => ['name' => 'Логин (e-mail):', 'disabled' => '', 'type' => 'text'],
						2 => ['name' => 'Пароль:', 'disabled' => '', 'type' => 'password']];
						
		$inputButton = [0 => ['disabled' => '', 'type' => 'button', 'value' => 'Регистрация', 'url' => BASEURL . 'logreg/reg/', 'emptypost' => ''],
						1 => ['disabled' => '', 'type' => 'button', 'value' => 'Войти', 'url' => BASEURL . 'logreg/in/', 'emptypost' => 'emptypost']];		

		echo '<h5>Регистрация</h5>';
		echo $this->formPattern($dataUser, $inputText, $inputButton);
    }

    private function formRead()
    {
		$this->reloadPage();
		$dataUser = $this->model->getData();
		$inputText =   [0 => ['name' => 'Имя:', 'disabled' => '', 'type' => 'text'],
						1 => ['name' => 'Логин (e-mail):', 'disabled' => '', 'type' => 'text'],
						2 => ['name' => 'Пароль:', 'disabled' => '', 'type' => 'password']];
						
		$inputButton = [0 => ['disabled' => '', 'type' => 'button', 'value' => 'Изменить', 'url' => BASEURL . 'logreg/update/', 'emptypost' => ''],
						1 => ['disabled' => '', 'type' => 'button', 'value' => 'Отменить', 'url' => BASEURL . 'logreg/update/', 'emptypost' => 'emptypost'],
						2 => ['disabled' => '', 'type' => 'button', 'value' => 'Удалить аккаунт', 'url' => BASEURL . 'logreg/delete/', 'emptypost' => '']];		

		echo '<h5>Сведения об аккаунте</h5>';
		echo $this->formPattern($dataUser, $inputText, $inputButton);
    } 

    private function formPattern($dataUser, $inputText, $inputButton) //шаблон для построения формы
    {
		$htmlInputText = '<br>{*name*}<br><input {*disabled*} type="{*type*}" size="30" maxlength="50" id="{*dataname*}" name="{*dataname*}" value="{*data*}"><br> ';
		$htmlInputButton = '<input {*disabled*} type="{*type*}" name="button" value="{*value*}" onclick="sendRequest(\'{*url*}\', \'{*emptypost*}\');"> '; //'emptypost' передаем в js скрипт, чтобы на сервер не передавались данные (используется при переходе с одной формы на другую, чтобы следом не тянулись с нее данные)
		$htmlinputtext1 = $htmlInputText;
		$htmlinputbutton1 = $htmlInputButton;
		$htmlLogRegForm = '';
		
        foreach ($inputText as $value) {
            foreach ($value as $key => $valueinput) {
				$htmlinputtext1 = str_replace("{*{$key}*}", $valueinput, $htmlinputtext1);
            }
			$htmlinputtext1 = str_replace("{*dataname*}", key($dataUser[0]), $htmlinputtext1);
			$htmlinputtext1 = str_replace("{*data*}", current($dataUser[0]), $htmlinputtext1);
			if (str_contains($htmlinputtext1, 'hidden')) {
				$htmlinputtext1 = str_replace('<br>', '', $htmlinputtext1);
			}
			next($dataUser[0]);
			$htmlLogRegForm .= $htmlinputtext1;
			$htmlinputtext1 = $htmlInputText;
        }
		
		$htmlLogRegForm .= '<br>';
        foreach ($inputButton as $value) {
            foreach ($value as $key => $valueinput) {
				$htmlinputbutton1 = str_replace("{*{$key}*}", $valueinput, $htmlinputbutton1);
            }
			$htmlLogRegForm .= $htmlinputbutton1;
			$htmlinputbutton1 = $htmlInputButton;
        }
		return $htmlLogRegForm;
    } 

    public function in()
    {
		$this->formIn();
		
    }
	
    public function reg()
    {
		$this->formReg();
		
    }

	public function out()
    {
		echo 'reloadpage';
	}
	
	public function read()
    {
		$this->formRead();
		
	}
	
	public function update()
    {
		$this->formRead();
		
	}
	
	public function delete()
    {
		echo 'reloadpage';
	}


}
