// Dashboard Administrador - Funciones JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar funcionalidades
    loadUserData();
    loadStats();
    loadUserList();
});

// Hacer funciones disponibles globalmente
window.manageUsers = manageUsers;
window.manageActivities = manageActivities;
window.manageCompanies = manageCompanies;
window.manageCategories = manageCategories;
window.manageMunicipios = manageMunicipios;
window.managePublications = managePublications;
window.manageMunicipios = manageMunicipios;
window.viewReports = viewReports;
window.closeCategoriesModal = closeCategoriesModal;
window.showCreateCategoryForm = showCreateCategoryForm;
window.closeCategoryFormModal = closeCategoryFormModal;
window.editCategory = editCategory;
window.deleteCategory = deleteCategory;
window.toggleCategoryStatus = toggleCategoryStatus;
window.saveCategory = saveCategory;
window.showCreateUserForm = showCreateUserForm;
window.closeUserFormModal = closeUserFormModal;
window.editUser = editUser;
window.deleteUser = deleteUser;
window.saveUser = saveUser;
window.showCreateActivityForm = showCreateActivityForm;
window.closeActivityFormModal = closeActivityFormModal;
window.editActivity = editActivity;
window.deleteActivity = deleteActivity;
window.saveActivity = saveActivity;
window.closeCompaniesModal = closeCompaniesModal;
window.showCreateCompanyForm = showCreateCompanyForm;
window.closeCompanyFormModal = closeCompanyFormModal;
window.editCompany = editCompany;
window.deleteCompany = deleteCompany;
window.toggleCompanyStatus = toggleCompanyStatus;
window.saveCompany = saveCompany;
window.viewCompanyReport = viewCompanyReport;
window.closeCompanyReportModal = closeCompanyReportModal;

// Funciones de carga de datos
function loadUserData() {
    fetch('http://127.0.0.1:8000/api/me', {
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('user-name').textContent = data.usuario.Nombre;
        }
    })
    .catch(error => {
        console.error('Error cargando datos del usuario:', error);
        localStorage.removeItem('token');
        window.location.href = '/login';
    });
}

function loadStats() {
    const statsSection = document.getElementById('stats-section');

    // Función auxiliar para obtener datos con manejo de errores
    const fetchData = async (url) => {
        try {
            const response = await fetchWithAuth(url);
            const data = await response.json();
            // Si la respuesta tiene una propiedad 'data', usar esa, sino usar directamente el array
            return Array.isArray(data) ? data : (data.data || []);
        } catch (error) {
            console.error(`Error fetching ${url}:`, error);
            return [];
        }
    };

    Promise.all([
        fetchData('http://127.0.0.1:8000/api/listarUsuarios'),
        fetchData('http://127.0.0.1:8000/api/listarActividades'),
        fetchData('http://127.0.0.1:8000/api/listarReservas'),
        fetchData('http://127.0.0.1:8000/api/listarEmpresas')
    ])
    .then(([usuarios, actividades, reservas, empresas]) => {
        statsSection.innerHTML = `
            <div class="bg-blue-50 p-6 rounded-lg text-center">
                <h3 class="text-2xl font-bold text-blue-600">${usuarios.length}</h3>
                <p class="text-gray-600">Total Usuarios</p>
            </div>
            <div class="bg-green-50 p-6 rounded-lg text-center">
                <h3 class="text-2xl font-bold text-green-600">${actividades.length}</h3>
                <p class="text-gray-600">Total Actividades</p>
            </div>
            <div class="bg-yellow-50 p-6 rounded-lg text-center">
                <h3 class="text-2xl font-bold text-yellow-600">${reservas.length}</h3>
                <p class="text-gray-600">Total Reservas</p>
            </div>
            <div class="bg-purple-50 p-6 rounded-lg text-center">
                <h3 class="text-2xl font-bold text-purple-600">${empresas.length}</h3>
                <p class="text-gray-600">Total Empresas</p>
            </div>
        `;
    })
    .catch(error => {
        console.error('Error cargando estadísticas:', error);
        statsSection.innerHTML = '<p class="text-red-500">Error al cargar las estadísticas</p>';
    });
}

function loadUserList() {
    const listSection = document.getElementById('list-section');

    fetch('http://127.0.0.1:8000/api/listarUsuarios', {
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    })
    .then(response => response.json())
    .then(data => {
        let html = '<h3 class="text-2xl font-bold mb-6 text-gray-800">Gestión de Usuarios</h3>';
        html += '<button onclick="showCreateUserForm()" class="bg-blue-600 text-white px-4 py-2 rounded mb-4">Crear Nuevo Usuario</button>';
        html += '<div class="overflow-x-auto">';
        html += '<table class="w-full table-auto">';
        html += '<thead><tr class="bg-gray-100"><th class="px-4 py-2">Nombre</th><th class="px-4 py-2">Email</th><th class="px-4 py-2">Rol</th><th class="px-4 py-2">Acciones</th></tr></thead>';
        html += '<tbody>';
        data.forEach(usuario => {
            html += `
                <tr class="border-b">
                    <td class="px-4 py-2">${usuario.Nombre} ${usuario.Apellido}</td>
                    <td class="px-4 py-2">${usuario.Email}</td>
                    <td class="px-4 py-2">${usuario.Rol}</td>
                    <td class="px-4 py-2">
                        <button onclick="editUser(${usuario.id})" class="bg-blue-500 text-white px-2 py-1 rounded text-sm mr-2">Editar</button>
                        <button onclick="deleteUser(${usuario.id})" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Eliminar</button>
                    </td>
                </tr>
            `;
        });
        html += '</tbody></table></div>';
        listSection.innerHTML = html;
    })
    .catch(error => console.error('Error cargando lista de usuarios:', error));
}

// Funciones de navegación
function manageUsers() {
    loadUserList();
}

function manageActivities() {
    loadActivitiesList();
}

