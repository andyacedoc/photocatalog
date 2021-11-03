<!--menubar-->
<nav class="navbar navbar-expand-lg navbar sticky-top navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?=BASEURL?>">PhotoCatalog</a> <!--главная страница-->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Изображения</a>
          <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
			<?=$htmlRootMenu?> <!--корневое меню-->
		  </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Видео</a>
          <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
			<li><a class="dropdown-item" href="#">Находится в разработке</a></li>
		  </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">О проекте</a>
        </li>	  
        <li class="nav-item dropdown">
			<?=$htmlAdminMenu?> <!--меню админа-->
        </li>
	  </ul>
      <form class="d-flex" style="margin-top : 1%;">
        <input class="form-control me-2" type="search" placeholder="Поиск" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Поиск</button>
      </form>
	  &nbsp; &nbsp; 
	  <ul class="navbar-nav ">
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="offcanvas" id="enter" href="#logreg"><?=$namenik?></a> <!--вход/регистрация-->
        </li>
	  </ul>
    </div>
  </div>
</nav>



