<!--Login-Registration-Update window-->
<div class="offcanvas offcanvas-end" tabindex="-1" id="logreg" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasRightLabel"><?=$submitvalue?></h5> <!--заголовок-->
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
     <form name="logreg_form">
		<div id="inreg">
			<?=$htmlLogRegForm?> <!--вывод формы-->
		</div>
     </form>
  </div>
</div>


