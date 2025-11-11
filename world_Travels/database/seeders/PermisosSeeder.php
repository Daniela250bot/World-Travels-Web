<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar permisos existentes para evitar duplicados
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles_permisos')->truncate();
        DB::table('permisos')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Reiniciar auto-increment
        DB::statement('ALTER TABLE permisos AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE roles_permisos AUTO_INCREMENT = 1');

        // Permisos para administradores
        $permisos_admin = [
            // Gestión de Usuarios
            ['nombre' => 'ver_administradores', 'descripcion' => 'Ver lista de administradores', 'modulo' => 'usuarios'],
            ['nombre' => 'crear_administradores', 'descripcion' => 'Crear nuevos administradores', 'modulo' => 'usuarios'],
            ['nombre' => 'editar_administradores', 'descripcion' => 'Editar administradores existentes', 'modulo' => 'usuarios'],
            ['nombre' => 'eliminar_administradores', 'descripcion' => 'Eliminar administradores', 'modulo' => 'usuarios'],
            ['nombre' => 'gestionar_usuarios', 'descripcion' => 'Gestionar usuarios del sistema', 'modulo' => 'usuarios'],
            ['nombre' => 'gestionar_empresas', 'descripcion' => 'Gestionar empresas afiliadas', 'modulo' => 'usuarios'],
            ['nombre' => 'gestionar_guias', 'descripcion' => 'Gestionar guías turísticos', 'modulo' => 'usuarios'],
            ['nombre' => 'gestionar_turistas', 'descripcion' => 'Gestionar turistas', 'modulo' => 'usuarios'],

            // Gestión de Viajes y Actividades
            ['nombre' => 'gestionar_viajes', 'descripcion' => 'Crear, editar y eliminar viajes', 'modulo' => 'viajes'],
            ['nombre' => 'gestionar_actividades', 'descripcion' => 'Administrar actividades turísticas', 'modulo' => 'viajes'],
            ['nombre' => 'aprobar_actividades', 'descripcion' => 'Aprobar actividades creadas por terceros', 'modulo' => 'viajes'],
            ['nombre' => 'gestionar_precios', 'descripcion' => 'Establecer y modificar precios', 'modulo' => 'viajes'],

            // Gestión Financiera
            ['nombre' => 'ver_finanzas', 'descripcion' => 'Visualizar reportes financieros', 'modulo' => 'finanzas'],
            ['nombre' => 'gestionar_comisiones', 'descripcion' => 'Configurar comisiones y pagos', 'modulo' => 'finanzas'],
            ['nombre' => 'generar_reportes_financieros', 'descripcion' => 'Generar reportes de ingresos/egresos', 'modulo' => 'finanzas'],

            // Gestión de Contenido y Marketing
            ['nombre' => 'gestionar_contenido', 'descripcion' => 'Administrar banners, noticias y contenido', 'modulo' => 'marketing'],
            ['nombre' => 'gestionar_promociones', 'descripcion' => 'Crear y gestionar promociones', 'modulo' => 'marketing'],
            ['nombre' => 'moderacion_comentarios', 'descripcion' => 'Moderar comentarios y reseñas', 'modulo' => 'marketing'],

            // Gestión de Destinos y Lugares
            ['nombre' => 'gestionar_destinos', 'descripcion' => 'Administrar destinos y lugares turísticos', 'modulo' => 'destinos'],
            ['nombre' => 'gestionar_categorias', 'descripcion' => 'Gestionar categorías de turismo', 'modulo' => 'destinos'],

            // Gestión de Reservas y Clientes
            ['nombre' => 'ver_reservas', 'descripcion' => 'Visualizar todas las reservas', 'modulo' => 'reservas'],
            ['nombre' => 'gestionar_reservas', 'descripcion' => 'Confirmar, modificar y cancelar reservas', 'modulo' => 'reservas'],
            ['nombre' => 'atender_clientes', 'descripcion' => 'Gestionar soporte al cliente', 'modulo' => 'reservas'],

            // Gestión del Sistema
            ['nombre' => 'configurar_sistema', 'descripcion' => 'Configurar parámetros del sistema', 'modulo' => 'sistema'],
            ['nombre' => 'gestionar_roles', 'descripcion' => 'Administrar roles y permisos', 'modulo' => 'sistema'],
            ['nombre' => 'ver_logs', 'descripcion' => 'Acceder a logs del sistema', 'modulo' => 'sistema'],
            ['nombre' => 'backup_sistema', 'descripcion' => 'Realizar copias de seguridad', 'modulo' => 'sistema'],

            // Reportes y Analítica
            ['nombre' => 'ver_reportes', 'descripcion' => 'Acceder a panel de estadísticas', 'modulo' => 'reportes'],
            ['nombre' => 'generar_reportes', 'descripcion' => 'Generar informes personalizados', 'modulo' => 'reportes'],
            ['nombre' => 'exportar_datos', 'descripcion' => 'Exportar datos a diferentes formatos', 'modulo' => 'reportes'],

            // Permisos Estratégicos
            ['nombre' => 'acceso_total', 'descripcion' => 'Acceso completo a todas las funciones', 'modulo' => 'general'],
        ];

        // Permisos para empresas de viajes
        $permisos_empresa = [
            // Gestión de Perfil Empresarial
            ['nombre' => 'gestionar_perfil_empresarial', 'descripcion' => 'Registrar y editar información de perfil empresarial', 'modulo' => 'perfil'],
            ['nombre' => 'cambiar_foto_perfil', 'descripcion' => 'Cambiar foto de perfil, banner o portada', 'modulo' => 'perfil'],
            ['nombre' => 'actualizar_datos_facturacion', 'descripcion' => 'Actualizar datos de facturación y métodos de pago', 'modulo' => 'perfil'],
            ['nombre' => 'consultar_estado_verificacion', 'descripcion' => 'Consultar estado de verificación o aprobación', 'modulo' => 'perfil'],

            // Gestión de Actividades, Tours y Paquetes
            ['nombre' => 'crear_actividades', 'descripcion' => 'Crear nuevas actividades, tours o paquetes turísticos', 'modulo' => 'actividades'],
            ['nombre' => 'editar_actividades_propias', 'descripcion' => 'Editar, actualizar o eliminar actividades propias', 'modulo' => 'actividades'],
            ['nombre' => 'definir_precios_fechas', 'descripcion' => 'Definir precios, fechas, horarios, puntos de encuentro y número de cupos', 'modulo' => 'actividades'],
            ['nombre' => 'cargar_multimedia', 'descripcion' => 'Cargar imágenes, videos y descripciones detalladas', 'modulo' => 'actividades'],
            ['nombre' => 'establecer_categorias', 'descripcion' => 'Establecer categorías (aventura, cultura, naturaleza, gastronómico, etc.)', 'modulo' => 'actividades'],
            ['nombre' => 'habilitar_deshabilitar_actividades', 'descripcion' => 'Habilitar o deshabilitar temporalmente una actividad', 'modulo' => 'actividades'],
            ['nombre' => 'revisar_comentarios', 'descripcion' => 'Revisar comentarios o calificaciones de usuarios', 'modulo' => 'actividades'],
            ['nombre' => 'consultar_estado_aprobacion', 'descripcion' => 'Consultar estado de aprobación por el administrador', 'modulo' => 'actividades'],
            ['nombre' => 'duplicar_actividades', 'descripcion' => 'Duplicar actividades para eventos similares', 'modulo' => 'actividades'],

            // Gestión Financiera y de Pagos
            ['nombre' => 'consultar_historial_pagos', 'descripcion' => 'Consultar historial de pagos y comisiones', 'modulo' => 'finanzas'],
            ['nombre' => 'descargar_reportes_financieros', 'descripcion' => 'Descargar reportes financieros y facturas (Excel/PDF)', 'modulo' => 'finanzas'],
            ['nombre' => 'configurar_precios_descuentos', 'descripcion' => 'Configurar precios dinámicos o descuentos promocionales', 'modulo' => 'finanzas'],

            // Gestión de Reservas
            ['nombre' => 'visualizar_reservas_propias', 'descripcion' => 'Visualizar todas las reservas en sus actividades', 'modulo' => 'reservas'],
            ['nombre' => 'confirmar_rechazar_reservas', 'descripcion' => 'Confirmar o rechazar reservas según disponibilidad', 'modulo' => 'reservas'],
            ['nombre' => 'modificar_reservas', 'descripcion' => 'Modificar fechas o cupos de reservas (si permitido)', 'modulo' => 'reservas'],
            ['nombre' => 'enviar_recordatorios', 'descripcion' => 'Enviar recordatorios o mensajes a usuarios', 'modulo' => 'reservas'],
            ['nombre' => 'marcar_estado_reservas', 'descripcion' => 'Marcar reservas como completadas, canceladas o pendientes', 'modulo' => 'reservas'],
            ['nombre' => 'generar_comprobantes', 'descripcion' => 'Generar comprobantes o itinerarios personalizados', 'modulo' => 'reservas'],
            ['nombre' => 'consultar_historial_reservas', 'descripcion' => 'Consultar historial de reservas pasadas', 'modulo' => 'reservas'],

            // Gestión de Clientes y Participantes
            ['nombre' => 'acceder_lista_clientes', 'descripcion' => 'Acceder a lista de clientes que reservaron', 'modulo' => 'clientes'],
            ['nombre' => 'ver_informacion_contacto', 'descripcion' => 'Ver información básica de contacto (según políticas de privacidad)', 'modulo' => 'clientes'],
            ['nombre' => 'enviar_mensajes_clientes', 'descripcion' => 'Enviar mensajes informativos o de agradecimiento', 'modulo' => 'clientes'],
            ['nombre' => 'gestionar_requerimientos_especiales', 'descripcion' => 'Gestionar confirmaciones o requerimientos especiales', 'modulo' => 'clientes'],
            ['nombre' => 'revisar_resenas', 'descripcion' => 'Revisar reseñas o calificaciones', 'modulo' => 'clientes'],
            ['nombre' => 'responder_comentarios', 'descripcion' => 'Responder comentarios públicos o privados', 'modulo' => 'clientes'],

            // Marketing y Publicidad
            ['nombre' => 'crear_promociones', 'descripcion' => 'Crear promociones, descuentos o códigos de oferta', 'modulo' => 'marketing'],
            ['nombre' => 'publicar_campanas', 'descripcion' => 'Publicar campañas o anuncios en el perfil', 'modulo' => 'marketing'],
            ['nombre' => 'destacar_actividades', 'descripcion' => 'Destacar actividades con etiquetas "Popular" o "Recomendado"', 'modulo' => 'marketing'],
            ['nombre' => 'vincular_redes_sociales', 'descripcion' => 'Vincular redes sociales o enlaces externos', 'modulo' => 'marketing'],
            ['nombre' => 'configurar_mensajes_automaticos', 'descripcion' => 'Configurar mensajes automáticos de bienvenida o agradecimiento', 'modulo' => 'marketing'],
            ['nombre' => 'analizar_estadisticas_marketing', 'descripcion' => 'Analizar estadísticas de visitas, clics y reservas por campaña', 'modulo' => 'marketing'],

            // Configuración del Sistema Propio
            ['nombre' => 'configurar_notificaciones', 'descripcion' => 'Configurar notificaciones (correo, SMS, push)', 'modulo' => 'sistema'],
            ['nombre' => 'elegir_idioma_zona', 'descripcion' => 'Elegir idioma, zona horaria y moneda por defecto', 'modulo' => 'sistema'],
            ['nombre' => 'actualizar_politicas', 'descripcion' => 'Actualizar políticas de cancelación o reembolso', 'modulo' => 'sistema'],
            ['nombre' => 'integrar_servicios_externos', 'descripcion' => 'Integrar servicios externos (chat, chatbot, CRM)', 'modulo' => 'sistema'],
            ['nombre' => 'gestionar_roles_internos', 'descripcion' => 'Gestionar roles internos (propietario, supervisor, guía)', 'modulo' => 'sistema'],
            ['nombre' => 'solicitar_soporte_tecnico', 'descripcion' => 'Solicitar soporte técnico o reportar errores', 'modulo' => 'sistema'],

            // Integraciones Externas
            ['nombre' => 'conectar_apis_terceros', 'descripcion' => 'Conectar con APIs de terceros (pagos, reservas externas)', 'modulo' => 'integraciones'],
            ['nombre' => 'sincronizar_datos_crm', 'descripcion' => 'Sincronizar datos con sistemas CRM o ERP', 'modulo' => 'integraciones'],
            ['nombre' => 'gestionar_webhooks', 'descripcion' => 'Gestionar webhooks para actualizaciones automáticas', 'modulo' => 'integraciones'],

            // Reportes y Analítica
            ['nombre' => 'ver_estadisticas_ventas', 'descripcion' => 'Ver estadísticas de ventas, reservas y clientes frecuentes', 'modulo' => 'reportes'],
            ['nombre' => 'consultar_destinos_populares', 'descripcion' => 'Consultar destinos o actividades más populares', 'modulo' => 'reportes'],
            ['nombre' => 'generar_reportes_rango', 'descripcion' => 'Generar reportes por rango de fechas, tipo de actividad o región', 'modulo' => 'reportes'],
            ['nombre' => 'descargar_informes', 'descripcion' => 'Descargar informes en PDF o Excel', 'modulo' => 'reportes'],
            ['nombre' => 'monitorear_tendencias', 'descripcion' => 'Monitorear tendencias de temporada y ocupación', 'modulo' => 'reportes'],
            ['nombre' => 'evaluar_desempeno', 'descripcion' => 'Evaluar desempeño de guías o tours', 'modulo' => 'reportes'],
        ];

        // Insertar permisos para administrador
        foreach ($permisos_admin as $permiso) {
            $permisoId = DB::table('permisos')->insertGetId($permiso);
            DB::table('roles_permisos')->insert([
                'rol' => 'administrador',
                'permiso_id' => $permisoId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Permisos para turistas
        $permisos_turista = [
            // Gestión de Perfil Personal
            ['nombre' => 'registrarse_cuenta', 'descripcion' => 'Registrarse y crear cuenta en la aplicación', 'modulo' => 'perfil'],
            ['nombre' => 'iniciar_cerrar_sesion', 'descripcion' => 'Iniciar sesión o cerrar sesión', 'modulo' => 'perfil'],
            ['nombre' => 'editar_datos_personales', 'descripcion' => 'Editar datos personales (nombre, correo, foto, país, teléfono, idioma)', 'modulo' => 'perfil'],
            ['nombre' => 'cambiar_contraseña', 'descripcion' => 'Cambiar contraseña o recuperar acceso', 'modulo' => 'perfil'],
            ['nombre' => 'ver_historial_reservas', 'descripcion' => 'Ver historial de reservas y actividades realizadas', 'modulo' => 'perfil'],
            ['nombre' => 'eliminar_cuenta', 'descripcion' => 'Eliminar o desactivar cuenta voluntariamente', 'modulo' => 'perfil'],
            ['nombre' => 'ver_puntos_recompensas', 'descripcion' => 'Ver puntos o recompensas del sistema de fidelización', 'modulo' => 'perfil'],
            ['nombre' => 'configurar_preferencias', 'descripcion' => 'Configurar preferencias personales (tipo turismo, idioma, moneda, notificaciones)', 'modulo' => 'perfil'],
            ['nombre' => 'vincular_redes_sociales', 'descripcion' => 'Vincular cuentas de redes sociales para inicio de sesión', 'modulo' => 'perfil'],
            ['nombre' => 'gestionar_dispositivos', 'descripcion' => 'Gestionar dispositivos conectados a la cuenta', 'modulo' => 'perfil'],
            ['nombre' => 'configurar_perfiles_grupo', 'descripcion' => 'Configurar perfiles familiares o de grupo para reservas compartidas', 'modulo' => 'perfil'],
            ['nombre' => 'exportar_datos_personales', 'descripcion' => 'Exportar datos personales según leyes de privacidad', 'modulo' => 'perfil'],

            // Exploración y Búsqueda de Actividades
            ['nombre' => 'navegar_catalogo', 'descripcion' => 'Navegar por el catálogo de viajes, tours y paquetes turísticos', 'modulo' => 'exploracion'],
            ['nombre' => 'filtrar_actividades', 'descripcion' => 'Filtrar actividades por ubicación, categoría, precio, duración, popularidad', 'modulo' => 'exploracion'],
            ['nombre' => 'ver_detalles_actividades', 'descripcion' => 'Ver detalles completos de cada actividad (precio, descripción, guías, fotos)', 'modulo' => 'exploracion'],
            ['nombre' => 'acceder_mapa_interactivo', 'descripcion' => 'Acceder al mapa interactivo para visualizar destinos y rutas', 'modulo' => 'exploracion'],
            ['nombre' => 'consultar_disponibilidad', 'descripcion' => 'Consultar disponibilidad de fechas y cupos en tiempo real', 'modulo' => 'exploracion'],
            ['nombre' => 'guardar_favoritos', 'descripcion' => 'Guardar actividades como "Favoritas" o "Deseadas" en listas personalizadas', 'modulo' => 'exploracion'],
            ['nombre' => 'compartir_actividades', 'descripcion' => 'Compartir actividades por redes sociales, mensajería o enlaces directos', 'modulo' => 'exploracion'],
            ['nombre' => 'busqueda_voz_texto', 'descripcion' => 'Buscar actividades mediante voz o texto predictivo', 'modulo' => 'exploracion'],
            ['nombre' => 'ver_resenas_calificaciones', 'descripcion' => 'Ver reseñas y calificaciones de otros usuarios', 'modulo' => 'exploracion'],
            ['nombre' => 'explorar_destinos_relacionados', 'descripcion' => 'Explorar destinos relacionados o sugeridos automáticamente', 'modulo' => 'exploracion'],
            ['nombre' => 'acceder_guias_virtuales', 'descripcion' => 'Acceder a guías virtuales o tours en 360 grados', 'modulo' => 'exploracion'],
            ['nombre' => 'recibir_alertas_nuevas', 'descripcion' => 'Recibir alertas de nuevas actividades en destinos de interés', 'modulo' => 'exploracion'],

            // Gestión de Reservas
            ['nombre' => 'realizar_reservas', 'descripcion' => 'Realizar reservas de actividades o tours disponibles', 'modulo' => 'reservas'],
            ['nombre' => 'seleccionar_opciones_reserva', 'descripcion' => 'Seleccionar fechas, cantidad de personas, opciones adicionales y método de pago', 'modulo' => 'reservas'],
            ['nombre' => 'recibir_confirmacion_reserva', 'descripcion' => 'Recibir confirmación de reserva por correo, notificación push o SMS', 'modulo' => 'reservas'],
            ['nombre' => 'consultar_estado_reservas', 'descripcion' => 'Consultar el estado de sus reservas en dashboard personal', 'modulo' => 'reservas'],
            ['nombre' => 'cancelar_reservas', 'descripcion' => 'Cancelar una reserva dentro de los plazos permitidos con reembolso', 'modulo' => 'reservas'],
            ['nombre' => 'modificar_reservas', 'descripcion' => 'Modificar reservas existentes (fechas o participantes)', 'modulo' => 'reservas'],
            ['nombre' => 'descargar_comprobantes', 'descripcion' => 'Descargar o visualizar comprobantes, itinerarios y vouchers digitales', 'modulo' => 'reservas'],
            ['nombre' => 'ver_politicas_cancelacion', 'descripcion' => 'Ver políticas de cancelación, reembolso o cambios detalladas', 'modulo' => 'reservas'],
            ['nombre' => 'calificar_comentar_actividades', 'descripcion' => 'Calificar o comentar una actividad tras su finalización', 'modulo' => 'reservas'],
            ['nombre' => 'recibir_recordatorios', 'descripcion' => 'Recibir recordatorios automáticos antes de la actividad', 'modulo' => 'reservas'],
            ['nombre' => 'reservar_actividades_grupales', 'descripcion' => 'Reservar actividades grupales o privadas con personalización', 'modulo' => 'reservas'],
            ['nombre' => 'gestionar_reservas_recurrentes', 'descripcion' => 'Gestionar reservas recurrentes o suscripciones a paquetes', 'modulo' => 'reservas'],

            // Pagos y Transacciones
            ['nombre' => 'aplicar_cupones_descuentos', 'descripcion' => 'Aplicar cupones, códigos de descuento o promociones en compras', 'modulo' => 'pagos'],
            ['nombre' => 'procesar_pagos', 'descripcion' => 'Procesar pagos con tarjetas, billeteras digitales o transferencias', 'modulo' => 'pagos'],
            ['nombre' => 'ver_historial_transacciones', 'descripcion' => 'Ver historial de transacciones y recibos', 'modulo' => 'pagos'],
            ['nombre' => 'solicitar_reembolsos', 'descripcion' => 'Solicitar reembolsos o disputas en caso de problemas', 'modulo' => 'pagos'],
            ['nombre' => 'configurar_metodos_pago', 'descripcion' => 'Configurar métodos de pago predeterminados para futuras reservas', 'modulo' => 'pagos'],
            ['nombre' => 'recibir_notificaciones_pago', 'descripcion' => 'Recibir notificaciones de cargos o confirmaciones de pago', 'modulo' => 'pagos'],
            ['nombre' => 'integrar_criptomonedas', 'descripcion' => 'Integrar pagos con criptomonedas o monedas locales', 'modulo' => 'pagos'],
            ['nombre' => 'verificar_saldos_recompensas', 'descripcion' => 'Verificar saldos de recompensas aplicables a pagos', 'modulo' => 'pagos'],

            // Interacción con Empresas y Guías
            ['nombre' => 'ver_perfiles_empresas', 'descripcion' => 'Ver perfil público de las empresas que ofrecen actividades', 'modulo' => 'interaccion'],
            ['nombre' => 'enviar_mensajes_empresas', 'descripcion' => 'Enviar mensajes o consultas previas a la empresa', 'modulo' => 'interaccion'],
            ['nombre' => 'recibir_mensajes_empresas', 'descripcion' => 'Recibir mensajes, recordatorios o actualizaciones de operadores turísticos', 'modulo' => 'interaccion'],
            ['nombre' => 'calificar_servicios', 'descripcion' => 'Calificar el servicio recibido (guía, transporte, atención, experiencia)', 'modulo' => 'interaccion'],
            ['nombre' => 'publicar_resenas', 'descripcion' => 'Publicar reseñas o comentarios visibles para otros usuarios', 'modulo' => 'interaccion'],
            ['nombre' => 'reportar_actividades', 'descripcion' => 'Reportar actividades en caso de inconvenientes o quejas', 'modulo' => 'interaccion'],
            ['nombre' => 'solicitar_asistencia_adicional', 'descripcion' => 'Solicitar asistencia adicional durante la actividad', 'modulo' => 'interaccion'],
            ['nombre' => 'unirse_grupos_foros', 'descripcion' => 'Unirse a grupos o foros de usuarios para compartir experiencias', 'modulo' => 'interaccion'],
            ['nombre' => 'participar_chats_guias', 'descripcion' => 'Participar en chats en vivo con guías antes o durante la actividad', 'modulo' => 'interaccion'],
            ['nombre' => 'acceder_perfiles_guias', 'descripcion' => 'Acceder a perfiles de guías certificados con calificaciones', 'modulo' => 'interaccion'],

            // Soporte y Atención al Usuario
            ['nombre' => 'contactar_soporte', 'descripcion' => 'Contactar al soporte técnico vía chat, correo o teléfono', 'modulo' => 'soporte'],
            ['nombre' => 'enviar_solicitudes_reclamos', 'descripcion' => 'Enviar solicitudes, reclamos o reportes de problemas con adjuntos', 'modulo' => 'soporte'],
            ['nombre' => 'consultar_estado_tickets', 'descripcion' => 'Consultar el estado de sus tickets o reclamos enviados', 'modulo' => 'soporte'],
            ['nombre' => 'recibir_notificaciones_soporte', 'descripcion' => 'Recibir notificaciones del equipo de soporte o administrador', 'modulo' => 'soporte'],
            ['nombre' => 'acceder_base_conocimientos', 'descripcion' => 'Acceder a una base de conocimientos o FAQ integrada', 'modulo' => 'soporte'],
            ['nombre' => 'participar_sesiones_soporte', 'descripcion' => 'Participar en sesiones de soporte en vivo o webinars', 'modulo' => 'soporte'],
            ['nombre' => 'solicitar_asistencia_idiomas', 'descripcion' => 'Solicitar asistencia en múltiples idiomas', 'modulo' => 'soporte'],
            ['nombre' => 'reportar_bugs_sugerencias', 'descripcion' => 'Reportar bugs o sugerencias de mejora en la app', 'modulo' => 'soporte'],

            // Notificaciones y Comunicación
            ['nombre' => 'recibir_notificaciones_push', 'descripcion' => 'Recibir notificaciones push, SMS o correo electrónico', 'modulo' => 'notificaciones'],
            ['nombre' => 'activar_desactivar_notificaciones', 'descripcion' => 'Activar o desactivar notificaciones según preferencia', 'modulo' => 'notificaciones'],
            ['nombre' => 'recibir_alertas_cambios', 'descripcion' => 'Recibir alertas de cambios en reservas, itinerarios o condiciones climáticas', 'modulo' => 'notificaciones'],
            ['nombre' => 'gestionar_suscripciones_newsletter', 'descripcion' => 'Gestionar suscripciones a boletines o newsletters personalizados', 'modulo' => 'notificaciones'],
            ['nombre' => 'configurar_notificaciones_multilingues', 'descripcion' => 'Configurar notificaciones multilingües basadas en configuración', 'modulo' => 'notificaciones'],
            ['nombre' => 'configurar_alertas_ubicacion', 'descripcion' => 'Configurar alertas de ubicación en tiempo real durante viajes', 'modulo' => 'notificaciones'],
            ['nombre' => 'recibir_notificaciones_emergencias', 'descripcion' => 'Recibir notificaciones de eventos locales o emergencias', 'modulo' => 'notificaciones'],

            // Experiencia y Recomendaciones
            ['nombre' => 'ver_sugerencias_personalizadas', 'descripcion' => 'Ver sugerencias personalizadas según ubicación e intereses', 'modulo' => 'experiencia'],
            ['nombre' => 'participar_encuestas', 'descripcion' => 'Participar en encuestas o valoraciones para mejorar la app', 'modulo' => 'experiencia'],
            ['nombre' => 'acceder_promociones_exclusivas', 'descripcion' => 'Acceder a promociones, descuentos especiales o paquetes exclusivos', 'modulo' => 'experiencia'],
            ['nombre' => 'visualizar_rankings_populares', 'descripcion' => 'Visualizar rankings de destinos o actividades más populares', 'modulo' => 'experiencia'],
            ['nombre' => 'recomendar_app', 'descripcion' => 'Recomendar la app a otros usuarios con programa de referidos', 'modulo' => 'experiencia'],
            ['nombre' => 'recibir_itinerarios_ia', 'descripcion' => 'Recibir itinerarios sugeridos basados en IA para viajes personalizados', 'modulo' => 'experiencia'],
            ['nombre' => 'explorar_contenido_educativo', 'descripcion' => 'Explorar contenido educativo sobre destinos (historia, cultura)', 'modulo' => 'experiencia'],
            ['nombre' => 'acceder_contenido_multimedia', 'descripcion' => 'Acceder a playlists o contenido multimedia relacionado con destinos', 'modulo' => 'experiencia'],
            ['nombre' => 'participar_gamificacion', 'descripcion' => 'Participar en desafíos o gamificación para ganar recompensas', 'modulo' => 'experiencia'],

            // Privacidad y Seguridad
            ['nombre' => 'aceptar_politicas_privacidad', 'descripcion' => 'Aceptar políticas de privacidad y términos de uso', 'modulo' => 'privacidad'],
            ['nombre' => 'configurar_visibilidad_perfil', 'descripcion' => 'Configurar la visibilidad de su perfil (público, privado)', 'modulo' => 'privacidad'],
            ['nombre' => 'decidir_visibilidad_resenas', 'descripcion' => 'Decidir si sus reseñas son públicas, anónimas o solo para empresa', 'modulo' => 'privacidad'],
            ['nombre' => 'solicitar_eliminacion_datos', 'descripcion' => 'Solicitar la eliminación total de sus datos personales', 'modulo' => 'privacidad'],
            ['nombre' => 'gestionar_permisos_ubicacion', 'descripcion' => 'Gestionar permisos de ubicación para recomendaciones precisas', 'modulo' => 'privacidad'],
            ['nombre' => 'verificar_auditar_datos', 'descripcion' => 'Verificar y auditar el uso de sus datos por la plataforma', 'modulo' => 'privacidad'],
            ['nombre' => 'activar_autenticacion_dos_factores', 'descripcion' => 'Activar autenticación de dos factores para mayor seguridad', 'modulo' => 'privacidad'],
            ['nombre' => 'configurar_bloqueo_inactividad', 'descripcion' => 'Configurar bloqueo de cuenta por inactividad', 'modulo' => 'privacidad'],
            ['nombre' => 'gestionar_permisos_camara_microfono', 'descripcion' => 'Gestionar permisos de acceso a cámara y micrófono', 'modulo' => 'privacidad'],

            // Reportes Personales
            ['nombre' => 'consultar_historial_completo', 'descripcion' => 'Consultar historial de reservas, gastos y transacciones detalladas', 'modulo' => 'reportes'],
            ['nombre' => 'ver_destinos_mas_visitados', 'descripcion' => 'Ver destinos más visitados, preferidos o estadísticas de uso', 'modulo' => 'reportes'],
            ['nombre' => 'descargar_comprobantes_viajes', 'descripcion' => 'Descargar comprobantes de viajes completados en formatos PDF o CSV', 'modulo' => 'reportes'],
            ['nombre' => 'visualizar_estadisticas_personales', 'descripcion' => 'Visualizar estadísticas personales (número de viajes, reseñas dadas)', 'modulo' => 'reportes'],
            ['nombre' => 'generar_informes_personalizados', 'descripcion' => 'Generar informes personalizados de su actividad turística', 'modulo' => 'reportes'],
            ['nombre' => 'compartir_resumenes_anuales', 'descripcion' => 'Compartir resúmenes anuales de viajes con amigos o redes sociales', 'modulo' => 'reportes'],
            ['nombre' => 'analizar_tendencias_gasto', 'descripcion' => 'Analizar tendencias de gasto y preferencias de viaje', 'modulo' => 'reportes'],
            ['nombre' => 'exportar_datos_finanzas', 'descripcion' => 'Exportar datos para integraciones con apps de finanzas personales', 'modulo' => 'reportes'],
        ];

        // Función helper para insertar permisos sin duplicados
        $insertarPermisos = function($permisos, $rol) {
            foreach ($permisos as $permiso) {
                $permisoExistente = DB::table('permisos')->where('nombre', $permiso['nombre'])->first();

                if (!$permisoExistente) {
                    $permisoId = DB::table('permisos')->insertGetId($permiso);
                } else {
                    $permisoId = $permisoExistente->id;
                }

                // Verificar si ya existe la relación rol-permiso
                $existeRelacion = DB::table('roles_permisos')
                    ->where('rol', $rol)
                    ->where('permiso_id', $permisoId)
                    ->exists();

                if (!$existeRelacion) {
                    DB::table('roles_permisos')->insert([
                        'rol' => $rol,
                        'permiso_id' => $permisoId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        };

        // Insertar permisos para cada rol
        $insertarPermisos($permisos_admin, 'administrador');
        $insertarPermisos($permisos_empresa, 'empresa_viajes');
        $insertarPermisos($permisos_turista, 'turista');
    }
}
