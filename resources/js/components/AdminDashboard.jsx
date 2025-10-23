import React, { useState, useEffect } from 'react';

const AdminDashboard = ({ bins, openAlerts, routes }) => {
    const [binData, setBinData] = useState(bins || []);
    const [alertData, setAlertData] = useState(openAlerts || []);
    const [todayCollections, setTodayCollections] = useState(0);

    useEffect(() => {
        // Fetch today's collections
        fetch('/api/admin/analytics/collections/today')
            .then(response => response.json())
            .then(data => {
                setTodayCollections(data.collections_today || 0);
            })
            .catch(error => console.log('Collections data not available'));

        // Fetch bin summary stats
        fetch('/api/admin/analytics/bins/summary')
            .then(response => response.json())
            .then(data => {
                setBinData(data);
            })
            .catch(error => console.log('Bin summary data not available'));
    }, []);

    return (
        <div className="py-12">
            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 bg-white border-b border-gray-200">
                        <div className="flex justify-between items-center mb-6">
                            <h1 className="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                            <a href={routes.adminAnalytics}
                                className="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                View Analytics
                            </a>
                        </div>

                        {/* System Overview */}
                        <div className="grid md:grid-cols-3 gap-6 mb-8">
                            <div className="bg-blue-50 border border-blue-200 rounded-lg p-6">
                                <h3 className="text-xl font-semibold text-blue-800 mb-2">Total Bins</h3>
                                <p className="text-3xl font-bold text-blue-600">{binData.length}</p>
                            </div>
                            <div className="bg-red-50 border border-red-200 rounded-lg p-6">
                                <h3 className="text-xl font-semibold text-red-800 mb-2">Open Alerts</h3>
                                <p className="text-3xl font-bold text-red-600">{alertData.length}</p>
                            </div>
                            <div className="bg-green-50 border border-green-200 rounded-lg p-6">
                                <h3 className="text-xl font-semibold text-green-800 mb-2">Collections Today</h3>
                                <p className="text-3xl font-bold text-green-600">{todayCollections}</p>
                            </div>
                        </div>

                        {/* Bins Table */}
                        <div className="mb-8">
                            <h2 className="text-2xl font-bold text-gray-900 mb-4">All Bins</h2>
                            <div className="overflow-x-auto">
                                <table className="min-w-full bg-white border border-gray-300">
                                    <thead>
                                        <tr className="bg-gray-50">
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {binData.map((bin) => (
                                            <tr key={bin.id}>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{bin.id}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{bin.name}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{bin.latitude}, {bin.longitude}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{bin.level}</td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                                        bin.level === 'empty' ? 'bg-green-100 text-green-800' :
                                                        bin.level === 'half' ? 'bg-yellow-100 text-yellow-800' :
                                                        bin.level === 'full' ? 'bg-red-100 text-red-800' :
                                                        'bg-red-100 text-red-800'
                                                    }`}>
                                                        {bin.level}
                                                    </span>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {/* Open Alerts */}
                        {alertData.length > 0 && (
                            <div>
                                <h2 className="text-2xl font-bold text-gray-900 mb-4">Open Alerts</h2>
                                <div className="space-y-4">
                                    {alertData.map((alert) => (
                                        <div key={alert.id} className="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                            <div className="flex justify-between items-start">
                                                <div>
                                                    <h3 className="text-lg font-semibold text-yellow-800">
                                                        Alert for Bin {alert.bin?.name || alert.bin_id}
                                                    </h3>
                                                    <p className="text-yellow-700">{alert.message}</p>
                                                    <p className="text-sm text-yellow-600 mt-1">
                                                        Type: {alert.type} | Status: {alert.status}
                                                    </p>
                                                </div>
                                                <span className="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-200 text-yellow-800">
                                                    {alert.level}
                                                </span>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default AdminDashboard;
