<div>
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
</div>
