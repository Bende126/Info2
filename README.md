# Házi feladat

Ebbe a könyvtárba kerül az opcionális házi feladat megoldása.

# Házi feladat specifikáció

## Feladat leírása
A feladat egy vasúti társaságnak igazgatásához szükséges weboldal megírása. A weboldalon szeretnénk számon tartani a vasúti társaság vasúthálózatát, vonatait, utasait, stb... Az oldal lehetőséget kínál a teljes vasúti rendszer karbantartására, valamint jegyek vásárlására. 

## Menü elemek
A weboldalon megtalálható menü elemei. Az a felhasználó, aki csak meglátogatja az oldalt az a vonalak menüpontig fér hozzá az oldalhoz.
Bejelentkezés/regisztráció után elérhetővé válik a jegyvásárlás menüpont.
Ha a bejelentkezett felhasználó admin jogokkal rendelkezik, akkor a felhasználó tudja alkalmazni a különböző CRUD műveleteket
### Kezdőlap
Egy rövid leírás az oldalról
### Vonalak
Egy vonalábra a jelenlegi hálózatról
## Regisztráció/Bejelentkezés
Bejelentkezés/Regisztráció
#### Jegyvásárlás
Jegy vásárlása adott vonalra
## Admin panel
Az összes tábla CRUD műveletei

## Adatbázis séma

![image](https://user-images.githubusercontent.com/28264530/162775591-38b7b6bb-96b2-4066-921e-819f32a86f2d.png)

### Tárolt adatok:

Vonat: Név, Kapacitás

Állomás: Név, x koordináta a térben, y koordináta a térben

Felhasználó: Név, Email cím, Telefonszám, Felhasználónév, Jelszó

Jegy: Honnan, Hova, Eddig érvényes, Felhasználó

Útvonal: Név, Egyik állomás, Másik állomás

