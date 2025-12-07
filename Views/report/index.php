<?php

if ( !App\Core\Session::check() ) {
    header( 'Location: /login' );
    exit;
}

$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';
$userId = $_GET['user_id'] ?? '';

$submissions = $submissions ?? [];

$totalAmount = array_sum( array_column( $submissions, 'amount' ) );
$submissionCount = count( $submissions );
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submission Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f4f7f6;
        }
        .table-responsive-custom {
            max-height: 70vh;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
        .table-responsive-custom thead th {
            position: sticky;
            top: 0;
            background-color: #212529;
            color: white;
            z-index: 10;
        }

        .summary-metric {
            border-left: 4px solid var(--bs-primary);
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">Submission Reports</h2>
            <div>
                <a href="/submission/form" class="btn btn-primary me-2">New Submission</a>
                <a href="/logout" class="btn btn-danger">Logout</a>
            </div>
        </div>

        <div class="row mb-4 g-3">
            <div class="col-md-4">
                <div class="card p-3 shadow-sm summary-metric border-0">
                    <p class="text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Total Submissions</p>
                    <h4 class="fw-bold text-primary mb-0"><?php echo number_format( $submissionCount ); ?></h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 shadow-sm summary-metric border-0" style="border-left-color: var(--bs-success) !important;">
                    <p class="text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Total Amount</p>
                    <h4 class="fw-bold text-success mb-0">$<?php echo number_format( $totalAmount, 2 ); ?></h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 shadow-sm summary-metric border-0" style="border-left-color: var(--bs-secondary) !important;">
                    <p class="text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Current User</p>
                    <h4 class="fw-bold text-secondary mb-0"><?php echo htmlspecialchars( App\Core\Session::get( 'user_name' ) ); ?></h4>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">Filter Submissions</h5>
            </div>
            <div class="card-body">
                <form action="/report" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars( $startDate ); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars( $endDate ); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="user_id" class="form-label">User ID (Entry By)</label>
                        <input type="number" class="form-control" id="user_id" name="user_id" placeholder="e.g., 1" value="<?php echo htmlspecialchars( $userId ); ?>">
                    </div>
                    <div class="col-md-3 d-flex">
                        <button type="submit" class="btn btn-info w-50">Apply Filter</button>
                        <a href="/report" class="btn btn-secondary ms-2 w-50">Clear Filter</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive-custom shadow-sm">
            <table class="table table-bordered table-striped table-hover table-sm mb-0">
                <thead class="table-dark">
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
                        <th>Entry By (Name)</th>
                        <th>Note</th>
                        <th>Hash Key</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $submissions ) ): ?>
                        <tr>
                            <td colspan="13" class="text-center py-4">No submissions found matching the criteria.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ( $submissions as $sub ): ?>
                        <tr>
                            <td><?php echo htmlspecialchars( $sub['id'] ); ?></td>
                            <td class="fw-bold">$<?php echo number_format( $sub['amount'], 2 ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['buyer'] ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['receipt_id'] ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['items'] ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['buyer_email'] ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['city'] ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['phone'] ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['buyer_ip'] ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['entry_at'] ); ?></td>
                            <td><?php echo htmlspecialchars( $sub['entry_by'] ); ?> (<?php echo htmlspecialchars( $sub['entry_by_name'] ?? 'N/A' ); ?>)</td>
                            <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars( $sub['note'] ); ?>">
                                <?php echo htmlspecialchars( $sub['note'] ); ?>
                            </td>
                            <td style="font-size: 0.7rem; max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars( $sub['hash_key'] ); ?>">
                                <?php echo htmlspecialchars( $sub['hash_key'] ); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>