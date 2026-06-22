<?php

require "connect.php";

$dozvoljene_kategorije = ["Posao i karijera", "Hrana", "Ostalo"];

$naziv = isset($_GET["naziv"]) ? trim($_GET["naziv"]) : "";

if (!in_array($naziv, $dozvoljene_kategorije, true)) {
    $naziv = "";
}

$vijesti = [];

if ($naziv !== "") {
    $upit = $mysqli->prepare(
        "SELECT id, naslov, sazetak, slika, datum
         FROM vijesti
         WHERE kategorija = ? AND prikazi = 1
         ORDER BY datum DESC, id DESC"
    );
    $upit->bind_param("s", $naziv);
    $upit->execute();
    $rezultat = $upit->get_result();
    $vijesti = $rezultat->fetch_all(MYSQLI_ASSOC);
    $upit->close();
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $naziv !== "" ? htmlspecialchars($naziv) : "Kategorija"; ?> | Glasnik</title>
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
        <li><a href="index.php">Početna</a></li>
        <li><a href="index.php#posao-karijera" <?php echo ($naziv === "Posao i karijera") ? 'class="aktivno"' : ''; ?>>Posao i karijera</a></li>
        <li><a href="index.php#hrana" <?php echo ($naziv === "Hrana") ? 'class="aktivno"' : ''; ?>>Hrana</a></li>
        <li><a href="o-nama.html">O nama</a></li>
        <li><a href="administracija.php">Administracija</a></li>
      </ul>
    </div>
  </nav>

  <main class="sadrzaj">
    <div class="container">
      <section>

        <?php if ($naziv === ""): ?>

          <h1 class="naslov-sekcije">Kategorija nije pronađena</h1>
          <div class="poruka-greska">
            <p>Nepoznata ili nedostajuća kategorija. Dostupne kategorije su: <?php echo htmlspecialchars(implode(", ", $dozvoljene_kategorije)); ?>.</p>
            <p><a class="povratak" href="index.php">&larr; Povratak na početnu stranicu</a></p>
          </div>

        <?php else: ?>

          <h1 class="naslov-sekcije"><?php echo htmlspecialchars($naziv); ?></h1>

          <?php if (count($vijesti) > 0): ?>
            <div class="mreza-kartica">
              <?php foreach ($vijesti as $vijest): ?>
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
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="poruka-izrada">Trenutno nema objavljenih vijesti u ovoj kategoriji.</p>
          <?php endif; ?>

        <?php endif; ?>

      </section>
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
<?php $mysqli->close(); ?>