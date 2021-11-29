

function Ingresar_socio() {
  event.preventDefault();
  var formulario = document.forms["form_socio"];
  var datos = new FormData();
  datos.append("nombre", formulario["nombre_socio"].value);
  datos.append("apellido", formulario["apellido_socio"].value);
  datos.append("telefono", formulario["telefono_socio"].value);
  $.ajax({
    processData: false,
    contentType: false,
    url: "ingresar_socio",
    type: "POST",
    data: datos,
    success: function (response) {
      console.log(response);
      if (response == "1") {
        alert("Se ingreso el socio");
        location.reload();
      } else {
        alert("Error al ingresar el socio", response);
      }
    },
    error: function (response) {
      console.log("response:" + JSON.stringify(response));
    },
  });
}



const form2 = document.getElementById("form_socio");
form2.addEventListener("submit", Ingresar_socio);