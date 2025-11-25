<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>World Travels - Descubre Boyacá</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Header / Barra de navegación pública -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">WORLD TRAVELS</h1>
            <nav class="hidden md:flex space-x-6">
                <a href="#inicio" class="text-gray-700 hover:text-blue-600 transition">Inicio</a>
                <a href="#sobre-nosotros" class="text-gray-700 hover:text-blue-600 transition">Sobre Nosotros</a>
                <a href="#actividades" class="text-gray-700 hover:text-blue-600 transition">Actividades</a>
                <a href="#contacto" class="text-gray-700 hover:text-blue-600 transition">Contacto</a>
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Registrarse</a>
            </nav>
            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button class="text-gray-700 hover:text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Banner principal o imagen hero -->
    <section id="inicio" class="relative bg-gradient-to-r from-blue-600 to-blue-800 text-white py-32">
        <div class="absolute inset-0 bg-black opacity-40"></div>
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');"></div>
        <div class="relative container mx-auto px-4 text-center">
            <h1 class="text-6xl font-bold mb-6">Descubre Boyacá</h1>
            <p class="text-xl mb-8 max-w-3xl mx-auto">Explora la riqueza histórica, cultural y natural de Boyacá. Desde pueblos coloniales hasta aventuras en la naturaleza, encuentra tu próximo destino inolvidable.</p>
            <div class="space-x-4">
                <a href="#actividades" class="bg-white text-blue-600 px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition duration-300 text-lg">Explorar Destinos</a>
                <a href="{{ route('register') }}" class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-blue-600 transition duration-300 text-lg">Comenzar Ahora</a>
            </div>
        </div>
    </section>

    <!-- Descripción de la plataforma -->
    <section id="sobre-nosotros" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-bold mb-8 text-gray-800">¿Qué es World Travels?</h2>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    World Travels es la plataforma líder para descubrir y reservar experiencias turísticas en Boyacá, Colombia.
                    Conectamos a viajeros apasionados con empresas locales que ofrecen actividades únicas, desde tours culturales
                    hasta aventuras en la naturaleza, todo en un solo lugar.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                    <div class="text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Para Turistas</h3>
                        <p class="text-gray-600">Descubre experiencias únicas y reserva actividades con facilidad.</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Para Empresas</h3>
                        <p class="text-gray-600">Promociona tus servicios turísticos y llega a más clientes.</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Para la Comunidad</h3>
                        <p class="text-gray-600">Fomentamos el turismo sostenible y el desarrollo local.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categorías -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12 text-gray-800">Categorías de Actividades</h2>
            <div id="categories-section" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Categorías se cargarán aquí -->
            </div>
        </div>
    </section>

    <!-- Actividades Turísticas -->
    <section id="actividades" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-8 text-gray-800">Actividades Turísticas</h2>
                <div id="actividades-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Actividades se cargarán aquí con JavaScript -->
                </div>
                <div class="mt-8">
                    <a href="{{ route('search') }}" class="bg-blue-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-blue-700 transition duration-300">Ver Todas las Actividades</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Opiniones / testimonios -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12 text-gray-800">Lo que dicen nuestros viajeros</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-6 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-4">M</div>
                        <div>
                            <h4 class="font-semibold">María González</h4>
                            <div class="flex text-yellow-400">
                                ★★★★★
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Villa de Leyva superó todas mis expectativas. La plataforma de World Travels hizo que fuera muy fácil encontrar y reservar actividades. ¡Definitivamente volveré!"</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center text-white font-bold mr-4">C</div>
                        <div>
                            <h4 class="font-semibold">Carlos Rodríguez</h4>
                            <div class="flex text-yellow-400">
                                ★★★★★
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Las aguas termales de Sogamoso fueron perfectas para relajarme. Gracias a World Travels pude descubrir este lugar increíble que no conocía."</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold mr-4">A</div>
                        <div>
                            <h4 class="font-semibold">Ana López</h4>
                            <div class="flex text-yellow-400">
                                ★★★★★
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"La historia de Tunja me fascinó. World Travels me ayudó a organizar un viaje perfecto con guías locales expertos."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Cómo funciona -->
    <section class="py-20 bg-blue-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12 text-gray-800">¿Cómo funciona?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-blue-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">1</div>
                    <h3 class="text-2xl font-bold mb-4">Regístrate</h3>
                    <p class="text-gray-600">Crea tu cuenta gratuita y comienza a explorar todas las opciones disponibles en Boyacá.</p>
                </div>
                <div class="text-center">
                    <div class="bg-blue-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">2</div>
                    <h3 class="text-2xl font-bold mb-4">Elige tu aventura</h3>
                    <p class="text-gray-600">Navega por nuestros destinos destacados y actividades, lee reseñas y compara opciones.</p>
                </div>
                <div class="text-center">
                    <div class="bg-blue-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">3</div>
                    <h3 class="text-2xl font-bold mb-4">Reserva y disfruta</h3>
                    <p class="text-gray-600">Realiza tu reserva de forma segura y prepárate para vivir una experiencia inolvidable.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Beneficios -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12 text-gray-800">¿Por qué elegir World Travels?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Reservas Seguras</h3>
                    <p class="text-gray-600">Pago seguro y confirmación inmediata de tus reservas.</p>
                </div>
                <div class="text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Guías Locales</h3>
                    <p class="text-gray-600">Experiencias auténticas con expertos locales.</p>
                </div>
                <div class="text-center">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Precios Competitivos</h3>
                    <p class="text-gray-600">Las mejores ofertas directamente de proveedores locales.</p>
                </div>
                <div class="text-center">
                    <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Soporte 24/7</h3>
                    <p class="text-gray-600">Atención al cliente disponible en todo momento.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Galería de imágenes -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12 text-gray-800">Descubre la magia de Boyacá</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="relative overflow-hidden rounded-xl group">
                    <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Naturaleza Boyacá" class="w-full h-64 object-cover group-hover:scale-110 transition duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                        <h3 class="text-white text-xl font-bold">Naturaleza</h3>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-xl group">
                    <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Arquitectura colonial" class="w-full h-64 object-cover group-hover:scale-110 transition duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                        <h3 class="text-white text-xl font-bold">Historia</h3>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-xl group">
                    <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Gastronomía" class="w-full h-64 object-cover group-hover:scale-110 transition duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                        <h3 class="text-white text-xl font-bold">Gastronomía</h3>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-xl group">
                    <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Aventura" class="w-full h-64 object-cover group-hover:scale-110 transition duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                        <h3 class="text-white text-xl font-bold">Aventura</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Preguntas frecuentes (FAQ) -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12 text-gray-800">Preguntas Frecuentes</h2>
            <div class="max-w-3xl mx-auto space-y-6">
                <div class="border border-gray-200 rounded-lg">
                    <button class="w-full text-left p-6 focus:outline-none focus:bg-gray-50" onclick="toggleFAQ(this)">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800">¿Cómo puedo reservar una actividad?</h3>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <div class="mt-4 text-gray-600 hidden">
                            Para reservar una actividad, primero debes registrarte en nuestra plataforma. Una vez registrado, puedes navegar por las actividades disponibles, seleccionar la que te interesa y seguir el proceso de reserva. Recibirás una confirmación inmediata por email.
                        </div>
                    </button>
                </div>
                <div class="border border-gray-200 rounded-lg">
                    <button class="w-full text-left p-6 focus:outline-none focus:bg-gray-50" onclick="toggleFAQ(this)">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800">¿Las reservas son reembolsables?</h3>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <div class="mt-4 text-gray-600 hidden">
                            Las políticas de reembolso varían según la actividad y el proveedor. Generalmente, puedes cancelar hasta 48 horas antes de la actividad para obtener un reembolso completo. Te recomendamos revisar las condiciones específicas de cada actividad antes de reservar.
                        </div>
                    </button>
                </div>
                <div class="border border-gray-200 rounded-lg">
                    <button class="w-full text-left p-6 focus:outline-none focus:bg-gray-50" onclick="toggleFAQ(this)">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800">¿Necesito guía para las actividades?</h3>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <div class="mt-4 text-gray-600 hidden">
                            Muchas de nuestras actividades incluyen guías locales expertos que conocen perfectamente la zona y pueden proporcionarte una experiencia más enriquecedora. Sin embargo, algunas actividades permiten explorar de forma independiente. Esta información se detalla en la descripción de cada actividad.
                        </div>
                    </button>
                </div>
                <div class="border border-gray-200 rounded-lg">
                    <button class="w-full text-left p-6 focus:outline-none focus:bg-gray-50" onclick="toggleFAQ(this)">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800">¿Qué documentos necesito para viajar a Boyacá?</h3>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <div class="mt-4 text-gray-600 hidden">
                            Para ciudadanos colombianos, solo necesitas tu cédula de ciudadanía. Si eres extranjero, necesitarás tu pasaporte válido y, en algunos casos, visa dependiendo de tu nacionalidad. Te recomendamos verificar los requisitos específicos según tu situación.
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Pie de página (footer) -->
    <footer id="contacto" class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">WORLD TRAVELS</h3>
                    <p class="text-gray-400">Descubre la magia de Boyacá, un departamento lleno de historia, cultura y naturaleza que te espera con los brazos abiertos.</p>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white transition">Facebook</a>
                        <a href="#" class="text-gray-400 hover:text-white transition">Instagram</a>
                        <a href="#" class="text-gray-400 hover:text-white transition">Twitter</a>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Enlaces Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="#inicio" class="text-gray-400 hover:text-white transition">Inicio</a></li>
                        <li><a href="#sobre-nosotros" class="text-gray-400 hover:text-white transition">Sobre Nosotros</a></li>
                        <li><a href="#actividades" class="text-gray-400 hover:text-white transition">Actividades</a></li>
                        <li><a href="{{ route('search') }}" class="text-gray-400 hover:text-white transition">Buscar</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Soporte</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Centro de Ayuda</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Política de Privacidad</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Términos de Servicio</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Contacto</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contacto</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>📧 info@worldtravels.co</li>
                        <li>📞 +57 300 123 4567</li>
                        <li>📍 Tunja, Boyacá, Colombia</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p>&copy; 2025 World Travels. Todos los derechos reservados. Hecho con ❤️ para Boyacá.</p>
            </div>
        </div>
    </footer>

    @php
        $isAuthenticated = auth()->check();
    @endphp

    <!-- Modal de Reserva -->
    <div id="reservation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" id="modal-title">Reservar Actividad</h3>
                <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800 mb-2"><strong>Detalles de la actividad:</strong></p>
                    <div id="activity-details" class="text-sm text-blue-700 space-y-1">
                        <!-- Los detalles se cargarán dinámicamente -->
                    </div>
                </div>
                <form id="reservation-form">
                    <input type="hidden" id="activity-id" name="idActividad">
                    <input type="hidden" id="user-id" name="idUsuario">
                    <input type="hidden" name="Estado" value="pendiente">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Número de Personas</label>
                        <input type="number" id="numero-personas" name="Numero_Personas" min="1" max="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <p class="text-xs text-gray-500 mt-1">Máximo 10 personas por reserva</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="button" onclick="closeReservationModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancelar</button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Reservar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ==================== UTILIDADES ====================
        function showLoading(element, message = 'Cargando...') {
            element.innerHTML = `<p class="col-span-full text-center text-gray-500 text-lg">${message}</p>`;
        }

        function showError(element, message = 'Error al cargar') {
            element.innerHTML = `<p class="col-span-full text-center text-red-500 text-lg">${message}</p>`;
        }

        // ==================== INTERFAZ DE USUARIO ====================
        function toggleFAQ(button) {
            const content = button.querySelector('.mt-4');
            const icon = button.querySelector('svg');

            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        // ==================== CATEGORÍAS ====================
        function loadCategories() {
            const categoriesSection = document.getElementById('categories-section');
            showLoading(categoriesSection, 'Cargando categorías...');

            fetch('http://127.0.0.1:8000/api/categories/active')
                .then(response => response.json())
                .then(data => {
                    categoriesSection.innerHTML = '';

                    if (data.length === 0) {
                        categoriesSection.innerHTML = '<p class="col-span-full text-center text-gray-500">No hay categorías disponibles</p>';
                        return;
                    }

                    data.forEach(category => {
                        const categoryDiv = createCategoryCard(category);
                        categoriesSection.appendChild(categoryDiv);
                    });
                })
                .catch(error => {
                    console.error('Error cargando categorías:', error);
                    showError(categoriesSection, 'Error al cargar categorías');
                });
        }

        function createCategoryCard(category) {
            const div = document.createElement('div');
            div.className = 'bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300 cursor-pointer group';
            div.onclick = () => filterByCategory(category.id);

            div.innerHTML = `
                <div class="relative h-48 overflow-hidden">
                    ${category.imagen
                        ? `<img src="${category.imagen}" alt="${category.nombre}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">`
                        : '<div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center"><span class="text-white text-4xl">📁</span></div>'
                    }
                    <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                        <span class="text-white font-semibold text-lg">Explorar</span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2 text-gray-800">${category.nombre}</h3>
                    ${category.descripcion
                        ? `<p class="text-gray-600 text-sm leading-relaxed">${category.descripcion}</p>`
                        : '<p class="text-gray-500 text-sm italic">Descubre actividades únicas en esta categoría</p>'
                    }
                </div>
            `;

            return div;
        }

        // ==================== GESTIÓN DE ACTIVIDADES ====================

        function filterByCategory(categoryId) {
            const actividadesList = document.getElementById('actividades-list');
            showLoading(actividadesList, 'Cargando actividades filtradas...');

            fetch(`http://127.0.0.1:8000/api/listarActividades?categoria=${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    renderActivities(data, actividadesList);
                })
                .catch(error => {
                    console.error('Error cargando actividades filtradas:', error);
                    showError(actividadesList, 'Error al cargar actividades');
                });
        }

        // ==================== ACTIVIDADES ====================
        function renderActivities(data, container) {
            container.innerHTML = '';

            if (data.length === 0) {
                container.innerHTML = '<p class="col-span-full text-center text-gray-500">No hay actividades disponibles</p>';
                return;
            }

            data.forEach(actividad => {
                const card = createActivityCard(actividad);
                container.appendChild(card);
            });
        }

        function createActivityCard(actividad) {
            const div = document.createElement('div');
            div.className = 'bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300 group';

            div.innerHTML = `
                <div class="relative overflow-hidden rounded-t-xl">
                    <img src="${actividad.Imagen || 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'}"
                         alt="${actividad.Nombre_Actividad}"
                         class="w-full h-56 object-cover transition-transform duration-300 group-hover:scale-110"
                         onerror="this.src='https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'">
                    <div class="absolute top-4 right-4 bg-white bg-opacity-90 backdrop-blur-sm rounded-full px-3 py-1 shadow-lg">
                        <span class="text-sm font-bold text-gray-800">$${actividad.Precio}</span>
                    </div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <div class="bg-gradient-to-t from-black to-transparent rounded-lg p-4">
                            <h3 class="text-xl font-bold text-white mb-1 line-clamp-2">${actividad.Nombre_Actividad}</h3>
                            <div class="flex items-center text-white text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                ${actividad.Ubicacion}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-white">
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">${actividad.Descripcion}</p>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <div class="font-medium">${new Date(actividad.Fecha_Actividad).toLocaleDateString('es-ES')}</div>
                                <div class="text-xs">${actividad.Hora_Actividad.substring(0, 5)}</div>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <div>
                                <div class="font-medium">Máx. ${actividad.Cupo_Maximo}</div>
                                <div class="text-xs">personas</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 mr-2">
                                ★★★★☆
                            </div>
                            <span class="text-sm text-gray-500">(4.2)</span>
                        </div>
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">4-6 horas</span>
                    </div>

                    <button onclick="viewReviews(${actividad.id})" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Ver Detalles & Reseñas
                    </button>
                </div>
            `;

            return div;
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadCategories();
            const actividadesList = document.getElementById('actividades-list');

            // Manejar envío del formulario de reserva
            document.getElementById('reservation-form').addEventListener('submit', function(e) {
                e.preventDefault();
                makeReservation();
            });

            // Mostrar mensaje de carga
            actividadesList.innerHTML = '<p class="col-span-full text-center text-gray-500 text-lg">Cargando actividades...</p>';

            fetch('http://127.0.0.1:8000/api/listarActividades')
                .then(response => response.json())
                .then(data => {
                    actividadesList.innerHTML = ''; // Limpiar mensaje de carga

                    if (data.length === 0) {
                        // Datos de ejemplo si no hay actividades
                        const actividadesEjemplo = [
                            {
                                nombre_actividad: 'Visita al Museo Casa del Fundador',
                                descripcion: 'Explora la historia de Colombia en este museo ubicado en el corazón de Tunja.',
                                precio: 15000,
                                ubicacion: 'Tunja, Boyacá',
                                imagen: 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
                            },
                            {
                                nombre_actividad: 'Paseo por Villa de Leyva',
                                descripcion: 'Recorre las calles empedradas y plazas coloniales de este pueblo mágico.',
                                precio: 25000,
                                ubicacion: 'Villa de Leyva, Boyacá',
                                imagen: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
                            },
                            {
                                nombre_actividad: 'Baños Termales en Sogamoso',
                                descripcion: 'Relájate en las aguas medicinales de los termales de Sogamoso.',
                                precio: 30000,
                                ubicacion: 'Sogamoso, Boyacá',
                                imagen: 'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
                            }
                        ];

                        actividadesEjemplo.forEach((actividad, index) => {
                            const div = document.createElement('div');
                            div.className = 'bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300';
                            div.innerHTML = `
                                <img src="${actividad.imagen}" alt="${actividad.nombre_actividad}" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-2xl font-bold mb-2 text-gray-800">${actividad.nombre_actividad}</h3>
                                    <p class="text-gray-600 mb-4">${actividad.descripcion}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-2xl font-bold text-blue-600">$${actividad.precio}</span>
                                        <span class="text-sm text-gray-500">${actividad.ubicacion}</span>
                                    </div>
                                    <div class="mt-4 space-y-2">
                                        <button onclick="viewReviews(${index + 1})" class="w-full bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700 transition duration-300">Ver Reseñas</button>
                                    </div>
                                </div>
                            `;
                            actividadesList.appendChild(div);
                        });
                        return;
                    }

                    // Mostrar actividades reales
                    data.forEach(actividad => {
                        const div = document.createElement('div');
                        div.className = 'bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300 group';
                        div.innerHTML = `
                            <div class="relative overflow-hidden rounded-t-xl">
                                <img src="${actividad.Imagen || 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'}" alt="${actividad.Nombre_Actividad}" class="w-full h-56 object-cover transition-transform duration-300 group-hover:scale-110" onerror="this.src='https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'">
                                <div class="absolute top-4 right-4 bg-white bg-opacity-90 backdrop-blur-sm rounded-full px-3 py-1 shadow-lg">
                                    <span class="text-sm font-bold text-gray-800">$${actividad.Precio}</span>
                                </div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <div class="bg-gradient-to-t from-black to-transparent rounded-lg p-4">
                                        <h3 class="text-xl font-bold text-white mb-1 line-clamp-2">${actividad.Nombre_Actividad}</h3>
                                        <div class="flex items-center text-white text-sm">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            ${actividad.Ubicacion}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6 bg-white">
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">${actividad.Descripcion}</p>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <div class="font-medium">${new Date(actividad.Fecha_Actividad).toLocaleDateString('es-ES')}</div>
                                            <div class="text-xs">${actividad.Hora_Actividad.substring(0, 5)}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                        <div>
                                            <div class="font-medium">Máx. ${actividad.Cupo_Maximo}</div>
                                            <div class="text-xs">personas</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400 mr-2">
                                            ★★★★☆
                                        </div>
                                        <span class="text-sm text-gray-500">(4.2)</span>
                                    </div>
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">4-6 horas</span>
                                </div>

                                <button onclick="viewReviews(${actividad.id})" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    Ver Detalles & Reseñas
                                </button>
                            </div>
                        `;
                        actividadesList.appendChild(div);
                    });
                })
                .catch(error => {
                    console.error('Error cargando actividades:', error);
                    actividadesList.innerHTML = '<p class="col-span-full text-center text-red-500 text-lg">Error al cargar las actividades. Mostrando actividades de ejemplo...</p>';

                    // Mostrar actividades de ejemplo en caso de error
                    const actividadesEjemplo = [
                        {
                            nombre_actividad: 'Visita al Museo Casa del Fundador',
                            descripcion: 'Explora la historia de Colombia en este museo ubicado en el corazón de Tunja.',
                            precio: 15000,
                            ubicacion: 'Tunja, Boyacá',
                            imagen: 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
                        },
                        {
                            nombre_actividad: 'Paseo por Villa de Leyva',
                            descripcion: 'Recorre las calles empedradas y plazas coloniales de este pueblo mágico.',
                            precio: 25000,
                            ubicacion: 'Villa de Leyva, Boyacá',
                            imagen: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
                        },
                        {
                            nombre_actividad: 'Baños Termales en Sogamoso',
                            descripcion: 'Relájate en las aguas medicinales de los termales de Sogamoso.',
                            precio: 30000,
                            ubicacion: 'Sogamoso, Boyacá',
                            imagen: 'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
                        }
                    ];

                    setTimeout(() => {
                        actividadesList.innerHTML = '';
                        actividadesEjemplo.forEach((actividad, index) => {
                            const div = document.createElement('div');
                            div.className = 'bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300';
                            div.innerHTML = `
                                <img src="${actividad.imagen}" alt="${actividad.nombre_actividad}" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-2xl font-bold mb-2 text-gray-800">${actividad.nombre_actividad}</h3>
                                    <p class="text-gray-600 mb-4">${actividad.descripcion}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-2xl font-bold text-blue-600">$${actividad.precio}</span>
                                        <span class="text-sm text-gray-500">${actividad.ubicacion}</span>
                                    </div>
                                    <div class="mt-4 space-y-2">
                                        @if($isAuthenticated)
                                            <button onclick="openReservationModal(${index + 1}, '${actividad.nombre_actividad}')" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300">Reservar Ahora</button>
                                        @else
                                            <button onclick="redirectToLogin()" class="w-full bg-yellow-600 text-white py-2 rounded-lg hover:bg-yellow-700 transition duration-300">Inicia Sesión para Reservar</button>
                                        @endif
                                        <button onclick="viewReviews(${index + 1})" class="w-full bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700 transition duration-300">Ver Reseñas</button>
                                    </div>
                                </div>
                            `;
                            actividadesList.appendChild(div);
                        });
                    }, 1000);
                });
        });

        function openReservationModal(activityId, activityName) {
            // Verificar si hay token de autenticación
            const token = localStorage.getItem('token');
            if (token) {
                // Obtener información del usuario y detalles de la actividad
                Promise.all([
                    fetch('http://127.0.0.1:8000/api/me', {
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    }),
                    fetch(`http://127.0.0.1:8000/api/actividades/${activityId}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                ])
                .then(([userResponse, activityResponse]) => Promise.all([userResponse.json(), activityResponse.json()]))
                .then(([userData, activityData]) => {
                    if (userData.id && activityData.id) {
                        document.getElementById('activity-id').value = activityId;
                        document.getElementById('user-id').value = userData.id;
                        document.getElementById('modal-title').textContent = `Reservar: ${activityName}`;

                        // Mostrar detalles de la actividad
                        const activityDetails = document.getElementById('activity-details');
                        const fecha = new Date(activityData.Fecha_Actividad).toLocaleDateString('es-ES');
                        const hora = activityData.Hora_Actividad.substring(0, 5);

                        activityDetails.innerHTML = `
                            <div><strong>📅 Fecha:</strong> ${fecha}</div>
                            <div><strong>🕐 Hora:</strong> ${hora}</div>
                            <div><strong>📍 Lugar:</strong> ${activityData.Ubicacion}</div>
                            <div><strong>💰 Precio:</strong> $${activityData.Precio} por persona</div>
                            <div><strong>👥 Cupo máximo:</strong> ${activityData.Cupo_Maximo} personas</div>
                        `;

                        document.getElementById('reservation-modal').classList.remove('hidden');
                    } else {
                        alert('Error al obtener información del usuario o actividad.');
                        window.location.href = '{{ route("login") }}';
                    }
                })
                .catch(error => {
                    console.error('Error obteniendo datos:', error);
                    alert('Error de conexión. Inténtalo nuevamente.');
                });
            } else {
                // Mostrar mensaje de notificación mejorado
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-yellow-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-sm';
                notification.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold">Inicio de sesión requerido</p>
                            <p class="text-sm">Debes iniciar sesión para reservar actividades. Redirigiendo...</p>
                        </div>
                    </div>
                `;
                document.body.appendChild(notification);

                // Redirigir después de 3 segundos
                setTimeout(() => {
                    window.location.href = '{{ route("login") }}';
                }, 3000);

                // Remover notificación después de redirección
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 3500);
            }
        }

        function closeReservationModal() {
            document.getElementById('reservation-modal').classList.add('hidden');
        }

        function redirectToLogin() {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-sm';
            notification.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    <div>
                        <p class="font-semibold">Redirigiendo al login</p>
                        <p class="text-sm">Inicia sesión para acceder a todas las funcionalidades.</p>
                    </div>
                </div>
            `;
            document.body.appendChild(notification);

            setTimeout(() => {
                window.location.href = '{{ route("login") }}';
            }, 2000);

            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 2500);
        }

        function viewReviews(activityId) {
            // Mostrar reseñas de ejemplo por ahora
            const reviewsText = `Reseñas para la actividad ${activityId}:\n\n` +
                '⭐⭐⭐⭐⭐ María González: "Excelente experiencia, muy recomendado!"\n' +
                '⭐⭐⭐⭐☆ Carlos Rodríguez: "Buen lugar, pero un poco caro"\n' +
                '⭐⭐⭐⭐⭐ Ana López: "Volveré definitivamente"\n\n' +
                'Funcionalidad completa próximamente con reseñas reales.';
            alert(reviewsText);
        }

        function makeReservation() {
            const form = document.getElementById('reservation-form');
            const formData = new FormData(form);

            // Convertir FormData a objeto - solo campos necesarios
            const data = {
                idUsuario: parseInt(formData.get('idUsuario')),
                idActividad: parseInt(formData.get('idActividad')),
                Numero_Personas: parseInt(formData.get('Numero_Personas')),
                Estado: formData.get('Estado')
            };

            console.log('Enviando datos de reserva:', data);

            fetch('http://127.0.0.1:8000/api/crearReservas', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                console.log('Respuesta del servidor:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Datos de respuesta:', data);
                if (data.id) {
                    alert('¡Reserva creada exitosamente! Revisa tu email para la confirmación.');
                    closeReservationModal();
                    // Limpiar formulario
                    document.getElementById('reservation-form').reset();
                } else {
                    // Mostrar errores de validación específicos
                    if (data.errors) {
                        let errorMessage = 'Errores de validación:\n';
                        for (let field in data.errors) {
                            errorMessage += `${field}: ${data.errors[field].join(', ')}\n`;
                        }
                        alert(errorMessage);
                    } else {
                        alert('Error: ' + (data.message || 'Error desconocido'));
                    }
                }
            })
            .catch(error => {
                console.error('Error creando reserva:', error);
                alert('Error de conexión. Inténtalo nuevamente.');
            });
        }
    </script>
</body>
</html>
