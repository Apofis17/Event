CREATE SEQUENCE profile_user_id;

create table profileuser(
    id integer not null DEFAULT nextval('profile_user_id') primary key,
    login varchar(80) not null, 
    password varchar(150) not null, 
    date_registration date not null default current_date, 
    is_active boolean not null default false, 
    is_superuser boolean not null default false
);


CREATE SEQUENCE profile_event_id;

create table profileevent(
    id integer not null DEFAULT nextval('profile_event_id') primary key,
    user_id integer not null, 
    coordinates varchar(200) not null,
    message varchar(200),
    date_start date not null,
    date_stop date not null,
    FOREIGN KEY (user_id) REFERENCES profile_user(id) ON DELETE CASCADE on update cascade,
);

create table imageevent(
    id integer not null DEFAULT nextval('event_image_id') primary key,
    event_id integer not null,
    image varchar(200) not null
    FOREIGN KEY (event_id) REFERENCES profileevent(id) ON DELETE CASCADE on update cascade,
);

CREATE SEQUENCE correspondence_id;

create table correspondence(
    id integer not null DEFAULT nextval('correspondence_id') primary key,
    event_id integer not null,
    user_id integer not null,
    parent integer DEFAULT -1,
    text text not null,
    FOREIGN KEY (user_id) REFERENCES profileuser(id) ON DELETE CASCADE on update cascade,
    FOREIGN KEY (event_id) REFERENCES profileevent(id) ON DELETE CASCADE on update cascade
);