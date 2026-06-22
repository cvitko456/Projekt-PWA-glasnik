<?php
require "connect.php";

$kategorije = [
    "Posao i karijera" => "posao-karijera",
    "Hrana"            => "hrana",
    "Ostalo"           => "ostalo",
];

?>

<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Josip Cvitković">
  <title>Glasnik - Početna stranica</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <header class="zaglavlje">
    <div class="container">
      <a href="index.php" class="logo">GLASNIK</a>
    </div>
  </header>

  <nav class="navigacija">
    <div class="container">
      <ul>
        <li><a href="index.php" class="aktivno">Početna</a></li>
        <li><a href="#posao-karijera">Posao i karijera</a></li>
        <li><a href="#hrana">Hrana</a></li>
        <li><a href="o-nama.html">O nama</a></li>
        <li><a href="unos.html">Unos vijesti</a></li>
        <li><a href="administracija.php">Administracija</a></li>
      </ul>
    </div>
  </nav>

  <main class="sadrzaj">
    <div class="container">
 
      <?php foreach ($kategorije as $naziv_kategorije => $id_sekcije): ?>
 
        <?php
        $upit = $mysqli->prepare(
            "SELECT id, naslov, sazetak, slika, datum
             FROM vijesti
             WHERE kategorija = ? AND prikazi = 1
             ORDER BY datum DESC, id DESC"
        );
        $upit->bind_param("s", $naziv_kategorije);
        $upit->execute();
        $rezultat = $upit->get_result();
        ?>
 
        <?php if ($rezultat->num_rows > 0): ?>
        <section id="<?php echo $id_sekcije; ?>">
          <h1 class="naslov-sekcije"><?php echo htmlspecialchars($naziv_kategorije); ?></h1>
          <div class="mreza-kartica">
 
            <?php while ($vijest = $rezultat->fetch_assoc()): ?>
              <?php
              $slika = !empty($vijest["slika"]) ? $vijest["slika"] : "slike/bez-slike.jpg";
              $datum_hr = date("d.m.Y.", strtotime($vijest["datum"]));
              ?>
              <article class="kartica">
                <a href="clanak.php?id=<?php echo (int) $vijest['id']; ?>">
                  <img src="<?php echo htmlspecialchars($slika); ?>" alt="<?php echo htmlspecialchars($vijest['naslov']); ?>">
                </a>
                <h2><a href="clanak.php?id=<?php echo (int) $vijest['id']; ?>"><?php echo htmlspecialchars($vijest['naslov']); ?></a></h2>
                <p class="opis"><?php echo htmlspecialchars($vijest['sazetak']); ?></p>
                <p class="datum"><?php echo $datum_hr; ?></p>
              </article>
            <?php endwhile; ?>
 
          </div>
        </section>
        <?php endif; ?>
 
        <?php $upit->close(); ?>
 
      <?php endforeach; ?>
 
    </div>
  </main>

  <footer class="podnozje">
    <div class="container">
      <p class="logo">GLASNIK</p>
      <p>Autor: Josip Cvitković &nbsp;|&nbsp; Kontakt: <a href="mailto:jcvitkovi@tvz.hr">jcvitkovi@tvz.hr</a> &nbsp;|&nbsp; &copy; 2026</p>
    </div>
  </footer>

</body>
</html>