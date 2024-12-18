<div class="row">
	<div class="col-md-12">
		<div class="btn-group  pull-right"><a href="index.php?view=expense-categories/new" class="btn btn-default"><i class="fas fa-plus"></i> Agregar Categoría</a>

		</div>
		<h1>Lista de categorías gastos</h1>
		<div class="clearfix"></div>
		<?php
		$categories = ExpenseCategoryData::getAll();
		if ($categories && count($categories) > 0) {
			// si hay usuarios
		?>

			<table class="table table-bordered table-hover">
				<thead>
					<th>Nombre</th>
					<th style="width:80px;"></th>
				</thead>
				<?php
				foreach ($categories as $category) {
				?>
					<tr>
						<td><?php echo $category->name; ?></td>
						<td style="width:80px;" class="td-actions">
							<a href="index.php?view=expense-categories/edit&id=<?php echo $category->id; ?>" rel="tooltip" title="Editar" class="btn btn-simple btn-warning btn-xs"><i class='fas fa-pencil-alt'></i></a>
							<a href='index.php?action=expense-categories/update-status&id=<?php echo $category->id ?>&isActive=0' class='btn btn-danger btn-xs' onclick='return confirmDelete()'><i class="fas fa-trash"></i></a>
					</tr>
				<?php

				}
				?>
			</table>
		<?php


		} else {
			echo "<p class='alert alert-danger'>No hay categorías</p>";
		}


		?>
		</table>
	</div>
</div>
</div>
</div>
<script type="text/javascript">
	function confirmDelete() {
		var flag = confirm("¿Seguro que deseas eliminar la categoría?");
		if (flag == true) {
			return true;
		} else {
			return false;
		}
	}
</script>