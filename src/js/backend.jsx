/**
 * Backend JS.
 *
 */

import React from 'react';
import { createRoot } from 'react-dom/client';
import App from './backend/App';

document.addEventListener('DOMContentLoaded', function (){
    const container = document.getElementById('wcqf_root');
    if ( container ){
        const root = createRoot(container);
        root.render(<App/>)
    }
});