function loadActivitiesList() {
    const listSection = document.getElementById('list-section');

    fetchWithAuth('http://127.0.0.1:8000/api/listarActividades')
        .then(response => response.json())
        .then(data => {
            let html = '<h3 class="text-2xl font-bold mb-6 text-gray-800">Gestión de Actividades</h3>';
            html += '<button onclick="showCreateActivityForm()" class="bg-green-600 text-white px-4 py-2 rounded mb-4 hover:bg-green-700">Crear Nueva Actividad</button>';
            html += '<div class="overflow-x-auto">';
            html += '<table class="w-full table-auto border-collapse border border-gray-300">';
            html += '<thead><tr class="bg-gray-100"><th class="border border-gray-300 px-4 py-2 text-left">Nombre</th><th class="border border-gray-300 px-4 py-2 text-left">Fecha</th><th class="border border-gray-300 px-4 py-2 text-left">Precio</th><th class="border border-gray-300 px-4 py-2 text-left">Cupo</th><th class="border border-gray-300 px-4 py-2 text-left">Acciones</th></tr></thead>';
            html += '<tbody>';

            data.forEach(actividad => {
                html += `
                    <tr class="border-b">
                        <td class="px-4 py-2">${actividad.Nombre_Actividad}</td>
                        <td class="px-4 py-2">${actividad.Fecha_Actividad}</td>
                        <td class="px-4 py-2">$${actividad.Precio}</td>
                        <td class="px-4 py-2">${actividad.Cupo_Maximo}</td>
                        <td class="px-4 py-2">
                            <button onclick="editActivity(${actividad.id})" class="bg-blue-500 text-white px-2 py-1 rounded text-sm mr-2 hover:bg-blue-600">Editar</button>
                            <button onclick="deleteActivity(${actividad.id})" class="bg-red-500 text-white px-2 py-1 rounded text-sm hover:bg-red-600">Eliminar</button>
                        </td>
                    </tr>
                `;
            });

            html += '</tbody></table></div>';
            listSection.innerHTML = html;
        })
        .catch(error => {
            console.error('Error cargando actividades:', error);
            listSection.innerHTML = '<h3 class="text-2xl font-bold mb-6 text-gray-800">Gestión de Actividades</h3><p class="text-red-500">Error al cargar las actividades</p>';
        });
}

function showCreateActivityForm() {
    document.getElementById('activityFormTitle').textContent = 'Crear Actividad';
    document.getElementById('activityForm').reset();
    document.getElementById('activityId').value = '';
    loadCategoriesForActivity();
    loadMunicipiosForActivity();
    document.getElementById('activityFormModal').classList.remove('hidden');
}

function closeActivityFormModal() {
    document.getElementById('activityFormModal').classList.add('hidden');
    document.getElementById('activityForm').reset();
}

function editActivity(id) {
    fetchWithAuth(`http://127.0.0.1:8000/api/actividades/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('activityFormTitle').textContent = 'Editar Actividad';
            document.getElementById('activityId').value = data.id;
            document.getElementById('activityNombre').value = data.Nombre_Actividad;
            document.getElementById('activityDescripcion').value = data.Descripcion;
            document.getElementById('activityFecha').value = data.Fecha_Actividad;
            document.getElementById('activityHora').value = data.Hora_Actividad;
            document.getElementById('activityPrecio').value = data.Precio;
            document.getElementById('activityCupo').value = data.Cupo_Maximo;
            document.getElementById('activityUbicacion').value = data.Ubicacion;
            document.getElementById('activityImagen').value = data.Imagen || '';

            loadCategoriesForActivity(data.idCategoria);
            loadMunicipiosForActivity(data.idMunicipio);

            document.getElementById('activityFormModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error cargando actividad:', error);
            showNotification('Error al cargar datos de la actividad', 'error');
        });
}

function deleteActivity(id) {
    if (confirm('¿Estás seguro de eliminar esta actividad? Esta acción no se puede deshacer.')) {
        fetchWithAuth(`http://127.0.0.1:8000/api/eliminarActividades/${id}`, {
            method: 'DELETE'
        })
        .then(response => {
            if (response.ok) {
                showNotification('Actividad eliminada exitosamente', 'success');
                loadActivitiesList();
            } else {
                throw new Error('Error al eliminar actividad');
            }
        })
        .catch(error => {
            console.error('Error eliminando actividad:', error);
            showNotification('Error al eliminar actividad', 'error');
        });
    }
}

function saveActivity() {
    const form = document.getElementById('activityForm');
    const formData = new FormData(form);
    const activityId = document.getElementById('activityId').value;
    const isEdit = activityId !== '';

    const activityData = {
        Nombre_Actividad: formData.get('nombre'),
        Descripcion: formData.get('descripcion'),
        Fecha_Actividad: formData.get('fecha'),
        Hora_Actividad: formData.get('hora'),
        Precio: parseFloat(formData.get('precio')),
        Cupo_Maximo: parseInt(formData.get('cupo')),
        Ubicacion: formData.get('ubicacion'),
        Imagen: formData.get('imagen'),
        idCategoria: parseInt(formData.get('categoria')),
        idMunicipio: parseInt(formData.get('municipio'))
    };

    const url = isEdit
        ? `http://127.0.0.1:8000/api/actualizarActividades/${activityId}`
        : 'http://127.0.0.1:8000/api/crearActividades';

    const method = isEdit ? 'PUT' : 'POST';

    fetchWithAuth(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(activityData)
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return response.json().then(err => { throw err; });
        }
    })
    .then(data => {
        const message = isEdit ? 'Actividad actualizada exitosamente' : 'Actividad creada exitosamente';
        showNotification(message, 'success');
        closeActivityFormModal();
        loadActivitiesList();
    })
    .catch(error => {
        console.error('Error guardando actividad:', error);
        if (error.errors) {
            let errorMessage = 'Errores de validación:\n';
            Object.keys(error.errors).forEach(field => {
                errorMessage += `- ${field}: ${error.errors[field].join(', ')}\n`;
            });
            showNotification(errorMessage, 'error');
        } else {
            showNotification(error.message || 'Error al guardar actividad', 'error');
        }
    });
}

function loadCategoriesForActivity(selectedId = null) {
    fetchWithAuth('http://127.0.0.1:8000/api/categories')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('activityCategoria');
            select.innerHTML = '<option value="">Seleccione una categoría</option>';

            data.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.nombre;
                if (selectedId && category.id === selectedId) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Error cargando categorías:', error));
}

