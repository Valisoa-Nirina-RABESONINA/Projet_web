
create table tbl_menu (
	id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
	text_menu character varying(1024)
)

alter table tbl_menu add description_menu character varying (2048)
create unique index idx_menu_text on tbl_menu(text_menu)

create table tbl_page_history (
	id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
	id_menu BIGINT ,
	date_access timestamp without time zone default now(),

	CONSTRAINT fk_page_history_menu_id
        FOREIGN KEY (id_menu)
        REFERENCES tbl_menu(id)
)

CREATE TABLE tbl_page (
        id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
        id_menu bigint,
        titre_page character varying (1024),
        lien_page character varying (2048)
)

ALTER TABLE tbl_page ADD CONSTRAINT fk_page_id_menu
	FOREIGN KEY (id_menu)
	REFERENCES tbl_menu (id) on delete cascade
	
insert into tbl_menu (text_menu, description_menu) values ('Oracle', 'Tous les sujets qui touche oracle')