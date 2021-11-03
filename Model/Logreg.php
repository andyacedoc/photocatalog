<?php
namespace Model;

class Logreg {
	private $data;
	private $dataUser;
	private $error = '';
    private $select = 'select namenik, email, password, role from users where email=?';
	private	$insert = 'insert into users (namenik, email, password) values (?,?,?)';
    private $update = 'update users set namenik=?, email=?, password=? where email=?';
	private $delete = 'delete from users where email=?';	
    
    public function __construct($parameters)
    {
    }

    private function sessionVerify()
    {
		$this->dataUser = \Core\Db::select($this->select, [$this->data['email']]);
		if (count($this->dataUser) == 1) { //Проверка наличия данных в базе данных
			if (password_verify($_POST['password'], $this->dataUser[0]['password'])) { //Проверка пароля
				$_SESSION['email']=$this->dataUser[0]['email'];
				$_SESSION['namenik']=$this->dataUser[0]['namenik'];
				$_SESSION['role']=$this->dataUser[0]['role'];
				$this->error = 'reloadpage'; //передаем в js для перезагрузки страницы
			} else {
				$this->error = 'Введен не верный пароль.<br>';
			}
		} else {
			$this->error = 'Вы незарегистрированы.<br>';
		}
		$this->dataUser = [0 => ['namenik' => '', 'email' => $_POST['email'], 'password' => '']];
	}

    private function checkEmpty($datain)
    {
        if (isset($datain)) {
            $datain = trim($datain);
        } else {
            $datain = '';
        }
        if ((mb_strlen($datain)) == 0) {
			$datain = 0;
		}
		return $datain; //ноль если строка пустая или строка без пробелов в начале и конце
	}
	
    private function checkEmail($datain)
    {
		if (isset($datain)) {
			$datain = filter_var($datain, FILTER_VALIDATE_EMAIL);
		} else {
			$datain = false;
		}
		return $datain; //true если адрес в порядке иначе false
	}

    private function validateIn()
    {
		if ($this->checkEmail($this->checkEmpty($_POST['email']))){
			$this->data['email'] = $this->checkEmpty($_POST['email']);
		} else {
			$this->data['email'] = '';
			$this->error .= 'Логин не заполнен или некорректен.<br>';
		}
        return $this->error == ''; //true если не было ошибок
    }

    private function validateRegUpdate()
    {
		if ((!isset($_SESSION['email'])) 
			|| ($_SESSION['email'] !== $this->checkEmpty($_POST['email']))) { //Проверка наличия логина в базе данных
				$this->dataUser = \Core\Db::select($this->select, [$this->checkEmpty($_POST['email'])]);
				if (count($this->dataUser) !== 0) {
					$this->error .= 'Логин c таким именем уже занят.<br>';
				}
		}
		if ($this->checkEmpty($_POST['namenik']) !== 0) {
			$this->data['namenik'] = htmlspecialchars($this->checkEmpty($_POST['namenik']));
		} else {
			$this->data['namenik'] = '';
			$this->error .= 'Имя не заполнено.<br>';
		}
		if ($this->checkEmail($this->checkEmpty($_POST['email']))){
			$this->data['email'] = $this->checkEmpty($_POST['email']);
		} else {
			$this->data['email'] = '';
			$this->error .= 'Логин не заполнен или некорректен.<br>';
		}
		if ($this->checkEmpty($_POST['password']) !== 0) {
			$this->data['password'] = $_POST['password'];
		} else {
			$this->data['password'] = '';
			$this->error .= 'Пароль не заполнен.<br>';
		}
        return $this->error == ''; //true если не было ошибок
    }
    
    public function in()
    {
		if (($_SERVER['REQUEST_METHOD'] == 'POST') && (count($_POST) <> 0)) { //Проверка получения данных
			if ($this->validateIn()) { //Проверка данных
				$this->sessionVerify();
			} else { //Данные не прошли проверку. Возврат в форму.
				$this->dataUser = [0 => ['namenik' => $_POST['namenik'], 'email' => $_POST['email'], 'password' => $_POST['password']]];
			}
		} else {//Пустые данные для передачи в форму
			$this->dataUser = [0 => ['namenik' => '', 'email' => '', 'password' => '']];
		}
    }    
    
	public function reg()
    {
		if (($_SERVER['REQUEST_METHOD'] == 'POST') && (count($_POST) <> 0)) { //Проверка получения данных
			if ($this->validateRegUpdate()) { //Проверка данных
				\Core\Db::exec($this->insert, [$this->data['namenik'], $this->data['email'], password_hash($this->data['password'], PASSWORD_BCRYPT)]);
				$this->sessionVerify();
			} else { //Данные не прошли проверку. Возврат в форму.
				$this->dataUser = [0 => ['namenik' => $_POST['namenik'], 'email' => $_POST['email'], 'password' => $_POST['password']]];
			}
		} else {//Пустые данные для передачи в форму
			$this->dataUser = [0 => ['namenik' => '', 'email' => '', 'password' => '']];
		}		
	}
	
	public function out()
    {
		session_destroy();
		//$url = "http://" . HOST . BASEURL;
        //header("Location: $url");
	}
	
	public function read()
    {
		$this->dataUser = \Core\Db::select($this->select, [$_SESSION['email']]);
		$this->dataUser[0]['password'] = '';
	}

	public function update()
    {
		if (($_SERVER['REQUEST_METHOD'] == 'POST') && (count($_POST) <> 0)) { //Проверка получения данных
			if ($this->validateRegUpdate()) { //Проверка данных
				\Core\Db::exec($this->update, [$this->data['namenik'], $this->data['email'], password_hash($this->data['password'], PASSWORD_BCRYPT), $_SESSION['email']]);
				$this->sessionVerify();
			} else { //Данные не прошли проверку. Возврат в форму.
				$this->dataUser = [0 => ['namenik' => $_POST['namenik'], 'email' => $_POST['email'], 'password' => $_POST['password']]];
			}
		} else {
			$this->error = 'reloadpage'; //массив POST пустой, то передаем в js 'reloadpage' для перезагрузки страницы
		}
	}
	
	public function delete()
    {
		\Core\Db::exec($this->delete, [$_SESSION['email']]);
		session_destroy();
	}

//
    public function getError() {
        return $this->error;
    }
//
    public function getData() {
		return $this->dataUser;
		}
}