function loadMunicipiosForActivity(selectedId = null) {
    fetchWithAuth('http://127.0.0.1:8000/api/listarMunicipios')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('activityMunicipio');
            select.innerHTML = '<option value="">Seleccione un municipio</option>';

            data.forEach(municipio => {
                const option = document.createElement('option');
                option.value = municipio.id;
                option.textContent = municipio.Nombre_Municipio;
                if (selectedId && municipio.id === selectedId) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Error cargando municipios:', error));
}

function manageCompanies() {
    document.getElementById('companiesModal').classList.remove('hidden');
    loadCompanies();
}

function loadCompanies() {
    const activeTable = document.getElementById('active-companies-table');
    const inactiveTable = document.getElementById('inactive-companies-table');

    fetchWithAuth('http://127.0.0.1:8000/api/empresas')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Limpiar tablas
                activeTable.innerHTML = '';
                inactiveTable.innerHTML = '';

                data.data.forEach(company => {
                    const statusButton = company.estado
                        ? `<button class="bg-green-500 text-white px-2 py-1 rounded text-xs cursor-default">Activa</button>`
                        : `<button class="bg-red-500 text-white px-2 py-1 rounded text-xs cursor-default">Bloqueada</button>`;

                    const actionButtons = company.estado
                        ? `
                            <div class="flex space-x-2">
                                <button onclick="editCompany(${company.id})" class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">Editar</button>
                                <button onclick="deleteCompany(${company.id})" class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">Eliminar</button>
                                <button onclick="toggleCompanyStatus(${company.id}, ${company.estado})" class="bg-orange-500 text-white px-2 py-1 rounded text-xs hover:bg-orange-600">Bloquear</button>
                            </div>
                        `
                        : `
                            <div class="flex space-x-2">
                                <button onclick="toggleCompanyStatus(${company.id}, ${company.estado})" class="bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600">Desbloquear</button>
                            </div>
                        `;

                    const row = `
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">${company.id}</td>
                            <td class="px-4 py-2 font-medium">${company.nombre}</td>
                            <td class="px-4 py-2">${company.correo}</td>
                            <td class="px-4 py-2">${company.ciudad || 'N/A'}</td>
                            <td class="px-4 py-2">${statusButton}</td>
                            <td class="px-4 py-2">${actionButtons}</td>
                        </tr>
                    `;

                    if (company.estado) {
                        activeTable.innerHTML += row;
                    } else {
                        inactiveTable.innerHTML += row;
                    }
                });

                // Mostrar mensaje si no hay empresas en alguna tabla
                if (activeTable.innerHTML === '') {
                    activeTable.innerHTML = '<tr><td colspan="6" class="px-4 py-4 text-center text-gray-500">No hay empresas activas</td></tr>';
                }
                if (inactiveTable.innerHTML === '') {
                    inactiveTable.innerHTML = '<tr><td colspan="6" class="px-4 py-4 text-center text-gray-500">No hay empresas bloqueadas</td></tr>';
                }
            } else {
                throw new Error(data.message || 'Error al cargar empresas');
            }
        })
        .catch(error => {
            console.error('Error cargando empresas:', error);
            activeTable.innerHTML = '<tr><td colspan="6" class="px-4 py-4 text-center text-red-500">Error al cargar las empresas</td></tr>';
            inactiveTable.innerHTML = '<tr><td colspan="6" class="px-4 py-4 text-center text-red-500">Error al cargar las empresas</td></tr>';
        });
}

function closeCompaniesModal() {
    document.getElementById('companiesModal').classList.add('hidden');
}

function showCreateCompanyForm() {
    document.getElementById('companyFormTitle').textContent = 'Crear Empresa';
    document.getElementById('companyForm').reset();
    document.getElementById('companyId').value = '';
    document.getElementById('companyFormModal').classList.remove('hidden');
}

function closeCompanyFormModal() {
    document.getElementById('companyFormModal').classList.add('hidden');
    document.getElementById('companyForm').reset();
}

function editCompany(id) {
    fetchWithAuth(`http://127.0.0.1:8000/api/empresas/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('companyFormTitle').textContent = 'Editar Empresa';
                document.getElementById('companyId').value = data.data.id;
                document.getElementById('companyNumero').value = data.data.numero || '';
                document.getElementById('companyNombre').value = data.data.nombre || '';
                document.getElementById('companyDireccion').value = data.data.direccion || '';
                document.getElementById('companyCiudad').value = data.data.ciudad || '';
                document.getElementById('companyCorreo').value = data.data.correo || '';
                document.getElementById('companyFormModal').classList.remove('hidden');
            } else {
                throw new Error(data.message || 'Error al cargar empresa');
            }
        })
        .catch(error => {
            console.error('Error cargando empresa:', error);
            showNotification('Error al cargar datos de la empresa', 'error');
        });
}

function deleteCompany(id) {
    if (confirm('¿Estás seguro de eliminar esta empresa? Esta acción no se puede deshacer y eliminará todas sus actividades y reservas.')) {
        fetchWithAuth(`http://127.0.0.1:8000/api/empresas/${id}`, {
            method: 'DELETE'
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                return response.json().then(err => { throw err; });
            }
        })
        .then(data => {
            showNotification('Empresa eliminada exitosamente', 'success');
            loadCompanies(); // Recargar la lista
        })
        .catch(error => {
            console.error('Error eliminando empresa:', error);
            if (error.restricciones) {
                let message = 'No se puede eliminar la empresa debido a las siguientes restricciones:\n';
                error.restricciones.forEach(restriccion => {
                    message += `- ${restriccion}\n`;
                });
                showNotification(message, 'error');
            } else {
                showNotification(error.message || 'Error al eliminar empresa', 'error');
            }
        });
    }
}

function toggleCompanyStatus(id, currentStatus) {
    const newStatus = !currentStatus;
    const action = newStatus ? 'desbloquear' : 'bloquear';

    if (confirm(`¿Estás seguro de ${action} esta empresa?`)) {
        fetchWithAuth(`http://127.0.0.1:8000/api/empresas/${id}/toggle-status`, {
            method: 'PUT'
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Error al cambiar estado de empresa');
            }
        })
        .then(data => {
            showNotification(data.message, 'success');
            loadCompanies(); // Recargar la lista
        })
        .catch(error => {
            console.error('Error cambiando estado de empresa:', error);
            showNotification('Error al cambiar estado de empresa', 'error');
        });
    }
}

function saveCompany() {
    const form = document.getElementById('companyForm');
    const formData = new FormData(form);
    const companyId = document.getElementById('companyId').value;
    const isEdit = companyId !== '';

    const companyData = {
        numero: formData.get('numero'),
        nombre: formData.get('nombre'),
        direccion: formData.get('direccion'),
        ciudad: formData.get('ciudad'),
        correo: formData.get('correo')
    };

    // Solo incluir contraseña si se proporciona y no es edición
    const password = formData.get('contraseña');
    if (password && password.trim() !== '') {
        companyData.contraseña = password;
    }

    const url = isEdit
        ? `http://127.0.0.1:8000/api/empresas/${companyId}`
        : 'http://127.0.0.1:8000/api/empresas';

    const method = isEdit ? 'PUT' : 'POST';

    fetchWithAuth(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(companyData)
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return response.json().then(err => { throw err; });
        }
    })
    .then(data => {
        const message = isEdit ? 'Empresa actualizada exitosamente' : 'Empresa creada exitosamente';
        showNotification(message, 'success');
        closeCompanyFormModal();
        loadCompanies(); // Recargar la lista
    })
    .catch(error => {
        console.error('Error guardando empresa:', error);
        if (error.errors) {
            let errorMessage = 'Errores de validación:\n';
            Object.keys(error.errors).forEach(field => {
                errorMessage += `- ${field}: ${error.errors[field].join(', ')}\n`;
            });
            showNotification(errorMessage, 'error');
        } else {
            showNotification(error.message || 'Error al guardar empresa', 'error');
        }
    });
}

