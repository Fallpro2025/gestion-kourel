<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Gestion Kourel') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        secondary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        },
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://unpkg.com/vue-router@4/dist/vue-router.global.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    
    <!-- Meta tags pour PWA -->
    <meta name="description" content="Plateforme moderne de gestion de dahira/kourel avec suivi des membres, cotisations, activités et événements">
    <meta name="theme-color" content="#3b82f6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Gestion Kourel">
    
    <!-- Manifest PWA -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- Icons -->
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/icon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icons/icon-16x16.png">
    
    <!-- Styles inline pour éviter le flash -->
    <style>
        /* Loading spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Styles de base pour éviter le flash */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #eff6ff 0%, #ecfdf5 100%);
            margin: 0;
            padding: 0;
        }
        
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #eff6ff 0%, #ecfdf5 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        .loading-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #3b82f6, #10b981);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }
        
        .loading-text {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .loading-subtitle {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 32px;
        }
        
        .loading-progress {
            width: 200px;
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
        }
        
        .loading-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #10b981);
            border-radius: 2px;
            animation: progress 2s ease-in-out infinite;
        }
        
        @keyframes progress {
            0% { width: 0%; }
            50% { width: 70%; }
            100% { width: 100%; }
        }
        
        /* Masquer le loading une fois l'app chargée */
        .app-loaded .loading-screen {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Écran de chargement -->
    <div class="loading-screen" id="loading-screen">
        <div class="loading-logo">
            <span style="color: white; font-size: 32px; font-weight: bold;">DK</span>
        </div>
        <div class="loading-text">Gestion Kourel</div>
        <div class="loading-subtitle">Plateforme moderne de gestion de dahira</div>
        <div class="loading-progress">
            <div class="loading-progress-bar"></div>
        </div>
    </div>

    <!-- Application Vue.js -->
    <div id="app"></div>

    <!-- Scripts -->
    <script>
        // Masquer l'écran de chargement une fois l'app Vue chargée
        document.addEventListener('DOMContentLoaded', function() {
            // Attendre que Vue soit monté
            setTimeout(function() {
                const loadingScreen = document.getElementById('loading-screen');
                if (loadingScreen) {
                    loadingScreen.style.opacity = '0';
                    loadingScreen.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(function() {
                        loadingScreen.style.display = 'none';
                        document.body.classList.add('app-loaded');
                    }, 500);
                }
            }, 1000);
        });

        // Configuration globale
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            appName: '{{ config("app.name", "Gestion Kourel") }}',
            appUrl: '{{ config("app.url") }}',
            appEnv: '{{ config("app.env") }}',
            appDebug: {{ config("app.debug") ? "true" : "false" }}
        };

        // Service Worker pour PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker enregistré avec succès:', registration.scope);
                    })
                    .catch(function(error) {
                        console.log('Échec de l\'enregistrement du ServiceWorker:', error);
                    });
            });
        }

        // Gestion des erreurs globales
        window.addEventListener('error', function(event) {
            console.error('Erreur JavaScript:', event.error);
        });

        window.addEventListener('unhandledrejection', function(event) {
            console.error('Promesse rejetée non gérée:', event.reason);
        });

        // Fonction pour ajouter des notifications toast
        window.addToast = function(message, type = 'info') {
            // Cette fonction sera remplacée par le composant Vue
            console.log(`[${type.toUpperCase()}] ${message}`);
        };

        // Configuration des couleurs CSS personnalisées
        const root = document.documentElement;
        root.style.setProperty('--primary-500', '#3b82f6');
        root.style.setProperty('--primary-600', '#2563eb');
        root.style.setProperty('--secondary-500', '#10b981');
        root.style.setProperty('--secondary-600', '#059669');
        root.style.setProperty('--success', '#10b981');
        root.style.setProperty('--warning', '#f59e0b');
        root.style.setProperty('--error', '#ef4444');
        root.style.setProperty('--info', '#3b82f6');

        // Charger l'application Vue.js moderne
        const script = document.createElement('script');
        script.src = '/resources/js/app-modern.js';
        script.onload = function() {
            console.log('🚀 Gestion Kourel - Application Vue.js moderne chargée');
            console.log('📊 Dashboard interactif avec statistiques en temps réel');
            console.log('🎨 Interface glassmorphism et animations fluides');
            console.log('📱 Design responsive et moderne');
        };
        document.head.appendChild(script);
    </script>
</body>
</html>
