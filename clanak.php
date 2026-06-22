<?php

require "connect.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

$clanak = null;

if ($id > 0) {
    $upit = $mysqli->prepare("SELECT * FROM vijesti WHERE id = ?");
    $upit->bind_param("i", $id);
    $upit->execute();
    $rezultat = $upit->get_result();
    $clanak = $rezultat->fetch_assoc();
    $upit->close();
}

$id_sekcije = [
    "Posao i karijera" => "posao-karijera",
    "Hrana"            => "hrana",
    "Ostalo"           => "ostalo",
];

$naslov_stranice = $clanak ? $clanak["naslov"] . " | Glasnik" : "Članak nije pronađen | Glasnik";
?>
<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($naslov_stranice); ?></title>
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
        <li>
          <a href="index.php#posao-karijera"
             <?php echo ($clanak && $clanak["kategorija"] === "Posao i karijera") ? 'class="aktivno"' : ''; ?>>
             Posao i karijera</a>
        </li>
        <li>
          <a href="index.php#hrana"
             <?php echo ($clanak && $clanak["kategorija"] === "Hrana") ? 'class="aktivno"' : ''; ?>>
             Hrana</a>
        </li>
        <li><a href="o-nama.html">O nama</a></li>
        <li><a href="administracija.php">Administracija</a></li>
      </ul>
    </div>
  </nav>

  <main class="sadrzaj">
    <div class="container">
      <section>

        <?php if ($clanak): ?>

          <h1 class="naslov-sekcije"><?php echo htmlspecialchars($clanak["kategorija"]); ?></h1>

          <article class="clanak">
            <h2 class="naslov-clanka"><?php echo htmlspecialchars($clanak["naslov"]); ?></h2>
            <p class="datum-objave">Objavljeno: <?php echo date("d.m.Y.", strtotime($clanak["datum"])); ?></p>

            <?php
            $slika = !empty($clanak["slika"]) ? $clanak["slika"] : "slike/bez-slike.jpg";
            ?>
            <img class="slika-clanka" src="<?php echo htmlspecialchars($slika); ?>" alt="<?php echo htmlspecialchars($clanak["naslov"]); ?>">

            <p class="sazetak-vijesti"><?php echo nl2br(htmlspecialchars($clanak["sazetak"])); ?></p>

            <?php
            $odlomci = preg_split('/\r\n|\r|\n/', $clanak["tekst"]);
            foreach ($odlomci as $odlomak) {
                $odlomak = trim($odlomak);
                if ($odlomak !== "") {
                    echo "<p>" . htmlspecialchars($odlomak) . "</p>\n";
                }
            }
            ?>

            <?php if (!empty($clanak["oznake"])): ?>
              <p class="oznake-clanka">
                <?php
                $sve_oznake = explode(",", $clanak["oznake"]);
                foreach ($sve_oznake as $oznaka) {
                    $oznaka = trim($oznaka);
                    if ($oznaka !== "") {
                        echo '<span class="oznaka">' . htmlspecialchars($oznaka) . '</span>';
                    }
                }
                ?>
              </p>
            <?php endif; ?>

            <a class="povratak" href="index.php">&larr; Povratak na početnu stranicu</a>
          </article>

        <?php else: ?>

          <h1 class="naslov-sekcije">Članak nije pronađen</h1>
          <div class="poruka-greska">
            <p>Vijest koju tražite ne postoji ili je uklonjena iz baze podataka.</p>
            <p><a class="povratak" href="index.php">&larr; Povratak na početnu stranicu</a></p>
          </div>

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