function viewCompanyReport(companyId) {
    fetchWithAuth(`http://127.0.0.1:8000/api/empresas/${companyId}/reporte`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const report = data.data;
                let reportHtml = `
                    <div class="bg-white p-6 rounded-lg shadow-lg max-w-4xl mx-auto">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Balance de ${report.empresa.nombre}</h2>
                            <button onclick="closeCompanyReportModal()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-blue-50 p-4 rounded-lg text-center">
                                <h3 class="text-2xl font-bold text-blue-600">${report.estadisticas.total_actividades}</h3>
                                <p class="text-blue-600">Total Actividades</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg text-center">
                                <h3 class="text-2xl font-bold text-green-600">${report.estadisticas.total_reservas}</h3>
                                <p class="text-green-600">Total Reservas</p>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg text-center">
                                <h3 class="text-2xl font-bold text-yellow-600">$${report.estadisticas.total_ingresos}</h3>
                                <p class="text-yellow-600">Total Ingresos</p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-xl font-semibold mb-4">Actividades</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full table-auto border-collapse border border-gray-300">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border border-gray-300 px-4 py-2 text-left">Nombre</th>
                                            <th class="border border-gray-300 px-4 py-2 text-left">Reservas</th>
                                            <th class="border border-gray-300 px-4 py-2 text-left">Ingresos</th>
                                            <th class="border border-gray-300 px-4 py-2 text-left">Ocupación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                `;

                report.actividades.forEach(actividad => {
                    reportHtml += `
                        <tr class="border-b">
                            <td class="px-4 py-2">${actividad.nombre}</td>
                            <td class="px-4 py-2">${actividad.reservas_count}</td>
                            <td class="px-4 py-2">$${actividad.ingresos}</td>
                            <td class="px-4 py-2">${actividad.ocupacion_porcentaje}%</td>
                        </tr>
                    `;
                });

                reportHtml += `
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-xl font-semibold mb-4">Empleados (${report.estadisticas.total_empleados})</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full table-auto border-collapse border border-gray-300">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border border-gray-300 px-4 py-2 text-left">Nombre</th>
                                            <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                                            <th class="border border-gray-300 px-4 py-2 text-left">Rol</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                `;

                report.empleados.forEach(empleado => {
                    reportHtml += `
                        <tr class="border-b">
                            <td class="px-4 py-2">${empleado.nombre}</td>
                            <td class="px-4 py-2">${empleado.email}</td>
                            <td class="px-4 py-2">${empleado.rol}</td>
                        </tr>
                    `;
                });

                reportHtml += `
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                `;

                document.getElementById('companyReportContent').innerHTML = reportHtml;
                document.getElementById('companyReportModal').classList.remove('hidden');
            } else {
                throw new Error(data.message || 'Error al cargar reporte');
            }
        })
        .catch(error => {
            console.error('Error cargando reporte de empresa:', error);
            showNotification('Error al cargar el balance de la empresa', 'error');
        });
}

function closeCompanyReportModal() {
    document.getElementById('companyReportModal').classList.add('hidden');
}

function manageCategories() {
    document.getElementById('categoriesModal').classList.remove('hidden');
    loadCategories();
}


function managePublications() {
    const listSection = document.getElementById('list-section');
    listSection.innerHTML = '<h3 class="text-2xl font-bold mb-6 text-gray-800">Gestión de Publicaciones</h3><p>Funcionalidad próximamente...</p>';
}

function manageMunicipios() {
    const listSection = document.getElementById('list-section');
    listSection.innerHTML = `
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Gestión de Municipios</h3>
            <div class="flex space-x-4">
                <button onclick="showMunicipiosMap()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    Ver Mapa
                </button>
                <button onclick="showCreateMunicipioForm()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Crear Municipio</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Nombre</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Departamento</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody id="municipios-table">
                    <!-- Municipios se cargarán aquí -->
                </tbody>
            </table>
        </div>
    `;
    loadMunicipios();
}

function loadMunicipios() {
    const municipiosTable = document.getElementById('municipios-table');

    fetchWithAuth('http://127.0.0.1:8000/api/municipios')
        .then(response => response.json())
        .then(data => {
            let html = '';

            data.forEach(municipio => {
                html += `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">${municipio.id}</td>
                        <td class="px-4 py-2 font-medium">${municipio.Nombre_Municipio}</td>
                        <td class="px-4 py-2">${municipio.departamento ? municipio.departamento.Nombre_Departamento : 'N/A'}</td>
                        <td class="px-4 py-2">
                            <div class="flex space-x-2">
                                <button onclick="editMunicipio(${municipio.id})" class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600">Editar</button>
                                <button onclick="deleteMunicipio(${municipio.id})" class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            municipiosTable.innerHTML = html;

            // Mostrar mensaje si no hay municipios
            if (data.length === 0) {
                municipiosTable.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">No hay municipios registrados</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error cargando municipios:', error);
            municipiosTable.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-red-500">Error al cargar los municipios</td></tr>';
        });
}

function closeMunicipiosModal() {
    document.getElementById('municipiosModal').classList.add('hidden');
}

function showCreateMunicipioForm() {
    document.getElementById('municipioFormTitle').textContent = 'Crear Municipio';
    document.getElementById('municipioForm').reset();
    document.getElementById('municipioId').value = '';
    loadDepartamentosForMunicipio();
    document.getElementById('municipioFormModal').classList.remove('hidden');
}

function closeMunicipioFormModal() {
    document.getElementById('municipioFormModal').classList.add('hidden');
    document.getElementById('municipioForm').reset();
}

function editMunicipio(id) {
    fetchWithAuth(`http://127.0.0.1:8000/api/municipios/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('municipioFormTitle').textContent = 'Editar Municipio';
                document.getElementById('municipioId').value = data.data.id;
                document.getElementById('municipioNombre').value = data.data.Nombre_Municipio;
                loadDepartamentosForMunicipio(data.data.idDepartamento);
                document.getElementById('municipioFormModal').classList.remove('hidden');
            } else {
                throw new Error(data.message || 'Error al cargar municipio');
            }
        })
        .catch(error => {
            console.error('Error cargando municipio:', error);
            showNotification('Error al cargar datos del municipio', 'error');
        });
}

function deleteMunicipio(id) {
    if (confirm('¿Estás seguro de eliminar este municipio? Esta acción no se puede deshacer.')) {
        fetchWithAuth(`http://127.0.0.1:8000/api/municipios/${id}`, {
            method: 'DELETE'
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                return response.json().then(err => { throw err; });
            }
        })
        .then(data => {
            showNotification('Municipio eliminado exitosamente', 'success');
            loadMunicipios(); // Recargar la lista
        })
        .catch(error => {
            console.error('Error eliminando municipio:', error);
            if (error.message && error.message.includes('actividades asociadas')) {
                showNotification('No se puede eliminar el municipio porque tiene actividades asociadas', 'error');
            } else {
                showNotification(error.message || 'Error al eliminar municipio', 'error');
            }
        });
    }
}

