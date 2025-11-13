import React, { useState } from 'react';

const CardNav = ({ user, isAuthenticated, routes }) => {
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

    const toggleMobileMenu = () => {
        setIsMobileMenuOpen(!isMobileMenuOpen);
    };

    return (
        <nav className="bg-gradient-to-r from-green-600 via-green-700 to-green-800 shadow-xl relative overflow-hidden">
            {/* Animated background elements */}
            <div className="absolute inset-0 bg-gradient-to-r from-green-500/30 to-green-700/30 animate-pulse"></div>
            <div className="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-transparent via-green-600/20 to-transparent animate-pulse delay-1000"></div>
            <div className="absolute inset-0 bg-gradient-to-r from-green-400/10 via-green-600/10 to-green-800/10 animate-pulse delay-500"></div>

            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div className="flex justify-between h-16">
                    {/* Left: Logo */}
                    <div className="flex items-center">
                        <div className="shrink-0 flex items-center">
                            <div className="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2 border border-white/20 hover:bg-white/20 transition-all duration-300">
                                <a href={routes.home} className="text-white font-bold text-xl flex items-center space-x-2 hover:rotate-12 transition-transform duration-300">
                                    <span className="text-2xl">🗑️</span>
                                    <span>Garbage Collection System</span>
                                </a>
                            </div>
                        </div>
                    </div>



                    {/* Right: Other Navigation Links and User Info */}
                    <div className="flex items-center space-x-4">
                        {/* Navigation Links Cards */}
                        <div className="hidden space-x-4 sm:flex">
                            {isAuthenticated && (
                                <>
                                    {user.role?.name === 'Admin' && (
                                        <>
                                            <div className="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20 hover:bg-white/20 hover:scale-105 transition-all duration-300">
                                                <a href={routes.adminDashboard}
                                                    className="text-white hover:text-green-200 text-sm font-medium transition-colors duration-300 flex items-center space-x-1">
                                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                    </svg>
                                                    <span>Dashboard</span>
                                                </a>
                                            </div>
                                            <div className="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20 hover:bg-white/20 hover:scale-105 transition-all duration-300">
                                                <a href={routes.adminAnalytics}
                                                    className="text-white hover:text-green-200 text-sm font-medium transition-colors duration-300 flex items-center space-x-1">
                                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                    </svg>
                                                    <span>Analytics</span>
                                                </a>
                                            </div>
                                        </>
                                    )}

                                    {user.role?.name === 'Collector' && (
                                        <div className="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20 hover:bg-white/20 hover:scale-105 transition-all duration-300">
                                            <a href={routes.collectorDashboard}
                                                className="text-white hover:text-green-200 text-sm font-medium transition-colors duration-300 flex items-center space-x-1">
                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                <span>Collections</span>
                                            </a>
                                        </div>
                                    )}

                                    {user.role?.name === 'Public' && (
                                        <div className="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20 hover:bg-white/20 hover:scale-105 transition-all duration-300">
                                            <a href={routes.publicDashboard}
                                                className="text-white hover:text-green-200 text-sm font-medium transition-colors duration-300 flex items-center space-x-1">
                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                                <span>Report Issue</span>
                                            </a>
                                        </div>
                                    )}
                                </>
                            )}
                        </div>

                        {/* User Info */}
                        {isAuthenticated ? (
                            <div className="flex items-center space-x-4">
                                <div className="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2 border border-white/20">
                                    <span className="text-white text-sm">{user.name}</span>
                                </div>
                                <form method="POST" action={routes.logout} className="inline">
                                    <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]').getAttribute('content')} />
                                    <button type="submit"
                                        className="bg-red-500/20 backdrop-blur-sm hover:bg-red-500/30 text-white px-4 py-2 rounded-lg border border-red-400/30 transition-all duration-300">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        ) : (
                            <div className="flex items-center space-x-4">
                            </div>
                        )}
                    </div>

                    {/* Mobile menu button */}
                    <div className="-mr-2 flex items-center sm:hidden">
                        <button type="button"
                            onClick={toggleMobileMenu}
                            className="bg-white/10 backdrop-blur-sm inline-flex items-center justify-center p-2 rounded-lg text-white hover:text-green-200 hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white border border-white/20 transition-all duration-300"
                            aria-controls="mobile-menu" aria-expanded={isMobileMenuOpen}>
                            <span className="sr-only">Open main menu</span>
                            <svg className="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {/* Mobile menu */}
            {isMobileMenuOpen && (
                <div className="sm:hidden absolute top-16 left-0 right-0 bg-green-600/95 backdrop-blur-sm border-t border-white/20 z-50">
                    <div className="px-2 pt-2 pb-3 space-y-2">
                        {isAuthenticated && (
                            <>
                                {user.role?.name === 'Admin' && (
                                    <>
                                        <div className="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20">
                                            <a href={routes.adminDashboard}
                                                className="text-white hover:text-green-200 block text-base font-medium transition-colors duration-300">
                                                Dashboard
                                            </a>
                                        </div>
                                        <div className="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20">
                                            <a href={routes.adminAnalytics}
                                                className="text-white hover:text-green-200 block text-base font-medium transition-colors duration-300">
                                                Analytics
                                            </a>
                                        </div>
                                    </>
                                )}

                                {user.role?.name === 'Collector' && (
                                    <div className="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20">
                                        <a href={routes.collectorDashboard}
                                            className="text-white hover:text-green-200 block text-base font-medium transition-colors duration-300">
                                            Collections
                                        </a>
                                    </div>
                                )}

                                {user.role?.name === 'Public' && (
                                    <div className="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20">
                                        <a href={routes.publicDashboard}
                                            className="text-white hover:text-green-200 block text-base font-medium transition-colors duration-300">
                                            Report Issue
                                        </a>
                                    </div>
                                )}

                                <div className="border-t border-green-500 pt-4 pb-3">
                                    <div className="flex items-center px-5 mb-3">
                                        <div className="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20">
                                            <div className="text-base font-medium leading-none text-white">{user.name}</div>
                                        </div>
                                    </div>
                                    <div className="space-y-2">
                                        <form method="POST" action={routes.logout}>
                                            <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]').getAttribute('content')} />
                                            <button type="submit"
                                                className="bg-red-500/20 backdrop-blur-sm hover:bg-red-500/30 text-white block px-3 py-2 rounded-lg text-base font-medium w-full text-left border border-red-400/30 transition-all duration-300">
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </>
                        )}

                        {!isAuthenticated && (
                            <>
                                <div className="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20">
                                    <a href={routes.login}
                                        className="text-white hover:text-green-200 block text-base font-medium transition-colors duration-300">
                                        Login
                                    </a>
                                </div>
                            </>
                        )}
                    </div>
                </div>
            )}
        </nav>
    );
};

export default CardNav;
