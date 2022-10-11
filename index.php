<?php

  // Connect data.json
  $berkas = "js/data(1).json";
  $dataJson = file_get_contents($berkas);
  $rutePenerbanganAll = json_decode($dataJson, true);

  // Array data bandara dan pajak
  $asalPenerbangan = [
    "Soekarno-Hatta (CGK)",
    "Husein Sastranegara (BDO)",
    "Abdul Rachman Saleh (MLG)",
    "Juanda (SUB)"
  ];
  $tujuanPenerbangan = [
    "Ngurah Rai (DPS)",
    "Hasanuddin (UPG)",
    "Inanwatan (INX)",
    "Sultan Iskandarmuda (BTJ)",
  ];
  $pajakAsalPenerbangan = [
    "Soekarno-Hatta (CGK)" => 50000,
    "Husein Sastranegara (BDO)" => 30000,
    "Abdul Rachman Saleh (MLG)" => 40000,
    "Juanda (SUB)" => 40000
  ];

  $pajakTujuanPenerbangan = [
    "Ngurah Rai (DPS)" => 80000,
    "Hasanuddin (UPG)" => 70000,
    "Inanwatan (INX)" => 90000,
    "Sultan Iskandarmuda (BTJ)" => 70000
  ];

  // fungsi menghitung total pajak

  function totalPajak($pajakAsal, $pajakTujuan){
      global $pajakAsalPenerbangan, $pajakTujuanPenerbangan;

      foreach ($pajakAsalPenerbangan as $pajak1 => $pajak1_value){
          if($pajakAsal == $pajak1){
              $nilaiPajakA = $pajak1_value;
          }
      }

      foreach ($pajakTujuanPenerbangan as $pajak2 => $pajak2_value){
          if($pajakTujuan == $pajak2){
              $nilaiPajakB = $pajak2_value;
          }
      }

      return $nilaiPajakA + $nilaiPajakB;
  }

  // fungsi menghitung total harga tiket dan pajak di tambahkan

  function totalHarga($totalPajak, $hargaTiket){
    return $totalPajak + $hargaTiket;
  }

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jadwal Penerbangan</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  </head>
  <body>
   
  <div class="container-md">
    <img src="images/pesawat.png" class="rounded mx-auto d-block w-25" alt="">
    <div>
    
    <div style="border-radius: 5px; border: 1px solid #DC3545; ">
        <div class="bg-danger text-white style" style="border-radius: 5px;">
          <h3 class="text-center">Pendaftaran Rute Penerbangan</h3>
        </div>
        <br />
      
        <form action="index.php" method="post" style="padding: 10px;">
          <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Maskapai</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="maskapai" placeholder="Nama Maskapai" required>
              </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Bandara Asal</label>
            <div class="col-sm-5">
              <select class="form-select" name="ruteasal">
          
                <!-- perulangan untuk menampilkan opsi bandara asal penerbangan -->

                <?php

                  foreach ($asalPenerbangan as $aPen) {
                    echo "<option value='".$aPen."'>".$aPen."</option>";
                  }

                ?>
              </select>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Bandara Tujuan</label>
            <div class="col-sm-5">
              <select class="form-select" name="rutetujuan">

                <!-- perulangan untuk menampilkan opsi bandara asal penerbangan -->

                <?php

                  foreach ($tujuanPenerbangan as $tPen) {
                    echo "<option value='".$tPen."'>".$tPen."</option>";
                  }

                ?>
              </select>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Harga Tiket</label>
            <div class="col-sm-10">
              <input type="number" name="harga" class="form-control" placeholder="Harga Tiket" required>
            </div>
          </div>

          <div class="text-center">
            <button type="submit" name="submit" class="btn btn-danger">Submit</button>
          </div>
        </form>
      </div>              
  <!-- menampung hasil inputan user -->

  <?php

    if(isset($_POST['submit'])){
      $maskapaiPenerbangan = $_POST['maskapai'];
      $ruteAsalPenerbangan = $_POST['ruteasal'];
      $ruteTujuanPenerbangan = $_POST['rutetujuan'];
      $hargaPenerbangan = $_POST['harga'];
      $totalPajakPenerbangan = totalPajak($ruteAsalPenerbangan, $ruteTujuanPenerbangan);
      $totalHargaPenerbangan = totalHarga($totalPajakPenerbangan, $hargaPenerbangan);
      

      $rutePenerbangan = [
        $maskapaiPenerbangan, 
        $ruteAsalPenerbangan, 
        $ruteTujuanPenerbangan, 
        $hargaPenerbangan, 
        $totalPajakPenerbangan, 
        $totalHargaPenerbangan];
      array_push($rutePenerbanganAll, $rutePenerbangan);
      array_multisort($rutePenerbanganAll, SORT_ASC);
      $dataJson = json_encode($rutePenerbanganAll, JSON_PRETTY_PRINT);
      file_put_contents($berkas, $dataJson);
    }

  ?>
    <br />
    <!-- menampilkan semua data sesuai inputan user -->
    <div style="border-radius: 5px; border: 1px solid #DC3545; margin-bottom: 10px; padding: none;background-color: #ff5a5f;">
      <div class="bg-danger text-white">
        <h3 class="text-center">Daftar Rute Tersedia</h3>
      </div>
      <table class="table table-danger table-striped">
        <thead>
          <tr style='text-align: center;'>
            <th scope="col">Maskapai</th>
            <th scope="col">Asal Penerbangan</th>
            <th scope="col">Tujuan Penerbangan</th>
            <th scope="col">Harga Tiket</th>
            <th scope="col">Pajak</th>
            <th scope="col">Total Harga Tiket</th>
          </tr>
        </thead>
        <tbody>
      
          <!-- perulangan untuk menampilkan semua data -->

          <?php
            
            for($i=0; $i<count($rutePenerbanganAll); $i++){
              echo "<tr>";
              echo "<td style='text-align: center;'>".$rutePenerbanganAll[$i][0]."</td>";
              echo "<td style='text-align: center;'>".$rutePenerbanganAll[$i][1]."</td>";
              echo "<td style='text-align: center;'>".$rutePenerbanganAll[$i][2]."</td>";
              echo "<td style='text-align: center;'>".$rutePenerbanganAll[$i][3]."</td>";
              echo "<td style='text-align: center;'>".$rutePenerbanganAll[$i][4]."</td>";
              echo "<td style='text-align: center;'>".$rutePenerbanganAll[$i][5]."</td>";
              echo "</tr>";
            }
          
          ?>
        </tbody>
      </table>
    </div>
  </div>
    
    <script src="js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  </body>
</html>