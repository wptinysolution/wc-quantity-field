import React, { useState, useEffect, forwardRef } from 'react';
import { useSortable } from '@dnd-kit/sortable';

export const ItemForwardRef = forwardRef((props, ref) => {
    const {
        id,
        children
    } = props;

    const {
        attributes,
        listeners,
        setNodeRef,
        transform,
        transition,
        isDragging: isItemDragging,
    } = useSortable({ id });

    // Group Title Updated
    return (
        <div
            className="rtsb-repeater-item"
            ref={
                ref
                    ? (node) => {
                        ref.current = node;
                        setNodeRef(node);
                    }
                    : setNodeRef
            }
        >
            { children }
        </div>
    );
});
