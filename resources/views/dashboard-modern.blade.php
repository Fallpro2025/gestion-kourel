<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Gestion Kourel') }} - Dashboard Moderne</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
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
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
        
        .loading-skeleton {
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        .floating-card {
            animation: float 3s ease-in-out infinite;
        }
        
        .pulse-glow {
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        .status-indicator {
            position: relative;
        }
        
        .status-indicator::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: inherit;
            background: inherit;
            filter: blur(8px);
            opacity: 0.3;
            z-index: -1;
        }
        
        .progress-bar {
            background: linear-gradient(90deg, #3b82f6, #10b981);
            border-radius: 10px;
            height: 8px;
            transition: width 0.5s ease;
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
        
        @media (max-width: 768px) {
            .sidebar-mobile {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar-mobile.open {
                transform: translateX(0);
            }
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
                        <p class="text-xs text-white/70">Dashboard Moderne</p>
                    </div>
                </div>
                
                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="/" class="nav-item flex items-center px-4 py-3 text-white rounded-xl bg-white/20 hover:bg-white/30 transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="/membres" class="nav-item flex items-center px-4 py-3 text-white/80 rounded-xl hover:bg-white/20 hover:text-white transition-all duration-300">
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
                            <h2 class="text-2xl font-bold text-white">Dashboard Moderne</h2>
                            <p class="text-white/70">Vue d'ensemble de votre dahira/kourel</p>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <button class="relative p-2 text-white/80 hover:text-white hover:bg-white/20 rounded-xl transition-all duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828z"></path>
                                </svg>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            
                            <!-- Settings -->
                            <button class="p-2 text-white/80 hover:text-white hover:bg-white/20 rounded-xl transition-all duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Dashboard Content -->
            <main class="p-6">
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/70 text-sm font-medium">Total Membres</p>
                                <p class="text-3xl font-bold text-white mt-2">24</p>
                                <p class="text-green-400 text-sm mt-1">+12% ce mois</p>
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
                                <p class="text-white/70 text-sm font-medium">Cotisations</p>
                                <p class="text-3xl font-bold text-white mt-2">2.4M</p>
                                <p class="text-green-400 text-sm mt-1">+8% ce mois</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-secondary-500 to-secondary-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/70 text-sm font-medium">Activités</p>
                                <p class="text-3xl font-bold text-white mt-2">18</p>
                                <p class="text-blue-400 text-sm mt-1">3 cette semaine</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-accent-purple to-accent-rose rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/70 text-sm font-medium">Présence</p>
                                <p class="text-3xl font-bold text-white mt-2">94%</p>
                                <p class="text-green-400 text-sm mt-1">+2% cette semaine</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-accent-orange to-accent-rose rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts and Analytics -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Présence Chart -->
                    <div class="metric-card rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-white">Statistiques de Présence</h3>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 bg-white/20 text-white text-sm rounded-lg hover:bg-white/30 transition-all duration-300">7j</button>
                                <button class="px-3 py-1 bg-primary-500 text-white text-sm rounded-lg">30j</button>
                                <button class="px-3 py-1 bg-white/20 text-white text-sm rounded-lg hover:bg-white/30 transition-all duration-300">90j</button>
                            </div>
                        </div>
                        <div class="h-64 flex items-end justify-between space-x-2">
                            <div class="flex flex-col items-center">
                                <div class="w-8 bg-gradient-to-t from-primary-500 to-primary-400 rounded-t-lg mb-2" style="height: 60%"></div>
                                <span class="text-white/70 text-xs">Lun</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-8 bg-gradient-to-t from-primary-500 to-primary-400 rounded-t-lg mb-2" style="height: 80%"></div>
                                <span class="text-white/70 text-xs">Mar</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-8 bg-gradient-to-t from-primary-500 to-primary-400 rounded-t-lg mb-2" style="height: 45%"></div>
                                <span class="text-white/70 text-xs">Mer</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-8 bg-gradient-to-t from-primary-500 to-primary-400 rounded-t-lg mb-2" style="height: 90%"></div>
                                <span class="text-white/70 text-xs">Jeu</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-8 bg-gradient-to-t from-primary-500 to-primary-400 rounded-t-lg mb-2" style="height: 75%"></div>
                                <span class="text-white/70 text-xs">Ven</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-8 bg-gradient-to-t from-secondary-500 to-secondary-400 rounded-t-lg mb-2" style="height: 100%"></div>
                                <span class="text-white/70 text-xs">Sam</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-8 bg-gradient-to-t from-secondary-500 to-secondary-400 rounded-t-lg mb-2" style="height: 85%"></div>
                                <span class="text-white/70 text-xs">Dim</span>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between text-sm">
                            <span class="text-white/70">Moyenne: 94%</span>
                            <span class="text-green-400">+2% vs semaine dernière</span>
                        </div>
                    </div>
                    
                    <!-- Cotisations Chart -->
                    <div class="metric-card rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-white">Recouvrement Cotisations</h3>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-white/70 text-sm">Payées</span>
                                <div class="w-3 h-3 bg-yellow-500 rounded-full ml-4"></div>
                                <span class="text-white/70 text-sm">En retard</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-white/80 text-sm">Cotisation Magal 2025</span>
                                <span class="text-white font-medium">95%</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div class="progress-bar" style="width: 95%"></div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-white/80 text-sm">Cotisation Gamou</span>
                                <span class="text-white font-medium">87%</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div class="progress-bar" style="width: 87%"></div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-white/80 text-sm">Cotisation Promokhane</span>
                                <span class="text-white font-medium">92%</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div class="progress-bar" style="width: 92%"></div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-white/80 text-sm">Cotisation Générale</span>
                                <span class="text-white font-medium">78%</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div class="progress-bar" style="width: 78%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activities and Alerts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Alertes Récentes -->
                    <div class="metric-card rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-white">Alertes Récentes</h3>
                            <span class="px-3 py-1 bg-red-500/20 text-red-400 text-sm rounded-full">3 nouvelles</span>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3 p-3 bg-red-500/10 rounded-xl border border-red-500/20">
                                <div class="w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                                <div class="flex-1">
                                    <p class="text-white font-medium">Retard de paiement</p>
                                    <p class="text-white/70 text-sm">Fatou Diop - Cotisation Magal 2025</p>
                                    <p class="text-white/50 text-xs">Il y a 2 heures</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3 p-3 bg-orange-500/10 rounded-xl border border-orange-500/20">
                                <div class="w-2 h-2 bg-orange-500 rounded-full mt-2"></div>
                                <div class="flex-1">
                                    <p class="text-white font-medium">Absence non justifiée</p>
                                    <p class="text-white/70 text-sm">Moustapha Fall - Répétition Chorale</p>
                                    <p class="text-white/50 text-xs">Il y a 4 heures</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3 p-3 bg-blue-500/10 rounded-xl border border-blue-500/20">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                <div class="flex-1">
                                    <p class="text-white font-medium">Nouvelle activité</p>
                                    <p class="text-white/70 text-sm">Répétition Chorale - Dimanche 15h</p>
                                    <p class="text-white/50 text-xs">Il y a 6 heures</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Activités Récentes -->
                    <div class="metric-card rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-white">Activités Récentes</h3>
                            <button class="text-primary-400 hover:text-primary-300 text-sm font-medium">Voir tout</button>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3 p-3 bg-green-500/10 rounded-xl border border-green-500/20">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-white font-medium">Goudi Aldiouma</p>
                                    <p class="text-white/70 text-sm">Dimanche 10 Septembre - 15h00</p>
                                    <p class="text-green-400 text-xs">✓ 24/24 présents</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3 p-3 bg-purple-500/10 rounded-xl border border-purple-500/20">
                                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-white font-medium">Préparation Magal Touba</p>
                                    <p class="text-white/70 text-sm">Samedi 9 Septembre - 10h00</p>
                                    <p class="text-blue-400 text-xs">✓ 22/24 présents</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3 p-3 bg-yellow-500/10 rounded-xl border border-yellow-500/20">
                                <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-white font-medium">Réunion de planification</p>
                                    <p class="text-white/70 text-sm">Vendredi 8 Septembre - 19h00</p>
                                    <p class="text-yellow-400 text-xs">✓ 20/24 présents</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script>
        // Animation et interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Animation des cartes au chargement
            const cards = document.querySelectorAll('.metric-card');
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
            const progressBars = document.querySelectorAll('.progress-bar');
            setTimeout(() => {
                progressBars.forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 500);
                });
            }, 1000);
            
            // Effet de survol sur les éléments de navigation
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(5px)';
                });
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });
            
            // Animation des indicateurs de statut
            const statusIndicators = document.querySelectorAll('.status-indicator');
            statusIndicators.forEach(indicator => {
                setInterval(() => {
                    indicator.style.opacity = '0.5';
                    setTimeout(() => {
                        indicator.style.opacity = '1';
                    }, 200);
                }, 2000);
            });
            
            console.log('🚀 Dashboard Moderne Gestion Kourel chargé avec succès !');
            console.log('📊 Version Laravel:', '{{ app()->version() }}');
            console.log('🌍 Environnement:', '{{ app()->environment() }}');
            console.log('⏰ Timestamp:', new Date().toISOString());
        });
        
        // Fonction pour mettre à jour les statistiques en temps réel
        function updateStats() {
            // Simulation de mise à jour des données
            const stats = document.querySelectorAll('.metric-card .text-3xl');
            stats.forEach(stat => {
                const currentValue = parseInt(stat.textContent.replace(/[^\d]/g, ''));
                const newValue = currentValue + Math.floor(Math.random() * 3) - 1;
                stat.textContent = stat.textContent.replace(/\d+/, newValue);
            });
        }
        
        // Mise à jour toutes les 30 secondes
        setInterval(updateStats, 30000);
    </script>
</body>
</html>
