import './bootstrap';
import React from 'react';
import ReactDOM from 'react-dom/client';

// Import React components
import Welcome from './components/Welcome';
import AdminDashboard from './components/AdminDashboard';
import AnalyticsDashboard from './components/AnalyticsDashboard';
import CollectorDashboard from './components/CollectorDashboard';
import PublicDashboard from './components/PublicDashboard';
import CardNav from './components/CardNav';

// Make components available globally
window.React = React;
window.ReactDOM = ReactDOM;
window.Welcome = Welcome;
window.AdminDashboard = AdminDashboard;
window.AnalyticsDashboard = AnalyticsDashboard;
window.CollectorDashboard = CollectorDashboard;
window.PublicDashboard = PublicDashboard;
window.CardNav = CardNav;

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
