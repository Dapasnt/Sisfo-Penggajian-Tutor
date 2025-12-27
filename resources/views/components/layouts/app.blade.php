<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  {{-- <title> &mdash; {{ config('app.name') }}</title> --}}
  {{-- <title> {{ config('app.name') }}</title> --}}
  <title> {{ $title ?? 'Page Title' }}</title>

  <link rel="icon" type="image/png" sizes="16x16" href="/logo.webp">

  <x-layouts.css/>
  
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>

      <x-layouts.navbar/>
      <x-layouts.sidebars/>

      <!-- Main Content -->
      <div class="main-content">
       {{ $slot }}
      </div>

      <x-layouts.footer/>

    </div>

    <x-layouts.logout-modal/>

  </div>

    <x-layouts.scripts/>
    <x-layouts.toast/>

  @stack('scripts')
</body>
</html>
