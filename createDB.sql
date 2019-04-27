drop schema if exists concertManager;
create schema concertManager;
use concertManager;

create table band
(
  id int primary key auto_increment,
  name varchar(45) not null,
  formed_in int null,
  country varchar(45)
);

insert into band(name, formed_in, country) values ("Sabaton", 1999, "Svédország");
insert into band(name, formed_in, country) values ("Powerwolf", 2003, "Németország");
insert into band(name, formed_in, country) values ("Hammerfall", 1993, "Svédország");
insert into band(name, formed_in, country) values ("Road", 2004, "Magyarország");
insert into band(name, formed_in, country) values ("Nightwish", 1996, "Finnország");
insert into band(name, formed_in, country) values ("Depresszió", 1999, "Magyarország");

create table venue
(
  id int primary key auto_increment,
  name varchar(45) not null,
  address varchar(90),
  capacity int
);

insert into venue(name, address, capacity) values ("Barba Negra Music Club", "1117 Budapest, Prielle Kornélia utca 4", 1045);
insert into venue(name, address, capacity) values ("Barba Negra Track", "1117 Budapest, Neumann János u. 2", 6000);
insert into venue(name, address, capacity) values ("Papp László Budapest Sportaréna", "1143 Budapest, Stefánia Út 2.", 12500);

create table concert
(
  id int primary key auto_increment,
  venueid int,
  date DATE,
  available_tickets tinyint,
  foreign key (venueid) references venue (id)
);

insert into concert(venueid, date, available_tickets) values (1, '2019-03-24', 1);
insert into concert(venueid, date, available_tickets) values (1, '2019-04-12', 0);
insert into concert(venueid, date, available_tickets) values (1, '2019-04-30', 1);
insert into concert(venueid, date, available_tickets) values (1, '2019-05-04', 1);
insert into concert(venueid, date, available_tickets) values (2, '2019-05-26', 1);
insert into concert(venueid, date, available_tickets) values (2, '2019-06-12', 1);
insert into concert(venueid, date, available_tickets) values (2, '2019-06-24', 1);
insert into concert(venueid, date, available_tickets) values (2, '2019-07-18', 0);
insert into concert(venueid, date, available_tickets) values (3, '2019-09-04', 1);
insert into concert(venueid, date, available_tickets) values (3, '2019-11-06', 1);

create table concert_has_band
(
  concertid int not null,
  bandid int not null,
  primary key (concertid, bandid),
  foreign key (concertid) references concert(id),
  foreign key (bandid) references band(id)
);

insert into concert_has_band(concertid, bandid) values (1,1);
insert into concert_has_band(concertid, bandid) values (1,3);
insert into concert_has_band(concertid, bandid) values (1,6);
insert into concert_has_band(concertid, bandid) values (2,2);
insert into concert_has_band(concertid, bandid) values (2,4);
insert into concert_has_band(concertid, bandid) values (3,5);
insert into concert_has_band(concertid, bandid) values (3,6);
insert into concert_has_band(concertid, bandid) values (4,1);
insert into concert_has_band(concertid, bandid) values (4,2);
insert into concert_has_band(concertid, bandid) values (4,5);
insert into concert_has_band(concertid, bandid) values (5,1);
insert into concert_has_band(concertid, bandid) values (5,6);
insert into concert_has_band(concertid, bandid) values (6,2);
insert into concert_has_band(concertid, bandid) values (6,3);
insert into concert_has_band(concertid, bandid) values (6,6);
insert into concert_has_band(concertid, bandid) values (7,1);
insert into concert_has_band(concertid, bandid) values (7,2);
insert into concert_has_band(concertid, bandid) values (7,4);
insert into concert_has_band(concertid, bandid) values (8,4);
insert into concert_has_band(concertid, bandid) values (8,5);
insert into concert_has_band(concertid, bandid) values (8,6);
insert into concert_has_band(concertid, bandid) values (9,2);
insert into concert_has_band(concertid, bandid) values (9,4);
insert into concert_has_band(concertid, bandid) values (9,5);
insert into concert_has_band(concertid, bandid) values (10,1);
insert into concert_has_band(concertid, bandid) values (10,3);
insert into concert_has_band(concertid, bandid) values (10,4);