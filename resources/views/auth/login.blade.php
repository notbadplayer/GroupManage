@extends('layout.layout')

@section('content')


<main>
    <div class="container">
       <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
          <div class="container">
             <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                   <div class="d-flex justify-content-center py-4"> <a href="" class="logo d-flex align-items-center w-auto"> <img src="{{asset('logo.png')}}" alt=""> <span class="d-none d-lg-block">JFstudio</span> </a></div>
                   <div class="card mb-3">
                      <div class="card-body">
                         <div class="pt-4 pb-2">
                            <h5 class="card-title text-center pb-0 fs-4">Zaloguj się</h5>
                            <p class="text-center small">Proszę wpisać login i hasło</p>
                         </div>
                         <form class="row g-3 needs-validation" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="col-12">
                               <label for="email" class="form-label">Adres email</label>
                               <div class="input-group has-validation">
                                  <span class="input-group-text" id="inputGroupPrepend">@</span> <input type="email" name="email"  value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" id="email" required autocomplete="email" autofocus>
                                  <div class="invalid-feedback">Proszę wpisać prawidłowy adres mailowy.</div>
                                  @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                               </div>
                            </div>
                            <div class="col-12">
                               <label for="password" class="form-label">Hasło</label> <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" required autocomplete="current-password">
                               <div class="invalid-feedback">Proszę wpisać hasło</div>
                               @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12">
                               <div class="form-check"> <input class="form-check-input" type="checkbox" name="remember" value="true" id="remember" {{ old('remember') ? 'checked' : '' }}> <label class="form-check-label" for="remember">Zapamiętaj mnie</label></div>
                            </div>
                            <div class="col-12"> <button class="btn btn-primary w-100" type="submit">Zaloguj</button></div>
                            <div class="col-12">
                               <p class="small mb-0">Nie masz konta? <a href="{{ route('register') }}">Utwórz konto</a></p>
                               @if (Route::has('password.request'))
                               <p class="small mb-0">Nie pamiętasz hasła? <a href="{{ route('password.request') }}">Zresetuj</a></p>
                                @endif
                            </div>
                         </form>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </section>
    </div>
 </main>
@endsection