function saveMunicipio() {
    const form = document.getElementById('municipioForm');
    const formData = new FormData(form);
    const municipioId = document.getElementById('municipioId').value;
    const isEdit = municipioId !== '';

    const municipioData = {
        Nombre_Municipio: formData.get('nombre'),
        idDepartamento: parseInt(formData.get('departamento'))
    };

    const url = isEdit
        ? `http://127.0.0.1:8000/api/municipios/${municipioId}`
        : 'http://127.0.0.1:8000/api/municipios';

    const method = isEdit ? 'PUT' : 'POST';

    fetchWithAuth(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(municipioData)
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return response.json().then(err => { throw err; });
        }
    })
    .then(data => {
        const message = isEdit ? 'Municipio actualizado exitosamente' : 'Municipio creado exitosamente';
        showNotification(message, 'success');
        closeMunicipioFormModal();
        loadMunicipios(); // Recargar la lista
    })
    .catch(error => {
        console.error('Error guardando municipio:', error);
        if (error.errors) {
            let errorMessage = 'Errores de validación:\n';
            Object.keys(error.errors).forEach(field => {
                errorMessage += `- ${field}: ${error.errors[field].join(', ')}\n`;
            });
            showNotification(errorMessage, 'error');
        } else {
            showNotification(error.message || 'Error al guardar municipio', 'error');
        }
    });
}

function loadDepartamentosForMunicipio(selectedId = null) {
    fetchWithAuth('http://127.0.0.1:8000/api/departamentos')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('municipioDepartamento');
            select.innerHTML = '<option value="">Seleccione un departamento</option>';

            data.forEach(departamento => {
                const option = document.createElement('option');
                option.value = departamento.id;
                option.textContent = departamento.Nombre_Departamento;
                if (selectedId && departamento.id === selectedId) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Error cargando departamentos:', error));
}

function showMunicipiosMap() {
    document.getElementById('municipiosMapModal').classList.remove('hidden');
    loadMunicipiosMap();
}

function loadMunicipiosMap() {
    // Aquí implementaremos el mapa con Leaflet
    const mapContainer = document.getElementById('municipios-map');

    // Si ya hay un mapa, destruirlo
    if (window.municipiosMap) {
        window.municipiosMap.remove();
    }

    // Crear mapa centrado en Colombia
    window.municipiosMap = L.map('municipios-map').setView([4.5709, -74.2973], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(window.municipiosMap);

    // Agregar control de búsqueda
    L.Control.geocoder({
        defaultMarkGeocode: false,
        placeholder: 'Buscar municipios o lugares...',
        errorMessage: 'No se encontraron resultados'
    })
    .on('markgeocode', function(e) {
        const bbox = e.geocode.bbox;
        const poly = L.polygon([
            bbox.getSouthEast(),
            bbox.getNorthEast(),
            bbox.getNorthWest(),
            bbox.getSouthWest()
        ]).addTo(window.municipiosMap);
        window.municipiosMap.fitBounds(poly.getBounds());

        // Agregar marcador en el resultado de búsqueda
        L.marker(e.geocode.center)
            .addTo(window.municipiosMap)
            .bindPopup(`<b>${e.geocode.name}</b><br>Resultado de búsqueda`)
            .openPopup();
    })
    .addTo(window.municipiosMap);

    // Agregar control personalizado para buscar municipios específicos
    const searchControl = L.control({position: 'topright'});
    searchControl.onAdd = function(map) {
        const div = L.DomUtil.create('div', 'municipio-search-control');
        div.innerHTML = `
            <div class="bg-white p-2 rounded shadow-md border">
                <select id="municipio-search-select" class="w-full p-2 border rounded text-sm">
                    <option value="">Buscar municipio específico...</option>
                </select>
            </div>
        `;
        return div;
    };
    searchControl.addTo(window.municipiosMap);

    // Poblar el select con municipios que tienen coordenadas válidas
    const searchSelect = document.getElementById('municipio-search-select');
    fetchWithAuth('http://127.0.0.1:8000/api/municipios')
        .then(response => response.json())
        .then(data => {
            // Filtrar solo municipios con coordenadas válidas
            const municipiosConCoordenadas = data.filter(municipio => {
                const lat = parseFloat(municipio.latitud);
                const lng = parseFloat(municipio.longitud);
                return !isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180;
            });

            municipiosConCoordenadas.forEach(municipio => {
                const option = document.createElement('option');
                option.value = municipio.id;
                option.textContent = municipio.Nombre_Municipio;
                option.dataset.lat = municipio.latitud;
                option.dataset.lng = municipio.longitud;
                searchSelect.appendChild(option);
            });

            // Si no hay municipios con coordenadas, mostrar mensaje
            if (municipiosConCoordenadas.length === 0) {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No hay municipios con coordenadas disponibles';
                option.disabled = true;
                searchSelect.appendChild(option);
            }
        })
        .catch(error => console.error('Error cargando municipios para búsqueda:', error));

    // Event listener para el select de búsqueda
    searchSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value && selectedOption.dataset.lat && selectedOption.dataset.lng) {
            const lat = parseFloat(selectedOption.dataset.lat);
            const lng = parseFloat(selectedOption.dataset.lng);

            // Validar coordenadas antes de usarlas
            if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                window.municipiosMap.setView([lat, lng], 12);

                // Encontrar y abrir popup del marcador correspondiente
                window.municipiosMap.eachLayer(function(layer) {
                    if (layer instanceof L.Marker && layer.getLatLng().lat === lat && layer.getLatLng().lng === lng) {
                        layer.openPopup();
                    }
                });
            } else {
                showNotification('Coordenadas inválidas para este municipio', 'error');
            }
        }
    });

    // Cargar municipios con coordenadas
    fetchWithAuth('http://127.0.0.1:8000/api/municipios')
        .then(response => response.json())
        .then(data => {
            data.forEach(municipio => {
                // Validar que las coordenadas sean números válidos
                const lat = parseFloat(municipio.latitud);
                const lng = parseFloat(municipio.longitud);

                if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    // Verificar si el municipio tiene actividades activas
                    fetchWithAuth(`http://127.0.0.1:8000/api/listarActividades?municipio=${municipio.id}`)
                        .then(response => response.json())
                        .then(actividades => {
                            const hasActiveActivities = actividades.some(act =>
                                new Date(act.Fecha_Actividad) >= new Date()
                            );

                            if (hasActiveActivities) {
                                L.marker([lat, lng])
                                    .addTo(window.municipiosMap)
                                    .bindPopup(`
                                        <div class="text-center">
                                            <b>${municipio.Nombre_Municipio}</b><br>
                                            ${municipio.departamento ? municipio.departamento.Nombre_Departamento : 'N/A'}<br>
                                            <small>Actividades activas disponibles</small><br><br>
                                            <div class="flex flex-col space-y-1">
                                                <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}"
                                                   target="_blank"
                                                   class="inline-block bg-blue-500 text-white text-xs px-2 py-1 rounded hover:bg-blue-600">
                                                    📍 Ver en Google Maps
                                                </a>
                                                <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}"
                                                   target="_blank"
                                                   class="inline-block bg-green-500 text-white text-xs px-2 py-1 rounded hover:bg-green-600">
                                                    🗺️ Cómo llegar
                                                </a>
                                            </div>
                                        </div>
                                    `);
                            }
                        })
                        .catch(error => console.error('Error verificando actividades:', error));
                }
            });
        })
        .catch(error => console.error('Error cargando municipios para mapa:', error));
}

