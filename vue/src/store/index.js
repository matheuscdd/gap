import { createStore } from "vuex";
import userMod from "./user";
import clientMod from "./client";
import stockTypeMod from "./stockType";
import budgetMod from "./budget";
import deliveryMod from "./delivery";

export default createStore({
    modules: {
        userMod,
        clientMod,
        budgetMod,
        stockTypeMod,
        deliveryMod,
    },
    actions: {
        clearAll({ commit }){
            [
                "userMod",
                "clientMod",
                "budgetMod",
                "stockTypeMod",
                "deliveryMod",
            ].forEach(mod => commit(`${mod}/reset`));
        }
    }
});