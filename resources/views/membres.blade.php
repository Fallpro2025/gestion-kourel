<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Gestion Kourel') }} - Gestion des Membres</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Gestion des vues en JavaScript vanilla -->
    <script>
        // Variable globale pour la vue actuelle
        let vueActuelle = localStorage.getItem('vueActuelle') || 'grille';
        
        // Fonction pour changer la vue directement
        function changerVueDirectement() {
            const vues = ['grille', 'liste', 'tableau'];
            const indexActuel = vues.indexOf(vueActuelle);
            const prochainIndex = (indexActuel + 1) % vues.length;
            vueActuelle = vues[prochainIndex];
            
            // Sauvegarder la vue dans localStorage
            localStorage.setItem('vueActuelle', vueActuelle);
            
            // Mettre à jour l'icône et le texte
            mettreAJourBoutonVue();
            
            // Appeler la fonction JavaScript existante
            if (vueActuelle === 'grille') {
                switchToGridView();
            } else if (vueActuelle === 'liste') {
                switchToListView();
            } else if (vueActuelle === 'tableau') {
                switchToTableView();
            }
        }
        
        // Fonction pour mettre à jour le bouton
        function mettreAJourBoutonVue() {
            const icone = document.getElementById('vueIcon');
            const texte = document.getElementById('vueTexte');
            
            const icones = {
                'grille': 'fas fa-th',
                'liste': 'fas fa-list',
                'tableau': 'fas fa-table'
            };
            
            const textes = {
                'grille': 'Grille',
                'liste': 'Liste',
                'tableau': 'Tableau'
            };
            
            if (icone) {
                icone.className = icones[vueActuelle] + ' w-5 h-5';
            }
            
            if (texte) {
                texte.textContent = textes[vueActuelle];
            }
        }
        
        // Initialiser la vue au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Mettre à jour le bouton avec la vue actuelle
            mettreAJourBoutonVue();
            
            // Appliquer la vue actuelle si ce n'est pas la grille par défaut
            if (vueActuelle !== 'grille') {
                if (vueActuelle === 'liste') {
                    switchToListView();
                } else if (vueActuelle === 'tableau') {
                    switchToTableView();
                }
            }
        });
    </script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa',
                            500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a',
                        },
                        secondary: {
                            50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7', 400: '#34d399',
                            500: '#10b981', 600: '#059669', 700: '#047857', 800: '#065f46', 900: '#064e3b',
                        },
                        accent: {
                            rose: '#ec4899', orange: '#f97316', purple: '#8b5cf6', amber: '#f59e0b',
                        },
                        success: '#10b981', warning: '#f59e0b', error: '#ef4444', info: '#3b82f6',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'slide-up': 'slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
                        'slide-in-right': 'slideInRight 0.3s ease-out',
                        'pulse': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'loading': 'loading 1.5s infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { 
                            '0%': { opacity: '0', transform: 'translateY(30px) scale(0.95)' },
                            '100%': { opacity: '1', transform: 'translateY(0) scale(1)' }
                        },
                        slideInRight: { 
                            '0%': { opacity: '0', transform: 'translateX(100%)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' }
                        },
                        loading: { 
                            '0%': { backgroundPosition: '200% 0' },
                            '100%': { backgroundPosition: '-200% 0' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        glow: {
                            '0%': { boxShadow: '0 0 20px rgba(59, 130, 246, 0.5)' },
                            '100%': { boxShadow: '0 0 30px rgba(59, 130, 246, 0.8)' }
                        }
                    },
                    backdropBlur: { xs: '2px' },
                    boxShadow: {
                        'glass': '0 8px 32px rgba(0, 0, 0, 0.1)',
                        'glass-hover': '0 20px 40px rgba(0, 0, 0, 0.15)',
                        'neon': '0 0 20px rgba(59, 130, 246, 0.5)',
                        'neon-hover': '0 0 30px rgba(59, 130, 246, 0.8)',
                    },
                    borderRadius: { 'xl': '1rem', '2xl': '1.5rem', '3xl': '2rem' },
                },
            },
        }
    </script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .glass-dark {
            background: rgba(31, 41, 55, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(75, 85, 99, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .hover-lift:hover { 
            transform: translateY(-2px); 
            transition: transform 0.2s ease; 
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6, #10b981, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .neon-border {
            border: 1px solid rgba(59, 130, 246, 0.3);
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.2);
        }
        
        .neon-border:hover {
            border: 1px solid rgba(59, 130, 246, 0.6);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
        }
        
        .metric-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }
        
        .metric-card:hover {
            background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.08));
            transform: translateY(-2px);
        }
        
        .nav-item {
            position: relative;
            overflow: hidden;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .nav-item:hover::before {
            left: 100%;
        }
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        .member-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }
        
        .member-card:hover {
            background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.08));
            transform: translateY(-2px);
            border-color: rgba(59, 130, 246, 0.4);
        }
        
        .status-badge {
            position: relative;
            overflow: hidden;
        }
        
        .status-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .status-badge:hover::before {
            left: 100%;
        }
        
        .search-input {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
        }
        
        /* Styles pour les options des select */
        .search-input option {
            background: #1f2937 !important;
            color: #ffffff !important;
            padding: 8px 12px;
        }
        
        .search-input option:hover {
            background: #374151 !important;
            color: #ffffff !important;
        }
        
        .search-input option:checked {
            background: #3b82f6 !important;
            color: #ffffff !important;
        }
        
        .search-input option:focus {
            background: #3b82f6 !important;
            color: #ffffff !important;
        }
        
        /* Style pour le select lui-même */
        select.search-input {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.1) !important;
        }
        
        select.search-input option {
            background-color: #1f2937 !important;
            color: #ffffff !important;
        }
        
        /* Forcer les styles sur tous les selects */
        select {
            color: #ffffff !important;
        }
        
        select option {
            background-color: #1f2937 !important;
            color: #ffffff !important;
        }
        
        select option:hover {
            background-color: #374151 !important;
            color: #ffffff !important;
        }
        
        select option:checked {
            background-color: #3b82f6 !important;
            color: #ffffff !important;
        }
        
        /* Styles pour les différentes vues */
        .view-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .view-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .view-table {
            display: table;
            width: 100%;
        }
        
        .member-list-item {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .member-list-item:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(59, 130, 246, 0.3);
            transform: translateY(-2px);
        }
        
        .member-table-row {
            display: table-row;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .member-table-cell {
            display: table-cell;
            padding: 1rem;
            vertical-align: middle;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .member-table-cell:last-child {
            border-right: none;
        }
        
        /* Animation pour les modals */
        .modal-overlay {
            animation: fadeIn 0.3s ease-out;
        }
        
        .modal-content {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideIn {
            from { 
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
            to { 
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        /* Styles pour les onglets */
        .tab-button {
            transition: all 0.3s ease;
        }
        
        .tab-button.active {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.5);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #10b981, #059669);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 min-h-screen">
    <div id="app">
        <!-- Sidebar Navigation -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white/10 backdrop-blur-xl border-r border-white/20 sidebar-transition">
            <div class="flex flex-col h-full">
                <!-- Logo Section -->
                <div class="flex items-center justify-center p-6 border-b border-white/20">
                    <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center mr-3 pulse-glow">
                        <span class="text-white font-bold text-xl">DK</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">Gestion Kourel</h1>
                        <p class="text-xs text-white/70">Gestion des Membres</p>
                    </div>
                </div>
                
                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="/" class="nav-item flex items-center px-4 py-3 text-white/80 rounded-xl hover:bg-white/20 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="/membres" class="nav-item flex items-center px-4 py-3 text-white rounded-xl bg-white/20 hover:bg-white/30 transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Membres
                    </a>
                    
                    <a href="/cotisations" class="nav-item flex items-center px-4 py-3 text-white/80 rounded-xl hover:bg-white/20 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Cotisations
                    </a>
                    
                    <a href="/activites" class="nav-item flex items-center px-4 py-3 text-white/80 rounded-xl hover:bg-white/20 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Activités
                    </a>
                    
                    <a href="/evenements" class="nav-item flex items-center px-4 py-3 text-white/80 rounded-xl hover:bg-white/20 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Événements
                    </a>
                    
                    <a href="/alertes" class="nav-item flex items-center px-4 py-3 text-white/80 rounded-xl hover:bg-white/20 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828z"></path>
                        </svg>
                        Alertes
                    </a>
                </nav>
                
                <!-- User Profile -->
                <div class="p-4 border-t border-white/20">
                    <div class="flex items-center p-3 bg-white/10 rounded-xl">
                        <div class="w-10 h-10 bg-gradient-to-r from-accent-rose to-accent-purple rounded-full flex items-center justify-center mr-3">
                            <span class="text-white font-bold text-sm">AD</span>
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm">Admin</p>
                            <p class="text-white/60 text-xs">Administrateur</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="ml-64 min-h-screen">
            <!-- Top Header -->
            <header class="bg-white/10 backdrop-blur-xl border-b border-white/20 sticky top-0 z-40">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-white">Gestion des Membres</h2>
                            <p class="text-white/70">Gérez les membres de votre dahira/kourel</p>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Add Member Button -->
                            <button onclick="openAddMemberModal()" class="btn-primary px-6 py-2 text-white font-medium rounded-xl flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span>Ajouter Membre</span>
                            </button>
                            
                            <!-- Export Button -->
                            <button onclick="exportMembers()" class="btn-secondary px-4 py-2 text-white font-medium rounded-xl flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Exporter</span>
                            </button>
                            
                            <!-- View Options -->
                            <div class="relative">
                                <button onclick="changerVueDirectement()" class="px-4 py-2 bg-white/10 text-white font-medium rounded-xl flex items-center space-x-2 hover:bg-white/20 transition-all duration-300">
                                    <i id="vueIcon" class="fas fa-th w-5 h-5"></i>
                                    <span id="vueTexte">Grille</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content -->
            <main class="p-6">
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/70 text-sm font-medium">Total Membres</p>
                                <p class="text-3xl font-bold text-white mt-2">24</p>
                                <p class="text-green-400 text-sm mt-1">+3 ce mois</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/70 text-sm font-medium">Membres Actifs</p>
                                <p class="text-3xl font-bold text-white mt-2">22</p>
                                <p class="text-green-400 text-sm mt-1">92% actifs</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-secondary-500 to-secondary-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/70 text-sm font-medium">Nouveaux</p>
                                <p class="text-3xl font-bold text-white mt-2">3</p>
                                <p class="text-blue-400 text-sm mt-1">Ce mois</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-accent-purple to-accent-rose rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/70 text-sm font-medium">Présence Moy.</p>
                                <p class="text-3xl font-bold text-white mt-2">94%</p>
                                <p class="text-green-400 text-sm mt-1">+2% cette semaine</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-accent-orange to-accent-rose rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Search and Filters -->
                <div class="metric-card rounded-2xl p-6 mb-6">
                    <div class="flex flex-col lg:flex-row gap-4">
                        <!-- Search Input -->
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" id="searchInput" placeholder="Rechercher par nom, téléphone, email..." class="search-input w-full px-4 py-3 pl-12 text-white placeholder-white/50 rounded-xl focus:outline-none">
                                <svg class="w-5 h-5 text-white/50 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Filters -->
                        <div class="flex flex-wrap gap-3">
                            <select id="roleFilter" class="search-input px-4 py-3 text-white rounded-xl focus:outline-none" style="color: white; background: rgba(255, 255, 255, 0.1);">
                                <option value="" style="background: #1f2937; color: white;">Tous les rôles</option>
                                <option value="membre" style="background: #1f2937; color: white;">Membre</option>
                                <option value="responsable" style="background: #1f2937; color: white;">Responsable</option>
                                <option value="animateur" style="background: #1f2937; color: white;">Animateur</option>
                                <option value="choriste" style="background: #1f2937; color: white;">Choriste</option>
                                <option value="trésorier" style="background: #1f2937; color: white;">Trésorier</option>
                                <option value="secrétaire" style="background: #1f2937; color: white;">Secrétaire</option>
                            </select>
                            
                            <select id="statusFilter" class="search-input px-4 py-3 text-white rounded-xl focus:outline-none" style="color: white; background: rgba(255, 255, 255, 0.1);">
                                <option value="" style="background: #1f2937; color: white;">Tous les statuts</option>
                                <option value="actif" style="background: #1f2937; color: white;">Actif</option>
                                <option value="inactif" style="background: #1f2937; color: white;">Inactif</option>
                                <option value="suspendu" style="background: #1f2937; color: white;">Suspendu</option>
                                <option value="nouveau" style="background: #1f2937; color: white;">Nouveau</option>
                            </select>
                            
                            <select id="presenceFilter" class="search-input px-4 py-3 text-white rounded-xl focus:outline-none" style="color: white; background: rgba(255, 255, 255, 0.1);">
                                <option value="" style="background: #1f2937; color: white;">Toutes présences</option>
                                <option value="excellent" style="background: #1f2937; color: white;">Excellent (90%+)</option>
                                <option value="bon" style="background: #1f2937; color: white;">Bon (70-89%)</option>
                                <option value="moyen" style="background: #1f2937; color: white;">Moyen (50-69%)</option>
                                <option value="faible" style="background: #1f2937; color: white;">Faible (<50%)</option>
                            </select>
                            
                            <button onclick="clearFilters()" class="px-4 py-3 bg-white/10 text-white rounded-xl hover:bg-white/20 transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="flex flex-wrap gap-4 mt-4 pt-4 border-t border-white/20">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-white/70 text-sm">Actifs: <span class="text-green-400 font-bold">22</span></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                            <span class="text-white/70 text-sm">En retard: <span class="text-orange-400 font-bold">2</span></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-white/70 text-sm">Inactifs: <span class="text-red-400 font-bold">1</span></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-white/70 text-sm">Nouveaux: <span class="text-blue-400 font-bold">3</span></span>
                        </div>
                    </div>
                </div>
                
                <!-- Members Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="membersGrid">
                    <!-- Member Card 1 -->
                    <div class="member-card rounded-2xl p-6 card-hover" data-name="fatou diop" data-role="choriste" data-status="actif" data-presence="95">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-lg">FD</span>
                            </div>
                            <div class="status-badge px-3 py-1 bg-green-500/20 text-green-400 text-xs rounded-full border border-green-500/30">
                                Actif
                            </div>
                        </div>
                        
                        <h3 class="text-white font-bold text-lg mb-1">Fatou Diop</h3>
                        <p class="text-white/70 text-sm mb-2">Choriste</p>
                        <p class="text-white/60 text-xs mb-2">+221 77 123 45 67</p>
                        <p class="text-white/60 text-xs mb-4">fatou.diop@email.com</p>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-white/60">Présence</span>
                                <span class="text-green-400">95%</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-1">
                                <div class="bg-green-500 h-1 rounded-full" style="width: 95%"></div>
                            </div>
                            
                            <div class="flex justify-between text-xs">
                                <span class="text-white/60">Cotisations</span>
                                <span class="text-green-400">À jour</span>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2 mt-4">
                            <button onclick="viewMemberDetails('fatou-diop')" class="flex-1 px-3 py-2 bg-white/10 text-white text-xs rounded-lg hover:bg-white/20 transition-all duration-300">
                                Voir
                            </button>
                            <button onclick="editMember('fatou-diop')" class="flex-1 px-3 py-2 bg-primary-500/20 text-primary-400 text-xs rounded-lg hover:bg-primary-500/30 transition-all duration-300">
                                Modifier
                            </button>
                        </div>
                    </div>
                    
                    <!-- Member Card 2 -->
                    <div class="member-card rounded-2xl p-6 card-hover" data-name="moustapha fall" data-role="responsable" data-status="actif" data-presence="78">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-secondary-500 to-secondary-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-lg">MF</span>
                            </div>
                            <div class="status-badge px-3 py-1 bg-orange-500/20 text-orange-400 text-xs rounded-full border border-orange-500/30">
                                En retard
                            </div>
                        </div>
                        
                        <h3 class="text-white font-bold text-lg mb-1">Moustapha Fall</h3>
                        <p class="text-white/70 text-sm mb-2">Responsable</p>
                        <p class="text-white/60 text-xs mb-2">+221 78 234 56 78</p>
                        <p class="text-white/60 text-xs mb-4">moustapha.fall@email.com</p>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-white/60">Présence</span>
                                <span class="text-orange-400">78%</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-1">
                                <div class="bg-orange-500 h-1 rounded-full" style="width: 78%"></div>
                            </div>
                            
                            <div class="flex justify-between text-xs">
                                <span class="text-white/60">Cotisations</span>
                                <span class="text-orange-400">En retard</span>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2 mt-4">
                            <button onclick="viewMemberDetails('moustapha-fall')" class="flex-1 px-3 py-2 bg-white/10 text-white text-xs rounded-lg hover:bg-white/20 transition-all duration-300">
                                Voir
                            </button>
                            <button onclick="editMember('moustapha-fall')" class="flex-1 px-3 py-2 bg-primary-500/20 text-primary-400 text-xs rounded-lg hover:bg-primary-500/30 transition-all duration-300">
                                Modifier
                            </button>
                        </div>
                    </div>
                    
                    <!-- Member Card 3 -->
                    <div class="member-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-accent-purple to-accent-rose rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-lg">AS</span>
                            </div>
                            <div class="status-badge px-3 py-1 bg-green-500/20 text-green-400 text-xs rounded-full border border-green-500/30">
                                Actif
                            </div>
                        </div>
                        
                        <h3 class="text-white font-bold text-lg mb-1">Aminata Sow</h3>
                        <p class="text-white/70 text-sm mb-2">Animateur</p>
                        <p class="text-white/60 text-xs mb-4">+221 76 345 67 89</p>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-white/60">Présence</span>
                                <span class="text-green-400">98%</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-1">
                                <div class="bg-green-500 h-1 rounded-full" style="width: 98%"></div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2 mt-4">
                            <button class="flex-1 px-3 py-2 bg-white/10 text-white text-xs rounded-lg hover:bg-white/20 transition-all duration-300">
                                Voir
                            </button>
                            <button class="flex-1 px-3 py-2 bg-primary-500/20 text-primary-400 text-xs rounded-lg hover:bg-primary-500/30 transition-all duration-300">
                                Modifier
                            </button>
                        </div>
                    </div>
                    
                    <!-- Member Card 4 -->
                    <div class="member-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-accent-orange to-accent-rose rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-lg">IK</span>
                            </div>
                            <div class="status-badge px-3 py-1 bg-red-500/20 text-red-400 text-xs rounded-full border border-red-500/30">
                                Inactif
                            </div>
                        </div>
                        
                        <h3 class="text-white font-bold text-lg mb-1">Ibrahima Kane</h3>
                        <p class="text-white/70 text-sm mb-2">Membre</p>
                        <p class="text-white/60 text-xs mb-4">+221 77 456 78 90</p>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-white/60">Présence</span>
                                <span class="text-red-400">45%</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-1">
                                <div class="bg-red-500 h-1 rounded-full" style="width: 45%"></div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2 mt-4">
                            <button class="flex-1 px-3 py-2 bg-white/10 text-white text-xs rounded-lg hover:bg-white/20 transition-all duration-300">
                                Voir
                            </button>
                            <button class="flex-1 px-3 py-2 bg-primary-500/20 text-primary-400 text-xs rounded-lg hover:bg-primary-500/30 transition-all duration-300">
                                Modifier
                            </button>
                        </div>
                    </div>
                    
                    <!-- Add Member Card -->
                    <div class="member-card rounded-2xl p-6 card-hover border-2 border-dashed border-white/30 hover:border-primary-500/50 transition-all duration-300 cursor-pointer" onclick="openAddMemberModal()">
                        <div class="flex flex-col items-center justify-center h-full min-h-[200px] text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-primary-500/20 to-secondary-500/20 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <h3 class="text-white font-bold text-lg mb-2">Ajouter un Membre</h3>
                            <p class="text-white/60 text-sm">Cliquez pour ajouter un nouveau membre</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        
        <!-- Add Member Modal -->
        <div id="addMemberModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="metric-card rounded-2xl p-8 w-full max-w-md">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-white">Ajouter un Membre</h3>
                        <button onclick="closeAddMemberModal()" class="text-white/60 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <form class="space-y-4">
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Nom complet</label>
                            <input type="text" class="search-input w-full px-4 py-3 text-white placeholder-white/50 rounded-xl focus:outline-none" placeholder="Nom et prénom">
                        </div>
                        
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Téléphone</label>
                            <input type="tel" class="search-input w-full px-4 py-3 text-white placeholder-white/50 rounded-xl focus:outline-none" placeholder="+221 77 123 45 67">
                        </div>
                        
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Rôle</label>
                            <select class="search-input w-full px-4 py-3 text-white rounded-xl focus:outline-none" style="color: white; background: rgba(255, 255, 255, 0.1);">
                                <option value="" style="background: #1f2937; color: white;">Sélectionner un rôle</option>
                                <option value="membre" style="background: #1f2937; color: white;">Membre</option>
                                <option value="responsable" style="background: #1f2937; color: white;">Responsable</option>
                                <option value="animateur" style="background: #1f2937; color: white;">Animateur</option>
                                <option value="choriste" style="background: #1f2937; color: white;">Choriste</option>
                                <option value="trésorier" style="background: #1f2937; color: white;">Trésorier</option>
                                <option value="secrétaire" style="background: #1f2937; color: white;">Secrétaire</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Email (optionnel)</label>
                            <input type="email" class="search-input w-full px-4 py-3 text-white placeholder-white/50 rounded-xl focus:outline-none" placeholder="email@example.com">
                        </div>
                        
                        <div class="flex space-x-3 pt-4">
                            <button type="button" onclick="closeAddMemberModal()" class="flex-1 px-4 py-3 bg-white/10 text-white rounded-xl hover:bg-white/20 transition-all duration-300">
                                Annuler
                            </button>
                            <button type="submit" class="flex-1 px-4 py-3 btn-primary text-white rounded-xl">
                                Ajouter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Détails Membre -->
    <div id="memberDetailsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden modal-overlay">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-gradient-to-br from-gray-800/90 to-gray-900/90 backdrop-blur-xl border border-white/20 rounded-3xl p-8 max-w-4xl w-full max-h-[90vh] overflow-y-auto modal-content">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-2xl" id="memberInitials">FD</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white" id="memberName">Fatou Diop</h2>
                            <p class="text-white/70" id="memberRole">Choriste</p>
                        </div>
                    </div>
                    <button onclick="closeMemberDetails()" class="p-2 hover:bg-white/10 rounded-xl transition-all duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Onglets -->
                <div class="flex space-x-2 mb-6">
                    <button onclick="switchTab('info')" class="tab-button px-4 py-2 bg-white/10 text-white rounded-xl border border-white/20 active">
                        Informations
                    </button>
                    <button onclick="switchTab('presence')" class="tab-button px-4 py-2 bg-white/10 text-white rounded-xl border border-white/20">
                        Présence
                    </button>
                    <button onclick="switchTab('cotisations')" class="tab-button px-4 py-2 bg-white/10 text-white rounded-xl border border-white/20">
                        Cotisations
                    </button>
                    <button onclick="switchTab('historique')" class="tab-button px-4 py-2 bg-white/10 text-white rounded-xl border border-white/20">
                        Historique
                    </button>
                </div>
                
                <!-- Contenu des onglets -->
                <div id="tab-info" class="tab-content active">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Nom complet</label>
                                <div class="p-3 bg-white/5 rounded-xl text-white" id="memberFullName">Fatou Diop</div>
                            </div>
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Téléphone</label>
                                <div class="p-3 bg-white/5 rounded-xl text-white" id="memberPhone">+221 77 123 45 67</div>
                            </div>
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Email</label>
                                <div class="p-3 bg-white/5 rounded-xl text-white" id="memberEmail">fatou.diop@email.com</div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Rôle</label>
                                <div class="p-3 bg-white/5 rounded-xl text-white" id="memberRoleDetail">Choriste</div>
                            </div>
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Statut</label>
                                <div class="p-3 bg-white/5 rounded-xl">
                                    <span class="px-3 py-1 bg-green-500/20 text-green-400 text-sm rounded-full border border-green-500/30" id="memberStatus">Actif</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Date d'inscription</label>
                                <div class="p-3 bg-white/5 rounded-xl text-white" id="memberJoinDate">15 Janvier 2024</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="tab-presence" class="tab-content">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-white/5 rounded-xl">
                                <div class="text-2xl font-bold text-green-400 mb-1">95%</div>
                                <div class="text-white/70 text-sm">Taux de présence</div>
                            </div>
                            <div class="p-4 bg-white/5 rounded-xl">
                                <div class="text-2xl font-bold text-blue-400 mb-1">42</div>
                                <div class="text-white/70 text-sm">Séances présentes</div>
                            </div>
                            <div class="p-4 bg-white/5 rounded-xl">
                                <div class="text-2xl font-bold text-orange-400 mb-1">3</div>
                                <div class="text-white/70 text-sm">Retards</div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-4">Dernières présences</h3>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl">
                                    <span class="text-white">Répétition - 20 Janvier 2024</span>
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full">Présent</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl">
                                    <span class="text-white">Goudi Aldiouma - 18 Janvier 2024</span>
                                    <span class="px-2 py-1 bg-orange-500/20 text-orange-400 text-xs rounded-full">Retard 5min</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl">
                                    <span class="text-white">Répétition - 15 Janvier 2024</span>
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full">Présent</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="tab-cotisations" class="tab-content">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-white/5 rounded-xl">
                                <div class="text-2xl font-bold text-green-400 mb-1">25,000 FCFA</div>
                                <div class="text-white/70 text-sm">Total payé</div>
                            </div>
                            <div class="p-4 bg-white/5 rounded-xl">
                                <div class="text-2xl font-bold text-blue-400 mb-1">5,000 FCFA</div>
                                <div class="text-white/70 text-sm">Restant</div>
                            </div>
                            <div class="p-4 bg-white/5 rounded-xl">
                                <div class="text-2xl font-bold text-purple-400 mb-1">83%</div>
                                <div class="text-white/70 text-sm">Progression</div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-4">Historique des paiements</h3>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl">
                                    <span class="text-white">Cotisation Janvier 2024</span>
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full">Payé - 10,000 FCFA</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl">
                                    <span class="text-white">Cotisation Décembre 2023</span>
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full">Payé - 10,000 FCFA</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl">
                                    <span class="text-white">Cotisation Novembre 2023</span>
                                    <span class="px-2 py-1 bg-orange-500/20 text-orange-400 text-xs rounded-full">Partiel - 5,000 FCFA</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="tab-historique" class="tab-content">
                    <div class="space-y-4">
                        <div class="p-4 bg-white/5 rounded-xl">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-white font-medium">Inscription au groupe</span>
                                <span class="text-white/70 text-sm">15 Janvier 2024</span>
                            </div>
                            <p class="text-white/70 text-sm">Fatou Diop a rejoint le groupe en tant que Choriste</p>
                        </div>
                        
                        <div class="p-4 bg-white/5 rounded-xl">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-white font-medium">Première répétition</span>
                                <span class="text-white/70 text-sm">18 Janvier 2024</span>
                            </div>
                            <p class="text-white/70 text-sm">Participation à la première répétition du groupe</p>
                        </div>
                        
                        <div class="p-4 bg-white/5 rounded-xl">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-white font-medium">Premier paiement</span>
                                <span class="text-white/70 text-sm">20 Janvier 2024</span>
                            </div>
                            <p class="text-white/70 text-sm">Paiement de la cotisation de janvier 2024</p>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex space-x-3 mt-8">
                    <button onclick="editMemberFromDetails()" class="flex-1 px-6 py-3 bg-primary-500 text-white rounded-xl hover:bg-primary-600 transition-all duration-300">
                        Modifier
                    </button>
                    <button onclick="exportMemberHistory()" class="flex-1 px-6 py-3 bg-white/10 text-white rounded-xl hover:bg-white/20 transition-all duration-300">
                        Exporter PDF
                    </button>
                    <button onclick="sendMessageToMember()" class="flex-1 px-6 py-3 bg-green-500/20 text-green-400 rounded-xl hover:bg-green-500/30 transition-all duration-300 border border-green-500/30">
                        Envoyer SMS
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modification Membre -->
    <div id="editMemberModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden modal-overlay">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-gradient-to-br from-gray-800/90 to-gray-900/90 backdrop-blur-xl border border-white/20 rounded-3xl p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto modal-content">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white">Modifier le membre</h2>
                    <button onclick="closeEditMember()" class="p-2 hover:bg-white/10 rounded-xl transition-all duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Formulaire -->
                <form id="editMemberForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Prénom</label>
                            <input type="text" id="editFirstName" class="search-input w-full px-4 py-3 text-white placeholder-white/50 rounded-xl focus:outline-none" placeholder="Prénom" required>
                        </div>
                        
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Nom</label>
                            <input type="text" id="editLastName" class="search-input w-full px-4 py-3 text-white placeholder-white/50 rounded-xl focus:outline-none" placeholder="Nom" required>
                        </div>
                        
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Téléphone</label>
                            <input type="tel" id="editPhone" class="search-input w-full px-4 py-3 text-white placeholder-white/50 rounded-xl focus:outline-none" placeholder="+221 77 123 45 67" required>
                        </div>
                        
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Email</label>
                            <input type="email" id="editEmail" class="search-input w-full px-4 py-3 text-white placeholder-white/50 rounded-xl focus:outline-none" placeholder="email@example.com">
                        </div>
                        
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Rôle</label>
                            <select id="editRole" class="search-input w-full px-4 py-3 text-white rounded-xl focus:outline-none" style="color: white; background: rgba(255, 255, 255, 0.1);">
                                <option value="" style="background: #1f2937; color: white;">Sélectionner un rôle</option>
                                <option value="membre" style="background: #1f2937; color: white;">Membre</option>
                                <option value="responsable" style="background: #1f2937; color: white;">Responsable</option>
                                <option value="animateur" style="background: #1f2937; color: white;">Animateur</option>
                                <option value="choriste" style="background: #1f2937; color: white;">Choriste</option>
                                <option value="trésorier" style="background: #1f2937; color: white;">Trésorier</option>
                                <option value="secrétaire" style="background: #1f2937; color: white;">Secrétaire</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Statut</label>
                            <select id="editStatus" class="search-input w-full px-4 py-3 text-white rounded-xl focus:outline-none" style="color: white; background: rgba(255, 255, 255, 0.1);">
                                <option value="actif" style="background: #1f2937; color: white;">Actif</option>
                                <option value="inactif" style="background: #1f2937; color: white;">Inactif</option>
                                <option value="suspendu" style="background: #1f2937; color: white;">Suspendu</option>
                                <option value="nouveau" style="background: #1f2937; color: white;">Nouveau</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex space-x-3 pt-6">
                        <button type="button" onclick="closeEditMember()" class="flex-1 px-6 py-3 bg-white/10 text-white rounded-xl hover:bg-white/20 transition-all duration-300">
                            Annuler
                        </button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-primary-500 text-white rounded-xl hover:bg-primary-600 transition-all duration-300">
                            Sauvegarder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Modal functions
        function openAddMemberModal() {
            document.getElementById('addMemberModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeAddMemberModal() {
            document.getElementById('addMemberModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        // Animation des cartes au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.member-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
            
            // Animation des barres de progression
            const progressBars = document.querySelectorAll('.member-card .bg-green-500, .member-card .bg-orange-500, .member-card .bg-red-500');
            setTimeout(() => {
                progressBars.forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 500);
                });
            }, 1000);
            
            console.log('🚀 Page Gestion des Membres chargée avec succès !');
        });
        
        // Fermer les modals en cliquant à l'extérieur
        document.getElementById('addMemberModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddMemberModal();
            }
        });

        document.getElementById('memberDetailsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMemberDetails();
            }
        });

        document.getElementById('editMemberModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditMember();
            }
        });
        
        // Données des membres
        const membersData = [
            {
                id: 'fatou-diop',
                name: 'Fatou Diop',
                role: 'Choriste',
                status: 'Actif',
                presence: 95,
                phone: '+221 77 123 45 67',
                email: 'fatou.diop@email.com',
                contributions: 'À jour',
                joinDate: '2023-01-15',
                lastActivity: '2024-09-14'
            },
            {
                id: 'moustapha-fall',
                name: 'Moustapha Fall',
                role: 'Responsable',
                status: 'En retard',
                presence: 78,
                phone: '+221 78 234 56 78',
                email: 'moustapha.fall@email.com',
                contributions: 'En retard',
                joinDate: '2022-08-20',
                lastActivity: '2024-09-12'
            },
            {
                id: 'aminata-sow',
                name: 'Aminata Sow',
                role: 'Animateur',
                status: 'Actif',
                presence: 98,
                phone: '+221 76 345 67 89',
                email: 'aminata.sow@email.com',
                contributions: 'À jour',
                joinDate: '2023-03-10',
                lastActivity: '2024-09-14'
            },
            {
                id: 'ibrahima-kane',
                name: 'Ibrahima Kane',
                role: 'Membre',
                status: 'Inactif',
                presence: 45,
                phone: '+221 77 456 78 90',
                email: 'ibrahima.kane@email.com',
                contributions: 'En retard',
                joinDate: '2023-06-05',
                lastActivity: '2024-08-20'
            },
            {
                id: 'khadija-diallo',
                name: 'Khadija Diallo',
                role: 'Trésorier',
                status: 'Actif',
                presence: 92,
                phone: '+221 78 567 89 01',
                email: 'khadija.diallo@email.com',
                contributions: 'À jour',
                joinDate: '2022-11-12',
                lastActivity: '2024-09-14'
            },
            {
                id: 'mamadou-ba',
                name: 'Mamadou Ba',
                role: 'Secrétaire',
                status: 'Actif',
                presence: 88,
                phone: '+221 76 678 90 12',
                email: 'mamadou.ba@email.com',
                contributions: 'À jour',
                joinDate: '2023-02-28',
                lastActivity: '2024-09-13'
            },
            {
                id: 'aissatou-ndiaye',
                name: 'Aissatou Ndiaye',
                role: 'Choriste',
                status: 'Nouveau',
                presence: 85,
                phone: '+221 77 789 01 23',
                email: 'aissatou.ndiaye@email.com',
                contributions: 'À jour',
                joinDate: '2024-08-15',
                lastActivity: '2024-09-14'
            },
            {
                id: 'ousmane-sarr',
                name: 'Ousmane Sarr',
                role: 'Membre',
                status: 'Actif',
                presence: 76,
                phone: '+221 78 890 12 34',
                email: 'ousmane.sarr@email.com',
                contributions: 'En retard',
                joinDate: '2023-09-03',
                lastActivity: '2024-09-10'
            }
        ];

        // Recherche et filtres en temps réel
        function filterMembers() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const roleFilter = document.getElementById('roleFilter').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
            const presenceFilter = document.getElementById('presenceFilter').value.toLowerCase();
            
            const memberCards = document.querySelectorAll('.member-card');
            
            memberCards.forEach(card => {
                const memberName = card.querySelector('h3').textContent.toLowerCase();
                const memberRole = card.querySelector('p').textContent.toLowerCase();
                const memberStatus = card.getAttribute('data-status');
                const memberPresence = parseInt(card.getAttribute('data-presence'));
                
                let show = true;
                
                // Filtre par recherche
                if (searchTerm && !memberName.includes(searchTerm)) {
                    show = false;
                }
                
                // Filtre par rôle
                if (roleFilter && !memberRole.includes(roleFilter)) {
                    show = false;
                }
                
                // Filtre par statut
                if (statusFilter && memberStatus !== statusFilter) {
                    show = false;
                }
                
                // Filtre par présence
                if (presenceFilter) {
                    switch(presenceFilter) {
                        case 'excellent':
                            if (memberPresence < 90) show = false;
                            break;
                        case 'bon':
                            if (memberPresence < 70 || memberPresence >= 90) show = false;
                            break;
                        case 'moyen':
                            if (memberPresence < 50 || memberPresence >= 70) show = false;
                            break;
                        case 'faible':
                            if (memberPresence >= 50) show = false;
                            break;
                    }
                }
                
                card.style.display = show ? 'block' : 'none';
            });
            
            updateMemberCount();
        }

        // Mettre à jour le compteur de membres
        function updateMemberCount() {
            const visibleCards = document.querySelectorAll('.member-card[style*="block"], .member-card:not([style*="none"])');
            const totalCount = document.querySelectorAll('.member-card').length;
            const visibleCount = visibleCards.length;
            
            // Mettre à jour les statistiques
            document.querySelector('.text-3xl.font-bold.text-white').textContent = visibleCount;
        }

        // Effacer tous les filtres
        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('roleFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('presenceFilter').value = '';
            filterMembers();
        }

        // Variables globales
        let currentView = 'grid';
        let currentMember = null;

        // Voir les détails d'un membre
        function viewMemberDetails(memberId) {
            const member = membersData.find(m => m.id === memberId);
            if (member) {
                currentMember = member;
                
                // Remplir les données du modal
                document.getElementById('memberInitials').textContent = member.name.split(' ').map(n => n[0]).join('');
                document.getElementById('memberName').textContent = member.name;
                document.getElementById('memberRole').textContent = member.role;
                document.getElementById('memberFullName').textContent = member.name;
                document.getElementById('memberPhone').textContent = member.phone;
                document.getElementById('memberEmail').textContent = member.email;
                document.getElementById('memberRoleDetail').textContent = member.role;
                document.getElementById('memberStatus').textContent = member.status;
                document.getElementById('memberJoinDate').textContent = new Date(member.joinDate).toLocaleDateString('fr-FR', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                
                // Ajuster la couleur du statut
                const statusElement = document.getElementById('memberStatus');
                statusElement.className = 'px-3 py-1 text-sm rounded-full border';
                switch(member.status.toLowerCase()) {
                    case 'actif':
                        statusElement.classList.add('bg-green-500/20', 'text-green-400', 'border-green-500/30');
                        break;
                    case 'en retard':
                        statusElement.classList.add('bg-orange-500/20', 'text-orange-400', 'border-orange-500/30');
                        break;
                    case 'inactif':
                        statusElement.classList.add('bg-red-500/20', 'text-red-400', 'border-red-500/30');
                        break;
                    case 'nouveau':
                        statusElement.classList.add('bg-blue-500/20', 'text-blue-400', 'border-blue-500/30');
                        break;
                }
                
                // Ouvrir le modal
                document.getElementById('memberDetailsModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        // Fermer le modal de détails
        function closeMemberDetails() {
            document.getElementById('memberDetailsModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Modifier un membre
        function editMember(memberId) {
            const member = membersData.find(m => m.id === memberId);
            if (member) {
                currentMember = member;
                
                // Remplir le formulaire
                const nameParts = member.name.split(' ');
                document.getElementById('editFirstName').value = nameParts[0] || '';
                document.getElementById('editLastName').value = nameParts.slice(1).join(' ') || '';
                document.getElementById('editPhone').value = member.phone;
                document.getElementById('editEmail').value = member.email;
                document.getElementById('editRole').value = member.role.toLowerCase();
                document.getElementById('editStatus').value = member.status.toLowerCase();
                
                // Ouvrir le modal
                document.getElementById('editMemberModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        // Fermer le modal de modification
        function closeEditMember() {
            document.getElementById('editMemberModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Modifier depuis les détails
        function editMemberFromDetails() {
            closeMemberDetails();
            editMember(currentMember.id);
        }

        // Ajouter un nouveau membre
        function addNewMember() {
            alert('Ajout d\'un nouveau membre - Fonctionnalité à implémenter');
        }

        // Exporter les membres
        function exportMembers() {
            const format = prompt('Choisir le format d\'export:\n1. PDF\n2. Excel\n\nEntrez 1 ou 2:');
            if (format === '1') {
                alert('Export PDF en cours...\n\nFonctionnalité à implémenter avec une bibliothèque PDF');
            } else if (format === '2') {
                alert('Export Excel en cours...\n\nFonctionnalité à implémenter avec une bibliothèque Excel');
            }
        }

        // Fonction supprimée - maintenant gérée par Vue.js

        // Changer vers la vue grille
        function switchToGridView() {
            currentView = 'grid';
            const container = document.getElementById('membersGrid');
            
            if (!container) {
                console.error('❌ Conteneur membersGrid non trouvé');
                return;
            }
            
            container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6';
            
            // Supprimer l'en-tête du tableau s'il existe
            const header = container.querySelector('.member-table-row');
            if (header) {
                header.remove();
            }
            
            // Restaurer les cartes originales
            const cards = document.querySelectorAll('.member-card, .member-list-item, .member-table-row');
            cards.forEach(card => {
                // Restaurer la classe et le contenu original
                card.className = 'member-card rounded-2xl p-6 card-hover';
                
                // Restaurer le contenu original basé sur les données extraites
                const nameElement = card.querySelector('h3');
                const name = nameElement ? nameElement.textContent.trim() : 'Membre';
                const initials = name.split(' ').map(n => n[0]).join('').toUpperCase();
                
                card.innerHTML = `
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">${initials}</span>
                        </div>
                        <div class="status-badge px-3 py-1 bg-green-500/20 text-green-400 text-xs rounded-full border border-green-500/30">
                            Actif
                        </div>
                    </div>
                    
                    <h3 class="text-white font-bold text-lg mb-1">${name}</h3>
                    <p class="text-white/70 text-sm mb-2">Choriste</p>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-white/60 text-xs">Présence</span>
                            <span class="text-blue-400 font-semibold text-sm">95%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/60 text-xs">Activités</span>
                            <span class="text-green-400 font-semibold text-sm">12</span>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <button class="flex-1 px-3 py-2 bg-white/10 text-white text-xs rounded-lg hover:bg-white/20 transition-all duration-300">
                            Voir
                        </button>
                        <button class="flex-1 px-3 py-2 bg-primary-500/20 text-primary-400 text-xs rounded-lg hover:bg-primary-500/30 transition-all duration-300">
                            Modifier
                        </button>
                    </div>
                `;
            });
            
            console.log('✅ Vue Grille activée');
        }

        // Changer vers la vue liste
        function switchToListView() {
            currentView = 'list';
            const container = document.getElementById('membersGrid');
            
            if (!container) {
                console.error('❌ Conteneur membersGrid non trouvé');
                return;
            }
            
            container.className = 'flex flex-col space-y-4';
            
            // Convertir les cartes en format liste
            const cards = document.querySelectorAll('.member-card');
            cards.forEach(card => {
                // Extraire les données de la carte de manière sécurisée
                const nameElement = card.querySelector('h3');
                const roleElement = card.querySelector('p');
                const statusElement = card.querySelector('.status-badge');
                const presenceElement = card.querySelector('.text-blue-400');
                
                const name = nameElement ? nameElement.textContent.trim() : 'Membre';
                const role = roleElement ? roleElement.textContent.trim() : 'Rôle';
                const status = statusElement ? statusElement.textContent.trim() : 'Statut';
                const presence = presenceElement ? presenceElement.textContent.trim() : '0%';
                
                // Créer les initiales
                const initials = name.split(' ').map(n => n[0]).join('').toUpperCase();
                
                // Transformer la carte en élément de liste
                card.className = 'member-list-item flex items-center justify-between p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-all duration-300';
                card.innerHTML = `
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">${initials}</span>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-lg">${name}</h3>
                            <p class="text-white/70 text-sm">${role}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-center">
                            <div class="text-white font-bold">${presence}</div>
                            <div class="text-white/60 text-xs">Présence</div>
                        </div>
                        <div class="status-badge px-3 py-1 bg-green-500/20 text-green-400 text-xs rounded-full border border-green-500/30">
                            ${status}
                        </div>
                        <div class="flex space-x-2">
                            <button class="text-blue-400 hover:text-blue-300 transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-400 hover:text-red-300 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            
            console.log('✅ Vue Liste activée');
        }

        // Changer vers la vue tableau
        function switchToTableView() {
            currentView = 'table';
            const container = document.getElementById('membersGrid');
            
            if (!container) {
                console.error('❌ Conteneur membersGrid non trouvé');
                return;
            }
            
            container.className = 'space-y-2';
            
            // Créer l'en-tête du tableau
            const header = document.createElement('div');
            header.className = 'member-table-row grid grid-cols-6 gap-4 p-4 bg-white/10 rounded-xl font-semibold text-white';
            header.innerHTML = `
                <div>Membre</div>
                <div>Rôle</div>
                <div>Statut</div>
                <div>Présence</div>
                <div>Contact</div>
                <div>Actions</div>
            `;
            container.insertBefore(header, container.firstChild);
            
            // Convertir les cartes en format tableau
            const cards = document.querySelectorAll('.member-card');
            cards.forEach(card => {
                // Extraire les données de la carte de manière sécurisée
                const nameElement = card.querySelector('h3');
                const roleElement = card.querySelector('p');
                const statusElement = card.querySelector('.status-badge');
                const presenceElement = card.querySelector('.text-blue-400');
                
                const name = nameElement ? nameElement.textContent.trim() : 'Membre';
                const role = roleElement ? roleElement.textContent.trim() : 'Rôle';
                const status = statusElement ? statusElement.textContent.trim() : 'Statut';
                const presence = presenceElement ? presenceElement.textContent.trim() : '0%';
                
                // Créer les initiales
                const initials = name.split(' ').map(n => n[0]).join('').toUpperCase();
                
                // Transformer la carte en ligne de tableau
                card.className = 'member-table-row grid grid-cols-6 gap-4 p-4 items-center bg-white/5 rounded-xl hover:bg-white/10 transition-all duration-300';
                    card.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">${initials}</span>
                            </div>
                        <span class="text-white font-medium">${name}</span>
                            </div>
                    <div class="text-white/80">${role}</div>
                    <div class="text-white/80">${status}</div>
                    <div class="text-white/80">${presence}</div>
                    <div class="text-white/80">Contact</div>
                        <div class="flex space-x-2">
                        <button class="text-blue-400 hover:text-blue-300 transition-colors">
                            <i class="fas fa-edit"></i>
                            </button>
                        <button class="text-red-400 hover:text-red-300 transition-colors">
                            <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
            });
            
            console.log('✅ Vue Tableau activée');
        }

        // Fonction pour basculer entre les onglets
        function switchTab(tabName) {
            // Masquer tous les contenus d'onglets
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Désactiver tous les boutons d'onglets
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });
            
            // Activer l'onglet sélectionné
            document.getElementById(`tab-${tabName}`).classList.add('active');
            document.querySelector(`[onclick="switchTab('${tabName}')"]`).classList.add('active');
        }

        // Statistiques avancées
        function showAdvancedStats() {
            const stats = {
                totalMembers: membersData.length,
                activeMembers: membersData.filter(m => m.status === 'Actif').length,
                newMembers: membersData.filter(m => m.status === 'Nouveau').length,
                averagePresence: Math.round(membersData.reduce((sum, m) => sum + m.presence, 0) / membersData.length),
                membersUpToDate: membersData.filter(m => m.contributions === 'À jour').length,
                membersInDelay: membersData.filter(m => m.contributions === 'En retard').length
            };
            
            alert(`Statistiques Avancées:\n\n` +
                  `Total Membres: ${stats.totalMembers}\n` +
                  `Membres Actifs: ${stats.activeMembers}\n` +
                  `Nouveaux Membres: ${stats.newMembers}\n` +
                  `Présence Moyenne: ${stats.averagePresence}%\n` +
                  `Cotisations à Jour: ${stats.membersUpToDate}\n` +
                  `Cotisations en Retard: ${stats.membersInDelay}`);
        }

        // Actions en lot
        function bulkActions() {
            const action = prompt('Actions en lot:\n1. Envoyer SMS à tous\n2. Envoyer Email à tous\n3. Marquer présence\n4. Exporter sélection\n\nEntrez 1, 2, 3 ou 4:');
            
            switch(action) {
                case '1':
                    alert('Envoi SMS en masse - Fonctionnalité à implémenter');
                    break;
                case '2':
                    alert('Envoi Email en masse - Fonctionnalité à implémenter');
                    break;
                case '3':
                    alert('Marquage de présence en masse - Fonctionnalité à implémenter');
                    break;
                case '4':
                    alert('Export de sélection - Fonctionnalité à implémenter');
                    break;
            }
        }

        // Exporter l'historique du membre
        function exportMemberHistory() {
            if (currentMember) {
                alert(`Export PDF de l'historique de ${currentMember.name} en cours...\n\nFonctionnalité à implémenter avec une bibliothèque PDF`);
            }
        }

        // Envoyer un SMS au membre
        function sendMessageToMember() {
            if (currentMember) {
                const message = prompt(`Envoyer un SMS à ${currentMember.name} (${currentMember.phone}):\n\nEntrez votre message:`);
                if (message && message.trim()) {
                    alert(`SMS envoyé à ${currentMember.name}:\n\n"${message}"\n\nFonctionnalité à implémenter avec une API SMS`);
                }
            }
        }

        // Gestion du formulaire de modification
        document.getElementById('editMemberForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (currentMember) {
                // Récupérer les nouvelles valeurs
                const firstName = document.getElementById('editFirstName').value;
                const lastName = document.getElementById('editLastName').value;
                const phone = document.getElementById('editPhone').value;
                const email = document.getElementById('editEmail').value;
                const role = document.getElementById('editRole').value;
                const status = document.getElementById('editStatus').value;
                
                // Mettre à jour les données
                currentMember.name = `${firstName} ${lastName}`.trim();
                currentMember.phone = phone;
                currentMember.email = email;
                currentMember.role = role.charAt(0).toUpperCase() + role.slice(1);
                currentMember.status = status.charAt(0).toUpperCase() + status.slice(1);
                
                // Mettre à jour l'affichage
                updateMemberDisplay(currentMember);
                
                // Fermer le modal
                closeEditMember();
                
                // Afficher un message de succès
                alert(`✅ Membre ${currentMember.name} modifié avec succès !`);
                
                console.log('✅ Membre modifié:', currentMember);
            }
        });

        // Mettre à jour l'affichage d'un membre
        function updateMemberDisplay(member) {
            // Trouver la carte du membre
            const memberCards = document.querySelectorAll('.member-card');
            memberCards.forEach(card => {
                const memberName = card.querySelector('h3').textContent;
                if (memberName === member.name) {
                    // Mettre à jour les informations affichées
                    card.querySelector('h3').textContent = member.name;
                    card.querySelector('p').textContent = member.role;
                    card.querySelector('.text-white\\/60.text-xs').textContent = member.phone;
                    
                    // Mettre à jour le statut
                    const statusBadge = card.querySelector('.status-badge');
                    statusBadge.textContent = member.status;
                    statusBadge.className = 'status-badge px-3 py-1 text-xs rounded-full border';
                    
                    switch(member.status.toLowerCase()) {
                        case 'actif':
                            statusBadge.classList.add('bg-green-500/20', 'text-green-400', 'border-green-500/30');
                            break;
                        case 'en retard':
                            statusBadge.classList.add('bg-orange-500/20', 'text-orange-400', 'border-orange-500/30');
                            break;
                        case 'inactif':
                            statusBadge.classList.add('bg-red-500/20', 'text-red-400', 'border-red-500/30');
                            break;
                        case 'nouveau':
                            statusBadge.classList.add('bg-blue-500/20', 'text-blue-400', 'border-blue-500/30');
                            break;
                    }
                    
                    // Mettre à jour les attributs data
                    card.setAttribute('data-name', member.name.toLowerCase());
                    card.setAttribute('data-role', member.role.toLowerCase());
                    card.setAttribute('data-status', member.status.toLowerCase());
                }
            });
        }

        // Événements
        document.getElementById('searchInput').addEventListener('input', filterMembers);
        document.getElementById('roleFilter').addEventListener('change', filterMembers);
        document.getElementById('statusFilter').addEventListener('change', filterMembers);
        document.getElementById('presenceFilter').addEventListener('change', filterMembers);

        // Raccourcis clavier
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey) {
                switch(e.key) {
                    case 'n':
                        e.preventDefault();
                        openAddMemberModal();
                        break;
                    case 'e':
                        e.preventDefault();
                        exportMembers();
                        break;
                    case 'f':
                        e.preventDefault();
                        document.getElementById('searchInput').focus();
                        break;
                }
            }
        });

        // Application Vue.js déjà initialisée dans le head
    </script>
</body>
</html>
