import React, { useState, useEffect } from 'react';

const Welcome = ({ user, isAuthenticated, routes }) => {
    const [stats, setStats] = useState({
        totalBins: 0,
        activeAlerts: 0,
        collectionsToday: 0,
        efficiencyRate: 0
    });

    useEffect(() => {
        // Fetch system stats
        fetch('/api/admin/analytics/bin-summary')
            .then(response => response.json())
            .then(data => {
                setStats(prev => ({ ...prev, totalBins: data.total_bins || 5 }));
            })
            .catch(error => console.log('Stats not available'));

        fetch('/api/admin/analytics/alert-stats')
            .then(response => response.json())
            .then(data => {
                setStats(prev => ({ ...prev, activeAlerts: data.alerts_by_status?.open || 2 }));
            })
            .catch(error => console.log('Stats not available'));

        fetch('/api/admin/analytics/collections/today')
            .then(response => response.json())
            .then(data => {
                setStats(prev => ({ ...prev, collectionsToday: data.collections_today || 3 }));
            })
            .catch(error => console.log('Stats not available'));

        fetch('/api/admin/analytics/collection/efficiency')
            .then(response => response.json())
            .then(data => {
                setStats(prev => ({ ...prev, efficiencyRate: Math.round(data.overall_efficiency || 60) }));
            })
            .catch(error => console.log('Stats not available'));
    }, []);

    return (
        <div className="py-12">
            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 bg-white border-b border-gray-200">
                        <div className="text-center">
                            <h1 className="text-4xl font-bold text-gray-900 mb-4">
                                Welcome to the Garbage Collection System
                            </h1>
                            <p className="text-lg text-gray-600 mb-8">
                                Efficient waste management for a cleaner community
                            </p>

                            {isAuthenticated ? (
                                <div className="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                                    <h2 className="text-2xl font-semibold text-green-800 mb-4">
                                        Welcome back, {user.name}!
                                    </h2>
                                    <p className="text-green-700 mb-4">
                                        You are logged in as a <strong>{user.role?.name}</strong>.
                                    </p>

                                    <div className="flex justify-center mb-4">
                                        {user.role?.name === 'Admin' && (
                                            <>
                                                <a href={routes.adminDashboard}
                                                    className="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                                                    Go to Admin Dashboard
                                                </a>
                                            </>
                                        )}

                                        {user.role?.name === 'Collector' && (
                                            <a href={routes.collectorDashboard}
                                                className="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                                                Manage Collections
                                            </a>
                                        )}

                                        {user.role?.name === 'Public' && (
                                            <a href={routes.publicDashboard}
                                                className="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                                                Report Issue
                                            </a>
                                        )}
                                    </div>
                                </div>
                            ) : (
                                <div className="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                                    <h2 className="text-2xl font-semibold text-blue-800 mb-4">
                                        Get Started
                                    </h2>
                                    <p className="text-blue-700 mb-4">
                                        Join our community to access personalized features and contribute to cleaner surroundings.
                                    </p>
                                    <div className="flex justify-center space-x-4">
                                        <a href={routes.login}
                                            className="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                                            Login
                                        </a>
                                        <a href={routes.register}
                                            className="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                                            Register
                                        </a>
                                    </div>
                                </div>
                            )}

                        </div>

                        {/* Stats Section */}
                        <div className="mt-12 bg-gray-50 rounded-lg p-8">
                            <h2 className="text-3xl font-bold text-gray-900 mb-8">System Overview</h2>
                            <div className="grid md:grid-cols-4 gap-6">
                                <div className="text-center">
                                    <div className="text-3xl font-bold text-green-600">{stats.totalBins}</div>
                                    <div className="text-gray-600">Total Bins</div>
                                </div>
                                <div className="text-center">
                                    <div className="text-3xl font-bold text-blue-600">{stats.activeAlerts}</div>
                                    <div className="text-gray-600">Active Alerts</div>
                                </div>
                                <div className="text-center">
                                    <div className="text-3xl font-bold text-purple-600">{stats.collectionsToday}</div>
                                    <div className="text-gray-600">Collections Today</div>
                                </div>
                                <div className="text-center">
                                    <div className="text-3xl font-bold text-orange-600">{stats.efficiencyRate}%</div>
                                    <div className="text-gray-600">Efficiency Rate</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Welcome;
