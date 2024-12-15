import React, { useState, useEffect, useRef } from "react";
import PageMasterComponent from "@/js/backend/PageMasterComponent";
import GroupAccordion from "@/components/CustomizeComponent/GroupAccordion";
import InputField from "@/components/Fields/InputField";
import SwitchField from "@/components/Fields/SwitchField";
import SelectField from "@/components/Fields/SelectField";
import { DndContext, closestCenter } from "@dnd-kit/core";
import { arrayMove, SortableContext, verticalListSortingStrategy } from "@dnd-kit/sortable";
import TextareaField from "@/components/Fields/TextareaField";
import {ItemForwardRef} from "@/components/CustomizeComponent/ItemForwardRef";
import useStore from "@/js/backend/Utils/StateProvider";

/**
 * SortableItem component to make each group draggable.
 * SettingsPage Component with DnD support.
 */
function Bundle() {
    const {
        addNewField,
        setOptions,
        options,
        scrollToID
    } = useStore();

    const allGroups = options?.allGroups || [];
    const [sortableGroups, setSortableGroups] = useState(allGroups);
    const newItemRef = useRef(null);
    useEffect(() => {
        if (scrollToID && newItemRef.current) {
            newItemRef.current.scrollIntoView({ behavior: 'smooth' });
        }
    }, [sortableGroups, scrollToID]);

    useEffect(() => {
        setSortableGroups(allGroups);
    }, [allGroups]);

    const onChangeGroup = async (groupId, key, val) => {
        const groupsData = sortableGroups.map((grp) => {
            if (grp.id === groupId) {
                return { ...grp, [key]: val };
            }
            return grp;
        });
        await setOptions({
            ...options,
            allGroups: groupsData,
        });
    };

    const deleteGroup = async (groupId) => {
        const confirmDeletion = window.confirm("Are you sure you want to delete?");
        if (confirmDeletion) {
            const groupsData = sortableGroups.filter((grp) => grp.id !== groupId);
            let allFields = options?.allFields || {};
            if ( allFields[groupId] ){
                delete allFields[groupId];
            }
            await setOptions({
                ...options,
                allGroups: groupsData,
                allFields: allFields
            });
        }
    };

    const handleDragEnd = (event) => {
        const { active, over } = event;
        if (active.id !== over.id) {
            const oldIndex = sortableGroups.findIndex((item) => item.id === active.id);
            const newIndex = sortableGroups.findIndex((item) => item.id === over.id);
            const newOrder = arrayMove(sortableGroups, oldIndex, newIndex);
            setSortableGroups(newOrder);
            setOptions({
                ...options,
                allGroups: newOrder,
            });
        }
    };
    return (
        <PageMasterComponent>
            <h4 className='border border-l-4 p-4 border-l-sky-500 font-bold text-lg	mb-4'>Groups</h4>
            <DndContext collisionDetection={closestCenter} onDragEnd={handleDragEnd}>
                <SortableContext items={sortableGroups} strategy={verticalListSortingStrategy}>
                    {sortableGroups.length > 0
                        ? sortableGroups.map((item, index) => {
                            const ref = item.id === scrollToID ? newItemRef : null;
                            return (
                                <ItemForwardRef key={item.id} id={item.id} ref={ref}>
                                    <GroupAccordion
                                        key={item.id}
                                        id={item.id}
                                        Accordion={{...item}}
                                        onDelete={() => deleteGroup(item.id)}
                                        isActive={item.enable_group}
                                        expandedItem={scrollToID}
                                        nested={'first'}
                                    >
                                        <SwitchField
                                            label="Enable Bundle"
                                            desc="Bundle Enable Or disable"
                                            checked={item.enable_group}
                                            onCheckedChange={(value) => onChangeGroup(item.id, "enable_group", value)}
                                        />
                                        <InputField
                                            label="Bundle Name"
                                            desc="Bundle Name description"
                                            defaultValue={item.title}
                                            onChange={(e) => onChangeGroup(item.id, "title", e.target.value)}
                                        />
                                        <TextareaField
                                            label="Bundle Description"
                                            desc="Bundle description"
                                            defaultValue={item.description}
                                            onChange={(e) => onChangeGroup(item.id, "description", e.target.value)}
                                        />
                                        <SelectField
                                            label="Bundle By"
                                            desc="Select your desired field type."
                                            options={{
                                                'category': 'Category',
                                                'product': 'Product',
                                            }}
                                            defaultValue={item?.groupBy || 'product'}
                                            onValueChange={(value) => onChangeGroup(item.id, "groupBy", value)}
                                        />
                                        {
                                            'category' === item?.groupBy ?
                                                <SelectField
                                                    label="Bundle By Category"
                                                    desc="Choose Category to include. Leave blank to apply in all category.."
                                                    options={{
                                                        'cat-1': 'Cat 1',
                                                        'cat-2': 'Cat 2',
                                                    }}
                                                    defaultValue={item?.groupByCat || 'cat-1'}
                                                    onValueChange={(value) => onChangeGroup(item.id, "groupByCat", value)}
                                                /> : null
                                        }
                                        {
                                            'product' === item?.groupBy ?
                                                <SelectField
                                                    label="Bundle By Product"
                                                    desc="Choose products to include. Leave blank to apply in all product.."
                                                    options={{
                                                        'product-1': 'Product 1',
                                                        'product-2': 'Product 2',
                                                    }}
                                                    defaultValue={item?.groupByProduct || 'product-1'}
                                                    onValueChange={(value) => onChangeGroup(item.id, "groupByProduct", value)}
                                                /> : null
                                        }
                                        <SwitchField
                                           label="Display Bundle Name"
                                            desc="Switch on to show group name in the frontend."
                                            checked={item?.displayGroupName}
                                            onCheckedChange={(value) => onChangeGroup(item.id, "displayGroupName", value)}
                                        />
                                        <SwitchField
                                            label="Display Bundle Description"
                                            desc="Switch on to show Bundle description in the frontend."
                                            checked={item.displayGroupDesc}
                                            onCheckedChange={(value) => onChangeGroup(item.id, "displayGroupDesc", value)}
                                        />

                                    </GroupAccordion>
                                </ItemForwardRef>)
                        }) :
                        <h3 className='font-bold text-lg p-4 border text-center'> No Bundle Added </h3>
                    }
                </SortableContext>
            </DndContext>
        </PageMasterComponent>
    );
}

export default Bundle;
