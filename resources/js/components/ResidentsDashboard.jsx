import React, { useState, useEffect } from 'react';

const ResidentsDashboard = ({ bins, userAlerts, routes, csrfToken }) => {
    const [binData, setBinData] = useState(bins || []);
    const [alertData, setAlertData] = useState(userAlerts || []);
    const [openAlerts, setOpenAlerts] = useState(0);
    const [activeCard, setActiveCard] = useState('total_bins');

    useEffect(() => {
        // Fetch bins dynamically
        fetch('/api/residents/bins')
            .then(response => response.json())
            .then(data => {
                setBinData(data);
            })
            .catch(error => console.log('Bins data not available'));

        // Fetch open alerts count
        fetch('/api/admin/analytics/alerts/stats')
            .then(response => response.json())
            .then(data => {
                setOpenAlerts(data.open_alerts || 0);
            })
            .catch(error => console.log('Alerts data not available'));
    }, []);

    const handleCardClick = (cardType) => {
        setActiveCard(cardType);
    };

    const reportIssue = () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                fetch('/alerts', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        latitude: lat,
                        longitude: lng,
                        message: 'Public report from location'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert('Issue reported successfully!');
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error reporting issue');
                });
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    };

    const reportBinIssue = (binId) => {
        const message = prompt('Describe the issue:');
        if (message) {
            fetch('/alerts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    bin_id: binId,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                alert('Issue reported successfully!');
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error reporting issue');
            });
        }
    };

    return (
        <div className="py-12">
            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 bg-white border-b border-gray-200">
                        <div className="flex justify-between items-center mb-6">
                            <h1 className="text-3xl font-bold text-gray-900">Residents Dashboard</h1>
                            <form method="POST" action={routes.logout} className="inline">
                                <input type="hidden" name="_token" value={csrfToken} />
                                <button type="submit"
                                    className="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
                                    Log Out
                                </button>
                            </form>
                        </div>

                        {/* System Overview */}
                        <div className="grid md:grid-cols-3 gap-6 mb-8">
                            <div className={`border rounded-lg p-6 cursor-pointer transition-all ${activeCard === 'total_bins' ? 'ring-2 ring-blue-500' : ''}`}
                                 onClick={() => handleCardClick('total_bins')}>
                                <h3 className="text-xl font-semibold mb-2">Total Bins</h3>
                                <p className="text-3xl font-bold">{binData.length}</p>
                            </div>
                            <div className={`border rounded-lg p-6 cursor-pointer transition-all ${activeCard === 'your_reports' ? 'ring-2 ring-blue-500' : ''}`}
                                 onClick={() => handleCardClick('your_reports')}>
                                <h3 className="text-xl font-semibold mb-2">Your Reports</h3>
                                <p className="text-3xl font-bold">{alertData.length}</p>
                            </div>
                            <div className={`border rounded-lg p-6 cursor-pointer transition-all ${activeCard === 'open_alerts' ? 'ring-2 ring-blue-500' : ''}`}
                                 onClick={() => handleCardClick('open_alerts')}>
                                <h3 className="text-xl font-semibold mb-2">Open Alerts</h3>
                                <p className="text-3xl font-bold">{openAlerts}</p>
                            </div>
                        </div>

                        {/* Conditionally render sections based on activeCard */}
                        {activeCard === 'total_bins' && (
                            <div className="mb-8">
                                <h2 className="text-2xl font-bold text-gray-900 mb-4">Nearby Bins</h2>
                                <div className="bg-gray-100 rounded-lg p-6">
                                    <p className="text-gray-600 mb-4">Find and report issues with waste bins in your area.</p>
                                    <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        {binData.filter(bin => !bin.collected).map((bin) => (
                                            <div key={bin.id} className="bg-white rounded-lg p-4 shadow">
                                                <h3 className="font-semibold text-gray-900">{bin.name}</h3>
                                                <p className="text-sm text-gray-600">
                                                    Location: {bin.area_name}
                                                </p>
                                                <p className="text-sm text-gray-600">Status: {bin.collected ? 'Collected' : 'Not Collected'}</p>
                                                <p className="text-sm text-gray-600">Type: {bin.type}</p>
                                                <button
                                                    onClick={() => reportBinIssue(bin.id)}
                                                    className="mt-2 bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs"
                                                >
                                                    Report Issue
                                                </button>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        )}

                        {activeCard === 'your_reports' && alertData.length > 0 && (
                            <div>
                                <h2 className="text-2xl font-bold text-gray-900 mb-4">Your Recent Reports</h2>
                                <div className="space-y-4">
                                    {alertData.map((alert) => (
                                        <div key={alert.id} className="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                            <div className="flex justify-between items-start">
                                                <div>
                                                    <h3 className="text-lg font-semibold text-blue-800">
                                                        Report for Bin {alert.bin?.name || alert.bin_id}
                                                    </h3>
                                                    <p className="text-blue-700">{alert.message}</p>
                                                    <p className="text-sm text-blue-600 mt-1">
                                                        Status: {alert.status} | Type: {alert.type}
                                                    </p>
                                                </div>
                                                <span className={`px-2 py-1 text-xs font-semibold rounded-full ${
                                                    alert.status === 'open' ? 'bg-yellow-200 text-yellow-800' : 'bg-green-200 text-green-800'
                                                }`}>
                                                    {alert.status}
                                                </span>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}

                        {activeCard === 'open_alerts' && (
                            <div>
                                <h2 className="text-2xl font-bold text-gray-900 mb-4">Open Alerts</h2>
                                <div className="space-y-4">
                                    {alertData.filter(alert => alert.status === 'open').map((alert) => (
                                        <div key={alert.id} className="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                            <div className="flex justify-between items-start">
                                                <div>
                                                    <h3 className="text-lg font-semibold text-yellow-800">
                                                        Alert for Bin {alert.bin?.name || alert.bin_id}
                                                    </h3>
                                                    <p className="text-yellow-700">{alert.message}</p>
                                                    <p className="text-sm text-yellow-600 mt-1">
                                                        Type: {alert.type}
                                                    </p>
                                                </div>
                                                <span className="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-200 text-yellow-800">
                                                    Open
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

export default ResidentsDashboard;
