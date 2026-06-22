-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2026 at 10:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `glasnik`
--
CREATE DATABASE IF NOT EXISTS `glasnik` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `glasnik`;

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE `korisnik` (
  `id` int(10) UNSIGNED NOT NULL,
  `ime` varchar(50) NOT NULL,
  `prezime` varchar(50) NOT NULL,
  `korisnicko_ime` varchar(50) NOT NULL,
  `lozinka` varchar(255) NOT NULL,
  `razina` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`id`, `ime`, `prezime`, `korisnicko_ime`, `lozinka`, `razina`) VALUES
(1, 'Josip', 'C', 'nitkov', '$2y$10$mwaIbyE1MltU/r/Nb4eKpe9gTsCdZDD/mi9EtYjxadVZHudcHp14y', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vijesti`
--

CREATE TABLE `vijesti` (
  `id` int(10) UNSIGNED NOT NULL,
  `naslov` varchar(255) NOT NULL,
  `sazetak` text NOT NULL,
  `tekst` text NOT NULL,
  `kategorija` varchar(50) NOT NULL,
  `oznake` varchar(255) DEFAULT NULL,
  `slika` varchar(255) DEFAULT NULL,
  `prikazi` tinyint(1) NOT NULL DEFAULT 1,
  `datum` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vijesti`
--

INSERT INTO `vijesti` (`id`, `naslov`, `sazetak`, `tekst`, `kategorija`, `oznake`, `slika`, `prikazi`, `datum`) VALUES
(1, 'Kako boljim govorom tijela postižete više uspjeha', 'Samopouzdano držanje često je vrjednije od izgleda. Otkrivamo nekoliko jednostavnih trikova kojima možete poboljšati govor tijela na poslovnim sastancima, razgovorima za posao i u svakodnevnoj komunikaciji.', 'Prvi dojam stvara se u prvih nekoliko sekundi susreta, i to najčešće prije nego što izgovorimo i jednu riječ. Način na koji ulazimo u prostoriju, kako stojimo i gledamo sugovornika govori više o našem samopouzdanju nego što mislimo, a istraživanja pokazuju da neverbalna komunikacija često ostavlja dublji trag od samih riječi.\r\nOsnova svega je držanje tijela. Ravna leđa, opušteni ramena i podignuta glava odaju sigurnost, dok pogrbljen stav šalje suprotnu poruku, bez obzira na to koliko smo zapravo pripremljeni za sastanak. Jednostavna vježba prije važnog razgovora - nekoliko trenutaka uspravnog stajanja s rukama na bokovima - može pomoći da se osjećamo sabranije.\r\nKontakt očima i stisak ruke jednako su važni. Kratak, ali čvrst stisak ruke uz prirodan osmijeh ostavlja dojam otvorenosti, dok izbjegavanje pogleda može djelovati kao znak nesigurnosti ili nezainteresiranosti. Pravilo je jednostavno: gledati sugovornika dovoljno često da se osjeti povezanost, ali ne toliko intenzivno da postane neugodno.\r\nTijekom razgovora, otvoreni pokreti rukama djeluju umirujuće na sugovornika, dok skrštene ruke ili ruke u džepovima mogu stvoriti dojam zatvorenosti. Korisna je i tehnika zrcaljenja - blago prilagođavanje vlastitog držanja i tona govora sugovorniku, što potiče osjećaj povezanosti bez da to bude očito ili neprirodno.\r\nGovor tijela, kao i svaka druga vještina, poboljšava se vježbom. Snimanje vlastitih nastupa na telefon ili vježbanje pred ogledalom prije važnih razgovora pomaže da uočimo navike kojih nismo svjesni - od nervoznog dodirivanja lica do prebrzog govora - i postupno ih ispravimo.', 'Posao i karijera', 'Karijera,Savjeti', 'slike/govor-tijela.jpg', 1, '2026-05-13'),
(2, 'Totalna kontrola – kada aplikacija određuje vaš slobodan dan', 'Sve više tvrtki uvodi aplikacije za praćenje radnog vremena zaposlenika. Tehnologija za to postoji, ali otvara brojna pitanja o privatnosti i granici između posla i slobodnog vremena.', 'Sve veći broj poslodavaca razmišlja o uvođenju mobilnih i računalnih aplikacija koje bi automatski bilježile dolazak, odlazak i stanke zaposlenika. Ideja zvuči jednostavno, no posljedice za svakodnevni rad i privatnost zaposlenika mogu biti dalekosežne.\r\nTehnologija za potpuno praćenje radnog vremena postoji već godinama. Aplikacije mogu zapisivati prijavu i odjavu s posla, mjeriti vrijeme provedeno u određenim programima, a uz uključenu lokaciju i pratiti kretanje zaposlenika tijekom radnog dana. Pitanje koje se sve češće postavlja nije može li se to učiniti, već treba li.\r\nSindikati upozoravaju da bi takav nadzor mogao prijeći granicu razumne organizacije rada i postati sredstvo pritiska. Zaposlenici se pribojavaju da bi svaka stanka za kavu ili kratak razgovor s kolegom mogla postati stavka u evidenciji koja se ocjenjuje pri godišnjoj procjeni rada.\r\nS druge strane, dio poslodavaca smatra da precizna evidencija radnog vremena štiti i njih same od optužbi za neisplaćene prekovremene sate. Stručnjaci za radno pravo stoga predlažu da se ovakvi alati uvode isključivo uz jasna pravila, dogovor sa zaposlenicima i mogućnost provjere prikupljenih podataka.\r\nRasprava o granicama digitalnog nadzora na radnom mjestu vjerojatno će se tek zaoštriti kako sve više tvrtki prelazi na rad od kuće i hibridne modele rada, gdje je granica između privatnog i radnog vremena sve teže povući.', 'Posao i karijera', 'Karijera,Tehnologija', 'slike/ured-nocu.jpg', 1, '2026-05-16'),
(3, 'Kako vaše dijete može upisati elitno sveučilište', 'Upis na vrhunska svjetska sveučilišta postaje sve konkurentniji. Roditelji bi se trebali pripremiti unaprijed, a kandidati moraju u prijavi istaknuti tri ključne stvari.', 'Sve veći broj srednjoškolaca i njihovih roditelja razmišlja o studiranju na nekom od svjetski poznatih sveučilišta. Broj prijava raste iz godine u godinu, dok broj slobodnih mjesta ostaje gotovo nepromijenjen, što natjecanje za upis čini izrazito zahtjevnim.\r\nPrvi i najočitiji uvjet jest akademski uspjeh - visok prosjek ocjena te dobar rezultat na međunarodno priznatim ispitima. No iskustva pokazuju da odlične ocjene same po sebi danas više nisu dovoljne; one su ulaznica za razmatranje, a ne garancija upisa.\r\nDrugi ključni element su izvannastavne aktivnosti, pri čemu povjerenstva za upis posebnu pažnju posvećuju dosljednosti i dubini, a ne broju aktivnosti. Kandidat koji je nekoliko godina razvijao jedan projekt, sport ili volontersku inicijativu ostavlja snažniji dojam od onoga koji je kratko isprobao desetak različitih stvari.\r\nTreći, često podcijenjeni element jest motivacijsko pismo i preporuke profesora. Povjerenstva traže autentičan glas i konkretne primjere, a ne uvježbane fraze koje se mogu pronaći u bilo kojoj prijavi. Preporuke su najuvjerljivije kada profesor opisuje konkretnu situaciju u kojoj se učenik istaknuo, a ne samo opće pohvale.\r\nStručnjaci za savjetovanje pri upisu preporučuju da se priprema započne već u prva dva razreda srednje škole, kako bi kandidati imali vremena izgraditi konzistentan profil. Vrijedi napomenuti i da mnoga sveučilišta nude programe financijske pomoći, no za njih je u pravilu potrebna posebna, odvojena prijava koju treba podnijeti na vrijeme.', 'Posao i karijera', 'Karijera,Edukacija', 'slike/diploma.jpg', 1, '2026-05-04'),
(4, 'Limunasta piletina iz pećnice', 'Miris koji se širi kuhinjom već je sam po sebi nagrada. Otkrijte jednostavan recept za sočnu piletinu iz pećnice koji uspijeva i početnicima u kuhanju.', 'Cijela piletina pečena s limunom, češnjakom i svježim začinskim biljem jedno je od onih jela koja djeluju impresivno na stolu, a u pripremi su iznenađujuće jednostavna - savršeno za nedjeljni ručak ili objed s gostima.\r\nZa pripremu je potrebna cijela piletina od oko 1,5 kilograma, dva limuna, nekoliko čena češnjaka, svježi ružmarin i timijan, maslinovo ulje, sol, papar i krumpir narezan na kockice.\r\nMarinada se priprema od maslinovog ulja, soka jednog limuna, nasjeckanog češnjaka, soli i papra, a njome se piletina premaže izvana i iznutra, dok se u trbušnu šupljinu stavlja polovica drugog limuna i nekoliko grančica začinskog bilja.\r\nOstatak limuna nareže se na kriške i poreda zajedno s krumpirom oko piletine u pleh, koji se zatim peče u pećnici zagrijanoj na 200 stupnjeva oko 75 do 90 minuta, povremeno premazujući piletinu sokovima iz pleha dok kožica ne postane zlatno-smeđa i hrskava.\r\nPrije rezanja piletinu je dobro ostaviti da odstoji desetak minuta, kako bi meso ostalo sočno, a kao prilog dovoljan je samo zeleni list salate, jer je krumpir pečen uz piletinu već upio sve okuse iz pleha.', 'Hrana', 'Recepti', 'slike/piletina-limun.jpg', 1, '2026-05-16'),
(5, 'Nije dovoljno ljuto? Umaci koje morate probati', 'Za marinade, kuhanje ili samo začinjavanje – predstavljamo pet ljutih umaka kojima ćete osvježiti svoju kuhinju i podići okus svakog jela.', 'Za marinade, kuhanje ili samo začinjavanje gotovog jela - ljuti umak ima čarobnu moć da svaki, i naizgled najobičniji obrok, podigne na novu razinu. U nastavku predstavljamo pet umaka koji se razlikuju po profilu okusa, intenzitetu i podrijetlu.\r\nSriracha je tajlandski klasik na bazi crvenih čili papričica, češnjaka, šećera i octa, a kombinacija slatkoće i kiselosti čini ga izrazito pristupačnim za umaćanje, juhe, marinade i preljeve za salate.\r\nTabasco je jedan od najpoznatijih umaka u svijetu, jednostavnog sastava od papričica, soli i octa, fermentiranih mjesecima u drvenim bačvama, izrazito oštar i kiseo, pa je nekoliko kapi često dovoljno za cijeli tanjur.\r\nHarissa je sjevernoafrička pasta od pečenih čili papričica, kumina, češnjaka i maslinovog ulja, prepoznatljiva po dimljenom, zemljanom okusu, savršena za kuskus, pečeno povrće ili kao baza za marinade za meso.\r\nSambal je jugoistočnoazijski umak koji u osnovi sadrži svježe ili pirjane čili papričice s limetom, češnjakom i ribljim umakom, svjež, intenzivan i odličan uz rižu, tjesteninu ili pečenu ribu.\r\nGochujang je korejska fermentirana pasta od čilija, riže i sojinog graška, prepoznatljiva po kombinaciji slatkog, ljutog i umami okusa, koja se koristi u juhama, marinadama i umacima za pečenje na žaru.\r\nBez obzira na to koji umak odaberete, isplati se krenuti s malom količinom i postupno dodavati, jer se intenzitet ljutine razlikuje ne samo među umacima, nego i među pojedinim serijama istog proizvoda.', 'Hrana', 'Recepti,Savjeti', 'slike/ljuti-umaci.jpg', 1, '2026-05-15'),
(6, 'Špageti all\'ubriaco', 'Ovo jelo živi od kombinacije tjestenine i vina. Kod špageta „na pijani način” crno vino dodaje se izravno u vodu za kuhanje, čime tjestenina dobiva prepoznatljivu boju i aromu.', 'Ovo jelo živi od kombinacije samo dviju glavnih namirnica koje se savršeno nadopunjuju - tjestenine i vina. Kod „pijanih” špageta tjestenina se kuha izravno u crnom vinu umjesto u vodi, pa upija njegovu boju i aromu te dobiva prepoznatljivu tamnoljubičastu nijansu i blago opori, vinski okus.\r\nZa četiri osobe potrebno je 400 grama špageta, 500 mililitara crnog vina srednje punog tijela, 500 mililitara vode, maslinovo ulje, tri češnja češnjaka, po želji jedna manja ljuta papričica, parmezan, svježi peršin, sol i papar.\r\nVino i voda zagrijavaju se u većem loncu i posole kao obična voda za tjesteninu, a kada tekućina zavrije, dodaju se špageti koji se kuhaju prema uputama na pakiranju, uz povremeno miješanje da se ne lijepe.\r\nDok se tjestenina kuha, na maslinovom ulju u širokoj tavi kratko se popraži češnjak i nasjeckana ljuta papričica dok ne postanu mirisni, a kuhana tjestenina se ocijedi, uz čuvanje malo tekućine za kuhanje, te prebaci u tavu s češnjakom.\r\nDodavanjem nekoliko žlica sačuvane tekućine i ribanog parmezana stvara se lagana, blago kremasta tekstura, a jelo se poslužuje odmah, posipano svježim peršinom i dodatnim parmezanom. Za najbolji rezultat preporučuje se koristiti vino koje biste i sami popili uz jelo.', 'Hrana', 'Recepti', 'slike/spageti-vino.jpg', 1, '2026-05-14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `korisnicko_ime_unique` (`korisnicko_ime`);

--
-- Indexes for table `vijesti`
--
ALTER TABLE `vijesti`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `korisnik`
--
ALTER TABLE `korisnik`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vijesti`
--
ALTER TABLE `vijesti`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
