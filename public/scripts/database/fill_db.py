#!/usr/bin/env python3
import mysql.connector
import random
import math
import numpy as np
import time
startt = time.time()
mydb = mysql.connector.connect(
    host = "localhost",
    user = "labor",
    password = "asdf1234",
    database = "mav",
    auth_plugin='mysql_native_password'
)
mycursor = mydb.cursor()

class Coord:
    def __init__(self, x, y):
        self.x = x
        self.y = y

#http://www.vasutallomasok.hu/abc.php?betu=z
állomások = """Žabjak 
Zabok 
Zabola-fűrésztelep 
Zabola-Páva 
Zábrogy 
Zádor 
Zádorlak 
Zadubravlje 
Zagorska Sela 
Zágráb d.v. 
Zágráb m.á.v. 
Zagreb Klara 
Zagreb Samoborski Kolodvor 
Zaguzsén 
Zagyvapálfalva 
Zagyvarékas 
Záhony 
Záhony-Átrakó 
Záhony-Rendező 
Zajda-Doboskert 
Zajkány 
Zajló 
Zajta 
Zákány 
Zákány iskola 
Zákányszék 
Zákányszék alsó 
Zalaapáti 
Zalaba 
Zalabér 
Zalabér-Batyk 
Zalabesenyő 
Zalacsány 
Zalacséb-Salomvár 
Zalaegerszeg 
Zalaegerszeg külső kórház 
Zalaegerszeg-Ola 
Zalaegerszeg-Ola régi 
Zalagyömörő 
Zalahaláp 
Zalaháshágy-Szőce 
Zalahosszúfalu 
Zalakomár 
Zalalövő 
Zalapataka 
Zalapatakalja 
Zalaszentgrót 
Zalaszentgyörgy 
Zalaszentiván 
Zalaszentiván régi 
Zalaszentiván-Kisfaludpuszta 
Zalaszentjakab 
Zalaszentlászló 
Zalaszentlőrinc 
Zalaszentmihály-Pacsa 
Zalatna 
Zalaudvarnok 
Zalavég 
Zalesina 
Zaluka 
Zám 
Zamárdi 
Zamárdi felső 
Zánka-Erzsébettábor 
Zánka-Köveskál 
Zapolje 
Zaprešić 
Zaprešić-Savska 
Zarany puszta 
Zariečie 
Zarilac 
Zastávka viťazstva-Polustanok pobedy 
Závadka nad Hronom zastávka 
Závod 
Zăvoiul Crișana 
Zazár 
Zbehy 
Zbehy obec 
Zbelava 
Zbudské Dlhé 
Zbyňov 
Zdenčac 
Zdenci Orahovica 
Zdenčina 
Zebegény 
Žeinci 
Zelemér 
Zelenjak 
Zemanek 
Zemplénagárd 
Zemun 
Zenta 
Zentelke 
Zernest 
Žiar nad Hronom zastávka 
Zichybarlang 
Zichyfalva 
Zichyújfalu 
Zid Katalena 
Zilah 
Zilah északi 
Žilina-Solinky 
Ziliz 
Zimándújfalu 
Zimony külváros 
Zimony régi 
Žirany 
Zirc 
Živaja 
Zlatar Bistrica 
Zlatna h 
Zlatnó 
Zlobin 
Znióváralja 
Zohor 
Zöldhalom 
Zöldmajori elágazás 
Zoljan 
Zólyom megálló 
Zólyom régi 
Zólyombrézó 
Zólyombúcs-Osztroluka 
Zólyomi vasgyár 
Zólyomkecskés 
Zólyomlipcse 
Zombor 
Zombor vásártér 
Zorkovac 
Zorkovac-Polje 
Zrenjanin Predgrađe 
Zrínyitelep 
Zrmanja 
Žrnovac 
Zsablya 
Zsarkovác 
Zsarnóca 
Zsarnóca-Alsóhámor 
Zsebes 
Zsédeny mh. 
Zsédeny-Rózsamajor 
Zseliz 
Zséna 
Zsibó 
Zsidovin 
Zsidve 
Zsigárd 
Zsigárd felső 
Zsilkorojesd 
Zsitvafödémes 
Zsitvamártonfalva 
Zsitvaújfalu 
Zsobok 
Zsófialiget 
Zsögödfürdő 
Zsolna 
Zsolnai téglagyár 
Zsolnay-rakodó 
Zsombolya 
Zsujta 
Zsujta-Átrakó 
Zsuk 
Zsuppa 
Zsurk 
Zugló 
Županja 
Županjei szávapart 
Zurány 
Žutnica 
Zvečaj 
Zvolen osobná stanica 
Zvolen-Bučina 
Zwardoň"""

állomások_tomb = állomások.split("\n")

