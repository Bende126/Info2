use mav;

select * from utvonal;
select * from allomas;
select * from allomas_utvonal;
select * from felhasznalo;
select * from vonat;
select * from vonat_utvonal;
select * from jegy;
select * from jegy_felhasznalo;

delete from jegy where ID = '14';
delete from jegy_felhasznalo where ID = '2';
delete from felhasznalo where UserName = 'Károly';
delete from felhasznalo where UserName = 'Béla';

-- teszt felhasználók
insert into felhasznalo(Nev, Passwd, UserName, IsAdmin) values ('Lajos', md5('alma'), 'Béla', true);
insert into felhasznalo(Nev, Passwd, UserName, Telefon, Email) values ('Kedves Károly', md5('körte'), 'Károly', 'valami ide', 'ide valami');

insert into vonat(Nev, Kapacitas) values('Déi parti sebes', 210);
insert into vonat_utvonal(ID, VonatID, UtvonalID) values (3,1,1);
delete from vonat_utvonal where ID = '2';

update vonat_utvonal
set ID = 2, UtvonalID = 1, VonatID = 1
where ID = 1;

insert into jegy(Honnan, Hova, Utas, Ervenyes) values ('68','66', '1', '2022-05-20');

select vn.* from vonat_utvonal
inner join vonat vn on vn.ID = vonat_utvonal.VonatID
where UtvonalID = '1';

select current_date();

insert into jegy(Honnan, Hova, Utas, Ervenyes) values('1', '2', '1', date_add(curdate(), interval 1 week));
insert into jegy_felhasznalo(JegyID, FelhasznaloID) values('1','1');

select ID from jegy
order by ID desc;

select allomas1.Nev as 'Honnan', allomas2.Nev as 'Hova', jg.Ervenyes from felhasznalo
inner join jegy_felhasznalo jhf on jhf.FelhasznaloID = felhasznalo.ID
inner join jegy jg on jg.ID = jhf.JegyID
inner join allomas allomas1 on allomas1.ID = jg.Honnan
inner join allomas allomas2 on allomas2.ID = jg.Hova
where felhasznalo.ID = '1' and jg.Ervenyes < curdate();

-- svg végpontok
select allomas1.Coord_x as 'PointA_x', allomas1.Coord_y as 'PointA_y', allomas1.ID as 'PointA_ID', allomas2.Coord_x as 'PointB_x', allomas2.Coord_y as 'PointB_y', allomas2.ID as 'PointB_ID', utvonal.ID
from utvonal
inner join allomas allomas1 on utvonal.PointA = allomas1.ID
inner join allomas allomas2 on utvonal.PointB = allomas2.ID
where utvonal.ID = '1';

-- svg alapja
select allomas1.ID as 'PointA_ID', allomas2.ID as 'PointB_ID', utvonal.ID
from utvonal
inner join allomas allomas1 on utvonal.PointA = allomas1.ID
inner join allomas allomas2 on utvonal.PointB = allomas2.ID;

-- allomas_utvonal állomásainak útvonalhoz rendelése. svg köztespontok
select alu.*, allomas1.* from utvonal
inner join allomas_utvonal alu on alu.UtvonalID = utvonal.ID
inner join allomas allomas1 on allomas1.ID = alu.AllomasID
where utvonal.id = '1';

-- reszponzív jegyvásárlás
-- 2 és 21 id-jű pontok közötti útvonal
select allomas1.ID as 'ID1', allomas1.Nev as 'Nev1', allomas2.ID as 'ID2', allomas2.Nev as 'Nev2', alo.ID as 'ID3', alo.Nev as 'Nev3', utvonal.ID, utvonal.Ut_nev
from utvonal
inner join allomas allomas1 on utvonal.PointA = allomas1.ID
inner join allomas allomas2 on utvonal.PointB = allomas2.ID
inner join allomas_utvonal alu on alu.UtvonalID = utvonal.ID
inner join allomas alo on alu.AllomasID = alo.ID
where (allomas1.ID = '2' or allomas2.ID = '2' or alo.ID = '2') 
and (allomas1.ID = '21' or allomas2.ID = '21' or alo.ID = '21');

-- allomas_utvonal állomásai specifikus útvonalhoz
select alo.Nev as 'Nev' from allomas_utvonal
inner join allomas alo on alo.ID = allomas_utvonal.AllomasID
where allomas_utvonal.UtvonalID = '1';

-- az állomás az útvonal kezdő/végpontja
select allomas1.ID as 'ID1', allomas2.ID as 'ID2' from utvonal
inner join allomas allomas1 on allomas1.ID = utvonal.PointA
inner join allomas allomas2 on allomas2.ID = utvonal.PointB
where utvonal.PointA = '31'
or utvonal.PointB = '31';

