<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delegate Registration | 17th BIFFes 2026</title>

    <link href="https://fonts.googleapis.com/css2?family=Anek+Kannada:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />

    <style>
        :root { --primary: #443570; --danger: #dc3545; --bg-light: #f8fafc; --border: #e2e8f0; }
        body { background-color: var(--bg-light); font-family: 'Inter', sans-serif; color: #1e293b; padding: 40px 0; }
        .kan-text { font-family: 'Anek Kannada', sans-serif !important; }
        .registration-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); max-width: 900px; margin: auto; overflow: hidden; border: 1px solid var(--border); }
        .header-banner { background: var(--primary); color: white; padding: 40px; text-align: center; }
        .form-section { padding: 40px 50px; }
        .form-label { font-weight: 600; font-size: 0.88rem; color: #475569; margin-bottom: 6px; }
        .form-control, .form-select { padding: 12px 16px; border-radius: 8px; border: 1.5px solid var(--border); font-weight: 500; }
        
        .error-feedback { color: var(--danger); font-size: 0.8rem; font-weight: 600; margin-top: 5px; display: block; }
        .is-invalid { border-color: var(--danger) !important; }

        .btn-premium { background: var(--primary); color: white; padding: 16px 40px; border-radius: 10px; font-weight: 700; border: none; width: 100%; transition: 0.3s; }
        .btn-premium:hover:not(:disabled) { background: #322754; }
        .iti { width: 100%; }

        /* Step Logic */
        #step-instructions { display: {{ $errors->any() ? 'none' : 'block' }}; }
        #step-form { display: {{ $errors->any() ? 'block' : 'none' }}; }
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
                        <p class="small text-muted">1. Age must be 18+.<br>2. Unique email and mobile required.<br>3. Upload valid ID proof and photo (&lt; 2MB).</p>
                    </div>
                    <div class="col-md-6 kan-text">
                        <h6 class="fw-bold text-primary">ಮಾರ್ಗಸೂಚಿಗಳು</h6>
                        <p class="small text-muted">1. ವಯಸ್ಸು 18+ ಆಗಿರಬೇಕು.<br>2. ಮಾನ್ಯ ಇಮೇಲ್ ಮತ್ತು ಮೊಬೈಲ್ ಸಂಖ್ಯೆ.<br>3. ಗುರುತಿನ ಚೀಟಿ ಅಪ್‌ಲೋಡ್ ಮಾಡಿ.</p>
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

        <div id="step-form">
            <div class="header-banner">
                <h1 class="fw-bold h2 mb-1">Delegate Registration Form</h1>
            </div>

            <div class="form-section">
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        Please fix the errors highlighted below to proceed.
                    </div>
                @endif

                <form id="delegateRegistrationForm" action="{{ route('delegate.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf 

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">First Name *</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required>
                            @error('first_name') <div class="error-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}">
                            @error('last_name') <div class="error-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Email Address *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                            @error('email') <div class="error-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label d-block">Contact Number *</label>
                            <input id="phone_display" type="tel" class="form-control @error('phone') is-invalid @enderror" required>
                            <input type="hidden" name="phone" id="phone_full" value="{{ old('phone') }}">
                            <input type="hidden" name="country_code" id="country_code_val" value="{{ old('country_code') }}">
                            @error('phone') <div class="error-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Date of Birth *</label>
                            <input type="date" id="dob" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob') }}" required>
                            <input type="hidden" name="age" id="age_val" value="{{ old('age') }}">
                            <div id="ageLabel" class="mt-1 small fw-bold text-primary">Age: {{ old('age', '--') }} Years</div>
                            @error('dob') <div class="error-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Gender *</label>
                            <select class="form-select @error('gender') is-invalid @enderror" name="gender" required>
                                <option value="" disabled selected>Select...</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender') <div class="error-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Select ID Type *</label>
                            <select class="form-select @error('id_type') is-invalid @enderror" id="idType" name="id_type" required>
                                <option value="" disabled selected>Choose...</option>
                                <option value="AADHAR" {{ old('id_type') == 'AADHAR' ? 'selected' : '' }}>Aadhar Card</option>
                                <option value="VOTER" {{ old('id_type') == 'VOTER' ? 'selected' : '' }}>Voter ID</option>
                                <option value="DL" {{ old('id_type') == 'DL' ? 'selected' : '' }}>Driving License</option>
                                <option value="PASSPORT" {{ old('id_type') == 'PASSPORT' ? 'selected' : '' }}>Passport</option>
                            </select>
                            @error('id_type') <div class="error-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">ID Number *</label>
                            <input type="text" class="form-control @error('id_number') is-invalid @enderror" name="id_number" id="idNumber" value="{{ old('id_number') }}" required>
                            @error('id_number') <div class="error-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Residential Address *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="2" required>{{ old('address') }}</textarea>
                            @error('address') <div class="error-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">City *</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" required>
                            @error('city') <div class="error-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pincode *</label>
                            <input type="text" class="form-control @error('pincode') is-invalid @enderror" name="pincode" value="{{ old('pincode') }}" required>
                            @error('pincode') <div class="error-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Country (2 Letter) *</label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror" name="country" value="{{ old('country', 'IN') }}" maxlength="2" required>
                            @error('country') <div class="error-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Category *</label>
                            <select class="form-select @error('category') is-invalid @enderror" name="category" required>
                                @foreach(['Delegate', 'Film Fraternity', 'Senior Citizen', 'Student'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category') <div class="error-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pass Pickup Location *</label>
                            <select class="form-select @error('pickup_location') is-invalid @enderror" name="pickup_location" required>
                                <option value="" disabled selected>Select...</option>
                                <option value="KCA" {{ old('pickup_location') == 'KCA' ? 'selected' : '' }}>KCA</option>
                                <option value="SCA" {{ old('pickup_location') == 'SCA' ? 'selected' : '' }}>Suchitra Chitra Mandali</option>
                                <option value="ORI" {{ old('pickup_location') == 'ORI' ? 'selected' : '' }}>PVR Inox, Orion Mall</option>
                            </select>
                            @error('pickup_location') <div class="error-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">ID Document Proof *</label>
                            <input type="file" class="form-control @error('id_document') is-invalid @enderror" name="id_document" accept=".png,.jpg,.jpeg,.pdf" required>
                            @error('id_document') <div class="error-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Passport Size Photo *</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" name="photo" accept=".png,.jpg,.jpeg" required>
                            @error('photo') <div class="error-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 mt-4 pt-4 border-top">
                            <div class="p-4 bg-light rounded-4 border">
                                <div class="form-check">
                                    <input class="form-check-input @error('is_agree') is-invalid @enderror" type="checkbox" name="is_agree" id="is_agree" value="1" {{ old('is_agree') ? 'checked' : '' }} required>
                                    <label class="form-check-label small fw-bold" for="is_agree">
                                        I affirm that the information provided is accurate and correct.
                                    </label>
                                    @error('is_agree') <div class="error-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-premium py-3">SUBMIT & PROCEED TO PAYMENT</button>
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
        const phoneInput = document.querySelector("#phone_display");
        const iti = window.intlTelInput(phoneInput, {
            initialCountry: "in", separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
        });

        @if(old('phone'))
            iti.setNumber("{{ old('phone') }}");
        @endif

        $('#acceptTerms').on('change', function() { $('#btnStart').prop('disabled', !this.checked); });
        
        $('#btnStart').on('click', function() { 
            $('#step-instructions').fadeOut(300, () => { $('#step-form').fadeIn(); }); 
        });

        $('#delegateRegistrationForm').on('submit', function() {
            if (iti.isValidNumber()) {
                $('#phone_full').val(iti.getNumber());
                $('#country_code_val').val(iti.getSelectedCountryData().dialCode);
            }
        });

        $('#dob').on('change', function() {
            const birth = new Date($(this).val());
            const today = new Date();
            let age = today.getFullYear() - birth.getFullYear();
            if (today.getMonth() < birth.getMonth() || (today.getMonth() === birth.getMonth() && today.getDate() < birth.getDate())) age--;
            $('#ageLabel').text('Age: ' + (age >= 0 ? age : '--') + ' Years');
            $('#age_val').val(age);
        });
    });
</script>
</body>
</html>