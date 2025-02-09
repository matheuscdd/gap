import { createStore } from "vuex";
import userMod from "./user";
import clientMod from "./client";
import stockTypeMod from "./stockType";
import budgetMod from "./budget";
import deliveryMod from "./delivery";
import truckMod from "./truck";
import maintenanceMod from "./maintenance";
import driverMod from "./driver";

export default createStore({
    state: {
        iChoice: {}
    },

    mutations: {
        setChoice(ctx, payload) {
            ctx.iChoice = payload;
        }
    },
    modules: {
        userMod,
        clientMod,
        budgetMod,
        stockTypeMod,
        deliveryMod,
        truckMod,
        maintenanceMod,
        driverMod,
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
                "driverMod",
            ].forEach(mod => commit(`${mod}/reset`));
        }
    }
});