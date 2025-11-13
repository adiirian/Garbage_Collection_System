import React, { useState, useEffect } from 'react';

const AnalyticsDashboard = ({
    binSummary,
    alertStats,
    collectionEfficiency,
    binCollectionRates,
    routes
}) => {
    const [penaltiesData, setPenaltiesData] = useState({ total_penalties: 0, total_amount: 0, penalties_this_month: 0 });
    const [liveCollectionEfficiency, setLiveCollectionEfficiency] = useState(collectionEfficiency || 0);
    const [liveBinCollectionRates, setLiveBinCollectionRates] = useState(binCollectionRates || []);

    useEffect(() => {
        // Fetch penalties data
        fetch('/api/admin/analytics/penalties/stats')
            .then(response => response.json())
            .then(data => {
                setPenaltiesData(data);
            })
            .catch(error => console.log('Penalties data not available'));

        // Fetch collection efficiency data
        const fetchCollectionEfficiency = () => {
            fetch('/api/admin/analytics/collection-efficiency')
                .then(response => response.json())
                .then(data => {
                    setLiveCollectionEfficiency(data.overall_efficiency);
                    setLiveBinCollectionRates(data.bin_collection_rates);
                })
                .catch(error => console.log('Collection efficiency data not available'));
        };

        // Initial fetch
        fetchCollectionEfficiency();

        // Set up polling every 10 seconds for real-time updates
        const interval = setInterval(fetchCollectionEfficiency, 10000);

        // Cleanup interval on unmount
        return () => clearInterval(interval);
    }, []);

    return (
        <div className="py-12">
            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 bg-white border-b border-gray-200">
                        <div className="flex justify-between items-center mb-6">
                            <h1 className="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
                            <a href={routes.adminDashboard}
                                className="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                Back to Admin Dashboard
                            </a>
                        </div>

                        {/* Bin Summary */}
                        <div className="grid md:grid-cols-2 gap-6 mb-8">
                            <div className="border rounded-lg p-6">
                                <h3 className="text-xl font-semibold mb-2">Total Bins</h3>
                                <p className="text-3xl font-bold">{binSummary?.total_bins || 0}</p>
                            </div>
                            <div className="border rounded-lg p-6">
                                <h3 className="text-xl font-semibold mb-2">Total Alerts</h3>
                                <p className="text-3xl font-bold">{alertStats?.total_alerts || 0}</p>
                            </div>
                        </div>

                        {/* Penalties Card */}
                        <div className="grid md:grid-cols-3 gap-6 mb-8">
                            <div className="border rounded-lg p-6">
                                <h3 className="text-xl font-semibold mb-2">Total Penalties</h3>
                                <p className="text-3xl font-bold">{penaltiesData.total_penalties}</p>
                            </div>
                            <div className="border rounded-lg p-6">
                                <h3 className="text-xl font-semibold mb-2">Total Amount</h3>
                                <p className="text-3xl font-bold">₱{penaltiesData.total_amount}</p>
                            </div>
                            <div className="border rounded-lg p-6">
                                <h3 className="text-xl font-semibold mb-2">This Month</h3>
                                <p className="text-3xl font-bold">{penaltiesData.penalties_this_month}</p>
                            </div>
                        </div>

                        {/* Collection Efficiency */}
                        <div className="mb-8">
                            <h2 className="text-2xl font-bold text-gray-900 mb-4">Collection Efficiency</h2>
                            <div className="bg-gray-50 border border-gray-200 rounded-lg p-6">
                                <p className="text-lg font-semibold text-gray-800">
                                    Overall Efficiency: <span className="text-2xl font-bold text-green-600">
                                        {liveCollectionEfficiency ? liveCollectionEfficiency.toFixed(2) : 0}%
                                    </span>
                                </p>
                            </div>
                        </div>

                        {/* Bin Collection Rates by Type */}
                        {liveBinCollectionRates && liveBinCollectionRates.length > 0 && (
                            <div>
                                <h2 className="text-2xl font-bold text-gray-900 mb-4">Collection Efficiency by Bin Type</h2>
                                <div className="overflow-x-auto">
                                    <table className="min-w-full bg-white border border-gray-300">
                                        <thead>
                                            <tr className="bg-gray-50">
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Bin Type
                                                </th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Total Bins
                                                </th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Collections
                                                </th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Efficiency Rate (%)
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody className="bg-white divide-y divide-gray-200">
                                            {liveBinCollectionRates.map((rate, index) => (
                                                <tr key={index}>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 capitalize">
                                                        {rate.type || 'N/A'}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {rate.total_bins || 0}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {rate.collections || 0}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {rate.efficiency_rate ? rate.efficiency_rate.toFixed(2) : 0}%
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default AnalyticsDashboard;
