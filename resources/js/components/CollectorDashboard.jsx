import React, { useState, useEffect } from 'react';

const CollectorDashboard = ({ bins, openAlerts, routes, csrfToken }) => {
    const [binData, setBinData] = useState(bins || []);
    const [alertData, setAlertData] = useState(openAlerts || []);
    const [openAlertsCount, setOpenAlertsCount] = useState(0);
    const [todayCollections, setTodayCollections] = useState(0);
    const [profile, setProfile] = useState({});
    const [assignments, setAssignments] = useState([]);
    const [assignedAreas, setAssignedAreas] = useState([]);
    const [showProfileModal, setShowProfileModal] = useState(false);
    const [profileForm, setProfileForm] = useState({
        address: '',
        age: '',
        daily_salary: '',
        profile_picture: null
    });
    const [profilePicturePreview, setProfilePicturePreview] = useState(null);

    useEffect(() => {
        // Fetch today's collections
        fetch('/api/admin/analytics/collections/today')
            .then(response => response.json())
            .then(data => {
                setTodayCollections(data.collections_today || 0);
            })
            .catch(error => console.log('Collections data not available'));

        // Fetch open alerts count
        fetch('/api/admin/analytics/alerts/stats')
            .then(response => response.json())
            .then(data => {
                setOpenAlertsCount(data.open_alerts || 0);
            })
            .catch(error => console.log('Alerts data not available'));

        // Fetch user profile
        fetch('/api/collector/profile')
            .then(response => response.json())
            .then(data => {
                setProfile(data);
                setProfileForm({
                    address: data.address || '',
                    age: data.age || '',
                    daily_salary: data.daily_salary || '',
                    profile_picture: null
                });
                setProfilePicturePreview(data.profile_picture ? `/storage/${data.profile_picture}` : null);
            })
            .catch(error => console.log('Profile data not available'));

        // Fetch assignments
        fetch('/api/collector/assignments')
            .then(response => response.json())
            .then(data => {
                setAssignments(data);
                // Extract unique assigned areas
                const areas = [...new Set(data.map(a => a.assigned_area))];
                setAssignedAreas(areas);
                // Filter bins to only show those in assigned areas
                if (areas.length > 0) {
                    setBinData(bins.filter(bin => areas.includes(bin.area_name)));
                }
            })
            .catch(error => console.log('Assignments data not available'));
    }, [bins]);

    const markCollected = (binId) => {
        if (confirm('Mark this bin as collected?')) {
            fetch(`/collector/bins/${binId}/clean`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                alert('Bin marked as collected!');
                // Update local state
                setBinData(prev => prev.filter(bin => bin.id !== binId));
            })
            .catch(error => {
                console.error('Error:', error);
                alert(`Error marking bin as collected: ${error.message}`);
            });
        }
    };

    const handleProfileUpdate = async (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('address', profileForm.address);
        formData.append('age', profileForm.age);
        formData.append('daily_salary', profileForm.daily_salary);
        if (profileForm.profile_picture) {
            formData.append('profile_picture', profileForm.profile_picture);
        }
        formData.append('_token', csrfToken);

        try {
            const response = await fetch('/api/collector/profile', {
                method: 'POST',
                body: formData
            });
            if (response.ok) {
                alert('Profile updated successfully!');
                setShowProfileModal(false);
                // Refresh profile
                fetch('/api/collector/profile')
                    .then(response => response.json())
                    .then(data => setProfile(data));
            } else {
                alert('Error updating profile');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error updating profile');
        }
    };

    const handleFileChange = (e) => {
        const file = e.target.files[0];
        setProfileForm({...profileForm, profile_picture: file});
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => setProfilePicturePreview(e.target.result);
            reader.readAsDataURL(file);
        }
    };

    const updateAssignmentStatus = async (assignmentId, status) => {
        try {
            const response = await fetch(`/api/collector/assignments/${assignmentId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ status })
            });
            if (response.ok) {
                setAssignments(assignments.map(a => a.id === assignmentId ? {...a, status} : a));
            }
        } catch (error) {
            console.error('Error updating assignment:', error);
        }
    };

    return (
        <div className="py-12">
            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 bg-white border-b border-gray-200">
                        <div className="flex justify-between items-center mb-6">
                            <h1 className="text-3xl font-bold text-gray-900">Collector Dashboard</h1>
                            <div className="flex space-x-2">
                                <a
                                    href="/collector/profile"
                                    className="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition-colors inline-block">
                                    View Profile
                                </a>
                            </div>
                        </div>

                        {/* System Overview */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div className="bg-blue-50 border border-blue-200 rounded-lg p-6">
                                <h3 className="text-xl font-semibold text-blue-800 mb-2">Bins Needing Attention</h3>
                                <p className="text-3xl font-bold text-blue-600">{binData.length}</p>
                            </div>
                            <div className="bg-red-50 border border-red-200 rounded-lg p-6">
                                <h3 className="text-xl font-semibold text-red-800 mb-2">Open Alerts</h3>
                                <p className="text-3xl font-bold text-red-600">{openAlertsCount}</p>
                            </div>
                            <div className="bg-green-50 border border-green-200 rounded-lg p-6">
                                <h3 className="text-xl font-semibold text-green-800 mb-2">Today's Collections</h3>
                                <p className="text-3xl font-bold text-green-600">{todayCollections}</p>
                            </div>
                        </div>

                        {/* Assigned Tasks */}
                        <div className="mb-8">
                            <h2 className="text-2xl font-bold text-gray-900 mb-4">Assigned Tasks</h2>
                            <div className="overflow-x-auto">
                                <table className="min-w-full bg-white border border-gray-300">
                                    <thead>
                                        <tr className="bg-gray-50">
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Area</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {assignments.map((assignment) => (
                                            <tr key={assignment.id}>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{assignment.assigned_area}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{assignment.scheduled_date}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{assignment.scheduled_time}</td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                                        assignment.status === 'completed' ? 'bg-green-100 text-green-800' :
                                                        assignment.status === 'failed' ? 'bg-red-100 text-red-800' :
                                                        assignment.status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' :
                                                        'bg-gray-100 text-gray-800'
                                                    }`}>
                                                        {assignment.status.replace('_', ' ')}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <select
                                                        value={assignment.status}
                                                        onChange={(e) => updateAssignmentStatus(assignment.id, e.target.value)}
                                                        className="border border-gray-300 rounded px-2 py-1 text-xs"
                                                    >
                                                        <option value="pending">Pending</option>
                                                        <option value="in_progress">In Progress</option>
                                                        <option value="completed">Completed</option>
                                                        <option value="failed">Failed</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {/* Bins Needing Collection */}
                        <div className="mb-8">
                            <h2 className="text-2xl font-bold text-gray-900 mb-4">Bins Needing Collection</h2>
                            <div className="overflow-x-auto">
                                <table className="min-w-full bg-white border border-gray-300">
                                    <thead>
                                        <tr className="bg-gray-50">
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Area</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {binData.map((bin) => (
                                            <tr key={bin.id}>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{bin.id}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{bin.name}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{bin.area_name}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{bin.level}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <button
                                                        onClick={() => markCollected(bin.id)}
                                                        className="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition-colors"
                                                    >
                                                        Mark Collected
                                                    </button>
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

export default CollectorDashboard;
