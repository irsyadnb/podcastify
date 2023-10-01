<?php

require_once BASE_URL . "/src/app/helpers/ResponseHelper.php";
require_once SERVICES_DIR . "/podcast/PodcastService.php";
require_once SERVICES_DIR . "/upload/UploadService.php";
require_once BASE_URL . "/src/app/views/components/podcast/episodesList.php";

class PodcastController extends BaseController
{
    private $podcast_service;

    public function __construct() {
        $this->podcast_service = new PodcastService();
    }

    public function index()
    {
        switch ($_SERVER["REQUEST_METHOD"]) {
            case "GET":
                $isAjax = isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] === "XMLHttpRequest";

                $podcasts = $this->podcast_service->getAllPodcast();

                $data["podcasts"] = $podcasts;

                if ($isAjax) {
                    $this->view("pages/podcast/index", $data);
                } else {
                    // If it"s not an AJAX request, include the full HTML structure
                    $this->view("layouts/default", $data);
                }
                return;

            default:
                ResponseHelper::responseNotAllowedMethod();
                return;
        }
    }

    // /podcast/{id}
    public function podcast($id) {
        try {
            switch ($_SERVER["REQUEST_METHOD"]) {
                case "GET":
                    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

                    $data["podcast"] = $this->podcast_service->getPodcastById($id);
                    $data["episodes"] = $this->podcast_service->getEpisodesByPodcastId($id, 2, 1);

                    $this->view("pages/podcast/podcast_detail", $data);
                    return ResponseHelper::HTTP_STATUS_OK;

                default:
                    ResponseHelper::responseNotAllowedMethod();
                    return;
            }
        } catch (Exception $e) {
            $this->view('layouts/error');
            exit;
        }
    }

    // /episodes?page=
    public function episodes($id) {
        try {
            switch ($_SERVER["REQUEST_METHOD"]) {
                case "GET":
                    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
                    $page = isset($_GET["page"]) ? filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT) : 1;

                    $data["episodes"] = $this->podcast_service->getEpisodesByPodcastId($id, 2, $page);

                    return episodeList($data["episodes"]);

                default:
                    ResponseHelper::responseNotAllowedMethod();
                    return;
            }
        } catch (Exception $e) {
            $this->view('layouts/error');
            exit;
        }
    }

    // /search?key=
    public function search() {
        try {
            switch ($_SERVER["REQUEST_METHOD"]) {
                case "GET":
                    $search_key = isset($_GET["key"]) ? filter_var($_GET["key"], FILTER_SANITIZE_STRING) : "";

                    $podcasts = $this->podcast_service->getPodcastBySearch($search_key);

                    include VIEWS_DIR . "pages/podcast/index.php";
                    return ResponseHelper::HTTP_STATUS_OK;

                default:
                    ResponseHelper::responseNotAllowedMethod();
                    return;
            }
        } catch (Exception $e) {
            $this->view('layouts/error');
            exit;
        }
    }

    // /edit/{id}
    public function edit($id) {
        try {
            $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

            switch ($_SERVER["REQUEST_METHOD"]) {
                case "GET":
                    $data["podcast"] = $this->podcast_service->getPodcastById($id);
                    $data["type"] = "edit";

                    $this->view("pages/podcast/podcast_management", $data);
                    return ResponseHelper::HTTP_STATUS_OK;

                case "PATCH":
                    $data = json_decode(file_get_contents('php://input'), true);

                    $title = filter_var($data['podcast-name-input'], FILTER_SANITIZE_STRING);
                    $creator_name = filter_var($data['podcast-creator-input'], FILTER_SANITIZE_STRING);
                    $description = filter_var($data['podcast-desc-input'], FILTER_SANITIZE_STRING);
                    if (isset($data['preview-image-filename'])) {
                        $image_url = IMAGES_DIR . filter_var($data['preview-image-filename'], FILTER_SANITIZE_STRING);
                    } else {
                        $image_url = "";
                    }

                    $this->podcast_service->updatePodcast($id, $title, $description, $creator_name, $image_url);
                    return ResponseHelper::HTTP_STATUS_OK;

                case "DELETE":
                    $this->podcast_service->deletePodcast($id);
                    return;

                default:
                    ResponseHelper::responseNotAllowedMethod();
                    return;
            }
        } catch (Exception $e) {
            $this->view('layouts/error');
            exit;
        }
    }

    // /create
    public function create() {
        try {
            switch ($_SERVER["REQUEST_METHOD"]) {
                case "GET":
                    $data["type"] = "create";

                    $this->view("pages/podcast/podcast_management", $data);
                    break;

                case "POST":
                    $title = filter_var($_POST['podcast-name-input'], FILTER_SANITIZE_STRING);
                    $creator_name = filter_var($_POST['podcast-creator-input'], FILTER_SANITIZE_STRING);
                    $description = filter_var($_POST['podcast-desc-input'], FILTER_SANITIZE_STRING);
                    $image_url = IMAGES_DIR . filter_var($_POST['preview-image-filename'], FILTER_SANITIZE_STRING);

                    $this->podcast_service->createPodcast($title, $description, $creator_name, $image_url);
                    break;

                default:
                    ResponseHelper::responseNotAllowedMethod();
                    return;
            }
        } catch (Exception $e) {
            $this->view('layouts/error');
            exit;
        }
    }

    // /upload
    public function upload() {
        try {
            switch ($_SERVER["REQUEST_METHOD"]) {
                case "POST":
                    UploadService::upload();
                    break;

                default:
                    ResponseHelper::responseNotAllowedMethod();
                    break;
            }
        } catch (Exception $e) {
            $this->view('layouts/error');
            exit;
        }
    }
}