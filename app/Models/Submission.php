<?php

namespace App\Models;

use App\Core\Database;

class Submission {
    private $db;

    public function __construct() {
        $dbInstance = new Database();
        $this->db = $dbInstance->getConnection();
    }

    public function create( array $data ): bool {
        $amount = (int) $data['amount'];
        $buyer = $this->db->real_escape_string( $data['buyer'] );
        $receiptId = $this->db->real_escape_string( $data['receipt_id'] );
        $items = $this->db->real_escape_string( $data['items'] );
        $buyerEmail = $this->db->real_escape_string( $data['buyer_email'] );
        $buyerIp = $this->db->real_escape_string( $data['buyer_ip'] );
        $note = $this->db->real_escape_string( $data['note'] );
        $city = $this->db->real_escape_string( $data['city'] );
        $phone = $this->db->real_escape_string( $data['phone'] );
        $hashKey = $this->db->real_escape_string( $data['hash_key'] );
        $entryAt = $data['entry_at'];
        $entryBy = (int) $data['entry_by'];

        $sql = "INSERT INTO submissions (amount, buyer, receipt_id, items, buyer_email, buyer_ip, note, city, phone, hash_key, entry_at, entry_by)
                VALUES ($amount, '$buyer', '$receiptId', '$items', '$buyerEmail', '$buyerIp', '$note', '$city', '$phone', '$hashKey', '$entryAt', $entryBy)";

        return $this->db->query( $sql );
    }

    public function getSubmissions( string $startDate = null, string $endDate = null, int $userId = null ): array {
        $where = [];

        if ( $startDate && $endDate ) {
            $startDate = $this->db->real_escape_string( $startDate );
            $endDate = $this->db->real_escape_string( $endDate );
            $where[] = "s.entry_at BETWEEN '$startDate' AND '$endDate'";
        }

        if ( $userId ) {
            $where[] = "s.entry_by = $userId";
        }

        $whereClause = !empty( $where ) ? "WHERE " . implode( ' AND ', $where ) : "";

        $sql = "SELECT s.*, u.name AS entry_by_name
                FROM submissions s
                JOIN users u ON s.entry_by = u.id
                $whereClause
                ORDER BY s.entry_at DESC";

        $result = $this->db->query( $sql );

        $submissions = [];
        if ( $result ) {
            while ( $row = $result->fetch_assoc() ) {
                $submissions[] = $row;
            }
        }
        return $submissions;
    }
}