<?php
// Obrada podataka poslanih iz forme unos.html

require "connect.php";

$poslano = ($_SERVER['REQUEST_METHOD'] === 'POST');

// Dohvat text polja iz forme
$naslov = $poslano ? trim($_POST['naslov'] ?? '') : '';
$sazetak = $poslano ? trim($_POST['sazetak'] ?? '') : '';
$tekst = $poslano ? trim($_POST['tekst'] ?? '') : '';
$kategorija = $poslano ? trim($_POST['kategorija'] ?? '') : '';

// select multiple stiže kao niz
$oznake = $poslano ? ($_POST['oznake'] ?? []) : [];

// Checkbox
$arhiva = $poslano && isset($_POST['arhiva']);

$datum = date("d.m.Y.");

// Provjera obaveznih polja
$greska = '';
if ($poslano && ($naslov === '' || $sazetak === '' || $tekst === '' || $kategorija === '')) {
    $greska = 'Molimo ispunite sva obavezna polja (naslov, sažetak, tekst i kategorija su obavezni).';
}
 
// Obrada priložene slike (element <input type="file">)
$slika_web = '';
if ($poslano && $greska === '' && isset($_FILES['slika']) && $_FILES['slika']['error'] === UPLOAD_ERR_OK) {
    $dozvoljene_ekstenzije = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ekstenzija = strtolower(pathinfo($_FILES['slika']['name'], PATHINFO_EXTENSION));
 
    if (in_array($ekstenzija, $dozvoljene_ekstenzije)) {

        $novi_naziv = 'unos_' . time() . '.' . $ekstenzija;
        $odredisna_putanja = 'slike/' . $novi_naziv;
 
        if (move_uploaded_file($_FILES['slika']['tmp_name'], $odredisna_putanja)) {
            $slika_web = $odredisna_putanja;
        }
    }
}
 
// Pohrana vijesti u bazu podataka (tablica "vijesti")
$novi_id = 0;
 
if ($poslano && $greska === '') {
    $oznake_str = implode(',', $oznake);         
    $prikazi_db = $arhiva ? 0 : 1;                
    $datum_db   = date('Y-m-d');                   
    $slika_db   = $slika_web !== '' ? $slika_web : null;
 
    $upit = $mysqli->prepare(
        "INSERT INTO vijesti (naslov, sazetak, tekst, kategorija, oznake, slika, prikazi, datum)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $upit->bind_param(
        "ssssssis",
        $naslov, $sazetak, $tekst, $kategorija, $oznake_str, $slika_db, $prikazi_db, $datum_db
    );
 
    if ($upit->execute()) {
        $novi_id = $mysqli->insert_id;
    } else {
        $greska = 'Pohrana u bazu podataka nije uspjela: ' . $mysqli->error;
    }
 
    $upit->close();
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pregled unesene vijesti | Glasnik</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <header class="zaglavlje">
    <div class="container">
      <a href="index.html" class="logo">GLASNIK</a>
    </div>
  </header>

  <nav class="navigacija">
    <div class="container">
      <ul>
        <li><a href="index.html">Početna</a></li>
        <li><a href="index.html#posao-karijera">Posao i karijera</a></li>
        <li><a href="index.html#hrana">Hrana</a></li>
        <li><a href="o-nama.html">O nama</a></li>
        <li><a href="administracija.php">Administracija</a></li>
      </ul>
    </div>
  </nav>

  <main class="sadrzaj">
    <div class="container">
      <section>
 
        <?php if (!$poslano): ?>
 
          <!-- Stranica otvorena izravno, bez podataka iz forme -->
          <h1 class="naslov-sekcije">Pregled vijesti</h1>
          <div class="poruka-greska">
            <p>Ova stranica prikazuje pojedinačnu vijest tek nakon što se podaci pošalju iz forme.</p>
            <p>Vratite se na <a href="unos.html">formu za unos vijesti</a> i ispunite je kako bi se ovdje prikazao pregled članka.</p>
          </div>
 
        <?php elseif ($greska !== ''): ?>
 
          <!-- Forma je poslana, ali nedostaju obavezni podaci -->
          <h1 class="naslov-sekcije"><?php echo $kategorija !== '' ? htmlspecialchars($kategorija) : 'Unos vijesti'; ?></h1>
          <div class="poruka-greska">
            <p><?php echo htmlspecialchars($greska); ?></p>
            <p><a class="povratak" href="unos.html">&larr; Povratak na formu za unos</a></p>
          </div>
 
        <?php else: ?>
 
          <!-- Ispravno popunjena forma - prikaz vijesti -->
          <h1 class="naslov-sekcije"><?php echo htmlspecialchars($kategorija); ?></h1>
 
          <article class="clanak">
            <h2 class="naslov-clanka"><?php echo htmlspecialchars($naslov); ?></h2>
            <p class="datum-objave">Objavljeno: <?php echo $datum; ?></p>
 
            <?php if ($slika_web !== ''): ?>
              <img class="slika-clanka" src="<?php echo htmlspecialchars($slika_web); ?>" alt="<?php echo htmlspecialchars($naslov); ?>">
            <?php endif; ?>
 
            <p class="sazetak-vijesti"><?php echo nl2br(htmlspecialchars($sazetak)); ?></p>
 
            <?php
              // Tekst vijesti se razdvaja po novim redovima u zasebne odlomke
              $odlomci = preg_split('/\r\n|\r|\n/', $tekst);
              foreach ($odlomci as $odlomak) {
                  $odlomak = trim($odlomak);
                  if ($odlomak !== '') {
                      echo '<p>' . htmlspecialchars($odlomak) . '</p>' . "\n";
                  }
              }
            ?>
 
            <dl class="meta-vijesti">
              <dt>Kategorija</dt>
              <dd><?php echo htmlspecialchars($kategorija); ?></dd>
 
              <dt>Oznake</dt>
              <dd>
                <?php
                  if (!empty($oznake)) {
                      echo htmlspecialchars(implode(', ', $oznake));
                  } else {
                      echo 'Nema odabranih oznaka';
                  }
                ?>
              </dd>
 
              <dt>Prikaz na naslovnoj stranici</dt>
              <dd><?php echo $arhiva ? 'Ne (vijest je arhivirana)' : 'Da'; ?></dd>
 
              <dt>Spremljeno u bazu podataka</dt>
              <dd>Da, pod ID-om <?php echo (int) $novi_id; ?></dd>
            </dl>
 
            <p>
              <a class="povratak" href="clanak.php?id=<?php echo (int) $novi_id; ?>">Pogledaj vijest na stranici</a>
              &nbsp;|&nbsp;
              <a class="povratak" href="unos.html">Unesi novu vijest</a>
            </p>
          </article>
 
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