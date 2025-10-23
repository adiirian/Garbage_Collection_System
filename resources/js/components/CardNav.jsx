import React, { useState } from 'react';

const CardNav = ({ user, isAuthenticated, routes }) => {
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

    const toggleMobileMenu = () => {
        setIsMobileMenuOpen(!isMobileMenuOpen);
    };

    return (
        <nav className="bg-gradient-to-r from-green-500 to-green-700 shadow-lg relative overflow-hidden">
            {/* Animated background elements */}
            <div className="absolute inset-0 bg-gradient-to-r from-green-400/20 to-green-600/20 animate-pulse"></div>
            <div className="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-transparent via-green-500/10 to-transparent animate-pulse delay-1000"></div>

            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div className="flex justify-between h-16">
                    <div className="flex">
                        {/* Logo Card */}
                        <div className="shrink-0 flex items-center">
                            <div className="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2 border border-white/20 hover:bg-white/20 transition-all duration-300">
                                <a href={routes.home} className="text-white font-bold text-xl flex items-center space-x-2">
                                    <span className="text-2xl">🗑️</span>
                                    <span>Garbage Collection System</span>
                                </a>
                            </div>
                        </div>

                        {/* Navigation Links Cards */}
                        <div className="hidden space-x-4 sm:-my-px sm:ml-10 sm:flex">
                            <div className="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20 hover:bg-white/20 transition-all duration-300">
                                <a href={routes.home}
                                    className="text-white hover:text-green-200 text-sm font-medium transition-colors duration-300">
                                    Home
                                </a>
                            </div>

                            {isAuthenticated && (
                                <>
                                    {user.role?.name === 'Admin' && (
                                        <>
                                            <div className="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20 hover:bg-white/20 transition-all duration-300">
                                                <a href={routes.adminDashboard}
                                                    className="text-white hover:text-green-200 text-sm font-medium transition-colors duration-300">
                                                    Dashboard
                                                </a>
                                            </div>
                                            <div className="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20 hover:bg-white/20 transition-all duration-300">
                                                <a href={routes.adminAnalytics}
                                                    className="text-white hover:text-green-200 text-sm font-medium transition-colors duration-300">
                                                    Analytics
                                                </a>
                                            </div>
                                        </>
                                    )}

                                    {user.role?.name === 'Collector' && (
                                        <div className="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20 hover:bg-white/20 transition-all duration-300">
                                            <a href={routes.collectorDashboard}
                                                className="text-white hover:text-green-200 text-sm font-medium transition-colors duration-300">
                                                Collections
                                            </a>
                                        </div>
                                    )}

                                    {user.role?.name === 'Public' && (
                                        <div className="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20 hover:bg-white/20 transition-all duration-300">
                                            <a href={routes.publicDashboard}
                                                className="text-white hover:text-green-200 text-sm font-medium transition-colors duration-300">
                                                Report Issue
                                            </a>
                                        </div>
                                    )}
                                </>
                            )}
                        </div>
                    </div>

                    {/* Right Side Cards */}
                    <div className="hidden sm:flex sm:items-center sm:ml-6">
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
                        <div className="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20">
                            <a href={routes.home}
                                className="text-white hover:text-green-200 block text-base font-medium transition-colors duration-300">
                                Home
                            </a>
                        </div>

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
