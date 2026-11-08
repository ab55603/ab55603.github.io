<?php
    /** @var $score ?\App\Model\MusicScore */
?>

<div class="form-group">
    <label for="composer">Composer</label>
    <input type="text" id="composer" name="score[composer]" value="<?= $score ? $score->getComposer() : '' ?>">
</div>

<div class="form-group">
    <label for="title">Title</label>
    <input type="text" id="title" name="score[title]" value="<?= $score ? $score->getTitle() : '' ?>">
</div>

<div class="form-group">
    <label for="tempo">Tempo</label>
    <input type="text" id="tempo" name="score[tempo]" value="<?= $score ? $score->getTempo() : '' ?>">
</div>

<div class="form-group">
    <label for="tempo_modifier">Tempo modifier</label>
    <input type="text" id="tempo_modifier" name="score[tempo_modifier]" value="<?= $score ? htmlspecialchars($score->getTempoModifier() ?? '') : '' ?>">
</div>

<div class="form-group">
    <label for="catalogue_number">Catalogue number</label>
    <input type="text" id="catalogue_number" name="score[catalogue_number]" value="<?= $score ? htmlspecialchars($score->getCatalogueNumber() ?? '') : '' ?>">
</div>

<div class="form-group">
    <label for="instrument">Instrument</label>
    <input type="text" id="instrument" name="score[instrument]" value="<?= $score ? htmlspecialchars($score->getInstrument() ?? '') : '' ?>">
</div>

<div class="form-group">
    <label></label>
    <input type="submit" value="Submit">
</div>
