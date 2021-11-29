function confirmar() {

    event.preventDefault();

    var formulario = document.forms['loginForm'];

    const usuario = formulario["usuario"].value;

    const contraseña = formulario["contraseña"].value;


    confirmar_2(usuario, contraseña);

}



function confirmar_2(usuario, contraseña) {

    var datos = new FormData();

    datos.append("nombre", usuario);

    datos.append("contraseña", contraseña);

    $.ajax({

        processData: false,

        contentType: false,

        url: "login",

        type: "post",

        data: datos,

        success: function (response) {
            if (response != "0") {
                localStorage.setItem('cooperativa_id', jQuery.parseJSON(response)[0]["ID"]);
                localStorage.setItem('cooperativa_nombre', jQuery.parseJSON(response)[0]["Nombre"]);
                window.location.href = "/Nueva_pagina/public/inicio";

            }
            else {
                alert("El usuario o contraseña es incorrecto");
                document.getElementById("contrasena").value = "";
                document.getElementById("contrasena").focus();
            }
        },

        error: function (xhr) {

            console.log("no funciona" + xhr);

        }

    });



}

const form = document.getElementById("loginForm");
form.addEventListener("submit", confirmar);

