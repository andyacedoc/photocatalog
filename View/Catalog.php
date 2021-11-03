<?php
namespace View;

class Catalog {
    private $model;
	
    
    public function __construct($model)
    {
        $this->model = $model;
    }

    private function logRegForm() //первоначальный вывод формы "Вход/Регистрация" на страницу
	{
		if (!isset($_SESSION['email'])) {
			$urlin = BASEURL . 'logreg/in/';
			$urlreg = BASEURL . 'logreg/reg/';
			$htmlLogRegForm = '<h5>Вход</h5><input type="hidden" size="30" maxlength="50" id="namenik" name="namenik" value=""><br>
								Логин (e-mail):<br>
								<input type="text" size="30" maxlength="50" id="email" name="email" value=""><br>
								<br>Пароль:<br>
								<input type="password" size="30" maxlength="50" id="password" name="password" value=""><br>
								<input type="hidden" name="info" value="hiddenguestbook"><br>
								<input type="button" name="button" value="Войти" onclick="sendRequest(\'' . $urlin. '\');">
								<input type="button" name="button" value="Регистрация" onclick="sendRequest(\'' . $urlreg . '\', \'emptypost\');">';
		} else {
			$urlread = BASEURL . 'logreg/read/';
			$urlout = BASEURL . 'logreg/out/';
			$htmlLogRegForm = '<input type="button" name="button" value="Сведения об аккаунте" onclick="sendRequest(\'' . $urlread. '\');"><br><br>
								<input type="button" name="button" value="Выход" onclick="sendRequest(\'' . $urlout . '\');">';
		}
	return $htmlLogRegForm;
	}

    private function editForm() //первоначальный вывод формы редактирования данных фотографии на страницу
	{
			$urlRead = BASEURL . 'edit/photoread/';
			$urlCreate = BASEURL . 'edit/photocreate/';
			$formssite = '';
			$htmlEditForm = '<div class="col-12">Введите идентификатор фотографии 
							<input type="text" size="7" maxlength="50" id="idPhotoFound" name="idPhotoFound" value="">
							<input type="button" name="button" value="Найти" onclick="sendRequestPhoto(\'' . $urlRead . '\', \'\', \'' . $formssite . '\');"> 
							<input type="button" name="button" value="Добавить" onclick="sendRequestPhoto(\'' . $urlCreate . '\', \'emptypost\', \'' . $formssite . '\');"></p></div>';	
	return $htmlEditForm;
	}

	public function read()
    {
		$Idcatalog = $this->model->getIdcatalog();
		$IdPhoto = $this->model->getIdPhoto();
		
		$htmlRootMenu = ''; //формирование корневого меню каталога 'Изображения' на странице menubar.php
        foreach ($this->model->getData('dataRootCatalog') as $catalog) {
			$htmlRootMenu .= '<li><a class="dropdown-item" href="' . BASEURL . 'catalog/read/' 
																	. $catalog['idcatalog'] .  '">' 
																	. $catalog['name'] . '</a></li>';
        }
		
		$htmlAdminMenu = '';
		if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin')) { //Проверка открытой сессии и вывод меню для админа на странице menubar.php
			$htmlAdminMenu = '<a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Редактирование</a>
								<ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
									<li><a class="dropdown-item" href="' . BASEURL . 'edit/catalog/'
																	. $Idcatalog . '/'
																	. $IdPhoto . '">Структура каталога</a></li>
									<li><a class="dropdown-item" data-bs-toggle="modal" href="#editphoto">Фотографии</a></li>								
									<li><a class="dropdown-item" href="' . BASEURL . 'edit/user/' . '">Пользователи</a></li>									
								</ul>';	
		}

		if (isset($_SESSION['email'])) { //Проверка открытой сессии и передача имени юзера и заголовка формы на страницу
			$namenik = $_SESSION['namenik']; //имя юзера на страницу menubar.php
			$submitvalue = 'Здравствуйте, ' . $_SESSION['namenik'] . '!'; //заголовок в форму logregform.php
		} else {
			$namenik = 'Войти / Регистрация'; //вывод приглашения на страницу menubar.php
			$submitvalue = 'Войти / Регистрация'; //заголовок в форму logregform.php
		}
		
		$htmlLogRegForm = $this->logRegForm();
		$htmlEditForm = $this->editForm();
		include 'view/header.php';
		include 'view/menubar.php';
		include 'view/logregform.php';
		include 'view/editform.php';
		if ($Idcatalog === 0) include 'view/carousel.php';
		
