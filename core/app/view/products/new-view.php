<div class="row">
  <div class="col-md-12">
    <h1>Nuevo Producto/Medicamento</h1>
    <small>Por favor agrega la clave del producto registrada por el SAT</small><br>
    <small>Si no conoces la cleve da click <a href="https://www.sat.gob.mx/consultas/53693/catalogo-de-productos-y-servicios">AQUI</a></small>
      
    <div id="result"></div>
    <br>
    <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="index.php?action=products/add" role="form" autocomplete="off">
      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Descripción*</label>
        <div class="col-md-6">
          <input type="text" name="description" required class="form-control" placeholder="Descripción del Producto" />
        </div>
      </div>
    <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Clave del Producto*</label>
        <div class="col-md-6">
          <input type="text" name="product_key" required class="form-control" placeholder="Clave del Producto" />
        </div>
      </div>
          <div class="form-group">
        <label for="unit" class="col-lg-2 control-label">Unidad de medida*</label>
        <div class="col-md-6">
            <select name="unit" required class="form-control" placeholder="Unidad de medida">
                <option value="pieza">Pieza</option>
                <option value="kg">Kilogramo (kg)</option>
                <option value="L">Litro (L)</option>
                <option value="caja">Caja</option>
                <option value="paquete">Paquete</option>
                <!-- Agrega otras opciones según sea necesario -->
            </select>
        </div>
    </div>

      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Precio de Salida*</label>
        <div class="col-md-6">
          <input type="number" step=".01" min="0" name="priceOut" required class="form-control" id="priceOut" placeholder="Precio de salida">
        </div>
      </div>

      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Precio de Entrada*</label>
        <div class="col-md-6">
          <input type="number" step=".01" min="0" name="priceIn" required class="form-control" placeholder="Precio de entrada">
        </div>
      </div>




      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Mínima en inventario:</label>
        <div class="col-md-6">
          <input type="number" step=".1" min="0" value="10" name="minimumInventory" class="form-control" placeholder="Mínimo en Inventario (Predeterminada 10)">
        </div>
      </div>

      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Inventario inicial:*</label>
        <div class="col-md-6">
          <input type="number" step=".01" min="0" name="initialInventory" class="form-control" placeholder="Inventario inicial" required>
        </div>
      </div>

      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Fecha expiración inventario inicial:*</label>
        <div class="col-md-6">
          <input type="date" min="<?php echo date('Y-m-d'); ?>" name="expirationDate" class="form-control" placeholder="Fecha expiración inventario inicial" required>
        </div>
      </div>

      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Lote inventario inicial:*</label>
        <div class="col-md-6">
          <input type="text" name="lot" class="form-control" placeholder="Lote inventario inicial" required>
        </div>
      </div>

      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Fracción (Medicamento):</label>
        <div class="col-md-6">
          <input type="text" name="fraction" class="form-control" placeholder="Fracción(Medicamento)">
        </div>
      </div>

      <!-- Agregar otros campos si es necesario -->

      <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
          <button type="submit" class="btn btn-primary">Agregar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  $(document).ready(function() {
    $("#barcode").keydown(function(e) {
      if (e.which == 17 || e.which == 74) {
        e.preventDefault();
      } else {
        console.log(e.which);
      }
    });

    //comprobamos si se pulsa una tecla
    $("#name").keyup(function(e) {
      //obtenemos el texto introducido en el campo
      name = $("#name").val();
      //hace la búsqueda
      $("#result").delay(1000).queue(function(n) {
        $("#result").html();
        $.ajax({
          type: "POST",
          url: "./?action=products/validate-name",
          data: "name=" + name,
          dataType: "html",
          error: function() {
            alert("Ha ocurrido un error.");
          },
          success: function(data) {
            $("#result").html(data);
            if (data == "") {
              $("#productDetails").show();
            } else {
              $("#productDetails").hide();
            }
          }
        });

      });

    });

    //hacemos focus
    $("#barcode").focus();
    //comprobamos si se pulsa una tecla
    $("#barcode").keyup(function(e) {
      //obtenemos el texto introducido en el campo
      barcode = $("#barcode").val();
      //hace la búsqueda
      $("#result").delay(1000).queue(function(n) {

        $("#result").html();
        $.ajax({
          type: "POST",
          url: "./?action=products/validate-barcode",
          data: "barcode=" + barcode,
          dataType: "html",
          error: function() {
            alert("Ha ocurrido un error.");
          },
          success: function(data) {
            $("#result").html(data);
            if (data == "") {
              $("#productDetails").show();
            } else {
              $("#productDetails").hide();
            }
          }
        });

      });

    });
  });
</script>
