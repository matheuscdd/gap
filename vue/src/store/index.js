import { createStore } from "vuex";
import userMod from "./user";
import clientMod from "./client";
import stockTypeMod from "./stockType";
import budgetMod from "./budget";
import deliveryMod from "./delivery";
import truckMod from "./truck";

export default createStore({
    modules: {
        userMod,
        clientMod,
        budgetMod,
        stockTypeMod,
        deliveryMod,
        truckMod,
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
            ].forEach(mod => commit(`${mod}/reset`));
        }
    }
});