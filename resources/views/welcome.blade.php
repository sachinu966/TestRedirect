<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redirection Form</title>

    <!-- Include jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Include Bootstrap JS after jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Include Clipboard.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.12/clipboard.min.js"></script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Generate Redirect Script</h2>
    <form id="redirection-form">
        @csrf
        <div class="mb-3">
            <label for="main_url" class="form-label">Main URL</label>
            <input type="url" class="form-control" id="main_url" name="main_url" required>
        </div>
        <div class="mb-3">
            <label for="redirection_url_1" class="form-label">Redirection URL 1</label>
            <input type="url" class="form-control" id="redirection_url_1" name="redirection_url_1" required>
        </div>
        <div class="mb-3">
            <label for="redirection_url_2" class="form-label">Redirection URL 2</label>
            <input type="url" class="form-control" id="redirection_url_2" name="redirection_url_2" required>
        </div>
        <button type="button" class="btn btn-primary" id="save-button">Submit</button>
    </form>
</div>

<div class="modal fade" id="scriptModal" tabindex="-1" role="dialog" aria-labelledby="scriptModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scriptModalLabel">Generated Script</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input type="text" id="scriptInput" class="form-control" readonly>
                    <button class="btn btn-outline-secondary" type="button" id="copyButton" data-clipboard-target="#scriptInput">Copy</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add an event listener to the submit button -->
<script>
    $(document).ready(function () {
        $('#save-button').on('click', function () {
            // Get form data
            var formData = $('#redirection-form').serialize();

            // Send AJAX request to store the data
            $.ajax({
                type: 'POST',
                url: '/store-redirection-data',
                data: formData,
                success: function (response) {
                    // Handle the success response
                    var scriptUrl = response;
                    var scriptTag = "<script src='" + scriptUrl + "/generate-redirection-script'><\/script>";

                    // Display the script URL in the input field
                    $('#scriptInput').val(scriptTag);

                    // Open the modal
                    $('#scriptModal').modal('show');

                    // Initialize Clipboard.js
                    var clipboard = new ClipboardJS('#copyButton');

                    clipboard.on('success', function (e) {
                        console.log('Text copied to clipboard: ' + e.text);
                        // Update button text to indicate success
                        $('#copyButton').text('Copied').prop('disabled', true);
                        e.clearSelection();
                    });

                    clipboard.on('error', function (e) {
                        console.error('Unable to copy text to clipboard.');
                    });
                },
                error: function (error) {
                    // Handle the error response
                    console.log(error.responseJSON.message);
                }
            });
        });
    });
</script>

</body>
</html>
