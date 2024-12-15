import React, { useState } from "react";
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/components/ui/accordion"
import {Trash, Grip} from 'lucide-react';
import {useSortable} from "@dnd-kit/sortable";
import {CSS} from "@dnd-kit/utilities";

function GroupAccordion(props){
    const { children, onDelete, isActive, id, expandedItem, nested } = props;
    const { attributes, listeners, setNodeRef, transform, transition } = useSortable({ id });
    const { title } = props?.Accordion

    const style = {
        transform: CSS.Transform.toString(transform),
        transition,
    };
    const [expandItem, setExpandedItem] = useState(expandedItem); // Default expanded item
    const handleCollapse = () => {
        setExpandedItem(expandItem ? "" : id); // Collapse the accordion by setting value to an empty string
    };

    return (
            <Accordion id={id} style={style} value={ expandItem } type="single" collapsible className='border border-slate-200 rounded mb-3'>
                <AccordionItem value={id}>
                    <div className={`truncate rounded flex ${ 'first' !== nested ? 'py-3 px-2' : 'p-4' } gap-2 items-center border-l-4 ${isActive ? 'border-green-600' : 'border-red-600'}`}>
                        <button
                            {...attributes} {...listeners}
                            className='border hover:border-rose-600 transition rounded items-center font-medium h-8 w-8 text-lg p-2 flex justify-between hover:no-underline '>
                            <Grip className="h-4 w-4 text-rose-600 shrink-0 transition-transform duration-200"/>
                        </button>
                        <h3 className='flex-1 text-lg '>
                            {title || 'Untitled'}
                        </h3>
                        <button
                            onClick={onDelete}
                            className='border hover:border-rose-600 transition rounded items-center font-medium h-8 w-8 text-lg p-2 flex justify-between hover:no-underline '>
                            <Trash className="h-4 w-4 text-rose-600 shrink-0 transition-transform duration-200"/>
                        </button>
                        <AccordionTrigger onClick={handleCollapse} className='border rounded h-8 w-8 text-lg p-2 flex justify-between hover:no-underline'></AccordionTrigger>
                    </div>
                    <AccordionContent className='border-t border-slate-200 p-4 hover:no-underline'>
                        {children}
                    </AccordionContent>
                </AccordionItem>
            </Accordion>
        );
}

export default GroupAccordion;