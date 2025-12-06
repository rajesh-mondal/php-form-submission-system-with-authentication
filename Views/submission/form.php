<?php $canSubmit = $canSubmit ?? true; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Submission</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Welcome, <?php echo htmlspecialchars( App\Core\Session::get( 'user_name' ) ); ?></h2>
            <div>
                <a href="/report" class="btn btn-info mr-2">View Reports</a>
                <a href="/logout" class="btn btn-danger">Logout</a>
            </div>
        </div>

        <?php if ( !$canSubmit ): ?>
            <div class="alert alert-warning">
                You have already submitted data within the last 24 hours. Please wait before submitting again.
            </div>
        <?php else: ?>
            <div id="alert-container"></div>
            <div class="card">
                <div class="card-header">
                    <h4>Submission Form</h4>
                </div>
                <div class="card-body">
                    <form id="submissionForm">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="amount">Amount</label>
                                <input type="text" class="form-control" id="amount" name="amount" required>
                                <div class="invalid-feedback">Only numbers allowed.</div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="buyer">Buyer (Max 20 chars)</label>
                                <input type="text" class="form-control" id="buyer" name="buyer" maxlength="20" required>
                                <div class="invalid-feedback">Text, spaces, and numbers (max 20 chars).</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="receipt_id">Receipt ID</label>
                                <input type="text" class="form-control" id="receipt_id" name="receipt_id" required>
                                <div class="invalid-feedback">Only alphanumeric text.</div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="buyer_email">Buyer Email</label>
                                <input type="email" class="form-control" id="buyer_email" name="buyer_email" required>
                                <div class="invalid-feedback">Must be a valid email address.</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="items">Items (e.g., item1, item2)</label>
                            <input type="text" class="form-control" id="items" name="items" placeholder="Enter comma-separated items" required>
                            <div class="invalid-feedback">Please list items.</div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                                <div class="invalid-feedback">Only text and spaces.</div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="phone">Phone</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">880</span>
                                    </div>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="XXXXXXXXXX" required>
                                </div>
                                <div class="invalid-feedback">Only 10 numbers after 880 (e.g., 1712345678).</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="note">Note (Max 30 words)</label>
                            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                            <div class="invalid-feedback">Max 30 words.</div>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Data</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            const form = $('#submissionForm');
            const phoneInput = $('#phone');
            const noteInput = $('#note');

            const validateField = (input, regex, errorMessage) => {
                const value = input.val().trim();

                let isMatch = !!value.match(regex);

                if (input.prop('required') && value === '') {
                    isMatch = false;
                }

                input.toggleClass('is-invalid', !isMatch).toggleClass('is-valid', isMatch);
                input.next('.invalid-feedback').text(errorMessage);
                return isMatch;
            };

            const validateNote = (input) => {
                const words = input.val().trim().split(/\s+/).filter(Boolean);
                let isValid = words.length <= 30;
                input.toggleClass('is-invalid', !isValid).toggleClass('is-valid', isValid);
                input.next('.invalid-feedback').text(`Max 30 words. Current: ${words.length}`);
                return isValid;
            };

            const validateForm = () => {
                let isValid = true;

                isValid &= validateField($('#amount'), /^\d+$/, 'Only numbers allowed.');

                isValid &= validateField($('#buyer'), /^[a-zA-Z0-9\s]{1,20}$/, 'Text, spaces, and numbers (max 20 chars).');

                isValid &= validateField($('#receipt_id'), /^[a-zA-Z0-9]+$/, 'Only alphanumeric text.');

                isValid &= validateField($('#items'), /.+/, 'Please list items.');

                isValid &= validateField($('#buyer_email'), /^[\w.-]+@([\w-]+\.)+[\w-]{2,4}$/, 'Must be a valid email address.');

                isValid &= validateField($('#city'), /^[a-zA-Z\s]+$/, 'Only text and spaces.');

                isValid &= validateField(phoneInput, /^\d{10}$/, 'Only 10 numbers after 880 (e.g., 1712345678).');

                isValid &= validateNote(noteInput);

                return isValid;
            };

            $('input, textarea').blur(function() {
                validateForm();
            });

            form.submit(function(e) {
                e.preventDefault();

                if (!validateForm()) {
                    $('#alert-container').html('<div class="alert alert-danger">Please correct the highlighted errors.</div>');
                    return;
                }

                const fullPhone = '880' + phoneInput.val();

                let formData = new FormData(this);

                formData.set('phone', fullPhone);

                $.ajax({
                    url: '/submission/ajax',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        $('#alert-container').html('<div class="alert alert-success">' + response.message + '</div>');
                        form[0].reset();
                        $('input, textarea').removeClass('is-valid is-invalid');
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        let errorMessage = 'An unexpected error occurred.';
                        if (response && response.message) {
                            errorMessage = response.message;
                            if(response.errors && Array.isArray(response.errors)) {
                                errorMessage += '<br><ul>';
                                response.errors.forEach(err => errorMessage += '<li>' + err + '</li>');
                                errorMessage += '</ul>';
                            }
                        } else if (xhr.status === 403) {
                            errorMessage = 'You are blocked from submitting for 24 hours.';
                        } else if (xhr.status === 401) {
                            errorMessage = 'Session expired. Please log in.';
                            setTimeout(() => window.location.href = '/login', 2000);
                        } else if (xhr.status === 500) {
                             errorMessage = 'Server or Database Error. Check server logs.';
                        }

                        $('#alert-container').html('<div class="alert alert-danger">' + errorMessage + '</div>');
                    }
                });
            });
        });
    </script>
</body>
</html>