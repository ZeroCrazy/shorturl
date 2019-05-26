<?php require 'core.php'; ?>
<?php if(!$_GET['action'] == 'refresh'){ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo strtoupper($sitename); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://sweetalert.js.org/assets/sweetalert/sweetalert.min.js"></script>
    <style>
    body {
        background: #edf2f7;
    }
    .brand-logo {
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    .swal-overlay {
        background-color: rgba(74, 20, 140, 0.45);
    }
    .swal-button-container {
        display: block;
    }
    .swal-button {
        padding: 10px 19px;
        border-radius: 5px;
        background-color: #4a148c !important;
        font-size: 12px;
        border: none;
        text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.3);
        margin-left: auto;
        margin-right: auto;
        width: 100%;
        text-align: center;
        transition: 0.3s;
    }
    .swal-button:hover {
        opacity: 0.6;
    }
    input {
        font-size: 1rem !important;
        margin: 7em auto !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        background: #ffffff !important;
        border-radius: 8px !important;
        position: relative !important;
        color: #4a5568 !important;
        font-weight: bold;
        border: none !important;
        cursor: pointer !important;
        text-align: center;
    }
    .card {
        font-size: 1rem !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        background: #ffffff !important;
        border-radius: 8px !important;
        position: relative !important;
        color: #4a5568 !important;
        font-weight: bold;
        border: none !important;
        cursor: pointer !important;
        text-align: center;
    }
    .card-content {
        
        margin: 0px !important;
    }
    </style>
    <script>
    $(document).ready(function() {
    $("#notice_div").load("index.php?action=refresh");
    var refreshId = setInterval(function() {
    $("#notice_div").load('index.php?action=refresh&randval='+ Math.random());
    }, 15000);
    $.ajaxSetup({ cache: false });
    });
    </script>
</head>
<body>
  <nav class="#4a148c purple darken-4">
    <div class="nav-wrapper">
    <div class="container">
      <a href="<?php echo $site; ?>" class="brand-logo center"><?php echo $sitename; ?></a>
    </div>
    </div>
  </nav>
  <br><br><br>
  <div class="container">
    <?php
    if(isset($_POST['cut_url'])){
      $cut_url = str_replace('https', 'http', $_POST['cut_url']);
      $url = substr(md5($cut_url . time() . rand()), -6);

      if(empty($cut_url)){
        echo '
        <script>
        swal({
          title: "Oops!",
          text: "Introduce un enlace válido.",
          icon: "error",
          closeOnClickOutside: false,
          closeOnEsc: false,
          button: "Continuar",
        });
        </script>
        ';
      } else {
        $curl = curl_init($cut_url);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        $result = curl_exec($curl);
        if ($result !== false){
          $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  
          if ($statusCode == 404){
            echo '
            <script>
            swal({
              title: "Oops!",
              text: "Enlace error",
              icon: "error",
              closeOnClickOutside: false,
              closeOnEsc: false,
              button: "Continuar",
            });
            </script>
            ';
          } else {
            echo '
            <script>
            swal({
              title: "Genial!",
              text: "Se ha generado tu enlace correctamente.",
              icon: "success",
              closeOnClickOutside: false,
              closeOnEsc: false,
              button: "Continuar",
            })
            .then((value) => {
              swal({
                title: "Copia tu enlace",
                text: "'. $site . '/u/' . $url .'",
                icon: "info",
                closeOnClickOutside: false,
                closeOnEsc: false,
              }).then(function() {
                window.location = "'. $site .'";
              });
            });
            </script>
            ';
            mysqli_query($conn, "INSERT INTO urls (cut_url,url,date_created,ip) VALUES ('$cut_url','$url','". date(Y) ."-". date(m) ."-". date(d) ." ". date(H) .":". date(i) .":". date(s) ."','". $_SERVER[REMOTE_ADDR] ."')");
            return false;
          } 
        } else {
          echo '
          <script>
          swal({
            title: "Oops!",
            text: "Enlace error",
            icon: "error",
            closeOnClickOutside: false,
            closeOnEsc: false,
            button: "Continuar",
          });
          </script>
          ';
        }
      }
    }
    ?>
  <form method="POST" class="col s12">
      <div class="row">
        <div class="input-field col s12 m8 offset-m2 l6 offset-l3">
          <input placeholder="Introduce enlace" name="cut_url" type="url" class="validate" autocomplete="off" autofocus required>
        </div>
      </div>
  </form>
  <div id="notice_div"></div>
  </div>
</body>
</html>
<?php } else { 
  $sql_urls = "SELECT * FROM urls ORDER BY id DESC LIMIT 12";$result_urls = $conn->query($sql_urls); 
?>
  <div class="row">
  <?php if ($result_urls->num_rows > 0) { ?>  
    <div class="col s12 m12">
      <div class="card" style="background: transparent !important;box-shadow: none !important;">
        <div class="card-content">
          <span class="card-title center" style="font-weight: 500;">Últimos enlaces</span>
        </div>
      </div>
    </div>
    <?php }
      foreach ($result_urls as $u) {
    ?>
    <div class="col s12 m2">
      <a href="<?php echo $site . '/u/' . $u['url']; ?>">
      <div class="card">
        <div class="card-content">
          <span class="card-title" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;"><?php echo $u['url']; ?></span>
        </div>
      </div>
      </a>
    </div>
    <?php } ?>
  </div>
<?php } ?>
