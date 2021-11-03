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
/*
    private function reloadPage()
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
		$urlin = BASEURL . 'logreg/in/';
		$urlreg = BASEURL . 'logreg/reg/';
		$htmlLogRegForm = '	<input type="hidden" size="30" maxlength="50" id="namenik" name="namenik" value=""><br>
							Логин (e-mail):<br>
							<input type="text" size="30" maxlength="50" id="email" name="email" value="' . $dataUser[0]['email'] . '"><br>
							<br>Пароль:<br>
							<input type="password" size="30" maxlength="50" id="password" name="password" value="' . $dataUser[0]['password'] . '"><br>
							<input type="hidden" name="info" value="hiddenguestbook"><br>
							<input type="button" name="button" value="Войти" onclick="sendRequest(\'' . $urlin. '\');">
							<input type="button" name="button" value="Регистрация" onclick="sendRequest(\'' . $urlreg . '\', \'emptypost\');">';
		echo '<h5>Вход</h5>';
		echo $htmlLogRegForm;
    }

    private function formReg()
    {
		$this->reloadPage();
		$dataUser = $this->model->getData();
		$url1 = BASEURL . 'logreg/reg/';
		$url2 = BASEURL . 'logreg/in/';
		$value1 = 'Регистрация';
		$value2 = 'Войти';
		$disabled = ''; //установка блокировки корректировки данных в форме
		$emptypost ='emptypost'; //передаем в js скрипт, чтобы данные не передавались на сервер (используется при переходе с одной формы на другую, чтобы следом не тянулись с нее данные)
		echo '<h5>Регистрация</h5>';
		echo $this->formPatternRegReadUpdate($dataUser, $url1, $url2, '', $value1, $value2, '', $disabled, $emptypost);
    }

    private function formRead()
    {
		$this->reloadPage();
		$dataUser = $this->model->getData();
		$url1 = BASEURL . 'logreg/update/';
		$url2 = BASEURL . 'logreg/update/';
		$url3 = BASEURL . 'logreg/delete/';
		$value1 = 'Изменить';
		$value2 = 'Отменить';
		$value3 = 'Удалить аккаунт';
		$disabled = '';
		$emptypost ='emptypost';
		echo '<h5>Сведения об аккаунте</h5>';
		echo $this->formPatternRegReadUpdate($dataUser, $url1, $url2, $url3, $value1, $value2, $value3, $disabled, $emptypost);
    } 

    private function formPatternRegReadUpdate($dataUser, $url1, $url2, $url3, $value1, $value2, $value3, $disabled, $emptypost)
    {
		$htmlLogRegForm = '<br>Имя:<br>
							<input ' . $disabled . ' type="text" size="30" maxlength="50" id="namenik" name="namenik" value="' . $dataUser[0]['namenik'] . '"><br>
							<br>Логин (e-mail):<br>
							<input ' . $disabled . ' type="text" size="30" maxlength="50" id="email" name="email" value="' . $dataUser[0]['email'] . '"><br>
							<br>Пароль:<br>
							<input ' . $disabled . ' type="password" size="30" maxlength="15" id="password" name="password" value="' . $dataUser[0]['password'] . '"><br>
							<input type="hidden" name="info" value="hiddenguestbook"><br>
							<input type="button" name="button" value="' . $value1 . '" onclick="sendRequest(\'' . $url1 . '\');">
							<input type="button" name="button" value="' . $value2 . '" onclick="sendRequest(\'' . $url2 . '\', \'' . $emptypost . '\');">
							<input type="button" name="button" value="' . $value3 . '" onclick="sendRequest(\'' . $url3 . '\');">';
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
	*/