import React from "react";
import {Button} from "@/components/ui/button";
import PageMasterComponent from "@/js/backend/PageMasterComponent";
function Page() {
    return (
        <PageMasterComponent>
            <h1> Button </h1>
            <h1> Button </h1>
            <h1> Button </h1>
            <h1> Button </h1>
            <h1> Button </h1>
            <h1> Button </h1>
            <h1> Button </h1>
            <Button className="pl-1 pr-2 pt-1 pb-1" variant="outline">Button</Button>
            <h1> Button Footer </h1>
            <h1> Button </h1>
            <h1> Button </h1>
            <h1> Button </h1>
            <h1> Button </h1>
            <h1> Button </h1>
        </PageMasterComponent>
    );
}

export default Page;