inserts = 0
iteration = 0

tomb = []

#tábla feltöltése
for i in range(0, 100):
    c_tmp = Coord(random.randint(0,100), random.randint(0,100))
    sql = f"insert into allomas(Nev, Coord_x, Coord_y) VALUES ('{állomások_tomb[i]}',{c_tmp.x},{c_tmp.y})"
    mycursor.execute(sql)
    mydb.commit()
    inserts +=1
    iteration +=1

mycursor.execute("select * from allomas")
result = mycursor.fetchall() #tuple list

votma = [] # hogy ne legyen benne a táv visszafele
egybe = [] # képt pont közötti táv, tárolva: ponta, pontb, táv
for kulso in result:
    for belso in result:
        if belso[0] == kulso[0]: # ID compare
            continue
        tav = (int(kulso[2]) - int(belso[3]))**2 + (int(kulso[2]) - int(belso[3]))**2
        tuple_tmp = (kulso[0],belso[0])
        tmp_tuple = (belso[0],kulso[0])
        tpl = (kulso[0], belso[0], tav, kulso[1], belso[1])
        if tuple_tmp not in votma and tmp_tuple not in votma:
            votma.append(tuple_tmp)
            votma.append(tmp_tuple)
            tomb.append(tav)
            egybe.append(tpl)
        iteration +=1
    iteration +=1

ossz = 0
nagyobb_mint_atlag = 0
for x in tomb:
    ossz += x
    iteration +=1

for x in tomb:
    if x >= ossz/len(tomb):
        nagyobb_mint_atlag +=1
    iteration +=1

print(f"Összes útvonal elemszáma: {len(tomb)}\nÁtlag: {ossz/len(tomb)}\nNagyobb mint átlag: {nagyobb_mint_atlag}")

#utvonalak beillesztése
for x in egybe:
    if math.sqrt(int(x[2])) >= 130:
        sql = f"insert into utvonal(Ut_nev, PointA, PointB) values ('{x[3]} <--> {x[4]} - útvonal',{x[0]},{x[1]})"
        mycursor.execute(sql)
        mydb.commit()
        inserts +=1
    iteration +=1

mycursor.execute("""select allomas1.Coord_x as 'PointA_x', allomas1.Coord_y as 'PointA_y', allomas1.ID as 'PointA_ID', allomas2.Coord_x as 'PointB_x', allomas2.Coord_y as 'PointB_y', allomas2.ID as 'PointB_ID', utvonal.ID
from utvonal
inner join allomas allomas1 on utvonal.PointA = allomas1.ID
inner join allomas allomas2 on utvonal.PointB = allomas2.ID;""")
utvonal = mycursor.fetchall() #tuple ami: pointax pointay pointaid pointbx pointby pointbid utvonalid

mycursor.execute("select * from allomas")
allomas = mycursor.fetchall() #tuple list

time.sleep(3)

def recursive(curr, length, end, uid):
    min_len = length
    tpl = None
    for kulso in allomas:
        if int(kulso[0]) == int(curr[2]):
            continue
        x = int(kulso[2])-int(curr[0])
        y = int(kulso[3])-int(curr[1])
        leng = x**2+y**2
        if math.sqrt(leng) <=45:
            curr_x = int(end[0])-int(kulso[2])
            curr_y = int(end[1])-int(kulso[3])
            curr_len = curr_x**2+curr_y**2
            if curr_len <= min_len:
                min_len = curr_len
                tpl = (kulso[2], kulso[3], kulso[0])

    if tpl == None:
        return
    if tpl[2] == end[2]:
        return
        
    query = f"insert into allomas_utvonal(AllomasID, UtvonalID) values('{tpl[0]}','{uid}')"
    mycursor.execute(query)
    mydb.commit()
    recursive(curr=tpl, length=curr_len, end=end, uid=uid)

for x in utvonal:
    start = (x[0],x[1],x[2])
    end = (x[3],x[4],x[5])

    ax = int(end[0])-int(start[0])
    ay = int(end[1])-int(start[1])
    leng = ax**2+ay**2
    recursive(curr=start, length=leng, end=end, uid=x[6])
    print(x[6])


query = "insert into felhasznalo(Nev, Passwd, UserName, IsAdmin) values ('Lajos', md5('alma'), 'Béla', true);"
mycursor.execute(query)
mydb.commit()

query = "insert into felhasznalo(Nev, Passwd, UserName, Telefon, Email) values ('Kedves Károly', md5('körte'), 'Károly', '+36-12-456-7890', 'abc@example.com');"
mycursor.execute(query)
mydb.commit()

print(f"Done in: {time.time()-startt}")
print(f"Összes beillesztés: {inserts}\nÖsszes iteráció: {iteration}")