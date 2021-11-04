<?php
namespace View;

class Edit {
    private $model;
    
    public function __construct($model)
    {
        $this->model = $model;
    }

    private function formRead()
    {
		If ($this->model->getCreatefunc()) { //показываем или скрываем кнопки на форме
			$button = 'hidden';
			$filef = 'file';
			$commentstart = '<!--';
			$commentend = '-->';
		} else {
			$button = 'button';
			$filef = 'hidden';
			$commentstart = '';
			$commentend = '';			
		}
		$urlRead = BASEURL . 'edit/photoread/';
		$urlCreate = BASEURL . 'edit/photocreate/';
		$formssite = '';
		$htmlEditForm = '<div class="col-12">'
						. '<p>Введите идентификатор фотографии <input type="text" size="7" maxlength="50" id="idPhotoFound" name="idPhotoFound" value=""> '
						. '<input type="button" name="button" value="Найти" onclick="sendRequestPhoto(\'' . $urlRead . '\', \'\', \'' . $formssite . '\');"> '
						. '<span style="float:right;">Добавить фотографию <input type="button" name="button" value="Добавить" onclick="sendRequestPhoto(\'' . $urlCreate . '\', \'\', \'' . $formssite . '\');"></span></p></div>';	
		if ($this->model->getError() && !($this->model->getErrorValidate())) {
			echo '<b>' . $this->model->getError() . '</b>' . $htmlEditForm;
        } else {
			echo '<b>' . $this->model->getErrorValidate() . '</b>';
			$urlUpdate = BASEURL . 'edit/photoupdate/';
			$urlDelete = BASEURL . 'edit/photodelete/';
			$dataCatalog = $this->model->getData('dataCatalog');
			$dataAuthor = $this->model->getData('dataAuthor');
			$dataCountry = $this->model->getData('dataCountry');
			$dataPhoto = $this->model->getData('dataPhoto');
			$htmlEditForm .= '<div class="col-12">'
							. '<input type="' . $button . '" name="button" value="Изменить" onclick="sendRequestPhoto(\'' . $urlUpdate . '\', \'\', \'' . $formssite . '\');"> '
							. '<input type="' . $button . '" name="button" value="Удалить" onclick="sendRequestPhoto(\'' . $urlDelete . '\', \'\', \'' . $formssite . '\');"> '
							. '<input type="button" name="button" value="Оменить" onclick="location.reload();"><br><br></div>'
							. '<div class="col-4">'
							. $commentstart . '<img src="' . BASEURL . 'catalog/photos/' . $dataPhoto[0]['photofilesmall'] . '" class="img-thumbnail" alt="here image">' . $commentend . '</div>'
							. '<div class="col">'
							. '<input type="hidden" size="7" maxlength="50" name="photofilesmall" value="' . $dataPhoto[0]['photofilesmall'] . '">'
							. '<input type="hidden" size="7" maxlength="50" name="photofile" value="' . $dataPhoto[0]['photofile'] . '">'
							. '<p>Идентификатор фотографии <input readonly type="text" size="7" maxlength="50" name="idphoto" value="' . $dataPhoto[0]['idphoto'] . '"></p>' 
							. '<p>Дата добавления в каталог <input readonly type="text" size="16" maxlength="50" name="addtimestamp" value="' . $dataPhoto[0]['addtimestamp'] . '"></p>' 
							. '<p>Файл изображения: ' . $dataPhoto[0]['photofile'] . ' <input type="' . $filef . '" name="image"></p></div>'
							. '<div class="col-6">'
							. '<p>Раздел каталога <select name="idcatalog">';
			foreach ($dataCatalog as $valueoption) { //формируем список разделов каталогов
					if ($valueoption['idcatalog'] == $dataPhoto[0]['idcatalog']) {
						$htmlEditForm .= '<option selected value="' . $valueoption['idcatalog'] . '">' . $valueoption['name'] . '</option>';
					} else {
						$htmlEditForm .= '<option value="' . $valueoption['idcatalog'] . '">' . $valueoption['name'] . '</option>';
					}
			}
			$htmlEditForm .= '</select></p>'
								. '<p>Название <input type="text" size="30" maxlength="50" name="shortname" value="' . $dataPhoto[0]['shortname'] . '"></p>'
								. '<p>Место съемки <input type="text" size="25" maxlength="50" name="place" value="' . $dataPhoto[0]['place'] . '"></p>'
								. '<p>Страна съемки <select name="idcountry">';
			foreach ($dataCountry as $valueoption) { //формируем список стран
					if ($valueoption['idcountry'] == $dataPhoto[0]['idcountry']) {
						$htmlEditForm .= '<option selected value="' . $valueoption['idcountry'] . '">' . $valueoption['name'] . '</option>';
					} else {
						$htmlEditForm .= '<option value="' . $valueoption['idcountry'] . '">' . $valueoption['name'] . '</option>';
					}
			}			
			$htmlEditForm .= '</select></p>'
								. '<p>Дата <input type="date" size="30" maxlength="50" name="fdate" value="' . $dataPhoto[0]['fdate'] . '"></p>'
								. '<p>Время <input type="time" size="30" maxlength="50" name="ftime" value="' . $dataPhoto[0]['ftime'] . '"></p></div>'
								. '<div class="col-6">'
								. '<p>Автор <select name="idauthor">';
			foreach ($dataAuthor as $valueoption) { //формируем список авторов
					if ($valueoption['idauthor'] == $dataPhoto[0]['idauthor']) {
						$htmlEditForm .= '<option selected value="' . $valueoption['idauthor'] . '">' . $valueoption['surname'] . ' ' . $valueoption['name'] . '</option>';
					} else {
						$htmlEditForm .= '<option value="' . $valueoption['idauthor'] . '">'. $valueoption['surname'] . ' ' . $valueoption['name'] . '</option>';
					}
			}									
			$htmlEditForm .= '</select><p>'
								. '<p>Описание <textarea cols="40" rows="3" name="description">' . $dataPhoto[0]['description'] . '</textarea></p>'
								. '<p>Высота (pix) <input readonly type="text" size="7" maxlength="50" name="heightpix" value="' . $dataPhoto[0]['heightpix'] . '"></p>'
								. '<p>Ширина (pix) <input readonly type="text" size="7" maxlength="50" name="widthpix" value="' . $dataPhoto[0]['widthpix'] . '"></p></div>';

			echo $htmlEditForm;
		}
	}

	public function photoRead()
    {
		$this->formRead();
		
	}
	public function photoUpdate()
    {
		$this->formRead();
		
	}

	public function photoCreate()
    {
		$this->formRead();
		
	}

}