		if ($Idcatalog !== 0) {
			//формирование пути дерева каталога
			$htmlParentWay = '&nbsp; &nbsp;';
			foreach ($this->model->getData('dataParentCatalog') as $catalog) {
				$htmlParentWay .= '<a href="' . BASEURL . 'catalog/read/' 
												. $catalog['idcatalog'] .  '">' 
												. $catalog['name'] . '&nbsp;/&nbsp;</a>';
			}	
			echo '<br>' . $htmlParentWay . '<br><br>';
	
			$htmlRender = '';		
			if ($IdPhoto !== 0) {
				if (!empty($this->model->getData('dataPhoto'))) {
					//вывод одной фотографии
					$countCol = 2;
					foreach ($this->model->getData('dataPhoto') as $catalog) {
						$htmlRender .= '<div class="col">
											<img src="' . BASEURL . 'catalog/photos/' . $catalog['photofilemiddle'] . '" class="figure-img img-fluid rounded" alt="here image">
										</div>
										<div class="col">'
											. '<i>ID#:</i> ' . $catalog['idphoto']
											. '<br><i>Название:</i> ' . $catalog['shortname']
											. '<br><i>Место съемки:</i> ' . $catalog['place']
											. '<br><i>Страна:</i> ' . $catalog['countryname']
											. '<br><i>Дата:</i> ' . $catalog['fdate']
											. '<br><i>Время:</i> ' . $catalog['ftime']
											. '<br><i>Размер:</i> ' . $catalog['widthpix'] . ' x ' . $catalog['heightpix']
											. '<br><i>Автор:</i> ' . $catalog['name'] . ' ' . $catalog['surname']
											. '<br><i>Описание:</i> ' . $catalog['description'] .
										'</div>';
					}									
					include 'view/render.php';			
				} else {
					echo '<h5>&nbsp; &nbsp;Фотография не найдена.</h5>';
				}
			} else {				

				$htmlRender = '';
				if (!empty($this->model->getData('dataSubCatalog'))){
					//вывод содержимого подкаталога
					$countCol = 5;
					foreach ($this->model->getData('dataSubCatalog') as $catalog) {
						$htmlRender .= '<div class="col"> <figure class="figure">
											<a href="' . BASEURL . 'catalog/read/'
														. $catalog['idcatalog'] . '">
												<img src="' . BASEURL . 'catalog/imagecatalog/' . $catalog['imagefile'] . '" class="figure-img img-fluid rounded" alt="here image">
												<figcaption class="figure-caption"><b>' . $catalog['name'] . '.</b></figcaption>
											</a>
										</figure> </div>';
					}
					include 'view/render.php';
				} else {
					
					$htmlRender = '';
					if (!empty($this->model->getData('dataPhoto'))) {
						//вывод фотографий
						$countCol = 3;
						//$n = 1;
						foreach ($this->model->getData('dataPhoto') as $catalog) {
							$htmlRender .= '<div class="col"> <figure class="figure">
											<a href="' . BASEURL . 'catalog/read/'
														. $catalog['idcatalog'] . '/'
														. $catalog['idphoto'] . '">
												<img src="' . BASEURL . 'catalog/photos/' . $catalog['photofilesmall'] . '" class="figure-img img-fluid rounded" alt="here image">
												<figcaption class="figure-caption"><b>ID#: ' . $catalog['idphoto'] . '</b></figcaption>
											</a>
										</figure> </div>';
							//$n++;
						}									
						include 'view/render.php';
						//вывод номеров страниц
						$pageNumber = $this->model->getPageNumber();
						$pageCount = $this->model->getPagesCount();
						echo '<nav aria-label="Page navigation example">
								<ul class="pagination pagination-sm">';
						for ($i = 1; $i <= $pageCount; $i++) {
							if ($i == $pageNumber) {
								echo '<li class="page-item active" aria-current="page">
								<span class="page-link">' . $i . '</span></li>';
							} else {
								echo '<li class="page-item"><a class="page-link" href="' . BASEURL . 
										'catalog/read/'. $Idcatalog. '/0/' . $i . '">' . $i . '</a></li>';
							}
						}
						 echo '</ul></nav>';
					} else {
						echo '<h5>&nbsp; &nbsp;Фотографии в раздел не загружались.</h5>';
					}
				}
			}	
		}
		include 'view/footer.php';
    }
}
