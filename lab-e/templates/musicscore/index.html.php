<?php

/** @var \App\Model\MusicScore[] $scores */
/** @var \App\Service\Router $router */

$title = 'Score List';
$bodyClass = 'index';

$grouped = [];
foreach ($scores as $score) {
    $composer = trim((string) $score->getComposer());
    $grouped[$composer][] = $score;
}
ksort($grouped, SORT_NATURAL | SORT_FLAG_CASE);

ob_start(); ?>
    <h1>List of Music Scores</h1>

    <a href="<?= $router->generatePath('musicscore-create') ?>">Create new</a>

    <ul class="index-list">
        <?php foreach ($grouped as $composer => $works): ?>
            <li>
                <h3><?= htmlspecialchars($composer) ?> (<?= count($works) ?>)</h3>
                <ul>
                    <?php foreach ($works as $score): ?>
                        <li>
                            <a href="<?= $router->generatePath('musicscore-show', ['id' => $score->getId()]) ?>">
                                <?= htmlspecialchars((string) $score->getTitle()) ?>
                            </a>
                            <?php if ($score->getTempo() !== null && $score->getTempo() !== ''): ?>
                                <span>— <?= htmlspecialchars((string) $score->getTempo()) ?></span>
                            <?php endif; ?>
                            <a href="<?= $router->generatePath('musicscore-edit', ['id' => $score->getId()]) ?>">Edit</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
