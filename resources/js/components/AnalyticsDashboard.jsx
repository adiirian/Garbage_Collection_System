import React from 'react';

const AnalyticsDashboard = ({
    binSummary,
    alertStats,
    collectionEfficiency,
    binCollectionRates,
    routes
}) => {
    return (
        <div className="py-12">
            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 bg-white border-b border-gray-200">
                        <div className="flex justify-between items-center mb-6">
                            <h1 className="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
                            <a href={routes.adminDashboard}
                                className="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                Back to Dashboard
                            </a>
                        </div>

                        {/* Bin Summary */}
                        <div className="grid md:grid-cols-4 gap-6 mb-8">
                            <div className="bg-blue-50 border border-blue-200 rounded-lg p-6">
                                <h3 className="text-xl font-semibold text-blue-800 mb-2">Total Bins</h3>
                                <p className="text-3xl font-bold text-blue-600">{binSummary?.total_bins || 0}</p>
                            </div>
                            <div className="bg-green-50 border border-green-200 rounded-lg p-6">
                                <h3 className="text-xl font-semibold text-green-800 mb-2">Empty Bins</h3>
                                <p className="text-3xl font-bold text-green-600">{binSummary?.bins_by_level?.empty || 0}</p>
                            </div>
                            <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                                <h3 className="text-xl font-semibold text-yellow-800 mb-2">Half Full Bins</h3>
                                <p className="text-3xl font-bold text-yellow-600">{binSummary?.bins_by_level?.half || 0}</p>
                            </div>
                            <div className="bg-red-50 border border-red-200 rounded-lg p-6">
                                <h3 className="text-xl font-semibold text-red-800 mb-2">Full Bins</h3>
                                <p className="text-3xl font-bold text-red-600">{binSummary?.bins_by_level?.full || 0}</p>
                            </div>
                        </div>

                        {/* Alert Statistics */}
                        <div className="grid md:grid-cols-3 gap-6 mb-8">
                            <div className="bg-purple-50 border border-purple-200 rounded-lg p-6">
                                <h3 className="text-xl font-semibold text-purple-800 mb-2">Total Alerts</h3>
                                <p className="text-3xl font-bold text-purple-600">{alertStats?.total_alerts || 0}</p>
                            </div>
                        </div>

                        {/* Collection Efficiency */}
                        <div className="mb-8">
                            <h2 className="text-2xl font-bold text-gray-900 mb-4">Collection Efficiency</h2>
                            <div className="bg-gray-50 border border-gray-200 rounded-lg p-6">
                                <p className="text-lg font-semibold text-gray-800">
                                    Overall Efficiency: <span className="text-2xl font-bold text-green-600">
                                        {collectionEfficiency ? collectionEfficiency.toFixed(2) : 0}%
                                    </span>
                                </p>
                            </div>
                        </div>

                        {/* Bin Collection Rates */}
                        {binCollectionRates && binCollectionRates.length > 0 && (
                            <div>
                                <h2 className="text-2xl font-bold text-gray-900 mb-4">Bin Collection Rates</h2>
                                <div className="overflow-x-auto">
                                    <table className="min-w-full bg-white border border-gray-300">
                                        <thead>
                                            <tr className="bg-gray-50">
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Bin Name
                                                </th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Collection Rate (%)
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody className="bg-white divide-y divide-gray-200">
                                            {binCollectionRates.map((rate, index) => (
                                                <tr key={index}>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {rate.bin_name || 'N/A'}
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
