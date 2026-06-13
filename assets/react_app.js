import React from 'react';
import { createRoot } from 'react-dom/client';
import App from './components/ApodCard';

const root = createRoot(document.getElementById('react-root'));
root.render(<App />);
