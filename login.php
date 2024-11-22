<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>EsteriHUS</title>
    <link href="public/img/icologo.png" rel="shortcut icon"><!-- ESTE ES EL ICONO DE LA PLANTILLA -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/font-awesome.min.css">
    <link rel="stylesheet" href="public/css/_all-skins.min.css">
    <link rel="stylesheet" href="public/css/styles.css">
    <script src="public/js/jquery.min.js"></script>
    <script src="public/js/bootstrap.min.js"></script>
    <script src="public/js/bootbox.min.js"></script>
    <script src="views/scripts/login.js"></script>
  </head>
  <body class="hold-transition login-page">
    <div class="login-box login-container">
      <div class="login-logo"></div>
      <div class="login-box-body">
        <p class="login-box-msg"><img src="public/img/banner.png" alt="Logo" class="logo-img"></p>
        <h4 class="texto-rojo"><p class="center-text"></p></h4>
        <form  method="post" id="frmAcceso">
          <div class="form-group has-feedback">
            <input type="text" id="logina" name="logina" class="form-control" placeholder="Usuario">
            <span class="fa fa-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" id="clavea" name="clavea" class="form-control" placeholder="Contraseña">
            <span class="fa fa-key form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8"></div>
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat btn-sm">Ingresar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>