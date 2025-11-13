import React from 'react';

const Welcome = ({ user, isAuthenticated, routes }) => {

    return (
        <div className="py-12 bg-gradient-to-br from-green-50 via-blue-50 to-purple-50 min-h-screen">
            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {/* Hero Section */}
                <div className="text-center mb-16">
                    <div className="flex justify-center mb-6">
                        <div className="bg-green-100 p-4 rounded-full">
                            <svg className="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                    </div>
                    <h1 className="text-5xl font-bold text-gray-900 mb-6">
                        Welcome to the Trinidad Garbage Collection Management System
                    </h1>
                    <p className="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                        Revolutionizing waste management for a cleaner, greener, and more sustainable community in Trinidad.
                    </p>
                </div>

                {/* Purpose and Goals Section */}
                <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                    <div className="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-300">
                        <div className="bg-blue-100 p-3 rounded-full w-fit mb-4">
                            <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 className="text-2xl font-semibold text-gray-900 mb-4">Our Purpose</h3>
                        <p className="text-gray-600">
                            To provide an efficient, transparent, and user-friendly platform for managing waste collection services, ensuring timely pickups and reducing environmental impact through smart technology.
                        </p>
                    </div>

                    <div className="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-300">
                        <div className="bg-green-100 p-3 rounded-full w-fit mb-4">
                            <svg className="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 className="text-2xl font-semibold text-gray-900 mb-4">Our Goals</h3>
                        <ul className="text-gray-600 space-y-2">
                            <li>• Achieve 100% waste collection coverage</li>
                            <li>• Reduce response time to reports by 50%</li>
                            <li>• Promote community environmental awareness</li>
                            <li>• Implement sustainable waste management practices</li>
                        </ul>
                    </div>

                    <div className="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-300 md:col-span-2 lg:col-span-1">
                        <div className="bg-purple-100 p-3 rounded-full w-fit mb-4">
                            <svg className="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 className="text-2xl font-semibold text-gray-900 mb-4">Key Features</h3>
                        <ul className="text-gray-600 space-y-2">
                            <li>• Real-time bin monitoring</li>
                            <li>• Role-based dashboards</li>
                            <li>• Instant issue reporting</li>
                            <li>• Collection scheduling</li>
                            <li>• Analytics and insights</li>
                        </ul>
                    </div>
                </div>

                {/* Benefits Section */}
                <div className="bg-white rounded-xl shadow-lg p-8 mb-16">
                    <h2 className="text-3xl font-bold text-center text-gray-900 mb-8">Why Choose Our System?</h2>
                    <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div className="text-center">
                            <div className="bg-green-100 p-4 rounded-full w-fit mx-auto mb-4">
                                <svg className="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 className="font-semibold text-gray-900 mb-2">Efficiency</h4>
                            <p className="text-gray-600 text-sm">Streamlined processes for faster service delivery</p>
                        </div>
                        <div className="text-center">
                            <div className="bg-blue-100 p-4 rounded-full w-fit mx-auto mb-4">
                                <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <h4 className="font-semibold text-gray-900 mb-2">Reliability</h4>
                            <p className="text-gray-600 text-sm">Consistent and dependable waste collection services</p>
                        </div>
                        <div className="text-center">
                            <div className="bg-purple-100 p-4 rounded-full w-fit mx-auto mb-4">
                                <svg className="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <h4 className="font-semibold text-gray-900 mb-2">Community</h4>
                            <p className="text-gray-600 text-sm">Building stronger, cleaner neighborhoods together</p>
                        </div>
                        <div className="text-center">
                            <div className="bg-orange-100 p-4 rounded-full w-fit mx-auto mb-4">
                                <svg className="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <h4 className="font-semibold text-gray-900 mb-2">Innovation</h4>
                            <p className="text-gray-600 text-sm">Leveraging technology for better environmental outcomes</p>
                        </div>
                    </div>
                </div>

                {/* Authentication Section */}
                {isAuthenticated ? (
                    <div className="bg-green-50 border border-green-200 rounded-xl p-8 mb-8">
                        <h2 className="text-3xl font-semibold text-green-800 mb-6 text-center">
                            Welcome back, {user.name}!
                        </h2>
                        <p className="text-green-700 mb-6 text-center text-lg">
                            You are logged in as a <strong>{user.role?.name}</strong>.
                        </p>

                        <div className="flex justify-center">
                            {user.role?.name === 'Admin' && (
                                <a href={routes.adminDashboard}
                                    className="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 shadow-lg">
                                    Go to Admin Dashboard
                                </a>
                            )}

                            {user.role?.name === 'Collector' && (
                                <a href={routes.collectorDashboard}
                                    className="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 shadow-lg">
                                    Manage Collections
                                </a>
                            )}

                            {user.role?.name === 'Public' && (
                                <a href={routes.publicDashboard}
                                    className="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 shadow-lg">
                                    Report Issue
                                </a>
                            )}
                        </div>
                    </div>
                ) : (
                    <div className="bg-blue-50 border border-blue-200 rounded-xl p-8 mb-8">
                        <h2 className="text-3xl font-semibold text-blue-800 mb-6 text-center">
                            Join Our Community
                        </h2>
                        <p className="text-blue-700 mb-6 text-center text-lg">
                            Be part of the solution for a cleaner Trinidad. Access personalized features and contribute to sustainable waste management.
                        </p>
                        <div className="flex justify-center space-x-6">
                            <a href={routes.login}
                                className="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 shadow-lg">
                                Login
                            </a>
                            <a href={routes.register}
                                className="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 shadow-lg">
                                Register
                            </a>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
};

export default Welcome;
