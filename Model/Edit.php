<?php
namespace Model;

class Edit {
	private $createfunc = false;
	private $error = '';
	private $errorValidate = '';
	private $data;
	private $dataCatalog;
	private $dataAuthor;
	private $dataCountry;
	private $dataPhoto;
    
    private $selectCatalog = 'select idcatalog, idparent, name, imagefile from catalog';
    private $selectAuthor = 'select idauthor, name, surname from authors';
    private $selectCountry = 'select idcountry, name from countres';
    private $selectPhoto = 'select photos.idphoto, photos.addtimestamp, photos.idcatalog, photos.shortname, photos.place, photos.idcountry, 
									photos.fdate, photos.ftime, photos.heightpix, photos.widthpix, photos.idauthor, photos.description, 
									photos.photofile, photos.photofilemiddle, photos.photofilesmall, authors.surname, authors.name, countres.name as countryname 
							from photos 
							inner join authors 
								on photos.idauthor = authors.idauthor 
							inner join countres 
								on photos.idcountry = countres.idcountry 
							where';	
	private $update = 'update photos set idcatalog=?, shortname=?, place=?, idcountry=?, fdate=?, ftime=?, 
								heightpix=?, widthpix=?, idauthor=?, description=? where idphoto=?';
	private $insert = 'insert into photos (idcatalog, shortname, place, idcountry, fdate, ftime, 
								heightpix, widthpix, idauthor, description, photofile, photofilemiddle, photofilesmall) values (?,?,?,?,?,?,?,?,?,?,?,?,?)';								
    
    public function __construct($parameters)
    {
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

    private function validateUpdateCreate()
    {
		if ($this->checkEmpty($_POST['shortname']) !== 0) {
			$this->data['shortname'] = htmlspecialchars($this->checkEmpty($_POST['shortname']));
		} else {
			$this->data['shortname'] = '';
			$this->errorValidate .= 'Название не заполнено.<br>';
		}
		if ($this->checkEmpty($_POST['fdate']) == 0) {
			$this->errorValidate .= 'Дата не заполнена.<br>';
		}		
		if ($this->checkEmpty($_POST['ftime']) == 0) {
			$this->errorValidate .= 'Время не заполнено.<br>';
		}			
		return $this->errorValidate == ''; //true если не было ошибок
	}

    private function validateFile() //проверка файла и его уменьшение в 2 файла
    {
		if (!empty($_FILES['image']['tmp_name'])) {
			if (strtolower(mime_content_type($_FILES['image']['tmp_name'])) === 'image/jpeg') { //проверяем является файл изображением JPEG
				$file_ext = strtolower(strrchr($_FILES['image']['name'], '.')); // получаем расширение файла
				$file_name = uniqid(rand(9999, 100000)); // генерим уникальное имя
				$this->data['photofile'] = $file_name . $file_ext;
				$this->data['photofilemiddle'] = $file_name . '_middle' . $file_ext;
				$this->data['photofilesmall'] = $file_name . '_small' . $file_ext;
				
				$this->data['widthpix'] = getimagesize($_FILES['image']['tmp_name'])[0];
				$this->data['heightpix'] = getimagesize($_FILES['image']['tmp_name'])[1];				
				$percentmiddle = 0.35; //процент уменьшения 1-го изображения
				$percentsmall = 0.25; //процент уменьшения 2-го изображения
				//если изображение больше 1920 точек в ширину, то процент уменьшения корректируем в меньшую сторону
				if ((1920 / $this->data['widthpix']) < 0) { 
					$percentmiddle = (1920 / $this->data['widthpix']) * $percentmiddle;
					$percentsmall = (1920 / $this->data['widthpix']) * $percentsmall;
				}
				$image = imagecreatefromjpeg($_FILES['image']['tmp_name']);
				//создаем 2 изображения
				$image_middle = imagecreatetruecolor(round($this->data['widthpix'] * $percentmiddle), round($this->data['heightpix'] * $percentmiddle));
				$image_small = imagecreatetruecolor(round($this->data['widthpix'] * $percentsmall), round($this->data['heightpix'] * $percentsmall));
				imagecopyresampled($image_middle, $image, 0, 0, 0, 0, 
									round($this->data['widthpix'] * $percentmiddle), round($this->data['heightpix'] * $percentmiddle), 
									$this->data['widthpix'], $this->data['heightpix']);
				imagecopyresampled($image_small, $image, 0, 0, 0, 0, 
									round($this->data['widthpix'] * $percentsmall), round($this->data['heightpix'] * $percentsmall), 
									$this->data['widthpix'], $this->data['heightpix']);
				$url = str_replace('/', '\\', BASEURL);
				$dir = strstr(__DIR__, $url, true) . $url . 'catalog\\photos\\'; //определяем путь сохранения
				imagejpeg($image_middle, $dir . $this->data['photofilemiddle'], 100); //сохраняем 1-ое уменьшенное изображение
				imagejpeg($image_small, $dir . $this->data['photofilesmall'], 100); //сохраняем 2-ое уменьшенное изображение
				move_uploaded_file($_FILES['image']['tmp_name'], $dir . $this->data['photofile']); //сохраняем оригинальный файл
				imagedestroy ($image);
			} else {
				$this->errorValidate .= 'Файл не в формате jpeg или не является изображением.<br>';
			}
		} else {
			$this->errorValidate .= 'Файл не выбран.<br>';
		}
		return $this->errorValidate == ''; //true если не было ошибок
	}

    public function photoRead()
    {
		if (($_SERVER['REQUEST_METHOD'] == 'POST') && (count($_POST) <> 0)) {
			$sql = $this->selectPhoto . ' photos.idphoto=?';
			$this->dataPhoto = \core\db::select($sql, [$_POST['idPhotoFound']]);
			if (count($this->dataPhoto) == 1) {
				$this->dataCatalog = \core\db::select($this->selectCatalog);
				$this->dataAuthor = \core\db::select($this->selectAuthor);
				$this->dataCountry = \core\db::select($this->selectCountry);
			} else {
				$this->error = 'Данные не найдены.<br>';
			}
		} else {
			$this->error = 'Не удалось передать данные из формы.<br>';
		}
    }

	public function photoUpdate()
    {
		if (($_SERVER['REQUEST_METHOD'] == 'POST') && (count($_POST) <> 0)) { //Проверка получения данных
			//var_dump($_POST);
			//var_dump($_FILES);
			if ($this->validateUpdateCreate()) { //Проверка данных
				\Core\Db::exec($this->update, [$_POST['idcatalog'], $this->data['shortname'], $_POST['place'], $_POST['idcountry'], 
												$_POST['fdate'], $_POST['ftime'], $_POST['heightpix'], $_POST['widthpix'], 
												$_POST['idauthor'], $_POST['description'], $_POST['idphoto']]);
				$this->errorValidate = 'Данные успешно изменены.<br>';
			} 
			//Возврат в форму.
			$this->dataPhoto = [0 => ['idphoto' => $_POST['idphoto'], 'addtimestamp' => $_POST['addtimestamp'], 
										'photofile' => $_POST['photofile'], 'photofilesmall' => $_POST['photofilesmall'], 
										'idcatalog' => $_POST['idcatalog'], 'shortname' => $_POST['shortname'], 
										'place' => $_POST['place'], 'idcountry' => $_POST['idcountry'], 'fdate' => $_POST['fdate'], 
										'ftime' => $_POST['ftime'], 'heightpix' => $_POST['heightpix'], 'widthpix' => $_POST['widthpix'], 
										'idauthor' => $_POST['idauthor'], 'description' => $_POST['description']]];
			$this->dataCatalog = \core\db::select($this->selectCatalog);
			$this->dataAuthor = \core\db::select($this->selectAuthor);
			$this->dataCountry = \core\db::select($this->selectCountry);
		} else {
			$this->error = 'Не удалось передать данные из формы.<br>';
		}
	}

	public function photoCreate()
    {
		$this->createfunc = true; //переменная пеередается во view
		if (($_SERVER['REQUEST_METHOD'] == 'POST') && (count($_POST) == 0)) { //Проверка получения данных
			//Отправляем в форму пустые данные.
			$this->dataPhoto = [0 => ['idphoto' => 0, 'addtimestamp' => 0, 
										'photofile' => '', 'photofilesmall' => '', 
										'idcatalog' => '', 'shortname' => '', 
										'place' => '', 'idcountry' => 0, 'fdate' => 0, 
										'ftime' => 0, 'heightpix' => 0, 'widthpix' => 0, 
										'idauthor' => 0, 'description' => '']];
			$this->dataCatalog = \core\db::select($this->selectCatalog);
			$this->dataAuthor = \core\db::select($this->selectAuthor);
			$this->dataCountry = \core\db::select($this->selectCountry);
		} else{
			if ($this->validateUpdateCreate()) { //Проверка данных
				if ($this->validateFile()) { //Проверка файла
					\Core\Db::exec($this->insert, [$_POST['idcatalog'], $this->data['shortname'], $_POST['place'], $_POST['idcountry'], 
													$_POST['fdate'], $_POST['ftime'], $this->data['heightpix'], $this->data['widthpix'], 
													$_POST['idauthor'], $_POST['description'], $this->data['photofile'], $this->data['photofilemiddle'], 
													$this->data['photofilesmall']]);
					$this->errorValidate = 'Данные успешно добавлены.<br>';
				}
			}
		//Возврат в форму.
		$this->dataPhoto = [0 => ['idphoto' => $_POST['idphoto'], 'addtimestamp' => $_POST['addtimestamp'], 
									'photofile' => $_POST['photofile'], 'photofilesmall' => $_POST['photofilesmall'], 
									'idcatalog' => $_POST['idcatalog'], 'shortname' => $_POST['shortname'], 
									'place' => $_POST['place'], 'idcountry' => $_POST['idcountry'], 'fdate' => $_POST['fdate'], 
									'ftime' => $_POST['ftime'], 'heightpix' => $_POST['heightpix'], 'widthpix' => $_POST['widthpix'], 
									'idauthor' => $_POST['idauthor'], 'description' => $_POST['description']]];
		$this->dataCatalog = \core\db::select($this->selectCatalog);
		$this->dataAuthor = \core\db::select($this->selectAuthor);
		$this->dataCountry = \core\db::select($this->selectCountry);
		}
	}

    public function getError() {
        return $this->error;
    }

    public function getErrorValidate() {
        return $this->errorValidate;
    }
	
    public function getCreatefunc() {
        return $this->createfunc;
    }

    public function getData($data) {
        switch ($data) {
			case 'dataCatalog':
				return $this->dataCatalog;
				break;
			case 'dataAuthor':
				return $this->dataAuthor;
				break;
			case 'dataPhoto':
				return $this->dataPhoto;
				break;
			case 'dataCountry':
				return $this->dataCountry;
				break;
		}
    }
}
