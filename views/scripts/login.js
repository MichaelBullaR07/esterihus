$("#frmAcceso").on('submit', function(e) {
    e.preventDefault();
    logina = $("#logina").val();
    clavea = $("#clavea").val();

    $.post("../controllers/usuario.php?op=verificar", {
        "logina": logina,
        "clavea": clavea
    }, function(data) {
        if (data != "null") {
            // Parsear el JSON para obtener el rol
            var usuario = JSON.parse(data);
            if (usuario.rol === "5") {
                $(location).attr("href", "persona");
            } else {
                $(location).attr("href", "dashboard");
            }
        } else {
            bootbox.alert("Usuario y/o Contraseña incorrectos");
        }
    });
});