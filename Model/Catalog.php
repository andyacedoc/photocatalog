<?php
namespace Model;

class Catalog {
    private $pageNumber;
    private $idCatalog;
	private $idPhoto;
    private $pagesCount = 1;
	private $error = '';
    private $dataRootCatalog;
	private $dataSubCatalog;
	private $dataPhoto;
	private $dataParentCatalog;
    
    private $select = 'select idcatalog, idparent, name, imagefile 
						from catalog where idparent=?';
    private $selectPhoto = 'select photos.idphoto, photos.idcatalog, photos.idauthor, photos.idcountry, photos.shortname, photos.description,
									photos.place, photos.fdate, photos.ftime, photos.heightpix, photos.widthpix, photos.photofile,
									photos.photofilemiddle, photos.photofilesmall, photos.addtimestamp, authors.name, authors.surname, countres.name as countryname 
							from photos 
							inner join authors 
								on photos.idauthor = authors.idauthor 
							inner join countres 
								on photos.idcountry = countres.idcountry 	
							where';						
	private $selectParent = 'with recursive cte (idcatalog, idparent, name, imagefile) as (
								  select     idcatalog,
											 idparent,
											 name,
											 imagefile
								  from       catalog
								  WHERE      idcatalog=?
								  union all
								  select     p.idcatalog,
											 p.idparent,
											 p.name,
											 p.imagefile
								  from       catalog p
								  inner join cte
										  on p.idcatalog = cte.idparent
								)
								SELECT idcatalog, idparent, name, imagefile FROM cte ORDER BY idcatalog ASC;';
    private $count = 'select count(*) from photos where idcatalog=?';
    
    public function __construct($parameters)
    {
		if (empty($parameters[2])) $this->idCatalog = 0; else $this->idCatalog = $parameters[2];
		if (empty($parameters[3])) $this->idPhoto = 0; else $this->idPhoto = $parameters[3];
        if (empty($parameters[4])) $this->pageNumber = 1; else $this->pageNumber = $parameters[4];
    }

    public function read()
    {
		$this->dataRootCatalog = \core\db::select($this->select, [0]);
		if (($_SERVER['REQUEST_METHOD'] == 'POST') && (count($_POST) <> 0)) { //обработка запроса по поиску
			$sql = $this->selectPhoto . ' MATCH (photos.shortname, photos.place, photos.description) AGAINST (? IN NATURAL LANGUAGE MODE)';
			$this->dataPhoto = \core\db::select($sql, [$_POST['searchtext']]);
			if (count($this->dataPhoto) == 0) {
				$this->error = 'Фотографии по поиску "' . $_POST['searchtext'] . '" не найдены.<br>';
			} else {
				$this->error = 'Найдено по поиску "' . $_POST['searchtext'] . '"<br><br>';
			}
		} else { //иначе обычная обработка вывода фотографий
			if ($this->idCatalog !== 0) {
				if ($this->idPhoto !== 0) {
					$sql = $this->selectPhoto . ' photos.idphoto=?';
					$this->dataPhoto = \core\db::select($sql, [$this->idPhoto]);
				} else {				
					$this->dataSubCatalog = \core\db::select($this->select, [$this->idCatalog]);
					if (empty($this->dataSubCatalog)) {
					//рассчитываем колличество страниц для вывода
						$this->pagesCount = ceil(\core\db::count($this->count, [$this->idCatalog]) / DATA_PER_PAGE);
						if ($this->pagesCount < 1) $this->pagesCount = 1;
						$first = ($this->pageNumber - 1) * DATA_PER_PAGE;
					//выбираем рассчитанное количество фотографий на страницу
						$sql = $this->selectPhoto . ' photos.idcatalog=?' . ' limit ' . $first . ',' . DATA_PER_PAGE;
						$this->dataPhoto = \core\db::select($sql, [$this->idCatalog]);
					}
				}
			$this->dataParentCatalog = \core\db::select($this->selectParent, [$this->idCatalog]);
			}		
		}
	}

    public function getError() {
        return $this->error;
    }   
  
    public function getPageNumber() {
        return $this->pageNumber;
    }

    public function getIdCatalog() {
        return $this->idCatalog;
    }

    public function getIdPhoto() {
        return $this->idPhoto;
    }

    public function getPagesCount() {
        return $this->pagesCount;
    }

    public function getData($data) {
        switch ($data) {
			case 'dataRootCatalog':
				return $this->dataRootCatalog;
				break;
			case 'dataSubCatalog':
				return $this->dataSubCatalog;
				break;			
			case 'dataPhoto':
				return $this->dataPhoto;
				break;		
			case 'dataParentCatalog':
				return $this->dataParentCatalog;
				break;			
		}
    }
}





    /*public function read()
    {
		$this->dataRootCatalog = \core\db::select($this->select, [0]);
		if ($this->idCatalog !== 0) {
			if ($this->idPhoto !== 0) {
				$sql = $this->selectPhoto . ' photos.idphoto=?';
				$this->dataPhoto = \core\db::select($sql, [$this->idPhoto]);
			} else {				
				$this->dataSubCatalog = \core\db::select($this->select, [$this->idCatalog]);
				if (empty($this->dataSubCatalog)) {
				//рассчитываем колличество страниц для вывода
					$this->pagesCount = ceil(\core\db::count($this->count, [$this->idCatalog]) / DATA_PER_PAGE);
					if ($this->pagesCount < 1) $this->pagesCount = 1;
					$first = ($this->pageNumber - 1) * DATA_PER_PAGE;
				//выбираем рассчитанное количество фотографий на страницу
					$sql = $this->selectPhoto . ' photos.idcatalog=?' . ' limit ' . $first . ',' . DATA_PER_PAGE;
					$this->dataPhoto = \core\db::select($sql, [$this->idCatalog]);
				}
			}
		$this->dataParentCatalog = \core\db::select($this->selectParent, [$this->idCatalog]);
		}		
    }*/