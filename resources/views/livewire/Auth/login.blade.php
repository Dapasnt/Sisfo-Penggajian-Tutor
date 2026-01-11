{{-- <div>
<div class="container mt-5">
<div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
        <div class="login-brand mb-1">
            <img src="{{ asset('assets/img/logo_gtc.png') }}" alt="logo" width="100" class="shadow-light rounded-circle">
        </div>
        <h6 class="fs-5 text-center text-primary">GALLANT TUTORING CENTER</h6>
        <div class="card card-primary">
            <div class="card-header"><h4>Login</h4></div>
                <div class="card-body">
                    <form wire:submit.prevent="login" class="needs-validation" novalidate="">
                        <div class="form-group">
                            <label for="usermail">Email atau Username</label>
                            <input id="usermail" type="text" class="form-control" wire:model="usermail" tabindex="1" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="password" class="control-label">Password</label>
                            <input id="password" type="password" class="form-control" wire:model="password" tabindex="2" required>
                        </div>

                        <div class="form-group">
                        <button wire:loading.remove wire:target="login" type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                            Login
                        </button>
                        <button wire:loading wire:target="login" class="btn btn-primary btn-lg btn-block" tabindex="4">
                            Loading ...
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>    
</div>
</div> --}}

<div style="background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%); min-height: 100vh; display: flex; align-items: center;">
    
    <div class="container">
        <div class="row justify-content-center">
            
            <div class="col-12 col-md-10 col-lg-8">
                
                <div class="card border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
                    <div class="row g-0"> <div class="col-md-6 bg-light d-flex flex-column justify-content-center align-items-center text-center p-5">
                            <div class="mb-3">
                                <img src="{{ asset('assets/img/logo_gtc.png') }}" alt="logo" width="120" class="shadow-sm rounded-circle">
                            </div>
                            <h5 class="text-primary font-weight-bold">GALLANT TUTORING CENTER</h5>
                            <p class="text-muted small mt-2 mb-0">Masuk untuk melanjutkan akses.</p>
                        </div>

                        <div class="col-md-6 bg-white p-4 p-md-5">
                            <div class="mb-4">
                                <h4 class="font-weight-bold">Login</h4>
                            </div>
                            
                            <form wire:submit.prevent="login" class="needs-validation" novalidate="">
                                
                                <div class="form-group mb-3">
                                    <label for="usermail" class="text-muted small text-uppercase">Email atau Username</label>
                                    <input id="usermail" type="text" class="form-control" wire:model="usermail" tabindex="1" required autofocus placeholder="Masukkan username">
                                </div>

                                <div class="form-group mb-4">
                                    <label for="password" class="control-label text-muted small text-uppercase">Password</label>
                                    <input id="password" type="password" class="form-control" wire:model="password" tabindex="2" required placeholder="Masukkan password">
                                </div>

                                <div class="form-group mb-0">
                                    <button wire:loading.remove wire:target="login" type="submit" class="btn btn-success btn-lg btn-block w-100" style="border-radius: 50px;">
                                        Login
                                    </button>
                                    <button wire:loading wire:target="login" class="btn btn-success btn-lg btn-block w-100" tabindex="4" disabled style="border-radius: 50px;">
                                        Loading ...
                                    </button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>