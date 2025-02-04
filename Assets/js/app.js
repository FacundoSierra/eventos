document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('calendar');
    let frm = document.getElementById('formulario');
    let eliminar = document.getElementById('btnEliminar');
    let myModalEl = document.getElementById('myModal');
    let registerForm = document.getElementById('registerForm');
    let loginForm = document.getElementById('loginForm');
    let logoutBtn = document.getElementById('logoutBtn');

    let myModal = myModalEl ? new bootstrap.Modal(myModalEl) : null;

    // Verifica si registerForm existe antes de asignar eventos
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetch(base_url + 'Auth/register', {
                method: 'POST',
                body: new FormData(this)
            }).then(res => res.json()).then(data => alert(data));
        });
    }

    // Verifica si loginForm existe antes de asignar eventos
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetch(base_url + 'Auth/login', {
                method: 'POST',
                body: new FormData(this)
            }).then(res => res.json()).then(data => {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert('Error en inicio de sesión');
                }
            });
        });
    }

    // Verifica si logoutBtn existe antes de asignar evento
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            window.location.href = base_url + 'Auth/logout';
        });
    }

    // Verifica si el calendario existe antes de inicializarlo
    if (calendarEl) {
        let calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'local',
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev next today',
                center: 'title',
                right: 'dayGridMonth timeGridWeek listWeek'
            },
            events: base_url + 'Home/listar',
            editable: true,
            dateClick: function (info) {
                if (!frm || !myModal) return;
                frm.reset();
                if (eliminar) eliminar.classList.add('d-none');
                document.getElementById('start').value = info.dateStr;
                document.getElementById('id').value = '';
                document.getElementById('btnAccion').textContent = 'Registrar';
                document.getElementById('titulo').textContent = 'Registrar Evento';
                myModal.show();
            },

            eventClick: function (info) {
                if (!myModal) return;
                document.getElementById('id').value = info.event.id;
                document.getElementById('title').value = info.event.title;
                document.getElementById('start').value = info.event.startStr;
                document.getElementById('color').value = info.event.backgroundColor;
                document.getElementById('btnAccion').textContent = 'Modificar';
                document.getElementById('titulo').textContent = 'Actualizar Evento';
                if (eliminar) eliminar.classList.remove('d-none');
                myModal.show();
            },

            eventDrop: function (info) {
                const start = info.event.startStr;
                const id = info.event.id;
                const url = base_url + 'Home/drag';
                const http = new XMLHttpRequest();
                const formDta = new FormData();
                formDta.append('start', start);
                formDta.append('id', id);
                http.open("POST", url, true);
                http.send(formDta);
                http.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        const res = JSON.parse(this.responseText);
                        Swal.fire('Avisos?', res.msg, res.tipo);
                        if (res.estado) {
                            if (myModal) myModal.hide();
                            calendar.refetchEvents();
                        }
                    }
                }
            }
        });

        calendar.render();
    }

    // Verifica si frm existe antes de asignar evento
    if (frm) {
        frm.addEventListener('submit', function (e) {
            e.preventDefault();
            const title = document.getElementById('title').value;
            const start = document.getElementById('start').value;
            if (title == '' || start == '') {
                Swal.fire('Avisos?', 'Todos los campos son obligatorios', 'warning');
            } else {
                const url = base_url + 'Home/registrar';
                const http = new XMLHttpRequest();
                http.open("POST", url, true);
                http.send(new FormData(frm));
                http.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        const res = JSON.parse(this.responseText);
                        Swal.fire('Avisos?', res.msg, res.tipo);
                        if (res.estado) {
                            if (myModal) myModal.hide();
                            calendar.refetchEvents();
                        }
                    }
                }
            }
        });
    }

    // Verifica si eliminar existe antes de asignar evento
    if (eliminar) {
        eliminar.addEventListener('click', function () {
            if (!myModal) return;
            myModal.hide();
            Swal.fire({
                title: 'Advertencia?',
                text: "¿Está seguro de eliminar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = base_url + 'Home/eliminar/' + document.getElementById('id').value;
                    const http = new XMLHttpRequest();
                    http.open("GET", url, true);
                    http.send();
                    http.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            console.log(this.responseText);
                            const res = JSON.parse(this.responseText);
                            Swal.fire('Avisos?', res.msg, res.tipo);
                            if (res.estado) {
                                calendar.refetchEvents();
                            }
                        }
                    }
                }
            })
        });
    }
});
