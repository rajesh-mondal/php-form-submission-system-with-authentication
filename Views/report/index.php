<?php

if ( !App\Core\Session::check() ) {
    header( 'Location: /login' );
    exit;
}

$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';
$userId = $_GET['user_id'] ?? '';

$submissions = $submissions ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submission Reports</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .table-responsive {
            max-height: 70vh;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Submission Reports</h2>
            <div>
                <a href="/submission/form" class="btn btn-primary mr-2">New Submission</a>
                <a href="/logout" class="btn btn-danger">Logout</a>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Filter Submissions</div>
            <div class="card-body">
                <form action="/report" method="GET" class="form-row align-items-end">
                    <div class="form-group col-md-3">
                        <label for="start_date">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars( $startDate ); ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="end_date">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars( $endDate ); ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="user_id">User ID (Entry By)</label>
                        <input type="number" class="form-control" id="user_id" name="user_id" placeholder="e.g., 1" value="<?php echo htmlspecialchars( $userId ); ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-info">Apply Filter</button>
                        <a href="/report" class="btn btn-secondary ml-2">Clear Filter</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark sticky-top">
                    <tr>
                        <th>ID</th>
                        <th>Amount</th>
                        <th>Buyer</th>
                        <th>Receipt ID</th>
                        <th>Items</th>
                        <th>Email</th>
                        <th>City</th>
                        <th>Phone</th>
                        <th>IP</th>
                        <th>Entry At</th>
                        <th>Entry By (User)</th>
                        <th>Note</th>
                        <th>Hash Key</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $submissions ) ): ?>
                        <tr>
                            <td colspan="13" class="text-center">No submissions found matching the criteria.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ( $submissions as $sub ): ?>
                        <tr>
                            <td><?php echo htmlspecialchars( $sub['id'] ); ?></td>
                            <td>$<?php echo number_format( $sub['amount'] ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['buyer'] ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['receipt_id'] ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['items'] ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['buyer_email'] ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['city'] ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['phone'] ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['buyer_ip'] ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['entry_at'] ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['entry_by'] ); ?> (<?php echo htmlspecialchars( $sub['entry_by_name'] ); ?>)</td>
                            <td><?php echo htmlspecialchars( $sub['note'] ); ?></td>
                            <td style="font-size: 0.7rem; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars( $sub['hash_key'] ); ?>">
                                <?php echo htmlspecialchars( $sub['hash_key'] ); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>