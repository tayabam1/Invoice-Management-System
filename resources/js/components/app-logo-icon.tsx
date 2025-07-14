import { SVGAttributes } from 'react';

export default function AppLogoIcon(props: SVGAttributes<SVGElement>) {
    return (
        <svg {...props} viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
            {/* Invoice document background */}
            <rect 
                x="4" 
                y="3" 
                width="24" 
                height="26" 
                rx="2" 
                ry="2" 
                fill="currentColor" 
                opacity="0.1"
            />
            
            {/* Invoice document outline */}
            <rect 
                x="4" 
                y="3" 
                width="24" 
                height="26" 
                rx="2" 
                ry="2" 
                fill="none" 
                stroke="currentColor" 
                strokeWidth="1.5"
            />
            
            {/* Document header lines */}
            <rect 
                x="7" 
                y="7" 
                width="10" 
                height="1.5" 
                rx="0.75" 
                fill="currentColor" 
                opacity="0.8"
            />
            <rect 
                x="7" 
                y="10" 
                width="7" 
                height="1" 
                rx="0.5" 
                fill="currentColor" 
                opacity="0.6"
            />
            
            {/* Invoice lines */}
            <rect 
                x="7" 
                y="15" 
                width="18" 
                height="1" 
                rx="0.5" 
                fill="currentColor" 
                opacity="0.5"
            />
            <rect 
                x="7" 
                y="18" 
                width="15" 
                height="1" 
                rx="0.5" 
                fill="currentColor" 
                opacity="0.5"
            />
            <rect 
                x="7" 
                y="21" 
                width="13" 
                height="1" 
                rx="0.5" 
                fill="currentColor" 
                opacity="0.5"
            />
            
            {/* Currency symbol */}
            <circle 
                cx="22" 
                cy="8.5" 
                r="3" 
                fill="currentColor" 
                opacity="0.15"
            />
            <text 
                x="22" 
                y="11" 
                textAnchor="middle" 
                fontSize="4" 
                fontWeight="bold" 
                fill="currentColor"
            >
                $
            </text>
            
            {/* Total line with emphasis */}
            <rect 
                x="7" 
                y="24.5" 
                width="18" 
                height="1.5" 
                rx="0.75" 
                fill="currentColor" 
                opacity="0.9"
            />
        </svg>
    );
}
