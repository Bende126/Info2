drop database if exists mav;

create database mav
	default char set utf8
    default collate utf8_hungarian_ci;
    
use mav;

drop table if exists vonat;
drop table if exists allomas;
drop table if exists utvonal;

drop table if exists felhasznalo;
drop table if exists jegy;

drop table if exists vonat_utvonal;
drop table if exists allomas_utvonal;
drop table if exists jegy_felhasznalo;

create table vonat(
	ID int primary key auto_increment,
    Nev varchar(30) not null,
    Kapacitas int not null
);

create table allomas(
	ID int primary key auto_increment,
    Nev varchar(255) not null,
    Coord_x int not null,
    Coord_y int not null
);

create table utvonal(
	ID int primary key auto_increment,
    Ut_nev varchar(255) not null,
    PointA int not null,
    PointB int not null,
    
    foreign key (PointA) references allomas(ID),
    foreign key (PointB) references allomas(ID)
);

-- regisztrációs adatok tárolása az utassal együtt
-- szebb megoldás lenne, ha ezt még ketté szednénk, de ezen a vasúton egy felhasználó = egy utas
create table felhasznalo(
	ID int primary key auto_increment,
    Nev varchar(60) not null,
    Telefon varchar(45) default null,
    Email varchar(45) default null,
    
    UserName varchar(20) not null unique,
    Passwd varchar(255) not null,
    IsAdmin bool default false,
    Style varchar(10) not null default 'default'
);

-- akkor tudsz jegyet venni, ha be vagy jelentkezve
-- azért kell az utas, mert névre szóló a jegy
create table jegy(
	ID int primary key auto_increment,
    Honnan int not null,
    Hova int not null,
    Utas int not null,
    Ervenyes date not null,
    
    foreign key (Honnan) references allomas(ID),
    foreign key (Hova) references allomas(ID),
    foreign key (Utas) references felhasznalo(ID)
);

-- a vonat éppen az adott útvonalon közlekedik
create table vonat_utvonal(
	ID int primary key auto_increment,
    UtvonalID int not null,
    VonatID int not null,
    
    foreign key (UtvonalID) references utvonal(ID),
    foreign key (VonatID) references vonat(ID)
);

-- két állomás között csinálunk kapcsolatot az útvonallal, de ha a 2 állomás között van egy 3. állomás is, akkor azt az állomást fel tudjuk fűzni az utvonalra
-- 3 vagy több ugyanazon az úton lévő állomás kezeléséhez elég lesz egy darab útvonal az n-1db útvonal helyett
-- egy állomás több útvonalhoz is tartozhat, több sínpár van egy adott állomáson
create table allomas_utvonal(
	ID int primary key auto_increment,
    AllomasID int not null,
    UtvonalID int not null,
    
    foreign key (UtvonalID) references utvonal(ID),
    foreign key (AllomasID) references allomas(ID)
);

-- csak a weboldalon regisztrál emberek utazhatnak, de ők bármennyi jegyet vehetnek
create table jegy_felhasznalo(
	ID int primary key auto_increment,
    JegyID int not null,
    FelhasznaloID int not null,
    
    foreign key (JegyID) references jegy(ID),
    foreign key (FelhasznaloID) references felhasznalo(ID)
);
