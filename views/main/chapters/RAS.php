<?php
/**
 * Vista: Capítulos RAS (Robotics and Automation Society)
 * Ruta: views/main/chapters/RAS.php
 * 
 * Descripción:
 * Página que muestra información sobre los capítulos de la Rama IEEE Unicauca
 * dentro de la Robotics and Automation Society (RAS).
 * 
 * Variables esperadas del controlador:
 * - $chapters: array con información de los capítulos
 * - $title: título de la página
 * - $metaTitle: título meta para SEO
 * - $descriptionSite: descripción meta para SEO
 */
?>

<main id="ras-chapters-main" aria-labelledby="ras-main-title">
    
    <!-- Hero Section -->
    <section id="ras-hero" class="ras-hero" aria-label="Sección introductoria de capítulos RAS">
        <div class="ras-hero__content">
            <div class="ras-hero__text">
                <h1 id="ras-main-title">Capítulos RAS</h1>
                <p class="ras-hero__subtitle">
                    Robotics and Automation Society - Rama IEEE Unicauca
                </p>
                <p class="ras-hero__description">
                    Descubre los capítulos especializados de la 
                    <abbr title="Robotics and Automation Society">RAS</abbr> 
                    que impulsan la innovación en robótica y automatización en nuestra región.
                </p>
            </div>
            <figure class="ras-hero__image" aria-label="Imagen representativa de RAS">
                <picture>
                    <source srcset="/build/img/pages/ras/ras-hero.webp" type="image/webp">
                    <img 
                        loading="lazy" 
                        width="400" 
                        height="300" 
                        src="/build/img/pages/ras/ras-hero.jpg" 
                        alt="Representación visual de la Robotics and Automation Society"
                    >
                </picture>
            </figure>
        </div>
    </section>

    <!-- Información sobre RAS -->
    <section id="ras-info" class="ras-info" aria-labelledby="ras-info-title">
        <div class="container">
            <h2 id="ras-info-title">¿Qué es RAS?</h2>
            
            <div class="ras-info__grid">
                <article class="ras-info__card">
                    <div class="ras-info__icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="48" height="48">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
                        </svg>
                    </div>
                    <h3>Liderazgo en Robótica</h3>
                    <p>
                        La mayor organización internacional dedicada al avance de la robótica 
                        y la automatización, con presencia global y reconocimiento académico.
                    </p>
                </article>

                <article class="ras-info__card">
                    <div class="ras-info__icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="48" height="48">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <h3>Innovación y Desarrollo</h3>
                    <p>
                        Promueve investigación de calidad en áreas como robótica móvil, 
                        manipuladores y sistemas autónomos.
                    </p>
                </article>

                <article class="ras-info__card">
                    <div class="ras-info__icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="48" height="48">
                            <path d="M17 10.5V7c0 .55-.45 1-1 1H4c-.55 0-1-.45-1-1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
                        </svg>
                    </div>
                    <h3>Redes de Colaboración</h3>
                    <p>
                        Conecta profesionales, académicos y estudiantes para compartir 
                        conocimiento e impulsar proyectos colaborativos.
                    </p>
                </article>
            </div>
        </div>
    </section>

    <!-- Capítulos Técnicos -->
    <section id="ras-chapters" class="ras-chapters" aria-labelledby="chapters-title">
        <div class="container">
            <h2 id="chapters-title">Nuestros Capítulos Especializados</h2>
            <p class="ras-chapters__intro">
                Conoce los equipos y áreas de especialización que conforman la RAS en Unicauca:
            </p>

            <div class="ras-chapters__grid">
                
                <!-- Capítulo 1: Robótica Móvil -->
                <article class="ras-chapter-card" id="chapter-mobile-robotics">
                    <div class="ras-chapter-card__header">
                        <h3>Robótica Móvil</h3>
                        <span class="ras-chapter-card__badge" aria-label="Categoría">Especialidad</span>
                    </div>
                    
                    <div class="ras-chapter-card__content">
                        <p class="ras-chapter-card__description">
                            Especializado en el diseño, control y navegación de robots móviles 
                            autónomos en diversos entornos.
                        </p>
                        
                        <div class="ras-chapter-card__focus">
                            <h4>Áreas de Enfoque:</h4>
                            <ul>
                                <li>Navegación autónoma</li>
                                <li>Visión por computadora</li>
                                <li>Sistemas SLAM (Localización y Mapeo)</li>
                                <li>Inteligencia Artificial aplicada</li>
                            </ul>
                        </div>

                        <div class="ras-chapter-card__stats">
                            <div class="stat">
                                <span class="stat__number">15+</span>
                                <span class="stat__label">Miembros activos</span>
                            </div>
                            <div class="stat">
                                <span class="stat__number">8</span>
                                <span class="stat__label">Proyectos activos</span>
                            </div>
                        </div>
                    </div>

                    <footer class="ras-chapter-card__footer">
                        <a href="/ras/capitulos/robotica-movil" class="btn btn--primary btn--sm" 
                           aria-label="Más información sobre Robótica Móvil">
                            Conocer más
                        </a>
                        <a href="mailto:ras-mobile@ieee.org" class="btn btn--outline btn--sm"
                           aria-label="Contactar al capítulo de Robótica Móvil">
                            Contactar
                        </a>
                    </footer>
                </article>

                <!-- Capítulo 2: Manipuladores y Brazos Robóticos -->
                <article class="ras-chapter-card" id="chapter-manipulators">
                    <div class="ras-chapter-card__header">
                        <h3>Manipuladores Robóticos</h3>
                        <span class="ras-chapter-card__badge" aria-label="Categoría">Especialidad</span>
                    </div>
                    
                    <div class="ras-chapter-card__content">
                        <p class="ras-chapter-card__description">
                            Enfocado en el desarrollo de brazos robóticos, control de movimiento 
                            y aplicaciones industriales.
                        </p>
                        
                        <div class="ras-chapter-card__focus">
                            <h4>Áreas de Enfoque:</h4>
                            <ul>
                                <li>Cinemática y dinámica</li>
                                <li>Control de trayectorias</li>
                                <li>Sensores y retroalimentación</li>
                                <li>Aplicaciones de manufactura</li>
                            </ul>
                        </div>

                        <div class="ras-chapter-card__stats">
                            <div class="stat">
                                <span class="stat__number">12+</span>
                                <span class="stat__label">Miembros activos</span>
                            </div>
                            <div class="stat">
                                <span class="stat__number">5</span>
                                <span class="stat__label">Prototipos</span>
                            </div>
                        </div>
                    </div>

                    <footer class="ras-chapter-card__footer">
                        <a href="/ras/capitulos/manipuladores" class="btn btn--primary btn--sm"
                           aria-label="Más información sobre Manipuladores Robóticos">
                            Conocer más
                        </a>
                        <a href="mailto:ras-manipulators@ieee.org" class="btn btn--outline btn--sm"
                           aria-label="Contactar al capítulo de Manipuladores">
                            Contactar
                        </a>
                    </footer>
                </article>

                <!-- Capítulo 3: Sistemas Autónomos y Control -->
                <article class="ras-chapter-card" id="chapter-autonomous-systems">
                    <div class="ras-chapter-card__header">
                        <h3>Sistemas Autónomos</h3>
                        <span class="ras-chapter-card__badge" aria-label="Categoría">Especialidad</span>
                    </div>
                    
                    <div class="ras-chapter-card__content">
                        <p class="ras-chapter-card__description">
                            Desarrollo de sistemas que toman decisiones independientes mediante 
                            algoritmos avanzados e IA.
                        </p>
                        
                        <div class="ras-chapter-card__focus">
                            <h4>Áreas de Enfoque:</h4>
                            <ul>
                                <li>Control automático</li>
                                <li>Machine Learning</li>
                                <li>Toma de decisiones autónoma</li>
                                <li>Sistemas Multi-agente</li>
                            </ul>
                        </div>

                        <div class="ras-chapter-card__stats">
                            <div class="stat">
                                <span class="stat__number">18+</span>
                                <span class="stat__label">Miembros activos</span>
                            </div>
                            <div class="stat">
                                <span class="stat__number">10</span>
                                <span class="stat__label">Publicaciones</span>
                            </div>
                        </div>
                    </div>

                    <footer class="ras-chapter-card__footer">
                        <a href="/ras/capitulos/sistemas-autonomos" class="btn btn--primary btn--sm"
                           aria-label="Más información sobre Sistemas Autónomos">
                            Conocer más
                        </a>
                        <a href="mailto:ras-autonomous@ieee.org" class="btn btn--outline btn--sm"
                           aria-label="Contactar al capítulo de Sistemas Autónomos">
                            Contactar
                        </a>
                    </footer>
                </article>

                <!-- Capítulo 4: Robótica Humanoides -->
                <article class="ras-chapter-card" id="chapter-humanoid-robotics">
                    <div class="ras-chapter-card__header">
                        <h3>Robótica Humanoides</h3>
                        <span class="ras-chapter-card__badge" aria-label="Categoría">Especialidad</span>
                    </div>
                    
                    <div class="ras-chapter-card__content">
                        <p class="ras-chapter-card__description">
                            Investigación en robots con morfología similar a la humana y 
                            capacidades de interacción.
                        </p>
                        
                        <div class="ras-chapter-card__focus">
                            <h4>Áreas de Enfoque:</h4>
                            <ul>
                                <li>Locomoción bípeda</li>
                                <li>Interacción humano-robot</li>
                                <li>Reconocimiento de gestos</li>
                                <li>Aplicaciones sociales</li>
                            </ul>
                        </div>

                        <div class="ras-chapter-card__stats">
                            <div class="stat">
                                <span class="stat__number">10+</span>
                                <span class="stat__label">Miembros activos</span>
                            </div>
                            <div class="stat">
                                <span class="stat__number">3</span>
                                <span class="stat__label">Prototipos humanoides</span>
                            </div>
                        </div>
                    </div>

                    <footer class="ras-chapter-card__footer">
                        <a href="/ras/capitulos/humanoides" class="btn btn--primary btn--sm"
                           aria-label="Más información sobre Robótica Humanoides">
                            Conocer más
                        </a>
                        <a href="mailto:ras-humanoid@ieee.org" class="btn btn--outline btn--sm"
                           aria-label="Contactar al capítulo de Robótica Humanoides">
                            Contactar
                        </a>
                    </footer>
                </article>

                <!-- Capítulo 5: Robótica Submarina y Aérea -->
                <article class="ras-chapter-card" id="chapter-marine-aerial">
                    <div class="ras-chapter-card__header">
                        <h3>Robótica Marina y Aérea</h3>
                        <span class="ras-chapter-card__badge" aria-label="Categoría">Especialidad</span>
                    </div>
                    
                    <div class="ras-chapter-card__content">
                        <p class="ras-chapter-card__description">
                            Especialización en Vehículos Aéreos No Tripulados (UAV) y 
                            sistemas submarinos autónomos.
                        </p>
                        
                        <div class="ras-chapter-card__focus">
                            <h4>Áreas de Enfoque:</h4>
                            <ul>
                                <li>Drones y cuadricópteros</li>
                                <li>Vehículos autónomos submarinos</li>
                                <li>Navegación aérea</li>
                                <li>Aplicaciones de mapeo y monitoreo</li>
                            </ul>
                        </div>

                        <div class="ras-chapter-card__stats">
                            <div class="stat">
                                <span class="stat__number">14+</span>
                                <span class="stat__label">Miembros activos</span>
                            </div>
                            <div class="stat">
                                <span class="stat__number">7</span>
                                <span class="stat__label">UAVs operacionales</span>
                            </div>
                        </div>
                    </div>

                    <footer class="ras-chapter-card__footer">
                        <a href="/ras/capitulos/marina-aerea" class="btn btn--primary btn--sm"
                           aria-label="Más información sobre Robótica Marina y Aérea">
                            Conocer más
                        </a>
                        <a href="mailto:ras-marine@ieee.org" class="btn btn--outline btn--sm"
                           aria-label="Contactar al capítulo de Robótica Marina y Aérea">
                            Contactar
                        </a>
                    </footer>
                </article>

                <!-- Capítulo 6: Aplicaciones Industriales -->
                <article class="ras-chapter-card" id="chapter-industrial">
                    <div class="ras-chapter-card__header">
                        <h3>Aplicaciones Industriales</h3>
                        <span class="ras-chapter-card__badge" aria-label="Categoría">Especialidad</span>
                    </div>
                    
                    <div class="ras-chapter-card__content">
                        <p class="ras-chapter-card__description">
                            Transferencia de tecnología robótica a la industria manufacturera 
                            y de transformación.
                        </p>
                        
                        <div class="ras-chapter-card__focus">
                            <h4>Áreas de Enfoque:</h4>
                            <ul>
                                <li>Automatización industrial</li>
                                <li>Mejora de procesos</li>
                                <li>Robótica colaborativa</li>
                                <li>Industria 4.0</li>
                            </ul>
                        </div>

                        <div class="ras-chapter-card__stats">
                            <div class="stat">
                                <span class="stat__number">20+</span>
                                <span class="stat__label">Miembros activos</span>
                            </div>
                            <div class="stat">
                                <span class="stat__number">12</span>
                                <span class="stat__label">Alianzas industriales</span>
                            </div>
                        </div>
                    </div>

                    <footer class="ras-chapter-card__footer">
                        <a href="/ras/capitulos/industrial" class="btn btn--primary btn--sm"
                           aria-label="Más información sobre Aplicaciones Industriales">
                            Conocer más
                        </a>
                        <a href="mailto:ras-industrial@ieee.org" class="btn btn--outline btn--sm"
                           aria-label="Contactar al capítulo de Aplicaciones Industriales">
                            Contactar
                        </a>
                    </footer>
                </article>

            </div>
        </div>
    </section>

    <!-- Oportunidades de Participación -->
    <section id="ras-participation" class="ras-participation" aria-labelledby="participation-title">
        <div class="container">
            <h2 id="participation-title">Únete a Nuestros Capítulos</h2>
            
            <div class="ras-participation__content">
                <article class="ras-participation__card">
                    <h3>¿Por qué participar?</h3>
                    <ul class="ras-participation__list">
                        <li>
                            <strong>Red Global:</strong> Acceso a la red internacional de profesionales en robótica 
                            y automatización
                        </li>
                        <li>
                            <strong>Desarrollo profesional:</strong> Talleres, seminarios y conferencias especializadas
                        </li>
                        <li>
                            <strong>Oportunidades de investigación:</strong> Colaboración en proyectos innovadores
                        </li>
                        <li>
                            <strong>Publicaciones académicas:</strong> Acceso a revistas y conferencias indexadas
                        </li>
                        <li>
                            <strong>Reconocimiento:</strong> Premios y menciones por contribuciones destacadas
                        </li>
                    </ul>
                </article>

                <article class="ras-participation__card">
                    <h3>Cómo involucrarse</h3>
                    <ol class="ras-participation__list ras-participation__list--ordered">
                        <li>
                            <strong>Solicita membresía:</strong> Completa el formulario de registro como miembro IEEE
                        </li>
                        <li>
                            <strong>Elige tu capítulo:</strong> Selecciona el área de especialización que más te interese
                        </li>
                        <li>
                            <strong>Participa activamente:</strong> Asiste a reuniones, seminarios y eventos
                        </li>
                        <li>
                            <strong>Contribuye:</strong> Comparte proyectos, publica artículos y colabora con otros
                        </li>
                        <li>
                            <strong>Crece profesionalmente:</strong> Accede a recursos y oportunidades de desarrollo
                        </li>
                    </ol>
                </article>
            </div>

            <div class="ras-participation__cta">
                <a href="/membership" class="btn btn--primary btn--lg" aria-label="Solicitar membresía en RAS">
                    Solicitar Membresía
                </a>
                <a href="/ras/eventos" class="btn btn--outline btn--lg" aria-label="Ver eventos de RAS">
                    Ver Próximos Eventos
                </a>
            </div>
        </div>
    </section>

    <!-- Galería de Proyectos -->
    <section id="ras-projects" class="ras-projects" aria-labelledby="projects-title">
        <div class="container">
            <h2 id="projects-title">Proyectos Destacados</h2>
            
            <div class="ras-projects__grid">
                
                <article class="ras-project-card">
                    <figure class="ras-project-card__image">
                        <picture>
                            <source srcset="/build/img/pages/ras/proyecto-1.webp" type="image/webp">
                            <img loading="lazy" width="300" height="200" 
                                 src="/build/img/pages/ras/proyecto-1.jpg" 
                                 alt="Proyecto: Robot móvil autónomo">
                        </picture>
                    </figure>
                    <div class="ras-project-card__content">
                        <h3>Robot Móvil Autónomo</h3>
                        <p>Sistema de navegación autónoma con SLAM para entornos interiores y exteriores.</p>
                        <span class="ras-project-card__tag">Robótica Móvil</span>
                    </div>
                </article>

                <article class="ras-project-card">
                    <figure class="ras-project-card__image">
                        <picture>
                            <source srcset="/build/img/pages/ras/proyecto-2.webp" type="image/webp">
                            <img loading="lazy" width="300" height="200" 
                                 src="/build/img/pages/ras/proyecto-2.jpg" 
                                 alt="Proyecto: Brazo robótico colaborativo">
                        </picture>
                    </figure>
                    <div class="ras-project-card__content">
                        <h3>Brazo Robótico Colaborativo</h3>
                        <p>Manipulador de 6 DOF con control de fuerza y retroalimentación táctil.</p>
                        <span class="ras-project-card__tag">Manipuladores</span>
                    </div>
                </article>

                <article class="ras-project-card">
                    <figure class="ras-project-card__image">
                        <picture>
                            <source srcset="/build/img/pages/ras/proyecto-3.webp" type="image/webp">
                            <img loading="lazy" width="300" height="200" 
                                 src="/build/img/pages/ras/proyecto-3.jpg" 
                                 alt="Proyecto: Dron de mapeo inteligente">
                        </picture>
                    </figure>
                    <div class="ras-project-card__content">
                        <h3>Dron de Mapeo Inteligente</h3>
                        <p>UAV con procesamiento de imagen en tiempo real para vigilancia ambiental.</p>
                        <span class="ras-project-card__tag">Robótica Aérea</span>
                    </div>
                </article>

            </div>
        </div>
    </section>

    <!-- Newsletter y Contacto -->
    <section id="ras-newsletter" class="ras-newsletter" aria-labelledby="newsletter-title">
        <div class="container">
            <div class="ras-newsletter__content">
                <div class="ras-newsletter__text">
                    <h2 id="newsletter-title">Mantente Informado</h2>
                    <p>
                        Recibe actualizaciones sobre eventos, publicaciones y oportunidades de los 
                        capítulos RAS de la Rama IEEE Unicauca.
                    </p>
                </div>

                <form class="ras-newsletter__form" method="POST" action="/subscribe-ras" aria-label="Formulario de suscripción a boletín RAS">
                    <fieldset>
                        <legend class="sr-only">Suscripción al boletín RAS</legend>
                        
                        <div class="form-group">
                            <label for="email-ras" class="form-label">Correo electrónico:</label>
                            <input 
                                type="email" 
                                id="email-ras" 
                                name="email" 
                                class="form-control" 
                                placeholder="tu@correo.com"
                                required
                                aria-required="true"
                            >
                        </div>

                        <div class="form-group">
                            <label for="chapter-preference" class="form-label">Capítulo de interés:</label>
                            <select id="chapter-preference" name="chapter" class="form-control" required aria-required="true">
                                <option value="">-- Selecciona un capítulo --</option>
                                <option value="mobile">Robótica Móvil</option>
                                <option value="manipulators">Manipuladores</option>
                                <option value="autonomous">Sistemas Autónomos</option>
                                <option value="humanoid">Robótica Humanoides</option>
                                <option value="marine">Robótica Marina y Aérea</option>
                                <option value="industrial">Aplicaciones Industriales</option>
                                <option value="all">Todos los capítulos</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn--primary btn--full-width" aria-label="Suscribirse al boletín">
                            Suscribirse
                        </button>
                    </fieldset>
                </form>
            </div>
        </div>
    </section>

    <!-- Contacto Directo -->
    <section id="ras-contact" class="ras-contact" aria-labelledby="contact-title">
        <div class="container">
            <h2 id="contact-title">Contacto Directo</h2>
            
            <div class="ras-contact__grid">
                <address class="ras-contact__card">
                    <h3>Oficina Principal RAS</h3>
                    <p>
                        <strong>Email:</strong> 
                        <a href="mailto:ras@ieee.unicauca.edu.co">ras@ieee.unicauca.edu.co</a>
                    </p>
                    <p>
                        <strong>Teléfono:</strong> 
                        <a href="tel:+5728209834">(+57) 2 8209834</a>
                    </p>
                    <p>
                        <strong>Ubicación:</strong><br>
                        Edificio de Ingeniería, Piso 3<br>
                        Universidad del Cauca<br>
                        Popayán, Cauca, Colombia
                    </p>
                </address>

                <div class="ras-contact__card">
                    <h3>Síguenos en Redes</h3>
                    <ul class="ras-social__list">
                        <li>
                            <a href="https://linkedin.com/in/ieee-ras-unicauca" target="_blank" rel="noopener noreferrer"
                               aria-label="Síguenos en LinkedIn">
                                LinkedIn
                            </a>
                        </li>
                        <li>
                            <a href="https://twitter.com/ieee_ras_uca" target="_blank" rel="noopener noreferrer"
                               aria-label="Síguenos en Twitter">
                                Twitter
                            </a>
                        </li>
                        <li>
                            <a href="https://instagram.com/ieee_ras_unicauca" target="_blank" rel="noopener noreferrer"
                               aria-label="Síguenos en Instagram">
                                Instagram
                            </a>
                        </li>
                        <li>
                            <a href="https://youtube.com/@ieee-ras-unicauca" target="_blank" rel="noopener noreferrer"
                               aria-label="Síguenos en YouTube">
                                YouTube
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="ras-contact__card">
                    <h3>Recursos Útiles</h3>
                    <ul class="ras-resources__list">
                        <li>
                            <a href="https://www.ieee-ras.org" target="_blank" rel="noopener noreferrer"
                               aria-label="Sitio web oficial de IEEE RAS">
                                Sitio Web IEEE RAS
                            </a>
                        </li>
                        <li>
                            <a href="https://www.ieee.org" target="_blank" rel="noopener noreferrer"
                               aria-label="Sitio web de IEEE">
                                IEEE.org
                            </a>
                        </li>
                        <li>
                            <a href="/eventos" aria-label="Ver eventos IEEE Unicauca">
                                Eventos Unicauca
                            </a>
                        </li>
                        <li>
                            <a href="/publicaciones" aria-label="Ver publicaciones">
                                Publicaciones
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

</main>

<?php
/**
 * NOTAS PARA IMPLEMENTACIÓN:
 * 
 * 1. ESTRUCTURA SEO:
 *    - Encabezados semánticos (<h1>, <h2>, <h3>)
 *    - Atributos aria-labelledby en secciones
 *    - Meta descripciones en controller
 *    - URLs semánticas para cada capítulo
 * 
 * 2. ACCESIBILIDAD:
 *    - Etiquetas form correctas
 *    - aria-required en campos obligatorios
 *    - aria-hidden para iconos decorativos
 *    - Contraste de colores adecuado
 * 
 * 3. PERFORMANCE:
 *    - loading="lazy" para imágenes
 *    - Rutas WebP con fallback JPG
 *    - Minificación de CSS/JS requerida
 * 
 * 4. DISEÑO RESPONSIVO:
 *    - CSS Grid adaptable
 *    - Breakpoints con mixins SCSS
 *    - Unidades relativas (rem, em)
 * 
 * 5. SEGURIDAD:
 *    - Sanitización de inputs en controller
 *    - CSRF tokens en formularios
 *    - Validación server-side
 */
?>