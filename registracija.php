<?php

session_start();
require "connect.php";
 
$poruka_greska  = "";
$poruka_uspjeh  = "";
 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $korisnicko_ime = trim($_POST["korisnicko_ime"] ?? "");
    $ime = trim($_POST["ime"] ?? "");
    $prezime = trim($_POST["prezime"] ?? "");
    $lozinka = $_POST["lozinka"] ?? "";
    $lozinka2 = $_POST["lozinka2"] ?? "";
 
    if ($korisnicko_ime === "" || $ime === "" || $prezime === "" || $lozinka === "" || $lozinka2 === "") {
        $poruka_greska = "Molimo popunite sva polja.";
    } elseif ($lozinka !== $lozinka2) {
        $poruka_greska = "Unesene lozinke se ne podudaraju. Pokušajte ponovno.";
    } else {
        // Provjera postoji li već korisnik s tim korisničkim imenom
        $upit = $mysqli->prepare("SELECT id FROM korisnik WHERE korisnicko_ime = ?");
        $upit->bind_param("s", $korisnicko_ime);
        $upit->execute();
        $upit->store_result();
 
        if ($upit->num_rows > 0) {
            $poruka_greska = "Korisničko ime \"" . htmlspecialchars($korisnicko_ime) . "\" je već zauzeto. Odaberite drugo.";
        } else {

            $hash = password_hash($lozinka, PASSWORD_DEFAULT);
 
            $umetni = $mysqli->prepare(
                "INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka, razina)
                 VALUES (?, ?, ?, ?, 0)"
            );
            $umetni->bind_param("ssss", $ime, $prezime, $korisnicko_ime, $hash);
 
            if ($umetni->execute()) {
                $poruka_uspjeh = "Registracija je uspjela! Sada se možete prijaviti.";
            } else {
                $poruka_greska = "Pohrana u bazu podataka nije uspjela: " . $mysqli->error;
            }
            $umetni->close();
        }
        $upit->close();
    }
}

?>

<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registracija | Glasnik</title>
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
        <li><a href="index.php#posao-karijera">Posao i karijera</a></li>
        <li><a href="index.php#hrana">Hrana</a></li>
        <li><a href="o-nama.html">O nama</a></li>
        <li><a href="administracija.php">Administracija</a></li>
      </ul>
    </div>
  </nav>
 
  <main class="sadrzaj">
    <div class="container">
      <section>
        <h1 class="naslov-sekcije">Registracija</h1>
 
        <?php if ($poruka_greska !== ""): ?>
          <div class="poruka-greska">
            <p><?php echo htmlspecialchars($poruka_greska); ?></p>
          </div>
        <?php endif; ?>
 
        <?php if ($poruka_uspjeh !== ""): ?>
          <div class="poruka-uspjeh">
            <p><?php echo htmlspecialchars($poruka_uspjeh); ?></p>
            <p><a class="povratak" href="administracija.php">&larr; Idi na stranicu za prijavu</a></p>
          </div>
        <?php else: ?>
 
          <p class="napomena-forme">Registracijom dobivate korisnički račun koji vam omogućuje prijavu na portal.</p>
 
          <form name="forma-registracija" action="registracija.php" method="POST" class="forma-unosa">
 
            <div class="polje-forme">
              <label for="ime">Ime *</label>
              <input type="text" name="ime" id="ime" placeholder="Unesite svoje ime"
                     value="<?php echo isset($_POST['ime']) ? htmlspecialchars($_POST['ime']) : ''; ?>" required>
            </div>
 
            <div class="polje-forme">
              <label for="prezime">Prezime *</label>
              <input type="text" name="prezime" id="prezime" placeholder="Unesite svoje prezime"
                     value="<?php echo isset($_POST['prezime']) ? htmlspecialchars($_POST['prezime']) : ''; ?>" required>
            </div>
 
            <div class="polje-forme">
              <label for="korisnicko_ime">Korisničko ime *</label>
              <input type="text" name="korisnicko_ime" id="korisnicko_ime" placeholder="Odaberite korisničko ime"
                     value="<?php echo isset($_POST['korisnicko_ime']) ? htmlspecialchars($_POST['korisnicko_ime']) : ''; ?>" required>
            </div>
 
            <div class="polje-forme">
              <label for="lozinka">Lozinka *</label>
              <input type="password" name="lozinka" id="lozinka" placeholder="Unesite lozinku" required>
            </div>
 
            <div class="polje-forme">
              <label for="lozinka2">Potvrda lozinke *</label>
              <input type="password" name="lozinka2" id="lozinka2" placeholder="Ponovno unesite lozinku" required>
              <p class="pomoc">Obje lozinke moraju biti identične.</p>
            </div>
 
            <button type="submit" class="gumb-submit">Registriraj se</button>
 
          </form>
 
          <p class="napomena-forme">Već imate korisnički račun? <a href="administracija.php">Prijavite se ovdje</a>.</p>
 
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