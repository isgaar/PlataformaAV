<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard')</title>

    <!-- Íconos y Estilos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- Bootstrap CSS -->
</head>

<body>
    <!-- MODAL DE CONFIRMACIÓN PARA ELIMINAR ESCUELA -->
    <div class="modal fade" id="deleteSchoolModal" tabindex="-1" role="dialog" aria-labelledby="deleteSchoolLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteSchoolLabel">
                        <i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación
                    </h5>
                    <button type="button" class="close text-black" data-bs-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar la escuela <strong id="deleteSchoolName"></strong>? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteSchoolForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-delete" onclick="document.getElementById('deleteSchoolForm').submit()">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE CONFIRMACIÓN PARA ELIMINAR USUARIO -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteUserLabel">
                        <i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación
                    </h5>
                    <button type="button" class="close text-black" data-bs-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar al usuario <strong id="deleteUserName"></strong>? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteUserForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-delete" onclick="document.getElementById('deleteUserForm').submit()">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Script para configurar el modal -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const userModal = document.getElementById('deleteUserModal');
            userModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const userId = button.getAttribute('data-userid');
                const userName = button.getAttribute('data-username');
                const action = `{{ url('admin/users') }}/` + userId;

                document.getElementById('deleteUserForm').setAttribute('action', action);
                document.getElementById('deleteUserName').textContent = userName;
            });

            const schoolModal = document.getElementById('deleteSchoolModal');
            schoolModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const schoolId = button.getAttribute('data-schoolid');
                const schoolName = button.getAttribute('data-schoolname');
                const action = `{{ url('admin/schools') }}/` + schoolId;

                document.getElementById('deleteSchoolForm').setAttribute('action', action);
                document.getElementById('deleteSchoolName').textContent = schoolName;
            });
        });
    </script>
    <!-- Bootstrap JS y Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>