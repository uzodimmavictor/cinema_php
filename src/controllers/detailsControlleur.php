<?php
class detailsController {
    private $model;

    public function __construct() {
        $this->model = new detailsModel();
    }

    public function showFilmDetails($filmId) {
        $film = $this->model->getFilmById($filmId);
        $sceances = $this->model->getSceancesByFilmId($filmId);
        if ($film) {
            include __DIR__ . '/../views/details.php';
        } else {
            echo "Film not found.";
        }
    }
}