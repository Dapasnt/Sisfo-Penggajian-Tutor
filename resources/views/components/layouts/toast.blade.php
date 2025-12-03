
  <!-- toastify -->
  {{-- <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('success-message', (event) => {
                Toastify({
                    text: "✔️ " + event, 
                    className: "info",
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    style: {
                        background: "#4de265",
                        borderRadius: "8px",
                        fontWeight: "bold",
                        boxShadow: "0 4px 10px rgba(0, 0, 0, 0.15)",
                        color: "#fff"
                    }
                }).showToast();
            });

            Livewire.on('failed-message', (event) => {
                Toastify({
                    text: "❌ " + event,
                    className: "info",
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    style: {
                        background: "#e53935",
                        borderRadius: "8px",
                        fontWeight: "bold",
                        boxShadow: "0 4px 10px rgba(0, 0, 0, 0.15)",
                        color: "#fff"
                    }
                }).showToast();
            });
        });
  </script>
  @if (session('success-message'))
        <script>
            Toastify({
                text: "✔️ {{ session('success-message') }}",
                className: "info",
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top",
                style: {
                    background: "#4de265",
                    borderRadius: "8px",
                    fontWeight: "bold",
                    boxShadow: "0 4px 10px rgba(0, 0, 0, 0.15)",
                    color: "#fff"
                }
            }).showToast();
        </script>
  @endif 
  @if (session('failed-message'))
        <script>
            Toastify({
                text: "❌ {{ session('failed-message') }}",
                className: "info",
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top",
                style: {
                    background: "#e53935",
                    borderRadius: "8px",
                    fontWeight: "bold",
                    boxShadow: "0 4px 10px rgba(0, 0, 0, 0.15)",
                    color: "#fff"
                }
            }).showToast();
        </script>
  @endif --}}

  <!-- sweetalert -->
  <script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('success-message', (event) => {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: event,
                timer: 3000,
                // showConfirmButton: false,
                // toast: true,
                // position: 'top-end',
                // background: '#e6ffed',
                // color: '#2e7d32'
            });
        });

        Livewire.on('failed-message', (event) => {
            Swal.fire({
                icon: 'error',
                title: 'Whoops!',
                text: event,
                timer: 3000,
                // showConfirmButton: false,
                // toast: true,
                // position: 'top-end',
                // background: '#ffebee',
                // color: '#c62828'
            });
        });
    });
  </script>

  @if (session('success-message'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success-message') }}",
            timer: 3000,
            // showConfirmButton: false,
            // toast: true,
            // position: 'top-end',
            // background: '#e6ffed',
            // color: '#2e7d32'
        });
    </script>
  @endif
  @if (session('failed-message'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Whoops!',
                text: "{{ session('failed-message') }}",
                timer: 3000,
                // showConfirmButton: false,
                // toast: true,
                // position: 'top-end',
                // background: '#ffebee',
                // color: '#c62828'
            });
        </script>
  @endif
