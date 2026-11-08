create table musicscore
(
    id      integer not null
        constraint musicscore_pk
            primary key autoincrement,
    composer text not null,
    title text not null,
    tempo text,
    tempo_modifier text,
    catalogue_number text,
    instrument text
);
