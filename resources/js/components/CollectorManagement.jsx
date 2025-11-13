import React, { useState, useEffect } from 'react';

const CollectorManagement = ({ collectors, assignments, routes, areaNames }) => {
    const [collectorList, setCollectorList] = useState(collectors || []);
    const [assignmentList, setAssignmentList] = useState(assignments || []);
    const [selectedCollector, setSelectedCollector] = useState(null);
    const [selectedAssignment, setSelectedAssignment] = useState(null);
    const [showPenaltyModal, setShowPenaltyModal] = useState(false);
    const [showEditModal, setShowEditModal] = useState(false);
    const [showAssignmentModal, setShowAssignmentModal] = useState(false);
    const [showBulkAssignmentModal, setShowBulkAssignmentModal] = useState(false);
    const [showPenaltySuggestionModal, setShowPenaltySuggestionModal] = useState(false);
    const [penaltyForm, setPenaltyForm] = useState({
        violation: '',
        amount: '',
        penalty_date: new Date().toISOString().split('T')[0]
    });
    const [editForm, setEditForm] = useState({
        address: '',
        age: '',
        daily_salary: '',
        status: 'off_duty'
    });
    const [assignmentForm, setAssignmentForm] = useState({
        collector_id: '',
        assigned_area: '',
        scheduled_date: '',
        scheduled_time: '',
        status: 'pending'
    });
    const [bulkAssignmentForm, setBulkAssignmentForm] = useState({
        collector_ids: [],
        assigned_areas: [],
        scheduled_date: '',
        scheduled_time: '',
        status: 'pending'
    });
    const [collectorPenalties, setCollectorPenalties] = useState([]);
    const [violations, setViolations] = useState({});
    const [overdueAssignments, setOverdueAssignments] = useState([]);
    const [violationCounts, setViolationCounts] = useState({});
    const [suggestedPenalties, setSuggestedPenalties] = useState([]);

    useEffect(() => {
        // Check for overdue assignments and failed assignments to suggest penalties
        const now = new Date();
        const overdue = assignmentList.filter(assignment =>
            assignment.status !== 'completed' &&
            new Date(`${assignment.scheduled_date}T${assignment.scheduled_time}`) < now
        );
        const failed = assignmentList.filter(assignment => assignment.status === 'failed');

        const suggestions = [];
        overdue.forEach(assignment => {
            suggestions.push({
                type: 'overdue',
                assignment,
                reason: `Overdue assignment: ${assignment.assigned_area} on ${assignment.scheduled_date} at ${assignment.scheduled_time}`,
                suggestedAmount: 100.00
            });
        });
        failed.forEach(assignment => {
            suggestions.push({
                type: 'failed',
                assignment,
                reason: `Failed assignment: ${assignment.assigned_area} on ${assignment.scheduled_date}`,
                suggestedAmount: 200.00
            });
        });

        setSuggestedPenalties(suggestions);
    }, [assignmentList]);

    const getStatusColor = (status) => {
        switch (status) {
            case 'on_duty': return 'bg-green-100 text-green-800';
            case 'on_break': return 'bg-yellow-100 text-yellow-800';
            case 'off_duty': return 'bg-red-100 text-red-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    };

    const handleUpdateCollector = async (collectorId, field, value) => {
        try {
            const response = await fetch(window.routes.updateCollector.replace('{id}', collectorId), {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify({ [field]: value })
            });

            if (response.ok) {
                const updatedCollector = await response.json();
                setCollectorList(collectors => collectors.map(c =>
                    c.id === collectorId ? updatedCollector.user : c
                ));
            }
        } catch (error) {
            console.error('Error updating collector:', error);
        }
    };

    const handleApplyPenalty = async (e) => {
        e.preventDefault();
        if (!selectedCollector || !selectedCollector.id) {
            alert('Invalid collector selected.');
            return;
        }

        try {
            const response = await fetch(window.routes.applyPenalty.replace('{id}', selectedCollector.id), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify(penaltyForm),
                credentials: 'same-origin'
            });

            if (response.ok) {
                try {
                    const result = await response.json();
                    setShowPenaltyModal(false);
                    setPenaltyForm({
                        violation: '',
                        amount: '',
                        penalty_date: new Date().toISOString().split('T')[0]
                    });
                    // Refresh penalties
                    fetchPenalties(selectedCollector.id);
                    alert('Penalty applied successfully');
                } catch (jsonError) {
                    alert('Server returned invalid response. Please check if you are logged in.');
                }
            } else {
                let errorMessage = 'Unknown error occurred';
                try {
                    const errorData = await response.json();
                    errorMessage = errorData.message || JSON.stringify(errorData.errors) || errorMessage;
                } catch (parseError) {
                    // If not JSON, get text
                    const errorText = await response.text();
                    errorMessage = errorText.includes('<!DOCTYPE') ? 'Server error - please check if authenticated and try again' : errorText;
                }
                alert('Error applying penalty: ' + errorMessage);
            }
        } catch (error) {
            console.error('Error applying penalty:', error);
            alert('Error applying penalty: ' + error.message);
        }
    };

    const fetchPenalties = async (collectorId) => {
        try {
            const response = await fetch(window.routes.getPenalties.replace('{id}', collectorId));
            if (response.ok) {
                const penalties = await response.json();
                setCollectorPenalties(penalties);
            }
        } catch (error) {
            console.error('Error fetching penalties:', error);
        }
    };

    const openPenaltyModal = (collector) => {
        setSelectedCollector(collector);
        setShowPenaltyModal(true);
        fetchPenalties(collector.id);
    };

    const handleDeleteAssignment = async (assignmentId) => {
        if (!window.confirm('Are you sure you want to delete this assignment?')) {
            return;
        }

        try {
            const response = await fetch(window.routes.deleteAssignment.replace('{id}', assignmentId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': window.csrfToken
                }
            });

            if (response.ok) {
                setAssignmentList(assignments => assignments.filter(a => a.id !== assignmentId));
            } else {
                alert('Error deleting assignment');
            }
        } catch (error) {
            console.error('Error deleting assignment:', error);
            alert('Error deleting assignment');
        }
    };

    return (
        <div className="py-12">
            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 bg-white border-b border-gray-200">
                        <div className="flex justify-between items-center mb-6">
                            <h1 className="text-3xl font-bold text-gray-900">Collector Management</h1>
                            <a href={routes.adminDashboard}
                                className="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                Back to Admin Dashboard
                            </a>
                        </div>

                        {/* Collectors Table */}
                        <div className="mb-8">
                            <h2 className="text-2xl font-bold text-gray-900 mb-4">Registered Collectors</h2>
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                {collectorList.map((collector) => (
                                    <div key={collector.id} className="bg-white border border-gray-200 rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                                        <div className="flex justify-between items-start mb-4">
                                            <div>
                                                <h3 className="text-lg font-semibold text-gray-900">{collector.name}</h3>
                                                <p className="text-sm text-gray-600">{collector.email}</p>
                                            </div>
                                            <span className={`px-2 py-1 text-xs font-semibold rounded-full ${getStatusColor(collector.status)}`}>
                                                {collector.status ? collector.status.replace('_', ' ') : 'off_duty'.replace('_', ' ')}
                                            </span>
                                        </div>
                                        <div className="space-y-2 mb-4">
                                            <p className="text-sm"><span className="font-medium">Address:</span> {collector.address || 'Not provided'}</p>
                                            <p className="text-sm"><span className="font-medium">Age:</span> {collector.age || 'Not provided'}</p>
                                            <p className="text-sm"><span className="font-medium">Salary:</span> ₱{collector.daily_salary || '0.00'}</p>
                                            <p className="text-sm"><span className="font-medium">Penalties:</span> {collector.penalties_count || 0}</p>
                                        </div>
                                        <div className="flex space-x-2">
                                            <button
                                                onClick={() => {
                                                    setSelectedCollector(collector);
                                                    setEditForm({
                                                        address: collector.address || '',
                                                        age: collector.age || '',
                                                        daily_salary: collector.daily_salary || '',
                                                        status: collector.status || 'off_duty'
                                                    });
                                                    setShowEditModal(true);
                                                }}
                                                className="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                onClick={() => openPenaltyModal(collector)}
                                                className="flex-1 bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors"
                                            >
                                                Penalty
                                            </button>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Penalty Suggestions */}
                        {suggestedPenalties.length > 0 && (
                            <div className="mb-8">
                                <h2 className="text-2xl font-bold text-gray-900 mb-4">Penalty Suggestions</h2>
                                <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div className="space-y-3">
                                        {suggestedPenalties.map((suggestion, index) => (
                                            <div key={index} className="flex justify-between items-center bg-white p-3 rounded border">
                                                <div>
                                                    <p className="font-medium text-gray-900">{suggestion.reason}</p>
                                                    <p className="text-sm text-gray-600">Suggested Amount: ₱{suggestion.suggestedAmount}</p>
                                                </div>
                                                {suggestion.assignment.collector && (
                                                    <button
                                                        onClick={() => {
                                                            setSelectedCollector(suggestion.assignment.collector);
                                                            setPenaltyForm({
                                                                violation: suggestion.reason,
                                                                amount: suggestion.suggestedAmount.toString(),
                                                                penalty_date: new Date().toISOString().split('T')[0]
                                                            });
                                                            setShowPenaltyModal(true);
                                                            fetchPenalties(suggestion.assignment.collector.id);
                                                        }}
                                                        className="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm"
                                                    >
                                                        Apply Penalty
                                                    </button>
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        )}

                        {/* Collection Scheduling Section */}
                        <div className="mb-8">
                            <div className="flex justify-between items-center mb-4">
                                <h2 className="text-2xl font-bold text-gray-900">Collection Scheduling</h2>
                                <div className="space-x-2">
                                    <button
                                        onClick={() => {
                                            setAssignmentForm({
                                                collector_id: '',
                                                assigned_area: '',
                                                scheduled_date: '',
                                                scheduled_time: '',
                                                status: 'pending'
                                            });
                                            setShowAssignmentModal(true);
                                        }}
                                        className="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm"
                                    >
                                        Create Assignment
                                    </button>
                                    <button
                                        onClick={() => {
                                            setBulkAssignmentForm({
                                                collector_ids: [],
                                                assigned_areas: [],
                                                scheduled_date: '',
                                                scheduled_time: '',
                                                status: 'pending'
                                            });
                                            setShowBulkAssignmentModal(true);
                                        }}
                                        className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm"
                                    >
                                        Bulk Assign
                                    </button>
                                </div>
                            </div>
                            <div className="overflow-x-auto">
                                <table className="min-w-full bg-white border border-gray-300">
                                    <thead>
                                        <tr className="bg-gray-50">
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Collector</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Area</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {assignmentList.map((assignment) => (
                                            <tr key={assignment.id}>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {assignment.collector?.name || 'Unknown'}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {assignment.assigned_area}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {assignment.scheduled_date}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {assignment.scheduled_time}
                                                </td>
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
                                                    <button
                                                        onClick={() => {
                                                            setSelectedAssignment(assignment);
                                                            setAssignmentForm({
                                                                collector_id: assignment.collector_id,
                                                                assigned_area: assignment.assigned_area,
                                                                scheduled_date: assignment.scheduled_date,
                                                                scheduled_time: assignment.scheduled_time,
                                                                status: assignment.status
                                                            });
                                                            setShowAssignmentModal(true);
                                                        }}
                                                        className="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs mr-2"
                                                    >
                                                        Edit
                                                    </button>
                                                    <button
                                                        onClick={() => handleDeleteAssignment(assignment.id)}
                                                        className="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs mr-2"
                                                    >
                                                        Delete
                                                    </button>
                                                    {assignment.status === 'failed' && new Date(`${assignment.scheduled_date}T${assignment.scheduled_time}`) < new Date() && (
                                                        <button
                                                            onClick={() => openPenaltyModal(assignment.collector)}
                                                            className="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs"
                                                        >
                                                            Apply Penalty
                                                        </button>
                                                    )}
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {/* Edit Collector Modal */}
                        {showEditModal && selectedCollector && (
                            <div className="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                                <div className="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white animate-fade-in">
                                    <div className="mt-3">
                                        <h3 className="text-lg font-medium text-gray-900 mb-4">
                                            Edit Collector: {selectedCollector.name}
                                        </h3>
                                        <form onSubmit={async (e) => {
                                            e.preventDefault();
                                            try {
                                                const response = await fetch(window.routes.updateCollector.replace('{id}', selectedCollector.id), {
                                                    method: 'PUT',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': window.csrfToken
                                                    },
                                                    body: JSON.stringify(editForm)
                                                });

                                                if (response.ok) {
                                                    const updatedCollector = await response.json();
                                                    setCollectorList(collectors => collectors.map(c =>
                                                        c.id === selectedCollector.id ? updatedCollector.user : c
                                                    ));
                                                    setShowEditModal(false);
                                                }
                                            } catch (error) {
                                                console.error('Error updating collector:', error);
                                            }
                                        }}>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Address
                                                </label>
                                                <input
                                                    type="text"
                                                    value={editForm.address}
                                                    onChange={(e) => setEditForm({...editForm, address: e.target.value})}
                                                    className="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                />
                                            </div>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Age
                                                </label>
                                                <input
                                                    type="number"
                                                    value={editForm.age}
                                                    onChange={(e) => setEditForm({...editForm, age: e.target.value})}
                                                    className="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    min="18"
                                                    max="65"
                                                />
                                            </div>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Daily Salary (₱)
                                                </label>
                                                <input
                                                    type="number"
                                                    value={editForm.daily_salary}
                                                    onChange={(e) => setEditForm({...editForm, daily_salary: e.target.value})}
                                                    className="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    step="0.01"
                                                    min="0"
                                                />
                                            </div>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Status
                                                </label>
                                                <select
                                                    value={editForm.status}
                                                    onChange={(e) => setEditForm({...editForm, status: e.target.value})}
                                                    className="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                >
                                                    <option value="on_duty">On Duty</option>
                                                    <option value="on_break">On Break</option>
                                                    <option value="off_duty">Off Duty</option>
                                                </select>
                                            </div>
                                            <div className="flex justify-end space-x-2">
                                                <button
                                                    type="button"
                                                    onClick={() => setShowEditModal(false)}
                                                    className="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition-colors"
                                                >
                                                    Cancel
                                                </button>
                                                <button
                                                    type="submit"
                                                    className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors"
                                                >
                                                    Update Collector
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        )}

                        {/* Assignment Modal */}
                        {showAssignmentModal && (
                            <div className="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                                <div className="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                    <div className="mt-3">
                                        <h3 className="text-lg font-medium text-gray-900 mb-4">
                                            {selectedAssignment ? 'Edit Assignment' : 'Create Assignment'}
                                        </h3>
                                        <form onSubmit={async (e) => {
                                            e.preventDefault();
                                            try {
                                                const url = selectedAssignment
                                                    ? window.routes.updateAssignment.replace('{id}', selectedAssignment.id)
                                                    : window.routes.createAssignment;
                                                const method = selectedAssignment ? 'PUT' : 'POST';

                                                const response = await fetch(url, {
                                                    method,
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': window.csrfToken
                                                    },
                                                    body: JSON.stringify(assignmentForm)
                                                });

                                                if (response.ok) {
                                                    const result = await response.json();
                                                    if (selectedAssignment) {
                                                        setAssignmentList(assignments => assignments.map(a =>
                                                            a.id === selectedAssignment.id ? result.assignment : a
                                                        ));
                                                    } else {
                                                        setAssignmentList(assignments => [...assignments, result.assignment]);
                                                    }
                                                    setShowAssignmentModal(false);
                                                    setSelectedAssignment(null);
                                                }
                                            } catch (error) {
                                                console.error('Error saving assignment:', error);
                                            }
                                        }}>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Collector
                                                </label>
                                                <select
                                                    value={assignmentForm.collector_id}
                                                    onChange={(e) => setAssignmentForm({...assignmentForm, collector_id: e.target.value})}
                                                    className="w-full border border-gray-300 rounded px-3 py-2"
                                                    required
                                                >
                                                    <option value="">Select Collector</option>
                                                    {collectorList.map(collector => (
                                                        <option key={collector.id} value={collector.id}>
                                                            {collector.name}
                                                        </option>
                                                    ))}
                                                </select>
                                            </div>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Assigned Area
                                                </label>
                                                <select
                                                    value={assignmentForm.assigned_area}
                                                    onChange={(e) => setAssignmentForm({...assignmentForm, assigned_area: e.target.value})}
                                                    className="w-full border border-gray-300 rounded px-3 py-2"
                                                    required
                                                >
                                                    <option value="">Select Area</option>
                                                    {areaNames.map(area => (
                                                        <option key={area} value={area}>
                                                            {area}
                                                        </option>
                                                    ))}
                                                </select>
                                            </div>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Scheduled Date
                                                </label>
                                                <input
                                                    type="date"
                                                    value={assignmentForm.scheduled_date}
                                                    onChange={(e) => setAssignmentForm({...assignmentForm, scheduled_date: e.target.value})}
                                                    className="w-full border border-gray-300 rounded px-3 py-2"
                                                    min={new Date().toISOString().split('T')[0]}
                                                    required
                                                />
                                            </div>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Scheduled Time
                                                </label>
                                                <input
                                                    type="time"
                                                    value={assignmentForm.scheduled_time}
                                                    onChange={(e) => setAssignmentForm({...assignmentForm, scheduled_time: e.target.value})}
                                                    className="w-full border border-gray-300 rounded px-3 py-2"
                                                    required
                                                />
                                            </div>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Status
                                                </label>
                                                <select
                                                    value={assignmentForm.status}
                                                    onChange={(e) => setAssignmentForm({...assignmentForm, status: e.target.value})}
                                                    className="w-full border border-gray-300 rounded px-3 py-2"
                                                >
                                                    <option value="pending">Pending</option>
                                                    <option value="in_progress">In Progress</option>
                                                    <option value="completed">Completed</option>
                                                    <option value="failed">Failed</option>
                                                </select>
                                            </div>
                                            <div className="flex justify-end space-x-2">
                                                <button
                                                    type="button"
                                                    onClick={() => {
                                                        setShowAssignmentModal(false);
                                                        setSelectedAssignment(null);
                                                    }}
                                                    className="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded"
                                                >
                                                    Cancel
                                                </button>
                                                <button
                                                    type="submit"
                                                    className="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded"
                                                >
                                                    {selectedAssignment ? 'Update Assignment' : 'Create Assignment'}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        )}

                        {/* Penalty Modal */}
                        {showPenaltyModal && selectedCollector && (
                            <div className="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                                <div className="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white animate-fade-in">
                                    <div className="mt-3">
                                        <h3 className="text-lg font-medium text-gray-900 mb-4">
                                            Apply Penalty to {selectedCollector.name}
                                        </h3>
                                        <form onSubmit={handleApplyPenalty}>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Violation
                                                </label>
                                                <textarea
                                                    value={penaltyForm.violation}
                                                    onChange={(e) => setPenaltyForm({...penaltyForm, violation: e.target.value})}
                                                    className="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    rows="3"
                                                    required
                                                />
                                            </div>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Amount (₱)
                                                </label>
                                                <input
                                                    type="number"
                                                    value={penaltyForm.amount}
                                                    onChange={(e) => setPenaltyForm({...penaltyForm, amount: e.target.value})}
                                                    className="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    step="0.01"
                                                    min="0"
                                                    required
                                                />
                                            </div>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Penalty Date
                                                </label>
                                                <input
                                                    type="date"
                                                    value={penaltyForm.penalty_date}
                                                    onChange={(e) => setPenaltyForm({...penaltyForm, penalty_date: e.target.value})}
                                                    className="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    required
                                                />
                                            </div>
                                            <div className="flex justify-end space-x-2">
                                                <button
                                                    type="button"
                                                    onClick={() => setShowPenaltyModal(false)}
                                                    className="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition-colors"
                                                >
                                                    Cancel
                                                </button>
                                                <button
                                                    type="submit"
                                                    className="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition-colors"
                                                >
                                                    Apply Penalty
                                                </button>
                                            </div>
                                        </form>

                                        {/* Penalties History */}
                                        {collectorPenalties.length > 0 && (
                                            <div className="mt-6">
                                                <h4 className="text-md font-medium text-gray-900 mb-2">Penalty History</h4>
                                                <div className="space-y-2 max-h-40 overflow-y-auto">
                                                    {collectorPenalties.map((penalty) => (
                                                        <div key={penalty.id} className="bg-red-50 p-2 rounded text-sm">
                                                            <div className="font-medium">₱{penalty.amount} - {penalty.reason}</div>
                                                            <div className="text-gray-600">{penalty.penalty_date}</div>
                                                        </div>
                                                    ))}
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>
                        )}

                        {/* Bulk Assignment Modal */}
                        {showBulkAssignmentModal && (
                            <div className="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                                <div className="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                    <div className="mt-3">
                                        <h3 className="text-lg font-medium text-gray-900 mb-4">
                                            Bulk Assignment Creation
                                        </h3>
                                        <form onSubmit={async (e) => {
                                            e.preventDefault();
                                            try {
                                                // Create assignments for each collector-area combination
                                                const promises = [];
                                                bulkAssignmentForm.collector_ids.forEach(collectorId => {
                                                    bulkAssignmentForm.assigned_areas.forEach(area => {
                                                        promises.push(
                                                            fetch(window.routes.createAssignment, {
                                                                method: 'POST',
                                                                headers: {
                                                                    'Content-Type': 'application/json',
                                                                    'X-CSRF-TOKEN': window.csrfToken
                                                                },
                                                                body: JSON.stringify({
                                                                    collector_id: collectorId,
                                                                    assigned_area: area,
                                                                    scheduled_date: bulkAssignmentForm.scheduled_date,
                                                                    scheduled_time: bulkAssignmentForm.scheduled_time,
                                                                    status: bulkAssignmentForm.status
                                                                })
                                                            })
                                                        );
                                                    });
                                                });

                                                const responses = await Promise.all(promises);
                                                const results = await Promise.all(responses.map(r => r.json()));

                                                // Refresh assignments list
                                                const assignmentsResponse = await fetch(window.routes.getAssignments);
                                                if (assignmentsResponse.ok) {
                                                    const updatedAssignments = await assignmentsResponse.json();
                                                    setAssignmentList(updatedAssignments);
                                                }

                                                setShowBulkAssignmentModal(false);
                                                alert(`Created ${results.length} assignments successfully`);
                                            } catch (error) {
                                                console.error('Error creating bulk assignments:', error);
                                                alert('Error creating bulk assignments');
                                            }
                                        }}>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Collectors (Hold Ctrl/Cmd to select multiple)
                                                </label>
                                                <select
                                                    multiple
                                                    value={bulkAssignmentForm.collector_ids}
                                                    onChange={(e) => {
                                                        const values = Array.from(e.target.selectedOptions, option => option.value);
                                                        setBulkAssignmentForm({...bulkAssignmentForm, collector_ids: values});
                                                    }}
                                                    className="w-full border border-gray-300 rounded px-3 py-2"
                                                    required
                                                >
                                                    {collectorList.map(collector => (
                                                        <option key={collector.id} value={collector.id}>
                                                            {collector.name}
                                                        </option>
                                                    ))}
                                                </select>
                                            </div>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Assigned Areas (Hold Ctrl/Cmd to select multiple)
                                                </label>
                                                <select
                                                    multiple
                                                    value={bulkAssignmentForm.assigned_areas}
                                                    onChange={(e) => {
                                                        const values = Array.from(e.target.selectedOptions, option => option.value);
                                                        setBulkAssignmentForm({...bulkAssignmentForm, assigned_areas: values});
                                                    }}
                                                    className="w-full border border-gray-300 rounded px-3 py-2"
                                                    required
                                                >
                                                    {areaNames.map(area => (
                                                        <option key={area} value={area}>
                                                            {area}
                                                        </option>
                                                    ))}
                                                </select>
                                            </div>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Scheduled Date
                                                </label>
                                                <input
                                                    type="date"
                                                    value={bulkAssignmentForm.scheduled_date}
                                                    onChange={(e) => setBulkAssignmentForm({...bulkAssignmentForm, scheduled_date: e.target.value})}
                                                    className="w-full border border-gray-300 rounded px-3 py-2"
                                                    min={new Date().toISOString().split('T')[0]}
                                                    required
                                                />
                                            </div>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Scheduled Time
                                                </label>
                                                <input
                                                    type="time"
                                                    value={bulkAssignmentForm.scheduled_time}
                                                    onChange={(e) => setBulkAssignmentForm({...bulkAssignmentForm, scheduled_time: e.target.value})}
                                                    className="w-full border border-gray-300 rounded px-3 py-2"
                                                    required
                                                />
                                            </div>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Status
                                                </label>
                                                <select
                                                    value={bulkAssignmentForm.status}
                                                    onChange={(e) => setBulkAssignmentForm({...bulkAssignmentForm, status: e.target.value})}
                                                    className="w-full border border-gray-300 rounded px-3 py-2"
                                                >
                                                    <option value="pending">Pending</option>
                                                    <option value="in_progress">In Progress</option>
                                                    <option value="completed">Completed</option>
                                                    <option value="failed">Failed</option>
                                                </select>
                                            </div>
                                            <div className="flex justify-end space-x-2">
                                                <button
                                                    type="button"
                                                    onClick={() => setShowBulkAssignmentModal(false)}
                                                    className="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded"
                                                >
                                                    Cancel
                                                </button>
                                                <button
                                                    type="submit"
                                                    className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
                                                >
                                                    Create Bulk Assignments
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default CollectorManagement;
