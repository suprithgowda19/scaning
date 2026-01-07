<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delegate Registration | 17th BIFFes 2026</title>

    <link href="https://fonts.googleapis.com/css2?family=Anek+Kannada:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #443570;
            --danger: #dc3545;
            --bg-light: #f8fafc;
            --border: #e2e8f0;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            padding: 40px 0;
        }

        .kan-text { font-family: 'Anek Kannada', sans-serif !important; }

        .registration-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            max-width: 900px;
            margin: auto;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .header-banner {
            background: var(--primary);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .form-section { padding: 40px 50px; }

        .form-label {
            font-weight: 600;
            font-size: 0.88rem;
            color: #475569;
            margin-bottom: 6px;
        }

        .form-control, .form-select {
            padding: 12px 16px;
            border-radius: 8px;
            border: 1.5px solid var(--border);
            font-weight: 500;
        }

        /* Error Styling */
        .error-feedback {
            display: none;
            width: 100%;
            margin-top: 0.4rem;
            font-size: 0.8rem;
            color: var(--danger);
            font-weight: 600;
        }

        /* Show error when invalid */
        .was-validated .form-control:invalid ~ .error-feedback,
        .form-control.is-invalid ~ .error-feedback,
        .was-validated .form-select:invalid ~ .error-feedback {
            display: block;
        }

        .btn-premium {
            background: var(--primary);
            color: white;
            padding: 16px 40px;
            border-radius: 10px;
            font-weight: 700;
            border: none;
            width: 100%;
            transition: 0.3s;
        }

        .btn-premium:hover:not(:disabled) {
            background: #322754;
        }

        .iti { width: 100%; }
    </style>
</head>
<body>

<div class="container">
    <div class="registration-card">
        
        <div id="step-instructions">
            <div class="header-banner">
                <h1 class="fw-bold h2 mb-2">Registration Instructions</h1>
                <p class="opacity-75 h5 kan-text">ನೋಂದಣಿ ಸೂಚನೆಗಳು</p>
            </div>
            
            <div class="form-section text-center">
                <div class="row g-4 mb-4 text-start">
                    <div class="col-md-6 border-end">
                        <h6 class="fw-bold text-primary">Guidelines</h6>
                        <p class="small text-muted">1. Age must be 18+.<br>2. Upload valid ID proof and photo.<br>3. Ensure email ends with .com, .in, .org, or .co.</p>
                    </div>
                    <div class="col-md-6 kan-text">
                        <h6 class="fw-bold text-primary">ಮಾರ್ಗಸೂಚಿಗಳು</h6>
                        <p class="small text-muted">1. ವಯಸ್ಸು 18+ ಆಗಿರಬೇಕು.<br>2. ಮಾನ್ಯ ಗುರುತಿನ ಚೀಟಿ ಅಪ್‌ಲೋಡ್ ಮಾಡಿ.<br>3. ಇಮೇಲ್ .com, .in ಇತ್ಯಾದಿಗಳಲ್ಲಿ ಕೊನೆಗೊಳ್ಳಬೇಕು.</p>
                    </div>
                </div>
                <div class="form-check d-inline-block text-start mb-4">
                    <input class="form-check-input" type="checkbox" id="acceptTerms">
                    <label class="form-check-label fw-bold small" for="acceptTerms">
                        I have read and accepted the instructions.
                    </label>
                </div>
                <br>
                <button id="btnStart" class="btn btn-premium px-5" disabled style="width:auto">PROCEED TO FORM</button>
            </div>
        </div>

        <div id="step-form" style="display: none;">
            <div class="header-banner">
                <h1 class="fw-bold h2 mb-1">Delegate Registration</h1>
            </div>

            <div class="form-section">
                <form id="delegateRegistrationForm" novalidate action="/payment-redirect" method="POST" enctype="multipart/form-data">
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">First Name *</label>
                            <input type="text" class="form-control" name="firstname" pattern="[A-Za-z\s]+" required placeholder="Ex: John">
                            <div class="error-feedback">First name is required (Alphabets only).</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name *</label>
                            <input type="text" class="form-control" name="lastname" pattern="[A-Za-z\s]+" required placeholder="Ex: Doe">
                            <div class="error-feedback">Last name is required (Alphabets only).</div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.(com|in|org|co)$" required 
                                   placeholder="example@domain.com">
                            <div class="error-feedback">Enter a valid email ending in .com, .in, .org, or .co</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label d-block">Contact Number *</label>
                            <input id="phone" type="tel" name="phone" class="form-control" required>
                            <div id="phone_error" class="error-feedback">Valid mobile number is required.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Date of Birth *</label>
                            <input type="date" id="dob" name="dob" class="form-control" required>
                            <div id="ageLabel" class="mt-1 small fw-bold text-primary">Age: --</div>
                            <div class="error-feedback">Registration is restricted to ages 18 and above.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Select ID Type *</label>
                            <select class="form-select" id="idType" name="id_doc" required>
                                <option value="">Choose Document...</option>
                                <option value="VOTER">Voter ID</option>
                                <option value="AADHAR">Aadhar Card</option>
                                <option value="DL">Driving License</option>
                                <option value="PASSPORT">Passport</option>
                            </select>
                            <div class="error-feedback">Please select an identity proof type.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ID Number *</label>
                            <input type="text" class="form-control" id="idNumber" required placeholder="Enter ID number">
                            <div class="error-feedback" id="idNumberFeedback">Format mismatch for the selected ID type.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Residential Address *</label>
                            <textarea class="form-control" name="address" rows="2" required placeholder="Street, Building, Area"></textarea>
                            <div class="error-feedback">Address is required.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">City *</label>
                            <input type="text" class="form-control" name="city" pattern="[A-Za-z\s]+" required>
                            <div class="error-feedback">City name required (Alphabets only).</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pincode *</label>
                            <input type="text" class="form-control" name="pincode" pattern="[0-9]+" maxlength="10" required>
                            <div class="error-feedback">Valid numeric pincode required.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Category *</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="DL">Delegates</option>
                                <option value="FF">Film Fraternity</option>
                                <option value="SN">Senior Citizen</option>
                                <option value="SD">Student</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">ID Proof (PNG/JPG/PDF) *</label>
                            <input type="file" class="form-control file-limit" name="doc_proof" accept=".png,.jpg,.jpeg,.pdf" required>
                            <div class="error-feedback">Required: File under 2MB.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Passport Size Photo (PNG/JPG) *</label>
                            <input type="file" class="form-control file-limit" name="photo" accept=".png,.jpg,.jpeg" required>
                            <div class="error-feedback">Required: Photo under 2MB.</div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Pass Pickup Location *</label>
                            <select class="form-select" required>
                                <option value="">Select Location...</option>
                                <option>KCA</option>
                                <option>Suchitra Chitra Mandali</option>
                                <option>PVR Inox, Orion Mall</option>
                            </select>
                            <div class="error-feedback">Please select where you will pick up your pass.</div>
                        </div>

                        <div class="col-12 mt-4 pt-4 border-top">
                            <div class="p-4 bg-light rounded-4 border">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="declarationCheck" required>
                                    <label class="form-check-label small fw-bold" for="declarationCheck">
                                        I affirm that the information provided by me is accurate and correct to the best of my knowledge.
                                    </label>
                                    <div class="error-feedback">You must check the declaration to continue.</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-premium py-3">PROCEED TO PAYMENT</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

<script>
    $(document).ready(function() {
        const iti = window.intlTelInput(document.querySelector("#phone"), {
            initialCountry: "in", separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
        });

        $('#acceptTerms').on('change', function() { $('#btnStart').prop('disabled', !this.checked); });
        $('#btnStart').on('click', function() { 
            $('#step-instructions').fadeOut(300, () => { $('#step-form').fadeIn(); window.scrollTo(0,0); }); 
        });

        // Age logic (18+)
        $('#dob').on('change', function() {
            const birth = new Date($(this).val());
            const today = new Date();
            let age = today.getFullYear() - birth.getFullYear();
            if (today.getMonth() < birth.getMonth() || (today.getMonth() === birth.getMonth() && today.getDate() < birth.getDate())) age--;

            if (age < 18) { 
                $(this).addClass('is-invalid');
                $('#ageLabel').text('Age: Invalid (18+ only)').css('color', 'red');
            } else {
                $(this).removeClass('is-invalid');
                $('#ageLabel').text('Age: ' + age + ' Years').css('color', 'var(--primary)');
            }
        });

        // ID Formatter
        $('#idNumber').on('input', function() {
            const type = $('#idType').val();
            let val = $(this).val().toUpperCase();
            $(this).val(val);
            let regex = /.*/;
            if (type === 'VOTER') regex = /^[A-Z]{3}[0-9]{7}$/;
            if (type === 'AADHAR') regex = /^[0-9]{14}$/;
            if (type === 'PASSPORT') regex = /^[A-Z][0-9]{7}$/;
            $(this).toggleClass('is-invalid', !regex.test(val));
        });

        // File size check
        $('.file-limit').on('change', function() {
            if (this.files[0] && this.files[0].size > 2097152) {
                alert("File too large. Max limit is 2MB.");
                $(this).val('').addClass('is-invalid');
            } else { $(this).removeClass('is-invalid'); }
        });

        // Validation & Auto-Scroll logic
        $('#delegateRegistrationForm').on('submit', function(e) {
            const form = this;
            const isPhoneValid = iti.isValidNumber();
            
            if (!isPhoneValid) { 
                $('#phone').addClass('is-invalid'); 
                $('#phone_error').show(); 
            } else { 
                $('#phone').removeClass('is-invalid'); 
                $('#phone_error').hide(); 
            }

            if (!form.checkValidity() || !isPhoneValid || $('#dob').hasClass('is-invalid') || $('#idNumber').hasClass('is-invalid')) {
                e.preventDefault();
                e.stopPropagation();
                $(form).addClass('was-validated');
                
                // Find first visible error and scroll to it
                setTimeout(() => {
                    const firstError = $('.is-invalid:visible, :invalid:visible').first();
                    if (firstError.length) {
                        $('html, body').animate({ scrollTop: firstError.offset().top - 150 }, 600);
                        firstError.focus();
                    }
                }, 50);
            }
        });
    });
</script>
</body>
</html>