import React, { useEffect, useState } from "react";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import useStore from "@/js/backend/Utils/StateProvider";

function TheAlerts({ title, desc, children }) {
    const { setNotice, notice } = useStore();
    const [isVisible, setIsVisible] = useState(true);

    useEffect(() => {
        const timer = setTimeout(() => {
            setIsVisible(false); // Trigger slide-down animation
            setTimeout(() => {
                setNotice({
                    ...notice,
                    hasNotice: false,
                    title: null,
                    desc: null,
                });
            }, 500); // Wait for animation to complete before removing
        }, 1000);

        return () => clearTimeout(timer);
    }, [setNotice]);

    return (
        <Alert
            className={`${ notice?.positionClass || 'bottom-10' } fixed right-6 w-96 bg-white border z-10 transition-all duration-300 ease-in-out ${
                isVisible ? "translate-y-0 opacity-100" : "translate-y-20 opacity-0"
            }`}
        >
            {title && <AlertTitle className={`text-lg text-teal-700 ${desc && 'mb-3' }`}>{title}</AlertTitle>}
            {desc && <AlertDescription>{desc}</AlertDescription>}
            {children}
        </Alert>
    );
}

export default TheAlerts;