function closeMunicipiosMapModal() {
    document.getElementById('municipiosMapModal').classList.add('hidden');
    if (window.municipiosMap) {
        window.municipiosMap.remove();
    }
}

function viewReports() {
    const listSection = document.getElementById('list-section');
    listSection.innerHTML = `
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-3xl font-bold text-gray-800">📊 Reportes y Estadísticas</h3>
                <div class="flex space-x-4">
                    <select id="report-period" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="week">Esta Semana</option>
                        <option value="month" selected>Este Mes</option>
                        <option value="quarter">Este Trimestre</option>
                        <option value="year">Este Año</option>
                    </select>
                    <button onclick="refreshReports()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Actualizar
                    </button>
                </div>
            </div>

            <!-- Estadísticas Generales -->
            <div id="general-stats" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Los stats se cargarán aquí -->
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Empresas Registradas -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h4 class="text-xl font-semibold mb-4 text-gray-800">🏢 Empresas Registradas</h4>
                    <canvas id="empresas-chart" width="400" height="300"></canvas>
                </div>

                <!-- Turistas Registrados -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h4 class="text-xl font-semibold mb-4 text-gray-800">👥 Turistas Registrados</h4>
                    <canvas id="turistas-chart" width="400" height="300"></canvas>
                </div>

                <!-- Reservas por Semana -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h4 class="text-xl font-semibold mb-4 text-gray-800">📅 Reservas por Semana</h4>
                    <canvas id="reservas-semana-chart" width="400" height="300"></canvas>
                </div>

                <!-- Reservas por Mes -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h4 class="text-xl font-semibold mb-4 text-gray-800">📊 Reservas por Mes</h4>
                    <canvas id="reservas-mes-chart" width="400" height="300"></canvas>
                </div>
            </div>

            <!-- Tabla de Datos Recientes -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h4 class="text-xl font-semibold mb-4 text-gray-800">📋 Actividad Reciente</h4>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2 text-left">Tipo</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Descripción</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Fecha</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Estado</th>
                            </tr>
                        </thead>
                        <tbody id="recent-activity-table">
                            <!-- Actividad reciente se cargará aquí -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;

    // Cargar datos iniciales
    loadGeneralStats();
    loadEmpresasChart();
    loadTuristasChart();
    loadReservasCharts();
    loadRecentActivity();

    // Event listener para cambio de período
    document.getElementById('report-period').addEventListener('change', function() {
        loadEmpresasChart();
        loadTuristasChart();
        loadReservasCharts();
        loadGeneralStats();
    });
}

// Funciones de categorías
function loadCategories() {
    const activeTable = document.getElementById('active-categories-table');
    const inactiveTable = document.getElementById('inactive-categories-table');

    fetchWithAuth('http://127.0.0.1:8000/api/categories')
        .then(response => response.json())
        .then(data => {
            // Limpiar tablas
            activeTable.innerHTML = '';
            inactiveTable.innerHTML = '';

            data.forEach(category => {
                const row = `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">${category.id}</td>
                        <td class="px-4 py-2 font-medium">${category.nombre}</td>
                        <td class="px-4 py-2">${category.descripcion || 'Sin descripción'}</td>
                        <td class="px-4 py-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${category.estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                ${category.estado ? 'Activa' : 'Inactiva'}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex space-x-2">
                                <button onclick="editCategory(${category.id})" class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600">Editar</button>
                                <button onclick="toggleCategoryStatus(${category.id}, ${category.estado})" class="${category.estado ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600'} text-white px-2 py-1 rounded text-xs">
                                    ${category.estado ? 'Desactivar' : 'Activar'}
                                </button>
                                <button onclick="deleteCategory(${category.id})" class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                `;

                if (category.estado) {
                    activeTable.innerHTML += row;
                } else {
                    inactiveTable.innerHTML += row;
                }
            });

            // Mostrar mensaje si no hay categorías en alguna tabla
            if (activeTable.innerHTML === '') {
                activeTable.innerHTML = '<tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">No hay categorías activas</td></tr>';
            }
            if (inactiveTable.innerHTML === '') {
                inactiveTable.innerHTML = '<tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">No hay categorías inactivas</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error cargando categorías:', error);
            activeTable.innerHTML = '<tr><td colspan="5" class="px-4 py-4 text-center text-red-500">Error al cargar las categorías</td></tr>';
            inactiveTable.innerHTML = '<tr><td colspan="5" class="px-4 py-4 text-center text-red-500">Error al cargar las categorías</td></tr>';
        });
}

function closeCategoriesModal() {
    document.getElementById('categoriesModal').classList.add('hidden');
}

function showCreateCategoryForm() {
    document.getElementById('categoryFormTitle').textContent = 'Crear Categoría';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryFormModal').classList.remove('hidden');
}

function closeCategoryFormModal() {
    document.getElementById('categoryFormModal').classList.add('hidden');
    document.getElementById('categoryForm').reset();
}

function editCategory(id) {
    fetchWithAuth(`http://127.0.0.1:8000/api/categories/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('categoryFormTitle').textContent = 'Editar Categoría';
            document.getElementById('categoryId').value = data.id;
            document.getElementById('categoryNombre').value = data.nombre;
            document.getElementById('categoryDescripcion').value = data.descripcion || '';
            document.getElementById('categoryImagen').value = data.imagen || '';
            document.getElementById('categoryEstado').checked = data.estado;
            document.getElementById('categoryFormModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error cargando categoría:', error);
            showNotification('Error al cargar datos de la categoría', 'error');
        });
}

