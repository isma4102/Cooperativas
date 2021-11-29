function inicio(){
    $.ajax({
        url: "Producto/BuscarNoticia",
        type: "get",
        dataType: "json",
        success: function (response) {
            alert(response);
        },
        error: function (xhr) {
            console.log("no func" + xhr);
        }
    });
}