<?php

namespace App\Model;

use App\Service\Config;

class MusicScore
{
    private ?int $id = null;
    private ?string $composer = null;
    private ?string $title = null;
    private ?string $tempo = null;
    private ?string $tempo_modifier = null;
    private ?string $catalogue_number = null;
    private ?string $instrument = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): MusicScore
    {
        $this->id = $id;

        return $this;
    }

    public function getComposer(): ?string
    {
        return $this->composer;
    }

    public function setComposer(?string $composer): MusicScore
    {
        $this->composer = $composer;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): MusicScore
    {
        $this->title = $title;

        return $this;
    }

    public function getTempo(): ?string
    {
        return $this->tempo;
    }

    public function setTempo(?string $tempo): MusicScore
    {
        $this->tempo = $tempo;

        return $this;
    }

    public function getTempoModifier(): ?string
    {
        return $this->tempo_modifier;
    }

    public function setTempoModifier(?string $tempo_modifier): MusicScore
    {
        $this->tempo_modifier = $tempo_modifier;

        return $this;
    }

    public function getCatalogueNumber(): ?string
    {
        return $this->catalogue_number;
    }

    public function setCatalogueNumber(?string $catalogue_number): MusicScore
    {
        $this->catalogue_number = $catalogue_number;

        return $this;
    }

    public function getInstrument(): ?string
    {
        return $this->instrument;
    }

    public function setInstrument(?string $instrument): MusicScore
    {
        $this->instrument = $instrument;

        return $this;
    }

    public static function fromArray($array): MusicScore
    {
        $musicScore = new self();
        $musicScore->fill($array);

        return $musicScore;
    }

    public function fill($array): MusicScore
    {
        if (isset($array['id']) && ! $this->getId()) {
            $this->setId((int) $array['id']);
        }

        if (isset($array['composer'])) {
            $this->setComposer($array['composer']);
        }

        if (isset($array['title'])) {
            $this->setTitle($array['title']);
        }

        if (isset($array['tempo'])) {
            $this->setTempo($array['tempo']);
        }

        if (isset($array['tempo_modifier'])) {
            $this->setTempoModifier($array['tempo_modifier']);
        }

        if (isset($array['catalogue_number'])) {
            $this->setCatalogueNumber($array['catalogue_number']);
        }

        if (isset($array['instrument'])) {
            $this->setInstrument($array['instrument']);
        }

        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(
            Config::get('db_dsn'),
            Config::get('db_user'),
            Config::get('db_pass')
        );

        $sql = 'SELECT * FROM musicscore';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $musicScores = [];
        $musicScoresArray = $statement->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($musicScoresArray as $musicScoreArray) {
            $musicScores[] = self::fromArray($musicScoreArray);
        }

        return $musicScores;
    }

    public static function find($id): ?MusicScore
    {
        $pdo = new \PDO(
            Config::get('db_dsn'),
            Config::get('db_user'),
            Config::get('db_pass')
        );

        $sql = 'SELECT * FROM musicscore WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);

        $musicScoreArray = $statement->fetch(\PDO::FETCH_ASSOC);

        if (! $musicScoreArray) {
            return null;
        }

        return self::fromArray($musicScoreArray);
    }

    public function save(): void
    {
        $pdo = new \PDO(
            Config::get('db_dsn'),
            Config::get('db_user'),
            Config::get('db_pass')
        );

        if (! $this->getId()) {
            $sql = 'INSERT INTO musicscore
                (composer, title, tempo, tempo_modifier, catalogue_number, instrument)
                VALUES
                (:composer, :title, :tempo, :tempo_modifier, :catalogue_number, :instrument)';

            $statement = $pdo->prepare($sql);
            $statement->execute([
                'composer' => $this->getComposer(),
                'title' => $this->getTitle(),
                'tempo' => $this->getTempo(),
                'tempo_modifier' => $this->getTempoModifier(),
                'catalogue_number' => $this->getCatalogueNumber(),
                'instrument' => $this->getInstrument(),
            ]);

            $this->setId((int) $pdo->lastInsertId());
        } else {
            $sql = 'UPDATE musicscore SET
                composer = :composer,
                title = :title,
                tempo = :tempo,
                tempo_modifier = :tempo_modifier,
                catalogue_number = :catalogue_number,
                instrument = :instrument
                WHERE id = :id';

            $statement = $pdo->prepare($sql);
            $statement->execute([
                'composer' => $this->getComposer(),
                'title' => $this->getTitle(),
                'tempo' => $this->getTempo(),
                'tempo_modifier' => $this->getTempoModifier(),
                'catalogue_number' => $this->getCatalogueNumber(),
                'instrument' => $this->getInstrument(),
                'id' => $this->getId(),
            ]);
        }
    }

    public function delete(): void
    {
        $pdo = new \PDO(
            Config::get('db_dsn'),
            Config::get('db_user'),
            Config::get('db_pass')
        );

        $sql = 'DELETE FROM musicscore WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute([
            'id' => $this->getId(),
        ]);

        $this->setId(null);
        $this->setComposer(null);
        $this->setTitle(null);
        $this->setTempo(null);
        $this->setTempoModifier(null);
        $this->setCatalogueNumber(null);
        $this->setInstrument(null);
    }
}