function deleteCategory(id) {
    if (confirm('¿Estás seguro de eliminar esta categoría? Esta acción no se puede deshacer.')) {
        fetchWithAuth(`http://127.0.0.1:8000/api/categories/${id}`, {
            method: 'DELETE'
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Error al eliminar categoría');
            }
        })
        .then(data => {
            showNotification('Categoría eliminada exitosamente', 'success');
            loadCategories(); // Recargar la lista
        })
        .catch(error => {
            console.error('Error eliminando categoría:', error);
            if (error.message && error.message.includes('actividades asociadas')) {
                showNotification('No se puede eliminar la categoría porque tiene actividades asociadas', 'error');
            } else {
                showNotification('Error al eliminar categoría', 'error');
            }
        });
    }
}

function toggleCategoryStatus(id, currentStatus) {
    const newStatus = !currentStatus;
    const action = newStatus ? 'activar' : 'desactivar';

    if (confirm(`¿Estás seguro de ${action} esta categoría?`)) {
        fetchWithAuth(`http://127.0.0.1:8000/api/categories/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ estado: newStatus })
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Error al cambiar estado de categoría');
            }
        })
        .then(data => {
            showNotification(`Categoría ${newStatus ? 'activada' : 'desactivada'} exitosamente`, 'success');
            loadCategories(); // Recargar la lista
        })
        .catch(error => {
            console.error('Error cambiando estado de categoría:', error);
            showNotification('Error al cambiar estado de categoría', 'error');
        });
    }
}

function saveCategory() {
    const form = document.getElementById('categoryForm');
    const formData = new FormData(form);
    const categoryId = document.getElementById('categoryId').value;
    const isEdit = categoryId !== '';

    const categoryData = {
        nombre: formData.get('nombre'),
        descripcion: formData.get('descripcion'),
        imagen: formData.get('imagen'),
        estado: formData.get('estado') === 'on' // Checkbox
    };

    const url = isEdit
        ? `http://127.0.0.1:8000/api/categories/${categoryId}`
        : 'http://127.0.0.1:8000/api/categories';

    const method = isEdit ? 'PUT' : 'POST';

    fetchWithAuth(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(categoryData)
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return response.json().then(err => { throw err; });
        }
    })
    .then(data => {
        const message = isEdit ? 'Categoría actualizada exitosamente' : 'Categoría creada exitosamente';
        showNotification(message, 'success');
        closeCategoryFormModal();
        loadCategories(); // Recargar la lista
    })
    .catch(error => {
        console.error('Error guardando categoría:', error);
        if (error.errors) {
            let errorMessage = 'Errores de validación:\n';
            Object.keys(error.errors).forEach(field => {
                errorMessage += `- ${field}: ${error.errors[field].join(', ')}\n`;
            });
            showNotification(errorMessage, 'error');
        } else {
            showNotification(error.message || 'Error al guardar categoría', 'error');
        }
    });
}

// Funciones de usuarios
function showCreateUserForm() {
    document.getElementById('userFormTitle').textContent = 'Crear Usuario';
    document.getElementById('userForm').reset();
    document.getElementById('userId').value = '';
    document.getElementById('userFormModal').classList.remove('hidden');
}

function closeUserFormModal() {
    document.getElementById('userFormModal').classList.add('hidden');
    document.getElementById('userForm').reset();
}

function editUser(id) {
    // Obtener datos del usuario
    fetchWithAuth(`http://127.0.0.1:8000/api/usuarios/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('userFormTitle').textContent = 'Editar Usuario';
            document.getElementById('userId').value = data.id;
            document.getElementById('userNombre').value = data.Nombre;
            document.getElementById('userApellido').value = data.Apellido;
            document.getElementById('userEmail').value = data.Email;
            document.getElementById('userTelefono').value = data.Telefono;
            document.getElementById('userNacionalidad').value = data.Nacionalidad;
            document.getElementById('userRol').value = data.Rol;
            document.getElementById('userFormModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error cargando usuario:', error);
            showNotification('Error al cargar datos del usuario', 'error');
        });
}

function deleteUser(id) {
    if (confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')) {
        fetchWithAuth(`http://127.0.0.1:8000/api/eliminarUsuarios/${id}`, {
            method: 'DELETE'
        })
        .then(response => {
            if (response.ok) {
                showNotification('Usuario eliminado exitosamente', 'success');
                loadUserList(); // Recargar la lista
            } else {
                throw new Error('Error al eliminar usuario');
            }
        })
        .catch(error => {
            console.error('Error eliminando usuario:', error);
            showNotification('Error al eliminar usuario', 'error');
        });
    }
}

function saveUser() {
    const form = document.getElementById('userForm');
    const formData = new FormData(form);
    const userId = document.getElementById('userId').value;
    const isEdit = userId !== '';

    const userData = {
        Nombre: formData.get('nombre'),
        Apellido: formData.get('apellido'),
        Email: formData.get('email'),
        Telefono: formData.get('telefono'),
        Nacionalidad: formData.get('nacionalidad'),
        Rol: formData.get('rol')
    };

    // Solo incluir contraseña si se proporciona
    const password = formData.get('contraseña');
    if (password) {
        userData.Contraseña = password;
    }

    const url = isEdit
        ? `http://127.0.0.1:8000/api/actualizarUsuarios/${userId}`
        : 'http://127.0.0.1:8000/api/crearUsuarios';

    const method = isEdit ? 'PUT' : 'POST';

    fetchWithAuth(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(userData)
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return response.json().then(err => { throw err; });
        }
    })
    .then(data => {
        const message = isEdit ? 'Usuario actualizado exitosamente' : 'Usuario creado exitosamente';
        showNotification(message, 'success');
        closeUserFormModal();
        loadUserList(); // Recargar la lista
    })
    .catch(error => {
        console.error('Error guardando usuario:', error);
        if (error.errors) {
            // Mostrar errores de validación
            let errorMessage = 'Errores de validación:\n';
            Object.keys(error.errors).forEach(field => {
                errorMessage += `- ${field}: ${error.errors[field].join(', ')}\n`;
            });
            showNotification(errorMessage, 'error');
        } else {
            showNotification('Error al guardar usuario', 'error');
        }
    });
}

