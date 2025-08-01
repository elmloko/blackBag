<title>GESPA|AGBC</title>
<link rel="icon" type="image/png" href="{{ asset('images/AGBClogo.png') }}" />
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<div class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            {{-- <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section">{{ __('Login') }}</h2>
                </div> --}}
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-wrap p-4 p-md-5">
                    <div class="icon d-flex align-items-center justify-content-center">
                        <img src="{{ asset('images/AGBClogo.png') }}" alt="Logo GESPA"
                            style="width: 65px; height: auto; border-radius: 50%;">
                    </div>
                    <div class="text-center mb-4">
                        <h2>SISTEMA DE GESTION DE SACAS POSTALES</h2>
                        <h3>GESPA</h3>
                    </div>
                    <form method="POST" action="{{ route('login') }}" class="login-form">
                        @csrf

                        <!-- Email Address -->
                        <div class="form-group">
                            <x-text-input id="email" class="form-control rounded-left" type="email" name="email"
                                placeholder="{{ __('Email') }}" :value="old('email')" required autofocus />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="form-group d-flex">
                            <x-text-input id="password" class="form-control rounded-left" type="password"
                                name="password" placeholder="{{ __('Password') }}" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Submit -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary rounded submit p-3 px-5">
                                {{ __('Log in') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<style>
    body {
        background-image: url('{{ asset('images/sobres.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 50vh;
        margin: 0;
    }
</style>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-X3ST0TCSVE"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-X3ST0TCSVE');
</script>
