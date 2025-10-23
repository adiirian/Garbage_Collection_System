import React from 'react';

const Welcome = ({ user, isAuthenticated, routes }) => {

    return (
        <div className="py-12">
            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 bg-white border-b border-gray-200">
                        <div className="text-center">
                            <h1 className="text-4xl font-bold text-gray-900 mb-4">
                                Welcome to the Garbage Collection Management System
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
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Welcome;
