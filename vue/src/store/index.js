import { createStore } from "vuex";
import userMod from "./user";
import clientMod from "./client";
import stockTypeMod from "./stockType";
import budgetMod from "./budget";
import deliveryMod from "./delivery";
import truckMod from "./truck";
import maintenanceMod from "./maintenance";

export default createStore({
    modules: {
        userMod,
        clientMod,
        budgetMod,
        stockTypeMod,
        deliveryMod,
        truckMod,
        maintenanceMod,
    },
    actions: {
        clearAll({ commit }){
            [
                "userMod",
                "clientMod",
                "budgetMod",
                "stockTypeMod",
                "deliveryMod",
                "truckMod",
                "maintenanceMod",
            ].forEach(mod => commit(`${mod}/reset`));
        }
    }
});