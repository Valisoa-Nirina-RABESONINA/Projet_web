create table if not exists papao.auteur(
	id integer generated always as identity primary key,
	auteur_nom character varying(64),
	auteur_annee_naissance numeric(4, 0),
	auteur_nationalite character varying(32)
);

create table if not exists papao.boky_genre (
	id integer generated always as identity primary key,
	valeur character varying(64)
);

Create table if not exists papao.boky (
	id integer generated always as identity primary key,
	b_titre character varying(64),
	b_nbr_pages integer,
	b_auteur_id integer, -- reference depuis la table papao.boky_auteur
	b_prix integer,
	b_genre_id integer,-- reference depuis la table papao.boky_genre
	b_etiquettes integer[] -- reference depuis la table papao.all_etiquettes 
);

alter table papao.boky add constraint fk_boky_auteur foreign key (b_auteur_id) references papao.auteur(id);
alter table papao.boky add constraint fk_boky_genre foreign key (b_genre_id) references papao.boky_genre(id);
