import './bootstrap';
import React from 'react';
import ReactDOM from 'react-dom/client';

// Import React components
import Welcome from './components/Welcome';
import AdminDashboard from './components/AdminDashboard';
import AnalyticsDashboard from './components/AnalyticsDashboard';
import CollectorDashboard from './components/CollectorDashboard';
import ResidentsDashboard from './components/ResidentsDashboard';
import CardNav from './components/CardNav';
import CollectorManagement from './components/CollectorManagement';

// Make components available globally
window.React = React;
window.ReactDOM = ReactDOM;
window.Welcome = Welcome;
window.AdminDashboard = AdminDashboard;
window.AnalyticsDashboard = AnalyticsDashboard;
window.CollectorDashboard = CollectorDashboard;
window.ResidentsDashboard = ResidentsDashboard;
window.CardNav = CardNav;
window.CollectorManagement = CollectorManagement;

// Mount navbar if element exists
const navbarRoot = document.getElementById('navbar-root');
if (navbarRoot) {
    const navbarRootElement = ReactDOM.createRoot(navbarRoot);
    navbarRootElement.render(
        <React.StrictMode>
            <CardNav
                user={window.user || null}
                isAuthenticated={window.isAuthenticated || false}
                routes={window.routes || {}}
            />
        </React.StrictMode>
    );
}

// Mount welcome component if element exists
const welcomeRoot = document.getElementById('welcome-root');
if (welcomeRoot) {
    const welcomeRootElement = ReactDOM.createRoot(welcomeRoot);
    welcomeRootElement.render(
        <React.StrictMode>
            <Welcome
                user={window.user || null}
                isAuthenticated={window.isAuthenticated || false}
                routes={window.routes || {}}
            />
        </React.StrictMode>
    );
}

// Mount admin dashboard component if element exists
const adminDashboardRoot = document.getElementById('admin-dashboard-root');
if (adminDashboardRoot) {
    const adminDashboardRootElement = ReactDOM.createRoot(adminDashboardRoot);
    adminDashboardRootElement.render(
        <React.StrictMode>
            <AdminDashboard
                bins={window.bins || []}
                openAlerts={window.openAlerts || []}
                binSummary={window.binSummary || {}}
                alertStats={window.alertStats || {}}
                todayCollections={window.todayCollections || 0}
                routes={window.routes || {}}
            />
        </React.StrictMode>
    );
}

// Mount analytics dashboard component if element exists
const analyticsDashboardRoot = document.getElementById('analytics-dashboard-root');
if (analyticsDashboardRoot) {
    const analyticsDashboardRootElement = ReactDOM.createRoot(analyticsDashboardRoot);
    analyticsDashboardRootElement.render(
        <React.StrictMode>
            <AnalyticsDashboard
                binSummary={window.binSummary || {}}
                alertStats={window.alertStats || {}}
                collectionEfficiency={window.collectionEfficiency || 0}
                binCollectionRates={window.binCollectionRates || []}
                routes={window.routes || {}}
            />
        </React.StrictMode>
    );
}

// Mount collector management component if element exists
const collectorManagementRoot = document.getElementById('collector-management-root');
if (collectorManagementRoot) {
    const collectorManagementRootElement = ReactDOM.createRoot(collectorManagementRoot);
    collectorManagementRootElement.render(
        <React.StrictMode>
            <CollectorManagement
                collectors={window.collectors || []}
                assignments={window.assignments || []}
                routes={window.routes || {}}
                areaNames={window.areaNames || []}
            />
        </React.StrictMode>
    );
}

// Mount residents dashboard component if element exists
const residentsDashboardRoot = document.getElementById('residents-dashboard-root');
if (residentsDashboardRoot) {
    const residentsDashboardRootElement = ReactDOM.createRoot(residentsDashboardRoot);
    residentsDashboardRootElement.render(
        <React.StrictMode>
            <ResidentsDashboard
                bins={window.bins || []}
                userAlerts={window.userAlerts || []}
                routes={window.routes || {}}
                csrfToken={window.csrfToken || ''}
            />
        </React.StrictMode>
    );
}
