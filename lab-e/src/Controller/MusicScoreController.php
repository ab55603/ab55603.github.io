<?php
namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\MusicScore;
use App\Service\Router;
use App\Service\Templating;

class MusicScoreController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        $scores = MusicScore::findAll();
        $html = $templating->render('musicscore/index.html.php', [
            'scores' => $scores,
            'router' => $router,
        ]);
        return $html;
    }

    private function validate(array $data): array
    {
        $errors = [];
        if (empty(trim($data['composer'] ?? ''))) {
            $errors['composer'] = 'Composer is required.';
        }
        if (empty(trim($data['title'] ?? ''))) {
            $errors['title'] = 'Title is required.';
        }
        if (!empty(trim($data['tempo_modifier'] ?? '')) && empty(trim($data['tempo'] ?? ''))) {
            $errors['tempo_modifier'] = 'You must specify tempo before adding a tempo modifier.';
        }
        return $errors;
    }

    public function createAction(?array $requestScore, Templating $templating, Router $router): ?string
    {
        $errors = [];
        if ($requestScore) {
            $errors = $this->validate($requestScore);
            if (!$errors) {
                $score = MusicScore::fromArray($requestScore);
                $score->save();
                $path = $router->generatePath('musicscore-index');
                $router->redirect($path);
                return null;
            }
        } else {
            $score = new MusicScore();
        }

        $html = $templating->render('musicscore/create.html.php', [
            'score' => $score,
            'router' => $router,
        ]);
        return $html;
    }

    public function editAction(int $scoreId, ?array $requestScore, Templating $templating, Router $router): ?string
    {
        $score = MusicScore::find($scoreId);
        if (!$score) {
            throw new NotFoundException("Missing music score with id $scoreId");
        }
        $errors = [];
        if ($requestScore) {
            $errors = $this->validate($requestScore);
            if (!$errors) {
                $score->fill($requestScore);
                $score->save();
                $path = $router->generatePath('musicscore-index');
                $router->redirect($path);
                return null;
            }
        }

        $html = $templating->render('musicscore/edit.html.php', [
            'score' => $score,
            'router' => $router,
        ]);
        return $html;
    }

    public function showAction(int $scoreId, Templating $templating, Router $router): ?string
    {
        $score = MusicScore::find($scoreId);
        if (!$score) {
            throw new NotFoundException("Missing music score with id $scoreId");
        }

        $html = $templating->render('musicscore/show.html.php', [
            'score' => $score,
            'router' => $router,
        ]);
        return $html;
    }

    public function deleteAction(int $scoreId, Router $router): ?string
    {
        $score = MusicScore::find($scoreId);
        if (!$score) {
            throw new NotFoundException("Missing music score with id $scoreId");
        }

        $score->delete();
        $path = $router->generatePath('musicscore-index');
        $router->redirect($path);
        return null;
    }
}
