<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet" />
    <title>Reset Password</title>
    <style>
        .gradient-custom {
            background: #6a11cb;
            background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));
            background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));
        }
    </style>
</head>

<body>
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <div class="mb-md-5 mt-md-4 pb-5">
                                <form method="POST" action="{{ route('password.update') }}">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                    <h2 class="fw-bold mb-2 text-uppercase">Reset Password</h2>
                                    <p class="text-white-50 mb-5">Enter your email address and new password below to
                                        reset your password.</p>

                                    @if ($errors->any())
                                        <div class="mb-4 font-medium text-sm text-red-600">
                                            @foreach ($errors->all() as $error)
                                                <div>{{ $error }}</div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Floating Label for Email -->
                                    <div class="form-floating mb-4">
                                        <input id="email" type="email" name="email" class="form-control form-control-lg"
                                            :value="old('email', $request->email)" required autofocus
                                            autocomplete="username" />
                                        <label for="email">{{ __('Email') }}</label>
                                    </div>

                                    <!-- Floating Label for Password -->
                                    <div class="form-floating mb-4">
                                        <input id="password" type="password" name="password"
                                            class="form-control form-control-lg" required autocomplete="new-password" />
                                        <label for="password">{{ __('Password') }}</label>
                                    </div>

                                    <!-- Floating Label for Password Confirmation -->
                                    <div class="form-floating mb-4">
                                        <input id="password_confirmation" type="password" name="password_confirmation"
                                            class="form-control form-control-lg" required autocomplete="new-password" />
                                        <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button class="btn btn-outline-light btn-lg px-5" type="submit">
                                            Reset Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MDB -->
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
</body>

</html>