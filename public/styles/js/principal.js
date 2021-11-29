
function Ingresar_recibo() {
  event.preventDefault();
  var formulario = document.forms["form_recibo"];
  var datos = new FormData();
  datos.append("select_cobro", formulario["select_cobro"].value);
  datos.append("select_pago", formulario["select_pago"].value);
  datos.append("socio", formulario["socio"].value);
  datos.append("Concepto", formulario["Concepto"].value);
  datos.append("Moneda", formulario["Moneda"].value);
  datos.append("ingreso", formulario["ingreso"].value);
  datos.append("n_cuenta", formulario["n_cuenta"].value);
  $.ajax({
    processData: false,
    contentType: false,
    url: "ingresar_recibo",
    type: "POST",
    data: datos,
    success: function (response) {
      console.log(response);
      if (response == "1") {
        alert("Se ingreso el recibo");
        location.reload();
      } else {
        alert("Error al ingresar el recibo", response);
      }
    },
    error: function (response) {
      console.log("response:" + JSON.stringify(response));
    },
  });
}




const form = document.getElementById("form_recibo");
form.addEventListener("submit", Ingresar_recibo);