<?php

namespace App\Controllers;

use App\Core\Session;
use App\Models\Submission;

class SubmissionController {
    private $submissionModel;
    private $salt = 'your_long_secure_salt_string_here';

    public function __construct() {
        $this->submissionModel = new Submission();
    }

    private function getClientIp(): string {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    private function validateBackend( array $data ): array {
        $errors = [];

        $amount = filter_var( $data['amount'] ?? 0, FILTER_SANITIZE_NUMBER_INT );
        if ( !is_numeric( $amount ) || $amount <= 0 ) {
            $errors[] = "Amount must be a positive number.";
        }

        if ( !isset( $data['buyer'] ) || !preg_match( '/^[a-zA-Z0-9\s]{1,20}$/', $data['buyer'] ) ) {
            $errors[] = "Buyer must be 1-20 characters (text/numbers/spaces).";
        }

        if ( !isset( $data['receipt_id'] ) || !preg_match( '/^[a-zA-Z0-9]+$/', $data['receipt_id'] ) ) {
            $errors[] = "Receipt ID must be alphanumeric text.";
        }

        if ( !isset( $data['items'] ) || empty( trim( $data['items'] ) ) ) {
            $errors[] = "Items field is required.";
        }

        if ( !isset( $data['buyer_email'] ) || !filter_var( $data['buyer_email'], FILTER_VALIDATE_EMAIL ) ) {
            $errors[] = "Invalid buyer email format.";
        }

        $wordCount = str_word_count( $data['note'] ?? '' );
        if ( $wordCount > 30 ) {
            $errors[] = "Note must not exceed 30 words. (Currently $wordCount)";
        }

        if ( !isset( $data['city'] ) || !preg_match( '/^[a-zA-Z\s]+$/', $data['city'] ) ) {
            $errors[] = "City must contain only text and spaces.";
        }

        if ( !isset( $data['phone'] ) || !preg_match( '/^880\d{10}$/', $data['phone'] ) ) {
            $errors[] = "Phone must be in the format 880XXXXXXXXXX.";
        }

        if ( !Session::check() || !is_numeric( Session::get( 'user_id' ) ) ) {
            $errors[] = "System Error: User session required for entry.";
        }

        return $errors;
    }

    public function showForm() {
        if ( !Session::check() ) {
            header( 'Location: /login' );
            exit;
        }

        $canSubmit = !isset( $_COOKIE['last_submission'] );

        include __DIR__ . '/../../views/submission/form.php';
    }

    public function submitAjax() {
        header( 'Content-Type: application/json' );

        if ( !Session::check() ) {
            http_response_code( 401 );
            echo json_encode( ['success' => false, 'message' => 'Unauthorized.'] );
            exit;
        }

        if ( isset( $_COOKIE['last_submission'] ) ) {
            http_response_code( 403 );
            echo json_encode( ['success' => false, 'message' => 'You can only submit once every 24 hours.'] );
            exit;
        }

        $data = $_POST;

        $errors = $this->validateBackend( $data );

        if ( !empty( $errors ) ) {
            http_response_code( 400 ); // Bad Request
            echo json_encode( ['success' => false, 'message' => 'Validation failed.', 'errors' => $errors] );
            exit;
        }

        $data['buyer_ip'] = $this->getClientIp();
        $data['hash_key'] = hash( 'sha512', $data['receipt_id'] . $this->salt );
        $data['entry_at'] = date( 'Y-m-d' );
        $data['entry_by'] = Session::get( 'user_id' );

        if ( $this->submissionModel->create( $data ) ) {
            setcookie( 'last_submission', time(), time() + 86400, '/' );

            echo json_encode( ['success' => true, 'message' => 'Data submitted successfully.'] );
        } else {
            http_response_code( 500 ); // Server Error
            echo json_encode( ['success' => false, 'message' => 'Failed to save data.'] );
        }
    }
}