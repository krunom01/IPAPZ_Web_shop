


javni dio: vide se kategorije i proizvodi, jedan proizvod moze imat samo jednu kategoriju, a jedna kategorija pripada
vise proizvoda.
odabirat proizvod i stavljat u kosaricu, odabrat kolicinu proizvoda i naruciti sve proizvode iz kosarice.
prilikom narudbe unosi email i adresu za dostavu. ako u bazi ima email onda se useru dodjeljuje narrudzba inace se kreira novi user.

admin dio: admin potvrduje narudzbe ili ih odbacuje. moze promjeniti indikaciju narudzbe da je poslana i admin moze promjeniti indikaciju da je placena

pozeljenje 1 : admin moze promjeniti podatke korisniku,  a korisnik samo svoje podatke mijenja, admin moze obrisati korisnika, admin moze na narudzbu
na postojece narudzbe dodavati ili brisati proizvodi. admin moze dodavati proizvode(svaki proizvod mora imati sku. kljuc proizvoda magento?), brisati i isto za kategorije .crud

javni dio pretraga

paginacija 10 po stranici preko ajaxa
svakom proizvodu se moze dodati vise slika, a jedna je glavna
proizvodu se moze dodjeliti youtube video.



opcionalne :
za proizvod se moze generirati 2d barkod od sku proizvoda
narudzba sa svim podacima se moze kreirati u pdfu.
moguce preuzimanje exel datoteke sa podacima sa svih narudzbi (datum, email kupca, ukupni iznos)
svaki korisnik sebi moze dodjeliti pokemona.
kreiran javni rest api za detalje o proizvodi preko sku. u url ide sku , a rezultat je json sa svim podacima o tom proizvodu

kreiranom useru se salje email sa svim kreiranim podacima i forsira ga se na promjenu lozinke.