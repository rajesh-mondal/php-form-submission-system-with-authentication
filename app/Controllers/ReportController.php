<?php

namespace App\Controllers;

use App\Core\Session;
use App\Models\Submission;

class ReportController {
    private $submissionModel;

    public function __construct() {
        $this->submissionModel = new Submission();
    }

    public function index() {
        if ( !Session::check() ) {
            header( 'Location: /login' );
            exit;
        }

        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $userId = $_GET['user_id'] ?? null;

        $userId = $userId ? (int) $userId : null;

        $submissions = $this->submissionModel->getSubmissions( $startDate, $endDate, $userId );

        include __DIR__ . '/../../views/report/index.php';
    }
}