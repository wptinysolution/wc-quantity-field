import React, { useState, useEffect } from 'react';
import {HashRouter, Navigate, Route, Routes} from "react-router-dom";

import TheAlerts from "@/components/CustomizeComponent/TheAlerts";
import useStore from "@/js/backend/Utils/StateProvider";
import Settings from "@/js/backend/Settings";

const App = () => {
    const {
        setNotice,
        notice,
        options
    } = useStore();

    return (
        <div className="border border-sky-500 p-1.5 rounded">
            <HashRouter>
                <Routes>
                    <Route path="/" element={<Settings/>}/>
                    <Route path="*" element={<Navigate to="/" replace/>}/>
                </Routes>
            </HashRouter>
            { notice.hasNotice ? <TheAlerts title={notice.title} desc={notice.desc}/> : null }
        </div>
    );
};
export default App;