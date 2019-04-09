drop schema if exists info2;
create schema info2;
use info2;

create table band
(
  id int primary key auto_increment,
  name varchar(45) not null,
  formed_in int null,
  country varchar(45)
);

create table venue
(
  id int primary key auto_increment,
  name varchar(45) not null,
  address varchar(90),
  capacity int
);

create table concert
(
  id int primary key auto_increment,
  venueid int not null,
  date DATE,
  available_tickets tinyint,
  foreign key (venueid) references venue (id)
);

create table concert_has_band
(
  concertid int not null,
  bandid int not null,
  primary key (concertid, bandid),
  foreign key (concertid) references concert(id),
  foreign key (bandid) references band(id)
);