<!--Edit window-->
<div class="modal fade" id="editphoto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Редактирование</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
		 <form name="edit_form">
			<div id="edit" class="row row-cols-2">
				<?=$htmlEditForm?> <!--вывод формы-->
			</div>
		 </form>
      </div>
      <!--div class="modal-footer">
		<a class="btn btn-secondary" role="button" href="">Изменить</a>
		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
      </div-->
    </div>
  </div>
</div>




