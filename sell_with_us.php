<?php include 'navbar.php'; ?>
<?php include 'navbar2.php'; ?>
<?php include './includes/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell With Us - Kwetu Auctions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .sell-container {
            width: 90%;
            max-width: 800px;
            margin: 120px auto 50px;
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .page-title {
            color: #f78b00;
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 10px;
            text-align: center;
        }

        .subtitle {
            color: #6c757d;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-section {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #e9ecef;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #f78b00;
            box-shadow: 0 0 0 0.2rem rgba(247, 139, 0, 0.25);
        }

        .submit-btn {
            background-color: #f78b00;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .submit-btn:hover {
            background-color: #e67a00;
            transform: translateY(-2px);
        }

        .file-upload {
            border: 2px dashed #e9ecef;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload:hover {
            border-color: #f78b00;
        }


        .mdx-btn-sell-with-us{
            border-radius: 30px;
            font-size: small !important;
        }

        .preview-images {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .preview-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="sell-container">
        <h1 class="page-title">Sell With Us</h1>
        <p class="subtitle">Partner with Kwetu Auctions to reach thousands of potential buyers</p>

        <form action="sell_with_us.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company_name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Company Email</label>
                    <input type="email" name="company_email" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Company Location</label>
                    <input type="text" name="company_location" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Company Phone Number</label>
                    <input type="tel" name="company_phone" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Items Being Sold</label>
                <textarea name="items_sold" class="form-control" rows="4" placeholder="Please describe the items you wish to auction..." required></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Auction Date</label>
                    <input type="date" name="auction_date" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Inspection Date</label>
                    <input type="date" name="inspection_date" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Upload Photos</label>
                <div class="file-upload" id="dropZone">
                    <input type="file" name="images[]" id="fileInput" multiple accept="image/*" class="form-control" required style="display: none;">
                    <div class="upload-text">
                        <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                        <p>Drag and drop your images here or click to browse</p>
                    </div>
                    <div class="preview-images" id="imagePreview"></div>
                </div>
            </div>

            <button type="submit" class="submit-btn mdx-btn-sell-with-us">Submit Request</button>
        </form>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        // Form validation
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Image preview functionality
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const imagePreview = document.getElementById('imagePreview');

        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#f78b00';
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.style.borderColor = '#e9ecef';
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            fileInput.files = e.dataTransfer.files;
            updateImagePreview();
        });

        fileInput.addEventListener('change', updateImagePreview);

        function updateImagePreview() {
            imagePreview.innerHTML = '';
            Array.from(fileInput.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'preview-image';
                    imagePreview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
</body>
</html>
