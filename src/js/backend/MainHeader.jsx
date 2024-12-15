import React from "react";
import {
    NavigationMenu,
    NavigationMenuList
} from "@/components/ui/navigation-menu"
import MenuLink from "@/components/CustomizeComponent/MenuLink";

function MainHeader() {
    return (
        <div className="border w-full rounded bg-current">
            <NavigationMenu className="m-0 " >
                <NavigationMenuList>
                    <MenuLink
                        URL='/'
                        NavigationClass={''}
                        LinkClass={''}
                        Text='Settings'
                    />
                </NavigationMenuList>
            </NavigationMenu>
        </div>
    );
}

export default MainHeader;