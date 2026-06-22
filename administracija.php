<?php
session_start();
require "connect.php";

if (isset($_GET["odjava"])) {
    $_SESSION = [];
    session_destroy();
    header("Location: administracija.php");
    exit;
}
 
$poruka_greska = "";
 
// Obrada forme za prijavu
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $korisnicko_ime = trim($_POST["korisnicko_ime"] ?? "");
    $lozinka        = $_POST["lozinka"] ?? "";
 
    $korisnik = null;
 
    if ($korisnicko_ime !== "" && $lozinka !== "") {
        $upit = $mysqli->prepare(
            "SELECT id, ime, prezime, korisnicko_ime, lozinka, razina
             FROM korisnik WHERE korisnicko_ime = ?"
        );
        $upit->bind_param("s", $korisnicko_ime);
        $upit->execute();
        $rezultat = $upit->get_result();
        $korisnik = $rezultat->fetch_assoc();
        $upit->close();
    }
 
    // password_verify()
    if ($korisnik && password_verify($lozinka, $korisnik["lozinka"])) {
        $_SESSION["korisnik_id"]      = $korisnik["id"];
        $_SESSION["ime"]              = $korisnik["ime"];
        $_SESSION["prezime"]          = $korisnik["prezime"];
        $_SESSION["korisnicko_ime"]   = $korisnik["korisnicko_ime"];
        $_SESSION["razina"]           = (int) $korisnik["razina"];
    } else {
        $poruka_greska = "Korisničko ime i/ili lozinka nisu ispravni.";
    }
}
 
// Provjera stanja prijave
$prijavljen = isset($_SESSION["korisnicko_ime"]);
$ima_pristup = $prijavljen && (int) $_SESSION["razina"] > 0;
 

$sve_vijesti = [];
if ($ima_pristup) {
    $rezultat = $mysqli->query(
        "SELECT id, naslov, kategorija, datum, prikazi
         FROM vijesti
         ORDER BY datum DESC, id DESC"
    );
    $sve_vijesti = $rezultat->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administracija | Glasnik</title>
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
        <li><a href="unos.html">Unos vijesti</a></li>
        <li><a href="administracija.php" class="aktivno">Administracija</a></li>
      </ul>
    </div>
  </nav>

  <main class="sadrzaj">
    <div class="container">
      <section>
        <h1 class="naslov-sekcije">Administracija</h1>
 
        <?php if (!$prijavljen): ?>
 
          <!-- Korisnik NIJE prijavljen - prikaz forme za prijavu -->
 
          <?php if ($poruka_greska !== ""): ?>
            <div class="poruka-greska">
              <p><?php echo htmlspecialchars($poruka_greska); ?></p>
              <p>Ako još nemate korisnički račun, prvo se morate
                 <a href="registracija.php">registrirati</a>.</p>
            </div>
          <?php endif; ?>
 
          <p class="napomena-forme">Ovaj dio stranice dostupan je samo prijavljenim korisnicima s administratorskim pravima. Unesite svoje korisničko ime i lozinku.</p>
 
          <form name="forma-prijava" action="administracija.php" method="POST" class="forma-unosa">
 
            <div class="polje-forme">
              <label for="korisnicko_ime">Korisničko ime</label>
              <input type="text" name="korisnicko_ime" id="korisnicko_ime" placeholder="Unesite korisničko ime" required>
            </div>
 
            <div class="polje-forme">
              <label for="lozinka">Lozinka</label>
              <input type="password" name="lozinka" id="lozinka" placeholder="Unesite lozinku" required>
            </div>
 
            <button type="submit" class="gumb-submit">Prijava</button>
 
          </form>
 
          <p class="napomena-forme">Nemate korisnički račun? <a href="registracija.php">Registrirajte se ovdje</a>.</p>
 
        <?php elseif (!$ima_pristup): ?>
 
          <!-- Korisnik JE prijavljen, ali NEMA administratorska prava (razina = 0) -->
 
          <div class="poruka-greska">
            <p>Bok, <?php echo htmlspecialchars($_SESSION["ime"] . " " . $_SESSION["prezime"]); ?>!</p>
            <p>Vaš korisnički račun (<?php echo htmlspecialchars($_SESSION["korisnicko_ime"]); ?>) nema administratorska prava potrebna za pristup ovoj stranici. Ako smatrate da je to greška, obratite se administratoru portala.</p>
          </div>
 
          <a class="gumb-odjava" href="administracija.php?odjava=1">Odjava</a>
 
        <?php else: ?>
 
          <!-- Korisnik JE prijavljen i IMA administratorska prava (razina > 0) -->
 
          <p class="dobrodoslica">Pozdrav, <strong><?php echo htmlspecialchars($_SESSION["ime"] . " " . $_SESSION["prezime"]); ?></strong>! Prijavljeni ste kao administrator portala Glasnik.</p>
 
          <a class="gumb-odjava" href="administracija.php?odjava=1">Odjava</a>
 
          <h2>Pregled svih vijesti</h2>
          <p class="napomena-forme">Popis svih vijesti pohranjenih u bazi podataka, uključujući arhivirane (koje se ne prikazuju na naslovnoj stranici). Nove vijesti dodajete putem <a href="unos.html">forme za unos</a>.</p>
 
          <?php if (count($sve_vijesti) > 0): ?>
            <table class="tablica-admin">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Naslov</th>
                  <th>Kategorija</th>
                  <th>Datum</th>
                  <th>Prikazano</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($sve_vijesti as $vijest): ?>
                  <tr>
                    <td><?php echo (int) $vijest["id"]; ?></td>
                    <td><a href="clanak.php?id=<?php echo (int) $vijest['id']; ?>"><?php echo htmlspecialchars($vijest["naslov"]); ?></a></td>
                    <td><?php echo htmlspecialchars($vijest["kategorija"]); ?></td>
                    <td><?php echo date("d.m.Y.", strtotime($vijest["datum"])); ?></td>
                    <td>
                      <?php if ((int) $vijest["prikazi"] === 1): ?>
                        <span class="status-da">Da</span>
                      <?php else: ?>
                        <span class="status-ne">Ne (arhiva)</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <p class="poruka-izrada">Trenutno nema vijesti u bazi podataka.</p>
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