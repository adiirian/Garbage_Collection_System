import React, { useState, useEffect } from 'react';

const AdminDashboard = ({ bins, openAlerts, routes }) => {
    const [binData, setBinData] = useState(bins || []);
    const [alertData, setAlertData] = useState(openAlerts || []);
    const [openAlertsCount, setOpenAlertsCount] = useState(0);
    const [todayCollections, setTodayCollections] = useState(0);
    const [filteredBins, setFilteredBins] = useState(bins || []);
    const [activeFilter, setActiveFilter] = useState('all');
    const [typeFilter, setTypeFilter] = useState('all');

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

        // Fetch open alerts count
        fetch('/api/admin/analytics/alerts/stats')
            .then(response => response.json())
            .then(data => {
                setOpenAlertsCount(data.open_alerts || 0);
            })
            .catch(error => console.log('Alerts data not available'));
    }, []);

    const handleCardClick = (filterType) => {
        setActiveFilter(filterType);
        setTypeFilter('all'); // Reset type filter when changing card filter
    };

    const getBinTypeColor = (type) => {
        switch (type) {
            case 'paper': return 'bg-blue-100 text-blue-800';
            case 'glass': return 'bg-green-100 text-green-800';
            case 'plastic': return 'bg-yellow-100 text-yellow-800';
            case 'metal': return 'bg-gray-100 text-gray-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    };

    const applyFilters = () => {
        let filtered = binData;
        if (activeFilter === 'collected') {
            filtered = filtered.filter(bin => bin.collected);
        } else if (activeFilter === 'uncollected') {
            filtered = filtered.filter(bin => !bin.collected);
        } else if (activeFilter === 'alerts') {
            filtered = filtered.filter(bin => alertData.some(alert => alert.bin_id === bin.id));
        }
        if (typeFilter !== 'all') {
            filtered = filtered.filter(bin => bin.type === typeFilter);
        }
        setFilteredBins(filtered);
    };

    useEffect(() => {
        applyFilters();
    }, [binData, activeFilter, typeFilter, alertData]);

    return (
        <div className="py-12">
            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 bg-white border-b border-gray-200">
                        <div className="flex justify-between items-center mb-6">
                            <h1 className="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                            <div className="flex space-x-4">
                                <a href={routes.adminCollectorManagement}
                                    className="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                                    Collector Management
                                </a>
                                <a href={routes.adminAnalytics}
                                    className="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                    View Analytics
                                </a>
                                <button onClick={() => {
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = window.routes.logout;
                                    const csrfInput = document.createElement('input');
                                    csrfInput.type = 'hidden';
                                    csrfInput.name = '_token';
                                    csrfInput.value = window.csrfToken;
                                    form.appendChild(csrfInput);
                                    document.body.appendChild(form);
                                    form.submit();
                                }}
                                    className="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
                                    Logout
                                </button>
                            </div>
                        </div>

                        {/* System Overview */}
                        <div className="grid md:grid-cols-4 gap-6 mb-8">
                            <div className={`border rounded-lg p-6 cursor-pointer transition-all ${activeFilter === 'all' ? 'ring-2 ring-blue-500' : ''}`}
                                 onClick={() => handleCardClick('all')}>
                                <h3 className="text-xl font-semibold mb-2">Total Bins</h3>
                                <p className="text-3xl font-bold">{binData.length}</p>
                            </div>
                            <div className={`border rounded-lg p-6 cursor-pointer transition-all ${activeFilter === 'collected' ? 'ring-2 ring-blue-500' : ''}`}
                                 onClick={() => handleCardClick('collected')}>
                                <h3 className="text-xl font-semibold mb-2">Collected Bins</h3>
                                <p className="text-3xl font-bold">{binData.filter(bin => bin.collected).length}</p>
                            </div>
                            <div className={`border rounded-lg p-6 cursor-pointer transition-all ${activeFilter === 'uncollected' ? 'ring-2 ring-blue-500' : ''}`}
                                 onClick={() => handleCardClick('uncollected')}>
                                <h3 className="text-xl font-semibold mb-2">Uncollected Bins</h3>
                                <p className="text-3xl font-bold">{binData.filter(bin => !bin.collected).length}</p>
                            </div>
                            <div className={`border rounded-lg p-6 cursor-pointer transition-all ${activeFilter === 'alerts' ? 'ring-2 ring-blue-500' : ''}`}
                                 onClick={() => handleCardClick('alerts')}>
                                <h3 className="text-xl font-semibold mb-2">Open Alerts</h3>
                                <p className="text-3xl font-bold">{openAlertsCount}</p>
                            </div>
                        </div>

                        {/* Bins Table */}
                        <div className="mb-8">
                            <h2 className="text-2xl font-bold text-gray-900 mb-4">
                                {activeFilter === 'all' ? 'All Bins' :
                                 activeFilter === 'collected' ? 'Collected Bins' :
                                 activeFilter === 'uncollected' ? 'Uncollected Bins' :
                                 'Bins with Alerts'}
                            </h2>
                            <div className="overflow-x-auto">
                                <table className="min-w-full bg-white border border-gray-300">
                                    <thead>
                                        <tr className="bg-gray-50">
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <select
                                                    value={typeFilter}
                                                    onChange={(e) => setTypeFilter(e.target.value)}
                                                    className="bg-transparent border-none text-xs font-medium text-gray-500 uppercase tracking-wider focus:outline-none"
                                                >
                                                    <option value="all">Type</option>
                                                    <option value="paper">Paper</option>
                                                    <option value="glass">Glass</option>
                                                    <option value="plastic">Plastic</option>
                                                    <option value="metal">Metal</option>
                                                </select>
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {filteredBins.map((bin) => (
                                            <tr key={bin.id}>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{bin.id}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{bin.name}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{bin.area_name}</td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getBinTypeColor(bin.type)}`}>
                                                        {bin.type}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                                        bin.collected ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                                    }`}>
                                                        {bin.collected ? 'Collected' : 'Uncollected'}
                                                    </span>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>




                    </div>
                </div>
            </div>
        </div>
    );
};

export default AdminDashboard;