// Funciones de reportes
function loadGeneralStats() {
    fetchWithAuth('http://127.0.0.1:8000/api/reportes/dashboard-data')
        .then(response => response.json())
        .then(data => {
            const statsContainer = document.getElementById('general-stats');
            statsContainer.innerHTML = `
                <div class="bg-blue-50 p-6 rounded-lg text-center">
                    <h3 class="text-2xl font-bold text-blue-600">${data.usuarios_registrados}</h3>
                    <p class="text-blue-600">Turistas Registrados</p>
                </div>
                <div class="bg-green-50 p-6 rounded-lg text-center">
                    <h3 class="text-2xl font-bold text-green-600">${data.total_reservas}</h3>
                    <p class="text-green-600">Total Reservas</p>
                </div>
                <div class="bg-yellow-50 p-6 rounded-lg text-center">
                    <h3 class="text-2xl font-bold text-yellow-600">$${data.ingresos_totales}</h3>
                    <p class="text-yellow-600">Ingresos Totales</p>
                </div>
                <div class="bg-purple-50 p-6 rounded-lg text-center">
                    <h3 class="text-2xl font-bold text-purple-600">${data.empresas_registradas}</h3>
                    <p class="text-purple-600">Empresas Registradas</p>
                </div>
            `;
        })
        .catch(error => console.error('Error cargando estadísticas generales:', error));
}

function loadEmpresasChart() {
    const period = document.getElementById('report-period').value;
    const canvas = document.getElementById('empresas-chart');

    if (window.empresasChart) {
        window.empresasChart.destroy();
    }

    // Para empresas, necesitamos datos agrupados por fecha de registro
    fetchWithAuth(`http://127.0.0.1:8000/api/reportes/dashboard-data?period=${period}`)
        .then(response => response.json())
        .then(data => {
            // Crear datos simulados para el gráfico de empresas (por ahora usamos datos del período)
            const labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'];
            const empresasData = [2, 5, 3, 8, 6, data.empresas_registradas];

            window.empresasChart = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Empresas Registradas',
                        data: empresasData,
                        borderColor: 'rgb(147, 51, 234)',
                        backgroundColor: 'rgba(147, 51, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Registro de Empresas'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error cargando gráfico de empresas:', error));
}

function loadTuristasChart() {
    const period = document.getElementById('report-period').value;
    const canvas = document.getElementById('turistas-chart');

    if (window.turistasChart) {
        window.turistasChart.destroy();
    }

    fetchWithAuth(`http://127.0.0.1:8000/api/reportes/usuarios-chart?period=${period}`)
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.date);
            const values = data.map(item => item.count);

            window.turistasChart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Turistas Registrados',
                        data: values,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Registro de Turistas por Día'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error cargando gráfico de turistas:', error));
}

function loadReservasCharts() {
    const period = document.getElementById('report-period').value;

    // Gráfico de reservas por semana
    loadReservasSemanaChart(period);

    // Gráfico de reservas por mes
    loadReservasMesChart(period);
}

function loadReservasSemanaChart(period) {
    const canvas = document.getElementById('reservas-semana-chart');

    if (window.reservasSemanaChart) {
        window.reservasSemanaChart.destroy();
    }

    fetchWithAuth(`http://127.0.0.1:8000/api/reportes/reservas-chart?period=week`)
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('es-ES', { weekday: 'short', day: 'numeric' });
            });
            const values = data.map(item => item.count);

            window.reservasSemanaChart = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Reservas por Día',
                        data: values,
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Reservas de la Semana'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error cargando gráfico de reservas semanal:', error));
}

function loadReservasMesChart(period) {
    const canvas = document.getElementById('reservas-mes-chart');

    if (window.reservasMesChart) {
        window.reservasMesChart.destroy();
    }

    fetchWithAuth(`http://127.0.0.1:8000/api/reportes/reservas-chart?period=month`)
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('es-ES', { day: 'numeric', month: 'short' });
            });
            const values = data.map(item => item.count);

            window.reservasMesChart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Reservas por Día',
                        data: values,
                        backgroundColor: 'rgba(245, 101, 101, 0.8)',
                        borderColor: 'rgb(245, 101, 101)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Reservas del Mes'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error cargando gráfico de reservas mensual:', error));
}

function loadRecentActivity() {
    const tableBody = document.getElementById('recent-activity-table');

    // Simular actividad reciente (en una implementación real, esto vendría de la API)
    const activities = [
        { tipo: 'Usuario', descripcion: 'Nuevo turista registrado', fecha: '2025-12-03', estado: 'Completado' },
        { tipo: 'Reserva', descripcion: 'Reserva confirmada para Tunja', fecha: '2025-12-03', estado: 'Confirmada' },
        { tipo: 'Empresa', descripcion: 'Nueva empresa registrada', fecha: '2025-12-02', estado: 'Activa' },
        { tipo: 'Actividad', descripcion: 'Nueva actividad creada', fecha: '2025-12-02', estado: 'Publicada' },
        { tipo: 'Comentario', descripcion: 'Nuevo comentario en actividad', fecha: '2025-12-01', estado: 'Aprobado' }
    ];

    tableBody.innerHTML = activities.map(activity => `
        <tr class="border-b hover:bg-gray-50">
            <td class="px-4 py-2 font-medium">${activity.tipo}</td>
            <td class="px-4 py-2">${activity.descripcion}</td>
            <td class="px-4 py-2">${activity.fecha}</td>
            <td class="px-4 py-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                    activity.estado === 'Completado' || activity.estado === 'Confirmada' || activity.estado === 'Activa' || activity.estado === 'Aprobado'
                        ? 'bg-green-100 text-green-800'
                        : 'bg-blue-100 text-blue-800'
                }">
                    ${activity.estado}
                </span>
            </td>
        </tr>
    `).join('');
}

function refreshReports() {
    loadGeneralStats();
    loadEmpresasChart();
    loadTuristasChart();
    loadReservasCharts();
    loadRecentActivity();
    showNotification('Reportes actualizados', 'success');
}

// Agregar funciones de municipios al objeto window
window.showCreateMunicipioForm = showCreateMunicipioForm;
window.closeMunicipioFormModal = closeMunicipioFormModal;
window.editMunicipio = editMunicipio;
window.deleteMunicipio = deleteMunicipio;
window.saveMunicipio = saveMunicipio;
window.showMunicipiosMap = showMunicipiosMap;
window.closeMunicipiosMapModal = closeMunicipiosMapModal;